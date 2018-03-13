<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'scholarship_monitoring.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'scholarship_monitoring.php');

if ($op == 1){
    if (!can_access('Scholarship Monitoring', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Scholarship Monitoring', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_scholarship_monitoring", "SELECT * FROM psi_scholarship_monitoring WHERE scholar_mon_id = ".$id);
} else {
    initFormValues('psi_scholarship_monitoring');
    $GLOBALS['scholar_mon_year_from'] = date('Y');
    $GLOBALS['scholar_mon_year_to'] = intval(date('Y')) + 1;
}

loadFormCache('psi_scholarship_monitoring');
page_header('Scholarship Monitoring ('.$opstr.')');

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Scholarship Monitoring (<?php echo $opstr; ?>) </h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="scholarship_monitoring.php" title="Scholarship Monitoring"><span class="fa fa-arrow-circle-left"></span> Back</a>        
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="scholarship_monitoring_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group">
        <label for="scholar_mon_year_from" class="control-label">School Year (From) *</label>
        <input class="form-control input-sm" placeholder="School Year (From)" maxlength="4" min="1800" max="<?php echo date('Y'); ?>" required="required" name="scholar_mon_year_from" id="scholar_mon_year_from" type="number" value="<?php echo $GLOBALS['scholar_mon_year_from']; ?>">
                </div>

        <div class="form-group">
        <label for="scholar_mon_year_to" class="control-label">School Year (To) *</label>
        <input class="form-control input-sm" placeholder="School Year (To)" maxlength="4" min="1800" max="<?php echo (intval(date('Y')) + 1); ?>" required="required" name="scholar_mon_year_to" id="scholar_mon_year_to" type="number" value="<?php echo $GLOBALS['scholar_mon_year_to']; ?>">
                </div>

        <div class="form-group">
        <label for="scholar_mon_no_examinees" class="control-label">No. of Qualifiers *</label>
        <input class="form-control input-sm" placeholder="No. of Qualifiers" min="0" step="1" required="required" name="scholar_mon_no_examinees" id="scholar_mon_no_examinees" type="number" value="<?php echo $GLOBALS['scholar_mon_no_examinees']; ?>">
                </div>

        <div class="form-group">
        <label for="scholar_mon_no_qualifiers" class="control-label">No. of Qualifiers *</label>
        <input class="form-control input-sm" placeholder="No. of Qualifiers" min="0" step="1" required="required" name="scholar_mon_no_qualifiers" id="scholar_mon_no_qualifiers" type="number" value="<?php echo $GLOBALS['scholar_mon_no_qualifiers']; ?>">
                </div>

        <div class="form-group">
        <label for="scholar_mon_no_ongoing" class="control-label">No. of On-Going *</label>
        <input class="form-control input-sm" placeholder="No. of On-Going" min="0" step="1" required="required" name="scholar_mon_no_ongoing" id="scholar_mon_no_ongoing" type="number" value="<?php echo $GLOBALS['scholar_mon_no_ongoing']; ?>">
                </div>

        <div class="form-group">
        <label for="scholar_mon_no_graduates" class="control-label">No. of Graduates *</label>
        <input class="form-control input-sm" placeholder="No. of Graduates" min="0" step="1" required="required" name="scholar_mon_no_graduates" id="scholar_mon_no_graduates" type="number" value="<?php echo $GLOBALS['scholar_mon_no_graduates']; ?>">
                </div>

        <div class="form-group">
        <label for="scholar_mon_remarks" class="control-label">Remarks</label>
        <textarea class="form-control input-sm" placeholder="Remarks" name="scholar_mon_remarks" id="scholar_mon_remarks" cols="50" rows="4"><?php echo $GLOBALS['scholar_mon_remarks']; ?></textarea>
        </div>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="scholar_mon_id" value="<?php echo $GLOBALS['scholar_mon_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>