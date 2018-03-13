<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'trainings.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'trainings_documents.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'trainings_documents.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Training Documents', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Training Documents', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_trainings', 'tr_id', $pid, false)){
    redirect(WEBSITE_URL.'trainings.php');
    die();
}

loadDBValues("psi_trainings", "SELECT * FROM psi_trainings WHERE tr_id = ".$pid);
$timestamp = '
                Encoded on '.zeroDateTime($GLOBALS['date_encoded']).' by '.$GLOBALS['encoder'].'
                <br>
                Last updated on '.zeroDateTime($GLOBALS['last_updated']).' by '.$GLOBALS['updater'];
                
$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_training_documents", "SELECT * FROM psi_training_documents WHERE trdoc_id = ".$id);
} else {
    initFormValues('psi_training_documents');
}

loadFormCache('psi_training_documents');

$page_title = 'Training Documents ('.$opstr.')';
page_header($page_title, 2);
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
                <a class="btn btn-primary btn-sm" href="trainings_view.php?pid=<?php echo $pid; ?>" title="Training Details"><span class="fa fa-folder-open"></span> Training Details</a>
                <a class="btn btn-primary btn-sm" href="trainings_documents.php?pid=<?php echo $pid; ?>" title="Training Documents"><span class="fa fa-arrow-circle-left"></span> Back</a>
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
        <form method="POST" action="trainings_documents_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form" role="form" enctype="multipart/form-data">

        <div class="form-group">
            <label for="trdoc_file" class="control-label">Document *</label><br>
            Current File : <a href="<?php echo TRAINING_DOCS_LINK_PATH.$GLOBALS['trdoc_file']; ?>" title="<?php echo $GLOBALS['trdoc_filename']; ?>"><?php echo $GLOBALS['trdoc_filename']; ?></a><br>
            <input class="form-control input-sm" placeholder="Document" name="trdoc_file" id="trdoc_file" type="file" accept="application/ms*,application/vnd.ms*">
        </div>

        <div class="form-group">
        <label for="trdoc_remarks" class="control-label">Remarks</label>
        <textarea class="form-control input-sm" placeholder="Remarks" name="trdoc_remarks" id="trdoc_remarks" cols="50" rows="4"><?php echo $GLOBALS['trdoc_remarks']; ?></textarea>
        </div>
        
        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="tr_id" value="<?php echo $pid; ?>">
        <input type="hidden" name="trdoc_id" value="<?php echo $GLOBALS['trdoc_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>