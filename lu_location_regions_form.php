<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_location_regions.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_location_regions.php');

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
    loadDBValues("psi_regions", "SELECT * FROM psi_regions WHERE region_id = ".$id);
} else {
    initFormValues('psi_regions');
}

loadFormCache('psi_regions');

$page_title = 'Location Listings - Regions ('.$opstr.')';
page_header($page_title);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left"><?php echo $page_title; ?></h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="lu_location_regions.php" title="Go Back"><span class="fa fa-arrow-circle-left"></span> Back</a>        
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="lu_location_regions_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

            <div class="form-group">
                <label for="region_code" class="control-label">Code *</label>
                <input class="form-control input-sm" placeholder="Code" maxlength="255" required="required" name="region_code" id="region_code" type="text" value="<?php echo $GLOBALS['region_code']; ?>">
            </div>

            <div class="form-group">
                <label for="region_name" class="control-label">Name *</label>
                <input class="form-control input-sm" placeholder="Name" maxlength="255" required="required" name="region_name" id="region_name" type="text" value="<?php echo $GLOBALS['region_name']; ?>">
            </div>

            <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
            <input type="hidden" name="region_id" value="<?php echo $GLOBALS['region_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
page_footer();
?>