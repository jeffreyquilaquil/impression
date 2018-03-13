<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Location Listings', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_location_regions.php');
$rid = requestInteger('rid', 'location: '.WEBSITE_URL.'lu_location_regions.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL."lu_location_provinces.php?rid=$rid");

$sql = "DELETE FROM psi_provinces WHERE province_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL."lu_location_provinces.php?rid=$rid");
?>