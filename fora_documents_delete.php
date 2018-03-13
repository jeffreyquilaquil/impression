<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Fora Documents', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'fora.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'fora_documents.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'fora_documents.php?pid='.$pid);

if (!dbValueExists('psi_fora', 'fr_id', $pid, false)){
    redirect(WEBSITE_URL.'fora.php');
    die();
}

loadDBValues("psi_fora", "SELECT * FROM psi_fora WHERE fr_id = ".$id);
$doc = TRAINING_DOCS_PATH.DIRECTORY_SEPARATOR.$GLOBALS['frdoc_file'];
//echo  $doc;
//die();
unlink($doc);


$sql = "DELETE FROM psi_fora_documents WHERE frdoc_id = $id";
mysqli_query($GLOBALS['cn'], $sql);


$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'fora_documents.php?pid='.$pid);
?>