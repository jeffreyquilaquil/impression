<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'activities.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'activities.php');

if ($op == 1){
    if (!can_access('Media Activities', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Media Activities', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}
$GLOBALS['region_id'] = $GLOBALS['ad_u_region_id'];

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_activities", "SELECT * FROM psi_activities WHERE activity_id = ".$id);
} else {
    initFormValues('psi_activities');
    $GLOBALS['activity_no_articles'] = 1;
    $GLOBALS['region_id'] = $GLOBALS['ad_u_region_id'];
}

loadFormCache('psi_activities');

$sel_type = getOptions('psi_activity_types', 'activity_type_name', 'activity_type_id', $GLOBALS['activity_type_id']);
$sel_regions = getOptions('vwpsi_regions', 'region_text', 'region_id', $GLOBALS['region_id'], '', 'ORDER BY region_text ASC');

$page_title = 'Media Activities ('.$opstr.')';
page_header($page_title, 2);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left"><?php echo $page_title; ?></h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="activities.php" title="Media Activities"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="activities_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

            <?php
            if (strlen($GLOBALS['ad_ug_is_admin']) == 1){
                ?>
                <div class="form-group form-group-sm">
                    <label for="region_id" class="control-label">Region</label>
                    <select class="form-control input-sm" id="region_id" name="region_id">
                        <?php echo $sel_regions; ?>
                    </select>
                </div>
                <?php 
            }
            ?>


            <div class="form-group">
                <label for="activity_title" class="control-label">Activity Title *</label>
                <input class="form-control input-sm" placeholder="Activity Title" required="required" name="activity_title" id="activity_title" type="text" value="<?php echo $GLOBALS['activity_title']; ?>">
            </div>

            <div class="form-group form-group-sm">
                <label for="activity_type_id" class="control-label">Category</label>
                <select class="form-control input-sm" id="activity_type_id" name="activity_type_id">
                    <?php echo $sel_type; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="activity_date_accomplished" class="control-label">Date Accomplished *</label>
                <input class="form-control input-sm date-picker" placeholder="Date Accomplished" maxlength="10" required="required" name="activity_date_accomplished" id="activity_date_accomplished" type="text" value="<?php echo $GLOBALS['activity_date_accomplished']; ?>">
            </div>

            <div class="form-group">
                <label for="activity_no_articles" class="control-label">No. of Articles *</label>
                <input class="form-control input-sm" placeholder="No. of Articles" min="0" step="1" required="required" name="activity_no_articles" id="activity_no_articles" type="number" value="<?php echo $GLOBALS['activity_no_articles']; ?>">
            </div>

            <div class="form-group">
                <label for="activity_csf" class="control-label">Overall CSF Rating *</label>
                <input class="form-control input-sm" placeholder="Overall CSF Rating" min="0" step="any" required="required" name="activity_csf" id="activity_csf" type="number" value="<?php echo $GLOBALS['activity_csf']; ?>">
            </div>

            <div class="form-group">
                <label for="activity_address" class="control-label">Address *</label>
                <textarea class="form-control input-sm" placeholder="Address" required="required" name="activity_address" id="activity_address" cols="50" rows="4"><?php echo $GLOBALS['activity_address']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="activity_remarks" class="control-label">Remarks</label>
                <textarea class="form-control input-sm" placeholder="Remarks" name="activity_remarks" id="activity_remarks" cols="50" rows="4"><?php echo $GLOBALS['activity_remarks']; ?></textarea>
            </div>

            <h3><span class="label label-default full-width">Participant Demographics</span></h3>

            <div class="form-group">
                <label for="activity_no_female" class="control-label">No. of Feminine Participants *</label>
                <input class="form-control input-sm" placeholder="No. of Feminine Participants" min="0" step="1" required="required" name="activity_no_female" id="activity_no_female" type="number" value="<?php echo $GLOBALS['activity_no_female']; ?>">
            </div>

            <div class="form-group">
                <label for="activity_no_male" class="control-label">No. of Masculine Participants *</label>
                <input class="form-control input-sm" placeholder="No. of Masculine Participants" min="0" step="1" required="required" name="activity_no_male" id="activity_no_male" type="number" value="<?php echo $GLOBALS['activity_no_male']; ?>">
            </div>

            <div class="form-group">
                <label for="activity_no_pwd" class="control-label">No. of PWD Participants *</label>
                <input class="form-control input-sm" placeholder="No. of PWD Participants" min="0" step="1" required="required" name="activity_no_pwd" id="activity_no_pwd" type="number" value="<?php echo $GLOBALS['activity_no_pwd']; ?>">
            </div>

            <div class="form-group">
                <label for="activity_no_senior" class="control-label">No. of Senior Citizen Participants *</label>
                <input class="form-control input-sm" placeholder="No. of Senior Citizen Participants" min="0" step="1" required="required" name="activity_no_senior" id="activity_no_senior" type="number" value="<?php echo $GLOBALS['activity_no_senior']; ?>">
            </div>

            <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
            <input type="hidden" name="activity_id" value="<?php echo $GLOBALS['activity_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
page_footer();
?>