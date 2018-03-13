<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Scholarships', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$q_key = 'q_scholarships';
$qg_key = 'qg_scholarships';
$qc_key = 'qc_scholarships';

$q = '';
$qg = 0;
$qc = 0;

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
        if (!postEmpty('qc')){
            $_SESSION[$qc_key] = safeString($_POST['qc']);
        } else {
            $_SESSION[$qc_key] = -1;
        }

    } else if ($_POST['search'] == 'Show All'){
        $_SESSION[$q_key] = '';
        $_SESSION[$qg_key] = 0;
    }
}

if (!sessionEmpty($q_key)){
    $q = $_SESSION[$q_key];
}

if (!sessionEmpty($qg_key)){
    $qg = intval($_SESSION[$qg_key]);
}

if (!sessionEmpty($qc_key)){
    $qc = intval($_SESSION[$qc_key]);
}

$sql = "SELECT * FROM vwpsi_scholarships";
$where = '';

if (strlen($q) > 0){
    $where .= "((scholar_name like '%$q%') OR (scholar_fname like '%$q%') OR (scholar_mname like '%$q%') OR (scholar_lname like '%$q%'))";
}

if ($qg > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(scholar_prog_id = $qg)";
}

if ($qc > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(course_id = $qc)";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY scholar_name ASC';

$rows = mysqli_query($GLOBALS['cn'], $sql);
$sel_programs = getOptions('psi_scholarship_programs', 'scholar_prog_name', 'scholar_prog_id', $qg, 'All');
$sel_courses = getOptions('vwpsi_courses', 'course_label', 'course_id', $qc, 'All');

page_header('Scholarships');
if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Scholarships</h3>
        <div class="pull-right">
            <?php 
            if (can_access('Scholarships', 'add')){
            ?>
            <a class="btn btn-primary btn-sm" href="scholarships_form.php?op=0&amp;id=0" title="Add Scholarship"><span class="fa fa-plus"></span> Add Scholarship</a>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="scholarships.php" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Scholar Name...</span>
                    <select class="form-control input-sm" id="qg" name="qg">
                    <?php echo $sel_programs; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Course</span>
                    <select class="form-control input-sm" id="qc" name="qc">
                    <?php echo $sel_courses; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <input class="form-control input-sm" placeholder="Scholarships Title ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
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
                    <th class="text-center">Program</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Scholar</th>
                    <th class="text-center">Course</th>
                    <th class="text-center">Year Awarded</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Mobile</th>
                    <th class="text-center">Remarks</th>
                    <th>&nbsp;</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
        	if ($rows) {
                while($row = mysqli_fetch_array($rows)) {
        			$action = '';
                    if (can_access('Scholarships', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'scholarships_view.php?pid='.$row['scholar_id'].'" title="View Details"><span class="fa fa-folder-open"></span></a>  ';
                    }
                    if (can_access('Scholarships', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'scholarships_form.php?op=1&amp;id='.$row['scholar_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }
                    if (can_access('Scholarships', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'scholarships_delete.php?op=2&amp;id='.$row['scholar_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>';
                    }


                    $addr = nl2br($row['scholar_address'].'');
                    $remarks = nl2br($row['scholar_remarks'].'');

?>                <tr>
                    <td class="nowrap"><?php echo $row['scholar_prog_name']; ?></td>
                    <td class="nowrap"><?php echo $row['scholar_stat_name']; ?></td>
                    <td class="nowrap"><?php echo $row['scholar_name']; ?></td>
                    <td class="nowrap"><?php echo $row['course_label']; ?></td>
                    <td class="nowrap text-center"><?php echo $row['scholar_year_award']; ?></td>
                    <td class="nowrap"><?php echo $row['scholar_email']; ?></td>
                    <td class="nowrap"><?php echo $row['scholar_mobile']; ?></td>
                    <td><?php echo $remarks; ?></td>
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
        8: { sorter: false}
        }
    };
</script>
<?php
    page_footer();
    deleteFormCache();

    function get_beneficiaries($id){
        $sql = "SELECT * FROM vwpsi_project_beneficiaries WHERE scholar_id = $id";
        $res = mysqli_query($GLOBALS['cn'], $sql);

        if (!$res) return '';
        $s = '';
        while ($row = mysqli_fetch_array($res)){
            if (strlen($s) > 0){
                $s .= '<br>';
            }
            $s .= $row['coop_name'];
        }
        mysqli_free_result($res);
        return $s;
    }

    function get_collaborators($id){
        $sql = "SELECT * FROM vwpsi_project_collaborators WHERE scholar_id = $id";
        $res = mysqli_query($GLOBALS['cn'], $sql);

        if (!$res) return '';
        $s = '';
        while ($row = mysqli_fetch_array($res)){
            if (strlen($s) > 0){
                $s .= '<br>';
            }
            $s .= '<abbr title="'.$row['col_name'].'">'.$row['col_abbr'].'</abbr>';
        }
        mysqli_free_result($res);
        return $s;
    }
?>