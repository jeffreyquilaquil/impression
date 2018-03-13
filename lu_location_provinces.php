<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Location Listings', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$rid = requestInteger('rid', 'location: '.WEBSITE_URL.'lu_location_regions.php');

loadDBValues("psi_regions", "SELECT * FROM psi_regions WHERE region_id = ".$rid);

$q_key = 'q_locations_provinces';

$q = '';

if (!postEmpty('search')){
    if ($_POST['search'] == 'Search'){
        if (!postEmpty('q')){
            $_SESSION[$q_key] = safeString($_POST['q']);
        } else {
            $_SESSION[$q_key] = '';
        }

    } else if ($_POST['search'] == 'Show All'){
        $_SESSION[$q_key] = '';
    }
}

if (!sessionEmpty($q_key)){
    $q = $_SESSION[$q_key];
}

$sql = "SELECT * FROM psi_provinces";
$where = "(region_id = $rid)";

if (strlen($q) > 0){
    if (stlen($where) > 0){
        $where .= " AND ";
    }
    $where .= "(province_name like '%$q%')";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY province_id ASC';
//echo $sql;
$rows = mysqli_query($GLOBALS['cn'], $sql);

$page_title = "Provinces";
$row_title = "Province";
if ($region_code == 'NCR'){
    $page_title = 'NCR Districts';
    $row_title = "District";
}

page_header("Location Listings - $page_title");
if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}


?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Location Listings - <?php echo $page_title; ?>
            <br>
            <span class="text-primary">
            <?php echo $GLOBALS['region_code'].' ('.$GLOBALS['region_name'].')'; ?>
            </span>

         </h3>
        <div class="pull-right">
            <?php
            if (can_access('Location Listings', 'add')){
            ?>
            <a class="btn btn-primary btn-sm" href="lu_location_provinces_form.php?op=0&amp;id=0&amp;rid=<?php echo $rid; ?>" title="Add Province"><span class="fa fa-plus"></span> Add Province</a>
            <a class="btn btn-primary btn-sm" href="lu_location_regions.php" title="Back to Regions"> Back</a>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="lu_location_provinces.php?rid=<?php echo $rid; ?>" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <input class="form-control input-sm" placeholder="Provinces ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
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
                    <th><?php echo $row_title; ?></th>
                </tr>
            </thead> 
            <tbody>
            <?php 
            $ctr = 0;
        	if ($rows) {
                while($row = mysqli_fetch_array($rows)) {

        			$action = '';

                    if (can_access('Location Listings', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'lu_location_cities.php?rid='.$rid.'&amp;pid='.$row['province_id'].'" title="View Details"><span class="fa fa-folder-open"></span></a> ';
                    }

                    if (can_access('Location Listings', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'lu_location_provinces_form.php?op=1&amp;rid='.$rid.'&amp;id='.$row['province_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }

                    if (can_access('Location Listings', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'lu_location_provinces_delete.php?op=2&amp;rid='.$rid.'&amp;id='.$row['province_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>  ';
                    }
                    $ctr++;

?>                <tr>
                    <td class="nowrap text-left"><?php echo $action; ?></td>
                    <td class="nowrap text-center"><?php echo $ctr; ?></td>
                    <td class="nowrap" width="100%"><a class="rowlink" href="<?php echo WEBSITE_URL.'lu_location_cities.php?rid='.$rid.'&amp;pid='.$row['province_id']; ?>" title="View Details"><?php echo $row['province_name']; ?></a></td>
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
?>