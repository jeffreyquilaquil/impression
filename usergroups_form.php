<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'usergroups.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'usergroups.php');

if ($op == 1){
    if (!can_access('UserGroups', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('UserGroups', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}


$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_usergroups", "SELECT * FROM psi_usergroups WHERE ug_id = ".$id);
    if (($GLOBALS['ug_is_admin'] == 1) && ($GLOBALS['ad_ug_is_admin'] != 1)) {
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    initFormValues('psi_usergroups');
}

loadFormCache('psi_usergroups');

page_header('User Groups ('.$opstr.')');

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">User Groups (<?php echo $opstr; ?>) </h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="usergroups.php" title="User Groups"><span class="fa fa-arrow-circle-left"></span> Back</a>        
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="usergroups_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group">
        <label for="ug_name" class="control-label">Group Name *</label>
       <span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Group Name" maxlength="255" required="required" name="ug_name" id="ug_name" type="text" value="<?php echo $GLOBALS['ug_name']; ?>">
        </div>

        <?php
        if ($GLOBALS['ug_is_admin'] == 1)  {
        ?>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="ug_is_admin" value="1" <?php echo checkbox_global('ug_is_admin'); ?> > Is Administrator Group
            </label>
        </div>
        <?php
        }
        $GLOBALS['row_count'] = 0;
        load_rights($id);
        echo show_checkboxes();

        ?>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="ug_id" value="<?php echo $GLOBALS['ug_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<script type="text/javascript">
    var _row_count = <?php echo $GLOBALS['row_count']; ?>;
</script>
<?php
    page_footer();

function load_rights($pid){
    if ($pid == 0) return;

    $sql = "SELECT * FROM psi_usergroup_rights WHERE ug_id = $pid";
    $res = mysqli_query($GLOBALS['cn'], $sql);
    if (!$res) return;
    while($row = mysqli_fetch_array($res)){
        $id = $row['ur_id'];
        if ($row['ugr_view'] == 1){
            $GLOBALS['ur'.$id.'_view'] = 1;
        }
        if ($row['ugr_add'] == 1){
            $GLOBALS['ur'.$id.'_add'] = 1;
        }
        if ($row['ugr_edit'] == 1){
            $GLOBALS['ur'.$id.'_edit'] = 1;
        }
        if ($row['ugr_delete'] == 1){
            $GLOBALS['ur'.$id.'_delete'] = 1;
        }
    }
    mysqli_free_result($res);
}

function show_checkboxes(){
    $sql = 'SELECT * FROM psi_user_rights ORDER BY ur_name ASC';
    $res = mysqli_query($GLOBALS['cn'], $sql);
    $s = '
        <h3><span class="label label-default full-width">Access Rights</span></h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed table-hover">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th class="text-center">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="chk_all_1" title="Check All">
                            </label>
                        </div>
                    </th>
                    <th class="text-center">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="chk_all_2" title="Check All">
                            </label>
                        </div>
                    </th>
                    <th class="text-center">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="chk_all_3" title="Check All">
                            </label>
                        </div>
                    </th>
                    <th class="text-center">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="chk_all_4" title="Check All">
                            </label>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
    ';  
    if (!$res) return '';
    while($row = mysqli_fetch_array($res)){
        $GLOBALS['row_count']++;
        $id = $row['ur_id'];

        $s .= '
            <tr>
                <td>
                    <div class="checkbox">
                        <label>
                        '.$row['ur_name'].'
                        </label>
                    </div>
                </td>
                <td class="text-center">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="chk1" name="ur'.$id.'_view" value="1" '.checkbox_global('ur'.$id.'_view').'> View
                        </label>
                    </div>
                </td>
                <td class="text-center">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="chk2" name="ur'.$id.'_add" value="1" '.checkbox_global('ur'.$id.'_add').'> Add
                        </label>
                   </div>
                </td>
                <td class="text-center">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="chk3" name="ur'.$id.'_edit" value="1" '.checkbox_global('ur'.$id.'_edit').'> Edit
                        </label>
                   </div>
                </td>
                <td class="text-center">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="chk4" name="ur'.$id.'_delete" value="1" '.checkbox_global('ur'.$id.'_delete').'> Delete
                        </label>
                    </div>
                </td>
            </tr>
        ';
    }
    $s .= '
            </tbody>
            </table>
        </div>
    ';
    mysqli_free_result($res);
    return $s;
}

function checkbox_global($fld){
    if (!isset($GLOBALS[$fld])) return '';
    return checkBox($GLOBALS[$fld]);
}

?>