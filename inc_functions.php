<?php
$g_prefix = 'impression';

function getFileNameOnly(){
	return basename(__FILE__, '.php');
}

function getRecNo($tbl, $fld, $prefix){
	$sql = "SELECT * FROM $tbl ORDER BY $fld DESC";
	$count = 0;
	
	$ser = 0;
	$last_no = $prefix.'0-0000';
	$res = mysqli_query($GLOBALS['cn'], $sql);
	if (!$res) return '';
	if (mysqli_num_rows($res) > 0){
		if ($row = mysqli_fetch_array($res)){

			$last_no = $row[$fld];
		}
		mysqli_free_result($res);
	}
	$dp = strpos($last_no, '-');
	$ser = intval(substr($last_no, strlen($prefix), $dp - 1));
	$count = intval(substr($last_no, -4));
	$count++;
	if ($count > 9999){
		$ser++;
		$count = 1;
	}
	return $prefix.$ser.'-'.zeroLead($count, 4);
}

function postDate($date){
	$sdate = safeString($_POST[$date]);
	if (strlen($sdate) == 0){
		return false;
	}
	
	$sdate = explode('/', $sdate);
	
	if (!is_array($sdate)){
		return false;
	}
	if (count($sdate) != 3){
		return false;
	}
	
	if (!ctype_digit($sdate[0])){
		return false;
	}
	
	if (!ctype_digit($sdate[1])){
		return false;
	}
	
	if (!ctype_digit($sdate[2])){
		return false;
	}

	if (strlen($sdate[0]) < 1){
		return false;
	}

	if (strlen($sdate[1]) < 1){
		return false;
	}
	
	if (strlen($sdate[2]) < 4){
		return false;
	}
	
	if (checkdate($sdate[0], $sdate[1], $sdate[2])){
		return true;
	}
	
	return false;
} 

function postDateTime($date, $format = 'm/d/Y h:i a'){
	$sdate = safeString($_POST[$date]);
	if (strlen($sdate) == 0){
		return false;
	}

	return (DateTime::createFromFormat($format, $sdate) !== false);
} 

function validDate($date){
	$sdate = $date;
	if (strlen($sdate) == 0){
		return false;
	}
	
	$sdate = explode('/', $sdate);
	
	if (!is_array($sdate)){
		return false;
	}
	if (count($sdate) != 3){
		return false;
	}
	
	if (!ctype_digit($sdate[0])){
		return false;
	}
	
	if (!ctype_digit($sdate[1])){
		return false;
	}
	
	if (!ctype_digit($sdate[2])){
		return false;
	}

	if (strlen($sdate[0]) < 1){
		return false;
	}

	if (strlen($sdate[1]) < 1){
		return false;
	}
	
	if (strlen($sdate[2]) < 4){
		return false;
	}
	
	if (checkdate($sdate[0], $sdate[1], $sdate[2])){
		return true;
	}
	
	return false;
} 

function dateIsGreater($date1, $date2){
	$sdate = explode('/',$date1);
	$edate = explode('/',$date2);

	if (intval($edate[2]) >  intval($sdate[2])){
		return true;
	} else if (intval($edate[2]) <  intval($sdate[2])){
		return false;
	}
	
	if (intval($edate[0]) >  intval($sdate[0])){
		return true;
	} else if (intval($edate[0]) <  intval($sdate[0])){
		return false;
	}
	
	if (intval($edate[1]) <=  intval($sdate[1])){
		return false;
	}
	
	return true;
} 

function nullInt($p_val){
	if (is_null($p_val)){
		return 0;
	}
	$len = strlen($p_val);
	if ($len == 0){
		return 0;
	}
	return $p_val;
}

function zeroDateTime($p_date, $show = 1){
	if (is_null($p_date)){
		return '--';
	}
	if (strlen($p_date) == 0){
		return '--';
	}
	
	if ($show == 0){
		return '--';
	}
	return date('m/d/Y h:i:s a', strtotime($p_date));
}

function zeroDate($p_date, $show = 1, $format = 'm/d/Y'){
	if (is_null($p_date)){
		return '--';
	}
	if (strlen($p_date) == 0){
		return '--';
	}
	
	if ($show == 0){
		return '--';
	}
	return date($format, strtotime($p_date));
}

function blankDate($p_date){
	if (is_null($p_date)){
		return '';
	}
	if (strlen($p_date) == 0){
		return '';
	}
	return date('m/d/Y', strtotime($p_date));
}

function zeroCurr($p_val, $p_curr = 'PHP'){
	if (is_null($p_val)){
		return "$p_curr 0.00";
	}
	$len = strlen($p_val);
	if ($len == 0){
		return "$p_curr 0.00";
	}
	return $p_curr.'&nbsp;'.number_format($p_val, 2);
}

function zeroCurr0($p_val, $p_curr = 'PHP'){
	if (is_null($p_val)){
		return "$p_curr 0";
	}
	$len = strlen($p_val);
	if ($len == 0){
		return "$p_curr 0";
	}
	return $p_curr.'&nbsp;'.number_format($p_val, 0);
}

