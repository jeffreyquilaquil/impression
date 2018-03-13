<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_trainings.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_trainings.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Fora', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Fora', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

saveFormCache('psi_fora', 'col_id');

//echo var_dump($_POST['fr_type_id']);
//die();


if (postEmpty('fr_requesting_party')){
	$_SESSION['errmsg'] = "Requesting Party is required.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('fr_title')){
	$_SESSION['errmsg'] = "Title is required.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('fr_start')){
	$_SESSION['errmsg'] = "Start is required.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif (!postDateTime('fr_start')){
	$_SESSION['errmsg'] = "Start must be a date (mm/dd/yyyy hr:min am/pm).";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('fr_end')){
	$_SESSION['errmsg'] = "End is required.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif (!postDateTime('fr_end')){
	$_SESSION['errmsg'] = "End must be a date (mm/dd/yyyy hr:min am/pm).";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

/*
if (postEmpty('sp_id')){
	$_SESSION['errmsg'] = "Service Provider is required.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}
*/

if (!postEmpty('fr_cost')){
	if (!postFloat('fr_cost')){
		$_SESSION['errmsg'] = "Cost must be a valid number.";
		redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (postEmpty('fr_location')){
	$_SESSION['errmsg'] = "Location is required.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('fr_csf')){
	$_SESSION['errmsg'] = "Overall CSF Rating is required.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif (!postFloat('fr_csf')){
	$_SESSION['errmsg'] = "Overall CSF Rating must be a number.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('ug_id')){
	$_SESSION['errmsg'] = "Implementor is required.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

// participants

if (postEmpty('fr_no_feminine')){
	$_SESSION['errmsg'] = "No. of Feminine Participants is required.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} else if (!postInteger('fr_no_feminine')){
	$_SESSION['errmsg'] = "No. of Feminine Participants must be a number.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif ($GLOBALS['fr_no_feminine'] < 0){
	$_SESSION['errmsg'] = "No. of Feminine Participants is invalid.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('fr_no_masculine')){
	$_SESSION['errmsg'] = "No. of Masculine Participants is required.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} else if (!postInteger('fr_no_masculine')){
	$_SESSION['errmsg'] = "No. of Masculine Participants must be a number.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif ($GLOBALS['fr_no_masculine'] < 0){
	$_SESSION['errmsg'] = "No. of Masculine Participants is invalid.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('fr_no_pwd')){
	$_SESSION['errmsg'] = "No. of PWD Participants is required.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} else if (!postInteger('fr_no_pwd')){
	$_SESSION['errmsg'] = "No. of PWD Participants must be a number.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif ($GLOBALS['fr_no_pwd'] < 0){
	$_SESSION['errmsg'] = "No. of PWD Participants is invalid.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('fr_no_seniors')){
	$_SESSION['errmsg'] = "No. of Senior Citizen Participants is required.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} else if (!postInteger('fr_no_seniors')){
	$_SESSION['errmsg'] = "No. of Senior Citizen Participants must be a number.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif ($GLOBALS['fr_no_seniors'] < 0){
	$_SESSION['errmsg'] = "No. of Senior Citizen Participants is invalid.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('fr_no_firms')){
	$_SESSION['errmsg'] = "No. of Participating Firms is required.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} else if (!postInteger('fr_no_firms')){
	$_SESSION['errmsg'] = "No. of Participating Firms must be a number.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif ($GLOBALS['fr_no_firms'] < 0){
	$_SESSION['errmsg'] = "No. of Participating Firms is invalid.";
	redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (!postEmpty('fr_longitude')){
	if (!postFloat('fr_longitude')){
		$_SESSION['errmsg'] = "Longitude must be a valid number.";
		redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('fr_latitude')){
	if (!postFloat('fr_latitude')){
		$_SESSION['errmsg'] = "Latitude must be a valid number.";
		redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('fr_elevation')){
	if (!postFloat('fr_elevation')){
		$_SESSION['errmsg'] = "Elevation must be a valid number.";
		redirect(WEBSITE_URL.'project_trainings_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}


$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = "DELETE FROM psi_fora_collaborators WHERE fr_id = $id";
	mysqli_query($GLOBALS['cn'], $sql);

	$sql = getUpdateQuery('psi_fora', 'fr_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
	save_collaborators($id);
} else {
	$GLOBALS['region_id'] = $GLOBALS['ad_u_region_id'];
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_fora', 'fr_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$id = mysqli_insert_id($GLOBALS['cn']);
	$msg = 'Record Added.';
	save_collaborators($id);
}


//echo $sql;
//die();

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_trainings.php?pid='.$pid);

function save_collaborators($id){
	if (postEmpty('col_id')) return;
	$sql = "INSERT INTO psi_fora_collaborators (fr_id, col_id) VALUES ";
	$values = '';
	foreach ($GLOBALS['col_id'] as $iid) {
		if (strlen($values) > 0){
			$values .= ', ';
		}
		$values .= "($id, $iid)";
	}
	$sql .= $values;
	mysqli_query($GLOBALS['cn'], $sql);
}

?>