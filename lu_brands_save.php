<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_brands.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_brands.php');

if ($op == 1){
    if (!can_access('Equipment Names', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Equipment Names', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_equipment_brands');

if (postEmpty('brand_name')){
	$_SESSION['errmsg'] = "Equipment Name is required.";
	redirect(WEBSITE_URL.'lu_brands_form.php?op='.$op.'&id='.$id);
	die();
}

$sql = '';
$msg = '';

if ($op == 1){
	$sql = getUpdateQuery('psi_equipment_brands', 'brand_id');
	$msg = 'Record Updated.';
} else {
	$sql = getInsertQuery('psi_equipment_brands', 'brand_id');
	$msg = 'Record Added.';
}

//echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);
$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'lu_brands.php');
?>