<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'trainings.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'trainings.php');

if ($op == 1){
    if (!can_access('Trainings', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Trainings', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_trainings');

//echo var_dump($_POST['tr_type_id']);
//die();


if (postEmpty('tr_title')){
	$_SESSION['errmsg'] = "Training Title is required.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('tr_start')){
	$_SESSION['errmsg'] = "Training Start is required.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
} elseif (!postDate('tr_start')){
	$_SESSION['errmsg'] = "Training Start must be a date (mm/dd/yyyy).";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('tr_end')){
	$_SESSION['errmsg'] = "Training End is required.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
} elseif (!postDate('tr_end')){
	$_SESSION['errmsg'] = "Training End must be a date (mm/dd/yyyy).";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('sp_id')){
	$_SESSION['errmsg'] = "Service Provider is required.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
}

if (!postEmpty('tr_cost')){
	if (!postFloat('tr_cost')){
		$_SESSION['errmsg'] = "Training Cost must be a valid number.";
		redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (postEmpty('tr_location')){
	$_SESSION['errmsg'] = "Location is required.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('tr_csf')){
	$_SESSION['errmsg'] = "Overall CSF Rating is required.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
} elseif (!postFloat('tr_csf')){
	$_SESSION['errmsg'] = "Overall CSF Rating must be a number.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('ug_id')){
	$_SESSION['errmsg'] = "Implementor is required.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
}

// participants

if (postEmpty('tr_no_feminine')){
	$_SESSION['errmsg'] = "No. of Feminine Participants is required.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('tr_no_feminine')){
	$_SESSION['errmsg'] = "No. of Feminine Participants must be a number.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['tr_no_feminine'] < 0){
	$_SESSION['errmsg'] = "No. of Feminine Participants is invalid.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('tr_no_musculine')){
	$_SESSION['errmsg'] = "No. of Masculine Participants is required.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('tr_no_musculine')){
	$_SESSION['errmsg'] = "No. of Masculine Participants must be a number.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['tr_no_musculine'] < 0){
	$_SESSION['errmsg'] = "No. of Masculine Participants is invalid.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('tr_no_pwd')){
	$_SESSION['errmsg'] = "No. of PWD Participants is required.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('tr_no_pwd')){
	$_SESSION['errmsg'] = "No. of PWD Participants must be a number.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['tr_no_pwd'] < 0){
	$_SESSION['errmsg'] = "No. of PWD Participants is invalid.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('tr_no_seniors')){
	$_SESSION['errmsg'] = "No. of Senior Citizen Participants is required.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('tr_no_seniors')){
	$_SESSION['errmsg'] = "No. of Senior Citizen Participants must be a number.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['tr_no_seniors'] < 0){
	$_SESSION['errmsg'] = "No. of Senior Citizen Participants is invalid.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('tr_no_firms')){
	$_SESSION['errmsg'] = "No. of Participating Firms is required.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('tr_no_firms')){
	$_SESSION['errmsg'] = "No. of Participating Firms must be a number.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['tr_no_firms'] < 0){
	$_SESSION['errmsg'] = "No. of Participating Firms is invalid.";
	redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
	die();
}

if (!postEmpty('tr_longitude')){
	if (!postFloat('tr_longitude')){
		$_SESSION['errmsg'] = "Longitude must be a valid number.";
		redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('tr_latitude')){
	if (!postFloat('tr_latitude')){
		$_SESSION['errmsg'] = "Latitude must be a valid number.";
		redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('tr_elevation')){
	if (!postFloat('tr_elevation')){
		$_SESSION['errmsg'] = "Elevation must be a valid number.";
		redirect(WEBSITE_URL.'trainings_form.php?op='.$op.'&id='.$id);
		die();
	}
}


$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_trainings', 'tr_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_trainings', 'tr_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Added.';
}


//echo $sql;
//die();

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'trainings.php');
?>