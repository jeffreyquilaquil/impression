<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_collaborators.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_collaborators.php');

if ($op == 1){
    if (!can_access('Collaborating Agencies', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Collaborating Agencies', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_collaborators');

if (postEmpty('col_name')){
	$_SESSION['errmsg'] = "Agency Name is required.";
	redirect(WEBSITE_URL.'lu_collaborators_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('col_abbr')){
	$_SESSION['errmsg'] = "Abbreviation is required.";
	redirect(WEBSITE_URL.'lu_collaborators_form.php?op='.$op.'&id='.$id);
	die();
}

$sql = '';
$msg = '';

if ($op == 1){
	$sql = getUpdateQuery('psi_collaborators', 'col_id');
	$msg = 'Record Updated.';
} else {
	$sql = getInsertQuery('psi_collaborators', 'col_id');
	$msg = 'Record Added.';
}

//echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);
$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'lu_collaborators.php');
?>