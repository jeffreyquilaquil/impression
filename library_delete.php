<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Library Monitoring', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'library.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'library.php');

$sql = "DELETE FROM psi_library WHERE lib_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'library.php');
?>