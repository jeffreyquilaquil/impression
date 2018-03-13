<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_repayments.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_repayments.php?pid='.$pid);
$yr = requestInteger('yr', 'location: '.WEBSITE_URL.'project_repayments.php?pid='.$pid);
$mo = requestInteger('mo', 'location: '.WEBSITE_URL.'project_repayments.php?pid='.$pid);

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
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$rep = getRepayment($pid);
if (!$rep){
	$_SESSION['errmsg'] = "No repayment schedule.";
    redirect(WEBSITE_URL.'project_repayments.php?pid='.$pid);
}

if ($rep['rep_deferment_monthcount'] >= 24){
	$_SESSION['errmsg'] = "Total deferments can't exceed 24 months.";
	redirect(WEBSITE_URL.'project_repayments.php?pid='.$pid);
}

if ($rep['rep_real_balance'] == 0){
	$_SESSION['errmsg'] = "No payments to defer.";
	redirect(WEBSITE_URL.'project_repayments.php?pid='.$pid);
}

saveFormCache('psi_repayments_payments');

// echo var_dump($_POST);
// echo strlen($GLOBALS['rep_start_year']);
// die();


$max_defs = 24 - $rep['rep_deferment_monthcount'];


if (postEmpty('pay_count')){
	$_SESSION['errmsg'] = "No. of Months to Defer is required.";
	redirect(WEBSITE_URL.'project_deferments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
} elseif (!postInteger('pay_count')){
	$_SESSION['errmsg'] = "No. of Months to Defer must be a valid number.";
	redirect(WEBSITE_URL.'project_deferments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
} elseif ($GLOBALS['pay_count'] > $max_defs){
	$_SESSION['errmsg'] = "No. of Months to Defer must not exceed $max_defs.";
	redirect(WEBSITE_URL.'project_deferments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
}


if (month_range_invalid($rep, $yr, $mo, $GLOBALS['pay_count'], $id, false)){
	$_SESSION['errmsg'] = "Deferment overlaps other records.";
	redirect(WEBSITE_URL.'project_deferments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
}

$sql = '';
$msg = '';

$GLOBALS['pay_otb'] = 2;

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_repayments_payments', 'pay_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_repayments_payments', 'pay_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Added.';
}

//echo $sql;
//die();

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_repayments.php?pid='.$pid);
?>