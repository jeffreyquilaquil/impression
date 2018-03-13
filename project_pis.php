<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Project PIS', 'view')){
    redirect(WEBSITE_URL.'index.php');
}


$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$qyr_key = 'qyr_project_pis';
$qqr_key = 'qqr_project_pis';
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

$sql = "SELECT * FROM vwpsi_project_pis";
$where = "(prj_id = $pid)";

if ($qyr > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(prjpis_year = $qyr)";
}

if ($qqr > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(sem_id= $qqr)";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY prjpis_year DESC, sem_id DESC';

$rows = mysqli_query($GLOBALS['cn'], $sql);

$sel_years = getPISYearOptions($qyr);
$sel_semesters = getOptions('psi_semesters', 'sem_name', 'sem_id', $qqr, 'All');

$page_title = 'Project PIS';
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
                if (can_access('Project PIS', 'add')){
                ?>
                <a class="btn btn-primary btn-sm" href="project_pis_form.php?frm=1&amp;op=0&amp;id=0&amp;pid=<?php echo $pid; ?>" title="Add Record"><span class="fa fa-plus"></span> Add Record</a>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="project_pis.php?pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form-inline" role="form">
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
                    <span class="input-group-addon">Semesters</span>
                    <select class="form-control input-sm" id="qqr" name="qqr">
                    <?php echo $sel_semesters; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <input class="btn btn-primary btn-sm" type="submit" name="search" id="search" value="Search">
 
            </div>
        </form>
 </div>
    <div class="table-responsive">
        <table id="grid_table" class="table table-bordered table-striped table-hover table-condensed tablesorter">
            <thead>
            	<tr>
                    <th class="text-center">&nbsp;</th>
                    <th class="text-center">#</th>
                    <th class="text-center">Year</th>
                    <th class="text-center">Semester</th>
                    <th class="text-center">Volume of Production Local</th>
                    <th class="text-center">Volume of Production Export</th>
                    <th class="text-center">Gross Sales Local</th>
                    <th class="text-center">Gross Sales Export</th>
                    <th class="text-center">Remarks</th>
                </tr>

            </thead> 
            <tbody>
            <?php
            $ctr = 0;
        	if ($rows) {
                while($row = mysqli_fetch_array($rows)) {
        			$action = '';
                    $ctr++;

                    if (can_access('Project PIS', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'project_pis_form.php?op=1&amp;id='.$row['prjpis_id'].'&amp;pid='.$pid.'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }
                    if (can_access('Project PIS', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'project_pis_delete.php?op=2&amp;id='.$row['prjpis_id'].'&amp;pid='.$pid.'\');" title="Delete"><span class="fa fa-close"></span></a>';
                    }

                    
                    $remarks = nl2br($row['prjpis_remarks'].'');
?>                <tr>
                    <td class="nowrap text-left"><?php echo $action; ?></td>
                    <td class="nowrap text-center"><?php echo $ctr; ?></td>
                    <td class="nowrap text-center"><?php echo $row['prjpis_year']; ?></td>
                    <td class="nowrap text-center"><?php echo $row['sem_name']; ?></td>
                    <td class="nowrap text-right"><?php echo zeroCurr($row['prjpis_volume_production_local']); ?></td>
                    <td class="nowrap text-right"><?php echo zeroCurr($row['prjpis_volume_production_export']); ?></td>
                    <td class="nowrap text-right"><?php echo zeroCurr($row['prjpis_gross_sales_local']); ?></td>
                    <td class="nowrap text-right"><?php echo zeroCurr($row['prjpis_gross_sales_export']); ?></td>
                    <td ><?php echo $remarks; ?></td>
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

function getPISYearOptions($p_selected){
    $p_default = 'All';
    $p_label = 'prjpis_year';
    $p_value = 'prjpis_year';
    $sql = "SELECT DISTINCT(prjpis_year) FROM psi_project_pis ORDER BY prjpis_year DESC";
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