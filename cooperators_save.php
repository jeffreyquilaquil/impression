<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'cooperators.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'cooperators.php');

if ($op == 1){
    if (!can_access('Cooperators', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Cooperators', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$sectors = save_form_sectors();

if (strlen($sectors) > 0){
	$sectors .= ', ';
}

$sectors .= 'sector_others';

//echo var_dump($GLOBALS['gsectors']);
//die();

saveFormCache('psi_cooperators', $sectors);

if (postEmpty('coop_name')){
	$_SESSION['errmsg'] = "Cooperator Name is required.";
	redirect(WEBSITE_URL.'cooperators_form.php?op='.$op.'&id='.$id);
	die();
}

/*
if (postEmpty('coop_p_fname')){
	$_SESSION['errmsg'] = "Contact Person's First Name is required.";
	redirect(WEBSITE_URL.'cooperators_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('coop_p_mname')){
	$_SESSION['errmsg'] = "Contact Person's Middle Name is required.";
	redirect(WEBSITE_URL.'cooperators_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('coop_p_lname')){
	$_SESSION['errmsg'] = "Contact Person's Last Name is required.";
	redirect(WEBSITE_URL.'cooperators_form.php?op='.$op.'&id='.$id);
	die();
}

*/

if (postEmpty('coop_address')){
	$_SESSION['errmsg'] = "Address is required.";
	redirect(WEBSITE_URL.'cooperators_form.php?op='.$op.'&id='.$id);
	die();
}

if (!postEmpty('coop_phone')){
	if (dbValueUnique('psi_cooperators', 'coop_id', $id, 'coop_phone', $GLOBALS['coop_phone'])){
		$_SESSION['errmsg'] = "Phone is already in use.";
		redirect(WEBSITE_URL.'cooperators_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('coop_mobile')){
	if (dbValueUnique('psi_cooperators', 'coop_id', $id, 'coop_mobile', $GLOBALS['coop_mobile'])){
		$_SESSION['errmsg'] = "Mobile is already in use.";
		redirect(WEBSITE_URL.'cooperators_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('coop_fax')){
	if (dbValueUnique('psi_cooperators', 'coop_id', $id, 'coop_fax', $GLOBALS['coop_fax'])){
		$_SESSION['errmsg'] = "Fax is already in use.";
		redirect(WEBSITE_URL.'cooperators_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('coop_email')){

	if (!isEmail('coop_email')){
		$_SESSION['errmsg'] = "Email is invalid.";
		redirect(WEBSITE_URL.'cooperators_form.php?op='.$op.'&id='.$id);
		die();
	}

	if (dbValueUnique('psi_cooperators', 'coop_id', $id, 'coop_email', $GLOBALS['coop_email'])){
		$_SESSION['errmsg'] = "Email is already in use.";
		redirect(WEBSITE_URL.'cooperators_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (postEmpty('coop_year_established')){
	$_SESSION['errmsg'] = "Year Established is required.";
	redirect(WEBSITE_URL.'cooperators_form.php?op='.$op.'&id='.$id);
	die();
}

if (!postInteger('coop_year_established')){
	$_SESSION['errmsg'] = "Year Established must be a number.";
	redirect(WEBSITE_URL.'cooperators_form.php?op='.$op.'&id='.$id);
	die();
}

$yr = intval($GLOBALS['coop_year_established']);

if ($yr < 1800) {
	$_SESSION['errmsg'] = "Year Established is too far behind.";
	redirect(WEBSITE_URL.'cooperators_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($yr > intval(date('Y'))) {
	$_SESSION['errmsg'] = "Cooperator has not been established yet.";
	redirect(WEBSITE_URL.'cooperators_form.php?op='.$op.'&id='.$id);
	die();
}

if (!postEmpty('coop_reg_dti_date')){
	if (!postDate('coop_reg_dti_date')){
		$_SESSION['errmsg'] = "Date Of Registration (DTI) must be a date (mm/dd/yyyy).";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('coop_reg_sec_date')){
	if (!postDate('coop_reg_sec_date')){
		$_SESSION['errmsg'] = "Date Of Registration (SEC) must be a date (mm/dd/yyyy).";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('coop_reg_cda_date')){
	if (!postDate('coop_reg_cda_date')){
		$_SESSION['errmsg'] = "Date Of Registration (CDA) must be a date (mm/dd/yyyy).";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){

	$sql = "DELETE FROM psi_coop_sectors WHERE coop_id = $id";
	mysqli_query($GLOBALS['cn'], $sql);

	$sql = getUpdateQuery('psi_cooperators', 'coop_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_cooperators', 'coop_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$id = mysqli_insert_id($GLOBALS['cn']);
	$msg = 'Record Added.';

}

save_other_sectors();
save_sectors($id);
//echo $sql;
//die();

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'cooperators.php');

function save_sectors($id){
	$sql = 'INSERT INTO psi_coop_sectors (coop_id, sector_id) VALUES ';
	$values = '';
	foreach ($GLOBALS['gsectors'] as $value) {

		if (strlen($values) > 0){
			$values .= ', ';
		}
		$values .= "($id, $value)";
	}
	$sql .= $values;
	mysqli_query($GLOBALS['cn'], $sql);
}
?>