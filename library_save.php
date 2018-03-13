<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'library.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'library.php');

if ($op == 1){
    if (!can_access('Library Monitoring', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Library Monitoring', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_library');


if (postEmpty('lib_year')){
	$_SESSION['errmsg'] = "Year is required.";
	redirect(WEBSITE_URL.'library_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('lib_year')){
	$_SESSION['errmsg'] = "Year must be a number.";
	redirect(WEBSITE_URL.'library_form.php?op='.$op.'&id='.$id);
	die();
} elseif (strlen($GLOBALS['lib_year']) < 4){
	$_SESSION['errmsg'] = "Year is invalid.";
	redirect(WEBSITE_URL.'library_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('lib_user_count')){
	$_SESSION['errmsg'] = "No. of Users is required.";
	redirect(WEBSITE_URL.'library_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('lib_user_count')){
	$_SESSION['errmsg'] = "No. of Users must be a number.";
	redirect(WEBSITE_URL.'library_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['lib_user_count'] < 0){
	$_SESSION['errmsg'] = "No. of Users is invalid.";
	redirect(WEBSITE_URL.'library_form.php?op='.$op.'&id='.$id);
	die();
}

$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_library', 'lib_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_library', 'lib_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Added.';
}


//echo $sql;
//die();

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'library.php');
?>