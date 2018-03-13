<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Service Providers', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'service_providers.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'service_providers.php');

if (dbValueExists('psi_trainings', 'sp_id', $id, false)){
	$_SESSION['errmsg'] = 'Cannot Delete. Record in use.';	
	redirect(WEBSITE_URL.'service_providers.php');
}

if (dbValueExists('psi_fora', 'sp_id', $id, false)){
	$_SESSION['errmsg'] = 'Cannot Delete. Record in use.';	
	redirect(WEBSITE_URL.'service_providers.php');
}

if (dbValueExists('psi_consultancies', 'sp_id', $id, false)){
	$_SESSION['errmsg'] = 'Cannot Delete. Record in use.';	
	redirect(WEBSITE_URL.'service_providers.php');
}

$sql = "DELETE FROM psi_service_providers WHERE sp_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'service_providers.php');
?>