<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$id = requestInteger('id', 'location: '.WEBSITE_URL.'project_gallery.php?pid='.$pid);
$op = requestInteger('op', 'location: '.WEBSITE_URL.'project_gallery.php?pid='.$pid);

if ($op == 1){
    if (!can_access('Project Photos', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Project Photos', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_project_albums", "SELECT * FROM psi_project_albums WHERE album_id = ".$id);
} else {
    initFormValues('psi_project_albums');
    $GLOBALS['album_event_date'] = date('m/d/Y');
}

loadFormCache('psi_project_albums');


$page_title = 'Project Photos ('.$opstr.')';
page_header($page_title, 1);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="project_gallery.php?pid=<?php echo $pid; ?>" title="Project Photos"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="project_gallery_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>&amp;pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form" role="form" enctype="multipart/form-data">

        <div class="form-group">
        <label for="album_name" class="control-label">Album Name *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Album Name" required="required" name="album_name" id="album_name" type="text" value="<?php echo $GLOBALS['album_name']; ?>">
                </div>

        <div class="form-group">
        <label for="album_desc" class="control-label">Description *</label>
        <textarea class="form-control input-sm" placeholder="Description" required="required" name="album_desc" id="album_desc" cols="50" rows="4"><?php echo $GLOBALS['album_desc']; ?></textarea>
        </div>

        <div class="form-group">
        <label for="album_event_date" class="control-label">Date *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm date-picker" placeholder="Date" maxlength="10" required="required" name="album_event_date" id="album_event_date" type="text" value="<?php echo $GLOBALS['album_event_date']; ?>">
                </div>

        <div class="form-group">
            <label for="album_photos" class="control-label">Photos *</label><br>
            <input class="form-control input-sm" placeholder="Photos" name="album_photos[]" id="album_photos" type="file" accept="image/*" multiple="multiple">
        </div>

        <?php 
            if ($op == 1){
                getPhotos($id);
            }
        ?>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="prj_id" value="<?php echo $pid; ?>">
        <input type="hidden" name="album_id" value="<?php echo $GLOBALS['album_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();

    function getPhotos($id){
        $sql = "SELECT * FROM psi_project_album_photos WHERE album_id = $id";
        $res = mysqli_query($GLOBALS['cn'], $sql);
        if (!$res) return;

        $s = '
        <label>Current Photos</label>
        <div class="well well-default">
            <div class="clearfix">';

        while ($row = mysqli_fetch_array($res)){
            $img = GALLERY_LINK_PATH.$row['photo_file'];
            $s .= '
                <div class="img_box">
                    <a href="'.$img.'" target="_blank"><img class="img_box_thumbnail" src="'.$img.'" alt="'.$row['photo_filename'].'"></a>
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