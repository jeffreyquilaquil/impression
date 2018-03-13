<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'lu_courses.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'lu_courses.php');

if ($op == 1){
    if (!can_access('Courses', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Courses', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_courses", "SELECT * FROM psi_courses WHERE course_id = ".$id);
} else {
    initFormValues('psi_courses');
}

loadFormCache('psi_courses');

$sel_category = getOptions('psi_course_categories', 'course_cat_name', 'course_cat_id', $GLOBALS['course_cat_id'], '', 'ORDER BY course_cat_name ASC');

$page_title = 'Courses ('.$opstr.')';
page_header($page_title);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left"><?php echo $page_title; ?></h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="lu_courses.php" title="Courses"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="lu_courses_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group form-group-sm">
        <label for="course_cat_id" class="control-label">Category</label>
        <select class="form-control input-sm" id="course_cat_id" name="course_cat_id">
        <?php echo $sel_category; ?>
        </select>
        </div>

        <div class="form-group">
        <label for="course_name" class="control-label">Course Name *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Course Name" maxlength="255" required="required" name="course_name" id="course_name" type="text" value="<?php echo $GLOBALS['course_name']; ?>">
                </div>

        <div class="form-group">
        <label for="course_yearcount" class="control-label">Years  *</label>
        <input class="form-control input-sm" placeholder="Years" min="0" step="1" required="required" name="course_yearcount" id="course_yearcount" type="number" value="<?php echo $GLOBALS['course_yearcount']; ?>">
                </div>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="course_id" value="<?php echo $GLOBALS['course_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>