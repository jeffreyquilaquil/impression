<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Collaborating Agencies', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$q_key = 'q_lu_collaborators';
$qg_key = 'qg_lu_collaborators';

$q = '';
$qg = 0;

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

$sql = "SELECT * FROM vwpsi_collaborators";
$where = '';

if (strlen($q) > 0){
    $where .= "((col_name like '%$q%') OR (col_abbr like '%$q%'))";
}

if ($qg > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(ot_id = $qg)";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY col_name ASC';

$rows = mysqli_query($GLOBALS['cn'], $sql);
$sel_category = getOptions('psi_organization_types', 'ot_name', 'ot_id', $qg, 'All', 'ORDER BY ot_name ASC');

$page_title = 'Collaborating Agencies';
page_header($page_title);
if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left"><?php echo $page_title; ?></h3>
        <div class="pull-right">
            <?php
            if (can_access('Collaborating Agencies', 'add')){
            ?>
            <a class="btn btn-primary btn-sm" href="lu_collaborators_form.php?op=0&amp;id=0" title="Add Agency"><span class="fa fa-plus"></span> Add Agency</a>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="lu_collaborators.php" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Category</span>
                    <select class="form-control input-sm" id="qg" name="qg">
                    <?php echo $sel_category; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <input class="form-control input-sm" placeholder="Agency ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
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
                    <th>Agency</th>
                    <th>Category</th>
                    <th>&nbsp;</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
        	if ($rows) {
                while($row = mysqli_fetch_array($rows)) {
        			$action = '';

                    if (can_access('Collaborating Agencies', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'lu_collaborators_form.php?op=1&amp;id='.$row['col_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }
                    if (can_access('Collaborating Agencies', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'lu_collaborators_delete.php?op=2&amp;id='.$row['col_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>  ';
                    }

?>                <tr>
                    <td class="nowrap"><abbr title="<?php echo $row['col_name']; ?>"><?php echo $row['col_abbr']; ?></abbr></td>
                    <td class="nowrap"><?php echo $row['ot_name']; ?></td>
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
        2: { sorter: false}
        }
    };
</script>
<?php
    page_footer();
    deleteFormCache();
?>