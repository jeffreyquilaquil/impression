<?php
function record_footprint($action){
    $uid = $GLOBALS['ad_u_id'];
    $host = "$_SERVER[HTTP_HOST]";
    $url = "$_SERVER[REQUEST_URI]";
    $date = date('Y-m-d H:i:s');

    $sql = "INSERT INTO psi_footprints (foot_host, foot_url, foot_date, foot_action, u_id) VALUES ('$host', '$url', '$date', '$action', $uid)";
    mysqli_query($GLOBALS['cn'], $sql);
}

function is_project_type($pid, $type){
    $found = false;
    $sql = "SELECT prj_type_id FROM psi_projects WHERE prj_id = $pid";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    $value = mysqli_fetch_assoc($res);
    $found = ($value['prj_type_id'] == $type) ;
    mysqli_free_result($res);
    return $found;
}


function getBeneficiaries($id, $delimiter = ', '){
    $sql = "SELECT * FROM vwpsi_project_beneficiaries WHERE prj_id = $id";
    $res = mysqli_query($GLOBALS['cn'], $sql);

    if (!$res) return '';
    $s = '';
    while ($row = mysqli_fetch_array($res)){
        if (strlen($s) > 0){
            $s .= $delimiter;
        }
        $s .= $row['coop_name'];
    }
    mysqli_free_result($res);
    return $s;
}

// repayment *****************************************************************************
define('$rep_month_count', 36);

function getMaxYearMonth($pid){
	$rep = getRepayment($pid);
	if (!$rep) return false;
	$yr = $rep['rep_start_year'];
	$mo = $rep['rep_start_month'];
	
	$date = strtotime($yr.'-'.$mo.'-1 +5 Years');

    $ret = array();
	$ret['year'] = date('Y', $date);
	$ret['month'] = date('m', $date);
	return $ret;
}

function getDeferments($pid){
    $sql = "SELECT * FROM vwpsi_repayments_payments WHERE (rep_id = $pid) AND (pay_otb = 2) ORDER BY pay_year ASC, pay_month ASC";
    $res = mysqli_query($GLOBALS['cn'], $sql);  
    if ($res){
        $ret = array();
        $yr = 0;
        while ($row = mysqli_fetch_array($res)){
            $yr = $row['pay_year'];
            $mo = $row['pay_month'];
            if (!isset($ret[$yr])){
                $ret[$yr] = array();
            }
            if (!isset($ret[$yr][$mo])){
                $ret[$yr][$mo] = array();
            }
            $ret[$yr][$mo] = $row;
        }
        mysqli_free_result($res);
        return $ret;
    }
    return false;
}

function getOTBPayment($pid){
    $sql = "SELECT * FROM vwpsi_repayments_payments WHERE (rep_id = $pid) AND (pay_otb = 1) ORDER BY pay_year ASC, pay_month ASC";
    $res = mysqli_query($GLOBALS['cn'], $sql);  
    if ($res){
        $ret = array();
        $yr = 0;
        while ($row = mysqli_fetch_array($res)){
            $yr = $row['pay_year'];
            $mo = $row['pay_month'];
            if (!isset($ret[$yr])){
                $ret[$yr] = array();
            }
            if (!isset($ret[$yr][$mo])){
                $ret[$yr][$mo] = array();
            }
            $ret[$yr][$mo] = $row;
        }
        mysqli_free_result($res);
        return $ret;
    }
    return false;
}

function getPayments($pid){
    $sql = "SELECT * FROM vwpsi_repayments_payments WHERE (rep_id = $pid) AND (pay_otb = 0) ORDER BY pay_year ASC, pay_month ASC";
    $res = mysqli_query($GLOBALS['cn'], $sql);  
    if ($res){
        $ret = array();
        $yr = 0;
        while ($row = mysqli_fetch_array($res)){
            $yr = $row['pay_year'];
            $mo = $row['pay_month'];
            if (!isset($ret[$yr])){
                $ret[$yr] = array();
            }
            if (!isset($ret[$yr][$mo])){
                $ret[$yr][$mo] = array();
            }
            $ret[$yr][$mo] = $row;
        }
        mysqli_free_result($res);
        return $ret;
    }
    return false;
}

function getAllPayments($pid){
    $sql = "SELECT * FROM vwpsi_repayments_payments WHERE (rep_id = $pid) ORDER BY pay_year ASC, pay_month ASC";
    $res = mysqli_query($GLOBALS['cn'], $sql);  
    if ($res){
        $ret = array();
        $yr = 0;
        while ($row = mysqli_fetch_array($res)){
            $yr = $row['pay_year'];
            $mo = $row['pay_month'];
            if (!isset($ret[$yr])){
                $ret[$yr] = array();
            }
            if (!isset($ret[$yr][$mo])){
                $ret[$yr][$mo] = array();
            }
            $ret[$yr][$mo] = $row;
        }
        mysqli_free_result($res);
        return $ret;
    }
    return false;
}

