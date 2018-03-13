<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Location Listings', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$rid = requestInteger('rid', 'location: '.WEBSITE_URL.'lu_location_regions.php');
loadDBValues("psi_regions", "SELECT * FROM psi_regions WHERE region_id = $rid");
$pid = requestInteger('pid', 'location: '.WEBSITE_URL."lu_location_regions.php?rid=$rid");
loadDBValues("psi_provinces", "SELECT * FROM psi_provinces WHERE province_id = $pid");
$cid = requestInteger('cid', 'location: '.WEBSITE_URL."lu_location_barangays.php?rid=$rid&pid=$pid");
loadDBValues("psi_cities", "SELECT * FROM psi_cities WHERE city_id = $cid");

$q_key = 'q_locations_cities';

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

$sql = "SELECT * FROM psi_barangays";
$where = "(city_id = $cid)";

if (strlen($q) > 0){
    if (stlen($where) > 0){
        $where .= " AND ";
    }
    $where .= "(barangay_name like '%$q%')";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY barangay_name ASC';
//echo $sql;
$rows = mysqli_query($GLOBALS['cn'], $sql);

page_header('Location Listings - Barangays');
if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Location Listings - Barangays
            <br>
            <span class="text-primary">
            <?php echo $GLOBALS['city_name']; ?>
            </span>
            <small>
            <br>
            <?php echo $GLOBALS['province_name']; ?>
            <br>
            <?php echo $GLOBALS['region_code'].' ('.$GLOBALS['region_name'].')'; ?>
            <br>
            </small>
         </h3>
        <div class="pull-right">
            <?php
            if (can_access('Location Listings', 'add')){
            ?>
            <a class="btn btn-primary btn-sm" href="lu_location_barangays_form.php?rid=<?php echo $rid;?>&amp;pid=<?php echo $pid;?>&amp;cid=<?php echo $cid;?>&amp;op=0&amp;id=0" title="Add City/Municipality"><span class="fa fa-plus"></span> Add Barangay</a>
            <a class="btn btn-primary btn-sm" href="lu_location_cities.php?rid=<?php echo $rid;?>&amp;pid=<?php echo $pid;?>&amp;cid=<?php echo $cid;?>" title="Back to Cities"> Back</a>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="lu_location_barangays.php?rid=<?php echo $rid; ?>&amp;pid=<?php echo $pid; ?>&amp;cid=<?php echo $cid; ?>" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <input class="form-control input-sm" placeholder="Barangay ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
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
                    <th>Barangay</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
            $ctr = 0;
        	if ($rows) {
                while($row = mysqli_fetch_array($rows)) {

        			$action = '';

                    if (can_access('Location Listings', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'lu_location_barangays_form.php?op=1&amp;rid='.$rid.'&amp;pid='.$pid.'&amp;cid='.$cid.'&amp;id='.$row['barangay_id'].'" title="Edit"><span class="fa fa-pencil"></span></a> ';
                    }

                    if (can_access('Location Listings', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'lu_location_barangays_delete.php?op=2&amp;rid='.$rid.'&amp;pid='.$pid.'&amp;cid='.$cid.'&amp;id='.$row['barangay_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>  ';
                    }
                    $ctr++;

?>                <tr>
                    <td class="nowrap text-left"><?php echo $action; ?></td>
                    <td class="nowrap text-center"><?php echo $ctr; ?></td>
                    <td class="nowrap" width="100%"><?php echo $row['barangay_name']; ?></td>
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