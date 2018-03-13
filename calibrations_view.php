<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Testings & Calibrations', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'calibrations.php');

if (!dbValueExists('psi_calibrations', 'cal_id', $pid, false)){
    redirect(WEBSITE_URL.'calibrations.php');
    die();
}

loadDBValues("vwpsi_calibrations", "SELECT * FROM vwpsi_calibrations WHERE cal_id = ".$pid);


$page_title = 'Testing &amp; Calibration Details';
page_header($page_title, 2);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title"><?php echo $page_title; ?></h3>
            <div>
                <small>
                Encoded on <?php echo zeroDateTime($GLOBALS['date_encoded']); ?> by <?php echo $GLOBALS['encoder']; ?><br>
                Last updated on <?php echo zeroDateTime($GLOBALS['last_updated']); ?> by <?php echo $GLOBALS['updater']; ?>
                </small>
            </div>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="calibrations.php" title="Testing &amp; Calibrations"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <h5>Laboratory</h5>
                <div class="well well-sm">
                    <abbr title="<?php echo $GLOBALS['ug_name']; ?>"><?php echo $GLOBALS['ug_name']; ?></abbr>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <h5>Month &amp; Year</h5>
                <div class="well well-sm">
                    <?php echo getMonthName($GLOBALS['cal_month']).', '.$GLOBALS['cal_year']; ?>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3">
                <h5>No. of Services Renderd</h5>
                <div class="well well-sm">
                    <?php echo zeroNumber($GLOBALS['cal_no_tests'], 0); ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
                <h5>No. of Samples Tested / Calibrations</h5>
                <div class="well well-sm">
                    <?php echo zeroNumber($GLOBALS['cal_no_calibrations'], 0); ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
                <h5>No. of Customers Assisted</h5>
                <div class="well well-sm">
                    <?php echo zeroNumber($GLOBALS['cal_no_clients'], 0); ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
                <h5>No. of Firms Assisted</h5>
                <div class="well well-sm">
                    <?php echo zeroNumber($GLOBALS['cal_no_firms'], 0); ?>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <h5>Income</h5>
                <div class="well well-sm">
                    <?php echo zeroCurr($GLOBALS['cal_income']); ?>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4">
                <h5>Value Of Assistance</h5>
                <div class="well well-sm">
                    <?php echo zeroCurr($GLOBALS['cal_value_service']); ?>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4">
                <h5>Gross Income</h5>
                <div class="well well-sm">
                    <?php 
                       $gross = $GLOBALS['cal_income'] + $GLOBALS['cal_value_service'];
                        echo zeroCurr($gross);
                    ?>
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