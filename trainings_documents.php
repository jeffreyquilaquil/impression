<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Training Documents', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'trainings.php');

if (!dbValueExists('psi_trainings', 'tr_id', $pid, false)){
    redirect(WEBSITE_URL.'trainings.php');
    die();
}

loadDBValues("vwpsi_trainings", "SELECT * FROM vwpsi_trainings WHERE tr_id = ".$pid);
$timestamp = '
                Encoded on '.zeroDateTime($GLOBALS['date_encoded']).' by '.$GLOBALS['encoder'].'
                <br>
                Last updated on '.zeroDateTime($GLOBALS['last_updated']).' by '.$GLOBALS['updater'];

$q_key = 'q_training_files';
$qg_key = 'qg_training_files';

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

$sql = "SELECT * FROM psi_training_documents";
$where = "(tr_id = $pid)";

if (strlen($q) > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(trdoc_filename like '%$q%')";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY date_encoded DESC';

$rows = mysqli_query($GLOBALS['cn'], $sql);

$page_title = 'Training Documents';
page_header($page_title, 2);

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
                <div>
                    <h3 class="detail-name text-primary">
                        <?php echo $GLOBALS['tr_title']; ?>
                    </h3>
                </div>
            </div>
            <div class="pull-right">
                <?php
                if (can_access('Training Documents', 'add')){
                ?>
                <a class="btn btn-primary btn-sm" href="trainings_documents_form.php?op=0&amp;id=0&amp;pid=<?php echo $pid; ?>" title="Add Document"><span class="fa fa-plus"></span> Add Document</a>
                <?php
                }
                ?>
                <a class="btn btn-primary btn-sm" href="trainings_view.php?pid=<?php echo $pid; ?>" title="Training Details"><span class="fa fa-folder-open"></span> Training Details</a>
                <a class="btn btn-primary btn-sm" href="trainings.php" title="Trainings"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
        <div class="pull-left">
            <small>
                <?php echo $timestamp; ?>
            </small>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="trainings_documents.php?pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form-inline" role="form">
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
        <table id="grid_table" class="table table-striped table-hover table-condensed tablesorter">
            <thead>
                <tr>
                    <th>Document</th>
                    <th class="text-center">Remarks</th>
                    <th class="text-center">Date Uploaded</th>
                    <th>&nbsp;</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
            if ($rows) {
                while($row = mysqli_fetch_array($rows)) {

                    $link = TRAINING_DOCS_LINK_PATH.$row['trdoc_file'];

                    $action = '';

                    if (can_access('Training Documents', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.$link.'" title="Open Document"><span class="fa fa-folder-open"></span></a>  ';
                    }

                    if (can_access('Training Documents', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'trainings_documents_form.php?op=1&amp;pid='.$pid.'&amp;id='.$row['trdoc_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }

                    if (can_access('Training Documents', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'trainings_documents_delete.php?op=2&amp;pid='.$pid.'&amp;id='.$row['trdoc_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>';
                    }


                    $remarks = nl2br($row['trdoc_remarks'].'');
?>                <tr>
                    <td class="nowrap"><a target="_blank" href="<?php echo $link; ?>" title="<?php echo $row['trdoc_filename']; ?>"><?php echo $row['trdoc_filename']; ?></a></td>
                    <td><?php echo $remarks; ?></td>
                    <td class="nowrap text-center"><?php echo zeroDateTime($row['date_encoded'].''); ?></td>
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
?>