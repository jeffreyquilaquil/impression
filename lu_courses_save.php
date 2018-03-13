<?php
require_once('inc_conn.php');
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

saveFormCache('psi_courses');

if (postEmpty('course_name')){
	$_SESSION['errmsg'] = "Course Name is required.";
	redirect(WEBSITE_URL.'lu_courses_form.php?op='.$op.'&id='.$id);
	die();
}

if (postEmpty('course_yearcount')){
	$_SESSION['errmsg'] = "Years is required.";
	redirect(WEBSITE_URL.'lu_courses_form.php?op='.$op.'&id='.$id);
	die();
} else if (!postInteger('course_yearcount')){
	$_SESSION['errmsg'] = "Years must be a number.";
	redirect(WEBSITE_URL.'lu_courses_form.php?op='.$op.'&id='.$id);
	die();
} elseif ($GLOBALS['course_yearcount'] < 0){
	$_SESSION['errmsg'] = "Years is invalid.";
	redirect(WEBSITE_URL.'lu_courses_form.php?op='.$op.'&id='.$id);
	die();
}


$sql = '';
$msg = '';

if ($op == 1){
	$sql = getUpdateQuery('psi_courses', 'course_id');
	$msg = 'Record Updated.';
} else {
	$sql = getInsertQuery('psi_courses', 'course_id');
	$msg = 'Record Added.';
}

//echo $sql;
//die();
mysqli_query($GLOBALS['cn'], $sql);
$_SESSION['errmsg'] = $msg;
redirect(WEBSITE_URL.'lu_courses.php');
?>