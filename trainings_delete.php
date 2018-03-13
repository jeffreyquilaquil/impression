<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Trainings', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'trainings.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'trainings.php');

$sql = "DELETE FROM psi_trainings WHERE tr_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'trainings.php');
?>