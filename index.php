<?php
require_once('inc_page.php');

$q_index = 'q';
$q1_index = 'region_id';
$q2_index = 'province_id';
$q3_index = 'district_id';
$q4_index = 'prj_type_id';
$q5_index = 'brand_id';
$q6_index = 'sector_id';
$q7_index = 'prj_status_id';
$q8_index = 'prj_year_approved';
$q9_index = 'prj_year_approved1';

$q_key = 'q_key';
$q1_key = 'q1_key';
$q2_key = 'q2_key';
$q3_key = 'q3_key';
$q4_key = 'q4_key';
$q5_key = 'q5_key';
$q6_key = 'q6_key';
$q7_key = 'q7_key';
$q8_key = 'q8_key';
$q9_key = 'q9_key';

$q = '';
$region_id = 0;
$province_id = 0;
$district_id = 0;
$prj_type_id = 0;
$brand_id = 0;
$sector_id = 0;
$prj_status_id = 0;
$prj_year_approved = 0;
$prj_year_approved1 = 0;

if (!postEmpty('search')){
    if ($_POST['search'] == 'search'){
        if (!postEmpty($q_index)){
            $_SESSION[$q_key] = safeString($_POST[$q_index]);
        } else {
            $_SESSION[$q_key] = '';
        }

        if (!postEmpty($q1_index)){
            $_SESSION[$q1_key] = safeString($_POST[$q1_index]);
        } else {
            $_SESSION[$q1_key] = 0;
        }

        if (!postEmpty($q2_index)){
            $_SESSION[$q2_key] = safeString($_POST[$q2_index]);
        } else {
            $_SESSION[$q2_key] = 0;
        }

        if (!postEmpty($q3_index)){
            $_SESSION[$q3_key] = safeString($_POST[$q3_index]);
        } else {
            $_SESSION[$q3_key] = 0;
        }

        if (!postEmpty($q4_index)){
            $_SESSION[$q4_key] = safeString($_POST[$q4_index]);
        } else {
            $_SESSION[$q4_key] = 0;
        }

        if (!postEmpty($q5_index)){
            $_SESSION[$q5_key] = safeString($_POST[$q5_index]);
        } else {
            $_SESSION[$q5_key] = 0;
        }

        if (!postEmpty($q6_index)){
            $_SESSION[$q6_key] = safeString($_POST[$q6_index]);
        } else {
            $_SESSION[$q6_key] = 0;
        }

        if (!postEmpty($q7_index)){
            $_SESSION[$q7_key] = safeString($_POST[$q7_index]);
        } else {
            $_SESSION[$q7_key] = 0;
        }

        if (!postEmpty($q8_index)){
            $_SESSION[$q8_key] = safeString($_POST[$q8_index]);
        } else {
            $_SESSION[$q8_key] = 0;
        }

        if (!postEmpty($q9_index)){
            $_SESSION[$q9_key] = safeString($_POST[$q9_index]);
        } else {
            $_SESSION[$q9_key] = 0;
        }

    }
}

if (!sessionEmpty($q_key)){
    $q = $_SESSION[$q_key];
}

if (!sessionEmpty($q1_key)){
    $region_id = intval($_SESSION[$q1_key]);
}

if (!sessionEmpty($q2_key)){
    $province_id = intval($_SESSION[$q2_key]);
}

if (!sessionEmpty($q3_key)){
    $district_id = intval($_SESSION[$q3_key]);
}

if (!sessionEmpty($q4_key)){
    $prj_type_id = intval($_SESSION[$q4_key]);
}

if (!sessionEmpty($q5_key)){
    $brand_id = intval($_SESSION[$q5_key]);
}

if (!sessionEmpty($q6_key)){
    $sector_id = intval($_SESSION[$q6_key]);
}

if (!sessionEmpty($q7_key)){
    $prj_status_id = intval($_SESSION[$q7_key]);
}

