<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('UserGroups', 'view')){
    redirect(WEBSITE_URL.'index.php');
}


$q_key = 'q_usergroups';

$q = '';

if (!postEmpty('search')){
    if ($_POST['search'] == 'Search'){
        if (!postEmpty('q')){
            $_SESSION[$q_key] = safeString($_POST['q']);
        } else {
            $_SESSION[$q_key] = '';
        }

    } else if ($_POST['search'] == 'Show All'){
        $_SESSION[$q_key] = '';
    }
}

if (!sessionEmpty($q_key)){
    $q = $_SESSION[$q_key];
}

$sql = "SELECT * FROM psi_usergroups";
$where = '';

if (strlen($q) > 0){
    $where .= "(ug_name like '%$q%')";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY ug_name ASC';

$rows = mysqli_query($GLOBALS['cn'], $sql);

page_header('User Groups');
if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">User Groups</h3>
        <div class="pull-right">
            <?php
            if (can_access('UserGroups', 'add')){
            ?>
            <a class="btn btn-primary btn-sm" href="usergroups_form.php?op=0&amp;id=0" title="Add Group"><span class="fa fa-plus"></span> Add Group</a>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="usergroups.php" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <input class="form-control input-sm" placeholder="User group ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
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
                    <th width="100%">User Group</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
        	if ($rows) {
                $ctr = 0;
                while($row = mysqli_fetch_array($rows)) {

                    if (($row['ug_is_admin'] == 1) && ($GLOBALS['ad_ug_is_admin'] != 1)) continue;
                    
        			$action = '';

                    if (can_access('UserGroups', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'usergroups_form.php?op=1&amp;id='.$row['ug_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }

                    if (can_access('UserGroups', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'usergroups_delete.php?op=2&amp;id='.$row['ug_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>  ';
                    }

                    $ctr++;

?>                <tr>
                    <td class="nowrap text-left hidden-print"><?php echo $action; ?></td>
                    <td class="nowrap text-left"><?php echo $ctr; ?></td>
                    <td class="nowrap"><?php echo $row['ug_name']; ?></td>
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
?>