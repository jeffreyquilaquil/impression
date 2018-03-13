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
            $_SESSION[$qpx_key] = '';
        }

        if (!postEmpty('qpd')){
            $_SESSION[$qpd_key] = safeString($_POST['qpd']);
        } else {
            $_SESSION[$qpd_key] = '';
        }

    } else if ($_POST['search'] == 'Show All'){
        $_SESSION[$q_key] = '';
        $_SESSION[$qg_key] = 0;
        $_SESSION[$qs_key] = 0;
        $_SESSION[$qy_key] = 0;
        $_SESSION[$qpr_key] = 0;
        $_SESSION[$qpx_key] = '';
        $_SESSION[$qpd_key] = '';
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

$where = '';
if (in_pstc($GLOBALS['ad_ug_name'])){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    $where .= "(ug_id = $GLOBALS[ad_ug_id])";
}


if (strlen($q) > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    $where .= "((prj_title like '%$q%') OR (prj_code like '%$q%') OR (coop_names like '%$q%') OR (coop_p_names like '%$q%') OR (collaborator_names like '%$q%'))";
}

if ($qg > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    $where .= "(prj_type_id = $qg)";
}

if ($qs > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(prj_status_id = $qs)";
}

if ($qy > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(prj_year_approved = $qy)";
}

if ($qpr > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(province_id = $qpr)";
}

if ($qpx > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(sector_id = $qpx)";
}

if ($qpd > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(district_id = $qpd)";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY prj_title ASC';

//echo $sql;

//die();

$rows = mysqli_query($GLOBALS['cn'], $sql);
$sel_projecttypes = getOptions('psi_project_types', 'prj_type_name', 'prj_type_id', $qg, 'All');
$sel_projectstatus = getOptions('psi_project_status', 'prj_status_name', 'prj_status_id', $qs , 'All');
$sel_projectyear = getOptions('psi_projects', 'prj_year_approved', 'prj_year_approved', $qy , 'All', ' ORDER BY prj_year_approved DESC', true);
$sel_province = getOptions('psi_provinces', 'province_name', 'province_id', $qpr , 'All', 'WHERE region_id = '.$GLOBALS['ad_u_region_id'].' ORDER BY province_name ASC');
$sel_sectors = getOptions('psi_sectors', 'sector_name', 'sector_id', $qpx, 'All');
$sel_districts = getOptions('psi_districts', 'district_name', 'district_id', $qpd, 'All');

page_header('Projects');
if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
$amt_due = 0;
$amt_paid = 0;
$refund_rate = 0;
$project_cost = 0;
$ctr = 0;
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Projects</h3>
        <div class="pull-right hidden-print">
            <a id="print-list-btn" name="print-list-btn" class="btn btn-primary btn-sm" href="javascript:void(0);" title="Print List"><span class="fa fa-print"></span> Print</a>
            <a id="download-list-btn" name="download-list-btn" class="btn btn-primary btn-sm" href="javascript:void(0);" title="Download List"><span class="fa fa-floppy-o"></span> Download</a>
            <?php 
            if (can_access('Projects', 'add')){
            ?>
            <a class="btn btn-primary btn-sm" href="projects_form.php?op=0&amp;id=0" title="Add Projects"><span class="fa fa-plus"></span> Add Projects</a>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="projects.php" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Project Type</span>
                    <select class="form-control input-sm" id="qg" name="qg">
                    <?php echo $sel_projecttypes; ?>
                    </select>
                </div>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Status</span>
                    <select class="form-control input-sm" id="qs" name="qs">
                    <?php echo $sel_projectstatus; ?>
                    </select>
                </div>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Year Approved</span>
                    <select class="form-control input-sm" id="qy" name="qy">
                    <?php echo $sel_projectyear; ?>
                    </select>
                </div>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Province</span>
                    <select class="form-control input-sm" id="qpr" name="qpr">
                    <?php echo $sel_province; ?>
                    </select>
                </div>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">District</span>
                    <select class="form-control input-sm" id="qpd" name="qpd">
                        <?php echo $sel_districts; ?>
                    </select>
                </div>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Sector</span>
                    <select class="form-control input-sm" id="qpx" name="qpx">
                        <?php echo $sel_sectors; ?>
                    </select>
                </div>

                <div class="input-group input-group-sm">
                    <input class="form-control input-sm" placeholder="Search text ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
                    <span class="input-group-btn">
                        <input class="btn btn-primary btn-sm" type="submit" name="search" id="search" value="Search">
                    </span>
                </div>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table id="grid_table" class="table table-striped table-hover table-condensed table-bordered tablesorter">
            <thead>
            	<tr>
                    <th class="hidden-print">&nbsp;</th>
                    <th>#</th>
                    <th>Code</th>
                    <th>Project</th>
                    <th>Type</th>
                    <th>Year Approved</th>
                    <th class="hidden-print">Beneficiaries</th>
                    <th class="hidden-print">Collaborators</th>
                    <th>Sector</th>
                    <th>Province</th>
                    <th>City</th>
                    <th>District</th>
                    <th>Status</th>
                    <th>Project Cost</th>
                    <th>Amount Due</th>
                    <th>Refunded</th>
                    <th>Refund Rate</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
        	if ($rows) {
                $ctr = 0;
                while($row = mysqli_fetch_array($rows)) {
        			$action = '';

                    if (can_access('Projects', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'projects_view.php?pid='.$row['prj_id'].'" title="View Details"><span class="fa fa-folder-open"></span></a>  ';
                    }

                    if ($row['region_id'] == $GLOBALS['ad_u_region_id']){
                        if (can_access('Projects', 'edit')){
                            $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'projects_form.php?op=1&amp;id='.$row['prj_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                        }
                        if (can_access('Projects', 'delete')){
                            $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'projects_delete.php?op=2&amp;id='.$row['prj_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>';
                        }
                    }


                    $objective = nl2br($row['prj_objective'].'');
                    $expected_output = nl2br($row['prj_expected_output'].'');
                    $beneficiaries = $row['coop_names'];
                    $collaborators = $row['collaborator_names'];
                    $ctr++;

                    $amt_due += $row['rep_total_due'];
                    $amt_paid += $row['rep_total_paid'];
                    $project_cost += $row['prj_cost_setup'];

?>                
                  <tr>
                    <td class="nowrap text-left hidden-print"><?php echo $action; ?></td>
                    <td class="nowrap text-left"><?php echo $ctr; ?></td>
                    <td class="nowrap"><?php echo $row['prj_code']; ?></td>
                    <td><?php echo $row['prj_title']; ?></td>
                    <td><?php echo $row['prj_type_name']; ?></td>
                    <td class="text-center nowrap"><?php echo $row['prj_year_approved']; ?></td>
                    <td class="text-left"><?php echo $beneficiaries; ?></td>
                    <td class="text-left"><?php echo $collaborators; ?></td>
                    <td><?php echo $row['sector_name']; ?></td>
                    <td><?php echo $row['province_name']; ?></td>
                    <td><?php echo $row['city_name']; ?></td>
                    <td class="text-center"><?php echo $row['district_name']; ?></td>
                    <td class="nowrap"><?php echo $row['prj_status_name']; ?></td>
                    <td class="nowrap text-right"><?php echo zeroCurr($row['prj_cost_setup']); ?></td>
                    <td class="nowrap text-right"><?php echo zeroCurr($row['rep_total_due']); ?></td>
                    <td class="nowrap text-right"><?php echo zeroCurr($row['rep_total_paid']); ?></td>
                    <td class="nowrap text-right"><?php echo zeroNumber($row['rep_refund_rate'], 0).'%'; ?></td>
                  </tr>
<?php
        		}

                mysqli_free_result($rows);

                if ($amt_paid > 0){
                    if ($amt_due > 0){
                        $refund_rate = ($amt_paid / $amt_due) * 100;
                    }
                }
        	}

?>         
            </tbody>
        </table>
    </div>

    <div class="panel-footer">
        <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th class="text-center">Total Number of Projects</th>
                <th class="text-center">Total Project Cost</th>
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
                    <?php echo zeroCurr($project_cost); ?>
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
    <div class="panel-footer">
        <div id="grid_pager" class="hidden-print">
            <form class="form-inline" style="text-align:center;" role="form">
                <div class="form-group form-group-sm">
                    <a class="btn btn-primary btn-sm first" title="First"><span class="glyphicon glyphicon-step-backward"></span></a>
                    <a class="btn btn-primary btn-sm prev" title="Previous"><span class="glyphicon glyphicon-backward"></span></a>
                    <input type="text" class="form-control input-sm text-center pagedisplay" disabled>
                    <a class="btn btn-primary btn-sm next" title="Next"><span class="glyphicon glyphicon-forward"></span></a>
                    <a class="btn btn-primary btn-sm last" title="Last"><span class="glyphicon glyphicon-step-forward"></span></a>
                    <select class="form-control input-sm pagesize" title="No. of items per page.">
                        
                        <option selected="selected" value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>
<form name="print-form" id="print-form" action="projects_printlist.php" method="post"  target="_blank">
    <input type="hidden" id="qg" name="qg" value="<?php echo $qg; ?>">
    <input type="hidden" id="qs" name="qs" value="<?php echo $qs; ?>">
    <input type="hidden" id="qy" name="qy" value="<?php echo $qy; ?>">
    <input type="hidden" id="qpr" name="qpr" value="<?php echo $qpr; ?>">
    <input type="hidden" id="qpx" name="qpx" value="<?php echo $qpx; ?>">
    <input type="hidden" id="q" name="q" value="<?php echo $q; ?>">
</form>

<form name="download-form" id="download-form" action="projects_downloadlist.php" method="post"  target="_blank">
    <input type="hidden" id="qg" name="qg" value="<?php echo $qg; ?>">
    <input type="hidden" id="qs" name="qs" value="<?php echo $qs; ?>">
    <input type="hidden" id="qy" name="qy" value="<?php echo $qy; ?>">
    <input type="hidden" id="qpr" name="qpr" value="<?php echo $qpr; ?>">
    <input type="hidden" id="qpx" name="qpx" value="<?php echo $qpx; ?>">
    <input type="hidden" id="q" name="q" value="<?php echo $q; ?>">
</form>

<script type="text/javascript">
var _tsOptions = {
    headers:{
        0: { sorter: false}
        }
    };
</script>
<?php
    page_footer();
    deleteFormCache();
?>
