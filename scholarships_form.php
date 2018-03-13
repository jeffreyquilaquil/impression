<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'scholarships.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'scholarships.php');

if ($op == 1){
    if (!can_access('Scholarships', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Scholarships', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_scholarships", "SELECT * FROM psi_scholarships WHERE scholar_id = ".$id);
} else {
    initFormValues('psi_scholarships');
    $GLOBALS['scholar_year_award'] = date('Y');
}

loadFormCache('psi_scholarships');

$sel_programs = getOptions('psi_scholarship_programs', 'scholar_prog_name', 'scholar_prog_id', $GLOBALS['scholar_prog_id']);
$sel_courses = getOptions('vwpsi_courses', 'course_label', 'course_id', $GLOBALS['course_id']);
$sel_status = getOptions('psi_scholarship_status', 'scholar_stat_name', 'scholar_stat_id', $GLOBALS['scholar_stat_id']);

page_header('Scholarships ('.$opstr.')');

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Scholarships (<?php echo $opstr; ?>) </h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="scholarships.php" title="Scholarships"><span class="fa fa-arrow-circle-left"></span> Back</a>        
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="scholarships_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group form-group-sm">
        <label for="scholar_prog_id" class="control-label">Scholarship Program</label>
        <select class="form-control input-sm" required="required" id="scholar_prog_id" name="scholar_prog_id">
        <?php echo $sel_programs; ?>
        </select>
        </div>

        <div class="form-group form-group-sm">
        <label for="course_id" class="control-label">Course</label>
        <select class="form-control input-sm" required="required" id="course_id" name="course_id">
        <?php echo $sel_courses; ?>
        </select>
        </div>

        <div class="form-group">
        <label for="scholar_year_award" class="control-label">Year Awarded *</label>
        <input class="form-control input-sm" placeholder="Year Awarded" maxlength="4" min="1800" max="<?php echo date('Y'); ?>" required="required" name="scholar_year_award" id="scholar_year_award" type="number" value="<?php echo $GLOBALS['scholar_year_award']; ?>">
                </div>

        <div class="form-group form-group-sm">
        <label for="scholar_stat_id" class="control-label">Status</label>
        <select class="form-control input-sm" required="required" id="scholar_stat_id" name="scholar_stat_id">
        <?php echo $sel_status; ?>
        </select>
        </div>

        <h3><span class="label label-default full-width">Scholar Information</span></h3>
    
        <div class="form-group">
        <label for="scholar_fname" class="control-label">First Name *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="First Name" maxlength="255" required="required" name="scholar_fname" id="scholar_fname" type="text" value="<?php echo $GLOBALS['scholar_fname']; ?>">
                </div>

        <div class="form-group">
        <label for="scholar_mname" class="control-label">Middle Name *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Middle Name" maxlength="255" required="required" name="scholar_mname" id="scholar_mname" type="text" value="<?php echo $GLOBALS['scholar_mname']; ?>">
                </div>

        <div class="form-group">
        <label for="scholar_lname" class="control-label">Last Name *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Last Name" maxlength="255" required="required" name="scholar_lname" id="scholar_lname" type="text" value="<?php echo $GLOBALS['scholar_lname']; ?>">
                </div>

        <div class="form-group">
        <label for="scholar_address" class="control-label">Address *</label>
        <textarea class="form-control input-sm" placeholder="Address" name="scholar_address" id="scholar_address" cols="50" rows="4"><?php echo $GLOBALS['scholar_address']; ?></textarea>
        </div>

        <div class="form-group has-feedback">
        <label for="scholar_email" class="control-label">Email</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Email" maxlength="255" name="scholar_email" id="scholar_email" type="email" value="<?php echo $GLOBALS['scholar_email']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="scholar_mobile" class="control-label">Mobile</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Mobile" maxlength="255" name="scholar_mobile" id="scholar_mobile" type="text" value="<?php echo $GLOBALS['scholar_mobile']; ?>">
                </div>

        <div class="form-group">
        <label for="scholar_remarks" class="control-label">Remarks</label>
        <textarea class="form-control input-sm" placeholder="Remarks" name="scholar_remarks" id="scholar_remarks" cols="50" rows="4"><?php echo $GLOBALS['scholar_remarks']; ?></textarea>
        </div>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="scholar_id" value="<?php echo $GLOBALS['scholar_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>