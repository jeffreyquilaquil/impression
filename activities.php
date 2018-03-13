<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Media Activities', 'view')){
    redirect(WEBSITE_URL.'index.php');
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

$q_key = 'q_activities';
$q1_key = 'q1_activities';
$q2_key = 'q2_activities';
$q3_key = 'q3_activities';

$q = '';
$q1 = 0;
$q2 = date('Y');
$q3 = getQuarterIndex();

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
        $_SESSION[$q1_key] = 0;
        $_SESSION[$q2_key] = date('Y');
        $_SESSION[$q3_key] = getQuarterIndex();
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


$sql = "SELECT * FROM vwpsi_activities";
$where = "";
if ($GLOBALS['ad_ug_is_admin'] == 0){
    $where .= "(region_id = $GLOBALS[ad_u_region_id])";
}

if (strlen($q) > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(activity_title like '%$q%')";
}

if ($q1 > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(activity_type_id = $q1)";
}

if ($q2 > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    $where .= "(activity_date_accomplished_yr = $q2)";
}

if ($q3 > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    
    $where .= "(activity_date_accomplished_yr = $q2) AND ";
    $where .= '((activity_date_accomplished_mo >= '.$quarter_start[$q3].') AND (activity_date_accomplished_mo <= '.$quarter_end[$q3].'))';
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY activity_date_accomplished DESC';

$sql;
$rows = mysqli_query($GLOBALS['cn'], $sql);
$sel_acttypes = getOptions('psi_activity_types', 'activity_type_name', 'activity_type_id', $q1, 'All');
$sel_years = getImpYearOptions($q2);
$sel_quarters = getOptions('psi_quarters', 'quarter_name', 'quarter_id', $q3, 'ALL');

page_header('PARCU Aactivities', 2);
if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Media Activities</h3>
        <div class="pull-right">
            <?php
            if (can_access('Media Activities', 'add')){
            ?>
            <a class="btn btn-primary btn-sm" href="activities_form.php?op=0&amp;id=0" title="Add Activity"><span class="fa fa-plus"></span> Add Activity</a>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="activities.php" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Categories</span>
                    <select class="form-control input-sm" id="q1" name="q1">
                    <?php echo $sel_acttypes; ?>
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
                    <input class="form-control input-sm" placeholder="Activity Title ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
                    <span class="input-group-btn">
                        <input class="btn btn-primary btn-sm" type="submit" name="search" id="search" value="Search">
                    </span>
                </div>
            </div>
        </form>
 </div>
    <div class="table-responsive">
        <table id="grid_table" class="table table-bordered table-striped table-hover table-condensed tablesorter">
            <thead>
            	<tr>
                    <th>&nbsp;</th>
                    <th class="text-center">#</th>
                    <th>Activity</th>
                    <th class="text-center">Category</th>
                    <th class="text-center">Date Accomplished</th>
                    <th class="nowrap text-center">No. of Articles</th>
                    <th class="nowrap text-center">No. of Feminine</th>
                    <th class="nowrap text-center">No. of Masculine</th>
                    <th class="nowrap text-center">No. of Seniors</th>
                    <th class="nowrap text-center">No. of PWD</th>
                    <th class="nowrap text-center">No. of Participants</th>
                    <th class="text-center">CSF Rating</th>
                </tr>
            </thead> 
            <tbody>
            <?php
            $articles = 0;
            $female = 0;
            $male = 0;
            $pwd = 0;
            $senior = 0;
            $total = 0;
            $ctr = 0;
        	if ($rows) {
                while($row = mysqli_fetch_array($rows)) {
                    $ctr++;
        			$action				= '';

                    if (can_access('Media Activities', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'activities_view.php?pid='.$row['activity_id'].'" title="Activity Details"><span class="fa fa-folder-open"></span></a>  ';
                    }
                    if (can_access('Media Activities', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'activities_form.php?op=1&amp;id='.$row['activity_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }
                    if (can_access('Media Activities', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'activities_delete.php?op=2&amp;id='.$row['activity_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>';
                    }

                    $female += $row['activity_no_female'];
                    $male += $row['activity_no_male'];
                    $pwd += $row['activity_no_pwd'];
                    $senior += $row['activity_no_senior'];

                    $sum = $row['activity_no_male'] + $row['activity_no_female'];
                    $total += $sum;
                    $articles += $row['activity_no_articles'];
?>                
                <tr>
                    <td class="nowrap text-left"><?php echo $action; ?></td>
                    <td class="nowrap text-center"><?php echo $ctr; ?></td>
                    <td class="nowrap"><?php echo $row['activity_title']; ?></td>
                    <td class="text-center"><?php echo $row['activity_type_name']; ?></td>
                    <td class="text-center"><?php echo zeroDate($row['activity_date_accomplished']); ?></td>
                    <td class="text-center"><?php echo zeroNumber($row['activity_no_articles'].'', 0); ?></td>
                    <td class="text-center"><?php echo zeroNumber($row['activity_no_female'].'', 0); ?></td>
                    <td class="text-center"><?php echo zeroNumber($row['activity_no_male'].'', 0); ?></td>
                    <td class="text-center"><?php echo zeroNumber($row['activity_no_pwd'].'', 0); ?></td>
                    <td class="text-center"><?php echo zeroNumber($row['activity_no_senior'].'', 0); ?></td>
                    <td class="text-center"><?php echo zeroNumber($sum, 0); ?></td>
                    <td class="text-center"><?php echo zeroNumber($row['activity_csf'].''); ?></td>
                  </tr>
<?php
        		}
                mysqli_free_result($rows);
        	}
?>         
            </tbody>
        </table>
    </div>

    <div class="panel-footer">
        <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th class="text-center">Total No. of Releases</th>
                <th class="text-center">Total No. of Articles</th>
                <th class="text-center">Total No. of Feminine</th>
                <th class="text-center">Total No. of Masculine</th>
                <th class="text-center">Total No. of PWD</th>
                <th class="text-center">Total No. of Senior</th>
                <th class="text-center">Total No. of Participants</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center">
                    <?php echo $ctr; ?>
                </td>
                <td class="text-center">
                    <?php echo $articles; ?>
                </td>
                <td class="text-center">
                    <?php echo $female; ?>
                </td>
                <td class="text-center">
                    <?php echo $male; ?>
                </td>
                <td class="text-center">
                    <?php echo $pwd; ?>
                </td>
                <td class="text-center">
                    <?php echo $senior; ?>
                </td>
                <td class="text-center">
                    <?php echo $total; ?>
                </td>
            </tr>
            </tbody>
        </table>
        </div>
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
</div>


<script type="text/javascript">
var _tsOptions = {
    headers:{
        0: { sorter: false}
        }
    };
</script>
<?php
    page_footer();
    deleteFormCache();

    function getImpYearOptions($value){
        $s = '';
        $sql = "SELECT DISTINCT activity_date_accomplished_yr FROM vwpsi_activities ORDER BY activity_date_accomplished_yr DESC";
        $res = mysqli_query($GLOBALS['cn'], $sql);

        if (!$res) return $s;
        $found = false;
        while ($row = mysqli_fetch_array($res)){
            if (intval($row['activity_date_accomplished_yr']) == intval($value) ){
                $s .= '<option value="'.$row['activity_date_accomplished_yr'].'" selected="selected">'.$row['activity_date_accomplished_yr'].'</option>';
                $found = true;
            } else {
                $s .= '<option value="'.$row['activity_date_accomplished_yr'].'">'.$row['activity_date_accomplished_yr'].'</option>';
            }
        }
        mysqli_free_result($res);

        if (!$found){
            $s = '<option value="'.$value.'" select="selected">'.$value.'</option>'.$s;
        }
        return $s;
    }

?>