if (!sessionEmpty($q8_key)){
    $prj_year_approved = intval($_SESSION[$q8_key]);
}

if (!sessionEmpty($q9_key)){
    $prj_year_approved1 = intval($_SESSION[$q9_key]);
}


$where = '';
if (strlen($q) > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    $where .= "((prj_title like '%$q%') OR (prj_code like '%$q%') OR (coop_names like '%$q%') OR (coop_p_names like '%$q%') OR (collaborator_names like '%$q%'))";
}

if ($region_id > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(region_id = $region_id)";
}

if ($province_id > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(province_id = $province_id)";
}

if ($district_id > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(district_id = $district_id)";
}

if ($prj_type_id > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    $where .= "(prj_type_id = $prj_type_id)";
}

if ($sector_id > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(sector_id = $sector_id)";
}

if ($prj_status_id > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(prj_status_id = $prj_status_id)";
}

if ($prj_year_approved > 0){

    if (strlen($where) > 0){
        $where .= " AND ";    
    }

    if ($prj_year_approved1 > 0){
        $where .= "(prj_year_approved >= $prj_year_approved) AND (prj_year_approved <= $prj_year_approved1)";
    } else {
        $where .= "(prj_year_approved = $prj_year_approved)";
    }
}

if ($brand_id > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(prj_id IN (SELECT prj_id FROM vwpsi_project_equipments WHERE brand_id = $brand_id))";
}


$sql = "SELECT * FROM vwpsi_projects";


if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}
$sql .= ' ORDER BY prj_title ASC';

//echo $sql;

/** 
** END GOOGLE MAPS VALUES 
**/

$sel_regions = getOptions('vwpsi_regions', 'region_text', 'region_id', $region_id, 'All');
$sel_provinces = getOptions('psi_provinces', 'province_name', 'province_id', $province_id, 'All', ' ORDER BY province_name ASC');
$sel_districts = getOptions('psi_districts', 'district_name', 'district_id', $district_id, 'All');
$sel_projectypes = getOptions('psi_project_types', 'prj_type_name', 'prj_type_id', $prj_type_id, 'All');
$sel_brands = getOptions('psi_equipment_brands', 'brand_name', 'brand_id', $brand_id, 'All');
$sel_sectors = getOptions('psi_sectors', 'sector_name', 'sector_id', $sector_id, 'All');
$sel_status = getOptions('psi_project_status', 'prj_status_name', 'prj_status_id', $prj_status_id, 'All');
$sel_year_approved = getYearOptions($prj_year_approved);
$sel_year_approved1 = getYearOptions($prj_year_approved1);

$amt_due = 0;
$amt_paid = 0;
$refund_rate = 0;
$project_cost = 0;
$ctr = 0;

$res_count = 0;
$prj_id = array();
$prj_title = array();
$prj_type_id = array();
$prj_latitude = array();
$prj_longitude = array();
$coor = array();
$view_link = array();

$res = mysqli_query($GLOBALS['cn'], $sql);


page_header('Home', 0, false);
?>

<div id="page-wrapper">
    <div id="map"></div>
</div>
<div class="container-fluid map-legends">
    <ul type="none">
        <li>Legend</li>
        <li><img src="<?php echo IMAGES_PATH.'pin_rollout.png'; ?>" alt="Rollout Projects"><br><small>Rollout Projects</small></li>
        <li><img src="<?php echo IMAGES_PATH.'pin_setup.png'; ?>" alt="Setup Projects"><br><small>Setup Projects</small></li>
        <li><img src="<?php echo IMAGES_PATH.'pin_tapi.png'; ?>" alt="Tapi Assisted Projects"><br><small>Tapi Assisted Projects</small></li>
        <li><img src="<?php echo IMAGES_PATH.'pin_gia_cbp.png'; ?>" alt="GIA Community Based Projects"><br><small>GIA Community Based Projects</small></li>
        <li><img src="<?php echo IMAGES_PATH.'pin_gia_int.png'; ?>" alt="GIA Internally Funded"><br><small>GIA Internally Funded</small></li>
        <li><img src="<?php echo IMAGES_PATH.'pin_gia_ext.png'; ?>" alt="GIA Externally Funded"><br><small>GIA Externally Funded</small></li>
    </ul>
