<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_activity_categories.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_activity_categories.php');

if ($op == 1){
    if (!can_access('Media Activity Categories', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Media Activity Categories', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_activity_types');

if (postEmpty('activity_type_name')){
	$_SESSION['errmsg'] = "Category Name is required.";
	redirect(WEBSITE_URL.'lu_activity_categories_form.php?op='.$op.'&id='.$id);
	die();
}

$sql = '';
$msg = '';

if ($op == 1){
	$sql = getUpdateQuery('psi_activity_types', 'activity_type_id');
	$msg = 'Record Updated.';
} else {
	$sql = getInsertQuery('psi_activity_types', 'activity_type_id');
	$msg = 'Record Added.';
}

//echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);
$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'lu_activity_categories.php');
?>