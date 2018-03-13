<?php
require_once('inc_db.php');
require_once('inc_functions.php');
require_once('inc_extra_functions.php');

date_default_timezone_set('Asia/Manila');
session_start();

// **************************************************************************
// edit to change database connections
define( 'DB_HOST', 'localhost' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_NAME', 'impression' );

// google maps ***********************************************************************************************
// edit to change default google map location & key
define('DEF_GOOGLE_MAPS_KEY', 'AIzaSyCMDx-ejfVStxIBhfqtBuLj98OV79kqbdY');
define('DEF_LATITUDE', 11.23354);
define('DEF_LONGITUDE', 125.0672082);
//define('DEF_LATITUDE', 12.2446);
//define('DEF_LONGITUDE', 125.0388);

// facebook ***********************************************************************************************
// edit app id and app url for facebook comments
define('DEF_FB_APP_ID', '1685702521726852');
define('DEF_FB_APP_URL', 'http://124.105.2.58/impression/');

define('WEBSITE_TITLE', 'Impression');
define('WEBSITE_URL', 'http://'. $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']).'/');

define('CSS_PATH', WEBSITE_URL.'css/');
define('IMAGES_PATH', WEBSITE_URL.'images/');
define('JS_PATH', WEBSITE_URL.'js/');

define('AGENCY_PATH', realpath('./uploads/agency/'));
define('AGENCY_LINK_PATH', WEBSITE_URL.'uploads/agency/');

define('PACKAGING_DOCS_PATH', realpath('./uploads/docs/packaging/'));
define('PACKAGING_DOCS_LINK_PATH', WEBSITE_URL.'uploads/docs/packaging/');

define('DESIGNS_PATH', realpath('./uploads/designs/'));
define('DESIGN_LINK_PATH', WEBSITE_URL.'uploads/designs/');

define('DOCS_PATH', realpath('./uploads/docs/'));
define('DOCS_LINK_PATH', WEBSITE_URL.'uploads/docs/');

define('GALLERY_PATH', realpath('./uploads/gallery/'));
define('GALLERY_LINK_PATH', WEBSITE_URL.'uploads/gallery/');

define('PROJECT_DOCS_PATH', realpath('./uploads/docs/projects/'));
define('PROJECT_DOCS_LINK_PATH', WEBSITE_URL.'uploads/docs/projects/');

define('TRAINING_DOCS_PATH', realpath('./uploads/docs/trainings/'));
define('TRAINING_DOCS_LINK_PATH', WEBSITE_URL.'uploads/docs/trainings/');

define('CONSULTANCY_DOCS_PATH', realpath('./uploads/docs/consultancies/'));
define('CONSULTANCY_DOCS_LINK_PATH', WEBSITE_URL.'uploads/docs/consultancies/');

$GLOBALS['under_maintenance'] = 0;
$GLOBALS['db'] = new dbconn();
$GLOBALS['db']->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$GLOBALS['cn'] = $GLOBALS['db']->conn;

$GLOBALS['rc_publickey'] = '6Lev-cYSAAAAAP6PAjNMmIz3DvOP9ciDyRs8zRta';
$GLOBALS['rc_privatekey'] = '6Lev-cYSAAAAAMFmVUkrocDob2c01ZG_ZndmEcm1';

$GLOBALS['host'] = 'http://'. $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/';

$GLOBALS['ad_loggedin'] = 0;
$GLOBALS['ad_u_id'] = 0;
$GLOBALS['ad_u_username'] = '';
$GLOBALS['ad_u_fname'] = '';
$GLOBALS['ad_u_mname'] = '';
$GLOBALS['ad_u_lname'] = '';
$GLOBALS['ad_u_name'] = '';
$GLOBALS['ad_u_region_id'] = 0;
$GLOBALS['ad_u_region_name'] = 0;
$GLOBALS['ad_ug_id'] = 0;
$GLOBALS['ad_ug_name'] = 0;
$GLOBALS['ad_ug_is_admin'] = 0;
$GLOBALS['ad_u_enabled'] = 0;
$GLOBALS['errmsg'] = '';
$GLOBALS['ad_rights'] = array();

if (isset($_SESSION['errmsg'])){
    $GLOBALS['errmsg'] = $_SESSION['errmsg'];
    $_SESSION['errmsg'] = '';
}

if (isset($_SESSION['ad_loggedin'])){
    if ($_SESSION['ad_loggedin'] == 1){
        $GLOBALS['ad_loggedin'] = loadUser($_SESSION['ad_u_id']);
    }
}

if ($GLOBALS['ad_ug_id'] > 0){
    loadUserGroupRights($GLOBALS['ad_ug_id']);
}

function loadUser($pid){
    if ($pid == 0) return 0;

    $sql = "SELECT * FROM vwpsi_users WHERE u_id = $pid";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    if (!$res) return 0;
    $numrows = mysqli_num_rows($res);
    if ($numrows == 0) {
        mysqli_free_result($res);
        return 0;
    }
    $row = mysqli_fetch_array($res);

    $GLOBALS["ad_u_id"] = $row['u_id'];
    $GLOBALS["ad_u_username"] = $row['u_username'];
    $GLOBALS["ad_u_fname"] = $row['u_fname'];
    $GLOBALS["ad_u_mname"] = $row['u_mname'];
    $GLOBALS["ad_u_lname"] = $row['u_lname'];
    $GLOBALS["ad_u_name"] = $row['u_name'];
    $GLOBALS["ad_u_enabled"] = $row['u_enabled'];

    $GLOBALS["ad_u_region_id"] = $row['region_id'];
    $GLOBALS["ad_u_region_name"] = $row['u_region_name'];
    $GLOBALS["ad_ug_id"] = $row['ug_id'];
    $GLOBALS["ad_ug_name"] = $row['ug_name'];
    $GLOBALS["ad_ug_is_admin"] = $row['ug_is_admin'];
    mysqli_free_result($res);
    return $GLOBALS["ad_u_enabled"];
}

function loadUserGroupRights($pid){
    if ($pid == 0) return;

    $sql = "SELECT * FROM vwpsi_usergroup_rights WHERE ug_id = $pid";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    if (!$res) return;
    while($row = mysqli_fetch_array($res)){

        $id = $row['ur_id'];
        $name = $row['ur_name'];
        $GLOBALS['ad_rights'][$name] = array();
        if ($row['ugr_view'] == 1){
            $GLOBALS['ad_rights'][$name]['view'] = 1;
        }
        if ($row['ugr_add'] == 1){
            $GLOBALS['ad_rights'][$name]['add'] = 1;
        }
        if ($row['ugr_edit'] == 1){
            $GLOBALS['ad_rights'][$name]['edit'] = 1;
        }
        if ($row['ugr_delete'] == 1){
            $GLOBALS['ad_rights'][$name]['delete'] = 1;
        }
    }
    //echo var_dump($GLOBALS['ad_rights']);
    mysqli_free_result($res);
}

function can_access($section, $action = 'view'){
    if (!isset($GLOBALS['ad_rights'])) return false;
    if (!isset($GLOBALS['ad_rights'][$section])) return false;
    if (strlen($action) == 0) return true;
    if (!isset($GLOBALS['ad_rights'][$section][$action])) return false;
    return true;
}

function can_access_any($sections){
    if (!is_array($sections)) return false;
    foreach ($sections as $item){
        if (can_access($item)) return true;
    }
    return false;
}

function in_lab($lab){
    if (has_prefix($lab, 'Laboratory-')) return true;
    /*
    if ($lab == 'Laboratory-RML') return true;
    if ($lab == 'Laboratory-RSTL') return true;
    if ($lab == 'Laboratory-CWWTL') return true;
    if ($lab == 'Laboratory-RVCL') return true;
    */
    return false;
}

function get_lab_id($id){
    if ($id == 5) return 1;
    if ($id == 6) return 2;
    if ($id == 7) return 3;
    if ($id == 8) return 4;
    return 1;
}

function in_pstc($value){

    if (has_prefix($value, 'PSTC-')) return true;
    /*
    if ($value == 'PSTC-BATANGAS') return true;
    if ($value == 'PSTC-CAVITE') return true;
    if ($value == 'PSTC-LAGUNA') return true;
    if ($value == 'PSTC-QUEZON') return true;
    if ($value == 'PSTC-RIZAL') return true;
    */
    return false;
}

function has_prefix($value, $prefix){

    if (strpos($value, $prefix) === false) return false;
    return true;
}

?>
