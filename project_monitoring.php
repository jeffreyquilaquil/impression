<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Project Monitoring', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$qyr_key = 'qyr_project_monitoring';
$qqr_key = 'qqr_project_monitoring';
$qyr = 0;
$qqr = 0;

if (!postEmpty('search')){
    if ($_POST['search'] == 'Search'){
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
        $_SESSION[$qyr_key] = 0;
        $_SESSION[$qqr_key] = 0;
    }
}

if (!sessionEmpty($qyr_key)){
    $qyr = intval($_SESSION[$qyr_key]);
}

if (!sessionEmpty($qqr_key)){
    $qqr = intval($_SESSION[$qqr_key]);
}

$sql = "SELECT * FROM vwpsi_project_monitoring";
$where = "((prj_id = $pid) AND (prjform_id != 1))";

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

$sql .= ' ORDER BY prjmon_year DESC, quarter_id DESC';

//echo $sql;

$rows = mysqli_query($GLOBALS['cn'], $sql);

$sel_years = getMonitoringYearOptions($qyr);
$sel_quarters = getOptions('psi_quarters', 'quarter_name', 'quarter_id', $qqr, 'All');

$page_title = 'Project Monitoring';
page_header($page_title, 1);
if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="pull-right">
                <?php
                if (can_access('Project Monitoring', 'add')){
                ?>
                <a class="btn btn-primary btn-sm" href="project_monitoring_form.php?frm=2&amp;op=0&amp;id=0&amp;pid=<?php echo $pid; ?>" title="Add Status Report"><span class="fa fa-plus"></span> Add Status Report</a>
                <a class="btn btn-primary btn-sm" href="project_monitoring_form.php?frm=3&amp;op=0&amp;id=0&amp;pid=<?php echo $pid; ?>" title="Add Terminal Report"><span class="fa fa-plus"></span> Add Terminal Report</a>
                <?php
                }
                ?>

            </div>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="project_monitoring.php?pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Year</span>
                    <select class="form-control input-sm" id="qyr" name="qyr">
                    <?php echo $sel_years; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Quarter</span>
                    <select class="form-control input-sm" id="qqr" name="qqr">
                    <?php echo $sel_quarters; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <input class="btn btn-primary btn-sm" type="submit" name="search" id="search" value="Search">
 
            </div>
        </form>
 </div>
    <div class="table-responsive">
        <table id="grid_table" class="table table-striped table-hover table-condensed tablesorter">
            <thead>
            	<tr>
                    <th>&nbsp;</th>
                    <th class="text-center">Form</th>
                    <th class="text-center">Year</th>
                    <th class="text-center">Quarter</th>
                    <th class="text-center">Expected Output</th>
                    <th class="text-center">Accomplishment</th>
                    <th class="text-center">Remarks/Justification</th>
                    <th class="text-center">--</th>
                </tr>
            </thead> 
            <tbody>
            <?php
        	if ($rows) {
                while($row = mysqli_fetch_array($rows)) {
        			$action = '';

                    if (can_access('Project Monitoring', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'project_monitoring_form.php?op=1&amp;id='.$row['prjmon_id'].'&amp;pid='.$pid.'&amp;frm='.$row['prjform_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }
                    if (can_access('Project Monitoring', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'project_monitoring_delete.php?op=2&amp;id='.$row['prjmon_id'].'&amp;pid='.$pid.'\');" title="Delete"><span class="fa fa-close"></span></a>';
                    }

                    
                    $expected = nl2br($row['prjmon_expected_output'].'');
                    $actual = nl2br($row['prjmon_actual_accomplishment'].'');
                    $remarks = nl2br($row['prjmon_output_remarks'].'');
                    $stamp = 'Encoded on '.zeroDateTime($row['date_encoded']).' by '.$row['encoder'].'<br>
                     Last updated on '.zeroDateTime($row['last_updated']).' by '.$row['updater'];
?>                <tr>
                    <td class="nowrap text-right"><?php echo $action; ?></td>
                    <td class="nowrap text-center"><?php echo $row['prjform_name']; ?></td>
                    <td class="nowrap text-center"><?php echo $row['prjmon_year']; ?></td>
                    <td class="nowrap text-center"><?php echo $row['quarter_name']; ?></td>
                    <td><?php echo $expected; ?></td>
                    <td><?php echo $actual; ?></td>
                    <td><?php echo $remarks; ?></td>
                    <td><?php echo $stamp; ?></td>
                  </tr><?php
        		}
                mysqli_free_result($rows);
        	}
?>         
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <div id="grid_pager">
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