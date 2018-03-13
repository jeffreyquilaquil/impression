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

if (postEmpty('rep_start_year')){
	$_SESSION['errmsg'] = "Payment Starting Year is required.";
	redirect(WEBSITE_URL.'project_repayments_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif (!postInteger('rep_start_year')){
	$_SESSION['errmsg'] = "Payment Starting Year must be a number.";
	redirect(WEBSITE_URL.'project_repayments_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif (strlen($GLOBALS['rep_start_year']) < 4){
	$_SESSION['errmsg'] = "Payment Starting Year must be a valid year.";
	redirect(WEBSITE_URL.'project_repayments_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('rep_start_month')){
	$_SESSION['errmsg'] = "Payment Starting Month is required.";
	redirect(WEBSITE_URL.'project_repayments_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('rep_amount')){
	$_SESSION['errmsg'] = "SETUP Cost is required.";
	redirect(WEBSITE_URL.'project_repayments_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif (!postFloat('rep_amount')){
	$_SESSION['errmsg'] = "SETUP Cost must be a valid number.";
	redirect(WEBSITE_URL.'project_repayments_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('rep_otb')){
	$_SESSION['errmsg'] = "Option to Purchase is required.";
	redirect(WEBSITE_URL.'project_repayments_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif (!postFloat('rep_otb')){
	$_SESSION['errmsg'] = "Option to Purchase must be a valid number.";
	redirect(WEBSITE_URL.'project_repayments_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('rep_ub_amount')){
	$_SESSION['errmsg'] = "Unexpended Balance is required.";
	redirect(WEBSITE_URL.'project_repayments_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif (!postFloat('rep_ub_amount')){
	$_SESSION['errmsg'] = "Unexpended Balance must be a valid number.";
	redirect(WEBSITE_URL.'project_repayments_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('rep_add_amount')){
	$_SESSION['errmsg'] = "Additional is required.";
	redirect(WEBSITE_URL.'project_repayments_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif (!postFloat('rep_add_amount')){
	$_SESSION['errmsg'] = "Additional must be a valid number.";
	redirect(WEBSITE_URL.'project_repayments_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('rep_monthly_payment')){
	$_SESSION['errmsg'] = "Monthly Payment is required.";
	redirect(WEBSITE_URL.'project_repayments_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif (!postFloat('rep_monthly_payment')){
	$_SESSION['errmsg'] = "Monthly Payment must be a valid number.";
	redirect(WEBSITE_URL.'project_repayments_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
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

//echo $sql;
//die();

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_repayments.php?pid='.$pid);
?>