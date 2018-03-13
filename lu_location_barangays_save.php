<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_location_regions.php');
$rid = requestInteger('rid', 'location: '.WEBSITE_URL.'lu_location_regions.php');
$pid = requestInteger('pid', 'location: '.WEBSITE_URL."lu_location_provinces.php?rid=$rid");
$cid = requestInteger('cid', 'location: '.WEBSITE_URL."lu_location_cities.php?rid=$rid&pid=$pid");
$id = requestInteger('id', 'location: '.WEBSITE_URL."lu_location_barangays.php?rid=$rid&pid=$pid&cid=$cid");

if ($op == 1){
    if (!can_access('Location Listings', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Location Listings', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}


saveFormCache('psi_barangays', 'confirm_password');

if (postEmpty('barangay_name')){
	$_SESSION['errmsg'] = "Name is required.";
	redirect(WEBSITE_URL."lu_location_barangays_form.php?rid=$rid&pid=$pid&cid=$cid&id=$id&op=$op");
	die();
}

$sql = '';
$msg = '';

if ($op == 1){
	$sql = getUpdateQuery('psi_barangays', 'barangay_id', '', '', false);
	$msg = 'Record Updated.';
} else {
	$sql = getInsertQuery('psi_barangays', 'barangay_id');
	$msg = 'Record Added.';
}

//echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);
$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL."lu_location_barangays.php?rid=$rid&pid=$pid&cid=$cid");
?>