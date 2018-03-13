<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_schools.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_schools.php');

if ($op == 1){
    if (!can_access('Schools', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Schools', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_schools", "SELECT * FROM psi_schools WHERE school_id = ".$id);
} else {
    initFormValues('psi_schools');
}

loadFormCache('psi_schools');

$page_title = 'Schools ('.$opstr.')';
page_header($page_title);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left"><?php echo $page_title; ?></h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="lu_schools.php" title="Schools"><span class="fa fa-arrow-circle-left"></span> Back</a>        
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="lu_schools_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group">
        <label for="school_name" class="control-label">School Name *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="School Name" maxlength="255" required="required" name="school_name" id="school_name" type="text" value="<?php echo $GLOBALS['school_name']; ?>">
                </div>

        <div class="form-group">
        <label for="school_acronym" class="control-label">Acronym *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Acronym" maxlength="255" required="required" name="school_acronym" id="school_acronym" type="text" value="<?php echo $GLOBALS['school_acronym']; ?>">
                </div>

        <div class="form-group">
        <label for="school_email" class="control-label">Address</label>
        <textarea class="form-control input-sm" placeholder="Address" name="school_address" id="school_address" cols="50" rows="4"><?php echo $GLOBALS['school_address']; ?></textarea>
        </div>

        <div class="form-group">
        <label for="school_coordinator" class="control-label">Coordinator</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Email" maxlength="255" name="school_coordinator" id="school_coordinator" type="text" value="<?php echo $GLOBALS['school_coordinator']; ?>">
                </div>

        <div class="form-group">
        <label for="school_email" class="control-label">Email</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Email" maxlength="255" name="school_email" id="school_email" type="text" value="<?php echo $GLOBALS['school_email']; ?>">
                </div>

        <div class="form-group">
        <label for="school_phone" class="control-label">Phone</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Phone" maxlength="255" name="school_phone" id="school_phone" type="text" value="<?php echo $GLOBALS['school_phone']; ?>">
                </div>

        <div class="form-group">
        <label for="school_mobile" class="control-label">Mobile</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Mobile" maxlength="255" name="school_mobile" id="school_mobile" type="text" value="<?php echo $GLOBALS['school_mobile']; ?>">
                </div>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="school_id" value="<?php echo $GLOBALS['school_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>