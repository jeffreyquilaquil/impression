<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_project_types.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_project_types.php');

if ($op == 1){
    if (!can_access('Project Categories', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Categories', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_project_types');

if (postEmpty('prj_type_name')){
	$_SESSION['errmsg'] = "Category Name is required.";
	redirect(WEBSITE_URL.'lu_project_types_form.php?op='.$op.'&id='.$id);
	die();
}

$sql = '';
$msg = '';

if ($op == 1){
	$sql = getUpdateQuery('psi_project_types', 'prj_type_id');
	$msg = 'Record Updated.';
} else {
	$sql = getInsertQuery('psi_project_types', 'prj_type_id');
	$msg = 'Record Added.';
}

//echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);
$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'lu_project_types.php');
?>