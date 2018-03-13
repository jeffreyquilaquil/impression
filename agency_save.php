<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Agency Profile', 'edit')){
    redirect(WEBSITE_URL.'index.php');
}

$id = 1;

if (!dbValueExists('psi_agencies', 'agency_id', $id, false)){
    redirect(WEBSITE_URL.'agency_view.php?walangid');
    die();
}

saveFormCache('psi_agencies');

$file_index = 'agency_file';

if ($_FILES[$file_index]['error'] != UPLOAD_ERR_NO_FILE){
	if (($_FILES[$file_index]['error'] == UPLOAD_ERR_INI_SIZE) || ($_FILES[$file_index]['error'] == UPLOAD_ERR_FORM_SIZE)){
		$_SESSION['errmsg'] = "File size too big : " . $_FILES[$file_index]['name'] ;
		redirect(WEBSITE_URL.'agency_form.php');
		die();
	} elseif ($_FILES[$file_index]['error'] == UPLOAD_ERR_CANT_WRITE){
		$_SESSION['errmsg'] = "Unable to upload file : " . $_FILES[$file_index]['name'] ;
		redirect(WEBSITE_URL.'agency_form.php');
		die();
	} elseif (
		(strpos($_FILES[$file_index]['type'], "jpg") === false) &&
		(strpos($_FILES[$file_index]['type'], "jpeg") === false) &&
		(strpos($_FILES[$file_index]['type'], "png") === false) &&
		(strpos($_FILES[$file_index]['type'], "gif") === false) &&
		(strpos($_FILES[$file_index]['type'], "bmp") === false)
	){
		$_SESSION['errmsg'] = "Only Png, Jpg or Gif files are allowed.";
		redirect(WEBSITE_URL.'agency_form.php');
		die();
	}

	$original_name = $_FILES[$file_index]['name'];
	$tmp_name = $_FILES[$file_index]['tmp_name'];

	$ext = pathinfo($original_name, PATHINFO_EXTENSION);
	$filename = basename($original_name, '.'.$ext);
	$new_name = randName($filename, $ext);
	$dest_path = AGENCY_PATH.DIRECTORY_SEPARATOR.$new_name;
	$GLOBALS['agency_file'] = $new_name;
	$GLOBALS['agency_filename'] = $filename;
	// echo $dest_path.'<br>';
	if (!move_uploaded_file($tmp_name, $dest_path)){
		$_SESSION['errmsg'] = "Unable to move file : " . $_FILES[$file_index]['name'];
		redirect(WEBSITE_URL.'agency_form.php');
		die();
	}

}


$sql = '';
$msg = '';

$GLOBALS['agency_id'] = 1;

$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

$sql = getUpdateQuery('psi_agencies', 'agency_id');

//echo $sql;
//die();

mysqli_query($GLOBALS['cn'], $sql);
$msg = 'Record Updated.';



$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'agency_view.php');
?>