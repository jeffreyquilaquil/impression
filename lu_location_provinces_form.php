<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_location_regions.php');
$rid = requestInteger('rid', 'location: '.WEBSITE_URL.'lu_location_regions.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL."lu_location_provinces.php?rid=$rid");
loadDBValues("psi_regions", "SELECT * FROM psi_regions WHERE region_id = $rid");


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
    loadDBValues("psi_provinces", "SELECT * FROM psi_provinces WHERE province_id = ".$id);
} else {
    initFormValues('psi_provinces');
    $GLOBALS['region_id'] = $rid;
}

loadFormCache('psi_provinces');

$sel_regions = getOptions('psi_regions', 'region_code', 'region_id', $GLOBALS['region_id'], '', 'ORDER BY region_id ASC');

$page_title = 'Location Listings - Provinces ('.$opstr.')';
page_header($page_title);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">
            <?php echo $page_title; ?>
            <br>
            <span class="text-primary">
                <?php echo $GLOBALS['region_name']; ?>
            </span>
        </h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="lu_location_provinces.php?rid=<?php echo $rid; ?>" title="Go Back"><span class="fa fa-arrow-circle-left"></span> Back</a>        
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="lu_location_provinces_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;rid=<?php echo $rid; ?>" accept-charset="UTF-8" class="form" role="form">

            <div class="form-group">
                <label for="province_name" class="control-label">Name *</label>
                <input class="form-control input-sm" placeholder="Name" maxlength="255" required="required" name="province_name" id="province_name" type="text" value="<?php echo $GLOBALS['province_name']; ?>">
            </div>

            <div class="form-group">
                <label for="region_id" class="control-label">Region </label>
                <select class="form-control" id="region_id" name="region_id"><?php echo $sel_regions; ?></select>
            </div>


            <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
            <input type="hidden" name="region_id" value="<?php echo $GLOBALS['region_id']; ?>">
            <input type="hidden" name="province_id" value="<?php echo $GLOBALS['province_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
page_footer();
?>