function zeroLead($p_val, $p_lead = 2){
	$len = strlen($p_val);
	if ($len < $p_lead){
		return str_repeat('0', $p_lead - $len).$p_val;
	}
	return $p_val;
}

function getCount($p_table, $p_id, $p_where){
	$ctr = 0;
	$sql = "SELECT COUNT($p_id) AS ccount FROM $p_table $p_where";
	$res = mysqli_query($GLOBALS['cn'], $sql);
	if ($res){
		if (mysqli_num_rows($res) > 0){
			if ($row = mysqli_fetch_array($res)){
				$ctr = $row['ccount'];
			}
		}

		mysqli_free_result($res);
	}
	return $ctr;
}

function countRecords($sql){
	$ctr = 0;
	$res = mysqli_query($GLOBALS['cn'], $sql);
	if ($res){
		$ctr = (mysqli_num_rows($res));
		mysqli_free_result($res);
	}
	return $ctr;
}

function txtTrim($p_txt, $p_len, $p_suffix = ' ...'){
	if ($p_txt == null){
		return '';
	}
	$len = strlen(strval($p_txt));
	if ($len == 0) {
		return '';
	}
	
	if  ($len > $p_len){
		return substr($p_txt, 0, $p_len).$p_suffix;
	}
	
	return $p_txt;
}

function randName($p_prefix,$p_ext){
	$s='';
	for ($i = 0; $i < 7; $i++){
		$s .= chr(rand(97,122));
	}
	$s = "$p_prefix-$s-".date('Ymd_His').".$p_ext";
	return $s;
}

function getExtension($filename)
{
	$path_info = pathinfo($filename);
	return $path_info['extension'];
}

function checkBox($p_value){
	if ($p_value == 1){
		return 'checked="checked"';
	}
	return '';
}

function radio($p_field, $p_value){
	if (!isset($GLOBALS[$p_field])) return '';

	if ($GLOBALS[$p_field] == $p_value){
		return 'checked="checked"';
	}
	return '';
}

function checkbox_multiselect($p_field, $p_value){
	if (!is_array($p_field)) return '';
	if (in_array($p_value, $p_field)){
		return 'checked="checked"';
	}
	return '';
}

function disabled($p_value){
	if ($p_value == false){
		return 'disabled="disabled"';
	}
	return '';
}

function yesno($p_value){
	if ($p_value == 1){
		return 'Yes';
	}
	return 'No';
}

function blankNumber($p_value, $p_dec = 2){
	if (strlen(trim($p_value)) == 0){
		return '';
	}
	
	if (!ctype_digit($p_value)){
		if (!is_numeric($p_value)){
			return $p_value;
		}
	}

	return number_format($p_value, $p_dec);
}

function zeroNumber($p_value, $p_dec = 2){
	if (strlen(trim($p_value.'')) == 0){
		return '--';
	}
	
	if (!ctype_digit($p_value.'')){
		if (!is_numeric($p_value)){
			return $p_value;
		}
	}

	if (($p_value == null) || ($p_value == 0)){
		return '--';
	}

	return number_format($p_value, $p_dec);
}

function zeroDash($p_value, $p_dec = 0){
	if (($p_value == null) || ($p_value == 0)){
		return '--';
	}
	
	return number_format($p_value, $p_dec);;
}

function postInteger($p_str){
	$value = safeString($_POST[$p_str]);
	
	if (!ctype_digit($value)){
		return false;
	}
	
	return true;
}

function postFloat($p_str){
	$value = safeString($_POST[$p_str]);
	
	if (!ctype_digit($value)){
		if (!is_numeric($value)){
			return false;
		}
	}
	
	return true;
}

function postZero($p_str){
	$value = safeString($_POST[$p_str]);
	
	if (intval($value) > 0){
		return false;
	}
	
	return true;
}

function postEmpty($p_str){
	if (!isset($_POST[$p_str])){
		return true;
	}
	
	if (is_array($_POST[$p_str])){
		if (count($_POST[$p_str]) == 0) return true;
		return false;
	}

	if (strlen(trim($_POST[$p_str])) == 0){
		return true;
	}
	
	return false;
}

function getPost($p_str, $p_string = true){
	if (!isset($_POST[$p_str])){
		if ($p_string){
			return '';
		} else {
			return 0;
		}
	}
	
	$val = safeString($_POST[$p_str]);
	
	if ($p_string){
		return $val;
	} else {
		if (strlen($val) == 0){
			return 0;
		} else {
			return intval($val);
		}
	}
}

function requestInteger($p_str, $p_redir = ''){
	if (isset($_REQUEST[$p_str])){
		$value = safeString($_REQUEST[$p_str]);
		if (strlen($value) > 0){
			if (ctype_digit($value)){
				return intval($value);
			}
		}
	}
	
	if (strlen($p_redir) > 0)	{
		header($p_redir);
		die();
	} 
	return 0;
}

