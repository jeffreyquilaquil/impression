<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Projects', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$q_key = 'q_projects';
$qg_key = 'qg_projects';
$qs_key = 'qs_projects';
$qy_key = 'qy_projects';
$qpr_key = 'qpr_projects';
$qpx_key = 'qpx_projects';
$qpd_key = 'qpd_projects';

$q = '';
$qg = 0;
$qs = 0;
$qy = 0;
$qpr = '';
$qpx = 0;
$qpd = 0;

if (!postEmpty('search')){
    if ($_POST['search'] == 'Search'){
        if (!postEmpty('q')){
            $_SESSION[$q_key] = safeString($_POST['q']);
        } else {
            $_SESSION[$q_key] = '';
        }

        if (!postEmpty('qg')){
            $_SESSION[$qg_key] = safeString($_POST['qg']);
        } else {
            $_SESSION[$qg_key] = -1;
        }

        if (!postEmpty('qs')){
            $_SESSION[$qs_key] = safeString($_POST['qs']);
        } else {
            $_SESSION[$qs_key] = -1;
        }

        if (!postEmpty('qy')){
            $_SESSION[$qy_key] = safeString($_POST['qy']);
        } else {
            $_SESSION[$qy_key] = -1;
        }

        if (!postEmpty('qpr')){
            $_SESSION[$qpr_key] = safeString($_POST['qpr']);
        } else {
            $_SESSION[$qpr_key] = -1;
        }

        if (!postEmpty('qpx')){
            $_SESSION[$qpx_key] = safeString($_POST['qpx']);
        } else {
            $_SESSION[$qpx_key] = -1;
        }

        if (!postEmpty('qpd')){
            $_SESSION[$qpd_key] = safeString($_POST['qpd']);
        } else {
            $_SESSION[$qpd_key] = -1;
        }

    } else if ($_POST['search'] == 'Show All'){
        $_SESSION[$q_key] = '';
        $_SESSION[$qg_key] = 0;
        $_SESSION[$qs_key] = 0;
        $_SESSION[$qy_key] = 0;
        $_SESSION[$qpr_key] = 0;
        $_SESSION[$qpx_key] = 0;
        $_SESSION[$qpd_key] = 0;
    }
}

if (!sessionEmpty($q_key)){
    $q = $_SESSION[$q_key];
}

if (!sessionEmpty($qg_key)){
    $qg = intval($_SESSION[$qg_key]);
}

if (!sessionEmpty($qs_key)){
    $qs = intval($_SESSION[$qs_key]);
}

if (!sessionEmpty($qy_key)){
    $qy = intval($_SESSION[$qy_key]);
}

if (!sessionEmpty($qpr_key)){
    $qpr = $_SESSION[$qpr_key];
}

if (!sessionEmpty($qpx_key)){
    $qpx = $_SESSION[$qpx_key];
}

if (!sessionEmpty($qpd_key)){
    $qpd = $_SESSION[$qpd_key];
}


$sql = "SELECT * FROM vwpsi_projects";
$filters = '';
$where = '';
if (in_pstc($GLOBALS['ad_ug_name'])){
    $where .= "(ug_id = $GLOBALS[ad_ug_id])";
    $filters .= $GLOBALS['ad_ug_name'];
}


if (strlen($q) > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
        $filters .= '<br>';
    }
    $where .= "((prj_title like '%$q%') OR (prj_code like '%$q%') OR (coop_names like '%$q%') OR (coop_p_names like '%$q%') OR (collaborator_names like '%$q%'))";
    $filters .= "Keyword : ".$q;
}

if ($qg > 0){
    loadDBValues("psi_project_types", "SELECT * FROM psi_project_types WHERE prj_type_id = $qg");

    if (strlen($where) > 0){
        $where .= " AND ";
        $filters .= '<br>';
    }
    $where .= "(prj_type_id = $qg)";
    $filters .= 'Type : '.$GLOBALS['prj_type_name'];
}

if ($qs > 0){
    loadDBValues("psi_project_status", "SELECT * FROM psi_project_status WHERE prj_status_id = $qs");
    if (strlen($where) > 0){
        $where .= " AND ";    
        $filters .= '<br>';
    }
    $where .= "(prj_status_id = $qs)";
    $filters .= 'Status : '.$GLOBALS['prj_status_name'];
}

if ($qy > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
        $filters .= '<br>';
    }
    $where .= "(prj_year_approved = $qy)";
    $filters .= 'Year Approved : '.$qy;
}

if ($qpr > 0){
    loadDBValues("psi_provinces", "SELECT * FROM psi_provinces WHERE province_id = $qpr");
    if (strlen($where) > 0){
        $where .= " AND ";    
        $filters .= '<br>';
    }
    $where .= "(province_id = $qpr)";
    $filters .= 'Province : '.$GLOBALS['province_name'];
}

