<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_location_regions.php');
$rid = requestInteger('rid', 'location: '.WEBSITE_URL.'lu_location_regions.php');
$pid = requestInteger('pid', 'location: '.WEBSITE_URL."lu_location_provinces.php?rid=$rid");
$id = requestInteger('id', 'location: '.WEBSITE_URL."lu_location_cities.php?rid=$rid&pid=$pid");

if ($op == 1){
    if (!can_access('Location Listings', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Location Listings', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}


saveFormCache('psi_cities', 'confirm_password');

if (postEmpty('city_name')){
	$_SESSION['errmsg'] = "Name is required.";
	redirect(WEBSITE_URL."lu_location_cities_form.php?op=$op&rid=$rid&pid=$pid&id=$id");
	die();
}

$sql = '';
$msg = '';

if ($op == 1){
	$sql = getUpdateQuery('psi_cities', 'city_id', '', '', false);
	$msg = 'Record Updated.';
} else {
	$sql = getInsertQuery('psi_cities', 'city_id');
	$msg = 'Record Added.';
}

//echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);
$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL."lu_location_cities.php?rid=$rid&pid=$pid");
?>