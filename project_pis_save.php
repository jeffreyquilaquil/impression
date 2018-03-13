<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_pis.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_pis.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project PIS', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project PIS', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}
	
saveFormCache('psi_project_pis');

//echo var_dump($_POST['prjpis_type_id']);
//die();


if (postEmpty('prjpis_year')){
	$_SESSION['errmsg'] = "Year is required.";
	redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} else if (!postInteger('prjpis_year')){
	$_SESSION['errmsg'] = "Year must be a number.";
	redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
} elseif (strlen($GLOBALS['prjpis_year']) < 4){
	$_SESSION['errmsg'] = "Year is invalid.";
	redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

if (postEmpty('sem_id')){
	$_SESSION['errmsg'] = "Semester is required.";
	redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
	die();
}

// ****************************************************************************************************
// ****************************************************************************************************
// total Assets ***************************************************************************************

if (!postEmpty('prjpis_total_assets_land')){
	if (!postFloat('prjpis_total_assets_land')){
		$_SESSION['errmsg'] = "Total Assets : Land must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_total_assets_building')){
	if (!postFloat('prjpis_total_assets_building')){
		$_SESSION['errmsg'] = "Total Assets : Building must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_total_assets_equipment')){
	if (!postFloat('prjpis_total_assets_equipment')){
		$_SESSION['errmsg'] = "Total Assets : Equipment must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_total_assets_working_capital')){
	if (!postFloat('prjpis_total_assets_working_capital')){
		$_SESSION['errmsg'] = "Total Assets : Working Equipment must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

// employment ***************************************************************************************
	// direct ***************************************************************************************
		// company hire ***************************************************************************************
			// regular ***************************************************************************************
if (!postEmpty('prjpis_dir_ch_regular_male')){
	if (!postFloat('prjpis_dir_ch_regular_male')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Regular : Male must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_dir_ch_regular_female')){
	if (!postFloat('prjpis_dir_ch_regular_female')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Regular : Female must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_dir_ch_regular_pwd')){
	if (!postFloat('prjpis_dir_ch_regular_pwd')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Regular : PWD must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_dir_ch_regular_senior')){
	if (!postFloat('prjpis_dir_ch_regular_senior')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Regular : Senior must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

// employment ***************************************************************************************
	// direct ***************************************************************************************
		// company hire ***************************************************************************************
			// part-time ***************************************************************************************
if (!postEmpty('prjpis_dir_ch_part_time_male')){
	if (!postFloat('prjpis_dir_ch_part_time_male')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Part-Time : Male must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_dir_ch_part_time_female')){
	if (!postFloat('prjpis_dir_ch_part_time_female')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Part-Time : Female must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_dir_ch_part_time_pwd')){
	if (!postFloat('prjpis_dir_ch_part_time_pwd')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Part-Time : PWD must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_dir_ch_part_time_senior')){
	if (!postFloat('prjpis_dir_ch_part_time_senior')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Part-Time : Senior must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

// employment ***************************************************************************************
	// direct ***************************************************************************************
		// company hire ***************************************************************************************
			// regular ***************************************************************************************
if (!postEmpty('prjpis_dir_sh_regular_male')){
	if (!postFloat('prjpis_dir_sh_regular_male')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Regular : Male must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_dir_sh_regular_female')){
	if (!postFloat('prjpis_dir_sh_regular_female')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Regular : Female must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_dir_sh_regular_pwd')){
	if (!postFloat('prjpis_dir_sh_regular_pwd')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Regular : PWD must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_dir_sh_regular_senior')){
	if (!postFloat('prjpis_dir_sh_regular_senior')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Regular : Senior must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

// employment ***************************************************************************************
	// direct ***************************************************************************************
		// subcontractor hire ***************************************************************************************
			// part-time ***************************************************************************************
if (!postEmpty('prjpis_dir_sh_part_time_male')){
	if (!postFloat('prjpis_dir_sh_part_time_male')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Part-Time : Male must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_dir_sh_part_time_female')){
	if (!postFloat('prjpis_dir_sh_part_time_female')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Part-Time : Female must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_dir_sh_part_time_pwd')){
	if (!postFloat('prjpis_dir_sh_part_time_pwd')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Part-Time : PWD must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_dir_sh_part_time_senior')){
	if (!postFloat('prjpis_dir_sh_part_time_senior')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Part-Time : Senior must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

// employment ***************************************************************************************
	// indirect ***************************************************************************************
		// forward ***************************************************************************************
if (!postEmpty('prjpis_indir_forward_male')){
	if (!postFloat('prjpis_indir_forward_male')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Forward : Male must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_indir_forward_female')){
	if (!postFloat('prjpis_indir_forward_female')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Forward : Female must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_indir_forward_pwd')){
	if (!postFloat('prjpis_indir_forward_pwd')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Forward : PWD must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_indir_forward_senior')){
	if (!postFloat('prjpis_indir_forward_senior')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Forward : Senior must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

// employment ***************************************************************************************
	// indirect ***************************************************************************************
		// backward ***************************************************************************************
if (!postEmpty('prjpis_indir_backward_male')){
	if (!postFloat('prjpis_indir_backward_male')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Backward : Male must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_indir_backward_female')){
	if (!postFloat('prjpis_indir_backward_female')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Backward : Female must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_indir_backward_pwd')){
	if (!postFloat('prjpis_indir_backward_pwd')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Backward : PWD must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_indir_backward_senior')){
	if (!postFloat('prjpis_indir_backward_senior')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Backward : Senior must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (postEmpty('prjpis_assistance_process')){
	$GLOBALS['prjpis_assistance_process'] = 0;
}

if (postEmpty('prjpis_assistance_equipment')){
	$GLOBALS['prjpis_assistance_equipment'] = 0;
}

if (postEmpty('prjpis_assistance_quality_control')){
	$GLOBALS['prjpis_assistance_quality_control'] = 0;
}

if (postEmpty('prjpis_assistance_packaging_labeling')){
	$GLOBALS['prjpis_assistance_packaging_labeling'] = 0;
}

if (postEmpty('prjpis_assistance_post_harvest')){
	$GLOBALS['prjpis_assistance_post_harvest'] = 0;
}

if (postEmpty('prjpis_assistance_marketing')){
	$GLOBALS['prjpis_assistance_marketing'] = 0;
}

if (postEmpty('prjpis_assistance_training')){
	$GLOBALS['prjpis_assistance_training'] = 0;
}

if (postEmpty('prjpis_assistance_consultancy')){
	$GLOBALS['prjpis_assistance_consultancy'] = 0;
}

// ****************************************************************************************************************
// ****************************************************************************************************************
// Total Volume Production ****************************************************************************************

if (!postEmpty('prjpis_volume_production_local')){
	if (!postFloat('prjpis_volume_production_local')){
		$_SESSION['errmsg'] = "Total Volume Production : Local must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_volume_production_export')){
	if (!postFloat('prjpis_volume_production_export')){
		$_SESSION['errmsg'] = "Total Volume Production : Export must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

// ****************************************************************************************************************
// ****************************************************************************************************************
// Total Gross Sales***********************************************************************************************

if (!postEmpty('prjpis_gross_sales_local')){
	if (!postFloat('prjpis_gross_sales_local')){
		$_SESSION['errmsg'] = "Total Gross Sales : Local must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

if (!postEmpty('prjpis_gross_sales_export')){
	if (!postFloat('prjpis_gross_sales_export')){
		$_SESSION['errmsg'] = "Total Gross Sales : Export must be a valid number.";
		redirect(WEBSITE_URL.'project_pis_form.php?op='.$op.'&id='.$id.'&pid='.$pid);
		die();
	}
}

// ****************************************************************************************************************
// ****************************************************************************************************************
// Assistance Obtained From DOST **********************************************************************************



$sql = '';
$msg = '';

$GLOBALS['prjform_id'] = 1;

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_project_pis', 'prjpis_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_project_pis', 'prjpis_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Added.';
}


//echo $sql;
//die();

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_pis.php?op='.$op.'&id='.$id.'&pid='.$pid);
?>