<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'consultancies.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'consultancies.php');

if ($op == 1){
    if (!can_access('Consultancies', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Consultancies', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$GLOBALS['region_id'] = $GLOBALS['ad_u_region_id'];
$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_consultancies", "SELECT * FROM psi_consultancies WHERE con_id = $id");
} else {
    initFormValues('psi_consultancies');
    $GLOBALS['region_id'] = $GLOBALS['ad_u_region_id'];
}

loadFormCache('psi_consultancies');

$sel_cooperators = getOptions('psi_cooperators', 'coop_name', 'coop_id', $GLOBALS['coop_id']);
$sel_providers = getOptions('psi_service_providers', 'sp_name', 'sp_id', $GLOBALS['sp_id'], '', 'ORDER BY sp_name ASC');
$sel_type = getOptions('psi_consultancy_types', 'con_type_name', 'con_type_id', $GLOBALS['con_type_id']);
$sel_usergroup = getOptions('psi_usergroups', 'ug_name', 'ug_id', $GLOBALS['ug_id']);
$sel_regions = getOptions('vwpsi_regions', 'region_text', 'region_id', $GLOBALS['region_id'], '', 'ORDER BY region_text ASC');

$pg_title = 'Consultancies ('.$opstr.')';
page_header($pg_title, 2);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left"><?php echo $pg_title; ?></h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="consultancies.php" title="Consultancies"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="consultancies_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

            <?php
            if (strlen($GLOBALS['ad_ug_is_admin']) == 1){
                ?>
                <div class="form-group form-group-sm">
                    <label for="region_id" class="control-label">Region</label>
                    <select class="form-control input-sm" id="region_id" name="region_id">
                        <?php echo $sel_regions; ?>
                    </select>
                </div>
                <?php 
            }
            ?>

            <div class="form-group form-group-sm">
                <label for="sp_id" class="control-label">Cooperator *</label>
                <select class="form-control input-sm" id="coop_id" name="coop_id" required="requiired">
                    <?php echo $sel_cooperators; ?>
                </select>
            </div>


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

            <div class="form-group form-group-sm">
                <label for="ug_id" class="control-label">Implementor *</label>
                <select class="form-control input-sm" id="ug_id" name="ug_id">
                    <?php echo $sel_usergroup; ?>
                </select>
            </div>

            <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
            <input type="hidden" name="con_id" value="<?php echo $GLOBALS['con_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
page_footer();
?>