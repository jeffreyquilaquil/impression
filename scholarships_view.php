<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Scholarships', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'scholarships.php?dedo=1');

if (!dbValueExists('psi_scholarships', 'scholar_id', $pid, false)){
    redirect(WEBSITE_URL.'scholarships.php');
    die();
}

loadDBValues("vwpsi_scholarships", "SELECT * FROM vwpsi_scholarships WHERE scholar_id = ".$pid);


page_header('Scholarship Details');
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title">Scholarship Details</h3>
            <div>
                <h3 class="detail-name text-primary">
                <?php echo $GLOBALS['scholar_name']; ?>
                </h3>
            </div>
            <div>
                <small>
                Encoded on <?php echo zeroDateTime($GLOBALS['date_encoded']); ?> by <?php echo $GLOBALS['encoder']; ?><br>
                Last updated on <?php echo zeroDateTime($GLOBALS['last_updated']); ?> by <?php echo $GLOBALS['updater']; ?>
                </small>
            </div>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="scholarships.php" title="Scholarship"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <div class="row-fluid">
            <h5>Scholarship Program</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['scholar_prog_name'].''; ?>
            </div>

            <h5>Course</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['course_name'].''; ?>
            </div>

            <h5>Year Awarded</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['scholar_year_award'].''; ?>
            </div>

            <h5>Status</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['scholar_stat_name'].''; ?>
            </div>

            <h5>Address</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['scholar_address'].''); ?>
            </div>

            <h5>Email</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['scholar_email'].''; ?>
            </div>

            <h5>Mobile</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['scholar_mobile'].''; ?>
            </div>

            <h5>Remarks</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['scholar_remarks'].''); ?>
            </div>
        </div>

    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>