<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_scholarship_programs.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_scholarship_programs.php');

if ($op == 1){
    if (!can_access('Scholarship Programs', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Scholarship Programs', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_scholarship_programs", "SELECT * FROM psi_scholarship_programs WHERE scholar_prog_id = ".$id);
} else {
    initFormValues('psi_scholarship_programs');
}

loadFormCache('psi_scholarship_programs');

$page_title = 'Scholarship Programs ('.$opstr.')';
page_header($page_title);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left"><?php echo $page_title; ?></h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="lu_scholarship_programs.php" title="Project Categories"><span class="fa fa-arrow-circle-left"></span> Back</a>        
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="lu_scholarship_programs_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group">
        <label for="scholar_prog_name" class="control-label">Program Name *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Program Name" maxlength="255" required="required" name="scholar_prog_name" id="scholar_prog_name" type="text" value="<?php echo $GLOBALS['scholar_prog_name']; ?>">
                </div>

        <div class="form-group">
        <label for="scholar_prog_desc" class="control-label">Description *</label>
        <textarea class="form-control input-sm" placeholder="Description" required="required" name="scholar_prog_desc" id="scholar_prog_desc" cols="50" rows="4"><?php echo $GLOBALS['scholar_prog_desc']; ?></textarea>
        </div>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="scholar_prog_id" value="<?php echo $GLOBALS['scholar_prog_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>