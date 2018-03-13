<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Project Consultancy Documents', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$did = requestInteger('did', 'location: '.WEBSITE_URL.'project_consultancies.php?pid='.$pid);

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

if (!dbValueExists('psi_consultancies', 'con_id', $did, false)){
    redirect(WEBSITE_URL.'project_consultancies.php?pid='.$pid);
    die();
}

loadDBValues("vwpsi_projects", "SELECT * FROM vwpsi_projects WHERE prj_id = $pid");
$timestamp = '
                Project Beneficiaries : '.getBeneficiaries($pid).'
                <br>
                Project Encoded on '.zeroDateTime($GLOBALS['date_encoded']).' by '.$GLOBALS['encoder'].'
                <br>
                Project Last updated on '.zeroDateTime($GLOBALS['last_updated']).' by '.$GLOBALS['updater'];


loadDBValues("vwpsi_consultancies", "SELECT * FROM vwpsi_consultancies WHERE con_id = ".$did);
$sub_timestamp = '
                Service Provider : '.$GLOBALS['sp_name'].'<br>
                Category : '.$GLOBALS['con_type_name'].'<br>
                Consultancy Start : '.zeroDate($GLOBALS['con_start'].'').'<br>
                Consultancy End : '.zeroDate($GLOBALS['con_end'].'').'<br>
                Consultancy Encoded on '.zeroDateTime($GLOBALS['date_encoded']).' by '.$GLOBALS['encoder'].'<br>
                Consultancy Last updated on '.zeroDateTime($GLOBALS['last_updated']).' by '.$GLOBALS['updater'].'
                
';

$q_key = 'q_consultancies_documents';
$qg_key = 'qg_consultancies_documents';

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
$where = "(con_id = $did)";

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

$page_title = 'Project Consultancy Documents';
page_header($page_title, 1);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
                <?php echo $sub_timestamp; ?>
            </div>
            <div class="pull-right">

                <?php
                if (can_access('Project Consultancy Documents', 'view')){
                ?>
                    <a class="btn btn-primary btn-sm" href="project_consultancies_documents_form.php?op=0&amp;id=0&amp;did=<?php echo $did; ?>&amp;pid=<?php echo $pid; ?>" title="Add Document"><span class="fa fa-plus"></span> Add Document</a>
                <?php
                }
                ?>
                <a class="btn btn-primary btn-sm" href="project_consultancies.php?pid=<?php echo $pid; ?>" title="Project Consultancies"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
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
        <table id="grid_table" class="table table-borderd table-striped table-hover table-condensed tablesorter">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>#</th>
                    <th>Document</th>
                    <th class="text-center">Remarks</th>
                    <th class="text-center">Date Uploaded</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
            $ctr = 0;
            if ($rows) {
                while($row = mysqli_fetch_array($rows)) {
                    $ctr++;

                    $link = CONSULTANCY_DOCS_LINK_PATH.'/'.$row['condoc_file'];

                    $action = '';


                    if (can_access('Project Consultancy Documents', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.$link.'" title="Open Document"><span class="fa fa-folder-open"></span></a>  ';
                    }

                    if (can_access('Project Consultancy Documents', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'project_consultancies_documents_form.php?op=1&amp;id='.$row['condoc_id'].'&amp;did='.$did.'&amp;pid='.$pid.'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }

                    if (can_access('Project Consultancy Documents', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'project_consultancies_documents_delete.php?op=2&amp;id='.$row['condoc_id'].'&amp;did='.$did.'&amp;pid='.$pid.'\');" title="Delete"><span class="fa fa-close"></span></a>';
                    }

                    $remarks = nl2br($row['condoc_remarks'].'');

                    $markit = '';
                    if (!file_exists(CONSULTANCY_DOCS_PATH.DIRECTORY_SEPARATOR.$row['condoc_file'])){
                        $markit = '<span title="File may be corrupt. Please upload again." class="fa fa-exclamation-triangle markit"> </span> ';
                    }

?>
                <tr>
                    <td class="nowrap text-left"><?php echo $action; ?></td>
                    <td class="nowrap text-center"><?php echo $ctr; ?></td>
                    <td class="nowrap"><?php echo $markit; ?><a target="_blank" href="<?php echo $link; ?>" title="<?php echo $row['condoc_filename']; ?>"><?php echo $row['condoc_filename']; ?></a></td>
                    <td><?php echo $remarks; ?></td>
                    <td class="nowrap text-center"><?php echo zeroDateTime($row['date_encoded'].''); ?></td>
                  </tr>
<?php
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