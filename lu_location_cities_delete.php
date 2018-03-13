<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Location Listings', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_location_regions.php');
$rid = requestInteger('rid', 'location: '.WEBSITE_URL.'lu_location_regions.php');
$pid = requestInteger('pid', 'location: '.WEBSITE_URL."lu_location_provinces.php?rid=$rid");
$id = requestInteger('id', 'location: '.WEBSITE_URL."lu_location_cities.php?rid=$rid&pid=$pid");

$sql = "DELETE FROM psi_cities WHERE city_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL."lu_location_cities.php?rid=$rid&pid=$pid");
?>