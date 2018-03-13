<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Project Packaging & Labeling Designs', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_packaging.php?pid='.$pid);

if (!dbValueExists('psi_packaging', 'pkg_id', $id, false)){
    redirect(WEBSITE_URL.'project_packaging.php?pid='.$pid);
    die();
}

$sid = requestInteger('sid', 'location: '.WEBSITE_URL.'project_packaging.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_packaging.php?pid='.$pid);

//$sql = "DELETE FROM psi_packaging_designs WHERE design_id = $sid";

if ($op == 3){
	$sql = "UPDATE psi_packaging_designs SET design_image1 = '', design_filename1 = '' WHERE design_id = $sid";
} else if ($op == 4){
	$sql = "UPDATE psi_packaging_designs SET design_image2 = '', design_filename2 = '' WHERE design_id = $sid";
} else {
	$sql = "DELETE FROM psi_packaging_designs WHERE design_id = $sid";
}

//echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_packaging_designs.php?id='.$id.'&pid='.$pid);
?>