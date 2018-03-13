<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'project.php');
$did = requestInteger('did', 'location: '.WEBSITE_URL.'project_trainings.php?pid='.$pid);
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_trainings_documents.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_trainings_documents.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Fora Documents', 'edit')){
        redirect(WEBSITE_URL.'index.php?1');
    }
} else {
    if (!can_access('Project Fora Documents', 'add')){
        redirect(WEBSITE_URL.'index.php?2');
    }
}

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php?3');
    die();
}

if (!dbValueExists('psi_fora', 'fr_id', $did, false)){
    redirect(WEBSITE_URL.'project_trainings.php?pid='.$pid);
    die();
}

loadDBValues("vwpsi_fora", "SELECT * FROM vwpsi_fora WHERE fr_id = ".$did);
$timestamp = '
                Encoded on '.zeroDateTime($GLOBALS['date_encoded']).' by '.$GLOBALS['encoder'].'<br>
                Last updated on '.zeroDateTime($GLOBALS['last_updated']).' by '.$GLOBALS['updater'].'
            ';
                
$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_fora_documents", "SELECT * FROM psi_fora_documents WHERE frdoc_id = ".$id);
} else {
    initFormValues('psi_fora_documents');
}

loadFormCache('psi_fora_documents');

$page_title = 'Project Fora/Trainings/Seminars Documents ('.$opstr.')';
page_header($page_title, 1);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
                <div>
                    <h3 class="detail-name text-primary">
                        <?php echo $GLOBALS['fr_title']; ?>
                    </h3>
                </div>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="project_trainings_view.php?did=<?php echo $did; ?>&amp;pid=<?php echo $pid; ?>" title="Project Fora Details"><span class="fa fa-folder-open"></span> Fora Details</a>
                <a class="btn btn-primary btn-sm" href="project_trainings_documents.php?did=<?php echo $did; ?>&amp;pid=<?php echo $pid; ?>" title="Project Fora Documents"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
        <div class="pull-left">
            <small>
                <?php echo $timestamp; ?>
            </small>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="project_trainings_documents_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;did=<?php echo $did; ?>&amp;pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form" role="form" enctype="multipart/form-data">

        <div class="form-group">
            <label for="frdoc_file" class="control-label">Document *</label><br>
            Current File : <a href="<?php echo TRAINING_DOCS_LINK_PATH.$GLOBALS['frdoc_file']; ?>" title="<?php echo $GLOBALS['frdoc_filename']; ?>"><?php echo $GLOBALS['frdoc_filename']; ?></a><br>
            <input class="form-control input-sm" placeholder="Document" name="frdoc_file" id="frdoc_file" type="file" accept="application/ms*,application/vnd.ms*">
        </div>

        <div class="form-group">
        <label for="frdoc_remarks" class="control-label">Remarks</label>
        <textarea class="form-control input-sm" placeholder="Remarks" name="frdoc_remarks" id="frdoc_remarks" cols="50" rows="4"><?php echo $GLOBALS['frdoc_remarks']; ?></textarea>
        </div>
        
        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="fr_id" value="<?php echo $did; ?>">
        <input type="hidden" name="frdoc_id" value="<?php echo $GLOBALS['frdoc_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>