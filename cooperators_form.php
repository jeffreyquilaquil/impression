<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'cooperators.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'cooperators.php');

if ($op == 1){
    if (!can_access('Cooperators', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Cooperators', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$GLOBALS['sector_others'] = '';

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_cooperators", "SELECT * FROM psi_cooperators WHERE coop_id = ".$id);
} else {
    initFormValues('psi_cooperators');
    $GLOBALS['coop_year_established'] = date('Y');
    $GLOBALS['coop_reg_dti_date'] = '';
    $GLOBALS['coop_reg_sec_date'] = '';
    $GLOBALS['coop_reg_cda_date'] = '';
}

loadFormCache('psi_cooperators', 'sector_others');

load_coop_sectors($id);
$sectorboxes = get_sector_checkboxes();

// $sel_sectors = getOptions('psi_sectors', 'sector_name', 'sector_id', $GLOBALS['sector_id'], '', 'ORDER by sector_name ASC');

$sel_orgtype_cat1 = getOptions('psi_organization_types_cat1', 'ot_cat1_name', 'ot_cat1_id', $GLOBALS['ot_cat1_id']);
$sel_orgtype_cat2 = getOptions('psi_organization_types_cat2', 'ot_cat2_name', 'ot_cat2_id', $GLOBALS['ot_cat2_id']);
$sel_orgtype_cat3 = getOptions('psi_organization_types_cat3', 'ot_cat3_name', 'ot_cat3_id', $GLOBALS['ot_cat3_id']);

$sel_usergroup = getOptions('psi_usergroups', 'ug_name', 'ug_id', $GLOBALS['ug_id'], '', "WHERE (ug_name like '%PSTC-%') OR (ug_name like '%RO-%')");


$page_title = 'Cooperators / Beneficiaries ('.$opstr.')';
page_header($page_title);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left"><?php echo $page_title; ?></h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="cooperators.php" title="Cooperators"><span class="fa fa-arrow-circle-left"></span> Back</a>        
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="cooperators_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group">
        <label for="coop_name" class="control-label">Beneficiary/Cooperator Name *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Cooperator Name" maxlength="255" required="required" name="coop_name" id="coop_name" type="text" value="<?php echo $GLOBALS['coop_name']; ?>">
                </div>

        <div class="form-group">
        <label for="coop_year_established" class="control-label">Year Established *</label>
        <input class="form-control input-sm" placeholder="Year Established" maxlength="4" min="1800" max="<?php echo date('Y'); ?>" required="required" name="coop_year_established" id="coop_year_established" type="number" value="<?php echo $GLOBALS['coop_year_established']; ?>">
                </div>

        <h3><span class="label label-default full-width">Contact Person</span></h3>
    
        <div class="form-group">
        <label for="coop_p_fname" class="control-label">First Name</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="First Name" maxlength="255" name="coop_p_fname" id="coop_p_fname" type="text" value="<?php echo $GLOBALS['coop_p_fname']; ?>">
                </div>

        <div class="form-group">
        <label for="coop_p_mname" class="control-label">Middle Name</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Middle Name" maxlength="255" name="coop_p_mname" id="coop_p_mname" type="text" value="<?php echo $GLOBALS['coop_p_mname']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="coop_p_lname" class="control-label">Last Name</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Last Name" maxlength="255" name="coop_p_lname" id="coop_p_lname" type="text" value="<?php echo $GLOBALS['coop_p_lname']; ?>">
                </div>

        <h3><span class="label label-default full-width">Contact Details</span></h3>

        <div class="form-group">
        <label for="coop_address" class="control-label">Address *</label>
        <textarea class="form-control input-sm" placeholder="Address" name="coop_address" id="coop_address" cols="50" rows="4"><?php echo $GLOBALS['coop_address']; ?></textarea>
        </div>

        <div class="form-group has-feedback">
        <label for="coop_phone" class="control-label">Phone</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Phone" maxlength="255" name="coop_phone" id="coop_phone" type="text" value="<?php echo $GLOBALS['coop_phone']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="coop_fax" class="control-label">Fax</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Fax" maxlength="255" name="coop_fax" id="coop_fax" type="text" value="<?php echo $GLOBALS['coop_fax']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="coop_mobile" class="control-label">Mobile</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Mobile" maxlength="255" name="coop_mobile" id="coop_mobile" type="text" value="<?php echo $GLOBALS['coop_mobile']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="coop_email" class="control-label">Email</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Email" maxlength="255" name="coop_email" id="coop_email" type="email" value="<?php echo $GLOBALS['coop_email']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="coop_website" class="control-label">Website</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Website" maxlength="255" name="coop_website" id="coop_website" type="text" value="<?php echo $GLOBALS['coop_website']; ?>">
                </div>

        <h3><span class="label label-default full-width">Type Of Organization</span></h3>
        <div class="form-group form-group-sm">
        <select class="form-control input-sm" id="ot_cat1_id" name="ot_cat1_id">
        <?php echo $sel_orgtype_cat1; ?>
        </select>
        </div>

        <div class="form-group form-group-sm">
        <select class="form-control input-sm" id="ot_cat2_id" name="ot_cat2_id">
        <?php echo $sel_orgtype_cat2; ?>
        </select>
        </div>

        <div class="form-group form-group-sm">
        <select class="form-control input-sm" id="ot_cat3_id" name="ot_cat3_id">
        <?php echo $sel_orgtype_cat3; ?>
        </select>
        </div>

        <h3><span class="label label-default full-width">Business Registration</span></h3>
        
        <div class="row clearfix">
            <div class="col-sm-6">
                <div class="form-group has-feedback">
                <label for="coop_reg_dti" class="control-label">DTI Registration #</label>
                <input class="form-control input-sm" placeholder="DTI Registration #" maxlength="255" name="coop_reg_dti" id="coop_reg_dti" type="text" value="<?php echo $GLOBALS['coop_reg_dti']; ?>">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group has-feedback">
                <label for="coop_reg_dti_date" class="control-label">Date Of Registration</label>
                <input class="form-control input-sm date-picker" placeholder="Date" maxlength="12" name="coop_reg_dti_date" id="coop_reg_dti_date" type="text" value="<?php echo $GLOBALS['coop_reg_dti_date']; ?>">
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-sm-6">
                <div class="form-group has-feedback">
                <label for="coop_reg_sec" class="control-label">SEC Registration #</label>
                <input class="form-control input-sm" placeholder="SEC Registration #" maxlength="255" name="coop_reg_sec" id="coop_reg_sec" type="text" value="<?php echo $GLOBALS['coop_reg_sec']; ?>">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group has-feedback">
                <label for="coop_reg_sec_date" class="control-label">Date Of Registration</label>
                <input class="form-control input-sm date-picker" placeholder="Date" maxlength="12" name="coop_reg_sec_date" id="coop_reg_sec_date" type="text" value="<?php echo $GLOBALS['coop_reg_sec_date']; ?>">
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-sm-6">
                <div class="form-group has-feedback">
                <label for="coop_reg_cda" class="control-label">CDA Registration #</label>
                <input class="form-control input-sm" placeholder="CDA Registration #" maxlength="255" name="coop_reg_cda" id="coop_reg_cda" type="text" value="<?php echo $GLOBALS['coop_reg_cda']; ?>">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group has-feedback">
                <label for="coop_reg_cda_date" class="control-label">Date Of Registration</label>
                <input class="form-control input-sm date-picker" placeholder="Date" maxlength="12" name="coop_reg_cda_date" id="coop_reg_cda_date" type="text" value="<?php echo $GLOBALS['coop_reg_cda_date']; ?>">
                </div>
            </div>
        </div>

        <div class="form-group has-feedback">
        <label for="coop_reg_others" class="control-label">Other Registrations</label>
        <textarea class="form-control input-sm" placeholder="Other Registrations" name="coop_reg_others" id="coop_reg_others" cols="50" rows="4"><?php echo $GLOBALS['coop_reg_others']; ?></textarea>
        </div>

        <h3><span class="label label-default full-width">Sectors / Business Activities</span></h3>

        <?php echo $sectorboxes; ?>

        <?php
        if (!in_pstc($GLOBALS['ad_ug_name'])){
        ?>
        <div class="form-group form-group-sm">
        <label for="ug_id" class="control-label">Contact Owner</label>
        <select class="form-control input-sm" id="ug_id" name="ug_id">
        <?php echo $sel_usergroup; ?>
        </select>
        </div>
        <?php
        }
        ?>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="coop_id" value="<?php echo $GLOBALS['coop_id']; ?>">


        <?php
        if (in_pstc($GLOBALS['ad_ug_name'])){
        ?>
        <input type="hidden" name="ug_id" value="<?php echo $GLOBALS['ad_ug_id']; ?>">
        <?php
        }
        ?>

        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>