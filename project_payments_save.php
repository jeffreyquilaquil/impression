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
	redirect(WEBSITE_URL.'project_repayments.php?pid='.$pid);	
}

saveFormCache('psi_repayments_payments');

if (postEmpty('pay_amount_due')){
	$_SESSION['errmsg'] = "Amount Due is required.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
} elseif (!postFloat('pay_amount_due')){
	$_SESSION['errmsg'] = "Amount Due must be a valid number.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
/*} elseif (($GLOBALS['pay_amount_due'] > $rep['rep_balance']) && ($rep['rep_balance'] > 0)){
	$_SESSION['errmsg'] = "Amount Due must be equal to or lower than the remaining balance.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
*/	
}

if (postEmpty('pay_amount_paid')){
	$_SESSION['errmsg'] = "Amount Paid is required.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
} elseif (!postFloat('pay_amount_paid')){
	$_SESSION['errmsg'] = "Amount Paid must be a valid number.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
/*	
} elseif (($GLOBALS['pay_amount_paid'] > $rep['rep_balance']) && ($rep['rep_balance'] > 0)){
	$_SESSION['errmsg'] = "Amount Paid must be equal to or lower than the remaining balance.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
*/	
}

if (!postEmpty('pay_amount_date_paid')){
	if (!postDate('pay_amount_date_paid')) {
		$_SESSION['errmsg'] = "Date Paid must be a valid date. (mm/dd/yyyy)";
		redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
		die();
	}
}

if (postEmpty('pay_count')){
	$_SESSION['errmsg'] = "No. of Months Paid For is required.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
} elseif (!postInteger('pay_count')){
	$_SESSION['errmsg'] = "No. of Months Paid For must be a valid number.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
} elseif ($GLOBALS['pay_count'] < 1){
	$_SESSION['errmsg'] = "No. of Months Paid For must be equal to or greater than 1.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
}

if (month_range_invalid($rep, $yr, $mo, $GLOBALS['pay_count'], $id)){
	$_SESSION['errmsg'] = "Payment overlaps other records.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
}

if ($GLOBALS['pay_type_id'] == 2){
	if (postEmpty('pay_check_no')){
		$_SESSION['errmsg'] = "Check No. is required.";
		redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
		die();
	}

	if (postEmpty('pay_check_date')){
		$_SESSION['errmsg'] = "Check Date is required.";
		redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
		die();
	}
} else {
	unset($GLOBALS['pay_check_date']);
	unset($GLOBALS['pay_check_no']);
}

if (!postEmpty('pay_check_date')){
	if (!postDate('pay_check_date')) {
		$_SESSION['errmsg'] = "Check Date must be a valid date. (mm/dd/yyyy)";
		redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
		die();
	}
}


if (postEmpty('pay_overdue_amount_due')){
	$_SESSION['errmsg'] = "Overdue Amount is required.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
} elseif (!postFloat('pay_overdue_amount_due')){
	$_SESSION['errmsg'] = "Overdue Amount must be a valid number.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
} elseif ($GLOBALS['pay_overdue_amount_due'] < 0){
	$_SESSION['errmsg'] = "Overdue Amount must be equal to or greater than 0.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
}

/*
if (postEmpty('pay_overdue_amount_paid')){
	$_SESSION['errmsg'] = "Overdue Amount Paid is required.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
} elseif (!postFloat('pay_overdue_amount_paid')){
	$_SESSION['errmsg'] = "Overdue Amount Paid must be a valid number.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
} elseif ($GLOBALS['pay_overdue_amount_paid'] > $GLOBALS['pay_overdue_amount_due']){
	$_SESSION['errmsg'] = "Overdue Amount Paid must be equal to or greater than 0.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
}

if (!postEmpty('pay_overdue_date_paid')){
	if (!postDate('pay_overdue_date_paid')) {
		$_SESSION['errmsg'] = "Overdue Date Paid must be a valid date. (mm/dd/yyyy)";
		redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
		die();
	}
}
*/

if (postEmpty('pay_penalty_amount_due')){
	$_SESSION['errmsg'] = "Penalty Amount Due is required.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
} elseif (!postFloat('pay_penalty_amount_due')){
	$_SESSION['errmsg'] = "Penalty Amount Due must be a valid number.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
} elseif ($GLOBALS['pay_penalty_amount_due'] < 0){
	$_SESSION['errmsg'] = "Penalty Amount Due must be equal to or greater than 0.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
}

if (postEmpty('pay_penalty_amount_paid')){
	$_SESSION['errmsg'] = "Penalty Amount Paid is required.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
} elseif (!postFloat('pay_penalty_amount_paid')){
	$_SESSION['errmsg'] = "Penalty Amount Paid must be a valid number.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
} elseif ($GLOBALS['pay_penalty_amount_paid'] > $GLOBALS['pay_penalty_amount_due']){
	$_SESSION['errmsg'] = "Penalty Amount Paid must be equal to or greater than 0.";
	redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
	die();
}

if ($GLOBALS['pay_amount_paid'] == 0){
	unset($GLOBALS['pay_amount_date_paid']);
}

if ($GLOBALS['pay_penalty_amount_paid'] == 0){
	unset($GLOBALS['pay_penalty_date_paid']);
}

if (!postEmpty('pay_penalty_date_paid')){
	if (!postDate('pay_penalty_date_paid')) {
		$_SESSION['errmsg'] = "Penalty Date Paid must be a valid date. (mm/dd/yyyy)";
		redirect(WEBSITE_URL.'project_payments_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
		die();
	}
}

$GLOBALS['pay_otb'] = 0;

$sql = '';
$msg = '';

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