<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_sectors.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_sectors.php');

if ($op == 1){
    if (!can_access('Sectors', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Sectors', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_sectors');

if (postEmpty('sector_name')){
	$_SESSION['errmsg'] = "Sector Name is required.";
	redirect(WEBSITE_URL.'lu_sectors_form.php?op='.$op.'&id='.$id);
	die();
}

$sql = '';
$msg = '';

if ($op == 1){
	$sql = getUpdateQuery('psi_sectors', 'sector_id');
	$msg = 'Record Updated.';
} else {
	$sql = getInsertQuery('psi_sectors', 'sector_id');
	$msg = 'Record Added.';
}

//echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);
$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'lu_sectors.php');
?>