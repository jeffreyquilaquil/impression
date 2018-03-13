<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'packaging.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'packaging.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'packaging.php');

if ($op == 1){
    if (!can_access('Packaging & Labeling Designs', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Packaging & Labeling Designs', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_packaging_designs');
//echo var_dump($_FILES);
//die();

if ($op == 0){
	$draft_level = getCount('psi_packaging_designs', 'design_id', "WHERE pkg_id = $pid");
	$draft_level += 1;
	if ($draft_level > 3){
		$_SESSION['errmsg'] = "Final Draft has already been uploaded.";
		redirect(WEBSITE_URL.'packaging_designs_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	}
	$GLOBALS['draftlevel_id'] = $draft_level;
}

$images = array();

$file_index = 'design_image1';
if ($_FILES[$file_index]['error'] == UPLOAD_ERR_NO_FILE){
	if ($op == 0){
		$_SESSION['errmsg'] = "Original Design is required.";
		redirect(WEBSITE_URL.'packaging_designs_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	}
} else {

	if (($_FILES[$file_index]['error'] == UPLOAD_ERR_INI_SIZE) || ($_FILES[$file_index]['error'] == UPLOAD_ERR_FORM_SIZE)){
		$_SESSION['errmsg'] = "File size too big : " . $_FILES[$file_index]['name'] ;
		redirect(WEBSITE_URL.'packaging_designs_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	} elseif ($_FILES[$file_index]['error'] == UPLOAD_ERR_CANT_WRITE){
		$_SESSION['errmsg'] = "Unable to upload file : " . $_FILES[$file_index]['name'] ;
		redirect(WEBSITE_URL.'packaging_designs_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	} elseif (
		(strpos($_FILES[$file_index]['type'], "jpg") === false) &&
		(strpos($_FILES[$file_index]['type'], "jpeg") === false) &&
		(strpos($_FILES[$file_index]['type'], "png") === false) &&
		(strpos($_FILES[$file_index]['type'], "gif") === false) &&
		(strpos($_FILES[$file_index]['type'], "bmp") === false)
	){
		$_SESSION['errmsg'] = "Only Png, Jpg or Gif files are allowed.";
		redirect(WEBSITE_URL.'packaging_designs_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	}

	$original_name = $_FILES[$file_index]['name'];
	$tmp_name = $_FILES[$file_index]['tmp_name'];

	$ext = pathinfo($original_name, PATHINFO_EXTENSION);
	$filename = basename($original_name, '.'.$ext);
	$new_name = randName($filename, $ext);
	$dest_path = DESIGNS_PATH.DIRECTORY_SEPARATOR.$new_name;
	$GLOBALS['design_image1'] = $new_name;
	$GLOBALS['design_filename1'] = $filename;
	// echo $dest_path.'<br>';
	if (!move_uploaded_file($tmp_name, $dest_path)){
		$_SESSION['errmsg'] = "Unable to move file : " . $_FILES[$file_index]['name'];
		redirect(WEBSITE_URL.'packaging_designs_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	}
}


$file_index = 'design_image2';

if ($_FILES[$file_index]['error'] != UPLOAD_ERR_NO_FILE){
	if (($_FILES[$file_index]['error'] == UPLOAD_ERR_INI_SIZE) || ($_FILES[$file_index]['error'] == UPLOAD_ERR_FORM_SIZE)){
		$_SESSION['errmsg'] = "File size too big : " . $_FILES[$file_index]['name'] ;
		redirect(WEBSITE_URL.'packaging_designs_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	} elseif ($_FILES[$file_index]['error'] == UPLOAD_ERR_CANT_WRITE){
		$_SESSION['errmsg'] = "Unable to upload file : " . $_FILES[$file_index]['name'] ;
		redirect(WEBSITE_URL.'packaging_designs_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	} elseif (
			(strpos($_FILES[$file_index]['type'], "jpg") === false) &&
			(strpos($_FILES[$file_index]['type'], "jpeg") === false) &&
			(strpos($_FILES[$file_index]['type'], "png") === false) &&
			(strpos($_FILES[$file_index]['type'], "gif") === false) &&
			(strpos($_FILES[$file_index]['type'], "bmp") === false)
			){
		$_SESSION['errmsg'] = "Only Png, Jpg or Gif files are allowed.";
		redirect(WEBSITE_URL.'packaging_designs_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	}

	$original_name = $_FILES[$file_index]['name'];
	$tmp_name = $_FILES[$file_index]['tmp_name'];

	$ext = pathinfo($original_name, PATHINFO_EXTENSION);
	$filename = basename($original_name, '.'.$ext);
	$new_name = randName($filename, $ext);
	$dest_path = DESIGNS_PATH.DIRECTORY_SEPARATOR.$new_name;
	$GLOBALS['design_image2'] = $new_name;
	$GLOBALS['design_filename2'] = $filename;
	// echo $dest_path.'<br>';
	if (!move_uploaded_file($tmp_name, $dest_path)){
		$_SESSION['errmsg'] = "Unable to move file : " . $_FILES[$file_index]['name'];
		redirect(WEBSITE_URL.'packaging_designs_form.php?pid='.$pid.'&op='.$op.'&id='.$id);
		die();
	}
}


$sql = '';
$msg = '';

if ($op == 1){
	$sql = getUpdateQuery('psi_packaging_designs', 'design_id', '', '', true, 'design_date, design_image1, design_image2, design_filename1, design_filename2');
	$msg = 'Record Updated.';
} else {
	$GLOBALS['design_date'] = date('Y-m-d H:i:s');
	$sql = getInsertQuery('psi_packaging_designs', 'design_id');
	$msg = 'Record Added.';
}

echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);

//echo $sql.'<br>';

/*
// save new images;
if ($op == 0){
	$ret =  mysqli_insert_id($GLOBALS['cn']);
	$sql = "INSERT INTO psi_packaging_designs_images (design_id, desimg_date, desimg_file, desimg_name) VALUES ";

	$values = '';
	$date = date('Y-m-d H:i:s');
	$max = count($images);
	for($i = 0; $i < $max; $i++){
		if (strlen($values) > 0){
			$values .= ', ';
		}
		$values .=	"($ret, '$date', '$images[$i]', '$names[$i]')";
	}

	$sql .= $values;

	mysqli_query($GLOBALS['cn'], $sql);

//echo $sql.'<br>';
}

//echo $sql;
//die();

*/
$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'packaging_designs.php?pid='.$pid);
?>