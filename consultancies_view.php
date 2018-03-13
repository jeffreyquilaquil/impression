<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Consultancies', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'consultancies.php');

if (!dbValueExists('psi_consultancies', 'con_id', $pid, false)){
    redirect(WEBSITE_URL.'consultancies.php');
    die();
}

loadDBValues("vwpsi_consultancies", "SELECT * FROM vwpsi_consultancies WHERE con_id = ".$pid);
$timestamp = '
                Cooperator : '.$GLOBALS['coop_name'].'<br>
                Service Provider : '.$GLOBALS['sp_name'].'<br>
                Category : '.$GLOBALS['con_type_name'].'<br>
                Consultancy Start : '.zeroDate($GLOBALS['con_start'].'').'<br>
                Consultancy End : '.zeroDate($GLOBALS['con_end'].'').'<br>
                Encoded on '.zeroDateTime($GLOBALS['date_encoded']).' by '.$GLOBALS['encoder'].'<br>
                Last updated on '.zeroDateTime($GLOBALS['last_updated']).' by '.$GLOBALS['updater'].'
';

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

$sql = "SELECT * FROM psi_consultancy_documents";
$where = "(con_id = $pid)";

if (strlen($q) > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(condoc_filename like '%$q%')";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY date_encoded DESC';

$rows = mysqli_query($GLOBALS['cn'], $sql);

$page_title = 'Consultancy Details';
page_header($page_title, 2);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title"><?php echo $page_title; ?></h3>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="consultancies.php" title="Consultancies"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <?php echo $timestamp; ?>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php
if (can_access('Consultancy Documents', 'view')){
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title">Documents</h3>
        </div>
        <div class="pull-right">
            <?php
            if (can_access('Consultancy Documents', 'add')){
            ?>
            <a class="btn btn-primary btn-sm" href="consultancies_documents_form.php?op=0&amp;id=0&amp;pid=<?php echo $pid; ?>" title="Consultancy Documents"><span class="fa fa-plus"></span> Add Document</a>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="panel-body">
        <?php
        if (strlen($GLOBALS['errmsg']) > 0){
            ?>
                <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
            <?php 
        }
        ?>

        <form method="POST" action="consultancies_view.php?pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form-inline" role="form">
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

                    $link = CONSULTANCY_DOCS_LINK_PATH.$row['condoc_file'];


                    $action = '';

                    if (can_access('Consultancy Documents', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.$link.'" title="Open Document"><span class="fa fa-folder-open"></span></a>  ';
                    }

                    if (can_access('Consultancy Documents', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'consultancies_documents_form.php?op=1&amp;pid='.$pid.'&amp;id='.$row['condoc_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }

                    if (can_access('Consultancy Documents', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'consultancies_documents_delete.php?op=2&amp;pid='.$pid.'&amp;id='.$row['condoc_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>';
                    }

                    $markit = '';
                    if (!file_exists(CONSULTANCY_DOCS_PATH.DIRECTORY_SEPARATOR.$row['condoc_file'])){
                        $markit = '<span title="File may be corrupt. Please upload again." class="fa fa-exclamation-triangle markit"> </span> ';
                    }

                    

                    $remarks = nl2br($row['condoc_remarks'].'');
?>                <tr>
                    <td class="nowrap"><?php echo $markit; ?><a target="_blank" href="<?php echo $link; ?>" title="<?php echo $row['condoc_filename']; ?>"><?php echo $row['condoc_filename']; ?></a></td>
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
<?php
}
?>

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