function requestEmpty($p_str){
	if (!isset($_REQUEST[$p_str])){
		return true;
	}
	
	if (strlen(trim($_REQUEST[$p_str])) == 0){
		return true;
	}
	
	return false;
}

function sessionInteger($p_str){
	$value = safeString($_SESSION[$p_str]);
	
	if (!ctype_digit($value)){
		return false;
	}
	
	return true;
}

function sessionEmpty($p_str){
	if (!isset($_SESSION[$p_str])){
		return true;
	}
	
	if (strlen(trim($_SESSION[$p_str])) == 0){
		return true;
	}
	
	return false;
}

function loadSessionVars($p_fields){
	if (strlen($p_fields) > 0){
		$fields = explode(', ',$p_fields);
		if (is_array($fields)){
			foreach ($fields as $fld){
				if (isset($_SESSION[$fld])){
					$GLOBALS[$fld] = $_SESSION[$fld];
				}
			}
		}
	}
}

function deleteSession($var){
	$_SESSION[$var] = '';
	if (isset($_SESSION[$var])){
		unset($_SESSION[$var]);
	}
}

function isEmail($p_str, $p_chkdns = false){
	if (!isset($_POST[$p_str])){
		return false;
	}

	$res = false;

	$email = trim($_POST[$p_str]);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$res = false;
	} else {
		$res = true;
	}

	
	if (!$p_chkdns){
		return $res;
	}
	if (!$res) return false;
	
	$str = $email;
	$ar = explode("@",$str);
	$res = checkdnsrr($ar[1], 'MX');

	return $res;
}

function isMobile($p_str){
	if (!isset($_POST[$p_str])){
		return false;
	}

	$val = trim($_POST[$p_str]);
	$res = preg_match('/^+63\d{10}$/', $val);
	return $res;
}

function isMobile2($p_str){
	if (!isset($_POST[$p_str])){
		return false;
	}

	$val = trim($_POST[$p_str]);
	$res = preg_match('/^63\d{10}$/', $val);
	return $res;
}

// for use with smstools 3
function sendSMS($p_to, $p_msg, $p_gsm = '', $p_path = '/var/spool/sms/outgoing/'){
	$filename = tempnam("","");
	$file = fopen($filename,"w");
	fwrite($file, "To: ".$p_to."\n");
	fwrite($file, "\n");

	if (strLen($p_gsm) > 0){
		fwrite($file, "Queue: ".$p_to."\n");
		fwrite($file, "\n");
	}

	fwrite($file, $p_msg."\n");
	fclose($file);
	copy($filename, $p_path.basename($filename));
	unlink($filename);
}

function getSelect($p_labels, $p_values, $p_selected, $p_default = ''){
	$ctr = 0;
	$found = 0;
	$s = '';
	if (!is_array($p_labels)){
		return $s;
	}

	if (!is_array($p_values)){
		return $s;
	}
	
	$ctr = count($p_labels);
	
	if (count($p_values) < $ctr){
		$ctr = count($p_values);
	}
	
	for ($i = 0; $i < $ctr; $i++){
		$val = strval($p_values[$i]);
		$lab = strval($p_labels[$i]);
		if ($val == strval($p_selected)){
			$s .= '<option value="'.$val.'" selected="selected">'.$lab.'</option>';
			$found = 1;
		} else {
			$s .= '<option value="'.$val.'">'.$lab.'</option>';
		}
	}
	
	if (strlen($p_default) > 0){
		if ($found == 0){
			$s = $s.'<option value="0" selected="selected">'.$p_default.'</option>';
		} else {
			$s = $s.'<option value="0">'.$p_default.'</option>';
		}
	}
	
	return $s;
}

function hourSelect($p_min = 0, $p_max = 24, $p_selected = 0, $p_default = ''){
	$found = 0;
	$s = '';
	$max = $p_min + $p_max;
	for ($i = $p_min; $i < $max; $i++){
		$val = strval($i);
		$lab = $val;
		if ($val == strval($p_selected)){
			$s .= '<option value="'.$val.'" selected="selected">'.$lab.'</option>';
			$found = 1;
		} else {
			$s .= '<option value="'.$val.'">'.$lab.'</option>';
		}
	}

	if (strlen($p_default) > 0){
		if ($found == 0){
			$s = $s.'<option value="0" selected="selected">'.$p_default.'</option>';
		} else {
			$s = $s.'<option value="0">'.$p_default.'</option>';
		}
	}
	
	return $s;
}

function dbValueExists($p_table, $p_field, $p_value, $p_isString = true){
	$sql = "SELECT * FROM $p_table WHERE $p_field = '$p_value'";
	if (!$p_isString){
		$sql = "SELECT * FROM $p_table WHERE $p_field = $p_value";
	}
	
	$res = mysqli_query($GLOBALS['cn'], $sql);
	
	if (!$res){
		return false;
		die();
	}
	
	$count = mysqli_num_rows($res);
	
	mysqli_free_result($res);
	if ($count > 0){
		return true;
		die();
	}
	return false;
}

