<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_equipment.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_equipment.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Equipment', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Equipment', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}


$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_equipment", "SELECT * FROM psi_equipment WHERE eqp_id = ".$id);
} else {
    initFormValues('psi_equipment');
    $GLOBALS['eqp_receipt_date'] = '';
    $GLOBALS['eqp_date_acquired'] = '';
    $GLOBALS['eqp_date_tagged'] = '';
}

loadFormCache('psi_equipment');

$page_title = 'Project Equipment ('.$opstr.')';
page_header($page_title, 1);

$sel_brands = getOptions('psi_equipment_brands', 'brand_name', 'brand_id', $GLOBALS['brand_id']);
$sel_providers = getOptions('psi_service_providers', 'sp_name', 'sp_id', $GLOBALS['sp_id'], '', 'WHERE sp_id in (SELECT sp_id FROM psi_service_provider_services WHERE (service_id = 1) OR (service_id = 2)) ORDER BY sp_name ASC');
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="project_equipment.php?pid=<?php echo $pid; ?>" title="Project Equipment"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
    </div>
    <div class="panel-body">

        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="project_equipment_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group form-group-sm">
        <label for="sp_id" class="control-label">Supplier</label>
        <select class="form-control input-sm" id="sp_id" name="sp_id">
        <?php echo $sel_providers; ?>
        </select>
        </div>

        <div class="form-group form-group-sm">
        <label for="brand_id" class="control-label">Equipment Name</label>
        <select class="form-control input-sm" id="brand_id" name="brand_id">
        <?php echo $sel_brands; ?>
        </select>
        </div>

        <div class="form-group">
        <label for="eqp_property_no" class="control-label">Property No. *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Property No." maxlength="255" required="required" name="eqp_property_no" id="eqp_property_no" type="text" value="<?php echo $GLOBALS['eqp_property_no']; ?>">
                </div>

        <div class="form-group">
        <label for="eqp_specs" class="control-label">Equipment Specification *</label>
        <textarea class="form-control input-sm" placeholder="Equipment Specification" name="eqp_specs" id="eqp_specs" cols="50" rows="4"><?php echo $GLOBALS['eqp_specs']; ?></textarea>
        </div>

        <div class="form-group">
        <label for="eqp_qty" class="control-label">Quantity *</label>
        <input class="form-control input-sm" placeholder="Quantity" min="0" step="1" required="required" name="eqp_qty" id="eqp_qty" type="number" value="<?php echo $GLOBALS['eqp_qty']; ?>">
                </div>

        <div class="form-group">
        <label for="eqp_amount_approved" class="control-label">Amount Approved *</label>
        <input class="form-control input-sm" placeholder="Amount Approved" min="0" step="any" required="required" name="eqp_amount_approved" id="eqp_amount_approved" type="number" value="<?php echo $GLOBALS['eqp_amount_approved']; ?>">
                </div>

        <div class="form-group">
        <label for="eqp_amount_acquired" class="control-label">Amount Acquired *</label>
        <input class="form-control input-sm" placeholder="Amount Acquired" min="0" step="any" required="required" name="eqp_amount_acquired" id="eqp_amount_acquired" type="number" value="<?php echo $GLOBALS['eqp_amount_acquired']; ?>">
                </div>

        <div class="form-group">
        <label for="eqp_receipt_no" class="control-label">Reciept No.</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Reciept No." maxlength="255" name="eqp_receipt_no" id="eqp_receipt_no" type="text" value="<?php echo $GLOBALS['eqp_receipt_no']; ?>">
                </div>

        <div class="form-group">
        <label for="eqp_receipt_date" class="control-label">Receipt Date</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm date-picker" placeholder="Receipt Date" maxlength="10" name="eqp_receipt_date" id="eqp_receipt_date" type="text" value="<?php echo $GLOBALS['eqp_receipt_date']; ?>">
                </div>

        <div class="form-group">
        <label for="eqp_date_acquired" class="control-label">Date Acquired</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm date-picker" placeholder="Date Acquired" maxlength="10" name="eqp_date_acquired" id="eqp_date_acquired" type="text" value="<?php echo $GLOBALS['eqp_date_acquired']; ?>">
                </div>

        <div class="form-group">
        <label for="eqp_warranty" class="control-label">Warranty</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Warranty" name="eqp_warranty" id="eqp_warranty" type="text" value="<?php echo $GLOBALS['eqp_warranty']; ?>">
                </div>

        <div class="form-group">
        <label for="eqp_date_tagged" class="control-label">Date Tagged</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm date-picker" placeholder="Date Tagged" maxlength="10" name="eqp_date_tagged" id="eqp_date_tagged" type="text" value="<?php echo $GLOBALS['eqp_date_tagged']; ?>">
                </div>

        <div class="form-group">
        <label for="eqp_remarks" class="control-label">Remarks</label>
        <textarea class="form-control input-sm" placeholder="Remarks" name="eqp_remarks" id="eqp_remarks" cols="50" rows="4"><?php echo $GLOBALS['eqp_remarks']; ?></textarea>
        </div>


        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="prj_id" value="<?php echo $pid; ?>">
        <input type="hidden" name="eqp_id" value="<?php echo $GLOBALS['eqp_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();

?>