<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Project Photos', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$q_key = 'q_project_albums';
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

$sql = "SELECT * FROM psi_project_albums";
$where = "(prj_id = $pid)";

if (strlen($q) > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "((album_name LIKE '%$q%') OR (album_desc LIKE '%$q%'))";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY date_encoded DESC';

$rows = mysqli_query($GLOBALS['cn'], $sql);

$page_title = 'Project Photos';
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
                if (can_access('Project Photos', 'add')){
                ?>
                <a class="btn btn-primary btn-sm" href="project_gallery_form.php?op=0&amp;id=0&amp;pid=<?php echo $pid; ?>" title="Add Album"><span class="fa fa-plus"></span> Add Album</a>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="project_gallery.php?pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <input class="form-control input-sm" placeholder="Filename ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
                    <span class="input-group-btn">
                        <input class="btn btn-primary btn-sm" type="submit" name="search" id="search" value="Search">
                    </span>
                </div>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table id="grid_table" class="table table-bordered table-striped table-hover table-condensed tablesorter">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th class="text-center">#</th>
                    <th class="nowrap">Album Name</th>
                    <th>Description</th>
                    <th class="text-center nowrap">Date Uploaded</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
            if ($rows) {
                $ctr = 0;
                while($row = mysqli_fetch_array($rows)) {

                    $link = WEBSITE_URL.'project_gallery_view.php?op=1&amp;pid='.$pid.'&amp;id='.$row['album_id'];
                    $action = '';
                    $ctr++;

                    if (can_access('Project Photos', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.$link.'" title="View Album"><span class="fa fa-folder-open"></span></a> ';
                    }
                    if (can_access('Project Photos', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'project_gallery_form.php?op=1&amp;pid='.$pid.'&amp;id='.$row['album_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }
                    if (can_access('Project Photos', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'project_gallery_delete.php?op=2&amp;pid='.$pid.'&amp;id='.$row['album_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>';
                    }

                    $desc = nl2br($row['album_desc'].'');

                    $markit = '';
                    if (!check_album_intact($row['album_id'])){
                        $markit = '<span title="Some files may be corrupt. Please upload again." class="fa fa-exclamation-triangle markit"> </span> ';
                    }
?>                <tr>
                    <td class="nowrap text-left"><?php echo $action; ?></td>
                    <td class="nowrap text-center"><?php echo $ctr; ?></td>
                    <td class="nowrap"><?php echo $markit; ?><a target="_blank" href="<?php echo $link; ?>" title="View Album"><?php echo $row['album_name']; ?></a></td>
                    <td><?php echo $desc; ?></td>
                    <td class="nowrap text-center"><?php echo zeroDateTime($row['date_encoded'].''); ?></td>
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

    function check_album_intact($id){
        $sql = "SELECT * FROM psi_project_album_photos WHERE album_id = $id";

        $rows = mysqli_query($GLOBALS['cn'], $sql);
        if (!$rows){
            return false;
            die();
        }
        $res = true;
        while ($row = mysqli_fetch_array($rows)){
            //echo GALLERY_PATH.DIRECTORY_SEPARATOR.$row['photo_file'].'<br>';
            if (!file_exists(GALLERY_PATH.DIRECTORY_SEPARATOR.$row['photo_file'])){
                $res = false;
                break;
            }
        }
        mysqli_free_result($rows);
        return $res;
    }
?>