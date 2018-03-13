<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_consultancies.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_consultancies.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Consultancies', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Consultancies', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

saveFormCache('psi_consultancies');

//echo var_dump($_POST['con_type_id']);
//die();

if (postEmpty('sp_id')){
	$_SESSION['errmsg'] = "Service Provider is required.";
	redirect(WEBSITE_URL.'project_consultancies_form.php?op='.$op.'&pid='.$pid.'&id='.$id);
	die();
}

if (postEmpty('con_type_id')){
	$_SESSION['errmsg'] = "Please select the Consultancy Type.";
	redirect(WEBSITE_URL.'project_consultancies_form.php?op='.$op.'&pid='.$pid.'&id='.$id);
	die();
}

if (postEmpty('con_start')){
	$_SESSION['errmsg'] = "Consultancy Start is required.";
	redirect(WEBSITE_URL.'project_consultancies_form.php?op='.$op.'&pid='.$pid.'&id='.$id);
	die();
} elseif (!postDate('con_start')){
	$_SESSION['errmsg'] = "Consultancy Start must be a date (mm/dd/yyyy).";
	redirect(WEBSITE_URL.'project_consultancies_form.php?op='.$op.'&pid='.$pid.'&id='.$id);
	die();
}

if (postEmpty('con_end')){
	$_SESSION['errmsg'] = "Consultancy End is required.";
	redirect(WEBSITE_URL.'project_consultancies_form.php?op='.$op.'&pid='.$pid.'&id='.$id);
	die();
} elseif (!postDate('con_end')){
	$_SESSION['errmsg'] = "Consultancy End must be a date (mm/dd/yyyy).";
	redirect(WEBSITE_URL.'project_consultancies_form.php?op='.$op.'&pid='.$pid.'&id='.$id);
	die();
}

//******************************************************************
$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){

	$sql = getUpdateQuery('psi_consultancies', 'con_id');

	//echo var_dump($_POST);
	//echo $sql;
	//die();
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_consultancies', 'con_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Added.';
}


//echo $sql;
//die();

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_consultancies.php?pid='.$pid);
?>