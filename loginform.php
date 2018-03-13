<?php
require_once ( 'inc_page.php' );
if ($GLOBALS['ad_loggedin'] == 1){
    redirect('index.php');
    die();
}

page_header('Login', 0, false);
?>

<div class="loginform-wrapper">
    <div class="loginform-content">
        <form id="loginform" class="form" method="post" action="login.php">    
        <div class="panel panel-default">
            <div class="panel-heading">
                <span>Please Sign-In</span>
            </div>
            <div class="panel-body">
                <?php if (strlen($GLOBALS['errmsg']) > 0){ ?>
                    <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
                <?php } ?>

                <div class="form-group has-feedback">
                    <div class="input-group">
                        <span class="input-group-addon">Username</span>
                        <input class="form-control" placeholder="Username" required="required" name="username" type="text">
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <div class="input-group">
                        <span class="input-group-addon">Password</span>
                        <input class="form-control" placeholder="Password" required="required" name="password" type="password" value="">
                    </div>
                </div>
                <div class="form-group">
                    <input class="btn btn-primary btn-block" type="submit" value="Sign-in">
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<?php
page_footer(false, false);
?>