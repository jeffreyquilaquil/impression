<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'projects.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'projects.php');

if ($op == 1){
    if (!can_access('Projects', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Projects', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}


$fields = 'coop_id, col_id, form_return';
saveFormCache('psi_projects', $fields);

//echo var_dump($_POST['prj_type_id']);
//die();


if (postEmpty('prj_title')){
	$_SESSION['errmsg'] = "Project Title is required.";
	redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('coop_id')){
	$_SESSION['errmsg'] = "Please select the Beneficiaries.";
	redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
	die();
}


if (postEmpty('province_id')){
	$_SESSION['errmsg'] = "Province is required.";
	redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('city_id')){
	$_SESSION['errmsg'] = "Municipality/City is required.";
	redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('barangay_id')){
	$_SESSION['errmsg'] = "Barangay is required.";
	redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('prj_year_approved')){
	$_SESSION['errmsg'] = "Year Approved is required.";
	redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
	die();
} elseif (!postInteger('prj_year_approved')){
	$_SESSION['errmsg'] = "Year Approved must be a number.";
	redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
	die();
} elseif (strlen($GLOBALS['prj_year_approved']) < 4){
	$_SESSION['errmsg'] = "Year Approved must be a valid year.";
	redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$ssid);
	die();
}

if (postEmpty('prj_objective')){
	$_SESSION['errmsg'] = "Objective is required.";
	redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('prj_expected_output')){
	$_SESSION['errmsg'] = "Expected Output is required.";
	redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
	die();
}

/*
if (postEmpty('prj_product_line')){
	$_SESSION['errmsg'] = "Products is required.";
	redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
	die();
}

*/

if (!postEmpty('prj_fund_release_date')) {
	if (!postDate('prj_fund_release_date')) {
		$_SESSION['errmsg'] = "Date Funds Released To The Beneficiary must be a date (mm/dd/yyyy).";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_refund_period_from')){
	if (!postDate('prj_refund_period_from')){
		$_SESSION['errmsg'] = "Refund Period (From) must be a date (mm/dd/yyyy).";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_refund_period_to')){
	if (!postDate('prj_refund_period_to')){
		$_SESSION['errmsg'] = "Refund Period (To) must be a date (mm/dd/yyyy).";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}


if (!postEmpty('prj_cost_setup')){
	if (!postFloat('prj_cost_setup')){
		$_SESSION['errmsg'] = "Project Cost must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

$GLOBALS['prj_cost_setup'] = ceil($GLOBALS['prj_cost_setup']);

if (!postEmpty('prj_cost_gia')){
	if (!postFloat('prj_cost_gia')){
		$_SESSION['errmsg'] = "GIA Project Cost must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}
$GLOBALS['prj_cost_gia'] = 0; //ceil($GLOBALS['prj_cost_gia']);

if (!postEmpty('prj_cost_rollout')){
	if (!postFloat('prj_cost_rollout')){
		$_SESSION['errmsg'] = "Roll-out Project Cost must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}
$GLOBALS['prj_cost_rollout'] = 0; //ceil($GLOBALS['prj_cost_rollout']);

if (!postEmpty('prj_cost_benefactor')){
	if (!postFloat('prj_cost_benefactor')){
		$_SESSION['errmsg'] = "Beneficiaries’ Counterpart Project Cost must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}
$GLOBALS['prj_cost_benefactor'] = ceil($GLOBALS['prj_cost_benefactor']);

if (!postEmpty('prj_cost_other')){
	if (!postFloat('prj_cost_other')){
		$_SESSION['errmsg'] = "Other Project Cost must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}
$GLOBALS['prj_cost_other'] = ceil($GLOBALS['prj_cost_other']);


// ****************************************************************************************************
// ****************************************************************************************************
// total Assets ***************************************************************************************

if (!postEmpty('prj_pis_total_assets_land')){
	if (!postFloat('prj_pis_total_assets_land')){
		$_SESSION['errmsg'] = "Total Assets : Land must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_total_assets_building')){
	if (!postFloat('prj_pis_total_assets_building')){
		$_SESSION['errmsg'] = "Total Assets : Building must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_total_assets_equipment')){
	if (!postFloat('prj_pis_total_assets_equipment')){
		$_SESSION['errmsg'] = "Total Assets : Equipment must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_total_assets_working_capital')){
	if (!postFloat('prj_pis_total_assets_working_capital')){
		$_SESSION['errmsg'] = "Total Assets : Working Equipment must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

// employment ***************************************************************************************
	// direct ***************************************************************************************
		// company hire ***************************************************************************************
			// regular ***************************************************************************************
if (!postEmpty('prj_pis_dir_ch_regular_male')){
	if (!postFloat('prj_pis_dir_ch_regular_male')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Regular : Male must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_dir_ch_regular_female')){
	if (!postFloat('prj_pis_dir_ch_regular_female')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Regular : Female must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_dir_ch_regular_pwd')){
	if (!postFloat('prj_pis_dir_ch_regular_pwd')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Regular : PWD must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_dir_ch_regular_senior')){
	if (!postFloat('prj_pis_dir_ch_regular_senior')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Regular : Senior must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

// employment ***************************************************************************************
	// direct ***************************************************************************************
		// company hire ***************************************************************************************
			// part-time ***************************************************************************************
if (!postEmpty('prj_pis_dir_ch_part_time_male')){
	if (!postFloat('prj_pis_dir_ch_part_time_male')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Part-Time : Male must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_dir_ch_part_time_female')){
	if (!postFloat('prj_pis_dir_ch_part_time_female')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Part-Time : Female must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_dir_ch_part_time_pwd')){
	if (!postFloat('prj_pis_dir_ch_part_time_pwd')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Part-Time : PWD must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_dir_ch_part_time_senior')){
	if (!postFloat('prj_pis_dir_ch_part_time_senior')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Company Hire : Part-Time : Senior must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

// employment ***************************************************************************************
	// direct ***************************************************************************************
		// company hire ***************************************************************************************
			// regular ***************************************************************************************
if (!postEmpty('prj_pis_dir_sh_regular_male')){
	if (!postFloat('prj_pis_dir_sh_regular_male')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Regular : Male must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_dir_sh_regular_female')){
	if (!postFloat('prj_pis_dir_sh_regular_female')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Regular : Female must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_dir_sh_regular_pwd')){
	if (!postFloat('prj_pis_dir_sh_regular_pwd')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Regular : PWD must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_dir_sh_regular_senior')){
	if (!postFloat('prj_pis_dir_sh_regular_senior')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Regular : Senior must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

// employment ***************************************************************************************
	// direct ***************************************************************************************
		// subcontractor hire ***************************************************************************************
			// part-time ***************************************************************************************
if (!postEmpty('prj_pis_dir_sh_part_time_male')){
	if (!postFloat('prj_pis_dir_sh_part_time_male')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Part-Time : Male must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_dir_sh_part_time_female')){
	if (!postFloat('prj_pis_dir_sh_part_time_female')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Part-Time : Female must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_dir_sh_part_time_pwd')){
	if (!postFloat('prj_pis_dir_sh_part_time_pwd')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Part-Time : PWD must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_dir_sh_part_time_senior')){
	if (!postFloat('prj_pis_dir_sh_part_time_senior')){
		$_SESSION['errmsg'] = "Total Employment Generated : Direct Employment : Sub-Contractor Hire : Part-Time : Senior must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

// employment ***************************************************************************************
	// indirect ***************************************************************************************
		// forward ***************************************************************************************
if (!postEmpty('prj_pis_indir_forward_male')){
	if (!postFloat('prj_pis_indir_forward_male')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Forward : Male must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_indir_forward_female')){
	if (!postFloat('prj_pis_indir_forward_female')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Forward : Female must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_indir_forward_pwd')){
	if (!postFloat('prj_pis_indir_forward_pwd')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Forward : PWD must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_indir_forward_senior')){
	if (!postFloat('prj_pis_indir_forward_senior')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Forward : Senior must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

// employment ***************************************************************************************
	// indirect ***************************************************************************************
		// backward ***************************************************************************************
if (!postEmpty('prj_pis_indir_backward_male')){
	if (!postFloat('prj_pis_indir_backward_male')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Backward : Male must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_indir_backward_female')){
	if (!postFloat('prj_pis_indir_backward_female')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Backward : Female must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_indir_backward_pwd')){
	if (!postFloat('prj_pis_indir_backward_pwd')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Backward : PWD must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_indir_backward_senior')){
	if (!postFloat('prj_pis_indir_backward_senior')){
		$_SESSION['errmsg'] = "Total Employment Generated : Indirect Employment : Backward : Senior must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (postEmpty('prj_fundingsource_local')){
	$GLOBALS['prj_fundingsource_local'] = 0;
}

if (postEmpty('prj_fundingsource_external')){
	$GLOBALS['prj_fundingsource_external'] = 0;
}

if (postEmpty('prj_pis_assistance_process')){
	$GLOBALS['prj_pis_assistance_process'] = 0;
}

if (postEmpty('prj_pis_assistance_equipment')){
	$GLOBALS['prj_pis_assistance_equipment'] = 0;
}

if (postEmpty('prj_pis_assistance_quality_control')){
	$GLOBALS['prj_pis_assistance_quality_control'] = 0;
}

if (postEmpty('prj_pis_assistance_packaging_labeling')){
	$GLOBALS['prj_pis_assistance_packaging_labeling'] = 0;
}

if (postEmpty('prj_pis_assistance_post_harvest')){
	$GLOBALS['prj_pis_assistance_post_harvest'] = 0;
}

if (postEmpty('prj_pis_assistance_marketing')){
	$GLOBALS['prj_pis_assistance_marketing'] = 0;
}

// ****************************************************************************************************************
// ****************************************************************************************************************
// Total Volume Production ****************************************************************************************

if (!postEmpty('prj_pis_volume_production_local')){
	if (!postFloat('prj_pis_volume_production_local')){
		$_SESSION['errmsg'] = "Total Volume Production : Local must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_volume_production_export')){
	if (!postFloat('prj_pis_volume_production_export')){
		$_SESSION['errmsg'] = "Total Volume Production : Export must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

// ****************************************************************************************************************
// ****************************************************************************************************************
// Total Gross Sales***********************************************************************************************

if (!postEmpty('prj_pis_gross_sales_local')){
	if (!postFloat('prj_pis_gross_sales_local')){
		$_SESSION['errmsg'] = "Total Gross Sales : Local must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_pis_gross_sales_export')){
	if (!postFloat('prj_pis_gross_sales_export')){
		$_SESSION['errmsg'] = "Total Gross Sales : Export must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}


if (!postEmpty('prj_longitude')){
	if (!postFloat('prj_longitude')){
		$_SESSION['errmsg'] = "Longitude must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_latitude')){
	if (!postFloat('prj_latitude')){
		$_SESSION['errmsg'] = "Latitude must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}

if (!postEmpty('prj_elevation')){
	if (!postFloat('prj_elevation')){
		$_SESSION['errmsg'] = "Elevation must be a valid number.";
		redirect(WEBSITE_URL.'projects_form.php?op='.$op.'&id='.$id);
		die();
	}
}


/*
if (!postEmpty('sector_others')){
    if (!dbValueExists('psi_sectors', 'sector_name', $GLOBALS['sector_others'])){
        //echo $GLOBALS['sector_others'];
        $sql = "INSERT INTO psi_sectors (sector_name) VALUES ('$GLOBALS[sector_others]')";
        mysqli_query($GLOBALS['cn'], $sql);
        $sid = mysqli_insert_id($GLOBALS['cn']);
        $GLOBALS['sector_id'] = $sid;
    } else {
        loadDBValues('psi_sectors', "SELECT sector_id FROM psi_sectors WHERE sector_name = '$GLOBALS[sector_others]'");
    }
}
*/



$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){

	$sql = getUpdateQuery('psi_projects', 'prj_id');
	//echo $sql;
	//die();

	mysqli_query($GLOBALS['cn'], $sql);

	$sql = "DELETE FROM psi_project_beneficiaries WHERE prj_id = $id";
	mysqli_query($GLOBALS['cn'], $sql);

	$sql = "DELETE FROM psi_project_collaborators WHERE prj_id = $id";
	mysqli_query($GLOBALS['cn'], $sql);

	//$sql = "DELETE FROM psi_project_sectors WHERE prj_id = $id";
	//mysqli_query($GLOBALS['cn'], $sql);

	$curr_status = get_current_status($id);

	if ($curr_status != $GLOBALS['prj_status_id']){
		$dt = $GLOBALS['last_updated'];
		$user = $GLOBALS['updater'];
		$sql = "INSERT INTO psi_project_status_history (psh_date, prj_status_id, prj_id, updater, last_updated) VALUES ";
		$sql .= "('$dt', $GLOBALS[prj_status_id], $id, '$user', '$dt')";
		mysqli_query($GLOBALS['cn'], $sql);
	}

	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_projects', 'prj_id');
	mysqli_query($GLOBALS['cn'], $sql);

//echo $sql;
//die();

	$id = mysqli_insert_id($GLOBALS['cn']);
	$msg = 'Record Added.';

	$dt = $GLOBALS['date_encoded'];
	$user = $GLOBALS['encoder'];
	
	$sql = "INSERT INTO psi_project_status_history (psh_date, prj_status_id, prj_id, encoder, date_encoded, updater, last_updated) VALUES ";
	$sql .= "('$dt', $GLOBALS[prj_status_id], $id, '$user', '$dt', '$user', '$dt')";

	mysqli_query($GLOBALS['cn'], $sql);
}


save_cooperators($id);
save_collaborators($id);

//echo $sql;
//die();

if (isset($GLOBALS['form_return'])) {
    deleteFormCache();
    $_SESSION['form_return'] = $GLOBALS['form_return'];
	$_SESSION['errmsg'] = $msg;
    redirect(WEBSITE_URL.'projects_form.php?op=0&id=0');
} else {
	$_SESSION['form_return'] = 0;
	$_SESSION['errmsg'] = $msg;
	redirect(WEBSITE_URL.'projects.php');
}



function get_current_status($id){
	$sql = "SELECT * FROM psi_projects WHERE prj_id = $id";
	$res = mysqli_query($GLOBALS['cn'], $sql);
	$val = 0;
	if ($res) {
		if ($row = mysqli_fetch_array($res)){
			$val = $row['prj_status_id'];
		}
		mysqli_free_result($res);
	}
	return $val;
}

function save_cooperators($id){
	if (postEmpty('coop_id')) return;
	$sql = "INSERT INTO psi_project_beneficiaries (prj_id, coop_id) VALUES ";
	$values = '';
	foreach ($GLOBALS['coop_id'] as $iid) {
		if (strlen($values) > 0){
			$values .= ', ';
		}
		$values .= "($id, $iid)";
	}
	$sql .= $values;
	mysqli_query($GLOBALS['cn'], $sql);
}

function save_collaborators($id){
	if (postEmpty('col_id')) return;
	$sql = "INSERT INTO psi_project_collaborators (prj_id, col_id) VALUES ";
	$values = '';
	foreach ($GLOBALS['col_id'] as $iid) {
		if (strlen($values) > 0){
			$values .= ', ';
		}
		$values .= "($id, $iid)";
	}
	$sql .= $values;
	mysqli_query($GLOBALS['cn'], $sql);
}

function save_sectors($id){
	$sql = 'INSERT INTO psi_project_sectors (prj_id, sector_id) VALUES ';
	$values = '';
	foreach ($GLOBALS['gsectors'] as $value) {

		if (strlen($values) > 0){
			$values .= ', ';
		}
		$values .= "($id, $value)";
	}
	$sql .= $values;
	mysqli_query($GLOBALS['cn'], $sql);
}
?>