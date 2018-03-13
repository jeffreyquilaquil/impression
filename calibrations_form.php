<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'calibrations.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'calibrations.php');

if ($op == 1){
    if (!can_access('Testings & Calibrations', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Testings & Calibrations', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_calibrations", "SELECT * FROM psi_calibrations WHERE cal_id = ".$id);
} else {
    initFormValues('psi_calibrations');

    $GLOBALS['cal_year'] = date('Y');
    $GLOBALS['lab_id'] = $GLOBALS['ad_ug_id'];
    /*
    if ($GLOBALS['ad_ug_id'] == 5){
        $GLOBALS['lab_id'] = 1;
    } elseif ($GLOBALS['ad_ug_id'] == 6){
        $GLOBALS['lab_id'] = 2;
    } elseif ($GLOBALS['ad_ug_id'] == 7){
        $GLOBALS['lab_id'] = 3;
    } elseif ($GLOBALS['ad_ug_id'] == 8){
        $GLOBALS['lab_id'] = 4;
    }
    */
}

loadFormCache('psi_calibrations');

$sel_labs = getOptions('psi_laboratories', 'lab_name', 'lab_id', $GLOBALS['lab_id']);
$sel_usergroup = getOptions('psi_usergroups', 'ug_name', 'ug_id', $GLOBALS['ug_id']);

$sel_month = getMonthOptions($GLOBALS['cal_month']);


page_header('Testing &amp; Calibrations ('.$opstr.')', 2);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Testing &amp; Calibrations (<?php echo $opstr; ?>) </h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="calibrations.php" title="Calibrations"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="calibrations_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group">
        <label for="cal_year" class="control-label">Year  *</label>
        <input class="form-control input-sm" placeholder="Year" maxlength="4" min="1800" max="<?php echo date('Y'); ?>" required="required" name="cal_year" id="cal_year" type="number" value="<?php echo $GLOBALS['cal_year']; ?>">
                </div>

        <div class="form-group form-group-sm">
        <label for="cal_month" class="control-label">Month *</label>
        <select class="form-control input-sm" id="cal_month" name="cal_month" required="requiired">
        <?php echo $sel_month; ?>
        </select>
        </div>

        <div class="form-group">
        <label for="cal_no_tests" class="control-label">No. of Services Rendered *</label>
        <input class="form-control input-sm" placeholder="No. of Services Rendered" min="0" step="1" required="required" name="cal_no_tests" id="cal_no_tests" type="number" value="<?php echo $GLOBALS['cal_no_tests']; ?>">
                </div>

        <div class="form-group">
        <label for="cal_no_calibrations" class="control-label">No. of Samples Tested / Calibrations *</label>
        <input class="form-control input-sm" placeholder="No. of Samples Tested / Calibrations" min="0" step="1" required="required" name="cal_no_calibrations" id="cal_no_calibrations" type="number" value="<?php echo $GLOBALS['cal_no_calibrations']; ?>">
                </div>

        <div class="form-group">
        <label for="cal_no_clients" class="control-label">No. of Customers Assisted *</label>
        <input class="form-control input-sm" placeholder="No. of Customers Assisted" min="0" step="1" required="required" name="cal_no_clients" id="cal_no_clients" type="number" value="<?php echo $GLOBALS['cal_no_clients']; ?>">
                </div>

        <div class="form-group">
        <label for="cal_no_firms" class="control-label">No. of Firms Assisted*</label>
        <input class="form-control input-sm" placeholder="No. of Firms Assisted" min="0" step="1" required="required" name="cal_no_firms" id="cal_no_firms" type="number" value="<?php echo $GLOBALS['cal_no_firms']; ?>">
                </div>

        <div class="form-group">
        <label for="cal_income" class="control-label">Income Generated *</label>
        <input class="form-control input-sm" placeholder="Income Generated" min="0" step="any" required="required" name="cal_income" id="cal_income" type="number" value="<?php echo $GLOBALS['cal_income']; ?>">
                </div>

        <div class="form-group">
        <label for="cal_value_service" class="control-label">Value Of Assistance *</label>
        <input class="form-control input-sm" placeholder="Value Of Assistance" min="0" step="any" required="required" name="cal_value_service" id="cal_value_service" type="number" value="<?php echo $GLOBALS['cal_value_service']; ?>">
                </div>


        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="lab_id" value="<?php echo $GLOBALS['lab_id']; ?>">
        <input type="hidden" name="cal_id" value="<?php echo $GLOBALS['cal_id']; ?>">
        <input type="hidden" name="ug_id" value="<?php echo $GLOBALS['ad_ug_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>