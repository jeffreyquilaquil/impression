<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_documents.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_documents.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Documentation', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Documentation', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

saveFormCache('psi_project_documents');

/*
echo var_dump($_FILES);
echo "<br>";
echo $_FILES[$file_index]['error'];
echo "<br>";
echo UPLOAD_ERR_NO_FILE;
die();
*/

if (postEmpty('doctype_id')){
	$_SESSION['errmsg'] = "Please select the Document Type.";
	redirect(WEBSITE_URL.'project_documents_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
}

$file_index = 'doc_file';

if ($op == 0){
	if ($_FILES[$file_index]['error'] == UPLOAD_ERR_NO_FILE){
		$_SESSION['errmsg'] = "Document is required.";
		redirect(WEBSITE_URL.'project_documents_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	}
}

if ($_FILES[$file_index]['error'] != UPLOAD_ERR_NO_FILE){
	if (($_FILES[$file_index]['error'] == UPLOAD_ERR_INI_SIZE) || ($_FILES[$file_index]['error'] == UPLOAD_ERR_FORM_SIZE)){
		$_SESSION['errmsg'] = "File size too big : " . $_FILES[$file_index]['name'] ;
		redirect(WEBSITE_URL.'project_documents_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	} elseif ($_FILES[$file_index]['error'] == UPLOAD_ERR_CANT_WRITE){
		$_SESSION['errmsg'] = "Unable to upload file : " . $_FILES[$file_index]['name'] ;
		redirect(WEBSITE_URL.'project_documents_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	} elseif (	(strpos($_FILES[$file_index]['type'], "msword") === false) && 
				(strpos($_FILES[$file_index]['type'], "ms-word") === false) &&
				(strpos($_FILES[$file_index]['type'], "ms-excel") === false) &&
				(strpos($_FILES[$file_index]['type'], "ms-powerpoint") === false) &&
				(strpos($_FILES[$file_index]['type'], "wordprocessing") === false) &&
				(strpos($_FILES[$file_index]['type'], "spreadsheet") === false) &&
				(strpos($_FILES[$file_index]['type'], "presentation") === false) &&
				(strpos($_FILES[$file_index]['type'], "pdf") === false)
			 ){

		//echo "<br>";
		//echo $_FILES[$file_index]['type'] .'<br>';
		//die();

		$_SESSION['errmsg'] = "Invalid file type.";
		redirect(WEBSITE_URL.'project_documents_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	}	

	$original_name = $_FILES[$file_index]['name'];
	$tmp_name = $_FILES[$file_index]['tmp_name'];

	$ext = pathinfo($original_name, PATHINFO_EXTENSION);
	$filename = basename($original_name, '.'.$ext);
	$new_name = randName($filename, $ext);
	$dest_path = PROJECT_DOCS_PATH.DIRECTORY_SEPARATOR.$new_name;
	$GLOBALS[$file_index] = $new_name;
	$GLOBALS['doc_filename'] = $filename;
	// echo $dest_path.'<br>';
	if (!move_uploaded_file($tmp_name, $dest_path)){
		$_SESSION['errmsg'] = "Unable to move file : " . $_FILES[$file_index]['name'];
		redirect(WEBSITE_URL.'project_documents_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	}
}



$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_project_documents', 'doc_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_project_documents', 'doc_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Added.';
}

//echo $sql;
//die();

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_documents.php?pid='.$pid);
?>