<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_gallery.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_gallery.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Photos', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Photos', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

saveFormCache('psi_project_albums');

$file_index = 'album_photos';

//echo "<br>";
//echo var_dump($_FILES[$file_index]);
//echo "<br>";
//echo $file_count;
//die();


if (postEmpty('album_name')){
	$_SESSION['errmsg'] = "Album Name is required.";
	redirect(WEBSITE_URL.'project_gallery_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
}

if (postEmpty('album_desc')){
	$_SESSION['errmsg'] = "Description is required.";
	redirect(WEBSITE_URL.'project_gallery_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
}

if (postEmpty('album_event_date')){
	$_SESSION['errmsg'] = "Date is required.";
	redirect(WEBSITE_URL.'project_gallery_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
	die();
}

$images = array();
$names = array();
$file_count = 0;

if ($op == 0){ // do only if adding new design
	if ($_FILES[$file_index]['error'][0] == UPLOAD_ERR_NO_FILE){
		$_SESSION['errmsg'] = "No files uploaded.";
		redirect(WEBSITE_URL.'project_gallery_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	}
}

if ($_FILES[$file_index]['error'][0] != UPLOAD_ERR_NO_FILE){
	$file_count = count($_FILES[$file_index]['name']);

	// file validation
	for ($i = 0; $i < $file_count; $i++){
		if (($_FILES[$file_index]['error'][$i] == UPLOAD_ERR_INI_SIZE) || ($_FILES[$file_index]['error'][$i] == UPLOAD_ERR_FORM_SIZE)){
			$_SESSION['errmsg'] = "File size too big : " . $_FILES[$file_index]['name'][$i];
			redirect(WEBSITE_URL.'project_gallery_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
			die();
		} elseif ($_FILES[$file_index]['error'][$i] == UPLOAD_ERR_CANT_WRITE){
			$_SESSION['errmsg'] = "Unable to upload file : " . $_FILES[$file_index]['name'][$i];
			redirect(WEBSITE_URL.'project_gallery_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
			die();
		} elseif (($_FILES[$file_index]['type'][$i] != 'image/gif') && ($_FILES[$file_index]['type'][$i] != 'image/jpeg') && ($_FILES[$file_index]['type'][$i] != 'image/jpg') && ($_FILES[$file_index]['type'][$i] != 'image/x-png') && ($_FILES[$file_index]['type'][$i] != 'image/png')){
			$_SESSION['errmsg'] = "Only Png, Jpg or Gif files are allowed.";
			redirect(WEBSITE_URL.'project_gallery_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
			die();
		}
	}

	// move files
	for ($i = 0; $i < $file_count; $i++){

		$original_name = $_FILES[$file_index]['name'][$i];
		$tmp_name = $_FILES[$file_index]['tmp_name'][$i];

		$ext = pathinfo($original_name, PATHINFO_EXTENSION);
		$filename = basename($original_name, '.'.$ext);
		$new_name = randName($filename, $ext);
		$dest_path = GALLERY_PATH.DIRECTORY_SEPARATOR.$new_name;
		$images[] = $new_name;
		$names[] = $filename;

		// echo $dest_path.'<br>';
		if (!move_uploaded_file($tmp_name, $dest_path)){
			$_SESSION['errmsg'] = "Unable to move file : " . $_FILES[$file_index]['name'][$i];
			redirect(WEBSITE_URL.'project_gallery_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
			die();
		}
	}
}

$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_project_albums', 'album_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_project_albums', 'album_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Added.';
}


$ret = ($op == 0 ? mysqli_insert_id($GLOBALS['cn']) : $id);
// save new images;

if ($op == 0){
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');
	$sql = "INSERT INTO psi_project_album_photos (album_id, photo_file, photo_filename, encoder, date_encoded, updater, last_updated) VALUES ";
	$values = '';
	$date = date('Y-m-d H:i:s');
	$max = count($images);
	for($i = 0; $i < $max; $i++){
		if (strlen($values) > 0){
			$values .= ', ';
		}
		$values .=	"($ret, '$images[$i]', '$names[$i]', '$GLOBALS[encoder]', '$GLOBALS[date_encoded]', '$GLOBALS[updater]', '$GLOBALS[last_updated]')";
	}
	$sql .= $values;
	mysqli_query($GLOBALS['cn'], $sql);
} else if ($op == 1){
	if (count($images) > 0){
		$sql = "INSERT INTO psi_project_album_photos (album_id, photo_file, photo_filename, updater, last_updated) VALUES ";
		$values = '';
		$date = date('Y-m-d H:i:s');
		$max = count($images);
		for($i = 0; $i < $max; $i++){
			if (strlen($values) > 0){
				$values .= ', ';
			}
			$values .=	"($ret, '$images[$i]', '$names[$i]', '$GLOBALS[updater]', '$GLOBALS[last_updated]')";
		}
		$sql .= $values;
		mysqli_query($GLOBALS['cn'], $sql);
	}
}
//echo $sql.'<br>';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_gallery.php?pid='.$pid);
?>