<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'library.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'library.php');

if ($op == 1){
    if (!can_access('Library Monitoring', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Library Monitoring', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_library", "SELECT * FROM psi_library WHERE lib_id = ".$id);
} else {
    initFormValues('psi_library');
    $GLOBALS['lib_year'] = date('Y');
    $GLOBALS['lib_month'] = date('m');
}

loadFormCache('psi_library');
page_header('Library Monitoring ('.$opstr.')');

$sel_months = getMonthOptions($GLOBALS['lib_month']);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Library Monitoring (<?php echo $opstr; ?>) </h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="library.php" title="Library Monitoring"><span class="fa fa-arrow-circle-left"></span> Back</a>        
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="library_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group">
        <label for="lib_year" class="control-label">Year *</label>
        <input class="form-control input-sm" placeholder="Year" maxlength="4" min="1800" max="<?php echo date('Y'); ?>" required="required" name="lib_year" id="lib_year" type="number" value="<?php echo $GLOBALS['lib_year']; ?>">
                </div>

        <div class="form-group form-group-sm">
        <label for="lib_month" class="control-label">Month</label>
        <select class="form-control input-sm" id="lib_month" name="lib_month">
        <?php echo $sel_months; ?>
        </select>
        </div>

        <div class="form-group">
        <label for="lib_user_count" class="control-label">No. of Users *</label>
        <input class="form-control input-sm" placeholder="No. of Users" min="0" step="1" required="required" name="lib_user_count" id="lib_user_count" type="number" value="<?php echo $GLOBALS['lib_user_count']; ?>">
                </div>

        <div class="form-group">
        <label for="lib_remarks" class="control-label">Remarks</label>
        <textarea class="form-control input-sm" placeholder="Remarks" name="lib_remarks" id="lib_remarks" cols="50" rows="4"><?php echo $GLOBALS['lib_remarks']; ?></textarea>
        </div>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="lib_id" value="<?php echo $GLOBALS['lib_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>