function dbValueUnique($p_table, $p_idfield, $p_idvalue, $p_field, $p_value, $p_isString = true){
	$sql = "SELECT * FROM $p_table WHERE ($p_field = '$p_value') AND ($p_idfield <> $p_idvalue)";
	if (!$p_isString){
		$sql = "SELECT * FROM $p_table WHERE ($p_field = $p_value AND) ($p_idfield <> $p_idvalue)";
	}
	
	$res = mysqli_query($GLOBALS['cn'], $sql);
	
	if (!$res){
		return false;
	}
	
	$count = mysqli_num_rows($res);
	
	mysqli_free_result($res);
	if ($count > 0){
		return true;
	}
	return false;
}

function loadDBValues($p_table, $p_sql){
	$sql = "DESCRIBE $p_table";
	$fields = mysqli_query($GLOBALS['cn'], $sql);

	if (!$fields){
		return false;
		die();
	}
	
	$res = mysqli_query($GLOBALS['cn'], $p_sql);
	
	if (!$res){
		return false;
		die();
	}
	
	if (!((bool)($row = mysqli_fetch_array($res)))){
		return false;
		die();
	}
	
	while ($fld = mysqli_fetch_array($fields)){
		if (strpos($fld['Type'], "datetime") !== false){
			if (strlen($row[$fld['Field']].'') > 0){
				$GLOBALS[$fld['Field']] = date('m/d/Y H:i:s', strtotime($row[$fld['Field']].''));
			} else {
				$GLOBALS[$fld['Field']] = '';
			}
		} elseif (strpos($fld['Type'], "date") !== false){
			if (strlen($row[$fld['Field']].'') > 0){
				$GLOBALS[$fld['Field']] = date('m/d/Y' , strtotime($row[$fld['Field']].''));
			} else {
				$GLOBALS[$fld['Field']] = '';
			}
		} else {
			$GLOBALS[$fld['Field']] = $row[$fld['Field']];
		}
	}

	@mysqli_free_result($res);
	@mysqli_free_result($fields);
}

function initFormValues($p_table, $p_fields = '', $p_value = ''){
	$sql = "DESCRIBE $p_table";
	$res = mysqli_query($GLOBALS['cn'], $sql);

	if (!$res){
		return false;
		die();
	}

	while ($fld = mysqli_fetch_array($res)){
		if ((strpos($fld['Type'], "int") !== false) || (strpos($fld['Type'], "double") !== false) || (strpos($fld['Type'], "decimal") !== false)){
			$GLOBALS[$fld['Field']] = 0;
		} elseif ((strpos($fld['Type'], "date") !== false) || (strpos($fld['Type'], "time") !== false)){
			$GLOBALS[$fld['Field']] = date('m/d/Y');
		} else {
			$GLOBALS[$fld['Field']] = "";
		}
		//echo $fld['Field'].', '.$fld['Type'].', '.$GLOBALS[$fld['Field']].', '.$GLOBALS[$fld['Field']].'<br>';
	}

	@mysqli_free_result($res);
	
	if (strlen($p_fields) > 0){
		$fields = explode(', ',$p_fields);
		if (is_array($fields)){
			foreach ($fields as $fld){
				$GLOBALS[$fld] = $p_value;
			}
		}
	}
}

function clearFormCache($p_table, $p_fields = '', $p_value = ''){
	global $g_prefix;
	$sql = "DESCRIBE $p_table";
	$res = mysqli_query($GLOBALS['cn'], $sql);

	if (!$res){
		return false;
		die();
	}

	while ($fld = mysqli_fetch_array($res)){
		if ((strpos($fld['Type'], "int") !== false) || (strpos($fld['Type'], "double") !== false)){
			$_SESSION[$g_prefix][$fld['Field']] = 0;
			$GLOBALS[$fld['Field']] = 0;
		} else {
			$GLOBALS[$fld['Field']] = "";
			$_SESSION[$g_prefix][$fld['Field']] = "";
		}  
	}

	@mysqli_free_result($res);
	
	if (strlen($p_fields) > 0){
		$fields = explode(', ',$p_fields);
		if (is_array($fields)){
			foreach ($fields as $fld){
				$GLOBALS[$fld] = $p_value;
				$_SESSION[$g_prefix][$fld['Field']] = $p_value;
			}
		}
	}
}

function deleteFormCache(){
	global $g_prefix;
	$_SESSION[$g_prefix] = NULL;
	unset($_SESSION[$g_prefix]);
}

function loadFormCache($p_table, $p_fields = ''){
	global $g_prefix;
	$sql = "DESCRIBE $p_table";
	$res = mysqli_query($GLOBALS['cn'], $sql);

	if (!$res){
		return false;
		die();
	}

	while ($fld = mysqli_fetch_array($res)){
		if (isset($_SESSION[$g_prefix][$fld['Field']])){
			$GLOBALS[$fld['Field']] = $_SESSION[$g_prefix][$fld['Field']];
		}
	}

	@mysqli_free_result($flds);

	if (strlen($p_fields) > 0){
		$fields = explode(', ',$p_fields);
		if (is_array($fields)){
			foreach ($fields as $fld){
				if (isset($_SESSION[$g_prefix][$fld])){
					$GLOBALS[$fld] = $_SESSION[$g_prefix][$fld];
				}
			}
		}
	}
}

