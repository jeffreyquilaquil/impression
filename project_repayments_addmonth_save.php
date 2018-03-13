<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php?ala');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_repayments.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_repayments.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Repayment', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Repayment', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php?aladin');
    die();
}

saveFormCache('psi_repayments');

// echo var_dump($_POST);
// echo strlen($GLOBALS['rep_start_year']);
// die();

if (postEmpty('rep_month_count')){
	$_SESSION['errmsg'] = "No of Months is required.";
	redirect(WEBSITE_URL.'project_repayments_addmonth_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif (!postInteger('rep_month_count')){
	$_SESSION['errmsg'] = "No of Months must be a number.";
	redirect(WEBSITE_URL.'project_repayments_addmonth_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif ($GLOBALS['rep_month_count'] < 36){
	$_SESSION['errmsg'] = "No of Months must be 36 or greater.";
	redirect(WEBSITE_URL.'project_repayments_addmonth_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}


$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_repayments', 'rep_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_repayments', 'rep_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Added.';
}

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_repayments.php?pid='.$pid);
?>