function getAllPaymentDates($pid, $id = 0){
    $sql = "SELECT * FROM vwpsi_repayments_payments WHERE (rep_id = $pid) AND (pay_id != $id) ORDER BY pay_year ASC, pay_month ASC";
    $res = mysqli_query($GLOBALS['cn'], $sql);  
    if ($res){
        $ret = array();
        $yr = 0;
        while ($row = mysqli_fetch_array($res)){
            $yr = $row['pay_year'];
            $mo = $row['pay_month'];
            if (!isset($ret[$yr])){
                $ret[$yr] = array();
            }
            if (!isset($ret[$yr][$mo])){
                $ret[$yr][$mo] = array();
            }
            $ret[$yr][$mo] = $row;

            $ctr = $row['pay_count'];
            if ($ctr == 0) continue;

            for ($i = 1; $i < $ctr; $i++){
                $mo++;
                if ($mo > 12){
                    $mo = 1;
                    $yr++;
                }

                if (!isset($ret[$yr])){
                    $ret[$yr] = array();
                }

                if (!isset($ret[$yr][$mo])){
                    $ret[$yr][$mo] = array();
                }

                $ret[$yr][$mo] = $row;

               }
        }
        mysqli_free_result($res);
        return $ret;
    }
    return false;
}


function getRepayment($pid){
	$sql = "SELECT * FROM vwpsi_repayments WHERE prj_id = $pid";
	$res = mysqli_query($GLOBALS['cn'], $sql);
    if ($res){
        $ret = array();
        while ($row = mysqli_fetch_array($res)){
			$ret = $row;
        }
        mysqli_free_result($res);
        return $ret;
    }
    return false;
}

function checkPaymentDate($pid, $yr, $mo){
    $res = getNextPaymentDate($pid);

    if (!$res) return false;
    return (($res['month'] == $mo) && ($res['year'] == $yr));
}

function getNextPaymentDate($pid){
    $rep = getRepayment($pid);

    if (!$rep) return false;
    $pay_year = $rep['rep_start_year'];
    $pay_month = $rep['rep_start_month'];

    // advance date by payments
    $payments = getAllPaymentDates($rep['rep_id']);
    if ($payments){
        if (count($payments) > 0){
            foreach ($payments as $payment_year) {
                foreach ($payment_year as $payment) {
                    if ($payment['pay_month'] >= $pay_month){
                        if ($payment['pay_year'] >= $pay_year){
                            $pay_year = $payment['pay_year'];
                            $pay_month = $payment['pay_month'];
                            $pay_month++;
                            if ($pay_month > 12){
                                $pay_month = 1;
                                $pay_year++;
                            }
                        }
                    }
                }
            }               
        }
    }
    $ret = array();
    $ret['year'] = $pay_year;
    $ret['month'] = $pay_month;
    return $ret;
}

function getDefermentLabel($i){
    if ($i == 1){
        return '1st Deferment';
    } elseif ($i == 2){
        return '2nd Deferment';
    } elseif ($i == 3){
        return '3rd Deferment';
    } elseif ($i == 4){
        return '4th Deferment';
    } elseif ($i == 5){
        return '5th Deferment';
    }
}

function month_exists($payments, $year, $month){
    if (!is_array($payments)) return false;
    if (!isset($payments[$year])) return false;
    if (!isset($payments[$year][$month])) return false;
    return true;
}

function get_year_month_array($year, $month, $count){

    $res = array();
    $yr = $year;
    $mo = $month;
    for ($i = 0; $i < $count; $i++){

        if (!isset($res[$yr])){
            $res[$yr] = array();
        }

        if (!isset($res[$yr][$mo])){
            $res[$yr][$mo] = 0;
        }

        $mo++;
        if ($mo > 12){
            $mo = 1;
            $yr++;
        }
    }
    return $res;
}

