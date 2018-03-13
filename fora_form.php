<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'fora.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'fora.php');

if ($op == 1){
    if (!can_access('Fora', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Fora', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$GLOBALS['col_id'] = array();
$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_fora", "SELECT * FROM psi_fora WHERE fr_id = ".$id);
    load_collaborators($id);
} else {
    initFormValues('psi_fora');
    $GLOBALS['fr_latitude'] = DEF_LATITUDE;
    $GLOBALS['fr_longitude'] = DEF_LONGITUDE;
}

loadFormCache('psi_fora');

//$sel_providers = getOptions('psi_service_providers', 'sp_name', 'sp_id', $GLOBALS['sp_id'], '', 'WHERE sp_id IN (SELECT sp_id FROM psi_service_provider_services WHERE service_id = 3)');
$sel_type = getOptions('psi_fora_types', 'fr_type_name', 'fr_type_id', $GLOBALS['fr_type_id']);
$sel_usergroup = getOptions('psi_usergroups', 'ug_name', 'ug_id', $GLOBALS['ug_id'], '', "WHERE (ug_name like '%PSTC-%') OR (ug_name like '%RO-%')");
$sel_collaborators = getOptions('psi_collaborators', 'col_name', 'col_id', $GLOBALS['col_id'], '', 'ORDER BY col_name ASC');

$pg_title = "Fora/Trainings/Seminars ($opstr)";
page_header($pg_title, 2);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left"><?php echo $pg_title; ?></h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="fora.php" title="Fora"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="fora_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

            <div class="form-group form-group-sm">
                <label for="fr_type_id" class="control-label">Type</label>
                <select class="form-control input-sm" id="fr_type_id" name="fr_type_id">
                    <?php echo $sel_type; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="fr_requesting_party" class="control-label">Requesting Party/Address *</label>
                <textarea class="form-control input-sm" placeholder="Requesting Party/Address" required="required" name="fr_requesting_party" id="fr_requesting_party" cols="50" rows="4"><?php echo $GLOBALS['fr_requesting_party']; ?></textarea>
            </div>

            <div class="form-group form-group-sm">
                <label for="col_id" class="control-label">Cooperating Agencies</label>
                <select class="form-control input-sm chosen-select" id="col_id" name="col_id[]" multiple="multiple">
                    <?php echo $sel_collaborators; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="fr_sectors" class="control-label">Sectors</label>
                <textarea class="form-control input-sm" placeholder="Sectors" required="required" name="fr_sectors" id="fr_sectors" cols="50" rows="4"><?php echo $GLOBALS['fr_sectors']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="fr_title" class="control-label">Title *</label>
                <input class="form-control input-sm" placeholder="Title" required="required" name="fr_title" id="fr_title" type="text" value="<?php echo $GLOBALS['fr_title']; ?>">
            </div>


            <div class="">
                <div class="form-group">
                    <label for="fr_start" class="control-label">Start *</label>
                    <input class="form-control input-sm datetime_range_start" placeholder="Start" maxlength="10" required="required" name="fr_start" id="fr_start" type="text" value="<?php echo $GLOBALS['fr_start']; ?>">
                </div>

                <div class="form-group">
                    <label for="fr_end" class="control-label">End *</label>
                    <input class="form-control input-sm datetime_range_end" placeholder="End" maxlength="10" required="required" name="fr_end" id="fr_end" type="text" value="<?php echo $GLOBALS['fr_end']; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="fr_location" class="control-label">Venue *</label>
                <textarea class="form-control input-sm" placeholder="Venue" required="required" name="fr_location" id="fr_location" cols="50" rows="4"><?php echo $GLOBALS['fr_location']; ?></textarea>
            </div>

<!--
            <div class="form-group">
                <label for="fr_duration" class="control-label">Duration (Hrs.) *</label>
                <input class="form-control input-sm" placeholder="Duration" maxlength="9" min="0" step="1" required="required" name="fr_duration" id="fr_duration" type="number" value="<?php echo $GLOBALS['fr_duration']; ?>">
            </div>

            <div class="form-group form-group-sm">
                <label for="sp_id" class="control-label">Service Provider *</label>
                <select class="form-control input-sm" id="sp_id" name="sp_id" required="requiired">
                    <?php echo $sel_providers; ?>
                </select>
            </div>
        -->            

        <div class="form-group">
            <label for="fr_cost" class="control-label">Cost</label>
            <input class="form-control input-sm" placeholder="Cost" min="0" step="any" name="fr_cost" id="fr_cost" type="number" value="<?php echo $GLOBALS['fr_cost']; ?>">
        </div>

        <div class="form-group">
            <label for="fr_csf" class="control-label">Overall CSF Rating *</label>
            <input class="form-control input-sm" placeholder="Overall CSF Rating" min="0" step="any" required="required" name="fr_csf" id="fr_csf" type="number" value="<?php echo $GLOBALS['fr_csf']; ?>">
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
            <label for="fr_remarks" class="control-label">Remarks</label>
            <textarea class="form-control input-sm" placeholder="Remarks" name="fr_remarks" id="fr_remarks" cols="50" rows="4"><?php echo $GLOBALS['fr_remarks']; ?></textarea>
        </div>

        <h3><span class="label label-default full-width">Participant Demographics</span></h3>

        <div class="form-group">
            <label for="fr_no_feminine" class="control-label">No. of Feminine Participants *</label>
            <input class="form-control input-sm" placeholder="No. of Feminine Participants" min="0" step="1" required="required" name="fr_no_feminine" id="fr_no_feminine" type="number" value="<?php echo $GLOBALS['fr_no_feminine']; ?>">
        </div>

        <div class="form-group">
            <label for="fr_no_masculine" class="control-label">No. of Masculine Participants *</label>
            <input class="form-control input-sm" placeholder="No. of Masculine Participants" min="0" step="1" required="required" name="fr_no_masculine" id="fr_no_musculine" type="number" value="<?php echo $GLOBALS['fr_no_masculine']; ?>">

        </div>

        <div class="form-group">
            <label for="fr_no_pwd" class="control-label">No. of PWD Participants *</label>
            <input class="form-control input-sm" placeholder="No. of PWD Participants" min="0" step="1" required="required" name="fr_no_pwd" id="fr_no_pwd" type="number" value="<?php echo $GLOBALS['fr_no_pwd']; ?>">
        </div>

        <div class="form-group">
            <label for="fr_no_seniors" class="control-label">No. of Senior Citizen Participants *</label>
            <input class="form-control input-sm" placeholder="No. of Senior Citizen Participants" min="0" step="1" required="required" name="fr_no_seniors" id="fr_no_seniors" type="number" value="<?php echo $GLOBALS['fr_no_seniors']; ?>">
        </div>

        <div class="form-group">
            <label for="fr_no_firms" class="control-label">Total No. of Participating Firms *</label>
            <input class="form-control input-sm" placeholder="Total No. of Participating Firms" min="0" step="1" required="required" name="fr_no_firms" id="fr_no_firms" type="number" value="<?php echo $GLOBALS['fr_no_firms']; ?>">
        </div>

        <h3><span class="label label-default full-width">Map Coordinates</span></h3>
        <div id="map-location-picker" class="form-group map-location-picker">
        </div>

        <div class="form-group">
            <label for="fr_longitude" class="control-label">Longitude</label>
            <input class="form-control input-sm" placeholder="Longitude" min="0" step="any" name="fr_longitude" id="longitude" type="number" value="<?php echo $GLOBALS['fr_longitude']; ?>">
        </div>

        <div class="form-group">
            <label for="fr_latitude" class="control-label">Latitude</label>
            <input class="form-control input-sm" placeholder="Latitude" min="0" step="any" name="fr_latitude" id="latitude" type="number" value="<?php echo $GLOBALS['fr_latitude']; ?>">
        </div>

        <div class="form-group">
            <label for="fr_elevation" class="control-label">Elevation</label>
            <input class="form-control input-sm" placeholder="Elevation" min="0" step="any" name="fr_elevation" id="fr_elevation" type="number" value="<?php echo $GLOBALS['fr_elevation']; ?>">
        </div>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="fr_id" value="<?php echo $GLOBALS['fr_id']; ?>">
        <?php
        if (in_pstc($GLOBALS['ad_ug_name'])){
            ?>
            <input type="hidden" name="ug_id" value="<?php echo $GLOBALS['ad_ug_id']; ?>" />
            <?php
        }
        ?>
    </form>
</div>
<div class="panel-footer">
</div>
</div>
<script src="http://maps.googleapis.com/maps/api/js?key=<?php echo DEF_GOOGLE_MAPS_KEY; ?>" type="text/javascript"></script>
<script>
    var _latitude = <?php echo $GLOBALS['fr_latitude']; ?>;
    var _longitude = <?php echo $GLOBALS['fr_longitude']; ?>;

</script>
<?php 
page_footer();

function load_collaborators($pid){
    $sql = "SELECT * FROM psi_fora_collaborators WHERE fr_id = $pid";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    if (!$res) return;
    while ($row = mysqli_fetch_array($res)){
        $GLOBALS['col_id'][] = $row['col_id'];
    }
    mysqli_free_result($res);
}
?>