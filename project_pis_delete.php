<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Project PIS', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_pis.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_pis.php?pid='.$pid);

$sql = "DELETE FROM psi_project_pis WHERE prjpis_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_pis.php?pid='.$pid);
?>