<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Scholarship Monitoring', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'scholarship_monitoring.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'scholarship_monitoring.php');

$sql = "DELETE FROM psi_scholarship_monitoring WHERE scholar_mon_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'scholarship_monitoring.php');
?>