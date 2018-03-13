<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'project_consultancies.php');
$did = requestInteger('did', 'location: '.WEBSITE_URL.'project_consultancies_view.php?pid='.$pid);
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_consultancies_view.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_consultancies_view.php?pid='.$pid);

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

if (!dbValueExists('psi_consultancies', 'con_id', $did, false)){
    redirect(WEBSITE_URL.'project_consultancies.php?pid='.$pid);
    die();
}

saveFormCache('psi_consultancy_documents');

/*
echo var_dump($_FILES);
echo "<br>";
echo $_FILES[$file_index]['error'];
echo "<br>";
echo UPLOAD_ERR_NO_FILE;
die();
*/

$file_index = 'condoc_file';

if ($op == 0){
	if ($_FILES[$file_index]['error'] == UPLOAD_ERR_NO_FILE){
		$_SESSION['errmsg'] = "Document is required.";
		redirect(WEBSITE_URL.'project_consultancies_documents_form.php?pid='.$pid.'&op='.$op.'&id='.$id.'&did='.$did);
		die();
	}
}

if ($_FILES[$file_index]['error'] != UPLOAD_ERR_NO_FILE){
	if (($_FILES[$file_index]['error'] == UPLOAD_ERR_INI_SIZE) || ($_FILES[$file_index]['error'] == UPLOAD_ERR_FORM_SIZE)){
		$_SESSION['errmsg'] = "File size too big : " . $_FILES[$file_index]['name'] ;
		redirect(WEBSITE_URL.'project_consultancies_documents_form.php?pid='.$pid.'&op='.$op.'&id='.$id.'&did='.$did);
		die();
	} elseif ($_FILES[$file_index]['error'] == UPLOAD_ERR_CANT_WRITE){
		$_SESSION['errmsg'] = "Unable to upload file : " . $_FILES[$file_index]['name'] ;
		redirect(WEBSITE_URL.'project_consultancies_documents_form.php?pid='.$pid.'&op='.$op.'&id='.$id.'&did='.$did);
		die();
	} elseif (	(strpos($_FILES[$file_index]['type'], "msword") === false) && 
				(strpos($_FILES[$file_index]['type'], "ms-word") === false) &&
				(strpos($_FILES[$file_index]['type'], "ms-excel") === false) &&
				(strpos($_FILES[$file_index]['type'], "ms-powerpoint") === false) &&
				(strpos($_FILES[$file_index]['type'], "wordprocessing") === false) &&
				(strpos($_FILES[$file_index]['type'], "spreadsheet") === false) &&
				(strpos($_FILES[$file_index]['type'], "presentation") === false) &&
				(strpos($_FILES[$file_index]['type'], "pdf") === false) &&
				(strpos($_FILES[$file_index]['type'], "jpg") === false) &&
				(strpos($_FILES[$file_index]['type'], "jpeg") === false) &&
				(strpos($_FILES[$file_index]['type'], "png") === false) &&
				(strpos($_FILES[$file_index]['type'], "gif") === false) &&
				(strpos($_FILES[$file_index]['type'], "bmp") === false) 
			 ){

		$_SESSION['errmsg'] = "Invalid file type.";
		redirect(WEBSITE_URL.'project_consultancies_documents_form.php?pid='.$pid.'&op='.$op.'&id='.$id.'&did='.$did);
		die();
	}	

	$original_name = $_FILES[$file_index]['name'];
	$tmp_name = $_FILES[$file_index]['tmp_name'];

	$ext = pathinfo($original_name, PATHINFO_EXTENSION);
	$filename = basename($original_name, '.'.$ext);
	$new_name = randName($filename, $ext);
	$dest_path = CONSULTANCY_DOCS_PATH.DIRECTORY_SEPARATOR.$new_name;
	$GLOBALS[$file_index] = $new_name;
	$GLOBALS['condoc_filename'] = $filename;
	// echo $dest_path.'<br>';
	if (!move_uploaded_file($tmp_name, $dest_path)){
		$_SESSION['errmsg'] = "Unable to move file : " . $_FILES[$file_index]['name'];
		redirect(WEBSITE_URL.'project_consultancies_documents_form.php?pid='.$pid.'&op='.$op.'&id='.$id.'&did='.$did);
		die();
	}
}



$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_consultancy_documents', 'condoc_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_consultancy_documents', 'condoc_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Added.';
}

//echo $sql;
//die();

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_consultancies_view.php?pid='.$pid.'&did='.$did);
?>