function saveFormCache($p_table, $p_fields = ''){
	global $g_prefix;
	$sql = "DESCRIBE $p_table";
	$res = mysqli_query($GLOBALS['cn'], $sql);

	if (!$res){
		return false;
		die();
	}

	while ($fld = mysqli_fetch_array($res)){
		if (isset($_POST[$fld['Field']])){
			$_SESSION[$g_prefix][$fld['Field']] = safeString($_POST[$fld['Field']]);

			if (strpos($fld['Type'], "time") !== false){
				$tmp = strtotime($_SESSION[$g_prefix][$fld['Field']]);
				$GLOBALS[$fld['Field']] = date('Y-m-d H:i:s', $tmp);
			} elseif (strpos($fld['Type'], "date") !== false){
				if (strlen(trim($_SESSION[$g_prefix][$fld['Field']]) > 0)){
					$tmp = strtotime($_SESSION[$g_prefix][$fld['Field']]);
					$GLOBALS[$fld['Field']] = date('Y-m-d', $tmp);
				} else {
					//echo $fld['Field'].' = '.$_POST[$fld['Field']].'<br>';
				}
			} else {
				$GLOBALS[$fld['Field']] = $_SESSION[$g_prefix][$fld['Field']];
			}
			
		}
	}

	@mysqli_free_result($res);

	if (strlen($p_fields) > 0){
		$fields = explode(', ',$p_fields);
		if (is_array($fields)){
			foreach ($fields as $fld){
				if (isset($_POST[$fld])){
					if (is_array($_POST[$fld])){
						$_SESSION[$g_prefix][$fld] = $_POST[$fld];
					} else {
						$_SESSION[$g_prefix][$fld] = safeString($_POST[$fld]);
					}
					$GLOBALS[$fld] = $_SESSION[$g_prefix][$fld];
				}
			}
		}
	}
}

function safeString($p_str){
	if (is_array($p_str)) return $p_str;
	$str = trim($p_str);
	
	if (strlen($str) == 0){
		return '';
	}
	$str = stripslashes($str);
	$str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8', false);
	$str = mysqli_real_escape_string($GLOBALS['cn'], $str);
	return $str;
}

function safeString2($p_str){
	$str = trim($p_str);
	
	if (strlen($str) == 0){
		return '';
	}
	$str = str_replace("'", "&#39;", $str);
	return $str;
}

function rteSafe($p_text) {
	//returns safe code for preloading in the RTE
	$str = $p_text;
	
	//convert all types of single quotes
	$str = str_replace(chr(145), chr(39), $str);
	$str = str_replace(chr(146), chr(39), $str);
	$str = str_replace("'", "&#39;", $str);
	
	//convert all types of double quotes
	$str = str_replace(chr(147), chr(34), $str);
	$str = str_replace(chr(148), chr(34), $str);
	//$str = str_replace("\"", "\\\"", $str);
	
	//replace carriage returns & line feeds
	$str = str_replace(chr(10), " ", $str);
	$str = str_replace(chr(13), " ", $str);

	return $str;
}

function getOptions($p_table, $p_label, $p_value, $p_selected, $p_default = '', $p_order = '', $p_distinct = false){
	$sql = "SELECT * FROM $p_table ".$p_order;

	if ($p_distinct){
		$sql = "SELECT DISTINCT($p_value) FROM $p_table ".$p_order;
	}

	$res = mysqli_query($GLOBALS['cn'], $sql);
	$str = '';
	$found = false;
	
	if (!$res){
		return $str;
		die();
	}
	
	if (is_array($p_selected)){
		while ($row = mysqli_fetch_array($res)){
			if (in_array($row[$p_value], $p_selected)){
				$str .= '<option value="'.$row[$p_value].'" selected="selected">'.$row[$p_label].'</option>';
				$found = true;
			} else {
				$str .= '<option value="'.$row[$p_value].'">'.$row[$p_label].'</option>';
			}
		}
	} else {
		while ($row = mysqli_fetch_array($res)){
			if ($row[$p_value] == $p_selected){
				$str .= '<option value="'.$row[$p_value].'" selected="selected">'.$row[$p_label].'</option>';
				$found = true;
			} else {
				$str .= '<option value="'.$row[$p_value].'">'.$row[$p_label].'</option>';
			}
		}
	}

	@mysqli_free_result($res);
	
	if (strLen($p_default) > 0){
		if (!$found){
			$str = '<option value="0" selected="selected">'.$p_default.'</option>'.$str;
		} else {
			$str = '<option value="0">'.$p_default.'</option>'.$str;
		}
	}
	return $str;
}

