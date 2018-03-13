<?php
require_once("inc_conn.php");
if (requestEmpty('pid')) return '';
if (requestEmpty('id')) return '';

$pid = requestInteger('pid');
$id = requestInteger('id');

if ($pid == 0) return '';


$select = getOptions('psi_cities', 'city_name', 'city_id', $id, '', "WHERE province_id = $pid ORDER BY city_name ASC");

echo $select;
?>