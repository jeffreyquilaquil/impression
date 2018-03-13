<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'usergroups.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'usergroups.php');

if ($op == 1){
    if (!can_access('UserGroups', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('UserGroups', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_usergroups');

if (postEmpty('ug_name')){
	$_SESSION['errmsg'] = "Group Name is required.";
	redirect(WEBSITE_URL.'usergroups_form.php?op='.$op.'&id='.$id);
	die();
}

if (!isset($_POST['ug_is_admin'])){
	$GLOBALS['ug_is_admin'] = 0;
} 

$sql = '';
$msg = '';

if ($op == 1){
	$sql = getUpdateQuery('psi_usergroups', 'ug_id');
	$msg = 'Record Updated.';
} else {
	$sql = getInsertQuery('psi_usergroups', 'ug_id');
	$msg = 'Record Added.';
}

$ret = ($op == 0 ? mysqli_insert_id($GLOBALS['cn']) : $id);
save_rights($ret);

//echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);
$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'usergroups.php');

function save_rights($pid){

	// delete previous rights
	$sql = "DELETE FROM psi_usergroup_rights WHERE ug_id = $pid";
	mysqli_query($GLOBALS['cn'], $sql);

    $sql = 'SELECT * FROM psi_user_rights ORDER BY ur_name ASC';
    $res = mysqli_query($GLOBALS['cn'], $sql);
    if (!$res) return;

    $records = '';

    while($row = mysqli_fetch_array($res)){
        $id = $row['ur_id'];

        $selected = false;

        $s = "($pid, $id, ";

		if (!isset($_POST['ur'.$id.'_view'])){
			$GLOBALS['ur'.$id.'_view'] = 0;
			$s .= '0, ';
		} else {
			$selected = true;
			$s .= '1, ';
		}

		if (!isset($_POST['ur'.$id.'_add'])){
			$GLOBALS['ur'.$id.'_add'] = 0;
			$s .= '0, ';
		} else {
			$selected = true;
			$s .= '1, ';
		}

		if (!isset($_POST['ur'.$id.'_edit'])){
			$GLOBALS['ur'.$id.'_edit'] = 0;
			$s .= '0, ';
		} else {
			$selected = true;
			$s .= '1, ';
		}

		if (!isset($_POST['ur'.$id.'_delete'])){
			$GLOBALS['ur'.$id.'_delete'] = 0;
			$s .= '0';
		} else {
			$selected = true;
			$s .= '1';
		}

		if (!$selected) continue;

		$s .= ')';

		if (strlen($records) > 0){
			$records .= ', ';
		}
		$records .= $s;
    }
    mysqli_free_result($res);

    if (strlen($records) == 0) return;

    $sql = "INSERT INTO psi_usergroup_rights (ug_id, ur_id, ugr_view, ugr_add, ugr_edit, ugr_delete) VALUES $records";
    
    //echo $sql;
    //die();
    mysqli_query($GLOBALS['cn'], $sql);
}


?>