<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_pis.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_pis.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project PIS', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project PIS', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_project_pis", "SELECT * FROM psi_project_pis WHERE prjpis_id = ".$id);
} else {
    initFormValues('psi_project_pis');
    $GLOBALS['prjpis_year'] = date('Y');
}


loadFormCache('psi_project_pis');

$page_title = 'Project PIS ('.$opstr.')';
page_header($page_title, 1);

$sel_semester = getOptions('psi_semesters', 'sem_name', 'sem_id', $GLOBALS['sem_id']);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="project_pis.php?pid=<?php echo $pid; ?>" title="Project PIS"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
    </div>
    <div class="panel-body">

        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="project_pis_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="well well-default">
            <div class="form-group">
            <label for="prjpis_year" class="control-label">Year *</label>
            <input class="form-control input-sm" placeholder="Year" maxlength="4" min="1800" max="<?php echo date('Y'); ?>" required="required" name="prjpis_year" id="prjpis_year" type="number" value="<?php echo $GLOBALS['prjpis_year']; ?>">
                        </div>


            <div class="form-group form-group-sm">
            <label for="sem_id" class="control-label">Semester</label>
            <select class="form-control input-sm" id="sem_id" name="sem_id">
            <?php echo $sel_semester; ?>
            </select>
            </div>
        </div>
        <div class="well well-default">
            <h4>Total Assests</h4>

            <div class="form-group">
            <label for="prjpis_total_assets_land" class="control-label">Land</label>
            <input class="form-control input-sm" placeholder="Land" min="0" step="any" name="prjpis_total_assets_land" id="prjpis_total_assets_land" type="number" value="<?php echo $GLOBALS['prjpis_total_assets_land']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjpis_total_assets_building" class="control-label">Building</label>
            <input class="form-control input-sm" placeholder="Building" min="0" step="any" name="prjpis_total_assets_building" id="prjpis_total_assets_building" type="number" value="<?php echo $GLOBALS['prjpis_total_assets_building']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjpis_total_assets_equipment" class="control-label">Equipment</label>
            <input class="form-control input-sm" placeholder="Equipment" min="0" step="any" name="prjpis_total_assets_equipment" id="prjpis_total_assets_equipment" type="number" value="<?php echo $GLOBALS['prjpis_total_assets_equipment']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjpis_total_assets_working_capital" class="control-label">Working Capital</label>
            <input class="form-control input-sm" placeholder="Working Capital" min="0" step="any" name="prjpis_total_assets_working_capital" id="prjpis_total_assets_working_capital" type="number" value="<?php echo $GLOBALS['prjpis_total_assets_working_capital']; ?>">
                        </div>

        </div>            

        <div class="well well-default">
            <h4>Total Employment Generated (Direct Employment)</h4>

                <h4>Company Hire (Regular)</h4>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_ch_regular_male" class="control-label">Masculine</label>
                        <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prjpis_dir_ch_regular_male" id="prjpis_dir_ch_regular_male" type="number" value="<?php echo $GLOBALS['prjpis_dir_ch_regular_male']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_ch_regular_female" class="control-label">Feminine</label>
                        <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prjpis_dir_ch_regular_female" id="prjpis_dir_ch_regular_female" type="number" value="<?php echo $GLOBALS['prjpis_dir_ch_regular_female']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_ch_regular_pwd" class="control-label">PWD</label>
                        <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prjpis_dir_ch_regular_pwd" id="prjpis_dir_ch_regular_pwd" type="number" value="<?php echo $GLOBALS['prjpis_dir_ch_regular_pwd']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_ch_regular_senior" class="control-label">Senior</label>
                        <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prjpis_dir_ch_regular_senior" id="prjpis_dir_ch_regular_senior" type="number" value="<?php echo $GLOBALS['prjpis_dir_ch_regular_senior']; ?>">
                                                </div>
                    </div>
                </div>

                <h4>Company Hire (Part-Time)</h4>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_ch_part_time_male" class="control-label">Masculine</label>
                        <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prjpis_dir_ch_part_time_male" id="prjpis_dir_ch_part_time_male" type="number" value="<?php echo $GLOBALS['prjpis_dir_ch_part_time_male']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_ch_part_time_female" class="control-label">Feminine</label>
                        <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prjpis_dir_ch_part_time_female" id="prjpis_dir_ch_part_time_female" type="number" value="<?php echo $GLOBALS['prjpis_dir_ch_part_time_female']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_ch_part_time_pwd" class="control-label">PWD</label>
                        <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prjpis_dir_ch_part_time_pwd" id="prjpis_dir_ch_part_time_pwd" type="number" value="<?php echo $GLOBALS['prjpis_dir_ch_part_time_pwd']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_ch_part_time_senior" class="control-label">Senior</label>
                        <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prjpis_dir_ch_part_time_senior" id="prjpis_dir_ch_part_time_senior" type="number" value="<?php echo $GLOBALS['prjpis_dir_ch_part_time_senior']; ?>">
                                                </div>
                    </div>
                </div>

                <h4>Sub-Contractor Hire (Regular)</h4>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_sh_regular_male" class="control-label">Masculine</label>
                        <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prjpis_dir_sh_regular_male" id="prjpis_dir_sh_regular_male" type="number" value="<?php echo $GLOBALS['prjpis_dir_sh_regular_male']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_sh_regular_female" class="control-label">Feminine</label>
                        <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prjpis_dir_sh_regular_female" id="prjpis_dir_sh_regular_female" type="number" value="<?php echo $GLOBALS['prjpis_dir_sh_regular_female']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_sh_regular_pwd" class="control-label">PWD</label>
                        <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prjpis_dir_sh_regular_pwd" id="prjpis_dir_sh_regular_pwd" type="number" value="<?php echo $GLOBALS['prjpis_dir_sh_regular_pwd']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_sh_regular_senior" class="control-label">Senior</label>
                        <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prjpis_dir_sh_regular_senior" id="prjpis_dir_sh_regular_senior" type="number" value="<?php echo $GLOBALS['prjpis_dir_sh_regular_senior']; ?>">
                                                </div>
                    </div>
                </div>

                <h4>Sub-Contractor Hire (Part-Time)</h4>
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_sh_part_time_male" class="control-label">Masculine</label>
                        <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prjpis_dir_sh_part_time_male" id="prjpis_dir_sh_part_time_male" type="number" value="<?php echo $GLOBALS['prjpis_dir_sh_part_time_male']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_sh_part_time_female" class="control-label">Feminine</label>
                        <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prjpis_dir_sh_part_time_female" id="prjpis_dir_sh_part_time_female" type="number" value="<?php echo $GLOBALS['prjpis_dir_sh_part_time_female']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_sh_part_time_pwd" class="control-label">PWD</label>
                        <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prjpis_dir_sh_part_time_pwd" id="prjpis_dir_sh_part_time_pwd" type="number" value="<?php echo $GLOBALS['prjpis_dir_sh_part_time_pwd']; ?>">
                                                </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                        <label for="prjpis_dir_sh_part_time_senior" class="control-label">Senior</label>
                        <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prjpis_dir_sh_part_time_senior" id="prjpis_dir_sh_part_time_senior" type="number" value="<?php echo $GLOBALS['prjpis_dir_sh_part_time_senior']; ?>">
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
                    <label for="prjpis_indir_forward_male" class="control-label">Masculine</label>
                    <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prjpis_indir_forward_male" id="prjpis_indir_forward_male" type="number" value="<?php echo $GLOBALS['prjpis_indir_forward_male']; ?>">
                                        </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="form-group">
                    <label for="prjpis_indir_forward_female" class="control-label">Feminine</label>
                    <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prjpis_indir_forward_female" id="prjpis_indir_forward_female" type="number" value="<?php echo $GLOBALS['prjpis_indir_forward_female']; ?>">
                                        </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="form-group">
                    <label for="prjpis_indir_forward_pwd" class="control-label">PWD</label>
                    <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prjpis_indir_forward_pwd" id="prjpis_indir_forward_pwd" type="number" value="<?php echo $GLOBALS['prjpis_indir_forward_pwd']; ?>">
                                        </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="form-group">
                    <label for="prjpis_indir_forward_senior" class="control-label">Senior</label>
                    <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prjpis_indir_forward_senior" id="prjpis_indir_forward_senior" type="number" value="<?php echo $GLOBALS['prjpis_indir_forward_senior']; ?>">
                                        </div>
                </div>
            </div>

            <h4>Backward</h4>
            <div class="row">
                <div class="col-md-3 col-sm-3">
                    <div class="form-group">
                    <label for="prjpis_indir_backward_male" class="control-label">Masculine</label>
                    <input class="form-control input-sm" placeholder="Masculine" min="0" step="any" name="prjpis_indir_backward_male" id="prjpis_indir_backward_male" type="number" value="<?php echo $GLOBALS['prjpis_indir_backward_male']; ?>">
                                        </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="form-group">
                    <label for="prjpis_indir_backward_female" class="control-label">Feminine</label>
                    <input class="form-control input-sm" placeholder="Feminine" min="0" step="any" name="prjpis_indir_backward_female" id="prjpis_indir_backward_female" type="number" value="<?php echo $GLOBALS['prjpis_indir_backward_female']; ?>">
                                        </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="form-group">
                    <label for="prjpis_indir_backward_pwd" class="control-label">PWD</label>
                    <input class="form-control input-sm" placeholder="PWD" min="0" step="any" name="prjpis_indir_backward_pwd" id="prjpis_indir_backward_pwd" type="number" value="<?php echo $GLOBALS['prjpis_indir_backward_pwd']; ?>">
                                        </div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="form-group">
                    <label for="prjpis_indir_backward_senior" class="control-label">Senior</label>
                    <input class="form-control input-sm" placeholder="Senior" min="0" step="any" name="prjpis_indir_backward_senior" id="prjpis_indir_backward_senior" type="number" value="<?php echo $GLOBALS['prjpis_indir_backward_senior']; ?>">
                                        </div>
                </div>
            </div>
        </div>            

        <div class="well well-default">
            <h4>Total Volume of Production</h4>
            <div class="form-group">
            <label for="prjpis_volume_production_local" class="control-label">Local</label>
            <input class="form-control input-sm" placeholder="Local" min="0" step="any" name="prjpis_volume_production_local" id="prjpis_volume_production_local" type="number" value="<?php echo $GLOBALS['prjpis_volume_production_local']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjpis_volume_production_export" class="control-label">Export</label>
            <input class="form-control input-sm" placeholder="Export" min="0" step="any" name="prjpis_volume_production_export" id="prjpis_volume_production_export" type="number" value="<?php echo $GLOBALS['prjpis_volume_production_export']; ?>">
                        </div>
        </div>            

        <div class="well well-default">
            <h4>Total Gross Sales</h4>
            <div class="form-group">
            <label for="prjpis_gross_sales_local" class="control-label">Local</label>
            <input class="form-control input-sm" placeholder="Local" min="0" step="any" name="prjpis_gross_sales_local" id="prjpis_gross_sales_local" type="number" value="<?php echo $GLOBALS['prjpis_gross_sales_local']; ?>">
                        </div>

            <div class="form-group">
            <label for="prjpis_gross_sales_export" class="control-label">Export</label>
            <input class="form-control input-sm" placeholder="Export" min="0" step="any" name="prjpis_gross_sales_export" id="prjpis_gross_sales_export" type="number" value="<?php echo $GLOBALS['prjpis_gross_sales_export']; ?>">
                        </div>
        </div>            


        <div class="well well-default">
            <h4>Countries of Destination</h4>
            <div class="form-group">
            <textarea class="form-control input-sm" placeholder="Countries of Destination" name="prjpis_countries_of_destination" id="prjpis_countries_of_destination" cols="50" rows="4"><?php echo $GLOBALS['prjpis_countries_of_destination']; ?></textarea>
            </div>
        </div>

        <div class="well well-default">
            <h4>Assistance Obtained From DOST</h4>
            <ul style="list-style-type:none; margin-left:-40px;">
                <li>
                    A. 1 Production Technology
                </li>
                <li>
                    <ul style="list-style-type:none; margin-left:-12px;"
                    <li>
                        <div class="form-group">
                            <input type="checkbox" name="prjpis_assistance_process" id="prjpis_assistance_process" value="1" <?php echo checkBox($GLOBALS['prjpis_assistance_process']); ?>> 
                            A. 1.1 Process
                            <textarea class="form-control input-sm" placeholder="Process" name="prjpis_assistance_process_text" id="prjpis_assistance_process_text" cols="50" rows="3"><?php echo $GLOBALS['prjpis_assistance_process_text']; ?></textarea>
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <input type="checkbox" name="prjpis_assistance_equipment" id="prjpis_assistance_equipment" value="1" <?php echo checkBox($GLOBALS['prjpis_assistance_equipment']); ?>>
                            A. 1.2 Equipment
                        </div>
                    </li>
                    <li>
                        <div class="form-group">
                            <input type="checkbox" name="prjpis_assistance_quality_control" id="prjpis_assistance_quality_control" value="1" <?php echo checkBox($GLOBALS['prjpis_assistance_quality_control']); ?>> 
                            A. 1.2 Quality Control / Laboratory Testing / Analysis
                            <textarea class="form-control input-sm" placeholder="Quality Control / Laboratory Testing / Analysis" name="prjpis_assistance_quality_control_text" id="prjpis_assistance_consultancy_text" cols="50" rows="3"><?php echo $GLOBALS['prjpis_assistance_quality_control_text']; ?></textarea>
                        </div>
                    </li>
                    </ul>
                </li>
                <li>
                    <div class="form-group">
                        <input type="checkbox" name="prjpis_assistance_packaging_labeling" id="prjpis_assistance_packaging_labeling" value="1" <?php echo checkBox($GLOBALS['prjpis_assistance_packaging_labeling']); ?>> 
                        A. 2 Packaging / Labeling
                    </div>
                </li>
                <li>
                    <div class="form-group">
                        <input type="checkbox" name="prjpis_assistance_post_harvest" id="prjpis_assistance_post_harvest" value="1" <?php echo checkBox($GLOBALS['prjpis_assistance_post_harvest']); ?>> 
                        A. 3 Post-Harvest
                        <textarea class="form-control input-sm" placeholder="Post-Harvest" name="prjpis_assistance_post_harvest_text" id="prjpis_assistance_post_harvest_text" cols="50" rows="3"><?php echo $GLOBALS['prjpis_assistance_post_harvest_text']; ?></textarea>
                    </div>
                </li>
                <li>
                    <div class="form-group">
                        <input type="checkbox" name="prjpis_assistance_marketing" id="prjpis_assistance_marketing" value="1" <?php echo checkBox($GLOBALS['prjpis_assistance_marketing']); ?>> 
                        A. 4 Marketing Assistance
                        <textarea class="form-control input-sm" placeholder="Marketing Assistance" name="prjpis_assistance_marketing_text" id="prjpis_assistance_marketing_text" cols="50" rows="3"><?php echo $GLOBALS['prjpis_assistance_marketing_text']; ?></textarea>
                    </div>
                </li>
                <li>
                    <div class="form-group">
                        <input type="checkbox" name="prjpis_assistance_training" id="prjpis_assistance_training" value="1" <?php echo checkBox($GLOBALS['prjpis_assistance_training']); ?>> 
                        A. 5 Human Resource Training
                    </div>
                </li>
                <li>
                    <div class="form-group">
                        <input type="checkbox" name="prjpis_assistance_consultancy" id="prjpis_assistance_consultancy" value="1" <?php echo checkBox($GLOBALS['prjpis_assistance_consultancy']); ?>> 
                        A. 6 Consultancy Service
                    </div>
                </li>
                <li>
                    <div class="form-group">
                        <input type="checkbox" name="prjpis_assistance_others" id="prjpis_assistance_others" value="1" <?php echo checkBox($GLOBALS['prjpis_assistance_others']); ?>> 
                        A. 7 Others (FPD Permit, LGU Registration, Bar Coding)
                        <textarea class="form-control input-sm" placeholder="Others" name="prjpis_assistance_others_text" id="prjpis_assistance_others_text" cols="50" rows="3"><?php echo $GLOBALS['prjpis_assistance_others_text']; ?></textarea>
                    </div>
                </li>
            </ul>
        </div>

        <div class="well well-default">
            <div class="form-group">
            <label for="prjpis_remarks" class="control-label">Remarks</label>
            <textarea class="form-control input-sm" placeholder="Remarks" name="prjpis_remarks" id="prjpis_remarks" cols="50" rows="4"><?php echo $GLOBALS['prjpis_remarks']; ?></textarea>
            </div>
        </div>


        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="prj_id" value="<?php echo $pid; ?>">
        <input type="hidden" name="prjpis_id" value="<?php echo $GLOBALS['prjpis_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();

?>