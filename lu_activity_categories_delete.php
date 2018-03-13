<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Media Activity Categories', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_activity_categories.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_activity_categories.php');

$sql = "DELETE FROM psi_activity_types WHERE activity_type_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'lu_activity_categories.php');
?>