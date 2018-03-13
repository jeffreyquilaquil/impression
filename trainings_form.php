<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'trainings.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'trainings.php');

if ($op == 1){
    if (!can_access('Trainings', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Trainings', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_trainings", "SELECT * FROM psi_trainings WHERE tr_id = ".$id);
} else {
    initFormValues('psi_trainings');
    $GLOBALS['tr_latitude'] = DEF_LATITUDE;
    $GLOBALS['tr_longitude'] = DEF_LONGITUDE;
}

loadFormCache('psi_trainings');

$sel_providers = getOptions('psi_service_providers', 'sp_name', 'sp_id', $GLOBALS['sp_id'], '', 'WHERE sp_id IN (SELECT sp_id FROM psi_service_provider_services WHERE service_id = 3)');
$sel_usergroup = getOptions('psi_usergroups', 'ug_name', 'ug_id', $GLOBALS['ug_id']);


page_header('Trainings ('.$opstr.')', 2);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Trainings (<?php echo $opstr; ?>) </h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="trainings.php" title="Trainings"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="trainings_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group">
        <label for="tr_title" class="control-label">Training Title *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Training Title" required="required" name="tr_title" id="tr_title" type="text" value="<?php echo $GLOBALS['tr_title']; ?>">
                </div>

        <div class="input-daterange">
        <div class="form-group">
        <label for="tr_start" class="control-label">Training Start *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Training Start" maxlength="10" required="required" name="tr_start" id="tr_start" type="text" value="<?php echo $GLOBALS['tr_start']; ?>">
                </div>

        <div class="form-group">
        <label for="tr_end" class="control-label">Training End *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Training End" maxlength="10" required="required" name="tr_end" id="tr_end" type="text" value="<?php echo $GLOBALS['tr_end']; ?>">
                </div>
        </div>

        <div class="form-group">
        <label for="tr_duration" class="control-label">Duration (hrs.) *</label>
        <input class="form-control input-sm" placeholder="Duration" maxlength="9" min="0" step="1" required="required" name="tr_duration" id="tr_duration" type="number" value="<?php echo $GLOBALS['tr_duration']; ?>">
                </div>

        <div class="form-group form-group-sm">
        <label for="sp_id" class="control-label">Service Provider *</label>
        <select class="form-control input-sm" id="sp_id" name="sp_id" required="requiired">
        <?php echo $sel_providers; ?>
        </select>
        </div>

        <div class="form-group">
        <label for="tr_cost" class="control-label">Training Cost</label>
        <input class="form-control input-sm" placeholder="Training Cost" min="0" step="any" name="tr_cost" id="tr_cost" type="number" value="<?php echo $GLOBALS['tr_cost']; ?>">
                </div>

        <div class="form-group">
        <label for="tr_location" class="control-label">Location *</label>
        <textarea class="form-control input-sm" placeholder="Location" required="required" name="tr_location" id="tr_location" cols="50" rows="4"><?php echo $GLOBALS['tr_location']; ?></textarea>
        </div>

        <div class="form-group">
        <label for="tr_csf" class="control-label">Overall CSF Rating *</label>
        <input class="form-control input-sm" placeholder="Overall CSF Rating" min="0" step="any" required="required" name="tr_csf" id="tr_csf" type="number" value="<?php echo $GLOBALS['tr_csf']; ?>">
                </div>

        <div class="form-group form-group-sm">
        <label for="ug_id" class="control-label">Implementor *</label>
        <select class="form-control input-sm" id="ug_id" name="ug_id">
        <?php echo $sel_usergroup; ?>
        </select>
        </div>

        <h3><span class="label label-default full-width">Participant Demographics</span></h3>

        <div class="form-group">
        <label for="tr_no_feminine" class="control-label">No. of Feminine Participants *</label>
        <input class="form-control input-sm" placeholder="No. of Feminine Participants" min="0" step="1" required="required" name="tr_no_feminine" id="tr_no_feminine" type="number" value="<?php echo $GLOBALS['tr_no_feminine']; ?>">
                </div>

        <div class="form-group">
        <label for="tr_no_musculine" class="control-label">No. of Masculine Participants *</label>
        <input class="form-control input-sm" placeholder="No. of Masculine Participants" min="0" step="1" required="required" name="tr_no_musculine" id="tr_no_musculine" type="number" value="<?php echo $GLOBALS['tr_no_musculine']; ?>">
                </div>

        <div class="form-group">
        <label for="tr_no_pwd" class="control-label">No. of PWD Participants *</label>
        <input class="form-control input-sm" placeholder="No. of PWD Participants" min="0" step="1" required="required" name="tr_no_pwd" id="tr_no_pwd" type="number" value="<?php echo $GLOBALS['tr_no_pwd']; ?>">
                </div>

        <div class="form-group">
        <label for="tr_no_seniors" class="control-label">No. of Senior Citizen Participants *</label>
        <input class="form-control input-sm" placeholder="No. of Senior Citizen Participants" min="0" step="1" required="required" name="tr_no_seniors" id="tr_no_seniors" type="number" value="<?php echo $GLOBALS['tr_no_seniors']; ?>">
                </div>

        <div class="form-group">
        <label for="tr_no_firms" class="control-label">Total No. of Participating Firms *</label>
        <input class="form-control input-sm" placeholder="Total No. of Participating Firms" min="0" step="1" required="required" name="tr_no_firms" id="tr_no_firms" type="number" value="<?php echo $GLOBALS['tr_no_firms']; ?>">
                </div>

        <h3><span class="label label-default full-width">Training Map Coordinates</span></h3>
        <div id="map-location-picker" class="form-group map-location-picker">
        </div>

        <div class="form-group">
        <label for="tr_longitude" class="control-label">Longitude</label>
        <input class="form-control input-sm" placeholder="Longitude" min="0" step="any" name="tr_longitude" id="longitude" type="number" value="<?php echo $GLOBALS['tr_longitude']; ?>">
                </div>

        <div class="form-group">
        <label for="tr_latitude" class="control-label">Latitude</label>
        <input class="form-control input-sm" placeholder="Latitude" min="0" step="any" name="tr_latitude" id="latitude" type="number" value="<?php echo $GLOBALS['tr_latitude']; ?>">
                </div>

        <div class="form-group">
        <label for="tr_elevation" class="control-label">Elevation</label>
        <input class="form-control input-sm" placeholder="Elevation" min="0" step="any" name="tr_elevation" id="tr_elevation" type="number" value="<?php echo $GLOBALS['tr_elevation']; ?>">
                </div>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="tr_id" value="<?php echo $GLOBALS['tr_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<script src="http://maps.googleapis.com/maps/api/js?key=<?php echo DEF_GOOGLE_MAPS_KEY; ?>" type="text/javascript"></script>
<script>
var _latitude = <?php echo $GLOBALS['tr_latitude']; ?>;
var _longitude = <?php echo $GLOBALS['tr_longitude']; ?>;

</script>
<?php 
    page_footer();
?>