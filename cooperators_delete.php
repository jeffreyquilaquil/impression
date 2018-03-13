<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Cooperators', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'cooperators.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'cooperators.php');

if (dbValueExists('psi_project_beneficiaries', 'coop_id', $id, false)){
	$_SESSION['errmsg'] = 'Cannot Delete. Record in use.';	
	redirect(WEBSITE_URL.'cooperators.php');
}

if (dbValueExists('psi_packagings', 'coop_id', $id, false)){
	$_SESSION['errmsg'] = 'Cannot Delete. Record in use.';	
	redirect(WEBSITE_URL.'cooperators.php');
}

if (dbValueExists('psi_consultancies', 'coop_id', $id, false)){
	$_SESSION['errmsg'] = 'Cannot Delete. Record in use.';	
	redirect(WEBSITE_URL.'cooperators.php');
}

$sql = "DELETE FROM psi_coop_sectors WHERE coop_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_cooperators WHERE coop_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'cooperators.php');
?>