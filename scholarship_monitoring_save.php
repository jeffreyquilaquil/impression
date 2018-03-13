<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'scholarship_monitoring.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'scholarship_monitoring.php');

if ($op == 1){
    if (!can_access('Scholarship Monitoring', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Scholarship Monitoring', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_scholarship_monitoring');

if (postEmpty('scholar_mon_year_from')){
	$_SESSION['errmsg'] = "School Year (From) is required.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('scholar_mon_year_from')){
	$_SESSION['errmsg'] = "School Year (From) must be a number.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
} elseif (strlen($GLOBALS['scholar_mon_year_from']) < 4){
	$_SESSION['errmsg'] = "School Year (From) is invalid.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('scholar_mon_year_to')){
	$_SESSION['errmsg'] = "School Year (To) is required.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('scholar_mon_year_to')){
	$_SESSION['errmsg'] = "School Year (To) must be a number.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
} elseif (strlen($GLOBALS['scholar_mon_year_to']) < 4){
	$_SESSION['errmsg'] = "School Year (To) is invalid.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
}

if ($GLOBALS['scholar_mon_year_from'] >= $GLOBALS['scholar_mon_year_to']){
	$_SESSION['errmsg'] = "School Year (To) must be greater than School Year (From).";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('scholar_mon_no_examinees')){
	$_SESSION['errmsg'] = "No. of Examinees is required.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('scholar_mon_no_examinees')){
	$_SESSION['errmsg'] = "No. of Examinees must be a number.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['scholar_mon_no_examinees'] < 0){
	$_SESSION['errmsg'] = "No. of Examinees is invalid.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('scholar_mon_no_qualifiers')){
	$_SESSION['errmsg'] = "No. of Qualifiers is required.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('scholar_mon_no_qualifiers')){
	$_SESSION['errmsg'] = "No. of Qualifiers must be a number.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['scholar_mon_no_qualifiers'] < 0){
	$_SESSION['errmsg'] = "No. of Qualifiers is invalid.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('scholar_mon_no_ongoing')){
	$_SESSION['errmsg'] = "No. of On-Going is required.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('scholar_mon_no_ongoing')){
	$_SESSION['errmsg'] = "No. of On-Going must be a number.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['scholar_mon_no_ongoing'] < 0){
	$_SESSION['errmsg'] = "No. of On-Going is invalid.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('scholar_mon_no_graduates')){
	$_SESSION['errmsg'] = "Total No. of Graduates is required.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('scholar_mon_no_graduates')){
	$_SESSION['errmsg'] = "Total No. of Graduates must be a number.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['scholar_mon_no_graduates'] < 0){
	$_SESSION['errmsg'] = "Total No. of Graduates is invalid.";
	redirect(WEBSITE_URL.'scholarship_monitoring_form.php?op='.$op.'&id='.$id);
	die();
}


$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_scholarship_monitoring', 'scholar_mon_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_scholarship_monitoring', 'scholar_mon_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Added.';
}


//echo $sql;
//die();

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'scholarship_monitoring.php');
?>