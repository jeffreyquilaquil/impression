<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = $GLOBALS['ad_u_id'];
$op = 1;
$opstr = 'Edit';
loadDBValues("vwpsi_users", "SELECT * FROM vwpsi_users WHERE u_id = ".$id);
loadFormCache('psi_users', 'confirm_password');

page_header('User Profile');

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Profile</h3>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
        <div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div>
        <?php } ?>
        <form method="POST" action="usersprofile_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form">

        <div class="form-group form-group-sm">
        <label for="ug_id" class="control-label">Region</label>
        <div>
            <?php echo $GLOBALS['u_region_name']; ?>
        </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="ug_id" class="control-label">Usergroup</label>
        <div>
            <?php echo $GLOBALS['ug_name']; ?>
        </div>
        </div>

        <div class="form-group">
        <label for="u_fname" class="control-label">First Name *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="First Name" maxlength="255" required="required" name="u_fname" id="u_fname" type="text" value="<?php echo $GLOBALS['u_fname']; ?>">
                </div>

        <div class="form-group">
        <label for="u_mname" class="control-label">Middle Name *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Middle Name" maxlength="255" required="required" name="u_mname" id="u_mname" type="text" value="<?php echo $GLOBALS['u_mname']; ?>">
		</div>

        <div class="form-group has-feedback">
        <label for="u_lname" class="control-label">Last Name *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Last Name" maxlength="255" required="required" name="u_lname" id="u_lname" type="text" value="<?php echo $GLOBALS['u_lname']; ?>">
                </div>

        <div class="form-group">
        <label for="u_email" class="control-label">Email *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Email" maxlength="255" required="required" name="u_email" id="u_email" type="email" value="<?php echo $GLOBALS['u_email']; ?>">
        </div>

        <div class="form-group">
        <label for="u_mobile" class="control-label">Mobile *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Mobile" maxlength="12" required="required" name="u_mobile" id="u_mobile" type="text" value="<?php echo $GLOBALS['u_mobile']; ?>">
        Ex. 639991234567
        </div>

        <div class="form-group">
        <label for="u_username" class="control-label">Username *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Username" maxlength="255" required="required" name="u_username" id="u_username" type="text" value="<?php echo $GLOBALS['u_username']; ?>">
        </div>

        <div class="form-group">
        <label for="u_password" class="control-label">Password <?php echo ($op == 0 ? '*': ''); ?></label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Password" maxlength="255" <?php echo ($op == 0 ? 'required="required"': ''); ?> name="u_password" id="u_password" type="password" value="">
        </div>

        <div class="form-group">
        <label for="confirm_password" class="control-label">Confirm Password <?php echo ($op == 0 ? '*': ''); ?></label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Confirm Password" maxlength="255" <?php echo ($op == 0 ? 'required="required"': ''); ?> name="confirm_password" id="confirm_password" type="password" value="">
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