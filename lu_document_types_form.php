<?php 
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_document_types.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_document_types.php');

if ($op == 1){
    if (!can_access('Document Categories', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Document Categories', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_project_document_types", "SELECT * FROM psi_project_document_types WHERE doctype_id = ".$id);
} else {
    initFormValues('psi_project_document_types');
}

loadFormCache('psi_project_document_types');

$page_title = 'Document Categories ('.$opstr.')';
page_header($page_title);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left"><?php echo $page_title; ?></h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="lu_document_types.php" title="Document Categories"><span class="fa fa-arrow-circle-left"></span> Back</a>        
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="lu_document_types_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group">
        <label for="doctype_name" class="control-label">Category Name *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Category Name" maxlength="255" required="required" name="doctype_name" id="doctype_name" type="text" value="<?php echo $GLOBALS['doctype_name']; ?>">
                </div>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="doctype_id" value="<?php echo $GLOBALS['doctype_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>