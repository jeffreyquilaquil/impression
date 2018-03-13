<?php
require_once("inc_conn.php");
if (requestEmpty('pid')) return '';
if (requestEmpty('id')) return '';

$pid = requestInteger('pid');
$id = requestInteger('id');

if ($pid == 0) return '';


$select = getOptions('psi_barangays', 'barangay_name', 'barangay_id', $id, '', "WHERE city_id = $pid ORDER BY barangay_name ASC");

echo $select;
?>