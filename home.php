<?php
require_once('inc_page.php');

$proj       = array();
$projID     = array();
$type       = array();
$coor       = array();

/*
$SQL_HandaData      = "SELECT * FROM ".DB_TABLE_PREFIX."prj_info";
$RS_HandaData       = $sql_helper->get_results($SQL_HandaData);
*/

$sql = "SELECT * FROM psi_projects";
$res = mysqli_query($GLOBALS['cn'], $sql);


$res_count = 0;
if ($res){
    while ($row = mysqli_fetch_array($res)){
        $prj_id                = $row['prj_id'];
        $prj_title             = $row['prj_title'];
        $prj_type_id           = $row['prj_type_id'];
        $coordinates           = $row['prj_latitude'].", ".$row['prj_longitude'];

        $proj[$res_count]        = $prj_title;
        $coor[$res_count]        = "".trim($row['prj_latitude']).", ".trim($row['prj_longitude'])."";
        $type[$res_count]        = $prj_type_id;

        //$view_info[$res_count] = WEBSITE_URL."projects_view_public.php?pid=".$prj_id;
        $view_info[$res_count] = "projects_info_view.php?pid=".$prj_id;
        if ($GLOBALS['ad_loggedin'] == 1) {
            $view_info[$res_count] = WEBSITE_URL."projects_view.php?pid=".$prj_id;
        }
        $res_count++;    
    }
}
mysqli_free_result($res);

/** 
** END GOOGLE MAPS VALUES 
**/

page_header('Home', 0, false);
?>


<div id="map"></div>
<form class="form">

<div class="map-filter-panel">
    <div class="filter-panel-header">Project Type</div>
    <select class="form-control">
        <option value="0">All</option>
        <option value="1">1st District</option>
        <option value="2">1st District</option>
        <option value="3">1st District</option>
        <option value="4">1st District</option>
        <option value="5">1st District</option>
        <option value="6">1st District</option>
        <option value="7">1st District</option>
        <option value="9">Lone District</option>
        <option value="10">None</option>
    </select>

    <div class="filter-panel-header">Province</div>
    <select class="form-control">
        <option value="0">All</option>
        <option value="1">1st District</option>
        <option value="2">1st District</option>
        <option value="3">1st District</option>
        <option value="4">1st District</option>
        <option value="5">1st District</option>
        <option value="6">1st District</option>
        <option value="7">1st District</option>
        <option value="9">Lone District</option>
        <option value="10">None</option>
    </select>

    <div class="filter-panel-header">District</div>
    <select class="form-control chosen-select">
        <option value="0">All</option>
        <option value="1">1st District</option>
        <option value="2">1st District</option>
        <option value="3">1st District</option>
        <option value="4">1st District</option>
        <option value="5">1st District</option>
        <option value="6">1st District</option>
        <option value="7">1st District</option>
        <option value="9">Lone District</option>
        <option value="10">None</option>
    </select>

        <div class="filter-panel-header">Sector</div>

        <div class="checkbox filter-checkbox">
            <label for="c1">
                <input type="checkbox" name="c1"> Agriculture, forestry and fishing
            </label>
        </div>

        <div class="checkbox filter-checkbox">
            <label for="c2">
                <input type="checkbox" name="c2"> Agriculture, forestry and fishing
            </label>
        </div>

        <div class="checkbox filter-checkbox">
            <label for="c3">
                <input type="checkbox" name="c3"> Agriculture, forestry and fishing
            </label>
        </div>

        <div class="checkbox filter-checkbox">
            <label for="c4">
                <input type="checkbox" name="c4"> Agriculture, forestry and fishing
            </label>
        </div>

        <div class="checkbox filter-checkbox">
            <label for="c5">
                <input type="checkbox" name="c5"> Agriculture, forestry and fishing
            </label>
        </div>

        <div class="checkbox filter-checkbox">
            <label>
                <input type="checkbox" name="0"> Agriculture, forestry and fishing
            </label>
        </div>
</div>


</form>


<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCMDx-ejfVStxIBhfqtBuLj98OV79kqbdY" type="text/javascript"></script>
<script type="text/javascript">

var _proj = [
<?php
    $s = '';
    for ($i = 0; $i < $res_count; $i++) {
        if (strlen($s) > 0){
            $s .= ','."\n";
        }
        $s .= '                "'.$proj[$i].'"';
    }
    echo $s;
?>
]

var _locations = [

<?php for ($i = 0; $i < $res_count; $i++) {

    if ($GLOBALS['ad_loggedin'] == 1) {
    ?>
    ['<a href="<?php echo $view_info[$i]; ?>"><?php echo $proj[$i]; ?></a>',
        <?php echo $coor[$i]; ?>, <?php echo $i; ?>] <?php if( ($i != ($res_count-1)) ) { echo ","; } else { echo ""; } ?> 
    <?php
    } else {
    ?>
    ['<a href="javascript:void(0);" onclick="showProjectInfo(\'<?php echo $proj[$i]; ?>\', \'<?php echo $view_info[$i]; ?>\'); return false;"><?php echo $proj[$i]; ?></a>',
        <?php echo $coor[$i]; ?>, <?php echo $i; ?>] <?php if( ($i != ($res_count-1)) ) { echo ","; } else { echo ""; } ?> 
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
        $s .= '                "'.$view_info[$i].'"';
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
?>