if ($qpx > 0){
    loadDBValues("psi_sectors", "SELECT * FROM psi_sectors WHERE sector_id = $qpx");
    if (strlen($where) > 0){
        $where .= " AND ";    
        $filters .= '<br>';
    }
    $where .= "(sector_id = $qpx)";
    $filters .= 'Sector : '.$GLOBALS['sector_name'];
}


if ($qpd > 0){
    loadDBValues("psi_districts", "SELECT * FROM psi_districts WHERE district_id = $qpd");
    if (strlen($where) > 0){
        $where .= " AND ";    
        $filters .= '<br>';
    }
    $where .= "(district_id = $qpd)";
    $filters .= 'District : '.$GLOBALS['district_name'];
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY prj_title ASC';

//echo $sql;

//die();

$rows = mysqli_query($GLOBALS['cn'], $sql);

print_header('Projects');
$ctr = 0;
$amt_due = 0;
$amt_paid = 0;
$refund_rate = 0;
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Projects<br>
            <small><?php echo $filters; ?></small>
        </h3>
        <div class="pull-right hidden-print">
            <a id="print-page-btn" name="print-page-btn" class="btn btn-primary btn-sm hidden-print" href="javascript:void(0);" title="Print Page"><span class="fa fa-print"></span> Print</a>
        </div>
    </div>
        <table class="table table-striped table-hover table-condensed table-bordered">
            <thead>
            	<tr>
                    <th>#</th>
                    <th>Project</th>
                    <th>Type</th>
                    <th>Year Approved</th>
                    <th>Beneficiaries</th>
                    <th>Collaborators</th>
                    <th>Status</th>
                    <th>Amount Due</th>
                    <th>Refunded</th>
                    <th>Refund Rate</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
        	if ($rows) {
                while($row = mysqli_fetch_array($rows)) {
        			$action = '';

                    if (can_access('Projects', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'projects_view.php?pid='.$row['prj_id'].'" title="View Details"><span class="fa fa-folder-open"></span></a>  ';
                    }
                    if (can_access('Projects', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'projects_form.php?op=1&amp;id='.$row['prj_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }
                    if (can_access('Projects', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'projects_delete.php?op=2&amp;id='.$row['prj_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>';
                    }


                    $objective = nl2br($row['prj_objective'].'');
                    $expected_output = nl2br($row['prj_expected_output'].'');
                    $beneficiaries = $row['coop_names'];
                    $collaborators = $row['collaborator_names'];
                    $ctr++;

                    $amt_due += $row['rep_total_due'];
                    $amt_paid += $row['rep_total_paid'];

?>                
                  <tr>
                    <td class="nowrap text-right"><?php echo $ctr; ?></td>
                    <td><?php if (strlen($row['prj_code'].'') > 0){
                        ?><span class="text-primary"><?php echo $row['prj_code']; ?></span><br><?php
                    } ?><?php echo $row['prj_title']; ?>
                    </td>
                    <td><?php echo $row['prj_type_name']; ?></td>
                    <td class="nowrap"><?php echo $row['prj_year_approved']; ?></td>
                    <td><?php echo $beneficiaries; ?></td>
                    <td><?php echo $collaborators; ?></td>
                    <td class="nowrap"><?php echo $row['prj_status_name']; ?></td>
                    <td class="nowrap text-right"><?php echo zeroCurr($row['rep_total_due']); ?></td>
                    <td class="nowrap text-right"><?php echo zeroCurr($row['rep_total_paid']); ?></td>
                    <td class="nowrap text-right"><?php echo zeroNumber($row['rep_refund_rate'], 0).'%'; ?></td>
                  </tr><?php
        		}

                if ($amt_paid > 0){
                    if ($amt_due > 0){
                        $refund_rate = ($amt_paid / $amt_due) * 100;
                    }
                }

                mysqli_free_result($rows);
        	}
?>         
            </tbody>
        </table>

    <div class="panel-footer">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th class="text-center">Total Number of Projects</th>
                <th class="text-center">Total Amount Due</th>
                <th class="text-center">Total Amount Refunded</th>
                <th class="text-center">Refund Rate</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center">
                    <?php echo $ctr; ?>
                </td>
                <td class="text-center">
                    <?php echo zeroCurr($amt_due); ?>
                </td>
                <td class="text-center">
                    <?php echo zeroCurr($amt_paid); ?>
                </td>
                <td class="text-center">
                    <?php echo zeroNumber($refund_rate, 0).'%'; ?>
                </td>
            <tr>
            </tbody>
        </table>
    </div>
</div>
<?php
    print_footer();

?>
