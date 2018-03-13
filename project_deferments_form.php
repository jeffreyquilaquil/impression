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

if (!$rep){
    redirect(WEBSITE_URL.'project_repayments.php?pid='.$pid);
}


$max_defs = 24 - $rep['rep_deferment_monthcount'];

if ($max_defs == 0){
    $_SESSION['errmsg'] = "The no. of months you can defer has already reached the limit.";
    redirect(WEBSITE_URL.'project_repayments.php?pid='.$pid);
}

//echo $max_defs;

$opstr = 'Add Deferment';
if ($op == 1){
    $opstr = 'Edit Deferment';
    loadDBValues("psi_repayments_payments", "SELECT * FROM psi_repayments_payments WHERE pay_id = ".$id);
} else {
    initFormValues('psi_repayments_payments');
    $GLOBALS['pay_year'] = $yr;
    $GLOBALS['pay_month'] = $mo;
    $GLOBALS['pay_count'] = 1;
}

loadFormCache('psi_repayments_payments');
$page_title = 'Project Repayments('.$opstr.')';
page_header($page_title, 1);
$maxYear = intval(date('Y')) + 10;
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title">Project Repayments (<?php echo $opstr; ?>) </h3>
            <h4>
            <?php 
                if ($rep){
                    $s = '
                        Total Amount Due : <span class="text-primary">'.zeroCurr($rep['rep_total_amount']).'</span>
                        <br>
                        Total Amount Refunded : <span class="text-primary">'.zeroCurr($rep['rep_total_paid']).'</span>
                        <br>
                        Refund Rate : <span class="text-primary">'.zeroNumber($rep['rep_refund_rate'], 0).'%</span>
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
        <form method="POST" action="project_deferments_save.php?op=<?php echo $op; ?>&amp;pid=<?php echo $pid; ?>&amp;id=<?php echo $id; ?>&amp;yr=<?php echo $yr; ?>&amp;mo=<?php echo $mo; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group">
        <label for="pay_start_year" class="control-label">Deferment Starting Period</label>
        <div class="well well-default">
        <?php echo getMonthName($mo).' '.$yr; ?>
        </div>
        </div>

        <div class="form-group">
        <label for="pay_count" class="control-label">No. of Months to Defer *</label>
        <input class="form-control input-sm" placeholder="No. of Months to Defer" min="1" max="<?php echo $max_defs; ?>" step="1" required="required" name="pay_count" id="pay_count" type="number" value="<?php echo $GLOBALS['pay_count']; ?>">
                </div>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="rep_id" value="<?php echo $rep['rep_id']; ?>">
        <input type="hidden" name="pay_month" value="<?php echo $mo; ?>">
        <input type="hidden" name="pay_otb" value="2">
        <input type="hidden" name="pay_year" value="<?php echo $yr; ?>">
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