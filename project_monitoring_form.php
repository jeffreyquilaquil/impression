<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$frm = requestInteger('frm', 'location: '.WEBSITE_URL.'project_monitoring.php?pid='.$pid);

if (($frm != 2) && ($frm != 3)){
    redirect(WEBSITE_URL.'project_monitoring.php?pid='.$pid);
    die();
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_monitoring.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_monitoring.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Monitoring', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Monitoring', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if ($frm == 1){
    $form_name = 'PIS';
} elseif ($frm == 2){
    $form_name = 'Status Report';
} elseif ($frm == 3){
    $form_name = 'Terminal Report';
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_project_monitoring", "SELECT * FROM psi_project_monitoring WHERE prjmon_id = ".$id);
} else {
    initFormValues('psi_project_monitoring');
    $GLOBALS['prjmon_refund_schedule_from'] = '';
    $GLOBALS['prjmon_refund_schedule_to'] = '';
    $GLOBALS['prjmon_liquidation_date'] = '';
    $GLOBALS['prjmon_refund_delay_date'] = '';
    $GLOBALS['prjmon_refund_date'] = '';
    loadDBValues("psi_project_monitoring", "SELECT * FROM psi_project_monitoring WHERE prj_id = ".$pid." ORDER BY prjmon_year DESC, quarter_id DESC LIMIT 1");
    $GLOBALS['prjmon_year'] = date('Y');
}

$opstr.=' '.$form_name;

loadFormCache('psi_project_monitoring');

$page_title = 'Project Monitoring ('.$opstr.')';
page_header($page_title, 1);

$sel_quarters = getOptions('psi_quarters', 'quarter_name', 'quarter_id', $GLOBALS['quarter_id']);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title ?></h3>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="project_monitoring.php?pid=<?php echo $pid; ?>" title="Project Record"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
    </div>
    <div class="panel-body">

        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="project_monitoring_save.php?frm=<?php echo $frm; ?>&amp;op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="well well-default">
            <div class="form-group">
            <label for="prjmon_effectivity" class="control-label">Effectivity *</label>
            &nbsp;&nbsp;<span class="text-danger"><small></small></span>
            <input class="form-control input-sm date-picker" placeholder="Date Tagged" maxlength="10" required="required" name="prjmon_effectivity" id="prjmon_effectivity" type="text" value="<?php echo $GLOBALS['prjmon_effectivity']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_year" class="control-label">Year *</label>
            <input class="form-control input-sm" placeholder="Year" maxlength="4" min="1800" max="<?php echo date('Y'); ?>" required="required" name="prjmon_year" id="prjmon_year" type="number" value="<?php echo $GLOBALS['prjmon_year']; ?>">
                        </div>


            <div class="form-group form-group-sm">
            <label for="quarter_id" class="control-label">Quarter</label>
            <select class="form-control input-sm" id="quarter_id" name="quarter_id">
            <?php echo $sel_quarters; ?>
            </select>
            </div>
        </div>

        <?php 
            if ($frm == 1) {
        ?>
        <div class="well well-default">
            <h4>Total Assests </h4>

            <div class="form-group">
            <label for="prjmon_total_assets_land" class="control-label">Land</label>
            <input class="form-control input-sm" placeholder="Land" min="0" step="any" name="prjmon_total_assets_land" id="prjmon_total_assets_land" type="number" value="<?php echo $GLOBALS['prjmon_total_assets_land']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_total_assets_building" class="control-label">Building</label>
            <input class="form-control input-sm" placeholder="Building" min="0" step="any" name="prjmon_total_assets_building" id="prjmon_total_assets_building" type="number" value="<?php echo $GLOBALS['prjmon_total_assets_building']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_total_assets_equipment" class="control-label">Equipment</label>
            <input class="form-control input-sm" placeholder="Equipment" min="0" step="any" name="prjmon_total_assets_equipment" id="prjmon_total_assets_equipment" type="number" value="<?php echo $GLOBALS['prjmon_total_assets_equipment']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_total_assets_working_capital" class="control-label">Working Capital</label>
            <input class="form-control input-sm" placeholder="Working Capital" min="0" step="any" name="prjmon_total_assets_working_capital" id="prjmon_total_assets_working_capital" type="number" value="<?php echo $GLOBALS['prjmon_total_assets_working_capital']; ?>">
                        </div>

        </div>            

        <div class="well well-default">
            <h4>Total Employment Generated (Direct Employment) </h4>

                <h4>Company Hire (Regular)</h4>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_ch_regular_male" class="control-label">Masculine</label>
                        <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prjmon_dir_ch_regular_male" id="prjmon_dir_ch_regular_male" type="number" value="<?php echo $GLOBALS['prjmon_dir_ch_regular_male']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_ch_regular_female" class="control-label">Feminine</label>
                        <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prjmon_dir_ch_regular_female" id="prjmon_dir_ch_regular_female" type="number" value="<?php echo $GLOBALS['prjmon_dir_ch_regular_female']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_ch_regular_pwd" class="control-label">PWD</label>
                        <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prjmon_dir_ch_regular_pwd" id="prjmon_dir_ch_regular_pwd" type="number" value="<?php echo $GLOBALS['prjmon_dir_ch_regular_pwd']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_ch_regular_senior" class="control-label">Senior</label>
                        <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prjmon_dir_ch_regular_senior" id="prjmon_dir_ch_regular_senior" type="number" value="<?php echo $GLOBALS['prjmon_dir_ch_regular_senior']; ?>">
                                                </div>
                    </div>
                </div>

                <h4>Company Hire (Part-Time)</h4>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_ch_part_time_male" class="control-label">Masculine</label>
                        <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prjmon_dir_ch_part_time_male" id="prjmon_dir_ch_part_time_male" type="number" value="<?php echo $GLOBALS['prjmon_dir_ch_part_time_male']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_ch_part_time_female" class="control-label">Feminine</label>
                        <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prjmon_dir_ch_part_time_female" id="prjmon_dir_ch_part_time_female" type="number" value="<?php echo $GLOBALS['prjmon_dir_ch_part_time_female']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_ch_part_time_pwd" class="control-label">PWD</label>
                        <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prjmon_dir_ch_part_time_pwd" id="prjmon_dir_ch_part_time_pwd" type="number" value="<?php echo $GLOBALS['prjmon_dir_ch_part_time_pwd']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_ch_part_time_senior" class="control-label">Senior</label>
                        <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prjmon_dir_ch_part_time_senior" id="prjmon_dir_ch_part_time_senior" type="number" value="<?php echo $GLOBALS['prjmon_dir_ch_part_time_senior']; ?>">
                                                </div>
                    </div>
                </div>

                <h4>Sub-Contractor Hire (Regular)</h4>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_sh_regular_male" class="control-label">Masculine</label>
                        <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prjmon_dir_sh_regular_male" id="prjmon_dir_sh_regular_male" type="number" value="<?php echo $GLOBALS['prjmon_dir_sh_regular_male']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_sh_regular_female" class="control-label">Feminine</label>
                        <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prjmon_dir_sh_regular_female" id="prjmon_dir_sh_regular_female" type="number" value="<?php echo $GLOBALS['prjmon_dir_sh_regular_female']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_sh_regular_pwd" class="control-label">PWD</label>
                        <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prjmon_dir_sh_regular_pwd" id="prjmon_dir_sh_regular_pwd" type="number" value="<?php echo $GLOBALS['prjmon_dir_sh_regular_pwd']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_sh_regular_senior" class="control-label">Senior</label>
                        <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prjmon_dir_sh_regular_senior" id="prjmon_dir_sh_regular_senior" type="number" value="<?php echo $GLOBALS['prjmon_dir_sh_regular_senior']; ?>">
                                                </div>
                    </div>
                </div>

                <h4>Sub-Contractor Hire (Part-Time)</h4>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_sh_part_time_male" class="control-label">Masculine</label>
                        <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prjmon_dir_sh_part_time_male" id="prjmon_dir_sh_part_time_male" type="number" value="<?php echo $GLOBALS['prjmon_dir_sh_part_time_male']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_sh_part_time_female" class="control-label">Feminine</label>
                        <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prjmon_dir_sh_part_time_female" id="prjmon_dir_sh_part_time_female" type="number" value="<?php echo $GLOBALS['prjmon_dir_sh_part_time_female']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_sh_part_time_pwd" class="control-label">PWD</label>
                        <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prjmon_dir_sh_part_time_pwd" id="prjmon_dir_sh_part_time_pwd" type="number" value="<?php echo $GLOBALS['prjmon_dir_sh_part_time_pwd']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjmon_dir_sh_part_time_senior" class="control-label">Senior</label>
                        <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prjmon_dir_sh_part_time_senior" id="prjmon_dir_sh_part_time_senior" type="number" value="<?php echo $GLOBALS['prjmon_dir_sh_part_time_senior']; ?>">
                                                </div>
                    </div>
                </div>
        </div>            

        <div class="well well-default">
            <h4>Total Employment Generated (Indirect Employment) </h4>
            <h4>Forward</h4>
            <div class="row">
                <div class="col-md-3 col-sm-3">
                    <div class="form-group">
                    <label for="prjmon_indir_forward_male" class="control-label">Masculine</label>
                    <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prjmon_indir_forward_male" id="prjmon_indir_forward_male" type="number" value="<?php echo $GLOBALS['prjmon_indir_forward_male']; ?>">
                                        </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="form-group">
                    <label for="prjmon_indir_forward_female" class="control-label">Feminine</label>
                    <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prjmon_indir_forward_female" id="prjmon_indir_forward_female" type="number" value="<?php echo $GLOBALS['prjmon_indir_forward_female']; ?>">
                                        </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="form-group">
                    <label for="prjmon_indir_forward_pwd" class="control-label">PWD</label>
                    <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prjmon_indir_forward_pwd" id="prjmon_indir_forward_pwd" type="number" value="<?php echo $GLOBALS['prjmon_indir_forward_pwd']; ?>">
                                        </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="form-group">
                    <label for="prjmon_indir_forward_senior" class="control-label">Senior</label>
                    <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prjmon_indir_forward_senior" id="prjmon_indir_forward_senior" type="number" value="<?php echo $GLOBALS['prjmon_indir_forward_senior']; ?>">
                                        </div>
                </div>
            </div>

            <h4>Backward</h4>
            <div class="row">
                <div class="col-md-3 col-sm-3">
                    <div class="form-group">
                    <label for="prjmon_indir_backward_male" class="control-label">Masculine</label>
                    <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prjmon_indir_backward_male" id="prjmon_indir_backward_male" type="number" value="<?php echo $GLOBALS['prjmon_indir_backward_male']; ?>">
                                        </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="form-group">
                    <label for="prjmon_indir_backward_female" class="control-label">Feminine</label>
                    <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prjmon_indir_backward_female" id="prjmon_indir_backward_female" type="number" value="<?php echo $GLOBALS['prjmon_indir_backward_female']; ?>">
                                        </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="form-group">
                    <label for="prjmon_indir_backward_pwd" class="control-label">PWD</label>
                    <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prjmon_indir_backward_pwd" id="prjmon_indir_backward_pwd" type="number" value="<?php echo $GLOBALS['prjmon_indir_backward_pwd']; ?>">
                                        </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="form-group">
                    <label for="prjmon_indir_backward_senior" class="control-label">Senior</label>
                    <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prjmon_indir_backward_senior" id="prjmon_indir_backward_senior" type="number" value="<?php echo $GLOBALS['prjmon_indir_backward_senior']; ?>">
                                        </div>
                </div>
            </div>
        </div>            

        <div class="well well-default">
            <h4>Total Volume of Production </h4>
            <div class="form-group">
            <label for="prjmon_volume_production_local" class="control-label">Local</label>
            <input class="form-control input-sm" placeholder="Local" min="0" step="any" name="prjmon_volume_production_local" id="prjmon_volume_production_local" type="number" value="<?php echo $GLOBALS['prjmon_volume_production_local']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_volume_production_export" class="control-label">Export</label>
            <input class="form-control input-sm" placeholder="Export" min="0" step="any" name="prjmon_volume_production_export" id="prjmon_volume_production_export" type="number" value="<?php echo $GLOBALS['prjmon_volume_production_export']; ?>">
                        </div>
        </div>            

        <div class="well well-default">
            <h4>Total Gross Sales </h4>
            <div class="form-group">
            <label for="prjmon_gross_sales_local" class="control-label">Local</label>
            <input class="form-control input-sm" placeholder="Local" min="0" step="any" name="prjmon_gross_sales_local" id="prjmon_gross_sales_local" type="number" value="<?php echo $GLOBALS['prjmon_gross_sales_local']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_gross_sales_export" class="control-label">Export</label>
            <input class="form-control input-sm" placeholder="Export" min="0" step="any" name="prjmon_gross_sales_export" id="prjmon_gross_sales_export" type="number" value="<?php echo $GLOBALS['prjmon_gross_sales_export']; ?>">
                        </div>
        </div>            


        <div class="well well-default">
            <h4>Countries of Destination </h4>
            <div class="form-group">
            <textarea class="form-control input-sm" placeholder="Countries of Destination" name="prjmon_countries_of_destination" id="prjmon_countries_of_destination" cols="50" rows="4"><?php echo $GLOBALS['prjmon_countries_of_destination']; ?></textarea>
            </div>
        </div>

        <div class="well well-default">
            <h4>Assistance Obtained From DOST </h4>
            <ul style="list-style-type:none; margin-left:-40px;">
                <li>
                    A. 1 Production Technology
                </li>
                <li>
                    <ul style="list-style-type:none; margin-left:-12px;"
                    <li>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="prjmon_assistance_process" id="prjmon_assistance_process" value="1" <?php echo checkBox($GLOBALS['prjmon_assistance_process']); ?>> 
                                A. 1.1 Process
                            </label>
                        </div>
                    </li>
                    <li>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="prjmon_assistance_equipment" id="prjmon_assistance_equipment" value="1" <?php echo checkBox($GLOBALS['prjmon_assistance_equipment']); ?>> A. 1.2 Equipment
                            </label>
                        </div>
                    </li>
                    <li>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="prjmon_assistance_quality_control" id="prjmon_assistance_quality_control" value="1" <?php echo checkBox($GLOBALS['prjmon_assistance_quality_control']); ?>> 
                                A. 1.2 Quality Control / Laboratory Testing / Analysis
                            </label>
                        </div>
                    </li>
                    </ul>
                </li>
                <li>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="prjmon_assistance_packaging_labeling" id="prjmon_assistance_packaging_labeling" value="1" <?php echo checkBox($GLOBALS['prjmon_assistance_packaging_labeling']); ?>> 
                            A. 2 Packaging / Labeling
                        </label>
                    </div>
                </li>
                <li>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="prjmon_assistance_post_harvest" id="prjmon_assistance_post_harvest" value="1" <?php echo checkBox($GLOBALS['prjmon_assistance_post_harvest']); ?>> 
                            A. 3 Post-Harvest
                        </label>
                    </div>
                </li>
                <li>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="prjmon_assistance_marketing" id="prjmon_assistance_marketing" value="1" <?php echo checkBox($GLOBALS['prjmon_assistance_marketing']); ?>> 
                            A. 4 Marketing Assistance
                        </label>
                    </div>
                </li>
                <li>
                    <div class="form-group">
                        A. 5 Human Resource Training
                        <textarea class="form-control input-sm" placeholder="Human Resource Training" name="prjmon_assistance_training" id="prjmon_assistance_training" cols="50" rows="3"><?php echo $GLOBALS['prjmon_assistance_training']; ?></textarea>
                    </div>
                </li>
                <li>
                    <div class="form-group">
                        A. 6 Consultancy Service
                        <textarea class="form-control input-sm" placeholder="Consultancy Service" name="prjmon_assistance_consultancy" id="prjmon_assistance_consultancy" cols="50" rows="3"><?php echo $GLOBALS['prjmon_assistance_consultancy']; ?></textarea>
                    </div>
                </li>
                <li>
                    <div class="form-group">
                        A. 7 Others (FPD Permit, LGU Registration, Bar Coding)
                        <textarea class="form-control input-sm" placeholder="Others" name="prjmon_assistance_others" id="prjmon_assistance_others" cols="50" rows="3"><?php echo $GLOBALS['prjmon_assistance_others']; ?></textarea>
                    </div>
                </li>
            </ul>
        </div>

        <?php 
                
            } 

            if (($frm == 2) || ($frm == 3)) {
        ?>
        <div class="well well-default">
            <h4>Expected Output Vs. Actual Accomplishment.</h4>

            <div class="form-group">
            <label for="prjmon_expected_output" class="control-label">Expected Output</label>
            <textarea class="form-control input-sm" placeholder="Expected Output" name="prjmon_expected_output" id="prjmon_expected_output" cols="50" rows="4"><?php echo $GLOBALS['prjmon_expected_output']; ?></textarea>
            </div>


            <div class="form-group">
            <label for="prjmon_actual_accomplishment" class="control-label">Actual Accomplishment</label>
            <textarea class="form-control input-sm" placeholder="Actual Accomplishment" name="prjmon_actual_accomplishment" id="prjmon_actual_accomplishment" cols="50" rows="4"><?php echo $GLOBALS['prjmon_actual_accomplishment']; ?></textarea>
            </div>
            

            <div class="form-group">
            <label for="prjmon_output_remarks" class="control-label">Remarks/Justification</label>
            <textarea class="form-control input-sm" placeholder="Remarks/Justification" name="prjmon_output_remarks" id="prjmon_output_remarks" cols="50" rows="4"><?php echo $GLOBALS['prjmon_output_remarks']; ?></textarea>
            </div>
        </div>

        <?php 
                
            } 

            if ($frm == 2) {

        ?>
        <div class="well well-default">
            <h4>Status Of Liquidation </h4>

            <div class="form-group">
            <label for="prjmon_liquidation_cost" class="control-label">Total Approved Project Cost</label>
            <input class="form-control input-sm" placeholder="Total Approved Project Cost" min="0" step="any" name="prjmon_liquidation_cost" id="prjmon_liquidation_cost" type="number" value="<?php echo $GLOBALS['prjmon_liquidation_cost']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_liquidation_used" class="control-label">Amount Utilized Per Financial Report</label>
            <input class="form-control input-sm" placeholder="Amount Utilized per Financial Report" min="0" step="any" name="prjmon_liquidation_used" id="prjmon_liquidation_used" type="number" value="<?php echo $GLOBALS['prjmon_liquidation_used']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_liquidation_date" class="control-label">as of</label>
            &nbsp;&nbsp;<span class="text-danger"><small></small></span>
            <input class="form-control input-sm date-picker" placeholder="as of" maxlength="10" name="prjmon_liquidation_date" id="prjmon_liquidation_date" type="text" value="<?php echo $GLOBALS['prjmon_liquidation_date']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_liquidation_remarks" class="control-label">Remarks On Status Of liquidation</label>
            <textarea class="form-control input-sm" placeholder="Remarks on status of liquidation" name="prjmon_liquidation_remarks" id="prjmon_liquidation_remarks" cols="50" rows="4"><?php echo $GLOBALS['prjmon_liquidation_remarks']; ?></textarea>
            </div>
        </div>
        <?php
            if ($GLOBALS['prj_type_id'] == 6){
        ?>


        <div class="well well-default">
            <h4>Status Of Refund </h4>

            <div class="form-group">
            <label for="prjmon_refund_amount" class="control-label">Total Amount To Be Refunded</label>
            <input class="form-control input-sm" placeholder="Total Amount To Be Refunded" min="0" step="any" name="prjmon_refund_amount" id="prjmon_refund_amount" type="number" value="<?php echo $GLOBALS['prjmon_refund_amount']; ?>">
                        </div>

            <div class="input-daterange">
                <div class="form-group">
                <label for="prjmon_refund_schedule_from" class="control-label">Approved Refund Schedule (From)</label>
                &nbsp;&nbsp;<span class="text-danger"><small></small></span>
                <input class="form-control input-sm" placeholder="Approved Refund Schedule (From)" maxlength="10" required="required" name="prjmon_refund_schedule_from" id="prjmon_refund_schedule_from" type="text" value="<?php echo $GLOBALS['prjmon_refund_schedule_from']; ?>">
                                </div>

                <div class="form-group">
                <label for="prjmon_refund_schedule_to" class="control-label">Approved Refund Schedule (To)</label>
                &nbsp;&nbsp;<span class="text-danger"><small></small></span>
                <input class="form-control input-sm" placeholder="Approved Refund Schedule (To)" maxlength="10" required="required" name="prjmon_refund_schedule_to" id="prjmon_refund_schedule_to" type="text" value="<?php echo $GLOBALS['prjmon_refund_schedule_to']; ?>">
                                </div>
            </div>

            <div class="form-group">
            <label for="prjmon_refund_amount_due" class="control-label">Total Amount Already Due</label>
            <input class="form-control input-sm" placeholder="Total Amount Already Due" min="0" step="any" name="prjmon_refund_amount_due" id="prjmon_refund_amount_due" type="number" value="<?php echo $GLOBALS['prjmon_refund_amount_due']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_refund_date" class="control-label">as of</label>
            &nbsp;&nbsp;<span class="text-danger"><small></small></span>
            <input class="form-control input-sm date-picker" placeholder="as of" maxlength="10" name="prjmon_refund_date" id="prjmon_refund_date" type="text" value="<?php echo $GLOBALS['prjmon_refund_date']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_refund_refunded" class="control-label">Total Amount Refunded</label>
            <input class="form-control input-sm" placeholder="Total Amount Refunded" min="0" step="any" name="prjmon_refund_refunded" id="prjmon_refund_refunded" type="number" value="<?php echo $GLOBALS['prjmon_refund_refunded']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_refund_unsettled" class="control-label">Unsettled Refund</label>
            <input class="form-control input-sm" placeholder="Unsettled Refund" min="0" step="any" name="prjmon_refund_unsettled" id="prjmon_refund_unsettled" type="number" value="<?php echo $GLOBALS['prjmon_refund_unsettled']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_refund_delay_date" class="control-label">Refund Delayed Since</label>
            &nbsp;&nbsp;<span class="text-danger"><small></small></span>
            <input class="form-control input-sm date-picker" placeholder="Refund Delayed Since" maxlength="10" name="prjmon_refund_delay_date" id="prjmon_refund_delay_date" type="text" value="<?php echo $GLOBALS['prjmon_refund_delay_date']; ?>">
                        </div>
        </div>

        <?php
            }
        ?>

        <div class="well well-default">
            <h4>Volume and value of production including sales generated. </h4>

            <div class="form-group">
            <label for="prjmon_volume_product_name" class="control-label">Name of Product</label>
            <input class="form-control input-sm" placeholder="Name of Product" name="prjmon_volume_product_name" id="prjmon_volume_product_name" type="text" maxlength="255" value="<?php echo $GLOBALS['prjmon_volume_product_name']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_volume_of_production" class="control-label">Volume of Production</label>
            <input class="form-control input-sm" placeholder="Volume of Production" min="0" step="any" name="prjmon_volume_of_production" id="prjmon_volume_of_production" type="number" value="<?php echo $GLOBALS['prjmon_volume_of_production']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_volume_gross_sales" class="control-label">Gross Sales</label>
            <input class="form-control input-sm" placeholder="Gross Sales" min="0" step="any" name="prjmon_volume_gross_sales" id="prjmon_volume_gross_sales" type="number" value="<?php echo $GLOBALS['prjmon_volume_gross_sales']; ?>">
                        </div>
        </div>

        <div class="well well-default">
            <h4>No. of new employment generated from the project. </h4>

            <div class="form-group">
            <label for="prjmon_emp_total" class="control-label">No. of Employees</label>
            <input class="form-control input-sm" placeholder="No. of Employees" min="0" step="1" name="prjmon_emp_total" id="prjmon_emp_total" type="number" value="<?php echo $GLOBALS['prjmon_emp_total']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_emp_male" class="control-label">No. of Masculine</label>
            <input class="form-control input-sm" placeholder="No. of Masculine" min="0" step="1" name="prjmon_emp_male" id="prjmon_emp_male" type="number" value="<?php echo $GLOBALS['prjmon_emp_male']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_emp_female" class="control-label">No. of Feminine</label>
            <input class="form-control input-sm" placeholder="No. of Feminine" min="0" step="1" name="prjmon_emp_female" id="prjmon_emp_female" type="number" value="<?php echo $GLOBALS['prjmon_emp_female']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjmon_emp_pwd" class="control-label">No. of PWD</label>
            <input class="form-control input-sm" placeholder="No. of PWD" min="0" step="1" name="prjmon_emp_pwd" id="prjmon_emp_pwd" type="number" value="<?php echo $GLOBALS['prjmon_emp_pwd']; ?>">
                        </div>
        </div>

        <div class="well well-default">
            <h4>No. of new indirect employment from the project. </h4>
            <h4>Forward</h4>
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                    <label for="prjmon_emp_indirect_forward_male" class="control-label">Masculine</label>
                    <input class="form-control input-sm" placeholder="Masculine" min="0" step="1" name="prjmon_emp_indirect_forward_male" id="prjmon_emp_indirect_forward_male" type="number" value="<?php echo $GLOBALS['prjmon_emp_indirect_forward_male']; ?>">
                                        </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                    <label for="prjmon_emp_indirect_forward_female" class="control-label">Feminine</label>
                    <input class="form-control input-sm" placeholder="Feminine" min="0" step="1" name="prjmon_emp_indirect_forward_female" id="prjmon_emp_indirect_forward_female" type="number" value="<?php echo $GLOBALS['prjmon_emp_indirect_forward_female']; ?>">
                                        </div>
                </div>
            </div>

            <h4>Backward</h4>
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                    <label for="prjmon_emp_indirect_backward_male" class="control-label">Masculine</label>
                    <input class="form-control input-sm" placeholder="Masculine" min="0" step="1" name="prjmon_emp_indirect_backward_male" id="prjmon_emp_indirect_backward_male" type="number" value="<?php echo $GLOBALS['prjmon_emp_indirect_backward_male']; ?>">
                                        </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                    <label for="prjmon_emp_indirect_backward_female" class="control-label">Feminine</label>
                    <input class="form-control input-sm" placeholder="Feminine" min="0" step="1" name="prjmon_emp_indirect_backward_female" id="prjmon_emp_indirect_backward_female" type="number" value="<?php echo $GLOBALS['prjmon_emp_indirect_backward_female']; ?>">
                                        </div>
                </div>
            </div>
        </div>            

        <div class="well well-default">
            <h4>List of Market Penetrated. </h4>

            <div class="form-group">
            <label for="prjmon_market_existing" class="control-label">Existing Market</label>
            <textarea class="form-control input-sm" placeholder="Existing Market" name="prjmon_market_existing" id="prjmon_market_existing" cols="50" rows="4"><?php echo $GLOBALS['prjmon_market_existing']; ?></textarea>
            </div>
            <div class="form-group">
            <label for="prjmon_market_new" class="control-label">New Market</label>
            <textarea class="form-control input-sm" placeholder="New Market" name="prjmon_market_new" id="prjmon_market_new" cols="50" rows="4"><?php echo $GLOBALS['prjmon_market_new']; ?></textarea>
            </div>
        </div>


        <div class="well well-default">
            <h4>Improvements in production efficiency.
            <small><br>(Includes quantitative indicators on improvement in number and quality of materials; number and value of produce; waste minimization; reject reduction, etc.)</small></h4>

            <div class="form-group">
            <textarea class="form-control input-sm" placeholder="Improvements in production efficiency." name="prjmon_improvement_production_efficiency" id="prjmon_improvement_production_efficiency" cols="50" rows="4"><?php echo $GLOBALS['prjmon_improvement_production_efficiency']; ?></textarea>
            </div>
        </div>

        <div class="well well-default">
            <h4>Problems Met &amp; Actions Taken During Project Implementation. </h4>

            <div class="form-group">
            <label for="prjmon_problems_met" class="control-label">Problems Met</label>
            <textarea class="form-control input-sm" placeholder="Problems Met" name="prjmon_problems_met" id="prjmon_problems_met" cols="50" rows="4"><?php echo $GLOBALS['prjmon_problems_met']; ?></textarea>
            </div>
            <div class="form-group">
            <label for="prjmon_actions_taken" class="control-label">Actions Taken</label>
            <textarea class="form-control input-sm" placeholder="Actions Taken" name="prjmon_actions_taken" id="prjmon_actions_taken" cols="50" rows="4"><?php echo $GLOBALS['prjmon_actions_taken']; ?></textarea>
            </div>
        </div>
        <div class="well well-default">
            <div class="form-group">
            <label for="prjmon_improvement_action_plan" class="control-label">Action/Plan For The Improvement Of The Project's Operation. </label>
            <textarea class="form-control input-sm" placeholder="Action/Plan For The Improvement Of The Project's Operation" name="prjmon_improvement_action_plan" id="prjmon_improvement_action_plan" cols="50" rows="4"><?php echo $GLOBALS['prjmon_improvement_action_plan']; ?></textarea>
            </div>
        </div>
        <?php 
            } 
            if ($frm == 3) {
        ?>
        <div class="well well-default">
            <h4>Problems Met &amp; Actions Taken During Project Implementation. </h4>

            <div class="form-group">
            <label for="prjmon_problems_met" class="control-label">Problems Met</label>
            <textarea class="form-control input-sm" placeholder="Problems Met" name="prjmon_problems_met" id="prjmon_problems_met" cols="50" rows="4"><?php echo $GLOBALS['prjmon_problems_met']; ?></textarea>
            </div>
            <div class="form-group">
            <label for="prjmon_actions_taken" class="control-label">Actions Taken</label>
            <textarea class="form-control input-sm" placeholder="Actions Taken" name="prjmon_actions_taken" id="prjmon_actions_taken" cols="50" rows="4"><?php echo $GLOBALS['prjmon_actions_taken']; ?></textarea>
            </div>
        </div>
        
        <div class="well well-default">
            <div class="form-group">
            <label for="prjmon_status_of_funds" class="control-label">Status Of Funds And Record Acquired Thru The Project.</label>
            <textarea class="form-control input-sm" placeholder="Status Of Funds And Record Acquired Thru The Project" name="prjmon_status_of_funds" id="prjmon_status_of_funds" cols="50" rows="4"><?php echo $GLOBALS['prjmon_status_of_funds']; ?></textarea>
            </div>
        </div>
        
        <div class="well well-default">
            <div class="form-group">
            <label for="prjmon_reasons_for_termination" class="control-label">Reason for Termination/Withdrawal.</label>
            <textarea class="form-control input-sm" placeholder="Reason for Termination/Withdrawal" name="prjmon_reasons_for_termination" id="prjmon_reasons_for_termination" cols="50" rows="4"><?php echo $GLOBALS['prjmon_reasons_for_termination']; ?></textarea>
            </div>
        </div>
        
        <div class="well well-default">
            <div class="form-group">
            <label for="prjmon_final_obligation" class="control-label">Final Obligation of the Beneficiary.</label>
            <textarea class="form-control input-sm" placeholder="Final Obligation of the Beneficiary" name="prjmon_final_obligation" id="prjmon_final_obligation" cols="50" rows="4"><?php echo $GLOBALS['prjmon_final_obligation']; ?></textarea>
            </div>
        </div>
        
        <div class="well well-default">
            <div class="form-group">
            <label for="prjmon_impact_of_intervention" class="control-label">Impact of Intervention.</label>
            <textarea class="form-control input-sm" placeholder="Impact of Intervention" name="prjmon_impact_of_intervention" id="prjmon_impact_of_intervention" cols="50" rows="4"><?php echo $GLOBALS['prjmon_impact_of_intervention']; ?></textarea>
            </div>
        </div>
        
        <div class="well well-default">
            <div class="form-group">
            <label for="prjmon_final_recommendation" class="control-label">Final Recommendation.</label>
            <textarea class="form-control input-sm" placeholder="Final Recommendation" name="prjmon_final_recommendation" id="prjmon_final_recommendation" cols="50" rows="4"><?php echo $GLOBALS['prjmon_final_recommendation']; ?></textarea>
            </div>
        </div>

        <?php 
            }
        ?>


        <div class="well well-default">
            <div class="form-group">
            <label for="prjmon_remarks" class="control-label">Remarks</label>
            <textarea class="form-control input-sm" placeholder="Remarks" name="prjmon_remarks" id="prjmon_remarks" cols="50" rows="4"><?php echo $GLOBALS['prjmon_remarks']; ?></textarea>
            </div>
        </div>


        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="prjform_id" value="<?php echo $frm; ?>">
        <input type="hidden" name="prj_id" value="<?php echo $pid; ?>">
        <input type="hidden" name="prjmon_id" value="<?php echo $GLOBALS['prjmon_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();

?>