<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Agency Profile', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

if (!dbValueExists('psi_agencies', 'agency_id', 1, false)){
    die();
}

loadDBValues("psi_agencies", "SELECT * FROM psi_agencies WHERE agency_id = 1");

page_header('Agency Profile');
$action = '';

if (can_access('Agency Profile', 'edit')){
    $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'agency_form.php" title="Edit Profile"><span class="fa fa-pencil"></span> Edit Profile</a>';
}

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title">Agency Profile</h3>
            <h3 class="detail-name text-primary">
                <?php echo $GLOBALS['agency_name']; ?>
                (<?php echo $GLOBALS['agency_acronym']; ?>)
            </h3>
            <?php 
                if (strlen($GLOBALS['date_encoded']) > 0){
            ?>
            <p>
                <small>
                Encoded on <?php echo zeroDateTime($GLOBALS['date_encoded']); ?> by <?php echo $GLOBALS['encoder']; ?><br>
                Last updated on <?php echo zeroDateTime($GLOBALS['last_updated']); ?> by <?php echo $GLOBALS['updater']; ?>
                </small>
            </p>
            <?php
                }
            ?>
        </div>
        <div class="pull-right">
            <?php echo $action; ?>
        </div>
    </div>
    <div class="panel-body">
            <?php 
                if (strlen($GLOBALS['agency_file']) > 0){
                   echo '
                       <img style="margin:auto;" class="img-responsive" src="'.AGENCY_LINK_PATH.$GLOBALS['agency_file'].'" alt="'.$GLOBALS['agency_filename'].'" >
                   ';
                }
            ?>
        <div class="row-fluid">
            <h5>Address</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['agency_address'].''); ?>
            </div>

            <h5>Contact</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['agency_contact'].''; ?>
            </div>

            <h5>Phone</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['agency_phone'].''; ?>
            </div>

            <h5>Mobile</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['agency_mobile'].''; ?>
            </div>

            <h5>Email</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['agency_email'].''; ?>
            </div>

            <h5>Website</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['agency_website'].''; ?>
            </div>
        </div>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>