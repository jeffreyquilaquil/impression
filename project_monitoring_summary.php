<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Project Monitoring', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$q_key = 'q_project_monitoring_summary';
$qg_key = 'qg_project_monitoring_summary';
$qs_key = 'qs_project_monitoring_summary';
$qy_key = 'qy_project_monitoring_summary';

$qpr_key = 'qpr_project_monitoring_summary';
$qyr_key = 'qyr_project_monitoring_summary';
$qqr_key = 'qqr_project_monitoring_summary';

$q = '';
$qg = 0;
$qs = 0;
$qy = 0;
$qpr = 0;
$qyr = 0;
$qqr = 0;

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
            $_SESSION[$qpr_key] = '';
        }

        if (!postEmpty('qyr')){
            $_SESSION[$qyr_key] = safeString($_POST['qyr']);
        } else {
            $_SESSION[$qyr_key] = -1;
        }

        if (!postEmpty('qqr')){
            $_SESSION[$qqr_key] = safeString($_POST['qqr']);
        } else {
            $_SESSION[$qqr_key] = -1;
        }


    } else if ($_POST['search'] == 'Show All'){
        $_SESSION[$q_key] = '';
        $_SESSION[$qg_key] = 0;
        $_SESSION[$qs_key] = 0;
        $_SESSION[$qy_key] = 0;
        $_SESSION[$qpr_key] = 0;
        $_SESSION[$qyr_key] = 0;
        $_SESSION[$qqr_key] = 0;
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

if (!sessionEmpty($qyr_key)){
    $qyr = intval($_SESSION[$qyr_key]);
}

if (!sessionEmpty($qqr_key)){
    $qqr = intval($_SESSION[$qqr_key]);
}
/*
if (($qyr == 0) && ($qqr == 0)) {
    $qyr = intval(Date('Y'));
    $qmo = intval(Date('n'));
    if ($qmo >= 10){
        $qqr = 4;
    } else if (($qmo >= 7) && ($qmo <= 9)){
        $qqr = 3;
    } else if (($qmo >= 4) && ($qmo <= 6)){
        $qqr = 2;
    } else if ($qmo <= 3){
        $qqr = 1;
    }
}
*/
$filters = '';
$sql = "SELECT * FROM vwpsi_project_monitoring_summary";
$where = "(prjform_id != 1)";

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
    $filters .= "Search for $q";    
}

if ($qg > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
        $filters .= ", ";    
    }
    $where .= "(prj_type_id = $qg)";
    $filters .= ", ";    
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

if ($qyr > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(prjmon_year = $qyr)";
}

if ($qqr > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(quarter_id = $qqr)";
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
$sel_years = getMonitoringYearOptions($qyr);
$sel_quarters = getOptions('psi_quarters', 'quarter_name', 'quarter_id', $qqr, 'All');

page_header('Project Status Reports List');
if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
$ctr = 0;
$amt_due = 0;
$amt_paid = 0;
$refund_rate = 0;
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Status Reports</h3>
        <div class="pull-right hidden-print">
            <!-- <a id="print-list-btn" name="print-list-btn" class="btn btn-primary btn-sm" href="javascript:void(0);" title="Print List"><span class="fa fa-print"></span> Print</a> -->
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="project_monitoring_summary.php" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Year</span>
                    <select class="form-control input-sm" id="qyr" name="qyr">
                    <?php echo $sel_years; ?>
                    </select>
                </div>
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Quarter</span>
                    <select class="form-control input-sm" id="qqr" name="qqr">
                    <?php echo $sel_quarters; ?>
                    </select>
                </div>
            </div>
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
                    <span class="input-group-addon">Province</span>
                    <select class="form-control input-sm" id="qpr" name="qpr">
                    <?php echo $sel_province; ?>
                    </select>
                </div>

            </div>
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <input class="form-control input-sm" placeholder="Project Title ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
                    <span class="input-group-btn">
                        <input class="btn btn-primary btn-sm" type="submit" name="search" id="search" value="Search">
                    </span>
                </div>
            </div>
        </form>
    </div>
    <div class="">
        <table id="grid_table" class="table table-striped table-hover table-condensed table-bordered tablesorter">
            <thead>
            	<tr>
                    <th>#</th>
                    <th>Year</th>
                    <th>Quarter</th>
                    <th>Code</th>
                    <th>Project</th>
                    <th>Beneficiaries</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th>Province</th>
                    <th>Encoded</th>
                    <th>Encoded By</th>
                    <th>Last Updated</th>
                    <th>Updated By</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
        	if ($rows) {
                while($row = mysqli_fetch_array($rows)) {
        			$action = '';
                    $ctr++;
                    $stamp = 'Encoded on '.zeroDateTime($row['date_encoded']).
                     ' by <span class="text-info">'.$row['encoder'].'</span><br>'.
                     'Last updated on '.zeroDateTime($row['last_updated']).
                     ' by <span class="text-danger">'.$row['updater'].'</span>';

?>                
                  <tr>
                    <td class="nowrap text-right"><?php echo $ctr; ?></td>
                    <td class="nowrap"><?php echo $row['prjmon_year']; ?></td>
                    <td class="nowrap"><?php echo $row['quarter_name']; ?></td>
                    <td class="nowrap"><?php echo $row['prj_code']; ?></td>
                    <td><?php echo $row['prj_title']; ?></td>
                    <td><?php echo $row['coop_names']; ?></td>
                    <td class="nowrap"><?php echo $row['prj_status_name']; ?></td>
                    <td><?php echo $row['prj_type_name']; ?></td>
                    <td ><?php echo $row['province_name']; ?></td>
                    <td class="nowrap"><?php echo zeroDateTime($row['date_encoded']); ?></td>
                    <td class="nowrap"><?php echo $row['encoder']; ?></td>
                    <td class="nowrap"><?php echo zeroDateTime($row['last_updated']); ?></td>
                    <td class="nowrap"><?php echo $row['updater']; ?></td>
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
    </div>

    <div class="panel-footer">
        <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th class="text-center">Total Number of Status Report Submitted</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center">
                    <?php echo $ctr; ?>
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
    <input type="hidden" id="q" name="q" value="<?php echo $q; ?>">
</form>

<script type="text/javascript">
var _tsOptions = {
    };
</script>
<?php
    page_footer();
    deleteFormCache();

function getMonitoringYearOptions($p_selected){
    $p_default = 'All';
    $p_label = 'prjmon_year';
    $p_value = 'prjmon_year';
    $sql = "SELECT DISTINCT(prjmon_year) FROM psi_project_monitoring ORDER BY prjmon_year DESC";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    $str = '';
    $found = false;
    
    if (!$res){
        return $str;
        die();
    }
    
    if (is_array($p_selected)){
        while ($row = mysqli_fetch_array($res)){
            $_label = $row[$p_label];
            if (in_array($row[$p_value], $p_selected)){
                $str .= '<option value="'.$row[$p_value].'" selected="selected">'.$_label.'</option>';
                $found = true;
            } else {
                $str .= '<option value="'.$row[$p_value].'">'.$_label.'</option>';
            }
        }
    } else {
        while ($row = mysqli_fetch_array($res)){
            $_label = $row[$p_label];
            if ($row[$p_value] == $p_selected){
                $str .= '<option value="'.$row[$p_value].'" selected="selected">'.$_label.'</option>';
                $found = true;
            } else {
                $str .= '<option value="'.$row[$p_value].'">'.$_label.'</option>';
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
?>
