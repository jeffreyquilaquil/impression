<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_packaging.php?pid='.$pid);

if (!dbValueExists('psi_packaging', 'pkg_id', $id, false)){
    redirect(WEBSITE_URL.'project_packaging.php?pid='.$pid);
    die();
}

$sid = requestInteger('sid', 'location: '.WEBSITE_URL.'project_packaging.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_packaging.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Packaging & Labeling Designs', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Packaging & Labeling Designs', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

loadDBValues("vwpsi_packaging", "SELECT * FROM vwpsi_packaging WHERE pkg_id = ".$id);

$subtimestamp = '
                Encoded on '.zeroDateTime($GLOBALS['date_encoded']).' by '.$GLOBALS['encoder'].'
                <br>
                Last updated on '.zeroDateTime($GLOBALS['last_updated']).' by '.$GLOBALS['updater'].'
                ';

$opstr = 'Upload';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues('psi_packaging_designs', "SELECT * FROM psi_packaging_designs WHERE design_id = $sid");
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
    $GLOBALS['pkg_id'] = $id;
    $GLOBALS['draftlevel_id'] = $draft_level;
}

//loadDBValues('psi_draftlevels', "SELECT * FROM psi_draftlevels WHERE draftlevel_id = $GLOBALS[draftlevel_id]");

//$GLOBALS['pkg_id'] = $id;
//$GLOBALS['design_id'] = $sid;

loadFormCache("psi_packaging_designs");

$sel_drafts = getOptions('psi_draftlevels', 'draftlevel_name', 'draftlevel_id', $GLOBALS['draftlevel_id']);

$page_title = 'Project Packaging &amp; Labeling Designs ('.$opstr.')';
page_header($page_title, 1);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
                <h3 class="detail-name">
                    Product Name : <span class="text-primary"> <?php echo $GLOBALS['pkg_product_name']; ?></span>
                    <?php
                    if (strlen($GLOBALS['pkg_brand_name']) > 0){
                    ?>
                    <br>
                    Brand Name : <span class="text-primary"><?php echo $GLOBALS['pkg_brand_name']; ?></span>
                    <?php
                    }
                    ?>
                </h3>
                <p>
                    <?php echo $subtimestamp; ?>
                </p>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="project_packaging_designs.php?pid=<?php echo $pid; ?>&amp;id=<?php echo $id; ?>" title="Back"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){
                ?><div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div><?php
            } ?>
        <form method="post" action="project_packaging_designs_save.php?pid=<?php echo $pid; ?>&amp;op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;sid=<?php echo $sid; ?>" accept-charset="UTF-8" class="form" role="form" enctype="multipart/form-data">
    
        <div class="form-group">
            <label for="draftlevel_id" class="control-label">Draft Level</label>
            <select class="form-control input-sm" id="draftlevel_id" name="draftlevel_id">
            <?php echo $sel_drafts; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="design_image1" class="control-label">Design (Original)</label>
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