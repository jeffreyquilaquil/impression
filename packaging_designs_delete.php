<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Packaging & Labeling Designs', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$op = requestInteger('op', 'location: '.WEBSITE_URL.'packaging.php');
$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'packaging.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'packaging.php');

if ($op == 3){
	$sql = "UPDATE psi_packaging_designs SET design_image1 = '', design_filename1 = '' WHERE design_id = $id";
} else if ($op == 4){
	$sql = "UPDATE psi_packaging_designs SET design_image2 = '', design_filename2 = '' WHERE design_id = $id";
} else {
	$sql = "DELETE FROM psi_packaging_designs WHERE design_id = $id";
}

mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'packaging_designs.php?pid='.$pid);
?>