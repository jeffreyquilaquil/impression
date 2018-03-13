<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_repayments.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_repayments.php?pid='.$pid);
$yr = requestInteger('yr', 'location: '.WEBSITE_URL.'project_repayments.php?pid='.$pid);
$mo = requestInteger('mo', 'location: '.WEBSITE_URL.'project_repayments.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Repayment', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Repayment', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$rep_count = getCount("psi_repayments", "rep_id", "WHERE (prj_id = $pid)");
if ($rep_count == 0){
    $id = requestInteger('id', 'location: '.WEBSITE_URL.'project_repayments.php?pid='.$pid);
}

$rep = getRepayment($pid);

$opstr = 'Add Option To Purchase Payment';
if ($op == 1){
    $opstr = 'Edit Option To Purchase Payment';
    loadDBValues("psi_repayments_payments", "SELECT * FROM psi_repayments_payments WHERE pay_id = ".$id);
    $yr = $GLOBALS['pay_year'];
    $mo = $GLOBALS['pay_month'];
} else {
    initFormValues('psi_repayments_payments');
    $GLOBALS['pay_year'] = $yr;
    $GLOBALS['pay_month'] = $mo;
    $GLOBALS['pay_amount_due'] = ($rep['rep_monthly_payment'] > $rep['rep_balance']) ?  $rep['rep_balance'] : $rep['rep_monthly_payment'];
    $GLOBALS['pay_amount_paid'] = $GLOBALS['pay_amount_due'];
    $GLOBALS['pay_amount_date_paid'] = date('m/d/Y');
    $GLOBALS['pay_count'] = 1;
}

loadFormCache('psi_repayments_payments');

page_header('Project Repayments ('.$opstr.')', 1);

$maxYear = intval(date('Y')) + 10;
$sel_type = getOptions('psi_repayments_payment_types', 'pay_type_name', 'pay_type_id', $GLOBALS['pay_type_id']);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title">Project Repayments (<?php echo $opstr; ?>) </h3>
            <h4>
            <?php 
                if ($rep){
                    $s = '
                        Total Amount To Be Paid : <span class="text-primary">'.zeroCurr($rep['rep_total_amount']).'</span>
                        <br>
                        Total Amount Paid : <span class="text-primary">'.zeroCurr($rep['rep_total_paid']).'</span>
                        <br>
                        Balance : <span class="text-primary">'.zeroCurr($rep['rep_balance']).'</span>
                        <br>
                        <br>
                        Option to Purchase Amount Due: <span class="text-primary">'.zeroCurr($rep['rep_otb_amount']).'</span>
                        <br>
                        Option to Purchase Amount Paid : <span class="text-primary">'.zeroCurr($rep['rep_otb_amount_paid']).'</span>
                    ';
                    echo $s;
                }
            ?>    
            </h4>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="project_repayments.php?pid=<?php echo $pid; ?>" title="Project Repayments"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">

        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="project_payments_otb_save.php?op=<?php echo $op; ?>&amp;pid=<?php echo $pid; ?>&amp;id=<?php echo $id; ?>&amp;yr=<?php echo $yr; ?>&amp;mo=<?php echo $mo; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group">
        <label for="pay_start_year" class="control-label">Payment for the Period</label>
        <div class="well well-default">
        <?php echo getMonthName($mo).' '.$yr; ?>
        </div>
        </div>

        <div class="form-group">
        <label for="pay_amount_paid" class="control-label">Option To Purchase Amount </label>
        <div class="well well-default">
        <?php echo zeroCurr($rep['rep_otb_amount']); ?>
        </div>
                </div>

        <div class="form-group">
        <label for="pay_amount_date_paid" class="control-label">Date Paid</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm date-picker" placeholder="Date Paid" maxlength="10" name="pay_amount_date_paid" id="pay_amount_date_paid" type="text" value="<?php echo $GLOBALS['pay_amount_date_paid']; ?>">
                </div>

        <div class="form-group">
        <label for="pay_receipt_no" class="control-label">Receipt No.</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Receipt No." maxlength="255" name="pay_receipt_no" id="pay_receipt_no" type="text" value="<?php echo $GLOBALS['pay_receipt_no']; ?>">
                </div>

        <div class="form-group form-group-sm">
        <label for="pay_type_id" class="control-label">Mode of Payment</label>
        <select class="form-control input-sm" id="pay_type_id" name="pay_type_id">
        <?php echo $sel_type; ?>
        </select>
        </div>

        <div class="form-group">
        <label for="pay_check_no" class="control-label">Check No. (If paid with check.)</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Check No." maxlength="255" name="pay_check_no" id="pay_check_no" type="text" value="<?php echo $GLOBALS['pay_check_no']; ?>">
                </div>

        <div class="form-group">
        <label for="pay_check_date" class="control-label">Check Dated (If paid with check.)</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm date-picker" placeholder="Check Dated" maxlength="10" name="pay_check_date" id="pay_check_date" type="text" value="<?php echo $GLOBALS['pay_check_date']; ?>">
                </div>

        <div class="form-group">
        <label for="pay_remarks" class="control-label">Remarks</label>
        <textarea class="form-control input-sm" placeholder="Remarks" name="pay_remarks" id="pay_remarks" cols="50" rows="4"><?php echo $GLOBALS['pay_remarks']; ?></textarea>
        </div>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="rep_id" value="<?php echo $rep['rep_id']; ?>">
        <input type="hidden" name="pay_year" value="<?php echo $yr; ?>">
        <input type="hidden" name="pay_month" value="<?php echo $mo; ?>">
        <input type="hidden" name="pay_otb" value="1">
        <input type="hidden" name="pay_id" value="<?php echo $GLOBALS['pay_id']; ?>">
        <input type="hidden" name="ug_id" value="<?php echo $GLOBALS['ug_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();

?>