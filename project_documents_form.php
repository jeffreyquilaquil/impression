<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_documents.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_documents.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Documentation', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Documentation', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_project_documents", "SELECT * FROM psi_project_documents WHERE doc_id = ".$id);
} else {
    initFormValues('psi_project_documents');
}

loadFormCache('psi_project_documents');

$sel_doctype = getOptions('psi_project_document_types', 'doctype_name', 'doctype_id', $GLOBALS['doctype_id']);

$page_title = 'Project Documentation ('.$opstr.')';
page_header($page_title, 1);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="project_documents.php?pid=<?php echo $pid; ?>" title="Project Documents"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="project_documents_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form" role="form" enctype="multipart/form-data">

        <div class="form-group form-group-sm">
        <label for="doctype_id" class="control-label">Document Type *</label>
        <select class="form-control input-sm" id="doctype_id" name="doctype_id">
        <?php echo $sel_doctype; ?>
        </select>
        </div>

        <div class="form-group">
            <label for="doc_file" class="control-label">Document *</label><br>
            Current File : <a href="<?php echo PROJECT_DOCS_LINK_PATH.$GLOBALS['doc_file']; ?>" title="<?php echo $GLOBALS['doc_filename']; ?>"><?php echo $GLOBALS['doc_filename']; ?></a><br>
            <input class="form-control input-sm" placeholder="Document" name="doc_file" id="doc_file" type="file" accept="application/ms*,application/vnd.ms*">
        </div>

        <div class="form-group">
        <label for="doc_remarks" class="control-label">Remarks</label>
        <textarea class="form-control input-sm" placeholder="Remarks" name="doc_remarks" id="doc_remarks" cols="50" rows="4"><?php echo $GLOBALS['doc_remarks']; ?></textarea>
        </div>
        
        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="prj_id" value="<?php echo $pid; ?>">
        <input type="hidden" name="doc_id" value="<?php echo $GLOBALS['doc_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>