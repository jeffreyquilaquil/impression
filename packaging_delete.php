<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Packaging & Labeling', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'packaging.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'packaging.php');

$sql = "DELETE FROM psi_packaging WHERE pkg_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_packaging_designs WHERE pkg_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'packaging.php');
?>