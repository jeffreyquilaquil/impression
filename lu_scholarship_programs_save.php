<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_scholarship_programs.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_scholarship_programs.php');

if ($op == 1){
    if (!can_access('Scholarship Programs', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Scholarship Programs', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_scholarship_programs');

if (postEmpty('scholar_prog_name')){
	$_SESSION['errmsg'] = "Program Name is required.";
	redirect(WEBSITE_URL.'lu_scholarship_programs_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('scholar_prog_desc')){
	$_SESSION['errmsg'] = "Description is required.";
	redirect(WEBSITE_URL.'lu_scholarship_programs_form.php?op='.$op.'&id='.$id);
	die();
}

$sql = '';
$msg = '';

if ($op == 1){
	$sql = getUpdateQuery('psi_scholarship_programs', 'scholar_prog_id');
	$msg = 'Record Updated.';
} else {
	$sql = getInsertQuery('psi_scholarship_programs', 'scholar_prog_id');
	$msg = 'Record Added.';
}

//echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);
$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'lu_scholarship_programs.php');
?>