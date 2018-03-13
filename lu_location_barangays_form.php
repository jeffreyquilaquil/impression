<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_location_regions.php');
$rid = requestInteger('rid', 'location: '.WEBSITE_URL.'lu_location_regions.php');
$pid = requestInteger('pid', 'location: '.WEBSITE_URL."lu_location_provinces.php?rid=$rid");
$cid = requestInteger('cid', 'location: '.WEBSITE_URL."lu_location_cities.php?rid=$rid&pid=$pid");
$id = requestInteger('id', 'location: '.WEBSITE_URL."lu_location_barangays.php?rid=$rid&pid=$pid&cid=$cid");

loadDBValues("psi_regions", "SELECT * FROM psi_regions WHERE region_id = $rid");
loadDBValues("psi_provinces", "SELECT * FROM psi_provinces WHERE province_id = $pid");
loadDBValues("psi_cities", "SELECT * FROM psi_cities WHERE city_id = $cid");


if ($op == 1){
    if (!can_access('Location Listings', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Location Listings', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_barangays", "SELECT * FROM psi_barangays WHERE barangay_id = $id");
} else {
    initFormValues('psi_barangays');
    $GLOBALS['region_id'] = $rid;
    $GLOBALS['province_id'] = $pid;
    $GLOBALS['city_id'] = $cid;
}

loadFormCache('psi_barangays');


$sel_cities = getOptions('psi_cities', 'city_name', 'city_id', $GLOBALS['city_id'], '', "WHERE province_id = $GLOBALS[province_id] ORDER BY city_name ASC");

$page_title = "Location Listings - Barangays ($opstr)";
page_header($page_title);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">
            <?php echo $page_title; ?>
            <br>
            <span class="text-primary">
            <?php echo $GLOBALS['city_name']; ?>
            </span>
            <small>
            <br>
            <?php echo $GLOBALS['province_name']; ?>
            <br>
            <?php echo $GLOBALS['region_code'].' ('.$GLOBALS['region_name'].')'; ?>
            </small>
            
        </h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="lu_location_barangays.php?id=$id&amp;rid=<?php echo $rid; ?>&amp;pid=<?php echo $pid; ?>&amp;cid=<?php echo $cid; ?>" title="Go Back"><span class="fa fa-arrow-circle-left"></span> Back</a>        
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="lu_location_barangays_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;rid=<?php echo $rid; ?>&amp;pid=<?php echo $pid; ?>&amp;cid=<?php echo $cid; ?>" accept-charset="UTF-8" class="form" role="form">

            <div class="form-group">
                <label for="barangay_name" class="control-label">Name *</label>
                <input class="form-control input-sm" placeholder="Name" maxlength="255" required="required" name="barangay_name" id="barangay_name" type="text" value="<?php echo $GLOBALS['barangay_name']; ?>">
            </div>

            <div class="form-group">
                <label for="city_id" class="control-label">City </label>
                <select class="form-control" id="city_id" name="city_id"><?php echo $sel_cities; ?></select>
            </div>

            <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
            <input type="hidden" name="region_id" value="<?php echo $GLOBALS['region_id']; ?>">
            <input type="hidden" name="province_id" value="<?php echo $GLOBALS['province_id']; ?>">
            <input type="hidden" name="city_id" value="<?php echo $GLOBALS['city_id']; ?>">
            <input type="hidden" name="barangay_id" value="<?php echo $GLOBALS['barangay_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
page_footer();
?>