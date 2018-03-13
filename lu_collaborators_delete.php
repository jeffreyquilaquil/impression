<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Collaborating Agencies', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_collaborators.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_collaborators.php');

$sql = "DELETE FROM psi_collaborators WHERE col_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'lu_collaborators.php');
?>