<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'calibrations.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'calibrations.php');

if ($op == 1){
    if (!can_access('Testings & Calibrations', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Testings & Calibrations', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_calibrations');

//echo var_dump($_POST['cal_type_id']);
//die();


if (postEmpty('cal_year')){
	$_SESSION['errmsg'] = "Year is required.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
} elseif (!postInteger('cal_year')){
	$_SESSION['errmsg'] = "Year must be a number.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
} elseif (strlen($GLOBALS['cal_year']) < 4){
	$_SESSION['errmsg'] = "Year must be a valid year.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$ssid);
	die();
}

if (postEmpty('cal_month')){
	$_SESSION['errmsg'] = "Month is required.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
}

// no

if (postEmpty('cal_no_tests')){
	$_SESSION['errmsg'] = "No. of Services Rendered is required.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('cal_no_tests')){
	$_SESSION['errmsg'] = "No. of Services Rendered must be a number.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['cal_no_tests'] < 0){
	$_SESSION['errmsg'] = "No. of Services Rendered is invalid.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('cal_no_calibrations')){
	$_SESSION['errmsg'] = "No. of Samples Tested / Calibrations is required.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('cal_no_calibrations')){
	$_SESSION['errmsg'] = "No. of Samples Tested / Calibrations must be a number.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['cal_no_calibrations'] < 0){
	$_SESSION['errmsg'] = "No. of Samples Tested / Calibrations is invalid.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('cal_no_clients')){
	$_SESSION['errmsg'] = "No. of Clients is required.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('cal_no_clients')){
	$_SESSION['errmsg'] = "No. of Clients must be a number.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['cal_no_clients'] < 0){
	$_SESSION['errmsg'] = "No. of Clients is invalid.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('cal_no_firms')){
	$_SESSION['errmsg'] = "No. of Firms is required.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('cal_no_firms')){
	$_SESSION['errmsg'] = "No. of Firms must be a number.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['cal_no_firms'] < 0){
	$_SESSION['errmsg'] = "No. of Firms is invalid.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('cal_income')){
	$_SESSION['errmsg'] = "Income is required.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
} elseif (!postFloat('cal_income')){
	$_SESSION['errmsg'] = "Income must be a valid number.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('cal_value_service')){
	$_SESSION['errmsg'] = "Value Of Assistance is required.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
} elseif (!postFloat('cal_value_service')){
	$_SESSION['errmsg'] = "Value Of Assistance must be a valid number.";
	redirect(WEBSITE_URL.'calibrations_form.php?op='.$op.'&id='.$id);
	die();
}

$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_calibrations', 'cal_id');
	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');
	$sql = getInsertQuery('psi_calibrations', 'cal_id');
	$msg = 'Record Added.';
}

//echo $sql;
//die();

mysqli_query($GLOBALS['cn'], $sql);


$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'calibrations.php');
?>