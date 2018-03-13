<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'consultancies.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'consultancies_view.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'consultancies_view.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Consultancy Documents', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Consultancy Documents', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_consultancies', 'con_id', $pid, false)){
    redirect(WEBSITE_URL.'trainings.php');
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
                
$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_consultancy_documents", "SELECT * FROM psi_consultancy_documents WHERE condoc_id = ".$id);
} else {
    initFormValues('psi_consultancy_documents');
}

loadFormCache('psi_consultancy_documents');

$page_title = 'Consultancy Details';
page_header($page_title, 2);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="consultancies_view.php?pid=<?php echo $pid; ?>" title="Consultancy Details"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <?php echo $timestamp; ?>
    </div>
    <div class="panel-footer">
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title">Documents <?php echo '('.$opstr.')' ?></h3>
            </div>
        </div>
        <div class="pull-left">
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="consultancies_documents_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form" role="form" enctype="multipart/form-data">

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
        <input type="hidden" name="con_id" value="<?php echo $pid; ?>">
        <input type="hidden" name="condoc_id" value="<?php echo $GLOBALS['condoc_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>