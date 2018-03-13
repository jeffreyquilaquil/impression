<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'trainings.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'trainings_documents.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'trainings_documents.php?pid='.$pid);

if (!dbValueExists('psi_trainings', 'tr_id', $pid, false)){
    redirect(WEBSITE_URL.'trainings.php');
    die();
}

$sql = "DELETE FROM psi_training_documents WHERE trdoc_id = $id";
mysqli_query($GLOBALS['cn'], $sql);


$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'trainings_documents.php?pid='.$pid);
?>