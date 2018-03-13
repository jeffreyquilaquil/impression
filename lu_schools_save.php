<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_schools.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_schools.php');

if ($op == 1){
    if (!can_access('Schools', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Schools', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_schools');

if (postEmpty('school_name')){
	$_SESSION['errmsg'] = "School Name is required.";
	redirect(WEBSITE_URL.'lu_schools_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('school_acronym')){
	$_SESSION['errmsg'] = "Acronym is required.";
	redirect(WEBSITE_URL.'lu_schools_form.php?op='.$op.'&id='.$id);
	die();
}

if (!postEmpty('school_email')){

	if (!isEmail($GLOBALS['school_email'])){
		$_SESSION['errmsg'] = "Email is invalid.";
		redirect(WEBSITE_URL.'schools_form.php?op='.$op.'&id='.$id);
		die();
	}

	if (dbValueUnique('psi_schools', 'school_id', $id, 'school_email', $GLOBALS['school_email'])){
		$_SESSION['errmsg'] = "Email is already in use.";
		redirect(WEBSITE_URL.'schools_form.php?op='.$op.'&id='.$id);
		die();
	}
}

$sql = '';
$msg = '';

if ($op == 1){
	$sql = getUpdateQuery('psi_schools', 'school_id');
	$msg = 'Record Updated.';
} else {
	$sql = getInsertQuery('psi_schools', 'school_id');
	$msg = 'Record Added.';
}

//echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);
$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'lu_schools.php');
?>