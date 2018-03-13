<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_consultancies.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_consultancies.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Consultancies', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Consultancies', 'add')){
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
    loadDBValues("psi_consultancies", "SELECT * FROM psi_consultancies WHERE con_id = $id");
} else {
    initFormValues('psi_consultancies');
}

loadFormCache('psi_consultancies');

$sel_providers = getOptions('psi_service_providers', 'sp_name', 'sp_id', $GLOBALS['sp_id'], '', 'ORDER BY sp_name ASC');
$sel_type = getOptions('psi_consultancy_types', 'con_type_name', 'con_type_id', $GLOBALS['con_type_id']);
$sel_usergroup = getOptions('psi_usergroups', 'ug_name', 'ug_id', $GLOBALS['ug_id']);

$page_title = 'Project Consultancies ('.$opstr.')';
page_header($page_title, 1);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="project_consultancies.php?pid=<?php echo $pid; ?>" title="Project Consultancies"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="project_consultancies_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group form-group-sm">
        <label for="sp_id" class="control-label">Service Provider *</label>
        <select class="form-control input-sm" id="sp_id" name="sp_id" required="requiired">
        <?php echo $sel_providers; ?>
        </select>
        </div>

        <div class="form-group form-group-sm">
        <label for="con_type_id" class="control-label">Consultancy Type</label>
        <select class="form-control input-sm" id="con_type_id" name="con_type_id">
        <?php echo $sel_type; ?>
        </select>
        </div>

        <div class="input-daterange">
        <div class="form-group text-left">
        <label for="con_start" class="control-label">Consultancy Start *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Consultancy Start" maxlength="10" required="required" name="con_start" id="from_date_time" type="text" value="<?php echo $GLOBALS['con_start']; ?>">
                </div>

        <div class="form-group text-left">
        <label for="con_end" class="control-label">Consultancy End *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Consultancy End" maxlength="10" required="required" name="con_end" id="to_date_time" type="text" value="<?php echo $GLOBALS['con_end']; ?>">
                </div>
        </div>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="ug_id" value="<?php echo $GLOBALS['ug_id']; ?>">
        <input type="hidden" name="prj_id" value="<?php echo $pid; ?>">
        <input type="hidden" name="con_id" value="<?php echo $GLOBALS['con_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>