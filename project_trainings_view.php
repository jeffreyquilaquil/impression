<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Project Fora', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'project.php');
$did = requestInteger('did', 'location: '.WEBSITE_URL.'project_trainings.php?pid='.$pid);

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

if (!dbValueExists('psi_fora', 'fr_id', $did, false)){
    redirect(WEBSITE_URL.'project_trainings.php?pid='.$pid);
    die();
}

loadDBValues("vwpsi_fora", "SELECT * FROM vwpsi_fora WHERE fr_id = ".$did);
$timestamp = '
                Encoded on '.zeroDateTime($GLOBALS['date_encoded']).' by '.$GLOBALS['encoder'].'<br>
                Last updated on '.zeroDateTime($GLOBALS['last_updated']).' by '.$GLOBALS['updater'].'
            ';


$page_title = 'Project Fora/Trainings/Seminars Details';
page_header($page_title, 1);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title"><?php echo $page_title; ?></h3>
            <div>
                <h3 class="detail-name text-primary">
                <?php echo $GLOBALS['fr_title']; ?>
                </h3>
            </div>
            <div>
                <small><?php echo $timestamp; ?></small>
            </div>
        </div>
        <div class="pull-right">
            <?php
            if (can_access('Project Training Documents', 'view')){
            ?>
            <a class="btn btn-primary btn-sm" href="project_trainings_documents.php?pid=<?php echo $pid; ?>&amp;did=<?php echo $did; ?>" title="Training Documents"><span class="fa fa-file"></span> Training Documents</a>
            <?php
            }
            ?>
            <a class="btn btn-primary btn-sm" href="project_trainings.php?pid=<?php echo $pid; ?>" title="Trainings"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">

        <div class="row-fluid">
            <div class="col-sm-6">
                <h5>Requesting Party/Address</h5>
                <div class="well well-sm">
                    <?php echo nl2br($GLOBALS['fr_requesting_party'].''); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <h5>Cooperating Agencies</h5>
                <div class="well well-sm">
                    <?php echo nl2br($GLOBALS['collaborator_names'].''); ?>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="col-sm-12">
                <h5>Sectors</h5>
                <div class="well well-sm">
                    <?php echo nl2br($GLOBALS['fr_sectors'].''); ?>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="col-sm-6">
                <h5>Forum Start</h5>
                <div class="well well-sm">
                    <?php echo zeroDate($GLOBALS['fr_start'].''); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <h5>Forum End</h5>
                <div class="well well-sm">
                    <?php echo zeroDate($GLOBALS['fr_end'].''); ?>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="col-sm-12">
                <h5>Venue</h5>
                <div class="well well-sm">
                    <?php echo nl2br($GLOBALS['fr_location'].''); ?>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="col-sm-6">
                <h5>Overall CSF Rating</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['fr_csf'].''; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <h5>Forum Cost</h5>
                <div class="well well-sm">
                    <?php echo zeroCurr($GLOBALS['fr_cost'].''); ?>
                </div>
            </div>
        </div>


        <div class="row-fluid">
            <div class="col-sm-12">
                <h5>Implementor</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['ug_name']; ?>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="col-sm-12">
                <h5>Remarks</h5>
                <div class="well well-sm">
                    <?php echo nl2br($GLOBALS['fr_remarks'].''); ?>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <h3>
                <span class="label label-default full-width">Participant Demographics</span>
            </h3>

            <div class="col-sm-6">
                <h5>No. of Feminine Participants</h5>
                <div class="well well-sm">
                    <?php echo zeroNumber($GLOBALS['fr_no_feminine'].''); ?>
                </div>
            </div>

            <div class="col-sm-6">
                <h5>No. of Masculine Participants</h5>
                <div class="well well-sm">
                    <?php echo zeroNumber($GLOBALS['fr_no_musculine'].''); ?>
                </div>
            </div>

            <div class="col-sm-6">
                <h5>No. of PWD Participants</h5>
                <div class="well well-sm">
                    <?php echo zeroNumber($GLOBALS['fr_no_pwd'].''); ?>
                </div>
            </div>

            <div class="col-sm-6">
                <h5>No. of Senior Participants</h5>
                <div class="well well-sm">
                    <?php echo zeroNumber($GLOBALS['fr_no_seniors'].''); ?>
                </div>
            </div>

            <div class="col-sm-6">
                <h5>No. of Participating Firms</h5>
                <div class="well well-sm">
                    <?php echo zeroNumber($GLOBALS['fr_no_firms'].''); ?>
                </div>
            </div>

            <div class="col-sm-6">
                <h5>Total No. of Participants</h5>
                <div class="well well-sm">
                    <?php 
                    $sum = $GLOBALS['fr_no_musculine'] + $GLOBALS['fr_no_feminine'];
                    echo zeroNumber($sum, 0);
                    ?>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <h3><span class="label label-default full-width">
                Forum Map Coordinates
            </span></h3>

            <div class="col-sm-4">
                <h5>Longitude</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['fr_longitude'].''; ?>
                </div>
            </div>

            <div class="col-sm-4">
                <h5>Latitude</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['fr_latitude'].''; ?>
                </div>
            </div>

            <div class="col-sm-4">
                <h5>Elevation</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['fr_elevation'].''; ?>
                </div>
            </div>
        </div>

    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>