<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_sites.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_sites.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Sites', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Sites', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

if (!is_project_type($pid, 8)){
    redirect(WEBSITE_URL."projects_view.php?pid=$pid");
}

saveFormCache('psi_project_sites');

if (postEmpty('prj_site_date')){
	$_SESSION['errmsg'] = "Date Deployed is required.";
	redirect(WEBSITE_URL.'project_sites_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif (!postDate('prj_site_date')){
	$_SESSION['errmsg'] = "Date Deployed must be a date (mm/dd/yyyy).";
	redirect(WEBSITE_URL.'project_sites_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}


if (!postEmpty('prj_site_longitude')){
	if (!postFloat('prj_site_longitude')){
		$_SESSION['errmsg'] = "Longitude must be a valid number.";
		redirect(WEBSITE_URL.'project_sites_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prj_site_latitude')){
	if (!postFloat('prj_site_latitude')){
		$_SESSION['errmsg'] = "Latitude must be a valid number.";
		redirect(WEBSITE_URL.'project_sites_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prj_site_elevation')){
	if (!postFloat('prj_site_elevation')){
		$_SESSION['errmsg'] = "Elevation must be a valid number.";
		redirect(WEBSITE_URL.'project_sites_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}


$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_project_sites', 'prj_site_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
} else {
	$GLOBALS['region_id'] = $GLOBALS['ad_u_region_id'];
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_project_sites', 'prj_site_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$id = mysqli_insert_id($GLOBALS['cn']);
	$msg = 'Record Added.';
}


$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_sites.php?pid='.$pid);
?>