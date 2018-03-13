<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Agency Profile', 'edit')){
    redirect(WEBSITE_URL.'index.php');
}

$id = 1;

if (!dbValueExists('psi_agencies', 'agency_id', $id, false)){
    redirect(WEBSITE_URL.'agency_view.php');
    die();
}

loadDBValues("psi_agencies", "SELECT * FROM psi_agencies WHERE agency_id = ".$id);
loadFormCache('psi_agencies');
$page_title = 'Agency Profile (Edit)';
page_header($page_title, 0);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-sm" href="agency_view.php" title="Agency Profile"><span class="fa fa-arrow-circle-left"></span> Back</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>

        <form method="POST" action="agency_save.php" accept-charset="UTF-8" class="form" role="form" enctype="multipart/form-data">

        <div class="form-group">
            <label for="agency_file" class="control-label">Logo
                <br>
            Current File : <a href="<?php echo AGENCY_LINK_PATH.$GLOBALS['agency_file']; ?>" title="<?php echo $GLOBALS['agency_filename']; ?>" target="blank"><?php echo $GLOBALS['agency_filename']; ?></a>
            </label>

            <input class="form-control input-sm" placeholder="Document" name="agency_file" id="agency_file" type="file" accept="image/*">
        </div>

        <div class="form-group has-feedback">
        <label for="agency_name" class="control-label">Agency Name *</label>
        <input class="form-control input-sm" placeholder="Agency Name" maxlength="255" required="required" name="agency_name" id="agency_name" type="text" value="<?php echo $GLOBALS['agency_name']; ?>">
        </div>

        <div class="form-group has-feedback">
        <label for="agency_acronym" class="control-label">Acronym *</label>
        <input class="form-control input-sm" placeholder="Acronym" maxlength="255" required="required" name="agency_acronym" id="agency_acronym" type="text" value="<?php echo $GLOBALS['agency_acronym']; ?>">
        </div>

        <div class="form-group">
            <label for="agency_address" class="control-label">Address</label>
            <textarea class="form-control input-sm" placeholder="Address" name="agency_address" id="agency_address" cols="50" rows="4"><?php echo $GLOBALS['agency_address']; ?></textarea>
        </div>


        <div class="form-group has-feedback">
        <label for="agency_contact" class="control-label">Contact</label>
        <input class="form-control input-sm" placeholder="Contact" maxlength="255" name="agency_contact" id="agency_contact" type="text" value="<?php echo $GLOBALS['agency_contact']; ?>">
        </div>

        <div class="form-group has-feedback">
        <label for="agency_phone" class="control-label">Phone</label>
        <input class="form-control input-sm" placeholder="Phone" maxlength="255" name="agency_phone" id="agency_phone" type="text" value="<?php echo $GLOBALS['agency_phone']; ?>">
        </div>

        <div class="form-group has-feedback">
        <label for="agency_mobile" class="control-label">Mobile</label>
        <input class="form-control input-sm" placeholder="Mobile" maxlength="255" name="agency_mobile" id="agency_mobile" type="text" value="<?php echo $GLOBALS['agency_mobile']; ?>">
        </div>

        <div class="form-group has-feedback">
        <label for="agency_email" class="control-label">Email</label>
        <input class="form-control input-sm" placeholder="Email" maxlength="255" name="agency_email" id="agency_email" type="email" value="<?php echo $GLOBALS['agency_email']; ?>">
        </div>

        <div class="form-group has-feedback">
        <label for="agency_website" class="control-label">Website</label>
        <input class="form-control input-sm" placeholder="Website" maxlength="255" name="agency_website" id="agency_website" type="text" value="<?php echo $GLOBALS['agency_website']; ?>">
        </div>

        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        </form>

    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>