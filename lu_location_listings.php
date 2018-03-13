<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Location Listings', 'view')){
    redirect(WEBSITE_URL.'index.php');
}


$q_key = 'q_locations';
$rid_key = 'rid_locations';
$pid_key = 'pid_locations';
$cid_key = 'cid_locations';
$bid_key = 'bid_locations';
$did_key = 'did_locations';

$q = '';
$rid = 0;
$pid = 0;
$cid = 0;
$bid = 0;
$did = 0;

if (!postEmpty('search')){
    if ($_POST['search'] == 'Search'){
        if (!postEmpty('q')){
            $_SESSION[$q_key] = safeString($_POST['q']);
        } else {
            $_SESSION[$q_key] = '';
        }

        if (!postEmpty('rid')){
            $_SESSION[$rid_key] = safeString($_POST['rid']);
        } else {
            $_SESSION[$rid_key] = 0;
        }

        if (!postEmpty('pid')){
            $_SESSION[$pid_key] = safeString($_POST['pid']);
        } else {
            $_SESSION[$pid_key] = 0;
        }

        if (!postEmpty('cid')){
            $_SESSION[$cid_key] = safeString($_POST['cid']);
        } else {
            $_SESSION[$cid_key] = 0;
        }

        if (!postEmpty('bid')){
            $_SESSION[$bid_key] = safeString($_POST['bid']);
        } else {
            $_SESSION[$bid_key] = 0;
        }

        if (!postEmpty('did')){
            $_SESSION[$did_key] = safeString($_POST['did']);
        } else {
            $_SESSION[$did_key] = 0;
        }

    } else if ($_POST['search'] == 'Show All'){
        $_SESSION[$q_key] = '';
        $_SESSION[$rid_key] = 0;
        $_SESSION[$pid_key] = 0;
        $_SESSION[$cid_key] = 0;
        $_SESSION[$bid_key] = 0;
        $_SESSION[$did_key] = 0;
    }
}

if (!sessionEmpty($q_key)){
    $q = $_SESSION[$q_key];
}

if (!sessionEmpty($rid_key)){
    $rid = intval($_SESSION[$rid_key]);
}

if (!sessionEmpty($pid_key)){
    $pid = intval($_SESSION[$pid_key]);
}

if (!sessionEmpty($cid_key)){
    $cid = intval($_SESSION[$cid_key]);
}

if (!sessionEmpty($bid_key)){
    $bid = intval($_SESSION[$bid_key]);
}

if (!sessionEmpty($did_key)){
    $did = intval($_SESSION[$did_key]);
}

$sql = "SELECT * FROM vwpsi_locations";
$where = '';

if (strlen($q) > 0){
    $where .= "((barangay_name like '%$q%') OR (city_name like '%$q%') OR (province_name like '%$q%') OR (region_name like '%$q%') OR (district_name like '%$q%'))";
}

if ($rid > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(region_id = $rid)";
}

if ($pid > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(province_id = $pid)";
}


if ($cid > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(city_id = $cid)";
}


if ($bid > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(barangay_id = $bid)";
}


if ($did > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(district_id = $did)";
}


if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY barangay_name ASC, district_name ASC, city_name ASC, province_name ASC, region_name ASC';

$rows = mysqli_query($GLOBALS['cn'], $sql);
$sel_regions = getOptions('psi_regions', 'region_name', 'region_id', $rid, 'All', 'ORDER BY region_name ASC');
$sel_provinces = getOptions('psi_provinces', 'province_name', 'province_id', $pid, 'All', 'ORDER BY province_name ASC');
$sel_cities = getOptions('psi_cities', 'city_name', 'city_id', $cid, 'All', 'ORDER BY city_name ASC');
$sel_barangays = getOptions('psi_barangays', 'barangay_name', 'barangay_id', $bid, 'All', 'ORDER BY barangay_name ASC');
$sel_districts = getOptions('psi_districts', 'district_name', 'district_id', $did, 'All', 'ORDER BY district_name ASC');

page_header('Location Listings');
if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Location Listings</h3>
        <div class="pull-right">
            <?php
            if (can_access('Location Listings', 'add')){
            ?>
            <a class="btn btn-primary btn-sm" href="users_form.php?op=0&amp;id=0" title="Add User"><span class="fa fa-plus"></span> Add User</a>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="lu_location_listings.php" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Region</span>
                    <select class="form-control input-sm" id="rid" name="rid">
                    <?php echo $sel_regions; ?>
                    </select>
                </div>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Provinces</span>
                    <select class="form-control input-sm" id="pid" name="pid">
                    <?php echo $sel_provinces; ?>
                    </select>
                </div>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">City</span>
                    <select class="form-control input-sm" id="cid" name="cid">
                    <?php echo $sel_cities; ?>
                    </select>
                </div>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">District</span>
                    <select class="form-control input-sm" id="did" name="did">
                    <?php echo $sel_districts; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <input class="form-control input-sm" placeholder="Location Listings ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
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
                    <th>District</th>
                    <th>City/Municipality</th>
                    <th>Province/Not Province</th>
                    <th>Region</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
            $ctr = 0;
        	if ($rows) {
                while($row = mysqli_fetch_array($rows)) {

        			$action = '';
                    if (can_access('Location Listings', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'users_form.php?op=1&amp;id='.$row['barangay_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }

                    if (can_access('Location Listings', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'users_delete.php?op=2&amp;id='.$row['barangay_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>  ';
                    }
                    $ctr++;

?>                <tr>
                    <td class="nowrap text-center"><?php echo $action; ?></td>
                    <td class="nowrap text-center"><?php echo $ctr; ?></td>
                    <td class="nowrap"><?php echo $row['barangay_name']; ?></td>
                    <td class="nowrap"><?php echo $row['district_name']; ?></td>
                    <td class="nowrap"><?php echo $row['city_name']; ?></td>
                    <td class="nowrap"><?php echo $row['province_name']; ?></td>
                    <td class="nowrap"><?php echo $row['region_name']; ?></td>
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