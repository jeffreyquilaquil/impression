<?php
require_once('inc_conn.php');
require_once('inc_secure.php');

if (!can_access('Projects', 'delete')){
    redirect(WEBSITE_URL.'index.php');
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'projects.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'projects.php');

$sql = "DELETE FROM psi_projects WHERE prj_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_equipment WHERE prj_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_consultancies WHERE prj_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_consulatncy_documents WHERE con_id NOT IN (SELECT con_id FROM psi_consultancies)";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_fora WHERE prj_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_fora_documents WHERE tr_id NOT IN (SELECT tr_id FROM psi_fora)";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_project_albums WHERE prj_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_project_album_photos WHERE album_id NOT IN (SELECT album_id FROM psi_project_albums)";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_project_beneficiaries WHERE prj_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_project_collaborators WHERE prj_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_project_documents WHERE prj_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_project_monitoring WHERE prj_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_project_pis WHERE prj_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_project_sectors WHERE prj_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_project_status_history WHERE prj_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_repayments WHERE prj_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_repayments_payments WHERE rep_id NOT IN (SELECT rep_id FROM psi_repayments)";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_trainings WHERE prj_id = $id";
mysqli_query($GLOBALS['cn'], $sql);

$sql = "DELETE FROM psi_training_documents WHERE tr_id NOT IN (SELECT tr_id FROM psi_trainings)";
mysqli_query($GLOBALS['cn'], $sql);

$msg = 'Record Deleted.';

$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'projects.php');
?>