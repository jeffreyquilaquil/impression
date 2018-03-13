<?php
require_once ('inc_conn.php');

if ($GLOBALS['ad_loggedin'] == 0){
    redirect('loginform.php');
	die();
}

if ($GLOBALS['under_maintenance'] == 1){
    redirect('logout.php');
	die();
}

?>