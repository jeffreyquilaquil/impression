<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_location_regions.php');
$rid = requestInteger('rid', 'location: '.WEBSITE_URL.'lu_location_regions.php');
$pid = requestInteger('pid', 'location: '.WEBSITE_URL."lu_location_provinces.php?rid=$rid");
$id = requestInteger('id', 'location: '.WEBSITE_URL."lu_location_cities.php?rid=$rid&pid=$pid");

loadDBValues("psi_regions", "SELECT * FROM psi_regions WHERE region_id = $rid");
loadDBValues("psi_provinces", "SELECT * FROM psi_provinces WHERE province_id = $pid");


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
    loadDBValues("psi_cities", "SELECT * FROM psi_cities WHERE city_id = ".$id);
} else {
    initFormValues('psi_cities');
    $GLOBALS['region_id'] = $rid;
    $GLOBALS['province_id'] = $pid;
}

loadFormCache('psi_cities');

$sel_provinces = getOptions('psi_provinces', 'province_name', 'province_id', $GLOBALS['province_id'], '', "WHERE region_id = $rid ORDER BY province_name ASC");
$sel_districts = getOptions('psi_districts', 'district_name', 'district_id', $GLOBALS['district_id'], '', 'ORDER BY district_name ASC');

$page_title = 'Location Listings - Cities ('.$opstr.')';
page_header($page_title);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">
            <?php echo $page_title; ?>
            <br>
            <span class="text-primary">
            <?php echo $GLOBALS['province_name']; ?>
            </span>
            <small>
            <br>
            <?php echo $GLOBALS['region_code'].' ('.$GLOBALS['region_name'].')'; ?>
            </small>
        </h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="lu_location_cities.php?rid=<?php echo $rid; ?>&amp;pid=<?php echo $pid; ?>" title="Go Back"><span class="fa fa-arrow-circle-left"></span> Back</a>        
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="lu_location_cities_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;rid=<?php echo $rid; ?>&amp;pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form" role="form">

            <div class="form-group">
                <label for="city_name" class="control-label">Name *</label>
                <input class="form-control input-sm" placeholder="Name" maxlength="255" required="required" name="city_name" id="city_name" type="text" value="<?php echo $GLOBALS['city_name']; ?>">
            </div>

            <div class="form-group">
                <label for="district_id" class="control-label">District </label>
                <select class="form-control" id="district_id" name="district_id"><?php echo $sel_districts; ?></select>
            </div>

            <div class="form-group">
                <label for="province_id" class="control-label">Province </label>
                <select class="form-control" id="province_id" name="province_id"><?php echo $sel_provinces; ?></select>

            </div>

            <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
            <input type="hidden" name="region_id" value="<?php echo $GLOBALS['region_id']; ?>">
            
            <input type="hidden" name="city_id" value="<?php echo $GLOBALS['city_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
page_footer();
?>