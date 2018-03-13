<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Consultancy Documents', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'consultancies.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'consultancies.php');

$sql = "DELETE FROM psi_consultancies WHERE con_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'consultancies.php');
?>