<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'service_providers.php?dedo=1');

if (!dbValueExists('psi_service_providers', 'sp_id', $pid, false)){
    redirect(WEBSITE_URL.'service_providers.php');
    die();
}

loadDBValues("vwpsi_service_providers", "SELECT * FROM vwpsi_service_providers WHERE sp_id = ".$pid);


$nos = '';
$tmp = trim($GLOBALS['sp_phone'].'');
if (strlen($tmp) > 0){
    $nos .= 'Phone : '.$tmp;
}

$tmp = trim($GLOBALS['sp_mobile'].'');
if (strlen($tmp) > 0){
    if (strlen($nos) > 0){
        $nos .= '<br>';
    }
    $nos .= 'Mobile : '.$tmp;
}

$addr = trim($GLOBALS['sp_address'].'');
$addr = nl2br($addr);
$services = get_services($GLOBALS['sp_id']);
if (strlen($GLOBALS['sp_other_service']) > 0){
    if (strlen($services)){
        $services .= '<br>';
    }
    $services .= $GLOBALS['sp_other_service'];
}

page_header('Service Providers (Details)');
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title">Service Providers (Details) </h3>
            <div>
                <h3 class="detail-name text-primary">
                <?php echo $GLOBALS['sp_name']; ?>
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
            <a class="btn btn-primary btn-sm" href="service_providers.php" title="Service Providers"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <div class="row-fluid">
            <h5>Services Provided</h5>
            <div class="well well-sm">
                <?php echo $services; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Field of Expertise</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['sp_expertise'].''; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Product Lines</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['sp_product_line'].''); ?>
            </div>
        </div>

       <div class="row-fluid">
            <h3><span class="label label-default full-width">
                Contact Information
            </span></h3>

            <h5>Contact Person</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['sp_contact_name'].''; ?><br>
                (<?php echo $GLOBALS['sp_designation'].''; ?>)
            </div>

            <h5>Address</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['sp_address'].''; ?>
            </div>

            <h5>Phone</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['sp_phone'].''; ?>
            </div>

            <h5>Mobile</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['sp_mobile'].''; ?>
            </div>

            <h5>Email</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['sp_email'].''; ?>
            </div>

            <h5>Website</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['sp_website'].''; ?>
            </div>
        </div>

    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
    function get_services($id){
        $sql = "SELECT * FROM vwpsi_service_provider_services WHERE sp_id = $id";
        $res = mysqli_query($GLOBALS['cn'], $sql);

        if (!$res) return '';
        $s = '';
        while ($row = mysqli_fetch_array($res)){
            if (strlen($s) > 0){
                $s .= '<br>';
            }
            $s .= $row['service_name'];
        }
        mysqli_free_result($res);
        return $s;
    }
?>