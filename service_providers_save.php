<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'service_providers.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'service_providers.php');

if ($op == 1){
    if (!can_access('Service Providers', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Service Providers', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}


saveFormCache('psi_service_providers', 'sp_type_id');

//echo var_dump($_POST['sp_type_id']);
//die();


if (postEmpty('sp_name')){
	$_SESSION['errmsg'] = "Company Name is required.";
	redirect(WEBSITE_URL.'service_providers_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('sp_type_id')){
	$_SESSION['errmsg'] = "Please choose Services Provided.";
	redirect(WEBSITE_URL.'service_providers_form.php?op='.$op.'&id='.$id);
	die();
}

if (in_array(4, $GLOBALS['sp_type_id'])){
	if (postEmpty('sp_other_service')){
		$_SESSION['errmsg'] = "Please specify service provided (Other).";
		redirect(WEBSITE_URL.'service_providers_form.php?op='.$op.'&id='.$id);
		die();
	}
} else {
	$GLOBALS['sp_other_service'] = '';
}

/*

if (postEmpty('sp_fname')){
	$_SESSION['errmsg'] = "Contact Person's First Name is required.";
	redirect(WEBSITE_URL.'service_providers_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('sp_mname')){
	$_SESSION['errmsg'] = "Contact Person's Middle Name is required.";
	redirect(WEBSITE_URL.'service_providers_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('sp_lname')){
	$_SESSION['errmsg'] = "Contact Person's Last Name is required.";
	redirect(WEBSITE_URL.'service_providers_form.php?op='.$op.'&id='.$id);
	die();
}


if (postEmpty('sp_designation')){
	$_SESSION['errmsg'] = "Contact Person's Designation is required.";
	redirect(WEBSITE_URL.'service_providers_form.php?op='.$op.'&id='.$id);
	die();
}
*/

if (postEmpty('sp_address')){
	$_SESSION['errmsg'] = "Address is required.";
	redirect(WEBSITE_URL.'service_providers_form.php?op='.$op.'&id='.$id);
	die();
}

if (!postEmpty('sp_phone')){
	if (dbValueUnique('psi_service_providers', 'sp_id', $id, 'sp_phone', $GLOBALS['sp_phone'])){
		$_SESSION['errmsg'] = "Phone is already in use.";
		redirect(WEBSITE_URL.'service_providers_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('sp_mobile')){
	if (dbValueUnique('psi_service_providers', 'sp_id', $id, 'sp_mobile', $GLOBALS['sp_mobile'])){
		$_SESSION['errmsg'] = "Mobile is already in use.";
		redirect(WEBSITE_URL.'service_providers_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('sp_email')){

	if (!isEmail($GLOBALS['sp_email'])){
		$_SESSION['errmsg'] = "Email is invalid.";
		redirect(WEBSITE_URL.'service_providers_form.php?op='.$op.'&id='.$id);
		die();
	}

	if (dbValueUnique('psi_service_providers', 'sp_id', $id, 'sp_email', $GLOBALS['sp_email'])){
		$_SESSION['errmsg'] = "Email is already in use.";
		redirect(WEBSITE_URL.'service_providers_form.php?op='.$op.'&id='.$id);
		die();
	}
}

$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_service_providers', 'sp_id');
	mysqli_query($GLOBALS['cn'], $sql);

	$sql = "DELETE FROM psi_service_provider_services WHERE sp_id = $id";
	mysqli_query($GLOBALS['cn'], $sql);

	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');
	$sql = getInsertQuery('psi_service_providers', 'sp_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$id = mysqli_insert_id($GLOBALS['cn']);
	$msg = 'Record Added.';
}


$sql = "INSERT INTO psi_service_provider_services (sp_id, service_id) VALUES ";
$values = '';
foreach ($GLOBALS['sp_type_id'] as $sp_type) {
	if (strlen($values) > 0){
		$values .= ', ';
	}
	$values .= "($id, $sp_type)";
}

$sql .= $values;

mysqli_query($GLOBALS['cn'], $sql);

//echo $sql;
//die();

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'service_providers.php');
?>