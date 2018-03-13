<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Trainings', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'trainings.php');

if (!dbValueExists('psi_trainings', 'tr_id', $pid, false)){
    redirect(WEBSITE_URL.'trainings.php');
    die();
}

loadDBValues("vwpsi_trainings", "SELECT * FROM vwpsi_trainings WHERE tr_id = ".$pid);


page_header('Trainings (Details)', 2);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title">Training Details </h3>
            <div>
                <h3 class="detail-name text-primary">
                <?php echo $GLOBALS['tr_title']; ?>
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
            <?php
            if (can_access('Training Documents', 'view')){
            ?>
            <a class="btn btn-primary btn-sm" href="trainings_documents.php?pid=<?php echo $pid; ?>" title="Training Documents"><span class="fa fa-file"></span> Training Documents</a>
            <?php
            }
            ?>
            <a class="btn btn-primary btn-sm" href="trainings.php" title="Trainings"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <div class="row-fluid">
            <h5>Training Start</h5>
            <div class="well well-sm">
                <?php echo zeroDate($GLOBALS['tr_start'].''); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Training End</h5>
            <div class="well well-sm">
                <?php echo zeroDate($GLOBALS['tr_end'].''); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Duration</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['tr_duration']; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Service Provider</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['sp_name']; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Training Cost</h5>
            <div class="well well-sm">
                <?php echo zeroCurr($GLOBALS['tr_cost'].''); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Location</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['tr_location'].''); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Overall CSF Rating</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['tr_csf'].''; ?>
            </div>
        </div>


        <div class="row-fluid">
            <h5>Implementor</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['ug_name']; ?>
            </div>
        </div>

       <div class="row-fluid">
            <h3><span class="label label-default full-width">
                Participant Demographics
            </span></h3>

            <h5>No. of Feminine Participants</h5>
            <div class="well well-sm">
                <?php echo zeroNumber($GLOBALS['tr_no_feminine'].''); ?>
            </div>

            <h5>No. of Masculine Participants</h5>
            <div class="well well-sm">
                <?php echo zeroNumber($GLOBALS['tr_no_musculine'].''); ?>
            </div>

            <h5>No. of PWD Participants</h5>
            <div class="well well-sm">
                <?php echo zeroNumber($GLOBALS['tr_no_pwd'].''); ?>
            </div>

            <h5>No. of Senior Participants</h5>
            <div class="well well-sm">
                <?php echo zeroNumber($GLOBALS['tr_no_seniors'].''); ?>
            </div>

            <h5>No. of Participating Firms</h5>
            <div class="well well-sm">
                <?php echo zeroNumber($GLOBALS['tr_no_firms'].''); ?>
            </div>

            <h5>Total No. of Participants</h5>
            <div class="well well-sm">
                <?php 
                $sum = $GLOBALS['tr_no_musculine'] + $GLOBALS['tr_no_feminine'];
                echo zeroNumber($sum, 0);
                ?>
            </div>
        </div>

       <div class="row-fluid">
            <h3><span class="label label-default full-width">
                Training Map Coordinates
            </span></h3>

            <h5>Longitude</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['tr_longitude'].''; ?>
            </div>

            <h5>Latitude</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['tr_latitude'].''; ?>
            </div>

            <h5>Elevation</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['tr_elevation'].''; ?>
            </div>
        </div>

    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>