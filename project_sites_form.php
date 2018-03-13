<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_sites.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_sites.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Sites', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Sites', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

if (!is_project_type($pid, 8)){
    redirect(WEBSITE_URL."projects_view.php?pid=$pid");
}

$GLOBALS['col_id'] = array();
$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_project_sites", "SELECT * FROM psi_project_sites WHERE prj_site_id = ".$id);

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
    initFormValues('psi_project_sites');
    $GLOBALS['prj_site_latitude'] = DEF_LATITUDE;
    $GLOBALS['prj_site_longitude'] = DEF_LONGITUDE;

    $GLOBALS['province_id'] = get_first_province($GLOBALS['ad_u_region_id']);
    $GLOBALS['city_id'] = get_first_city($GLOBALS['province_id']);
    $GLOBALS['barangay_id'] = get_first_barangay($GLOBALS['city_id']);
}

loadFormCache('psi_project_sites');

//$sel_usergroup = getOptions('psi_usergroups', 'ug_name', 'ug_id', $GLOBALS['ug_id'], '', "WHERE (ug_name like '%PSTC-%') OR (ug_name like '%RO-%')");

$sel_provinces = getOptions('psi_provinces', 'province_name', 'province_id', $GLOBALS['province_id'], '', 'WHERE region_id = '.$GLOBALS['ad_u_region_id'].' ORDER BY province_name ASC');
$sel_cities = getOptions('psi_cities', 'city_name', 'city_id', $GLOBALS['city_id'], '', "WHERE province_id = $GLOBALS[province_id] ORDER BY city_name ASC");
$sel_barangays = getOptions('psi_barangays', 'barangay_name', 'barangay_id', $GLOBALS['barangay_id'], 'None', "WHERE city_id = $GLOBALS[city_id] ORDER BY barangay_name ASC");

$sel_type = getOptions('psi_equipment_brands', 'brand_name', 'brand_id', $GLOBALS['brand_id']);

$page_title = 'Project Sites ('.$opstr.')';
page_header($page_title, 1);

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
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="project_sites.php?pid=<?php echo $pid;?>" title="Project Sites/Trainings/Seminars"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="project_sites_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form" role="form">

            <div class="form-group form-group-sm">
                <label for="brand_id" class="control-label">Equipment</label>
                <select class="form-control input-sm" id="brand_id" name="brand_id">
                    <?php echo $sel_type; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="prj_site_date" class="control-label">Date Deployed *</label>
                <input class="form-control input-sm date-picker" placeholder="Date" maxlength="10" required="required" name="prj_site_date" id="prj_site_date" type="text" value="<?php echo $GLOBALS['prj_site_date']; ?>">
            </div>

            <div class="form-group">
                <label for="prj_site_remarks" class="control-label">Remarks</label>
                <textarea class="form-control input-sm" placeholder="Remarks" name="prj_site_remarks" id="prj_site_remarks" cols="50" rows="4"><?php echo $GLOBALS['prj_site_remarks']; ?></textarea>
            </div>

            <h3><span class="label label-default full-width">Project Location</span></h3>

            <div class="form-group">
                <label for="prj_site_address" class="control-label">Address</label>
                <textarea class="form-control input-sm" placeholder="Address" name="prj_site_address" id="prj_site_address" cols="50" rows="3"><?php echo $GLOBALS['prj_site_address']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="prj_province" class="control-label">Province</label>
                <select class="form-control input-sm province_select" id="province_id" name="province_id" required="required">
                    <?php echo $sel_provinces; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="prj_city" class="control-label">Municipality/City</label>
                <select class="form-control input-sm city_select" id="city_id" name="city_id" required="required">
                    <?php echo $sel_cities; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="prj_city" class="control-label">Barangay</label>
                <select class="form-control input-sm barangay_select" id="barangay_id" name="barangay_id" required="required">
                    <?php echo $sel_barangays; ?>
                </select>
            </div>


            <h3><span class="label label-default full-width">Map Coordinates</span></h3>
            <div id="map-location-picker" class="form-group map-location-picker">
            </div>

            <div class="form-group">
                <label for="prj_site_longitude" class="control-label">Longitude</label>
                <input class="form-control input-sm" placeholder="Longitude" min="0" step="any" name="prj_site_longitude" id="longitude" type="number" value="<?php echo $GLOBALS['prj_site_longitude']; ?>">
            </div>

            <div class="form-group">
                <label for="prj_site_latitude" class="control-label">Latitude</label>
                <input class="form-control input-sm" placeholder="Latitude" min="0" step="any" name="prj_site_latitude" id="latitude" type="number" value="<?php echo $GLOBALS['prj_site_latitude']; ?>">
            </div>

            <div class="form-group">
                <label for="prj_site_elevation" class="control-label">Elevation</label>
                <input class="form-control input-sm" placeholder="Elevation" min="0" step="any" name="prj_site_elevation" id="prj_site_elevation" type="number" value="<?php echo $GLOBALS['prj_site_elevation']; ?>">
            </div>

            <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
            <input type="hidden" name="prj_id" value="<?php echo $pid; ?>" />
            <input type="hidden" name="prj_site_id" value="<?php echo $GLOBALS['prj_site_id']; ?>">

        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>

<script src="http://maps.googleapis.com/maps/api/js?key=<?php echo DEF_GOOGLE_MAPS_KEY; ?>" type="text/javascript"></script>
<script>
    var _latitude = <?php echo $GLOBALS['prj_site_latitude']; ?>;
    var _longitude = <?php echo $GLOBALS['prj_site_longitude']; ?>;
</script>
<?php 
page_footer();

function load_collaborators($pid){
    $sql = "SELECT * FROM psi_project_sites_collaborators WHERE prj_site_id = $pid";
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

    $s .= '{bid:0, cid:0 , name:"None"}';

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
    $row = mysqli_fetch_array($res);
    $id = $row['province_id'];
    mysqli_free_result($res);
    return $id;
}


function get_first_city($pid){
    $id = 0;
    $sql = "SELECT * FROM psi_cities WHERE province_id = $pid ORDER BY city_name ASC";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    if (!$res) return 0;
    $row = mysqli_fetch_array($res);
    $id = $row['city_id'];
    mysqli_free_result($res);
    return $id;
}

function get_first_barangay($pid){
    $id = 0;
    $sql = "SELECT * FROM psi_barangays WHERE city_id = $pid ORDER BY barangay_name ASC";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    if (!$res) return 0;
    $row = mysqli_fetch_array($res);
    $id = $row['barangay_id'];
    mysqli_free_result($res);
    return $id;
}
?>