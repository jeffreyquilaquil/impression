<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_packaging.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_packaging.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Packaging & Labeling', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Packaging & Labeling', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

saveFormCache('psi_packaging');

if (postEmpty('pkg_product_name')){
	$_SESSION['errmsg'] = "Product Name is required.";
	redirect(WEBSITE_URL.'project_packaging_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (!postEmpty('pkg_market_products_sold')){
	if (!postInteger('pkg_market_products_sold')){
		$_SESSION['errmsg'] = "Products Sold must be a valid number.";
		redirect(WEBSITE_URL.'project_packaging_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	} elseif ($GLOBALS['pkg_market_products_sold'] < 0){
		$_SESSION['errmsg'] = "Products Sold must be equal to or greater than 0.";
		redirect(WEBSITE_URL.'project_packaging_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('pkg_market_date_extablished')){
	if (!postDate('pkg_market_date_extablished')) {
		$_SESSION['errmsg'] = "Date Established must be a valid date. (mm/dd/yyyy)";
		redirect(WEBSITE_URL.'project_packaging_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('pkg_sales_before_intervention')){
	if (!postFloat('pkg_sales_before_intervention')){
		$_SESSION['errmsg'] = "Sales Before Intervention must be a valid number.";
		redirect(WEBSITE_URL.'project_packaging_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('pkg_sales_after_intervention')){
	if (!postFloat('pkg_sales_after_intervention')){
		$_SESSION['errmsg'] = "Sales After Intervention must be a valid number.";
		redirect(WEBSITE_URL.'project_packaging_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('pkg_employment_after_direct')){
	if (!postInteger('pkg_employment_after_direct')){
		$_SESSION['errmsg'] = "Employment After : Direct must be a valid number.";
		redirect(WEBSITE_URL.'project_packaging_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	} elseif ($GLOBALS['pkg_employment_after_direct'] < 0){
		$_SESSION['errmsg'] = "Employment After : Direct must be equal to or greater than 0.";
		redirect(WEBSITE_URL.'project_packaging_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('pkg_employment_after_months_employed')){
	if (!postInteger('pkg_employment_after_months_employed')){
		$_SESSION['errmsg'] = "Employment After : Months Employed must be a valid number.";
		reindirect(WEBSITE_URL.'project_packaging_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	} elseif ($GLOBALS['pkg_employment_after_months_employed'] < 0){
		$_SESSION['errmsg'] = "Employment After : Months Employed must be equal to or greater than 0.";
		reindirect(WEBSITE_URL.'project_packaging_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('pkg_avg_productivity_improvement')){
	if (!postFloat('pkg_avg_productivity_improvement')){
		$_SESSION['errmsg'] = "Average Productivity Improvement must be a valid number.";
		redirect(WEBSITE_URL.'project_packaging_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

$file_index = 'pkg_file';
if ($_FILES[$file_index]['error'] != UPLOAD_ERR_NO_FILE){
	if (($_FILES[$file_index]['error'] == UPLOAD_ERR_INI_SIZE) || ($_FILES[$file_index]['error'] == UPLOAD_ERR_FORM_SIZE)){
		$_SESSION['errmsg'] = "File size too big : " . $_FILES[$file_index]['name'] ;
		redirect(WEBSITE_URL.'project_packaging_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	} elseif ($_FILES[$file_index]['error'] == UPLOAD_ERR_CANT_WRITE){
		$_SESSION['errmsg'] = "Unable to upload file : " . $_FILES[$file_index]['name'] ;
		redirect(WEBSITE_URL.'project_packaging_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	} elseif (	(!strpos($_FILES[$file_index]['type'], "msword")) && 
				(!strpos($_FILES[$file_index]['type'], "ms-word")) &&
				(!strpos($_FILES[$file_index]['type'], "ms-excel")) &&
				(!strpos($_FILES[$file_index]['type'], "ms-powerpoint")) &&
				(!strpos($_FILES[$file_index]['type'], "wordprocessing")) &&
				(!strpos($_FILES[$file_index]['type'], "spreadsheet")) &&
				(!strpos($_FILES[$file_index]['type'], "presentation")) &&
				(!strpos($_FILES[$file_index]['type'], "pdf")) &&
				(!strpos($_FILES[$file_index]['type'], "png")) &&
				(!strpos($_FILES[$file_index]['type'], "gif")) &&
				(!strpos($_FILES[$file_index]['type'], "jpg")) &&
				(!strpos($_FILES[$file_index]['type'], "jpeg"))
			 ){

		echo "<br>";
		echo $_FILES[$file_index]['type'] .'<br>';
		die();

		$_SESSION['errmsg'] = "Invalid file type.";
		redirect(WEBSITE_URL.'project_packaging_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}	

	$original_name = $_FILES[$file_index]['name'];
	$tmp_name = $_FILES[$file_index]['tmp_name'];

	$ext = pathinfo($original_name, PATHINFO_EXTENSION);
	$filename = basename($original_name, '.'.$ext);
	$new_name = randName($filename, $ext);
	$dest_path = PACKAGING_DOCS_PATH.DIRECTORY_SEPARATOR.$new_name;
	$GLOBALS[$file_index] = $new_name;
	$GLOBALS['pkg_filename'] = $filename;
	// echo $dest_path.'<br>';
	if (!move_uploaded_file($tmp_name, $dest_path)){
		$_SESSION['errmsg'] = "Unable to move file : " . $_FILES[$file_index]['name'];
		redirect(WEBSITE_URL.'project_packaging_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}


$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_packaging', 'pkg_id');
	$msg = 'Record Updated.';
} else {
	$GLOBALS['pkg_date'] = date('Y-m-d H:i:s');
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');
	
	$sql = getInsertQuery('psi_packaging', 'pkg_id');
	$msg = 'Record Added.';
}

//echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);
$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_packaging.php?pid='.$pid);
?>