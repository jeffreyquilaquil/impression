<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Project Equipment', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_equipment.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_equipment.php?pid='.$pid);


if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}



loadDBValues("vwpsi_equipment", "SELECT * FROM vwpsi_equipment WHERE eqp_id = ".$id);

$page_title = 'Project Equipment Details';
page_header($page_title, 1);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title"><?php echo $page_title; ?></h3>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="project_equipment.php?pid=<?php echo $pid; ?>" title="Trainings"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <div class="row-fluid">
            <h5>Supplier.</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['sp_name']; ?>
            </div>
        </div>
        <div class="row-fluid">
            <h5>Brand</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['brand_name']; ?>
            </div>
        </div>
        <div class="row-fluid">
            <h5>Property No.</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['eqp_property_no']; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Equipment Specifications</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['eqp_specs'].''); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Quantity</h5>
            <div class="well well-sm">
                <?php echo zeroNumber($GLOBALS['eqp_qty'].'', 0); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Amount Approved</h5>
            <div class="well well-sm">
                <?php echo zeroCurr($GLOBALS['eqp_amount_approved']); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Amount Aqcuired</h5>
            <div class="well well-sm">
                <?php echo zeroCurr($GLOBALS['eqp_amount_acquired']); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Receipt No.</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['eqp_receipt_no'].''; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Receipt Date</h5>
            <div class="well well-sm">
                <?php echo zeroDate($GLOBALS['eqp_receipt_date'].''); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Date Aqcuired</h5>
            <div class="well well-sm">
                <?php echo zeroDate($GLOBALS['eqp_date_acquired'].''); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Warranty</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['eqp_warranty'].''; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Date Tagged</h5>
            <div class="well well-sm">
                <?php echo zeroDate($GLOBALS['eqp_date_tagged'].''); ?>
            </div>
        </div>

          <div class="row-fluid">
            <h5>Remarks</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['eqp_remarks'].''); ?>
            </div>
        </div>
  </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>