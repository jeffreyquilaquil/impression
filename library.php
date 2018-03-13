<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Library Monitoring', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$qyr_key = 'qyr_library';
$qmo_key = 'qmo_library';
$qyr = 0;
$qmo = 0;

if (!postEmpty('search')){
    if ($_POST['search'] == 'Search'){
        if (!postEmpty('qyr')){
            $_SESSION[$qyr_key] = safeString($_POST['qyr']);
        } else {
            $_SESSION[$qyr_key] = -1;
        }

        if (!postEmpty('qmo')){
            $_SESSION[$qmo_key] = safeString($_POST['qmo']);
        } else {
            $_SESSION[$qmo_key] = -1;
        }

    } else if ($_POST['search'] == 'Show All'){
        $_SESSION[$qyr_key] = 0;
        $_SESSION[$qmo_key] = 0;
    }
}

if (!sessionEmpty($qyr_key)){
    $qyr = intval($_SESSION[$qyr_key]);
}

if (!sessionEmpty($qmo_key)){
    $qmo = intval($_SESSION[$qmo_key]);
}

$sql = "SELECT * FROM psi_library";
$where = '';

if ($qyr > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(lib_year = $qyr)";
}

if ($qmo > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(lib_month = $qmo)";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY lib_year DESC, lib_month DESC';

$rows = mysqli_query($GLOBALS['cn'], $sql);
$sel_years = getLibraryYearOptions($qyr);
$sel_month = getMonthOptions($qmo, 'All');

page_header('Library Monitoring');
if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Library Monitoring</h3>
        <div class="pull-right">
            <?php
            if (can_access('Library Monitoring', 'add')){
            ?>
            <a class="btn btn-primary btn-sm" href="library_form.php?op=0&amp;id=0" title="Add Record"><span class="fa fa-plus"></span> Add Record</a>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="library.php" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Year</span>
                    <select class="form-control input-sm" id="qyr" name="qyr">
                    <?php echo $sel_years; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Month</span>
                    <select class="form-control input-sm" id="qmo" name="qmo">
                    <?php echo $sel_month; ?>
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
                    <th class="text-center">Year</th>
                    <th class="text-center">Month</th>
                    <th class="text-center">No. of Users</th>
                    <th>&nbsp;</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
        	if ($rows) {
                while($row = mysqli_fetch_array($rows)) {
        			$action = '';

                    if (can_access('Library Monitoring', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'library_form.php?op=1&amp;id='.$row['lib_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }

                    if (can_access('Library Monitoring', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'library_delete.php?op=2&amp;id='.$row['lib_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>';
                    }

?>                <tr>
                    <td class="nowrap text-center"><?php echo $row['lib_year']; ?></td>
                    <td class="nowrap text-center"><?php echo getMonthName($row['lib_month']); ?></td>
                    <td class="nowrap text-center"><?php echo $row['lib_user_count']; ?></td>
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
        3: { sorter: false}
        }
    };
</script>
<?php
    page_footer();
    deleteFormCache();

function getLibraryYearOptions($p_selected){
    $p_default = 'All';
    $p_label = 'lib_year';
    $p_value = 'lib_year';
    $sql = "SELECT DISTINCT(lib_year) FROM psi_library ORDER BY lib_year DESC";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    $str = '';
    $found = false;
    
    if (!$res){
        return $str;
        die();
    }
    
    if (is_array($p_selected)){
        while ($row = mysqli_fetch_array($res)){
            if (in_array($row[$p_value], $p_selected)){
                $str .= '<option value="'.$row[$p_value].'" selected="selected">'.$row[$p_label].'</option>';
                $found = true;
            } else {
                $str .= '<option value="'.$row[$p_value].'">'.$row[$p_label].'</option>';
            }
        }
    } else {
        while ($row = mysqli_fetch_array($res)){
            if ($row[$p_value] == $p_selected){
                $str .= '<option value="'.$row[$p_value].'" selected="selected">'.$row[$p_label].'</option>';
                $found = true;
            } else {
                $str .= '<option value="'.$row[$p_value].'">'.$row[$p_label].'</option>';
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