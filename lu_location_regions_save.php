<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'regions.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'regions.php');

if ($op == 1){
    if (!can_access('Location Listings', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Location Listings', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}


saveFormCache('psi_regions', 'confirm_password');

if (postEmpty('region_code')){
	$_SESSION['errmsg'] = "Code is required.";
	redirect(WEBSITE_URL."lu_location_regions_form.php?op=$op&id=$id");
	die();
}

if (postEmpty('region_name')){
	$_SESSION['errmsg'] = "Name is required.";
	redirect(WEBSITE_URL."lu_location_regions_form.php?op=$op&id=$id");
	die();
}

$sql = '';
$msg = '';

if ($op == 1){
	$sql = getUpdateQuery('psi_regions', 'region_id', '', '', false);
	$msg = 'Record Updated.';
} else {
	$sql = getInsertQuery('psi_regions', 'region_id');
	$msg = 'Record Added.';
}

//echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);
$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'lu_location_regions.php');
?>