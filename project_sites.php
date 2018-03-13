<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Project Sites', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

if (!is_project_type($pid, 8)){
    redirect(WEBSITE_URL."projects_view.php?pid=$pid");
}

$quarter_start[] = array();
$quarter_end[] = array();

$quarter_start[1] = 1;
$quarter_end[1] = 3;

$quarter_start[2] = 4;
$quarter_end[2] = 6;

$quarter_start[3] = 7;
$quarter_end[3] = 9;

$quarter_start[4] = 10;
$quarter_end[4] = 12;

$q_key = 'q_sites';
$q1_key = 'q1_sites';
$q2_key = 'q2_sites';
$q3_key = 'q3_sites';

$q = '';
$q1 = 0; 
$q2 = 0;
$q3 = 0;

if (!postEmpty('search')){
    if ($_POST['search'] == 'Search'){
        if (!postEmpty('q')){
            $_SESSION[$q_key] = safeString($_POST['q']);
        } else {
            $_SESSION[$q_key] = '';
        }
        if (!postEmpty('q1')){
            $_SESSION[$q1_key] = safeString($_POST['q1']);
        } else {
            $_SESSION[$q1_key] = -1;
        }

        if (!postEmpty('q2')){
            $_SESSION[$q2_key] = safeString($_POST['q2']);
        } else {
            $_SESSION[$q2_key] = date('Y');
        }

        if (!postEmpty('q3')){
            $_SESSION[$q3_key] = safeString($_POST['q3']);
        } else {
            $_SESSION[$q3_key] = getQuarterIndex();
        }

    } else if ($_POST['search'] == 'Show All'){
        $_SESSION[$q_key] = '';
        $_SESSION[$q1_key] = -1;
        $_SESSION[$q2_key] = 0;
        $_SESSION[$q3_key] = 0;
    }
}

if (!sessionEmpty($q_key)){
    $q = $_SESSION[$q_key];
}

if (!sessionEmpty($q1_key)){
    $q1 = intval($_SESSION[$q1_key]);
}

if (!sessionEmpty($q2_key)){
    $q2 = intval($_SESSION[$q2_key]);
}

if (!sessionEmpty($q3_key)){
    $q3 = intval($_SESSION[$q3_key]);
}

$sql = "SELECT * FROM vwpsi_project_sites";
$where = "(prj_id = $pid)";

if (strlen($q) > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    $where .= "(prj_site_remarks like '%$q%')";
}

if ($q2 > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    $where .= "(prj_site_yr = $q2)";
}

