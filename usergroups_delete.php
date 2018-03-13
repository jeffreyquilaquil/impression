<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('UserGroups', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'usergroups.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'usergroups.php');


if (dbValueExists('psi_users', 'ug_id', $id, false)){
	$_SESSION['errmsg'] = 'Cannot Delete. Record in use.';	
	redirect(WEBSITE_URL.'usergroups.php');
}

$sql = "DELETE FROM psi_usergroups WHERE ug_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'usergroups.php');
?>