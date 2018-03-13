<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php?doggy');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_repayments.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_repayments.php?pid='.$pid);

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
    redirect(WEBSITE_URL.'projects.php?doggy2');
    die();
}

$opstr = 'Initial Repayment Schedule';
if ($op == 1){
    $opstr = 'Edit Repayment Schedule';
    loadDBValues("psi_repayments", "SELECT * FROM psi_repayments WHERE rep_id = ".$id);
} else {
    initFormValues('psi_repayments');
    $GLOBALS['rep_start_year'] = date('Y');
    $GLOBALS['rep_start_month'] = date('m');
    $GLOBALS['rep_amount'] = 0;
}

loadFormCache('psi_repayments');

$page_title = 'Project Repayments('.$opstr.')';
page_header($page_title, 1);

$maxYear = intval(date('Y')) + 10;
$sel_month = getMonthOptions($GLOBALS['rep_start_month']);

$total_amount_to_pay = $GLOBALS['rep_amount'] + $GLOBALS['rep_add_amount'] - $GLOBALS['rep_ub_amount'];

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title"><?php echo $page_title; ?></h3>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="project_repayments.php?pid=<?php echo $pid; ?>" title="Project Repayments"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="project_repayments_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form" role="form">

        <?php
            $rep = getRepayment($pid);
            if ($op == 0){
            ?>
                <div class="form-group">
                    <label for="rep_start_year" class="control-label">Payment Starting Year  *</label>
                    <input class="form-control input-sm" placeholder="Payment Starting Year" maxlength="4" min="1800" max="<?php echo $maxYear; ?>" required="required" name="rep_start_year" id="rep_start_year" type="number" value="<?php echo $GLOBALS['rep_start_year']; ?>">
                </div>
        
                <div class="form-group">
                    <label for="rep_start_month" class="control-label">Payment Starting Month *</label>
                    <select class="form-control input-sm" id="rep_start_month" name="rep_start_month" required="requiired">
                        <?php echo $sel_month; ?>
                    </select>
                </div>
            <?php
                } else {
            ?>
                <div class="form-group">
                    <label class="control-label">Payment Starting Year</label>
                    <div class="well well-default">
                    <?php echo $GLOBALS['rep_start_year']; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Payment Starting Month</label>
                    <div class="well well-default">
                    <?php echo getMonthName($GLOBALS['rep_start_month']); ?>
                    </div>
                </div>

                <input type="hidden" name="rep_start_year" value="<?php echo $GLOBALS['rep_start_year']; ?>">
                <input type="hidden" name="rep_start_month" value="<?php echo $GLOBALS['rep_start_month']; ?>">
            <?php
            }
        ?>

        <div class="form-group">
        <label for="rep_amount" class="control-label">SETUP Cost *</label>
        <input class="form-control input-sm" placeholder="SETUP Cost" min="0" step="any" required="required" name="rep_amount" id="rep_amount" type="number" value="<?php echo $GLOBALS['rep_amount']; ?>">
        </div>

        <div class="form-group">
        <label for="rep_otb" class="control-label">Option to Purchase *</label>
        <input class="form-control input-sm" placeholder="Option to Purchase" min="0" step="any" required="required" name="rep_otb" id="rep_otb" type="number" value="<?php echo $GLOBALS['rep_otb']; ?>">
        </div>

        <div class="form-group">
        <label for="rep_add_amount" class="control-label"><span class="label label-success">+</span> Additional *</label>
        <input class="form-control input-sm" placeholder="Additional" min="0" step="any" required="required" name="rep_add_amount" id="rep_add_amount" type="number" value="<?php echo $GLOBALS['rep_add_amount']; ?>">
        </div>

        <div class="form-group">
        <label for="rep_ub_amount" class="control-label"><span class="label label-danger">-</span> Unexpended Balance *</label>
        <input class="form-control input-sm" placeholder="Total Amount To Be Paid" min="0" step="any" required="required" name="rep_ub_amount" id="rep_ub_amount" type="number" value="<?php echo $GLOBALS['rep_ub_amount']; ?>">
        </div>

        <!--
        <div class="form-group">
        <label class="control-label">Total Amount Due</label>
        <div class="well well-default" id="repayment-total-amount"><?php
            echo $total_amount_to_pay;
        ?></div>
        </div>
        -->

        <div class="form-group">
        <label for="rep_monthly_payment" class="control-label">Monthly Payment *</label>
        <input class="form-control input-sm" placeholder="Monthly Payment" min="0" step="any" required="required" name="rep_monthly_payment" id="rep_monthly_payment" type="number" value="<?php echo $GLOBALS['rep_monthly_payment']; ?>">
        </div>

        <div class="form-group">
        <label for="rep_remarks" class="control-label">Remarks</label>
        <textarea class="form-control input-sm" placeholder="Remarks" name="rep_remarks" id="rep_remarks" cols="50" rows="4"><?php echo $GLOBALS['rep_remarks']; ?></textarea>
        </div>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="ug_id" value="<?php echo $GLOBALS['ug_id']; ?>">
        <input type="hidden" name="prj_id" value="<?php echo $pid; ?>">
        <input type="hidden" name="rep_id" value="<?php echo $GLOBALS['rep_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<script>
var _op = <?php echo $op; ?>;
var _balance = <?php echo $GLOBALS['rep_balance']; ?>;
</script>
<?php
    page_footer();
?>