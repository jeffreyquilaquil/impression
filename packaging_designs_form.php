<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'packaging.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'packaging.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'packaging.php');

if ($op == 1){
    if (!can_access('Packaging & Labeling Designs', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Packaging & Labeling Designs', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_packaging', 'pkg_id', $pid, false)){
    redirect(WEBSITE_URL.'packaging.php');
    die();
}

loadDBValues("vwpsi_packaging", "SELECT * FROM vwpsi_packaging WHERE pkg_id = ".$pid);

$opstr = 'Upload';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues('psi_packaging_designs', "SELECT * FROM psi_packaging_designs WHERE design_id = ".$id);
} else {
    $draft_level = getCount('psi_packaging_designs', 'design_id', "WHERE pkg_id = $pid");
    $draft_level += 1;
    
    if ($draft_level > 3){
        $_SESSION['errmsg'] = "Final Draft has already been uploaded.";
        redirect(WEBSITE_URL.'packaging_designs.php?pid='.$pid);
        die();
    }
    

    initFormValues('psi_packaging_designs');
    $GLOBALS['pkg_selling_point'] = 1;
    $GLOBALS['pkg_distribution'] = 1;
    $GLOBALS['pkg_id'] = $pid;
    $GLOBALS['draftlevel_id'] = $draft_level;
}

//loadDBValues('psi_draftlevels', "SELECT * FROM psi_draftlevels WHERE draftlevel_id = $GLOBALS[draftlevel_id]");

//$GLOBALS['pkg_id'] = $pid;
//$GLOBALS['design_id'] = $id;

loadFormCache("psi_packaging_designs");

$sel_drafts = getOptions('psi_draftlevels', 'draftlevel_name', 'draftlevel_id', $GLOBALS['draftlevel_id']);


$page_title = 'Packaging &amp; Labeling Designs ('.$opstr.')';
page_header($page_title, 2);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title"><?php echo $page_title; ?></h3>
            <div>
                <h4>
                <?php
                    echo $GLOBALS['pkg_product_name'];
                    if (strlen($GLOBALS['pkg_brand_name']) > 0){
                        echo ' ('.$GLOBALS['pkg_brand_name'].')'; 
                    }
                ?>
                </h4>
            </div>
            <div><h5><?php echo $GLOBALS['coop_name']; ?></h5></div>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="packaging_designs.php?op=<?php echo $op; ?>&amp;pid=<?php echo $pid; ?>&amp;id=<?php echo $$GLOBALS['design_id']; ?>" title="Back"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){
                ?><div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div><?php
            } ?>
        <form method="post" action="packaging_designs_save.php?pid=<?php echo $pid; ?>&amp;op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form" enctype="multipart/form-data">
    
        <div class="form-group">
            <label for="draftlevel_id" class="control-label">Draft Level</label>
            <select class="form-control input-sm" id="draftlevel_id" name="draftlevel_id">
            <?php echo $sel_drafts; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="design_image1" class="control-label">Design</label>
            <?php
                if (strlen($GLOBALS['design_image1']) > 0){
                    ?>
                    <a href="<?php echo DESIGN_LINK_PATH.$GLOBALS['design_image1']; ?>" target="_blank" title="Current Design"><span class="fa fa-file-image-o"></span> Current Design</a><br>
                    <?php
                }
            ?>
            <input class="form-control input-sm" placeholder="Design (Original)" name="design_image1" id="design_image1" type="file" accept="image/*">
        </div>

        <div class="form-group">
            <label for="design_image2" class="control-label">Design (Commented)</label>
            <?php
                if (strlen($GLOBALS['design_image2']) > 0){
                    ?>
                    <a href="<?php echo DESIGN_LINK_PATH.$GLOBALS['design_image2']; ?>" target="_blank" title="Current Design"><span class="fa fa-file-image-o"></span> Current Design</a><br>
                    <?php
                }
            ?>
            <input class="form-control input-sm" placeholder="Design (Commented)" name="design_image2" id="design_image2" type="file" accept="image/*">
        </div>

        <div class="form-group">
            <label for="design_description" class="control-label">Description</label>
            <textarea class="form-control input-sm" placeholder="Description" name="design_description" id="design_description" cols="50" rows="10"><?php echo $GLOBALS['design_description']; ?></textarea>
        </div>
  
        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="pkg_id" value="<?php echo $GLOBALS['pkg_id']; ?>">
        <input type="hidden" name="design_id" value="<?php echo $GLOBALS['design_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>