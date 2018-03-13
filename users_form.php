<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'users.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'users.php');

if ($op == 1){
    if (!can_access('Users', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Users', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$GLOBALS['region_id'] = $GLOBALS['ad_u_region_id'];

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_users", "SELECT * FROM psi_users WHERE u_id = ".$id);
} else {
    initFormValues('psi_users', 'confirm_password');
    $GLOBALS['region_id'] = $GLOBALS['ad_u_region_id'];
}

loadFormCache('psi_users', 'confirm_password');

$sel_usergroups = getOptions('psi_usergroups', 'ug_name', 'ug_id', $GLOBALS['ug_id'], '', 'ORDER BY ug_name ASC');
$sel_regions = getOptions('vwpsi_regions', 'region_text', 'region_id', $GLOBALS['region_id'], '', 'ORDER BY region_text ASC');

$page_title = 'Users ('.$opstr.')';
page_header($page_title);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left"><?php echo $page_title; ?></h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="users.php" title="Users"><span class="fa fa-arrow-circle-left"></span> Back</a>        
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="users_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

            <?php
            if (strlen($GLOBALS['ad_ug_is_admin']) == 1){
                ?>
                <div class="form-group form-group-sm">
                    <label for="region_id" class="control-label">Region</label>
                    <select class="form-control input-sm" id="region_id" name="region_id">
                        <?php echo $sel_regions; ?>
                    </select>
                </div>
                <?php 
            }
            ?>

            <div class="form-group form-group-sm">
                <label for="ug_id" class="control-label">Usergroup</label>
                <select class="form-control input-sm" id="ug_id" name="ug_id">
                    <?php echo $sel_usergroups; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="u_fname" class="control-label">First Name *</label>

                <input class="form-control input-sm" placeholder="First Name" maxlength="255" required="required" name="u_fname" id="u_fname" type="text" value="<?php echo $GLOBALS['u_fname']; ?>">
            </div>

            <div class="form-group">
                <label for="u_mname" class="control-label">Middle Name</label>
                <input class="form-control input-sm" placeholder="Middle Name" maxlength="255" name="u_mname" id="u_mname" type="text" value="<?php echo $GLOBALS['u_mname']; ?>">
            </div>

            <div class="form-group has-feedback">
                <label for="u_lname" class="control-label">Last Name *</label>
                <input class="form-control input-sm" placeholder="Last Name" maxlength="255" required="required" name="u_lname" id="u_lname" type="text" value="<?php echo $GLOBALS['u_lname']; ?>">
            </div>

            <div class="form-group">
                <label for="u_email" class="control-label">Email</label>
                <input class="form-control input-sm" placeholder="Email" maxlength="255" name="u_email" id="u_email" type="email" value="<?php echo $GLOBALS['u_email']; ?>">
            </div>

            <div class="form-group">
                <label for="u_mobile" class="control-label">Mobile</label>
                <input class="form-control input-sm" placeholder="Mobile" maxlength="12" name="u_mobile" id="u_mobile" type="text" value="<?php echo $GLOBALS['u_mobile']; ?>">
                Ex. 639991234567
            </div>

            <div class="form-group">
                <label for="u_username" class="control-label">Username *</label>
                <input class="form-control input-sm" placeholder="Username" maxlength="255" required="required" name="u_username" id="u_username" type="text" value="<?php echo $GLOBALS['u_username']; ?>">
            </div>

            <div class="form-group">
                <label for="u_password" class="control-label">Password <?php echo ($op == 0 ? '*': ''); ?></label>
                <input class="form-control input-sm" placeholder="Password" maxlength="255" <?php echo ($op == 0 ? 'required="required"': ''); ?> name="u_password" id="u_password" type="password" value="">
            </div>

            <div class="form-group">
                <label for="confirm_password" class="control-label">Confirm Password <?php echo ($op == 0 ? '*': ''); ?></label>
                <input class="form-control input-sm" placeholder="Confirm Password" maxlength="255" <?php echo ($op == 0 ? 'required="required"': ''); ?> name="confirm_password" id="confirm_password" type="password" value="">
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="u_enabled" value="1" <?php echo checkBox($GLOBALS['u_enabled']); ?>> Account Enabled
                </label>
            </div>

            <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
            <input type="hidden" name="u_id" value="<?php echo $GLOBALS['u_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
page_footer();
?>