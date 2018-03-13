<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Scholarship Programs', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_scholarship_programs.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_scholarship_programs.php');

$sql = "DELETE FROM psi_scholarship_programs WHERE scholar_prog_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'lu_scholarship_programs.php');
?>