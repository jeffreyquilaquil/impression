<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Project Packaging & Labeling Designs', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

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

loadDBValues("vwpsi_packaging", "SELECT * FROM vwpsi_packaging WHERE pkg_id = ".$id);

$subtimestamp = ' 
                Encoded on '.zeroDateTime($GLOBALS['date_encoded']).' by '.$GLOBALS['encoder'].'
                <br>
                Last updated on '.zeroDateTime($GLOBALS['last_updated']).' by '.$GLOBALS['updater'].'
                ';

$page_title = 'Project Packaging &amp; Labeling Designs';
page_header($page_title, 1);

if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
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


                <?php
                if (can_access('Project Packaging & Labeling Designs', 'add')){
                ?>
                    <a class="btn btn-success btn-sm" href="project_packaging_designs_form.php?pid=<?php echo $pid; ?>&amp;id=<?php echo $id; ?>&amp;op=0&amp;sid=0" title="Upload Designs"><span class="fa fa-upload"></span> Upload Designs</a>
                <?php
                }
                ?>
                <a class="btn btn-primary btn-sm" href="project_packaging_view.php?pid=<?php echo $pid; ?>&amp;id=<?php echo $id; ?>" title="View Details"><span class="fa fa-folder-open"></span> View Details</a>
                <a class="btn btn-primary btn-sm" href="project_packaging.php?pid=<?php echo $pid?>" title="Packaging &amp; Labeling"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
<?php
    showDesigns($id);
?>
   </div>
    <div class="panel-footer">
    </div>
</div>
<?php
    deleteFormCache();
    page_footer();