if ($q3 > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    
    $where .= "((prj_site_yr = $q2) AND ((prj_site_mo >= $quarter_start[$q3]) AND (prj_site_mo <= $quarter_end[$q3])))";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY prj_site_yr DESC, prj_site_mo DESC';

//echo $sql;

$rows = mysqli_query($GLOBALS['cn'], $sql);

$sel_types = getOptions('psi_equipment_brands', 'brand_name', 'brand_id', $q1, 'All');
$sel_years = getImpYearOptions($q2);
$sel_quarters = getOptions('psi_quarters', 'quarter_name', 'quarter_id', $q3, 'ALL');


$page_title = 'Project Sites';
page_header($page_title, 1);

if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="pull-right">
                <?php
                if (can_access('Project Sites', 'add')){
                ?>
                <a class="btn btn-primary btn-sm" href="project_sites_form.php?op=0&amp;id=0&amp;pid=<?php echo $pid;?>" title="Add Site"><span class="fa fa-plus"></span> Add Site</a>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="panel-body">

        <div class="map-sites">
        </div>

        <form method="POST" action="project_sites.php?pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Equipment</span>
                    <select class="form-control input-sm" id="q1" name="q1">
                    <?php echo $sel_types; ?>
                    </select>
                </div>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Year</span>
                    <select class="form-control input-sm" id="q2" name="q2">
                    <?php echo $sel_years; ?>
                    </select>
                </div>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Quarter</span>
                    <select class="form-control input-sm" id="q3" name="q3">
                    <?php echo $sel_quarters; ?>
                    </select>
                </div>

                <div class="input-group input-group-sm">
                    <input class="form-control input-sm" placeholder="Keywords ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
                    <span class="input-group-btn">
                        <input class="btn btn-primary btn-sm" type="submit" name="search" id="search" value="Search">
                    </span>
                </div>
            </div>
        </form>
        <!--
        <div id="map-sites" class="form-group map-sites">
        </div>
    -->
    </div>
    <?php

    $res_count = 0;
    $_location = '';

    $ctr = 0;

    if ($rows) {
        if (mysqli_num_rows($rows) > 0){
    ?>

    <div class="table-responsive">
        <table id="grid_table" class="table table-bordered table-striped table-hover table-condensed tablesorter">
            <thead>
            	<tr>
                    <th class="hidden-print">&nbsp;</th>
                    <th>#</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Equipment</th>
                    <th class="text-center">Province</th>
                    <th class="text-center">City/Municipality</th>
                    <th class="text-center">Barangay</th>
                    <th class="text-center">Longitude</th>
                    <th class="text-center">Latitude</th>
                    <th class="text-center">Elevation</th>
                    <th class="text-center">Remarks</th>
                    <th>Encoded</th>
                    <th>Last Updated</th>
                </tr>
            </thead> 
            <tbody>
            <?php 


        	if ($rows) {
                while($row = mysqli_fetch_array($rows)) {

                    if (strlen($_location) > 0){
                        $_location .= ",\n";
                    }

                    $sInfo = $row['brand_name'].'<br>'.zeroDate($row['prj_site_date'], 1, 'm/d/Y').'<br>'.trim($row['prj_site_latitude']).", ".trim($row['prj_site_longitude']).", ".trim($row['prj_site_elevation']).'<br>'.$row['prj_site_address'].'<br>'.$row['barangay_name'].', '.$row['city_name'].', '.$row['province_name'];
                    //$_location .= "[".$res_count.", \"".$sInfo."\", ".trim($row['prj_site_latitude']).", ".trim($row['prj_site_longitude'])."]";
                    $_location .= "[".$res_count.", \"yahoo\", ".trim($row['prj_site_latitude']).", ".trim($row['prj_site_longitude'])."]";
                    $res_count++;

                    $ctr++;
                    $action = '';

                    /*
                    if (can_access('Project Sites', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'project_sites_view.php?pid='.$pid.'&amp;did='.$row['prj_site_id'].'" title="View Details"><span class="fa fa-folder-open"></span></a>  ';
                    }
                    */

                    if (can_access('Project Sites', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'project_sites_form.php?op=1&amp;pid='.$pid.'&amp;id='.$row['prj_site_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }

                    if (can_access('Project Sites', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'project_sites_delete.php?op=2&amp;pid='.$pid.'&amp;id='.$row['prj_site_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>';
                    }

            ?>
                <tr>
                    <td class="nowrap text-left"><?php echo $action; ?></td>
                    <td class="text-center"><?php echo $ctr; ?></td>
                    <td class="nowrap text-center"><?php echo zeroDate($row['prj_site_date'], 1, 'm/d/Y'); ?></td>
                    <td class="nowrap"><?php echo $row['brand_name']; ?></td>

                    <td class="text-center"><?php echo $row['province_name']; ?></td>
                    <td class="text-center"><?php echo $row['city_name']; ?></td>
                    <td class="text-center"><?php echo $row['barangay_name']; ?></td>

                    <td class="text-center"><?php echo $row['prj_site_latitude']; ?></td>
                    <td class="text-center"><?php echo $row['prj_site_longitude']; ?></td>
                    <td class="text-center"><?php echo $row['prj_site_elevation']; ?></td>
                    <td class="text-center"><?php echo nl2br($row['prj_site_remarks']).''; ?></td>
                    <td class="nowrap"><?php echo zeroDateTime($row['date_encoded']); ?><br>by <?php echo $row['encoder']; ?></td>
                    <td class="nowrap"><?php echo zeroDateTime($row['last_updated']); ?><br>by <?php echo $row['updater']; ?></td>
                  </tr><?php
        		}
                mysqli_free_result($rows);
        	}
?>         
            </tbody>
        </table>
    </div>

    <div class="panel-footer">
        <div id="grid_pager">
            <form class="form-inline" style="text-align:center;" role="form">
                <div class="form-group form-group-sm">
                    <a class="btn btn-primary btn-sm first" title="First"><span class="glyphicon glyphicon-step-backward"></span></a>
                    <a class="btn btn-primary btn-sm prev" title="Previous"><span class="glyphicon glyphicon-backward"></span></a>
                    <input type="text" class="form-control input-sm text-center pagedisplay" disabled>
                    <a class="btn btn-primary btn-sm next" title="Next"><span class="glyphicon glyphicon-forward"></span></a>
                    <a class="btn btn-primary btn-sm last" title="Last"><span class="glyphicon glyphicon-step-forward"></span></a>
                    <select class="form-control input-sm pagesize" title="No. of items per page.">
                        
                        <option selected="selected" value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </form>
        </div>    
    </div>
<?php
    }
}

?>

</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTy43uj8Qf1aizvTXKYY9Ip-7WS3u9iAM" type="text/javascript"></script>
<script>
    var _latitude = <?php echo DEF_LATITUDE; ?>;
    var _longitude = <?php echo DEF_LONGITUDE; ?>;

    var _tsOptions = {
        headers:{
            0: { sorter: false}
        }
    };

    var _locations = [
        <?php echo $_location; ?>
    ];

</script>
<?php
    page_footer();
    deleteFormCache();

  function getImpYearOptions($value){
        $s = '';
        $sql = "SELECT DISTINCT prj_site_yr FROM vwpsi_project_sites ORDER BY prj_site_yr DESC";
        $res = mysqli_query($GLOBALS['cn'], $sql);

        if (!$res) return $s;
        $found = false;
        while ($row = mysqli_fetch_array($res)){
            if (intval($row['prj_site_yr']) == intval($value) ){
                $s .= '<option value="'.$row['prj_site_yr'].'" selected="selected">'.$row['prj_site_yr'].'</option>';
                $found = true;
            } else {
                $s .= '<option value="'.$row['prj_site_yr'].'">'.$row['prj_site_yr'].'</option>';
            }
        }
        mysqli_free_result($res);

        if (!$found){
            $s = '<option value="0" select="selected">All</option>'.$s;
        } else {
            $s = '<option value="0">All</option>'.$s;
        }
        return $s;
    }

  ?>