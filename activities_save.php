<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'activities.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'activities.php');

if ($op == 1){
    if (!can_access('Media Activities', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Media Activities', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_activities');

//echo var_dump($_POST['activity_type_id']);
//die();


if (postEmpty('activity_title')){
	$_SESSION['errmsg'] = "Activity Title is required.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('activity_type_id')){
	$_SESSION['errmsg'] = "Please select a Category.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('activity_date_accomplished')){
	$_SESSION['errmsg'] = "Date Accomplished is required.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
} elseif (!postDate('activity_date_accomplished')){
	$_SESSION['errmsg'] = "Date Accomplished must be a date (mm/dd/yyyy).";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('activity_no_articles')){
	$_SESSION['errmsg'] = "No. of Articles is required.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
} elseif (!postInteger('activity_no_articles')){
	$_SESSION['errmsg'] = "No. of Articles must be a whole number.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('activity_csf')){
	$_SESSION['errmsg'] = "Overall CSF Rating is required.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
} elseif (!postFloat('activity_csf')){
	$_SESSION['errmsg'] = "Overall CSF Rating must be a number.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
}


if (postEmpty('activity_address')){
	$_SESSION['errmsg'] = "Address is required.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
}

// participants

if (postEmpty('activity_no_female')){
	$_SESSION['errmsg'] = "No. of Feminine Participants is required.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('activity_no_female')){
	$_SESSION['errmsg'] = "No. of Feminine Participants must be a number.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['activity_no_female'] < 0){
	$_SESSION['errmsg'] = "No. of Feminine Participants is invalid.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('activity_no_male')){
	$_SESSION['errmsg'] = "No. of Masculine Participants is required.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('activity_no_male')){
	$_SESSION['errmsg'] = "No. of Masculine Participants must be a number.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['activity_no_male'] < 0){
	$_SESSION['errmsg'] = "No. of Masculine Participants is invalid.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('activity_no_pwd')){
	$_SESSION['errmsg'] = "No. of PWD Participants is required.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('activity_no_pwd')){
	$_SESSION['errmsg'] = "No. of PWD Participants must be a number.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['activity_no_pwd'] < 0){
	$_SESSION['errmsg'] = "No. of PWD Participants is invalid.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('activity_no_senior')){
	$_SESSION['errmsg'] = "No. of Senior Citizen Participants is required.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('activity_no_senior')){
	$_SESSION['errmsg'] = "No. of Senior Citizen Participants must be a number.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['activity_no_senior'] < 0){
	$_SESSION['errmsg'] = "No. of Senior Citizen Participants is invalid.";
	redirect(WEBSITE_URL.'activities_form.php?op='.$op.'&id='.$id);
	die();
}


if (!isset($_POST['region_id'])){
	$GLOBALS["region_id"] = $GLOBALS['ad_u_region_id'];	
}

$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_activities', 'activity_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_activities', 'activity_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Added.';
}


//echo $sql;
//die();

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'activities.php');
?>