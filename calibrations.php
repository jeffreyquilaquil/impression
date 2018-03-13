<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Testings & Calibrations', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$qg_key = 'qg_calibrations';
$qy_key = 'qy_calibrations';

$qg = 0;
$qy = 0;

if (!postEmpty('search')){
    if ($_POST['search'] == 'Search'){
        if (!postEmpty('qg')){
            $_SESSION[$qg_key] = safeString($_POST['qg']);
        } else {
            $_SESSION[$qg_key] = -1;
        }

        if (!postEmpty('qy')){
            $_SESSION[$qy_key] = safeString($_POST['qy']);
        } else {
            $_SESSION[$qy_key] = 0;
        }

    } else if ($_POST['search'] == 'Show All'){
        $_SESSION[$q_key] = '';
        $_SESSION[$qg_key] = 0;
        $_SESSION[$qy_key] = 0;
    }
}

if (!sessionEmpty($qg_key)){
    $qg = intval($_SESSION[$qg_key]);
}

if (!sessionEmpty($qy_key)){
    $qy = intval($_SESSION[$qy_key]);
}

$sql = "SELECT * FROM vwpsi_calibrations";

$where = '';

if ($GLOBALS['ad_ug_is_admin'] == 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(region_id = $GLOBALS[ad_u_region_id])";
}

if (in_lab($GLOBALS['ad_ug_name'])){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= '(lab_id = '.$GLOBALS['ad_ug_id'].')';
}


if ($qg > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(lab_id = $qg)";
}

if ($qy > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(cal_year = $qy)";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY cal_year DESC, cal_month DESC';

$rows = mysqli_query($GLOBALS['cn'], $sql);
$sel_labs = getOptions('psi_usergroups', 'ug_name', 'ug_id', $qg, 'All', "WHERE ug_name LIKE '%Laboratory-%'");
$sel_year = getOptions('psi_calibrations', 'cal_year', 'cal_year', $qy , 'All', ' ORDER BY cal_year DESC', true);

$page_title = 'Testing &amp; Calibrations';
page_header($page_title, 2);

if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}

$ctr = 0;
$income_generated = 0;
$value_assistance = 0;
$income_gross = 0;

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left"><?php echo $page_title; ?></h3>
        <div class="pull-right">
            <?php
            if (can_access('Testings & Calibrations', 'add')){
            ?>
            <a class="btn btn-primary btn-sm" href="calibrations_form.php?op=0&amp;id=0" title="Add Testing/Calibration"><span class="fa fa-plus"></span> Add Testing/Calibration</a>
            <?php
            }
            ?>
        </div>
    </div>
    <?php
        if (!in_lab($GLOBALS['ad_ug_name'])){
    ?>
    <div class="panel-body">
        <form method="POST" action="calibrations.php" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Laboratory</span>
                    <select class="form-control input-sm" id="qg" name="qg">
                    <?php echo $sel_labs; ?>
                    </select>
                </div>
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Year</span>
                    <select class="form-control input-sm" id="qy" name="qy">
                    <?php echo $sel_year; ?>
                    </select>
                </div>

            </div>
            <input class="btn btn-primary btn-sm" type="submit" name="search" id="search" value="Search">
        </form>
    </div>
    <?php
        }
    ?>
    <div class="table-responsive">
        <table id="grid_table" class="table table-bordered table-striped table-hover table-condensed tablesorter">
            <thead>
            	<tr>
                    <th>&nbsp;</th>
                    <th>#</th>
                    <th>Laboratory</th>
                    <th class="text-center">Year</th>
                    <th class="text-center">Month</th>
                    <th class="nowrap text-center">No. of Services Rendered</th>
                    <th class="nowrap text-center">No. of Samples Tested / Calibrated</th>
                    <th class="nowrap text-center">No. of Customers Assisted</th>
                    <th class="nowrap text-center">No. of Firms Assisted</th>
                    <th class="nowrap text-center">Income Generated</th>
                    <th class="nowrap text-center">Value Of Assistance</th>
                    <th class="nowrap text-center">Gross Income</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
        	if ($rows) {

                while($row = mysqli_fetch_array($rows)) {
                    $ctr++;
        			$action				= '';
                    if (can_access('Testings & Calibrations', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'calibrations_view.php?pid='.$row['cal_id'].'" title="View Details"><span class="fa fa-folder-open"></span></a>  ';
                    }

                    if (can_access('Testings & Calibrations', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'calibrations_form.php?op=1&amp;id='.$row['cal_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }

                    if (can_access('Testings & Calibrations', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'calibrations_delete.php?op=2&amp;id='.$row['cal_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>';
                    }

                    $gross = $row['cal_income'] + $row['cal_value_service'];

                    $income_generated += $row['cal_income'];
                    $value_assistance += $row['cal_value_service'];
                    $income_gross += $gross;

?>                <tr>
                    <td class="nowrap text-right"><?php echo $action; ?></td>
                    <td class="nowrap text-center"><?php echo $ctr; ?></td>
                    <td class="nowrap"><abbr title="<?php echo $row['ug_name']; ?>"><?php echo $row['ug_name']; ?></abbr></td>
                    <td class="text-center"><?php echo $row['cal_year']; ?></td>
                    <td class="text-center"><?php echo numberToMonth($row['cal_month'].''); ?></td>
                    <td class="text-center"><?php echo zeroDash($row['cal_no_tests']); ?></td>
                    <td class="text-center"><?php echo zeroDash($row['cal_no_calibrations']); ?></td>
                    <td class="text-center"><?php echo zeroDash($row['cal_no_clients']); ?></td>
                    <td class="text-center"><?php echo zeroDash($row['cal_no_firms']); ?></td>
                    <td class="nowrap text-right"><?php echo zeroCurr0($row['cal_income']); ?></td>
                    <td class="nowrap text-right"><?php echo zeroCurr0($row['cal_value_service']); ?></td>
                    <td class="nowrap text-right"><?php echo zeroCurr0($gross); ?></td>
                  </tr><?php
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
                <th class="text-center">Total Number of Entries</th>
                <th class="text-center">Total Income Generated</th>
                <th class="text-center">Total Value Of Assistance</th>
                <th class="text-center">Total Gross Income</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center">
                    <?php echo $ctr; ?>
                </td>
                <td class="text-center">
                    <?php echo zeroCurr0($income_generated); ?>
                </td>
                <td class="text-center">
                    <?php echo zeroCurr0($value_assistance); ?>
                </td>
                <td class="text-center">
                    <?php echo zeroCurr0($income_gross); ?>
                </td>
            <tr>
            </tbody>
        </table>
        </div>
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
?>