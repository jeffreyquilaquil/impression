<?php
require_once('inc_conn.php');

if ($GLOBALS['ad_loggedin'] == 1) {
    redirect('index.php');
}

$user = '';
$pass = '';

if (postEmpty('username')){
	$_SESSION['errmsg'] = "Invalid username or password.";
	header('location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/loginform.php');
	die();
}

if (postEmpty('password')){
	$_SESSION['errmsg'] = "Invalid username or password.";
	header('location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/loginform.php');
	die();
}

$user = safeString($_POST['username']);
$pass = safeString($_POST['password']);

$s = "SELECT * FROM vwpsi_users WHERE (u_username = '$user') AND (u_password = '$pass') AND (u_enabled = 1)";
$res = mysqli_query($GLOBALS['cn'], $s);

if (!$res){
	$_SESSION['errmsg'] = "Unable to login at this time.";
	header('location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php');
	die();
}

$numrows = mysqli_num_rows($res);

$_SESSION['errmsg'] = '';
$id = 0;
if ( $numrows > 0){
	$row = mysqli_fetch_array($res);
	$_SESSION["ad_loggedin"] = 1;
	$_SESSION["ad_u_id"] = $row['u_id'];
	$_SESSION["ad_u_username"] = $row['u_username'];
	$_SESSION["ad_u_fname"] = $row['u_fname'];
	$_SESSION["ad_u_mname"] = $row['u_mname'];
	$_SESSION["ad_u_lname"] = $row['u_lname'];

	$_SESSION["ad_u_name"] = $row['u_fname'].' '.(strlen($row['u_mname']) > 0 ? $row['u_mname'].' ' : '' ).$row['u_lname'];
	$_SESSION["ad_u_region_id"] = $row['region_id'];
	$_SESSION["ad_u_region_name"] = $row['u_region_name'];

	$_SESSION["ad_ug_id"] = $row['ug_id'];
	$_SESSION["ad_ug_name"] = $row['ug_name'];
	$_SESSION["ad_ug_is_admin"] = $row['ug_is_admin'];
	//$id = $row['et_id'];
} else {
	$_SESSION["ad_loggedin"] = 0;
	$_SESSION['errmsg'] = "Invalid username or password.";
}

if ($_SESSION["ad_loggedin"] == 1){
	header('location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/project_summaries.php');
	die();
} else {
	header('location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/loginform.php');
	die();
}
?>