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

if (!postEmpty('pay_amount_date_paid')){
	if (!postDate('pay_amount_date_paid')) {
		$_SESSION['errmsg'] = "Date Paid must be a valid date. (mm/dd/yyyy)";
		redirect(WEBSITE_URL.'project_payments_otb_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
		die();
	}
}

if ($GLOBALS['pay_type_id'] == 2){
	if (postEmpty('pay_check_no')){
		$_SESSION['errmsg'] = "Check No. is required.";
		redirect(WEBSITE_URL.'project_payments_otb_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
		die();
	}

	if (postEmpty('pay_check_date')){
		$_SESSION['errmsg'] = "Check Date is required.";
		redirect(WEBSITE_URL.'project_payments_otb_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
		die();
	}
} else {
	unset($GLOBALS['pay_check_date']);
	unset($GLOBALS['pay_check_no']);
}

if (!postEmpty('pay_check_date')){
	if (!postDate('pay_check_date')) {
		$_SESSION['errmsg'] = "Check Date must be a valid date. (mm/dd/yyyy)";
		redirect(WEBSITE_URL.'project_payments_otb_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&yr='.$yr.'&mo='.$mo);
		die();
	}
}

unset($GLOBALS['pay_penalty_amount_due']);
unset($GLOBALS['pay_penalty_amount_paid']);
unset($GLOBALS['pay_penalty_date_paid']);

$GLOBALS['pay_amount_due'] = $rep['rep_otb_amount'];
$GLOBALS['pay_amount_paid'] = $rep['rep_otb_amount'];
$GLOBALS['pay_count'] = 1;
$GLOBALS['pay_otb'] = 1;

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