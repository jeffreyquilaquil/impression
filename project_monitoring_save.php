<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$frm = requestInteger('frm', 'location: '.WEBSITE_URL.'project_monitoring.php?pid='.$pid);

if (($frm != 2) && ($frm != 3)){
    redirect(WEBSITE_URL.'project_monitoring.php?pid='.$pid);
    die();
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_monitoring.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_monitoring.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Monitoring', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Monitoring', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

loadDBValues("vwpsi_projects", "SELECT * FROM vwpsi_projects WHERE prj_id = ".$pid);

saveFormCache('psi_project_monitoring');

//echo var_dump($_POST['prjmon_type_id']);
//die();


if (postEmpty('prjmon_effectivity')){
	$_SESSION['errmsg'] = "Effectivity is required.";
	redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
	die();
} elseif (!postDate('prjmon_effectivity')){
	$_SESSION['errmsg'] = "Effectivity must be a date (mm/dd/yyyy).";
	redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
	die();
}

if (postEmpty('prjmon_year')){
	$_SESSION['errmsg'] = "Year is required.";
	redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
	die();
} else if (!postInteger('prjmon_year')){
	$_SESSION['errmsg'] = "Year must be a number.";
	redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
	die();
} elseif (strlen($GLOBALS['prjmon_year']) < 4){
	$_SESSION['errmsg'] = "Year is invalid.";
	redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
	die();
}

if (postEmpty('quarter_id')){
	$_SESSION['errmsg'] = "Quarter is required.";
	redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
	die();
}

if ($frm == 2){
	// ****************************************************************************************************************
	// ****************************************************************************************************************
	// Status Of Liquidation ******************************************************************************************

	if (!postEmpty('prjmon_liquidation_cost')){
		if (!postFloat('prjmon_liquidation_cost')){
			$_SESSION['errmsg'] = "Total Approved Project Cost must be a valid number.";
			redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
			die();
		}
	}

	if (!postEmpty('prjmon_liquidation_used')){
		if (!postFloat('prjmon_liquidation_used')){
			$_SESSION['errmsg'] = "Amount Utilized Per Financial Report must be a valid number.";
			redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
			die();
		}
	}

	if (!postEmpty('prjmon_liquidation_date')){
		if (!postDate('prjmon_liquidation_date')){
			$_SESSION['errmsg'] = "Amount Utilized Per Financial Report : As Of must be a date (mm/dd/yyyy).";
			redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
			die();
		}
	}

	// ****************************************************************************************************************
	// ****************************************************************************************************************
	// Status Of Refund ***********************************************************************************************

    if ($GLOBALS['prj_type_id'] == 6){

		if (!postEmpty('prjmon_refund_amount')){
			if (!postFloat('prjmon_refund_amount')){
				$_SESSION['errmsg'] = "Total Amount To Be Refunded must be a valid number.";
				redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
				die();
			}
		}

		if (!postEmpty('prjmon_refund_schedule_from')){
			if (!postDate('prjmon_refund_schedule_from')){
				$_SESSION['errmsg'] = "Approved Refund Schedule (From) must be a date (mm/dd/yyyy).";
				redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
				die();
			}
		}

		if (!postEmpty('prjmon_refund_schedule_to')){
			if (!postDate('prjmon_refund_schedule_to')){
				$_SESSION['errmsg'] = "Approved Refund Schedule (To) must be a date (mm/dd/yyyy).";
				redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
				die();
			}
		}

		if (!postEmpty('prjmon_refund_amount_due')){
			if (!postFloat('prjmon_refund_amount_due')){
				$_SESSION['errmsg'] = "Total Amount Already Due must be a valid number.";
				redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
				die();
			}
		}

		if (!postEmpty('prjmon_refund_date')){
			if (!postDate('prjmon_refund_date')){
				$_SESSION['errmsg'] = "Total Amount Already Due (As Of) must be a date (mm/dd/yyyy).";
				redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
				die();
			}
		}

		if (!postEmpty('prjmon_refund_refunded')){
			if (!postFloat('prjmon_refund_refunded')){
				$_SESSION['errmsg'] = "Total Amount Refunded must be a valid number.";
				redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
				die();
			}
		}

		if (!postEmpty('prjmon_refund_unsettled')){
			if (!postFloat('prjmon_refund_unsettled')){
				$_SESSION['errmsg'] = "Unsettled Refund must be a valid number.";
				redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
				die();
			}
		}

		if (!postEmpty('prjmon_refund_delay_date')){
			if (!postDate('prjmon_refund_delay_date')){
				$_SESSION['errmsg'] = "Refund Delayed Since must be a date (mm/dd/yyyy).";
				redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
				die();
			}
		}

		if (!postEmpty('prjmon_refund_used')){
			if (!postFloat('prjmon_refund_used')){
				$_SESSION['errmsg'] = "Amount Utilized Per Financial Report must be a valid number.";
				redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
				die();
			}
		}

		if (!postEmpty('prjmon_refund_date')){
			if (!postDate('prjmon_refund_date')){
				$_SESSION['errmsg'] = "Amount Utilized Per Financial Report : As Of must be a date (mm/dd/yyyy).";
				redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
				die();
			}
		}
	}

	if (!postEmpty('prjmon_volume_of_production')){
		if (!postFloat('prjmon_volume_of_production')){
			$_SESSION['errmsg'] = "Volume of Production must be a valid number.";
			redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
			die();
		}
	}

	if (!postEmpty('prjmon_volume_gross_sales')){
		if (!postFloat('prjmon_volume_gross_sales')){
			$_SESSION['errmsg'] = "Gross Sales must be a valid number.";
			redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
			die();
		}
	}


	if (!postEmpty('prjmon_emp_total')){
		if (!postFloat('prjmon_emp_total')){
			$_SESSION['errmsg'] = "No. of new employment generated from the project : No. of Employees must be a valid number";
			redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
			die();
		}
	}

	if (!postEmpty('prjmon_emp_male')){
		if (!postFloat('prjmon_emp_male')){
			$_SESSION['errmsg'] = "No. of new employment generated from the project : No. of Masculine must be a valid number";
			redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
			die();
		}
	}

	if (!postEmpty('prjmon_emp_female')){
		if (!postFloat('prjmon_emp_female')){
			$_SESSION['errmsg'] = "Employment : No. of Feminine must be a valid number";
			redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
			die();
		}
	}

	if (!postEmpty('prjmon_emp_pwd')){
		if (!postFloat('prjmon_emp_pwd')){
			$_SESSION['errmsg'] = "Employment : No. of PWD must be a valid number";
			redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
			die();
		}
	}

//********************************************

	if (!postEmpty('prjmon_emp_indirect_forward_male')){
		if (!postFloat('prjmon_emp_indirect_forward_male')){
			$_SESSION['errmsg'] = "Employment Indirect Forward : No. of Masculine must be a valid number";
			redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
			die();
		}
	}

	if (!postEmpty('prjmon_emp_indirect_forward_female')){
		if (!postFloat('prjmon_emp_indirect_forward_female')){
			$_SESSION['errmsg'] = "Employment Indirect Forward : No. of Feminine must be a valid number";
			redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
			die();
		}
	}

//********************************************

	if (!postEmpty('prjmon_emp_indirect_backward_male')){
		if (!postFloat('prjmon_emp_indirect_backward_male')){
			$_SESSION['errmsg'] = "Employment Indirect Backward : No. of Masculine must be a valid number";
			redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
			die();
		}
	}

	if (!postEmpty('prjmon_emp_indirect_backward_female')){
		if (!postFloat('prjmon_emp_indirect_backward_female')){
			$_SESSION['errmsg'] = "Employment Indirect Backward : No. of Feminine must be a valid number";
			redirect(WEBSITE_URL.'project_monitoring_form.php?op='.$op.'&id='.$id.'&pid='.$pid.'&frm='.$frm);
			die();
		}
	}

}

$GLOBALS['prjform_id'] = $frm;

$sql = '';
$msg = '';

$GLOBALS['updater'] = $GLOBALS['ad_u_name'];
$GLOBALS['last_updated'] = date('Y-m-d H:i:s');

if ($op == 1){
	$sql = getUpdateQuery('psi_project_monitoring', 'prjmon_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Updated.';
} else {
	$GLOBALS['encoder'] = $GLOBALS['ad_u_name'];
	$GLOBALS['date_encoded'] = date('Y-m-d H:i:s');

	$sql = getInsertQuery('psi_project_monitoring', 'prjmon_id');
	mysqli_query($GLOBALS['cn'], $sql);
	$msg = 'Record Added.';
}

//
//echo $sql;
//die();

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'project_monitoring.php?op='.$op.'&id='.$id.'&pid='.$pid);
?>