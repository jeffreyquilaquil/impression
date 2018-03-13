<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'projects.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'projects.php');

if ($op == 1){
    if (!can_access('Projects', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Projects', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$form_return = 0;

if (isset($_SESSION['form_return'])){
    $form_return = $_SESSION['form_return'];
    $_SESSION['form_return'] = 0;
}

$GLOBALS['coop_id'] = array();
$GLOBALS['col_id'] = array();
$GLOBALS['sector_others'] = '';

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_projects", "SELECT * FROM psi_projects WHERE prj_id = ".$id);
    load_beneficiaries($id);
    load_collaborators($id);

    if (in_pstc($GLOBALS['ad_ug_name'])){
        if ($GLOBALS['ug_id'] != $GLOBALS['ad_ug_id']){
            redirect(WEBSITE_URL.'projects.php');
        }
    }

    // debug code
    //$GLOBALS['ad_u_region_id'] = 3;

    if ($GLOBALS['province_id'] == 0){
        $GLOBALS['province_id'] = get_first_province($GLOBALS['ad_u_region_id']);        
    }

    if ($GLOBALS['city_id'] == 0){
        $GLOBALS['city_id'] = get_first_city($GLOBALS['province_id']);
    }

    if ($GLOBALS['barangay_id'] == 0){
        $GLOBALS['barangay_id'] = get_first_barangay($GLOBALS['city_id']);
    }
} else {
    initFormValues('psi_projects');
    $GLOBALS['prj_year_approved'] = date('Y');
    $GLOBALS['prj_longitude'] = DEF_LONGITUDE;
    $GLOBALS['prj_latitude'] = DEF_LATITUDE;
    $GLOBALS['prj_fund_release_date'] = '';
    $GLOBALS['prj_refund_period_from'] = '';
    $GLOBALS['prj_refund_period_to'] = '';

    $GLOBALS['province_id'] = get_first_province($GLOBALS['ad_u_region_id']);
    $GLOBALS['city_id'] = get_first_city($GLOBALS['province_id']);
    $GLOBALS['barangay_id'] = get_first_barangay($GLOBALS['city_id']);

    //echo $GLOBALS['province_id'].'<br>';
    //echo $GLOBALS['city_id'].'<br>';
    //echo $GLOBALS['barangay_id'].'<br>';
}


// load_project_sectors($id);
// $sectorboxes = get_sector_checkboxes();

loadFormCache('psi_projects', 'coop_id, col_id, sector_others, form_return');
//fixText('psi_projects');

$sel_provinces = getOptions('psi_provinces', 'province_name', 'province_id', $GLOBALS['province_id'], '', 'WHERE region_id = '.$GLOBALS['ad_u_region_id'].' ORDER BY province_name ASC');
$sel_cities = getOptions('psi_cities', 'city_name', 'city_id', $GLOBALS['city_id'], '', "WHERE province_id = $GLOBALS[province_id] ORDER BY city_name ASC");
$sel_barangays = getOptions('psi_barangays', 'barangay_name', 'barangay_id', $GLOBALS['barangay_id'], 'None', "WHERE city_id = $GLOBALS[city_id] ORDER BY barangay_name ASC");

$sel_type = getOptions('psi_project_types', 'prj_type_name', 'prj_type_id', $GLOBALS['prj_type_id']);

$sel_beneficiaries = getOptions('psi_cooperators', 'coop_name', 'coop_id', $GLOBALS['coop_id'], '', 'ORDER BY coop_name ASC');

$sel_collaborators = getOptions('vwpsi_collaborators', 'col_listname', 'col_id', $GLOBALS['col_id'], '', 'ORDER BY col_name ASC');

$sel_usergroup = getOptions('psi_usergroups', 'ug_name', 'ug_id', $GLOBALS['ug_id'], '', "WHERE (ug_name like '%PSTC-%') OR (ug_name like '%RO-%')");

$sel_status = getOptions('psi_project_status', 'prj_status_name', 'prj_status_id', $GLOBALS['prj_status_id']);

$sel_sectors = getOptions('psi_sectors', 'sector_name', 'sector_id', $GLOBALS['sector_id'], '', 'ORDER by sector_name ASC');

page_header('Projects ('.$opstr.')');

?>
<script>
    var _province_id = <?php echo $GLOBALS['province_id']; ?>;
    var _city_id = <?php echo $GLOBALS['city_id']; ?>;
    var _barangay_id = <?php echo $GLOBALS['barangay_id']; ?>;

    <?php
    echo load_city_options();
    echo load_barangay_options();

    ?>
</script>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Projects (<?php echo $opstr; ?>) </h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="projects.php" title="Projects"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="projects_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

            <div class="form-group">
                <label for="prj_title" class="control-label">Project Title *</label>
                <input class="form-control input-sm" placeholder="Project Title" maxlength="255" required="required" name="prj_title" id="prj_title" type="text" value="<?php echo $GLOBALS['prj_title']; ?>">
            </div>

            <div class="form-group">
                <label for="prj_code" class="control-label">Project Code</label>
                <input class="form-control input-sm" placeholder="Project Code" maxlength="255" name="prj_code" id="prj_code" type="text" value="<?php echo $GLOBALS['prj_code']; ?>">
            </div>


            <div class="form-group form-group-sm">
                <label for="prj_type_id" class="control-label">Project Type</label>
                <select class="form-control input-sm" id="prj_type_id" name="prj_type_id">
                    <?php echo $sel_type; ?>
                </select>
            </div>

            <div class="form-group form-group-sm">
                <label for="coop_id" class="control-label">Beneficiaries</label>
                <select class="form-control input-sm chosen-select" id="coop_id" name="coop_id[]" multiple="multiple">
                    <?php echo $sel_beneficiaries; ?>
                </select>
            </div>


            <div class="form-group form-group-sm">
                <label for="col_id" class="control-label">Collaborating Agencies</label>
                <select class="form-control input-sm chosen-select" id="col_id" name="col_id[]" multiple="multiple">
                    <?php echo $sel_collaborators; ?>
                </select>
            </div>

            <?php
            if (!in_pstc($GLOBALS['ad_ug_name'])){
                ?>
                <div class="form-group form-group-sm">
                    <label for="ug_id" class="control-label">Implementor</label>
                    <select class="form-control input-sm" id="ug_id" name="ug_id">
                        <?php echo $sel_usergroup; ?>
                    </select>
                </div>
                <?php
            }
            ?>

            <div class="form-group">
                <label for="prj_year_approved" class="control-label">Year Approved *</label>
                <input class="form-control input-sm" placeholder="Year Approved" maxlength="4" min="1800" max="<?php echo date('Y'); ?>" required="required" name="prj_year_approved" id="prj_year_approved" type="number" value="<?php echo $GLOBALS['prj_year_approved']; ?>">
            </div>

            <div class="form-group">
                <label for="prj_objective" class="control-label">Objective *</label>
                <textarea class="form-control input-sm" placeholder="Objective" required="required" name="prj_objective" id="prj_objective" cols="50" rows="4"><?php echo $GLOBALS['prj_objective']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="prj_expected_output" class="control-label">Expected Output *</label>
                <textarea class="form-control input-sm" placeholder="Expected Output" required="required" name="prj_expected_output" id="prj_expected_output" cols="50" rows="4"><?php echo $GLOBALS['prj_expected_output']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="prj_product_line" class="control-label">Products</label>
                <textarea class="form-control input-sm" placeholder="Products" name="prj_product_line" id="prj_product_line" cols="50" rows="4"><?php echo $GLOBALS['prj_product_line']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="prj_fund_release_date" class="control-label">Date Funds Released To The Beneficiary</label>
                <input class="form-control input-sm date-picker" placeholder="Date Tagged" maxlength="10" name="prj_fund_release_date" id="prj_fund_release_date" type="text" value="<?php echo $GLOBALS['prj_fund_release_date']; ?>">
            </div>

            <!--
            <div class="input-daterange">
                <div class="form-group">
                    <label for="prj_refund_period_from" class="control-label">Refund Period (From)</label>
                    <input class="form-control input-sm" placeholder="Approved Refund Schedule (From)" maxlength="10" name="prj_refund_period_from" id="prj_refund_period_from" type="text" value="<?php echo $GLOBALS['prj_refund_period_from']; ?>">
                </div>

                <div class="form-group">
                    <label for="prj_refund_period_to" class="control-label">Refund Period (To)</label>
                    <input class="form-control input-sm" placeholder="Approved Refund Schedule (To)" maxlength="10" name="prj_refund_period_to" id="prj_refund_period_to" type="text" value="<?php echo $GLOBALS['prj_refund_period_to']; ?>">
                </div>
            </div>
            -->

            <div class="form-group form-group-sm">
                <label for="prj_status_id" class="control-label">Project Status</label>
                <select class="form-control input-sm" id="prj_status_id" name="prj_status_id">
                    <?php echo $sel_status; ?>
                </select>
            </div>


            <h3><span class="label label-default full-width">Sector</span></h3>
            <div class="form-group form-group-sm">
                <select class="form-control input-sm" id="sector_id" name="sector_id">
                    <?php echo $sel_sectors; ?>
                </select>
            </div>


            <h3><span class="label label-default full-width">Project Location</span></h3>

            <div class="form-group">
                <label for="prj_address" class="control-label">Address</label>
                <textarea class="form-control input-sm" placeholder="Address" name="prj_address" id="prj_address" cols="50" rows="3"><?php echo $GLOBALS['prj_address']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="province_id" class="control-label">Province</label>
                <select class="form-control input-sm province_select" id="province_id" name="province_id" required="required">
                    <?php echo $sel_provinces; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="city_id" class="control-label">Municipality/City</label>
                <select class="form-control input-sm city_select" id="city_id" name="city_id" required="required">
                    <?php echo $sel_cities; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="barangay_id" class="control-label">Barangay</label>
                <select class="form-control input-sm barangay_select" id="barangay_id" name="barangay_id" required="required">
                    <?php echo $sel_barangays; ?>
                </select>
            </div>

            <h3><span class="label label-default full-width">Costs</span></h3>

            <div class="form-group">
                <label for="prj_cost_setup" class="control-label">Project Cost</label>
                <input class="form-control input-sm" placeholder="Project Cost" min="0" step="any" name="prj_cost_setup" id="prj_cost_setup" type="number" value="<?php echo $GLOBALS['prj_cost_setup']; ?>">
            </div>

        <!--
        <div class="form-group">
            <label for="prj_cost_gia" class="control-label">GIA Project Cost</label>
            <input class="form-control input-sm" placeholder="GIA Project Cost" min="0" step="any" name="prj_cost_gia" id="prj_cost_gia" type="number" value="<?php echo $GLOBALS['prj_cost_gia']; ?>">
        </div>

        <div class="form-group">
            <label for="prj_cost_rollout" class="control-label">Roll-out Project Cost</label>
            <input class="form-control input-sm" placeholder="Roll-out Project Cost" min="0" step="any" name="prj_cost_rollout" id="prj_cost_rollout" type="number" value="<?php echo $GLOBALS['prj_cost_rollout']; ?>">
        </div>

    -->

    <div class="form-group">
        <label for="prj_cost_benefactor" class="control-label">Beneficiaries&rsquo; Counterpart Project Cost</label>
        <input class="form-control input-sm" placeholder="Beneficiaries&rsquo; Counterpart Project Cost" min="0" step="any" name="prj_cost_benefactor" id="prj_cost_benefactor" type="number" value="<?php echo $GLOBALS['prj_cost_benefactor']; ?>">
    </div>

    <div class="form-group">
        <label for="prj_cost_benefactor" class="control-label">Beneficiaries&rsquo; Counterpart Description</label>
        <textarea class="form-control input-sm" placeholder="Beneficiaries&rsquo; Counterpart Description" rows="5" name="prj_cost_benefactor_desc"><?php echo $GLOBALS['prj_cost_benefactor_desc']; ?></textarea> 
    </div>

    <div class="form-group">
        <label for="prj_cost_other" class="control-label">Other Project Cost</label>
        <input class="form-control input-sm" placeholder="Other Project Cost" min="0" step="any" name="prj_cost_other" id="prj_cost_other" type="number" value="<?php echo $GLOBALS['prj_cost_other']; ?>">
    </div>

    <div class="checkbox">
        <label>
            <input type="checkbox" name="prj_fundingsource_local" id="prj_fundingsource_local" value="1" <?php echo checkBox($GLOBALS['prj_fundingsource_local']); ?>>
            Locally Funded
        </label>
    </div>

    <div class="checkbox">
        <label>
            <input type="checkbox" name="prj_fundingsource_external" id="prj_fundingsource_external" value="1" <?php echo checkBox($GLOBALS['prj_fundingsource_external']); ?>>
            Externally Funded
        </label>
    </div>

    <h3><span class="label label-default full-width">Pre-PIS</span></h3>

    <div class="well well-default">
        <h4>Total Assets</h4>

        <div class="form-group">
            <label for="prj_pis_total_assets_land" class="control-label">Land</label>
            <input class="form-control input-sm" placeholder="Land" min="0" step="any" name="prj_pis_total_assets_land" id="prj_pis_total_assets_land" type="number" value="<?php echo $GLOBALS['prj_pis_total_assets_land']; ?>">
        </div>

        <div class="form-group">
            <label for="prj_pis_total_assets_building" class="control-label">Building</label>
            <input class="form-control input-sm" placeholder="Building" min="0" step="any" name="prj_pis_total_assets_building" id="prj_pis_total_assets_building" type="number" value="<?php echo $GLOBALS['prj_pis_total_assets_building']; ?>">
        </div>

        <div class="form-group">
            <label for="prj_pis_total_assets_equipment" class="control-label">Equipment</label>
            <input class="form-control input-sm" placeholder="Equipment" min="0" step="any" name="prj_pis_total_assets_equipment" id="prj_pis_total_assets_equipment" type="number" value="<?php echo $GLOBALS['prj_pis_total_assets_equipment']; ?>">
        </div>

        <div class="form-group">
            <label for="prj_pis_total_assets_working_capital" class="control-label">Working Capital</label>
            <input class="form-control input-sm" placeholder="Working Capital" min="0" step="any" name="prj_pis_total_assets_working_capital" id="prj_pis_total_assets_working_capital" type="number" value="<?php echo $GLOBALS['prj_pis_total_assets_working_capital']; ?>">
        </div>

    </div>            

    <div class="well well-default">
        <h4>Total Employment Generated (Direct Employment)</h4>

        <h4>Company Hire (Regular)</h4>
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_ch_regular_male" class="control-label">Masculine</label>
                    <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prj_pis_dir_ch_regular_male" id="prj_pis_dir_ch_regular_male" type="number" value="<?php echo $GLOBALS['prj_pis_dir_ch_regular_male']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_ch_regular_female" class="control-label">Feminine</label>
                    <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prj_pis_dir_ch_regular_female" id="prj_pis_dir_ch_regular_female" type="number" value="<?php echo $GLOBALS['prj_pis_dir_ch_regular_female']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_ch_regular_pwd" class="control-label">PWD</label>
                    <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prj_pis_dir_ch_regular_pwd" id="prj_pis_dir_ch_regular_pwd" type="number" value="<?php echo $GLOBALS['prj_pis_dir_ch_regular_pwd']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_ch_regular_senior" class="control-label">Senior</label>
                    <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prj_pis_dir_ch_regular_senior" id="prj_pis_dir_ch_regular_senior" type="number" value="<?php echo $GLOBALS['prj_pis_dir_ch_regular_senior']; ?>">
                </div>
            </div>
        </div>

        <h4>Company Hire (Part-Time)</h4>
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_ch_part_time_male" class="control-label">Masculine</label>
                    <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prj_pis_dir_ch_part_time_male" id="prj_pis_dir_ch_part_time_male" type="number" value="<?php echo $GLOBALS['prj_pis_dir_ch_part_time_male']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_ch_part_time_female" class="control-label">Feminine</label>
                    <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prj_pis_dir_ch_part_time_female" id="prj_pis_dir_ch_part_time_female" type="number" value="<?php echo $GLOBALS['prj_pis_dir_ch_part_time_female']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_ch_part_time_pwd" class="control-label">PWD</label>
                    <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prj_pis_dir_ch_part_time_pwd" id="prj_pis_dir_ch_part_time_pwd" type="number" value="<?php echo $GLOBALS['prj_pis_dir_ch_part_time_pwd']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_ch_part_time_senior" class="control-label">Senior</label>
                    <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prj_pis_dir_ch_part_time_senior" id="prj_pis_dir_ch_part_time_senior" type="number" value="<?php echo $GLOBALS['prj_pis_dir_ch_part_time_senior']; ?>">
                </div>
            </div>
        </div>

        <h4>Sub-Contractor Hire (Regular)</h4>
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_sh_regular_male" class="control-label">Masculine</label>
                    <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prj_pis_dir_sh_regular_male" id="prj_pis_dir_sh_regular_male" type="number" value="<?php echo $GLOBALS['prj_pis_dir_sh_regular_male']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_sh_regular_female" class="control-label">Feminine</label>
                    <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prj_pis_dir_sh_regular_female" id="prj_pis_dir_sh_regular_female" type="number" value="<?php echo $GLOBALS['prj_pis_dir_sh_regular_female']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_sh_regular_pwd" class="control-label">PWD</label>
                    <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prj_pis_dir_sh_regular_pwd" id="prj_pis_dir_sh_regular_pwd" type="number" value="<?php echo $GLOBALS['prj_pis_dir_sh_regular_pwd']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_sh_regular_senior" class="control-label">Senior</label>
                    <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prj_pis_dir_sh_regular_senior" id="prj_pis_dir_sh_regular_senior" type="number" value="<?php echo $GLOBALS['prj_pis_dir_sh_regular_senior']; ?>">
                </div>
            </div>
        </div>

        <h4>Sub-Contractor Hire (Part-Time)</h4>
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_sh_part_time_male" class="control-label">Masculine</label>
                    <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prj_pis_dir_sh_part_time_male" id="prj_pis_dir_sh_part_time_male" type="number" value="<?php echo $GLOBALS['prj_pis_dir_sh_part_time_male']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_sh_part_time_female" class="control-label">Feminine</label>
                    <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prj_pis_dir_sh_part_time_female" id="prj_pis_dir_sh_part_time_female" type="number" value="<?php echo $GLOBALS['prj_pis_dir_sh_part_time_female']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_sh_part_time_pwd" class="control-label">PWD</label>
                    <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prj_pis_dir_sh_part_time_pwd" id="prj_pis_dir_sh_part_time_pwd" type="number" value="<?php echo $GLOBALS['prj_pis_dir_sh_part_time_pwd']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_dir_sh_part_time_senior" class="control-label">Senior</label>
                    <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prj_pis_dir_sh_part_time_senior" id="prj_pis_dir_sh_part_time_senior" type="number" value="<?php echo $GLOBALS['prj_pis_dir_sh_part_time_senior']; ?>">
                </div>
            </div>
        </div>
    </div>            

    <div class="well well-default">
        <h4>Total Employment Generated (Indirect Employment)</h4>
        <h4>Forward</h4>
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_indir_forward_male" class="control-label">Masculine</label>
                    <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prj_pis_indir_forward_male" id="prj_pis_indir_forward_male" type="number" value="<?php echo $GLOBALS['prj_pis_indir_forward_male']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_indir_forward_female" class="control-label">Feminine</label>
                    <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prj_pis_indir_forward_female" id="prj_pis_indir_forward_female" type="number" value="<?php echo $GLOBALS['prj_pis_indir_forward_female']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_indir_forward_pwd" class="control-label">PWD</label>
                    <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prj_pis_indir_forward_pwd" id="prj_pis_indir_forward_pwd" type="number" value="<?php echo $GLOBALS['prj_pis_indir_forward_pwd']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_indir_forward_senior" class="control-label">Senior</label>
                    <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prj_pis_indir_forward_senior" id="prj_pis_indir_forward_senior" type="number" value="<?php echo $GLOBALS['prj_pis_indir_forward_senior']; ?>">
                </div>
            </div>
        </div>

        <h4>Backward</h4>
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_indir_backward_male" class="control-label">Masculine</label>
                    <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prj_pis_indir_backward_male" id="prj_pis_indir_backward_male" type="number" value="<?php echo $GLOBALS['prj_pis_indir_backward_male']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_indir_backward_female" class="control-label">Feminine</label>
                    <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prj_pis_indir_backward_female" id="prj_pis_indir_backward_female" type="number" value="<?php echo $GLOBALS['prj_pis_indir_backward_female']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_indir_backward_pwd" class="control-label">PWD</label>
                    <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prj_pis_indir_backward_pwd" id="prj_pis_indir_backward_pwd" type="number" value="<?php echo $GLOBALS['prj_pis_indir_backward_pwd']; ?>">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group">
                    <label for="prj_pis_indir_backward_senior" class="control-label">Senior</label>
                    <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prj_pis_indir_backward_senior" id="prj_pis_indir_backward_senior" type="number" value="<?php echo $GLOBALS['prj_pis_indir_backward_senior']; ?>">
                </div>
            </div>
        </div>
    </div>            

    <div class="well well-default">
        <h4>Total Volume of Production</h4>
        <div class="form-group">
            <label for="prj_pis_volume_production_local" class="control-label">Local</label>
            <input class="form-control input-sm" placeholder="Local" min="0" step="any" name="prj_pis_volume_production_local" id="prj_pis_volume_production_local" type="number" value="<?php echo $GLOBALS['prj_pis_volume_production_local']; ?>">
        </div>

        <div class="form-group">
            <label for="prj_pis_volume_production_export" class="control-label">Export</label>
            <input class="form-control input-sm" placeholder="Export" min="0" step="any" name="prj_pis_volume_production_export" id="prj_pis_volume_production_export" type="number" value="<?php echo $GLOBALS['prj_pis_volume_production_export']; ?>">
        </div>
    </div>            

    <div class="well well-default">
        <h4>Total Gross Sales</h4>
        <div class="form-group">
            <label for="prj_pis_gross_sales_local" class="control-label">Local</label>
            <input class="form-control input-sm" placeholder="Local" min="0" step="any" name="prj_pis_gross_sales_local" id="prj_pis_gross_sales_local" type="number" value="<?php echo $GLOBALS['prj_pis_gross_sales_local']; ?>">
        </div>

        <div class="form-group">
            <label for="prj_pis_gross_sales_export" class="control-label">Export</label>
            <input class="form-control input-sm" placeholder="Export" min="0" step="any" name="prj_pis_gross_sales_export" id="prj_pis_gross_sales_export" type="number" value="<?php echo $GLOBALS['prj_pis_gross_sales_export']; ?>">
        </div>
    </div>            

    <div class="well well-default">
        <h4>Countries of Destination</h4>
        <div class="form-group">
            <textarea class="form-control input-sm" placeholder="Countries of Destination" name="prj_pis_countries_of_destination" id="prj_pis_countries_of_destination" cols="50" rows="4"><?php echo $GLOBALS['prj_pis_countries_of_destination']; ?></textarea>
        </div>
    </div>

    <div class="well well-default">
        <h4>Assistance Obtained From DOST</h4>
        <ul style="list-style-type:none; margin-left:-40px;">
            <li>
                A. Pre-Implementation
            </li>
            <li>
                <div class="form-group">
                    1. Conceptualization
                    <textarea class="form-control input-sm" placeholder="Conceptualization" name="prj_pis_assistance_conceptualization" id="prj_pis_assistance_conceptualization" cols="50" rows="3"><?php echo $GLOBALS['prj_pis_assistance_conceptualization']; ?></textarea>
                </div>
            </li>
            <li>
                <div class="form-group">
                    2. Proposal Preparation
                    <textarea class="form-control input-sm" placeholder="Proposal Preparation" name="prj_pis_assistance_proposal_preparation" id="prj_pis_assistance_proposal_preparation" cols="50" rows="3"><?php echo $GLOBALS['prj_pis_assistance_proposal_preparation']; ?></textarea>
                </div>
            </li>
            <li>
                3. Others (Pls. Specify)
            </li>
            <li>
                <ul style="list-style-type:none; margin-left:-12px;">
                    <li>
                        3.1 Production Technology
                    </li>
                    <li>
                        <ul style="list-style-type:none; margin-left:-12px;">
                            <li>
                                <div class="form-group">
                                    A. Process
                                    <textarea class="form-control input-sm" placeholder="Process" name="prj_pis_assistance_process" id="prj_pis_assistance_process" cols="50" rows="3"><?php echo $GLOBALS['prj_pis_assistance_process']; ?></textarea>
                                </div>
                            </li>
                            <li>
                                <div class="checkbox">
                                    B. Equipment
                                    <textarea class="form-control input-sm" placeholder="Equipment" name="prj_pis_assistance_equipment" id="prj_pis_assistance_equipment" cols="50" rows="3"><?php echo $GLOBALS['prj_pis_assistance_equipment']; ?></textarea>
                                </div>
                            </li>
                            <li>
                                <div class="form-group">
                                    C. Quality Control / Laboratory Testing / Analysis
                                    <textarea class="form-control input-sm" placeholder="Quality Control / Laboratory Testing / Analysis" name="prj_pis_assistance_quality_control" id="prj_pis_assistance_quality_control" cols="50" rows="3"><?php echo $GLOBALS['prj_pis_assistance_quality_control']; ?></textarea>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div class="form-group">
                            3.2 Packaging / Labeling
                            <textarea class="form-control input-sm" placeholder="Packaging / Labeling" name="prj_pis_assistance_packaging_labeling" id="prj_pis_assistance_packaging_labeling" cols="50" rows="3"><?php echo $GLOBALS['prj_pis_assistance_packaging_labeling']; ?></textarea>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            3.3 Post-Harvest
                            <textarea class="form-control input-sm" placeholder="Post-Harvest" name="prj_pis_assistance_post_harvest" id="prj_pis_assistance_post_harvest" cols="50" rows="3"><?php echo $GLOBALS['prj_pis_assistance_post_harvest']; ?></textarea>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            3.4 Marketing Assistance
                            <textarea class="form-control input-sm" placeholder="Marketing Assistance" name="prj_pis_assistance_marketing" id="prj_pis_assistance_marketing" cols="50" rows="3"><?php echo $GLOBALS['prj_pis_assistance_marketing']; ?></textarea>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            3.5 Human Resource Training
                            <textarea class="form-control input-sm" placeholder="Human Resource Training" name="prj_pis_assistance_training" id="prj_pis_assistance_training" cols="50" rows="3"><?php echo $GLOBALS['prj_pis_assistance_training']; ?></textarea>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            3.6 Consultancy Service
                            <textarea class="form-control input-sm" placeholder="Consultancy Service" name="prj_pis_assistance_consultancy" id="prj_pis_assistance_consultancy" cols="50" rows="3"><?php echo $GLOBALS['prj_pis_assistance_consultancy']; ?></textarea>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            3.7 Others (FPD Permit, LGU Registration, Bar Coding)
                            <textarea class="form-control input-sm" placeholder="Others" name="prj_pis_assistance_others" id="prj_pis_assistance_others" cols="50" rows="3"><?php echo $GLOBALS['prj_pis_assistance_others']; ?></textarea>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

    <div class="well well-default">
        <div class="form-group">
            <label for="prj_remarks" class="control-label">Remarks</label>
            <textarea class="form-control input-sm" placeholder="Remarks" name="prj_remarks" id="prj_remarks" cols="50" rows="4"><?php echo $GLOBALS['prj_remarks']; ?></textarea>
        </div>
    </div>

    <h3><span class="label label-default full-width">Project Map Coordinates</span></h3>
    <div id="map-location-picker" class="form-group map-location-picker">
    </div>

    <div class="form-group">
        <label for="prj_longitude" class="control-label">Longitude</label>
        <input class="form-control input-sm" placeholder="Longitude" min="0" step="any" name="prj_longitude" id="longitude" type="number" value="<?php echo $GLOBALS['prj_longitude']; ?>">
    </div>

    <div class="form-group">
        <label for="prj_latitude" class="control-label">Latitude</label>
        <input class="form-control input-sm" placeholder="Latitude" min="0" step="any" name="prj_latitude" id="latitude" type="number" value="<?php echo $GLOBALS['prj_latitude']; ?>">
    </div>

    <div class="form-group">
        <label for="prj_elevation" class="control-label">Elevation</label>
        <input class="form-control input-sm" placeholder="Elevation" min="0" step="any" name="prj_elevation" id="elevation" type="number" value="<?php echo $GLOBALS['prj_elevation']; ?>">
    </div>


    <?php

    if ($op == 0){
    ?>
    <div class="well well-default">
        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" name="form_return" <?php echo ($form_return == 1 ? 'checked="checked"' : ''); ?>> Check to clear form after saving.
            </label>
        </div>
    </div>
    <?php
    }

    if (in_pstc($GLOBALS['ad_ug_name'])){
        ?>
        <input type="hidden" name="ug_id" value="<?php echo $GLOBALS['ad_ug_id']; ?>" />
        <?php
    }
    ?>
    <input type="hidden" name="prj_id" value="<?php echo $GLOBALS['prj_id']; ?>" />
    <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">

</form>

</div>    
<div class="panel-footer">

</div>
</div>

<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCMDx-ejfVStxIBhfqtBuLj98OV79kqbdY" type="text/javascript"></script>
<script>
    var _longitude = <?php echo $GLOBALS['prj_longitude']; ?>;
    var _latitude = <?php echo $GLOBALS['prj_latitude']; ?>;
</script>
<?php 
page_footer();

function load_beneficiaries($pid){
    $sql = "SELECT * FROM psi_project_beneficiaries WHERE prj_id = $pid";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    if (!$res) return;
    while ($row = mysqli_fetch_array($res)){
        $GLOBALS['coop_id'][] = $row['coop_id'];
    }
    mysqli_free_result($res);
}

function load_collaborators($pid){
    $sql = "SELECT * FROM psi_project_collaborators WHERE prj_id = $pid";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    if (!$res) return;
    while ($row = mysqli_fetch_array($res)){
        $GLOBALS['col_id'][] = $row['col_id'];
    }
    mysqli_free_result($res);
}

function load_city_options(){
    $s = '';
    $sql = "SELECT * FROM psi_cities ORDER BY  city_name ASC";
    $res = mysqli_query($GLOBALS['cn'], $sql);

    $s .= '{cid:0, pid:0, name:"None", did:0}';

    if ($res){
        while ($row = mysqli_fetch_array($res)){
            if (strlen($s) > 0){
                $s .= ", \n";
            }
            $s .= '{cid:'.$row['city_id'].', pid:'.$row['province_id'].', name:"'.$row['city_name'].'", did:'.$row['district_id'].'}';
        }
    }
    mysqli_free_result($res);
    $s = '
    var _cities = [
    '.$s.'
    ];
    ';
    return $s;
}

function load_barangay_options(){
    $s = '';
    $sql = "SELECT * FROM psi_barangays ORDER BY barangay_name ASC";
    $res = mysqli_query($GLOBALS['cn'], $sql);

    $s .= '{bid:0, cid:0, name:"None"}';

    if ($res){
        while ($row = mysqli_fetch_array($res)){
            if (strlen($s) > 0){
                $s .= ", \n";
            }
            $s .= '{bid:'.$row['barangay_id'].', cid:'.$row['city_id'].', name:"'.$row['barangay_name'].'"}';
        }
        mysqli_free_result($res);
    }
    $s = '
    var _barangays = [
    '.$s.'
    ];
    ';
    return $s;
}

function get_first_province($pid){
    $id = 0;
    $sql = "SELECT * FROM psi_provinces WHERE region_id = $pid ORDER BY province_name ASC";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    if (!$res) return 0;
    if ($row = mysqli_fetch_array($res)){
        $id = $row['province_id'];
    }
    mysqli_free_result($res);
    return $id;
}


function get_first_city($pid){
    $id = 0;
    $sql = "SELECT * FROM psi_cities WHERE province_id = $pid ORDER BY city_name ASC";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    if (!$res) return 0;
    if ($row = mysqli_fetch_array($res)) {
        $id = $row['city_id'];
    }
    mysqli_free_result($res);
    return $id;
}

function get_first_barangay($pid){
    $id = 0;
    $sql = "SELECT * FROM psi_barangays WHERE city_id = $pid ORDER BY barangay_name ASC";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    if (!$res) return 0;
    if ($row = mysqli_fetch_array($res)){
        $id = $row['barangay_id'];
    }
    mysqli_free_result($res);
    return $id;
}
?>