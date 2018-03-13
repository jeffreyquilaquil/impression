<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'scholarships.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'scholarships.php');

if ($op == 1){
    if (!can_access('Scholarships', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Scholarships', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_scholarships');

//echo var_dump($_POST['scholar_type_id']);
//die();


if (postEmpty('scholar_year_award')){
	$_SESSION['errmsg'] = "Year Awarded is required.";
	redirect(WEBSITE_URL.'scholarships_form.php?op='.$op.'&id='.$id);
	die();
} elseif (!postInteger('scholar_year_award')){
	$_SESSION['errmsg'] = "Year Awarded must be a number.";
	redirect(WEBSITE_URL.'scholarships_form.php?op='.$op.'&id='.$id);
	die();
} elseif (strlen($GLOBALS['scholar_year_award']) < 4){
	$_SESSION['errmsg'] = "Year Awarded must be a valid year.";
	redirect(WEBSITE_URL.'scholarships_form.php?op='.$op.'&id='.$ssid);
	die();
}

if (postEmpty('scholar_fname')){
	$_SESSION['errmsg'] = "First Name is required.";
	redirect(WEBSITE_URL.'scholarships_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('scholar_mname')){
	$_SESSION['errmsg'] = "Middle Name is required.";
	redirect(WEBSITE_URL.'scholarships_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('scholar_lname')){
	$_SESSION['errmsg'] = "Last Name is required.";
	redirect(WEBSITE_URL.'scholarships_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('scholar_address')){
	$_SESSION['errmsg'] = "Address is required.";
	redirect(WEBSITE_URL.'scholarships_form.php?op='.$op.'&id='.$id);
	die();
}

$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_scholarships', 'scholar_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';

	$curr_status = get_current_status($id);
	if ($curr_status != $GLOBALS['scholar_stat_id']){

		$dt = $GLOBALS['last_updated'];
		$user = $GLOBALS['updater'];

		$sql = "INSERT INTO psi_scholarship_status_history (schst_date, scholar_stat_id, scholar_id, encoder, date_encoded, updater, last_updated) VALUES ";
		$sql .= "('$dt', $GLOBALS[scholar_stat_id], $id, '$user', '$dt', '$user', '$dt')";
		mysqli_query($GLOBALS['cn'], $sql);
	}

} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');
	$sql = getInsertQuery('psi_scholarships', 'scholar_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Added.';

	$id = mysqli_insert_id($GLOBALS['cn']);
	$dt = $GLOBALS['date_encoded'];
	$user = $GLOBALS['encoder'];

	$sql = "INSERT INTO psi_scholarship_status_history (schst_date, scholar_stat_id, scholar_id, encoder, date_encoded, updater, last_updated) VALUES ";
	$sql .= "('$dt', $GLOBALS[scholar_stat_id], $id, '$user', '$dt', '$user', '$dt')";
	mysqli_query($GLOBALS['cn'], $sql);
}

//echo $sql;
//die();


$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'scholarships.php');

function get_current_status($id){
	$sql = "SELECT * FROM psi_scholarships WHERE scholar_id = $id";
	$res = mysqli_query($GLOBALS['cn'], $sql);
	$val = 0;
	if ($res) {
		if ($row = mysqli_fetch_array($res)){
			$val = $row['scholar_stat_id'];
		}
		mysqli_free_result($res);
	}
	return $val;
}

?>