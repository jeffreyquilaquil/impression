<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Consultancies', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'consultancies.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'consultancies_view.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'consultancies_view.php?pid='.$pid);

if (!dbValueExists('psi_consultancies', 'con_id', $pid, false)){
    redirect(WEBSITE_URL.'consultancies.php');
    die();
}

$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

$sql = "DELETE FROM psi_consultancy_documents WHERE condoc_id = $id";
mysqli_query($GLOBALS['cn'], $sql);
$msg = 'Record Updated.';

$_SESSION['errmsg'] = 'Record Deleted.';
redirect(WEBSITE_URL.'consultancies_view.php?pid='.$pid);
?>