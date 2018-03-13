<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Testings & Calibrations', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'calibrations.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'calibrations.php');

$sql = "DELETE FROM psi_calibrations WHERE cal_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'calibrations.php');
?>