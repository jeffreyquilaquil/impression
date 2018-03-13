<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Project Documentation', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_documents.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_documents.php?pid='.$pid);

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

loadDBValues("psi_project_documents", "SELECT * FROM psi_project_documents WHERE doc_id = ".$id);

$doc = PROJECT_DOCS_PATH.DIRECTORY_SEPARATOR.$GLOBALS['doc_file'];
//echo  $doc;
//die();
unlink($doc);

$sql = "DELETE FROM psi_project_documents WHERE doc_id = $id";
mysqli_query($GLOBALS['cn'], $sql);


$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_documents.php?pid='.$pid);
?>