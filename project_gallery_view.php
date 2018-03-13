<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Project Photos', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_gallery.php?pid='.$pid);
if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'project_gallery.php?pid='.$pid);
    die();
}

$page_title = 'Project Photos';
page_header($page_title, 1);

if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}

loadDBValues("psi_project_albums", "SELECT * FROM psi_project_albums WHERE prj_id = ".$pid);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
                <h3 class="detail-name text-primary">
                    <?php echo $GLOBALS['album_name']; ?>
                </h3>
                <p>
                    <small>
                    Encoded on <?php echo zeroDateTime($GLOBALS['date_encoded']); ?> by <?php echo $GLOBALS['encoder']; ?>
                    <br>
                    Last updated on <?php echo zeroDateTime($GLOBALS['last_updated']); ?> by <?php echo $GLOBALS['updater']; ?>
                    </small>
                </p>
                <?php 
                    $desc = nl2br($GLOBALS['album_desc'].'');
                    if (strlen($desc) > 0){
                        echo '<p>'.$desc.'</p>';
                    }
                ?>
            </div>
            <div class="pull-right">
                <?php
                if (can_access('Project Photos', 'add')){
                ?>
                <a class="btn btn-primary btn-sm" href="project_gallery_form.php?op=0&amp;id=0&amp;pid=<?php echo $pid; ?>" title="Add Album"><span class="fa fa-plus"></span> Add Album</a>
                <?php
                }
                ?>

                <a class="btn btn-primary btn-sm" href="project_gallery.php?pid=<?php echo $pid; ?>" title="Project Photos"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <?php 
            getPhotos($id);
        ?>
    </div>
</div>
<?php
    page_footer();

    function getPhotos($id){
        $sql = "SELECT * FROM psi_project_album_photos WHERE album_id = $id";
        $res = mysqli_query($GLOBALS['cn'], $sql);
        if (!$res) return;

        $s = '
        <div class="well well-default">
            <div class="clearfix">';

        while ($row = mysqli_fetch_array($res)){
            $img = GALLERY_LINK_PATH.$row['photo_file'];
            $s .= '
                <div class="img_box">
                    <a href="'.$img.'" title="'.$row['photo_filename'].'" target="_blank"><img class="img_box_thumbnail" src="'.$img.'" alt="'.$row['photo_filename'].'"></a>
                </div>
            ';

        }

        $s .= '
            </div>
        </div>
            ';

        mysqli_free_result($res);

        echo $s;
    }
?>