function month_range_invalid($rep, $year, $month, $count, $id, $check_bounds = true){
    if (!$rep) return true;
    
    $defcount = $rep['rep_deferment_monthcount'];
    $rep_month_count = $rep['rep_month_count'];
    $month_count = $rep_month_count;
    $month_count += $defcount;
    $remaining_months = $rep_month_count - $rep['rep_payment_count'];
    $month_count += 2;

    $start_year = $rep['rep_start_year'];
    $start_month = $rep['rep_start_month'];

    $end_time = strtotime($start_year.'-'.$start_month.'-'.'1 + '.($month_count).' Months');

    $end_year = intval(date('Y', $end_time));
    $end_month = intval(date('m', $end_time));

    $max_cols = $end_year - $start_year + 1;
    $max_rows = 12;

    $targets = array();
    $yr = $year;
    $mo = $month;
    $end_yr = $year;
    $end_mo = $month;
    $start_yr = $year;
    $start_mo = $month;

    $payments = getAllPaymentDates($rep['rep_id'], $id);

    for ($i = 0; $i < $count; $i++){
        if (!isset($targets[$yr])){
            $targets[$yr] = array();
        }

        if (!isset($targets[$yr][$mo])){
            $targets[$yr][$mo] = 0;
        }

        $end_yr = $yr;
        $end_mo = $mo;

        if (hit_months($yr, $mo, $payments)) {
            return true;
        }

        $mo++;
        if ($mo > 12){
            $mo = 1;
            $yr++;
        }
    }

    if ($check_bounds){

        if (month_out_of_bounds($start_yr, $start_mo, $end_yr, $end_mo, 
            $start_year, $start_month, $end_year, $end_month) ){
            return true;
        }
    }

    return false;
}

function hit_months($year, $month, $payments){
    if (!$payments) return false;
    if (!isset($payments[$year])) return false;
    if (!isset($payments[$year][$month])) return false;
    return true;
}

function month_out_of_bounds($year1, $month1, $year2, $month2,
                             $start_year, $start_month, $end_year, $end_month){
    if ($year2 < $start_year) {
        return true;
    } 
    if ($year1 > $end_year) {
        return true;

    }

    if ($year1 == $start_year){
        if ($month1 < $start_month){
            return true;
        }
    } 
    if ($year2 == $end_year){
        if ($month2 > $end_month){
            return true;
        }
    }
    return false;
}

// **********************************************

function load_coop_sectors($id){
    $sql = "SELECT * FROM psi_coop_sectors WHERE coop_id = $id";
    $res = mysqli_query($GLOBALS['cn'], $sql);

    if (!$res) return;

    while ($row = mysqli_fetch_array($res)){
        $fld_name = 'sec_'.$row['sector_id'];
        $GLOBALS[$fld_name] = $row['sector_id'];
    }
    mysqli_free_result($res);
}

function load_project_sectors($id){
    $sql = "SELECT * FROM psi_project_sectors WHERE prj_id = $id";
    $res = mysqli_query($GLOBALS['cn'], $sql);

    if (!$res) return;

    while ($row = mysqli_fetch_array($res)){
        $fld_name = 'sec_'.$row['sector_id'];
        $GLOBALS[$fld_name] = $row['sector_id'];
    }
    mysqli_free_result($res);
}

function get_sector_checkboxes(){
    $sql = "SELECT * FROM psi_sectors";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    $s = '';

    if (!$res) return $s;

    while ($row = mysqli_fetch_array($res)){
        $fld_name = 'sec_'.$row['sector_id'];
        $fld_val = '';

        if (isset($GLOBALS[$fld_name])){
            $fld_val = 'checked="checked"';
        }

        $s .= '
            <div class="checkbox">
            <label>
                <input name="'.$fld_name.'" id="'.$fld_name.'" type="checkbox" value="'.$row['sector_id'].'" '.$fld_val.'>
                '.$row['sector_name'].'
            </label>
            </div>
        ';
    }

    mysqli_free_result($res);
    return $s;
}

function save_other_sectors(){
    if (strlen($GLOBALS['sector_others']) == 0) return;

    if (!dbValueExists('psi_sectors', 'sector_name', $GLOBALS['sector_others'])){
        echo $GLOBALS['sector_others'];
        $sql = "INSERT INTO psi_sectors (sector_name) VALUES ('$GLOBALS[sector_others]')";
        mysqli_query($GLOBALS['cn'], $sql);
        $sid = mysqli_insert_id($GLOBALS['cn']);
        $GLOBALS['sector_id'] = $sid;
    } else {
        loadDBValues('psi_sectors', "SELECT sector_id FROM psi_sectors WHERE sector_name = '$GLOBALS[sector_others]'");
        $GLOBALS['sector_id'] = $sid;
    }
}

function save_form_sectors(){
    $sql = "SELECT * FROM psi_sectors";
    $res = mysqli_query($GLOBALS['cn'], $sql);

    $GLOBALS['gsectors'] = array();

    if (!$res) return '';
    $fld_names = '';
    while ($row = mysqli_fetch_array($res)){
        $fld_name = 'sec_'.$row['sector_id'];
        if (!isset($_POST[$fld_name])) continue;

        if (strlen($fld_names) > 0){
            $fld_names .= ', ';
        }
        $fld_names .= $fld_name;

        $GLOBALS['gsectors'][] = $row['sector_id'];
    }

    mysqli_free_result($res);
    return $fld_names;
}
?>