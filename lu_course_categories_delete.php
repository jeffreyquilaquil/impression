<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Course Categories', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_course_categories.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_course_categories.php');

$sql = "DELETE FROM psi_course_categories WHERE course_cat_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'lu_course_categories.php');
?>