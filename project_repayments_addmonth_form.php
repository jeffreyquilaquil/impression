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

$page_title = 'Project Repayments(Adjust No. of Months)';
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
            <div class="alert alert-danger">
        <?php echo $GLOBALS['errmsg']; ?>
            </div>
        <?php } ?>
        <form method="POST" action="project_repayments_addmonth_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form" role="form">

        <?php
            $rep = getRepayment($pid);
        ?>
            <div class="form-group">
            <label for="rep_month_count" class="control-label">No of Months  *</label>
            <input class="form-control input-sm" placeholder="No of Months" maxlength="4" min="1" step="1" required="required" name="rep_month_count" id="rep_month_count" type="number" value="<?php echo $GLOBALS['rep_month_count']; ?>">
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