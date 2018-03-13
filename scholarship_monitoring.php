<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Scholarship Monitoring', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$qyr_key = 'qyr_scholarship_monitoring';
$qyr = 0;

if (!postEmpty('search')){
    if ($_POST['search'] == 'Search'){
        if (!postEmpty('qyr')){
            $_SESSION[$qyr_key] = safeString($_POST['qyr']);
        } else {
            $_SESSION[$qyr_key] = -1;
        }

    } else if ($_POST['search'] == 'Show All'){
        $_SESSION[$qyr_key] = 0;
    }
}

if (!sessionEmpty($qyr_key)){
    $qyr = intval($_SESSION[$qyr_key]);
}

$sql = "SELECT * FROM psi_scholarship_monitoring";
$where = '';

if ($qyr > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(scholar_mon_year_from = $qyr)";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY scholar_mon_year_from DESC';

$rows = mysqli_query($GLOBALS['cn'], $sql);
$sel_years = getSchoolYearOptions($qyr);

page_header('Scholarship Monitoring');
if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Scholarship Monitoring</h3>
        <div class="pull-right">
            <?php 
            if (can_access('Scholarship Monitoring', 'add')){
            ?>
            <a class="btn btn-primary btn-sm" href="scholarship_monitoring_form.php?op=0&amp;id=0" title="Add Record"><span class="fa fa-plus"></span> Add Record</a>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="scholarship_monitoring.php" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">School Year</span>
                    <select class="form-control input-sm" id="qyr" name="qyr">
                    <?php echo $sel_years; ?>
                    </select>
                    <span class="input-group-btn">
                        <input class="btn btn-primary btn-sm" type="submit" name="search" id="search" value="Search">
                    </span>
                </div>
            </div>
        </form>
 </div>
    <div class="table-responsive">
        <table id="grid_table" class="table table-striped table-hover table-condensed tablesorter">
            <thead>
            	<tr>
                    <th class="text-center">School Year</th>
                    <th class="text-center"># of Examiniees</th>
                    <th class="text-center"># of Qualifiers</th>
                    <th class="text-center"># of On Going</th>
                    <th class="text-center"># of Graduates</th>
                    <th>&nbsp;</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
        	if ($rows) {
                while($row = mysqli_fetch_array($rows)) {
        			$action = '';

                    if (can_access('Scholarship Monitoring', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'scholarship_monitoring_form.php?op=1&amp;id='.$row['scholar_mon_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }
                    if (can_access('Scholarship Monitoring', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'scholarship_monitoring_delete.php?op=2&amp;id='.$row['scholar_mon_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>';
                    }

?>                <tr>
                    <td class="nowrap text-center"><?php echo $row['scholar_mon_year_from'].' - '.$row['scholar_mon_year_to']; ?></td>
                    <td class="nowrap text-center"><?php echo $row['scholar_mon_no_examinees']; ?></td>
                    <td class="nowrap text-center"><?php echo $row['scholar_mon_no_qualifiers']; ?></td>
                    <td class="nowrap text-center"><?php echo $row['scholar_mon_no_ongoing']; ?></td>
                    <td class="nowrap text-center"><?php echo $row['scholar_mon_no_graduates']; ?></td>
                    <td class="nowrap text-right"><?php echo $action; ?></td>
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
        5: { sorter: false}
        }
    };
</script>
<?php
    page_footer();
    deleteFormCache();

function getSchoolYearOptions($p_selected){
    $p_default = 'All';
    $p_label = 'scholar_mon_year_from';
    $p_value = 'scholar_mon_year_from';
    $sql = "SELECT * FROM psi_scholarship_monitoring ORDER BY scholar_mon_year_from DESC";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    $str = '';
    $found = false;
    
    if (!$res){
        return $str;
        die();
    }
    
    if (is_array($p_selected)){
        while ($row = mysqli_fetch_array($res)){
            $_label = $row[$p_label].' - '.($row[$p_label] + 1);
            if (in_array($row[$p_value], $p_selected)){
                $str .= '<option value="'.$row[$p_value].'" selected="selected">'.$_label.'</option>';
                $found = true;
            } else {
                $str .= '<option value="'.$row[$p_value].'">'.$_label.'</option>';
            }
        }
    } else {
        while ($row = mysqli_fetch_array($res)){
            $_label = $row[$p_label].' - '.($row[$p_label] + 1);
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