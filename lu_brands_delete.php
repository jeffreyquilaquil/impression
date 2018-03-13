<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Equipment Names', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_brands.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_brands.php');


if (dbValueExists('psi_equipment', 'brand_id', $id, false)){
	$_SESSION['errmsg'] = 'Cannot Delete. Record in use.';	
	redirect(WEBSITE_URL.'cooperators.php');
}

$sql = "DELETE FROM psi_equipment_brands WHERE brand_id = $id";
mysqli_query($GLOBALS['cn'], $sql);


$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'lu_brands.php');
?>