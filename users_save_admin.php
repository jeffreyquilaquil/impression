<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'users_admin.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'users_admin.php');

if ($op == 1){
    if (!can_access('Users', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Users', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}


saveFormCache('psi_users', 'confirm_password');

if (postEmpty('u_fname')){
	$_SESSION['errmsg'] = "First Name is required.";
	redirect(WEBSITE_URL.'users_form_admin.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('u_mname')){
	$_SESSION['errmsg'] = "Middle Name is required.";
	redirect(WEBSITE_URL.'users_form_admin.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('u_lname')){
	$_SESSION['errmsg'] = "Last Name is required.";
	redirect(WEBSITE_URL.'users_form_admin.php?op='.$op.'&id='.$id);
	die();
}

if (!postEmpty('u_email')){

	if (!isEmail('u_email')){
		$_SESSION['errmsg'] = "Email is invalid.";
		redirect(WEBSITE_URL.'users_form_admin.php?op='.$op.'&id='.$id);
		die();
	}

	if (dbValueUnique('psi_users', 'u_id', $id, 'u_email', $GLOBALS['u_email'])){
		$_SESSION['errmsg'] = "Email is already in use.";
		redirect(WEBSITE_URL.'users_form_admin.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('u_mobile')){
	if (!isMobile2('u_mobile')){
		$_SESSION['errmsg'] = "Mobile is invalid.";
		redirect(WEBSITE_URL.'users_form_admin.php?op='.$op.'&id='.$id);
		die();
	}

	if (dbValueUnique('psi_users', 'u_id', $id, 'u_mobile', $GLOBALS['u_mobile'])){
		$_SESSION['errmsg'] = "Mobile is already in use.";
		redirect(WEBSITE_URL.'users_form_admin.php?op='.$op.'&id='.$id);
		die();
	}
}

if (postEmpty('u_username')){
	$_SESSION['errmsg'] = "Username is required.";
	redirect(WEBSITE_URL.'users_form_admin.php?op='.$op.'&id='.$id);
	die();
}


if (dbValueUnique('psi_users', 'u_id', $id, 'u_username', $GLOBALS['u_username'])){
	$_SESSION['errmsg'] = "Username is already in use.";
	redirect(WEBSITE_URL.'users_form_admin.php?op='.$op.'&id='.$id);
	die();
}

if ($op == 0){
	if (postEmpty('u_password')){
		$_SESSION['errmsg'] = "Password is required.";
		redirect(WEBSITE_URL.'users_form_admin.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('u_password')){
	if (postEmpty('confirm_password')){
		$_SESSION['errmsg'] = "Confirm Password is required.";
		redirect(WEBSITE_URL.'users_form_admin.php?op='.$op.'&id='.$id);
		die();
	}

	if ($GLOBALS['u_password'] != $GLOBALS['confirm_password']){
		$_SESSION['errmsg'] = "Confirm Password must match Password.";
		redirect(WEBSITE_URL.'users_form_admin.php?op='.$op.'&id='.$id);
		die();
	}
} else {
	if (isset($GLOBALS['u_password'])) {
		unset($GLOBALS['u_password']);
	}
}

if (!isset($_POST['u_enabled'])){
	$GLOBALS["u_enabled"] = 1;	
}


$sql = '';
$msg = '';

if ($op == 1){
	$sql = getUpdateQuery('psi_users', 'u_id', '', '', false);
	$msg = 'Record Updated.';
} else {
	$sql = getInsertQuery('psi_users', 'u_id');
	$msg = 'Record Added.';
}

//echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);
$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'users_admin.php');
?>