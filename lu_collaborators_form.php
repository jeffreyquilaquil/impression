<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_collaborators.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_collaborators.php');

if ($op == 1){
    if (!can_access('Collaborating Agencies', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Collaborating Agencies', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_collaborators", "SELECT * FROM psi_collaborators WHERE col_id = ".$id);
} else {
    initFormValues('psi_collaborators');
}

loadFormCache('psi_collaborators');

$sel_category = getOptions('psi_organization_types', 'ot_name', 'ot_id', $GLOBALS['ot_id'], '', 'ORDER BY ot_name ASC');
$page_title = 'Collaborating Agencies ('.$opstr.')';
page_header($page_title);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left"><?php echo $page_title; ?></h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="lu_collaborators.php" title="Collaborating Agencies"><span class="fa fa-arrow-circle-left"></span> Back</a>        
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="lu_collaborators_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group form-group-sm">
        <label for="ot_id" class="control-label">Category</label>
        <select class="form-control input-sm" id="ot_id" name="ot_id">
        <?php echo $sel_category; ?>
        </select>
        </div>
    
        <div class="form-group">
        <label for="col_name" class="control-label">Agency Name *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Agency Name" maxlength="255" required="required" name="col_name" id="col_name" type="text" value="<?php echo $GLOBALS['col_name']; ?>">
                </div>

        <div class="form-group">
        <label for="col_abbr" class="control-label">Abbreviation *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Abbreviation" maxlength="255" required="required" name="col_abbr" id="col_abbr" type="text" value="<?php echo $GLOBALS['col_abbr']; ?>">
                </div>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="col_id" value="<?php echo $GLOBALS['col_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>