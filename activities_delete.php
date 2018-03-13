<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Media Activities', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'activities.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'activities.php');

$sql = "DELETE FROM psi_activities WHERE activity_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'activities.php');
?>