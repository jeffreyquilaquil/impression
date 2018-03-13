<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_equipment.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_equipment.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Equipment', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Equipment', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

saveFormCache('psi_equipment');

if (postEmpty('sp_id')){
	$_SESSION['errmsg'] = "Service Provider is required.";
	redirect(WEBSITE_URL.'project_equipment_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
}


if (postEmpty('brand_id')){
	$_SESSION['errmsg'] = "Brand is required.";
	redirect(WEBSITE_URL.'project_equipment_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
}


if (postEmpty('eqp_property_no')){
	$_SESSION['errmsg'] = "Property No. is required.";
	redirect(WEBSITE_URL.'project_equipment_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
} elseif (dbValueUnique('psi_equipment', 'eqp_id', $id, 'eqp_property_no', $GLOBALS['eqp_property_no'])){
	$_SESSION['errmsg'] = "Property No. is already in use.";
	redirect(WEBSITE_URL.'project_equipment_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
}


if (postEmpty('eqp_specs')){
	$_SESSION['errmsg'] = "Equipment Specification is required.";
	redirect(WEBSITE_URL.'project_equipment_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
}

if (postEmpty('eqp_qty')){
	$_SESSION['errmsg'] = "Quantity is required.";
	redirect(WEBSITE_URL.'project_equipment_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
} elseif (!postInteger('eqp_qty')){
	$_SESSION['errmsg'] = "Quantity must be a valid number.";
	redirect(WEBSITE_URL.'project_equipment_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
}


if (postEmpty('eqp_amount_approved')){
	$_SESSION['errmsg'] = "Amount Approved is required.";
	redirect(WEBSITE_URL.'project_equipment_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
} elseif (!postFloat('eqp_amount_approved')){
	$_SESSION['errmsg'] = "Amount Acquired must be a valid number.";
	redirect(WEBSITE_URL.'project_equipment_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
}

if (postEmpty('eqp_amount_acquired')){
	$_SESSION['errmsg'] = "Amount Acquired is required.";
	redirect(WEBSITE_URL.'project_equipment_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
} elseif (!postFloat('eqp_amount_acquired')){
	$_SESSION['errmsg'] = "Amount Acquired must be a valid number.";
	redirect(WEBSITE_URL.'project_equipment_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
}

if (!postEmpty('eqp_receipt_date')){
	if (!postDate('eqp_receipt_date')) {
		$_SESSION['errmsg'] = "Receipt Date must be a valid date. (mm/dd/yyyy)";
		redirect(WEBSITE_URL.'project_equipment_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('eqp_date_acquired')){
	if (!postDate('eqp_date_acquired')) {
		$_SESSION['errmsg'] = "Date Acquired must be a valid date. (mm/dd/yyyy)";
		redirect(WEBSITE_URL.'project_equipment_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	}
}


if (!postEmpty('eqp_date_tagged')){
	if (!postDate('eqp_date_tagged')) {
	$_SESSION['errmsg'] = "Date Tagged must be a valid date. (mm/dd/yyyy)";
	redirect(WEBSITE_URL.'project_equipment_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
	}
}

$sql = '';
$msg = '';

$GLOBALS['prj_id'] = $pid;
$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_equipment', 'eqp_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
} else {

	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_equipment', 'eqp_id');

	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Added.';
}

//echo $sql;
//die();

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_equipment.php?pid='.$pid);
?>