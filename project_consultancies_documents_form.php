<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$did = requestInteger('did', 'location: '.WEBSITE_URL.'project_consultancies.php?pid='.$pid);
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_consultancies.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_consultancies.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Consultancies', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Consultancies', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

if (!dbValueExists('psi_consultancies', 'con_id', $did, false)){
    redirect(WEBSITE_URL.'project_consultancies.php?pid='.$pid);
    die();
}

loadDBValues("vwpsi_consultancies", "SELECT * FROM vwpsi_consultancies WHERE con_id = ".$did);
$sub_timestamp = '
                Service Provider : '.$GLOBALS['sp_name'].'<br>
                Category : '.$GLOBALS['con_type_name'].'<br>
                Consultancy Start : '.zeroDate($GLOBALS['con_start'].'').'<br>
                Consultancy End : '.zeroDate($GLOBALS['con_end'].'').'<br>
                Consultancy Encoded on '.zeroDateTime($GLOBALS['date_encoded']).' by '.$GLOBALS['encoder'].'<br>
                Consultancy Last updated on '.zeroDateTime($GLOBALS['last_updated']).' by '.$GLOBALS['updater'].'
                
';

   
$opstr = 'Add Document';
if ($op == 1){
    $opstr = 'Edit Document';
    loadDBValues("psi_consultancy_documents", "SELECT * FROM psi_consultancy_documents WHERE condoc_id = ".$id);
} else {
    initFormValues('psi_consultancy_documents');
}

loadFormCache('psi_consultancy_documents');

$page_title = 'Project Consultancy Documents ('.$opstr.')';
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
                <a class="btn btn-primary btn-sm" href="project_consultancies_view.php?did=<?php echo $did; ?>&amp;pid=<?php echo $pid; ?>" title="Project Consultancy Details"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="project_consultancies_documents_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;did=<?php echo $did; ?>&amp;pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form" role="form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="condoc_file" class="control-label">Document *</label><br>
            Current File : <a href="<?php echo CONSULTANCY_DOCS_LINK_PATH.$GLOBALS['condoc_file']; ?>" title="<?php echo $GLOBALS['condoc_filename']; ?>"><?php echo $GLOBALS['condoc_filename']; ?></a><br>
            <input class="form-control input-sm" placeholder="Document" name="condoc_file" id="condoc_file" type="file" accept="application/ms*,application/vnd.ms*">
        </div>
        <div class="form-group">
            <label for="condoc_remarks" class="control-label">Remarks</label>
            <textarea class="form-control input-sm" placeholder="Remarks" name="condoc_remarks" id="condoc_remarks" cols="50" rows="4"><?php echo $GLOBALS['condoc_remarks']; ?></textarea>
        </div>
        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="con_id" value="<?php echo $did; ?>">
        <input type="hidden" name="condoc_id" value="<?php echo $GLOBALS['condoc_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>