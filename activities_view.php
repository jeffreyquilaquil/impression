<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Media Activities', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'activities.php?dedo=1');

if (!dbValueExists('psi_activities', 'activity_id', $pid, false)){
    redirect(WEBSITE_URL.'activities.php');
    die();
}

loadDBValues("vwpsi_activities", "SELECT * FROM vwpsi_activities WHERE activity_id = ".$pid);


page_header('Media Activities Details');
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title">Media Activities Details</h3>
            <div>
                <h3 class="detail-name text-primary">
                <?php echo $GLOBALS['activity_title']; ?>
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
            <a class="btn btn-primary btn-sm" href="activities.php" title="Media Activities"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <div class="row-fluid">
            <h5>Category</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['activity_type_name'].''; ?>
            </div>

            <h5>Address</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['activity_address'].''); ?>
            </div>

            <h5>Date Accomplished</h5>
            <div class="well well-sm">
                <?php echo zeroDate($GLOBALS['activity_date_accomplished'].''); ?>
            </div>

            <h5>No. of Articles</h5>
            <div class="well well-sm">
                <?php echo zeroNumber($GLOBALS['activity_no_articles'].'', 0); ?>
            </div>

            <h5>Overall CSF Rating</h5>
            <div class="well well-sm">
                <?php echo zeroNumber($GLOBALS['activity_csf'].'', 0); ?>
            </div>

            <h5>Remarks</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['activity_remarks'].''); ?>
            </div>

            <h3><span class="label label-default full-width">Participant Demographics</span></h3>


            <h5>No. of Participants</h5>
            <div class="well well-sm">
                <?php 
                $sum = $GLOBALS['activity_no_male'] + $GLOBALS['activity_no_female'];
                echo zeroNumber($sum, 0);
                ?>
            </div>

            <h5>No. of Feminine Participants</h5>
            <div class="well well-sm">
                <?php echo zeroNumber($GLOBALS['activity_no_female'].'', 0); ?>
            </div>

            <h5>No. of Masculine Participants</h5>
            <div class="well well-sm">
                <?php echo zeroNumber($GLOBALS['activity_no_male'].'', 0); ?>
            </div>

            <h5>No. of PWD</h5>
            <div class="well well-sm">
                <?php echo zeroNumber($GLOBALS['activity_no_pwd'].'', 0); ?>
            </div>

            <h5>No. of Senior Citizens</h5>
            <div class="well well-sm">
                <?php echo zeroNumber($GLOBALS['activity_no_senior'].'', 0); ?>
            </div>
        </div>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>