</div>    
<div id="project-list-panel">
    <div class="container-fluid">
        <?php        
        if ($res) {
            if (mysqli_num_rows($res) > 0){
            ?>
            <div>Projects</div>
            <div class="table-responsive">
                <table id="grid_table" class="table table-hover tablesorter">
                    <thead>
                        <tr>
                            <th class="hidden-print">&nbsp;</th>
                            <th>#</th>
                            <th>Project</th>
                            <th>Type</th>
                            <th class="nowrap">Year Approved</th>
                            <th class="hidden-print">Beneficiaries</th>
                            <th class="hidden-print">Collaborators</th>
                            <th>Sector</th>
                            <th>Province</th>
                            <th>City</th>
                            <th>District</th>
                            <th>Status</th>
        <!--                    
                            <th>Project Cost</th>
                            <th>Amount Due</th>
                            <th>Refunded</th>
                            <th>Refund Rate</th>
                        -->                    
                    </tr>
                </thead> 
                <tbody>
                    <?php
                    $ctr = 0;
                    while($row = mysqli_fetch_array($res)) {
                        $id = $row['prj_id'];
                        $prj_id[$res_count] = $id;
                        $prj_title[$res_count] = $row['prj_title'];
                        $prj_type_id[$res_count] = $row['prj_type_id'];
                        $prj_latitude[$res_count] = trim($row['prj_latitude']."")."";
                        $prj_longitude[$res_count] = trim($row['prj_longitude']."")."";
                        $coor[$res_count]        = "".trim($row['prj_latitude']).", ".trim($row['prj_longitude'])."";

                        $view_link[$res_count] = "projects_info_view.php?pid=".$id;
                        if ($GLOBALS['ad_loggedin'] == 1) {
                            $view_link[$res_count] = WEBSITE_URL."projects_view.php?pid=".$id;
                        }
                        $res_count++;

                        $action = '';

                        if (can_access('Projects', 'view')){
                            $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'projects_view.php?pid='.$row['prj_id'].'" title="View Details"><span class="fa fa-folder-open"></span></a>  ';
                        }
                        if (can_access('Projects', 'edit')){
                            $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'projects_form.php?op=1&amp;id='.$row['prj_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                        }
                        if (can_access('Projects', 'delete')){
                            $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'projects_delete.php?op=2&amp;id='.$row['prj_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>';
                        }

                        $objective = nl2br($row['prj_objective'].'');
                        $expected_output = nl2br($row['prj_expected_output'].'');
                        $beneficiaries = $row['coop_names'];
                        $collaborators = $row['collaborator_names'];
                        $ctr++;

                        $amt_due += $row['rep_total_due'];
                        $amt_paid += $row['rep_total_paid'];
                        $project_cost += $row['prj_cost_setup'];

                        ?>                
                        <tr>
                            <td class="nowrap text-left hidden-print"><?php echo $action; ?></td>
                            <td class="nowrap text-left"><?php echo $ctr; ?></td>
                            <td><?php echo $row['prj_title']; ?></td>
                            <td><?php echo $row['prj_type_name']; ?></td>
                            <td class="text-center nowrap"><?php echo $row['prj_year_approved']; ?></td>
                            <td class="text-left"><?php echo $beneficiaries; ?></td>
                            <td class="text-left"><?php echo $collaborators; ?></td>
                            <td><?php echo $row['sector_name']; ?></td>
                            <td><?php echo $row['province_name']; ?></td>
                            <td><?php echo $row['city_name']; ?></td>
                            <td class="text-center"><?php echo $row['district_name']; ?></td>
                            <td class="nowrap"><?php echo $row['prj_status_name']; ?></td>
                        </tr>
                        <?php
                    }

                    if ($amt_paid > 0){
                        if ($amt_due > 0){
                            $refund_rate = ($amt_paid / $amt_due) * 100;
                        }
                    }

                    ?>         
                </tbody>
            </table>
        </div>
        <div id="grid_pager" class="hidden-print">
            <form class="form-inline" style="text-align:center;" role="form">
                <div class="form-group form-group-sm">
                    <a class="btn btn-default btn-sm first" title="First"><span class="glyphicon glyphicon-step-backward"></span></a>
                    <a class="btn btn-default btn-sm prev" title="Previous"><span class="glyphicon glyphicon-backward"></span></a>
                    <input type="text" class="form-control input-sm text-center pagedisplay" disabled>
                    <a class="btn btn-default btn-sm next" title="Next"><span class="glyphicon glyphicon-forward"></span></a>
                    <a class="btn btn-default btn-sm last" title="Last"><span class="glyphicon glyphicon-step-forward"></span></a>
                    <select class="form-control input-sm pagesize" title="No. of items per page.">
                        <option selected="selected" value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </form>
        </div>

        <?php
            }
        }
        mysqli_free_result($res);
        ?>            
    </div>
</div>

<a class="btn btn-default filter-panel-btn-open" href="javascript:void(0);">Map Filters</a>

<div class="map-filter-panel">
    <form id="filter-form" name="filter-form" method="POST" action="index.php">
        <div class="filter-panel-wrapper">
            <div class="filter-panel-section">
                <div class="filter-panel-header clearfix">
                    <a class="btn btn-default btn-xs filter-panel-btn-close pull-right" href="javascript:void(0)">Close</a>
                    <!--
                    <a class="btn btn-default btn-xs filter-panel-btn-apply pull-right" href="javascript:void(0)">Apply Filter</a>
                -->
                <span class="filter-panel-title">Map Filters</span>
            </div>
        </div>
        <div class="filter-panel-section">
            <div class="filter-panel-header">Search</div>
            <div class="form-group">
                <input type="text" name="q" id="q" class="form-control input-sm" placeholder="Keyword" value="<?php echo $q; ?>">
            </div>

            <div class="filter-panel-header">Region</div>
            <div class="form-group">
                <select name="region_id" id="region_id" class="form-control chosen-select"><?php echo $sel_regions; ?></select>
            </div>

            <div class="filter-panel-header">Province</div>
            <div class="form-group">
                <select name="province_id" id="province_id" class="form-control chosen-select"><?php echo $sel_provinces; ?></select>
            </div>

            <div class="filter-panel-header">District</div>
            <div class="form-group">
                <select name="district_id" id="district_id" class="form-control chosen-select"><?php echo $sel_districts; ?></select>
            </div>

            <div class="filter-panel-header">Project Type</div>
            <div class="form-group">
                <select name="prj_type_id" id="prj_type_id" class="form-control chosen-select"><?php echo $sel_projectypes; ?></select>
            </div>

            <div class="filter-panel-header">Equipment</div>
            <div class="form-group">
                <select name="brand_id" id="brand_id" class="form-control chosen-select"><?php echo $sel_brands; ?></select>
            </div>

            <div class="filter-panel-header">Sector</div>
            <div class="form-group">
                <select name="sector_id" id="sector_id" class="form-control chosen-select"><?php echo $sel_sectors; ?></select>
            </div>

            <div class="filter-panel-header">Status</div>
            <div class="form-group">
                <select name="prj_status_id" id="prj_status_id" class="form-control chosen-select"><?php echo $sel_status; ?></select>
            </div>

            <div class="filter-panel-header">Year From</div>
            <div class="form-group">
                <select name="prj_year_approved" id="prj_year_approved" class="form-control chosen-select"><?php echo $sel_year_approved; ?></select>
            </div>

            <div class="filter-panel-header">Year To</div>
            <div class="form-group">
                <select name="prj_year_approved1" id="prj_year_approved1" class="form-control chosen-select"><?php echo $sel_year_approved1; ?></select>
            </div>

            <input type="submit" class="btn btn-default btn-xs btn-block" name="search" value="Apply Filter">
        </div>
            <!--
            <div class="filter-panel-section">
                <div class="filter-panel-header">Sector</div>
                <?php
                echo load_sectors_checkboxes();
                ?>
            </div>
        -->
        <div class="filter-panel-section">
            <div class="filter-panel-header clearfix">
                <a class="btn btn-default btn-xs filter-panel-btn-close pull-right" href="javascript:void(0)">Close</a>
                &nbsp;
            </div>
        </div>
    </div>
    <input type="hidden" name="search" value="search">
</form>
</div>
<div id="cross-column">
</div>

<script src="http://maps.googleapis.com/maps/api/js?key=<?php echo DEF_GOOGLE_MAPS_KEY; ?>" type="text/javascript"></script>
<script type="text/javascript">

    var _latitude = <?php echo DEF_LATITUDE; ?>;
    var _longitude = <?php echo DEF_LONGITUDE; ?>;

    var _tsOptions = {
        headers:{
            0: { sorter: false}
        }
    };

    var _proj = [
    <?php
    $s = '';
    for ($i = 0; $i < $res_count; $i++) {
        if (strlen($s) > 0){
            $s .= ','."\n";
        }
        $s .= ' "'.$prj_title[$i].'"';
    }
    echo $s;
    ?>
    ]

    var _locations = [

    <?php for ($i = 0; $i < $res_count; $i++) {

        if ($GLOBALS['ad_loggedin'] == 1) {
            ?>
            ['<a href="<?php echo $view_link[$i]; ?>"><?php echo $prj_title[$i]; ?></a>',
            <?php echo $coor[$i]; ?>, <?php echo $i; ?>, <?php echo $prj_type_id[$i]; ?>] <?php if( ($i != ($res_count-1)) ) { echo ","; } else { echo ""; } ?> 
            <?php
        } else {
            ?>
            ['<a href="javascript:void(0);" onclick="showProjectInfo(\'<?php echo $prj_title[$i]; ?>\', \'<?php echo $view_link[$i]; ?>\'); return false;"><?php echo $prj_title[$i]; ?></a>',
            <?php echo $coor[$i]; ?>, <?php echo $i; ?>, <?php echo $prj_type_id[$i]; ?>] <?php if( ($i != ($res_count-1)) ) { echo ","; } else { echo ""; } ?> 
            <?php
        }
    } 
    ?>
    ];

    var _href = [

    <?php
    $s = '';
    for ($i = 0; $i < $res_count; $i++) {
        if (strlen($s) > 0){
            $s .= ','."\n";
        }
        $s .= ' "'.$view_link[$i].'"';
    }
    echo $s;
    ?>
    ];

    var _max_points = <?php echo $res_count; ?>;

    <?php
    if ($GLOBALS['ad_loggedin'] == 1) {
        ?>
        function loadProjectInfo(i){
            location.href = _href[i];
        }
        <?php
    } else {
        ?>
        function loadProjectInfo(i){
            showProjectInfo(_proj[i], _href[i]);
        }
        <?php
    }
    ?>

</script>
<?php
page_footer(true, false);

function load_sectors_checkboxes(){
    $s = '';
    $sql = "SELECT * FROM psi_sectors ORDER BY sector_name";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    if (!$res) return '';
    while($row = mysqli_fetch_array($res)){
        $id = $row['sector_id'];
        $s .= '
        <div class="checkbox filter-checkbox">
            <label>
                <input type="checkbox" name="sectors" value="'.$id.'"> '.$row['sector_name'].'
            </label>
        </div>
        ';
    }

    mysqli_free_result($res);
    return $s;
}

?>