function getQuarterIndex($month = 0){
	$mo = ($month == 0) ? date('2'): $month;
	if ($mo < 4) {
		return 1;
	} else if (($mo > 3) && ($mo < 7)) {
		return 2;
	} else if (($mo > 6) && ($mo < 10)) {
		return 3;
	} else if ($mo > 9) {
		return 4;
	}
}

function getDaysInMonth($month, $year)
{
	if ($month < 1) return 0;
	if ($month > 12) return 0;
	return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
} 

function getMonthName($mo, $format = 'F'){
	return date($format, mktime(0, 0, 0, $mo, 10));
}

function getMonthOptions($val, $p_default = ''){
	$str = '';
	$found = false;
	for ($i = 1; $i < 13; $i++){
		$timestamp = mktime(0, 0, 0, $i, date("d"), date("Y"));
		$st = date("F", $timestamp);  		
		if ($i == $val){
			$str .= '<option value="'.$i.'" selected="selected">'.$st.'</option>';
			$found = true;
		} else {
			$str .= '<option value="'.$i.'">'.$st.'</option>';
		}
	}
	if (strLen($p_default) > 0){
		if (!$found){
			$str = '<option value="0" selected="selected">'.$p_default.'</option>'.$str;
		} else {
			$str = '<option value="0">'.$p_default.'</option>'.$str;
		}
	}
	return $str;
}

function getMonthOptionsMax($val, $start = 1){
	$sTemp = '';
	for ($i = $start; $i < 13; $i++){
//		$timestamp = mktime(0, 0, 0, $i, date("d"), date("Y"));
		$st = numberToMonth($i);
		if ($i == $val){
			$sTemp .= '<option value="'.$i.'" selected="selected">'.$st.'</option>';
		} else {
			$sTemp .= '<option value="'.$i.'">'.$st.'</option>';
		}
	}
	return $sTemp;
}