function showDesigns($id){
    global $pid;
    $sql = "SELECT * FROM vwpsi_packaging_designs WHERE pkg_id = $id ORDER BY design_date ASC";
    //echo $sql;
    $rows = mysqli_query($GLOBALS['cn'], $sql);

    if (!$rows) return;
    $ctr = 0;
    while ($row = mysqli_fetch_array($rows)){
        $ctr++;

        $draftlevel_name = $row['draftlevel_name'];
        $action = '';
        $action1 = '';
        $action2 = '';

        if (can_access('Packaging & Labeling Designs', 'edit')){
            $action .= '<a class="btn btn-primary btn-xs" href="project_packaging_designs_form.php?op=1&amp;sid='.$row['design_id'].'&amp;id='.$row['pkg_id'].'&amp;pid='.$pid.'"  title="Edit"><span class="fa fa-pencil"></span></a>';
        }



        if (can_access('Project Packaging & Labeling Designs', 'delete')){
            $action .= '<a class="btn btn-danger btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?<br>'.$draftlevel_name.'\', \'Confirm Delete\', \''.WEBSITE_URL.'project_packaging_designs_delete.php?op=2&amp;sid='.$row['design_id'].'&amp;id='.$row['pkg_id'].'&amp;pid='.$pid.'\');" title="Delete"><span class="fa fa-times-circle"></span></a>';
            $action1 .= '<a class="btn btn-danger btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete Original Design?<br>'.$draftlevel_name.'\', \'Confirm Delete\', \''.WEBSITE_URL.'project_packaging_designs_delete.php?op=3&amp;sid='.$row['design_id'].'&amp;id='.$row['pkg_id'].'&amp;pid='.$pid.'\');" title="Delete"><span class="fa fa-times-circle"></span></a>';
            $action2 .= '<a class="btn btn-danger btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete Commented Design?<br>'.$draftlevel_name.'\', \'Confirm Delete\', \''.WEBSITE_URL.'project_packaging_designs_delete.php?op=4&amp;sid='.$row['design_id'].'&amp;id='.$row['pkg_id'].'&amp;pid='.$pid.'\');" title="Delete"><span class="fa fa-times-circle"></span></a>';
        }

        //$action_img1 ='<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete Original Design from '.$row['draftlevel_name'].'?\', \'Confirm Delete\', \''.WEBSITE_URL.'packaging_designs_image_delete.php?op=2&amp;id='.$row['design_id'].'&amp;img=1&amp;pid='.$row['pkg_id'].'\');" title="Delete"><span class="fa fa-times-circle"></span> Delete</a>';
        //$action_img2 ='<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete Commented Design from '.$row['draftlevel_name'].'?\', \'Confirm Delete\', \''.WEBSITE_URL.'packaging_designs_image_delete.php?op=2&amp;id='.$row['design_id'].'&amp;img=2&amp;pid='.$row['pkg_id'].'\');" title="Delete"><span class="fa fa-times-circle"></span> Delete</a>';
    ?>

        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <div class="pull-left">
                    <h3 class="panel-title"><?php echo $draftlevel_name; ?> <small>(<?php echo $row['design_date']; ?>)</small></h3>
                </div>
                <div class="pull-right">
                    <?php echo $action; ?>
                </div>
                <div class="clearfix"></div>
                <?php 
                $desc = nl2br($row['design_description'].'');
                if (strlen($desc) > 0){
                ?>
                <div>
                    <small>
                    <?php echo $desc; ?>
                    </small>
                </div>
                <?php 
                }
                ?>
            </div>

            <div class="panel-body">
                    <?php 
                        if (strlen($row['design_image1']) > 0){
                    ?>
                    <div class="img_box_wrapper clearfix">
                        <div class="img_box text-center">
                            Original
                            <div class="img_box_clipper">
                                <a href="<?php echo DESIGN_LINK_PATH.$row['design_image1']; ?>" target="_blank" title="View <?php echo $row['design_filename1']; ?>">
                                    <img class="img_box_thumbnail" src="<?php echo DESIGN_LINK_PATH.$row['design_image1']; ?>" alt="<?php echo $row['design_filename1']; ?>">
                                </a>
                            </div>
                            <a class="btn btn-primary btn-xs" href="<?php echo DESIGN_LINK_PATH.$row['design_image1']; ?>" target="_blank" title="View <?php echo $row['design_filename1']; ?>"><span class="fa fa-eye"></span> View</a>
                            <?php 
                            //echo $action1; 
                            ?>
                        </div>
                    <?php 
                        }
                        if (strlen($row['design_image2']) > 0){
                    ?>
                        <div class="img_box text-center">
                            Commented
                            <div class="img_box_clipper">
                                <a href="<?php echo DESIGN_LINK_PATH.$row['design_image2']; ?>" target="_blank" title="View <?php echo $row['design_filename2']; ?>">
                                    <img class="img_box_thumbnail" src="<?php echo DESIGN_LINK_PATH.$row['design_image2']; ?>" alt="<?php echo $row['design_filename2']; ?>">
                                </a>
                            </div>
                            <a class="btn btn-primary btn-xs" href="<?php echo DESIGN_LINK_PATH.$row['design_image2']; ?>" target="_blank" title="View <?php echo $row['design_filename2']; ?>"><span class="fa fa-eye"></span> View</a>
                            <?php echo $action2; ?>
                        </div>
                    <?php 
                        }
                    ?>

                    </div>
                <div class="clearfix"></div>
            </div>
        </div>            
    <?php
    }
    mysqli_free_result($rows);
}

function showImages($id){
    $sql = "SELECT * FROM psi_packaging_designs_images WHERE design_id = $id";
    $rows = mysqli_query($GLOBALS['cn'], $sql);

    if (!$rows) return;

    while ($row = mysqli_fetch_array($rows)){
    ?>  
            <div class="img_box">
                <div class="img_box_clipper">
                    <a href="<?php echo DESIGN_LINK_PATH.$row['desimg_file']; ?>" target="_blank" title="View <?php echo $row['desimg_name']; ?>">
                        <img class="img_box_thumbnail" src="<?php echo DESIGN_LINK_PATH.$row['desimg_file']; ?>" alt="<?php echo $row['desimg_name']; ?>">
                    </a>
                </div>
                <a class="btn btn-primary btn-xs" href="<?php echo DESIGN_LINK_PATH.$row['desimg_file']; ?>" target="_blank" title="View <?php echo $row['desimg_name']; ?>"><span class="fa fa-eye"></span> View</a>
                <a class="btn btn-primary btn-xs" href="#" title="Delete"><span class="fa fa-times-circle"></span> Delete</a>  
            </div>
    <?php
    }

    mysqli_free_result($rows);
}

?>