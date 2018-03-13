<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Project Consultancies', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_consultancies.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_consultancies.php?pid='.$pid);

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$sql = "DELETE FROM psi_consultancies WHERE con_id = $id";
mysqli_query($GLOBALS['cn'], $sql);


$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_consultancies.php?pid='.$pid);
?>