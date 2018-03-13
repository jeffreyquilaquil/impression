<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'service_providers.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'service_providers.php');

if ($op == 1){
    if (!can_access('Service Providers', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Service Providers', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$GLOBALS['sp_type_id'] = array();

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_service_providers", "SELECT * FROM psi_service_providers WHERE sp_id = ".$id);
    load_services($id);
} else {
    initFormValues('psi_service_providers');
}

loadFormCache('psi_service_providers', 'sp_type_id');

$sel_orgtype = getOptions('psi_service_provider_types', 'sp_type_name', 'sp_type_id', $GLOBALS['sp_type_id']);

page_header('Service Providers ('.$opstr.')');

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Service Providers (<?php echo $opstr; ?>) </h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="service_providers.php" title="Service Providers"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="service_providers_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group">
        <label for="sp_name" class="control-label">Company Name / Resource Person Name (If Individual) *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Company Name" maxlength="255" required="required" name="sp_name" id="sp_name" type="text" value="<?php echo $GLOBALS['sp_name']; ?>">
                </div>

        <div class="form-group form-group-sm">
            <label class="control-label">Services Provided *</label>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="sp_type_id[]" id="sp_type_id1" value="1" <?php  echo checkbox_multiselect($GLOBALS['sp_type_id'], 1); ?>> Supplier
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="sp_type_id[]" id="sp_type_id2" value="2" <?php  echo checkbox_multiselect($GLOBALS['sp_type_id'], 2); ?>> Fabricator
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="sp_type_id[]" id="sp_type_id3" value="3" <?php  echo checkbox_multiselect($GLOBALS['sp_type_id'], 3); ?>> Trainor / Resource Person
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="sp_type_id[]" id="sp_type_id4" value="4" <?php  echo checkbox_multiselect($GLOBALS['sp_type_id'], 4); ?>> Others <span class="text-warning">(Please specify...)</span>
                </label>
                 <input class="form-control input-sm" placeholder="Other service..." maxlength="255" name="sp_other_service" id="sp_other_service" type="text" value="<?php echo $GLOBALS['sp_other_service']; ?>">
            </div>
        </div>

        <div class="form-group">
        <label for="sp_expertise" class="control-label">Field of Expertise</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Field of Expertise" maxlength="255" name="sp_expertise" id="sp_expertise" type="text" value="<?php echo $GLOBALS['sp_expertise']; ?>">
                </div>

        <div class="form-group">
        <label for="sp_product_line" class="control-label">Product Lines</label>
        <textarea class="form-control input-sm" placeholder="Product Lines" name="sp_product_line" id="sp_product_line" cols="50" rows="4"><?php echo $GLOBALS['sp_product_line']; ?></textarea>
        </div>

        <h3><span class="label label-default full-width">
            Contact Person
        </span></h3>

        <div class="form-group">
        <label for="sp_fname" class="control-label">First Name</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="First Name" maxlength="255" name="sp_fname" id="sp_fname" type="text" value="<?php echo $GLOBALS['sp_fname']; ?>">
                </div>

        <div class="form-group">
        <label for="sp_mname" class="control-label">Middle Name</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Middle Name" maxlength="255" name="sp_mname" id="sp_mname" type="text" value="<?php echo $GLOBALS['sp_mname']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="sp_lname" class="control-label">Last Name</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Last Name" maxlength="255" name="sp_lname" id="sp_lname" type="text" value="<?php echo $GLOBALS['sp_lname']; ?>">
                </div>

        <div class="form-group">
        <label for="sp_designation" class="control-label">Designation</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Designation" maxlength="255" name="sp_designation" id="sp_designation" type="text" value="<?php echo $GLOBALS['sp_designation']; ?>">
                </div>

        <h3><span class="label label-default full-width">
            Contact Details
        </span></h3>

        <div class="form-group">
        <label for="sp_address" class="control-label">Address *</label>
        <textarea class="form-control input-sm" placeholder="Address" name="sp_address" id="sp_address" cols="50" rows="4"><?php echo $GLOBALS['sp_address']; ?></textarea>
        </div>

        <div class="form-group has-feedback">
        <label for="sp_phone" class="control-label">Phone</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Phone" maxlength="255" name="sp_phone" id="sp_phone" type="text" value="<?php echo $GLOBALS['sp_phone']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="sp_mobile" class="control-label">Mobile</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Mobile" maxlength="255" name="sp_mobile" id="sp_mobile" type="text" value="<?php echo $GLOBALS['sp_mobile']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="sp_email" class="control-label">Email</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Email" maxlength="255" name="sp_email" id="sp_email" type="email" value="<?php echo $GLOBALS['sp_email']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="sp_website" class="control-label">Website</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Website" maxlength="255" name="sp_website" id="sp_website" type="text" value="<?php echo $GLOBALS['sp_website']; ?>">
                </div>
        
        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="sp_id" value="<?php echo $GLOBALS['sp_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();

    function load_services($pid){
        $sql = "SELECT * FROM psi_service_provider_services WHERE sp_id = $pid";
        $res = mysqli_query($GLOBALS['cn'], $sql);
        if (!$res) return;
        while ($row = mysqli_fetch_array($res)){
            $GLOBALS['sp_type_id'][] = $row['service_id'];
        }
        mysqli_free_result($res);
    }
?>