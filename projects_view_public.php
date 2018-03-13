<?php
require_once('inc_page.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'index.php');
    die();
}

loadDBValues("vwpsi_projects", "SELECT * FROM vwpsi_projects WHERE prj_id = ".$pid);
$timestamp = '
                Beneficiaries : '.getBeneficiaries($pid).'
                <br>
                Encoded on '.zeroDateTime($GLOBALS['date_encoded']).' by '.$GLOBALS['encoder'].'
                <br>
                Last updated on '.zeroDateTime($GLOBALS['last_updated']).' by '.$GLOBALS['updater'];

$page_title = 'Project Details';
page_header($page_title, 0);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="row-fluid">
            <h5>Project Code</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['prj_code'].''; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Project Type</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['prj_type_name']; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Beneficiaries</h5>
            <div class="well well-sm">
                <?php echo get_beneficiaries($pid); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Collaborating Agencies</h5>
            <div class="well well-sm">
                <?php echo get_collaborators($pid); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Implementor</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['ug_name']; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Year Approved</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['prj_year_approved']; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Objective</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['prj_objective'].''); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Expected Output</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['prj_expected_output'].''); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Products</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['prj_product_line'].''); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Date Funds Released To The Beneficiary</h5>
            <div class="well well-sm">
                <?php echo zeroDate($GLOBALS['prj_fund_release_date'].''); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Refund Period</h5>
            <div class="well well-sm">
                <?php echo zeroDate($GLOBALS['prj_refund_period_from'].''); ?> to <?php echo zeroDate($GLOBALS['prj_refund_period_to'].''); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Project Status</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['prj_status_name'].''); ?>
            </div>
        </div>
       <div class="row-fluid">
            <h3><span class="label label-default full-width">
                Sectors
            </span></h3>
            <div class="well well-sm">
                <?php echo $GLOBALS['prj_sector_list'].''; ?>
            </div>
        </div>

       <div class="row-fluid">
            <h3><span class="label label-default full-width">
                Project Location
            </span></h3>

            <h5>Address</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['prj_address'].''); ?>
            </div>

            <h5>Province</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['province_name'].''; ?>
            </div>

            <h5>City/Town</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['city_name'].''; ?>
            </div>

            <h5>Barangay</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['barangay_name'].''; ?>
            </div>
        </div>

       <div class="row-fluid">
            <h3><span class="label label-default full-width">
                Project Costs
            </span></h3>

            <h5>SETUP Project Cost</h5>
            <div class="well well-sm text-right">
                <?php echo zeroCurr($GLOBALS['prj_cost_setup'].''); ?>
            </div>

            <h5>GIA Project Cost</h5>
            <div class="well well-sm text-right">
                <?php echo zeroCurr($GLOBALS['prj_cost_gia'].''); ?>
            </div>

            <h5>Roll-Out Project Cost</h5>
            <div class="well well-sm text-right">
                <?php echo zeroCurr($GLOBALS['prj_cost_rollout'].''); ?>
            </div>

            <h5>Beneficiaries&rsquo; Counterpart Project Cost</h5>
            <div class="well well-sm text-right">
                <?php echo zeroCurr($GLOBALS['prj_cost_benefactor'].''); ?>
            </div>

            <h5>Other Project Cost</h5>
            <div class="well well-sm text-right">
                <?php echo zeroCurr($GLOBALS['prj_cost_other'].''); ?>
            </div>
        </div>

       <div class="row-fluid">
            <h3><span class="label label-default full-width">
                Project Map Coordinates
            </span></h3>

            <h5>Longitude</h5>
            <div class="well well-sm text-right">
                <?php echo $GLOBALS['prj_longitude'].''; ?>
            </div>

            <h5>Latitude</h5>
            <div class="well well-sm text-right">
                <?php echo $GLOBALS['prj_latitude'].''; ?>
            </div>

            <h5>Elevation</h5>
            <div class="well well-sm text-right">
                <?php echo $GLOBALS['prj_elevation'].''; ?>
            </div>
        </div>

    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();

    function get_beneficiaries($id){
        $sql = "SELECT * FROM vwpsi_project_beneficiaries WHERE prj_id = $id";
        $res = mysqli_query($GLOBALS['cn'], $sql);

        if (!$res) return '';
        $s = '';
        while ($row = mysqli_fetch_array($res)){
            if (strlen($s) > 0){
                $s .= '<br>';
            }
            $s .= $row['coop_name'];
        }
        mysqli_free_result($res);
        return $s;
    }

    function get_collaborators($id){
        $sql = "SELECT * FROM vwpsi_project_collaborators WHERE prj_id = $id";
        $res = mysqli_query($GLOBALS['cn'], $sql);

        if (!$res) return '';
        $s = '';
        while ($row = mysqli_fetch_array($res)){
            if (strlen($s) > 0){
                $s .= '<br>';
            }
            $s .= '<abbr title="'.$row['col_name'].'">'.$row['col_abbr'].'</abbr>';
        }
        mysqli_free_result($res);
        return $s;
    }
?>;