function getDayOptionsMax($val, $mo = 5, $yr = 2013, $start = 1){
	$sTemp = '';
	
	$timestamp = mktime(0, 0, 0, $mo, 1, $yr);
	
	$maxDays = date('t', $timestamp);
	
	for ($i = $start; $i <= $maxDays; $i++){
		if ($i == $val){
			$sTemp .= '<option value="'.$i.'" selected="selected">'.$i.'</option>';
		} else {
			$sTemp .= '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	return $sTemp;
}

function getDayOptions($val, $p_default = ''){
	$str = '';
	$found = false;
	for ($i = 1; $i < 32; $i++){
		if ($i == $val){
			$str .= '<option value="'.$i.'" selected="selected">'.$i.'</option>';
			$found = true;
		} else {
			$str .= '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	if (strLen($p_default) > 0){
		if (!$found){
			$str = '<option value="0" selected="selected">'.$p_default.'</option>'.$str;
		} else {
			$str = '<option value="0">'.$p_default.'</option>'.$str;
		}
	}
	return $str;
}

function getYearOptions($val, $start = 2012, $ext = 2){
	$sTemp = '';
	$found = false;
	for ($i = $start; $i < (date("Y") + $ext); $i++){
		if (intval($i) == intval($val)){

			$sTemp .= '<option value="'.$i.'" selected="selected">'.$i.'</option>';
			$found = true;
		} else {
			$sTemp .= '<option value="'.$i.'">'.$i.'</option>';
		}
	}

	if (!$found){
		$sTemp .= '<option value="0" selected="selected">--</option>';
	} else {
		$sTemp .= '<option value="0">--</option>';

	}
	return $sTemp;
}

function getWeekDayOptions($val, $def = ''){
	$labels = array('Any', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	$sTemp = '';
	$found = false;
	for ($i = 1; $i <= 7; $i++){
		if ($i == $val){
			$sTemp .= '<option value="'.$i.'" selected="selected">'.$labels[$i].'</option>';
			$found = true;
		} else {
			$sTemp .= '<option value="'.$i.'">'.$labels[$i].'</option>';
		}
	}

	if (strlen($def) > 0){	
		if (!$found){
			$sTemp = '<option value="0" selected="selected">'.$def.'</option>'.$sTemp;
		} else {
			if ($val == 0){
				$sTemp = '<option value="0" selected="selected">'.$def.'</option>'.$sTemp;
			} else {
				$sTemp = '<option value="0">'.$def.'</option>'.$sTemp;
			}
		}
	}
	return $sTemp;
}

function getHourOptions($val, $def = ''){
	$sTemp = '';
	$found = false;
	for ($i = 0; $i <= 12; $i++){
		if ($i == $val){
			$sTemp .= '<option value="'.$i.'" selected="selected">'.$i.'</option>';
			$found = true;
		} else {
			$sTemp .= '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	
	if (strlen($def) > 0){	
		if (!$found){
			$sTemp = '<option value="-1" selected="selected">'.$def.'</option>'.$sTemp;
		} else {
			if ($val == -1){
				$sTemp = '<option value="-1" selected="selected">'.$def.'</option>'.$sTemp;
			} else {
				$sTemp = '<option value="-1">'.$def.'</option>'.$sTemp;
			}
		}
	}
	return $sTemp;
}

function getMinuteOptions($val, $def = ''){
	$sTemp = '';
	$found = false;
	for ($i = 0; $i <= 59; $i++){
		if ($i == $val){
			$sTemp .= '<option value="'.$i.'" selected="selected">'.$i.'</option>';
			$found = true;
		} else {
			$sTemp .= '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	if (strlen($def) > 0){	
		if (!$found){
			$sTemp = '<option value="-1" selected="selected">'.$def.'</option>'.$sTemp;
		} else {
			if ($val == -1){
				$sTemp = '<option value="-1" selected="selected">'.$def.'</option>'.$sTemp;
			} else {
				$sTemp = '<option value="-1">'.$def.'</option>'.$sTemp;
			}
		}
	}
	return $sTemp;
}

function getAmPmOptions($val, $def = ''){
	$labels = array('AM', 'PM');
	$sTemp = '';
	$found = false;
	for ($i = 0; $i < 2; $i++){
		if ($i == $val){
			$sTemp .= '<option value="'.$i.'" selected="selected">'.$labels[$i].'</option>';
			$found = true;
		} else {
			$sTemp .= '<option value="'.$i.'">'.$labels[$i].'</option>';
		}
	}
	if (strlen($def) > 0){	
		if (!$found){
			$sTemp = '<option value="-1" selected="selected">'.$def.'</option>'.$sTemp;
		} else {
			if ($val == -1){
				$sTemp = '<option value="-1" selected="selected">'.$def.'</option>'.$sTemp;
			} else {
				$sTemp = '<option value="-1">'.$def.'</option>'.$sTemp;
			}
		}
	}
	return $sTemp;
}

function getTriOptions($val){
	$labels = array('Any', 'No', 'Yes');
	$values = array(-1, 0, 1);
	$sTemp = '';
	for ($i = 0; $i < 3; $i++){
		if ($values[$i] == $val){
			$sTemp .= '<option value="'.$values[$i].'" selected="selected">'.$labels[$i].'</option>';
		} else {
			$sTemp .= '<option value="'.$values[$i].'">'.$labels[$i].'</option>';
		}
	}
	return $sTemp;
}

function getUpdateQuery($p_table, $p_idField, $p_fields = '', $p_types = '', $p_null = true, $p_no_empty = ''){
	$sql = "DESCRIBE $p_table";
	$no_empty = array();
	if (strLen($p_no_empty) > 0){
		$no_empty = explode(', ', $p_no_empty);
		if (!is_array($no_empty)){
			echo 'Get update query: not an array - '.$p_no_empty;
			die();
		}
	}
	$res = mysqli_query($GLOBALS['cn'], $sql);
	if (!$res){
		return '';
	}
	$sql = "";
	while ($fld = mysqli_fetch_array($res)){
		if ($fld['Field'] != $p_idField){
			$fldName = $fld['Field'];

			if ($fldName == 'encoder') continue;
			if ($fldName == 'date_encoded') continue;

			if ((strpos($fld['Type'], "int") !== false) || (strpos($fld['Type'], "double") !== false) || (strpos($fld['Type'], "decimal") !== false)){
				if (isset($GLOBALS[$fldName])){
					if (strlen($sql) > 0){
						$sql .= ", ";
					}
					$sql .= $fldName." = $GLOBALS[$fldName]";
				}
			} elseif ((strpos($fld['Type'], "varchar") !== false) || (strpos($fld['Type'], "text") !== false)){
				if (isset($GLOBALS[$fldName])){
					if (strlen($sql) > 0){
						$sql .= ", ";
					}
					$sql .= $fldName." = '$GLOBALS[$fldName]'";
				} elseif ($p_null && (!in_array($fldName, $no_empty))) {
					if (strlen($sql) > 0){
						$sql .= ", ";
					}
					$sql .= $fldName." = ''";
				}
				
			} elseif ((strpos($fld['Type'], "date") !== false) || (strpos($fld['Type'], "time") !== false)){
				if (isset($GLOBALS[$fldName])){
					if (strlen($sql) > 0){
						$sql .= ", ";
					}
					$sql .= $fldName." = '$GLOBALS[$fldName]'";
				} elseif ($p_null && (!in_array($fldName, $no_empty))) {
					if (strlen($sql) > 0){
						$sql .= ", ";
					}
					$sql .= $fldName." = NULL";
				}
			}  

		}
	}

	@mysqli_free_result($res);
	
	if (strlen($p_fields) > 0){
		$fields = explode(', ',$p_fields);
		if (is_array($fields)){
			$types = explode(', ',$p_types);
			if (is_array($types)){
				if (count($fields) == count($types)){
					for ($i = 0; $i < count($fields); $i++){
						$fldName = $fields[$i];
						if (isset($GLOBALS[$fldName])){
							if (strlen($sql) > 0){
								$sql .= ", ";
							}
							$sql .= $fldName." = ";
							if ($types[$i] == 0){
								$sql .= "$GLOBALS[$fldName]";
							} else {
								$sql .= "'$GLOBALS[$fldName]'";
							}
						}
					}
				}
			}
		}
	}

	if (strlen($sql) > 0){
		$sql =  "UPDATE $p_table SET ".$sql." WHERE $p_idField = $GLOBALS[$p_idField]";
	}
	
	return $sql;
}

function getInsertQuery($p_table, $p_idField, $p_fields = '', $p_types = '', $p_null = true, $p_no_empty = ''){
	$sql = "DESCRIBE $p_table";
	$no_empty = array();
	if (strLen($p_no_empty) > 0){
		$no_empty = explode(', ', $p_no_empty);
		if (!is_array($no_empty)){
			echo 'Get insert query: not an array - '.$p_no_empty;
			die();
		}
	}
	
	$res = mysqli_query($GLOBALS['cn'], $sql);
	if (!$res){
		return '';
	}
	$flist = '';
	$vlist = '';
	while ($fld = mysqli_fetch_array($res)){
		if ($fld['Field'] != $p_idField){
			$fldName = $fld['Field'];
			if ((strpos($fld['Type'], "int") !== false) || (strpos($fld['Type'], "double") !== false) || (strpos($fld['Type'], "decimal") !== false)){
				if (isset($GLOBALS[$fldName])){
					if (strlen($flist) > 0){
						$flist .= ", ";
						$vlist .= ", ";
					}
					$flist .= $fldName;
					$vlist .= "$GLOBALS[$fldName]";
				}
			} elseif ((strpos($fld['Type'], "varchar") !== false) || (strpos($fld['Type'], "text") !== false)){
				if (isset($GLOBALS[$fldName])){
					if (strlen($flist) > 0){
						$flist .= ", ";
						$vlist .= ", ";
					}
					$flist .= $fldName;
					$vlist .= "'$GLOBALS[$fldName]'";
				} else if ($p_null && (!in_array($fldName, $no_empty))) {
					if (strlen($flist) > 0){
						$flist .= ", ";
						$vlist .= ", ";
					}
					$flist .= $fldName;
					$vlist .= "''";
				}
				
			} elseif ((strpos($fld['Type'], "date") !== false) || (strpos($fld['Type'], "time") !== false)){
				if (isset($GLOBALS[$fldName])){
					if (strlen($flist) > 0){
						$flist .= ", ";
						$vlist .= ", ";
					}
					$flist .= $fldName;
					$vlist .= "'$GLOBALS[$fldName]'";
				} else if ($p_null && (!in_array($fldName, $no_empty))) {
					if (strlen($flist) > 0){
						$flist .= ", ";
						$vlist .= ", ";
					}
					$flist .= $fldName;
					$vlist .= "NULL";
				}
			}  
		}
	}

	@mysqli_free_result($res);
	
	if (strlen($p_fields) > 0){
		$fields = explode(', ',$p_fields);
		if (is_array($fields)){
			$types = explode(', ',$p_types);
			if (is_array($types)){
				if (count($fields) == count($types)){
					for ($i = 0; $i < count($fields); $i++){
						$fldName = $fields[$i];
						if (isset($GLOBALS[$fldName])){
							if (strlen($flist) > 0){
								$flist .= ", ";
								$vlist .= ", ";
							}
							$flist .= $fldName;
							if ($types[$i] == 0){
								$vlist .= "$GLOBALS[$fldName]";
							} else {
								$vlist .= "'$GLOBALS[$fldName]'";
							}  
						}
					}
				}
			}
		}
	}

	if (strlen($flist) > 0){
		$sql =  "INSERT INTO $p_table ($flist) VALUES ($vlist)";
	}

	return $sql;
}

function getDeleteQuery($p_table, $p_idField, $p_idValue){
	return "DELETE FROM $p_table WHERE $p_idField = $p_idValue";
}

function numberToAP($val){
	return ($val == 0 ? 'AM' : 'PM');
}

function numberToDay($val){
	$labels = array('Any', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	return $labels[$val];
}

function numberToMonth($val){
	$labels = array('Any', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	return $labels[$val];
}

function redirect($s){
	header('location: ' . $s);
	die();
}

function getRightsString($rights){
	if (is_null($rights)) return null;
	if (!is_array($rights)) return null;
	if (count($rights) == 0)return null;
	$s = '';
	foreach($rights as $r){
		if (strlen($s) > 0){
			$s .= ', ';
		}
		$s .= $r;
	}
	return $s;
}

function ifNoRightsRedirect($array, $path){
	if (is_null($array)){
		redirect($path);
		die();
	}
	if (!is_array($array)){
		redirect($path);
		die();
	}
	if (count($array) == 0){
		redirect($path);
		die();
	}
}

function hasRights($rights){
	if (is_null($rights)) return false;
	if (strlen($rights) == 0) return false;
	return true;
}

?>