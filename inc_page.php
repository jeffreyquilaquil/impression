<?php
require_once("inc_conn.php");

function page_header($page_name = "", $submenu = 0, $container = true){
    ?><!DOCTYPE html>
<html lang='en' dir='ltr'>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <title><?php echo $page_name . " - " . WEBSITE_TITLE; ?></title>
    <link rel="shortcut icon" href="<?php echo WEBSITE_URL; ?>favicon.ico?v=6">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>chosen.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>tablesorter/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>bootstrap-datepicker3.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>bootstrap-datetimepicker.min.css" type="text/css">

    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>fonts.css" type="text/css">
<?php if ($GLOBALS['ad_loggedin'] == 0) { ?>
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>public.css" type="text/css">
<?php } else { ?>
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>private.css" type="text/css">
<?php } ?>
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>map-filter-panel.css" type="text/css">
</head>
<body>
    <div id="fb-root"></div>
    <?php if ($GLOBALS['ad_loggedin'] == 1) {
        navbar();
    } else { 
        navbar_public();
    }

    if ($container){ ?>
        <div id="body-wrapper" class="container-fluid">
    <?php }

    if ($submenu == 3) {
        page_settings_submenu();
    } elseif ($submenu == 2) {
        page_services_submenu();
    } elseif ($submenu == 1) {
        page_projects_submenu();
    } elseif ($submenu == 4) { ?>
        <div class="welcome">Welcome, <span><?php echo $GLOBALS['ad_u_username'];?></span>!</div>
    <?php }

}


function page_footer($container = true, $fb = true) {
    if ($container) { ?>
    </div>
    <?php }
    if ($fb) { ?>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="fb-comments" data-href="<?php echo DEF_FB_APP_URL; ?>" data-width="100%" data-numposts="5" data-order-by="reverse_time"></div>
        </div>
    </div>
    <?php } ?>

    <!-- BEGIN FOOTER -->
    <footer id="footer" class="text-center">
        <div class="container-fluid">
            Information &amp; Monitoring of Projects, Services and S&amp;T Interventions<br>
            &copy; 2015 by DOST Region 4A &middot; CALABARZON &middot; MIS Unit
        </div>
    </footer>

    <div id="confirm-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="confirm-modal-title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="confirm-modal-title" class="modal-title">Confirm Dialog Title</h4>
                </div>
                <div id="confirm-modal-message" class="modal-body text-center">Confirm Dialog Message</div>
                <div class="modal-footer">
                    <button type="button" id="confirm-btn-no" class="btn btn-primary" data-dismiss="modal">No</button>
                    <button type="button" id="confirm-btn-yes" class="btn btn-danger">Yes</button>
                </div>
            </div>
        </div>
    </div> <!-- / #info-modal -->
    <div id="info-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="info-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 id="info-modal-title" class="modal-title">Info Dialog Title</h4>
                </div>
                <div id="info-modal-message" class="modal-body">Info Dialog Message</div>
                <div class="modal-footer">
                    <button type="button" id="info-btn-close" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> <!-- / #info-modal -->


    <!-- END FOOTER -->
    <!--
    jquery-1.11.2.js
    jquery-2.1.1.min.js
-->
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=<?php echo DEF_FB_APP_ID; ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<script src="<?php echo JS_PATH; ?>jquery-2.1.3.min.js"></script>
<script src="<?php echo JS_PATH; ?>bootstrap.js"></script>
<script src="<?php echo JS_PATH; ?>bootstrap-datepicker.min.js"></script>
<script src="<?php echo JS_PATH; ?>moment-with-locales.js"></script>
<script src="<?php echo JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script src="<?php echo JS_PATH; ?>chosen.jquery.min.js"></script>
<script src="<?php echo JS_PATH; ?>jquery.tablesorter.min.js"></script>
<script src="<?php echo JS_PATH; ?>jquery.tablesorter.pager.js"></script>
<script src="<?php echo JS_PATH; ?>Chart.bundle.js"></script>
<script src="<?php echo JS_PATH; ?>impression.js?v=1"></script>
</body>
</html>
<?php
 }


function page_settings_submenu(){
    ?>
    <div id="nav-settings" class="subnav">
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>lu_activity_categories.php" title="Activity Categories">Activity Categories</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>lu_collaborators.php" title="Collaborating Agencies">Collaborating Agencies</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>lu_consultancy_types.php" title="Consultancy Types">Consultancy Types</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>lu_course_categories.php" title="Course Categories">Course Categories</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>lu_courses.php" title="Courses">Courses</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>lu_document_types.php" title="Document Types">Document Types</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>lu_brands.php" title="Equipment Names">Equipment Names</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>lu_expertise.php" title="Expertise">Expertise</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>lu_project_types.php" title="Project Types">Project Types</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>lu_sectors.php" title="Sector List">Sector List</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>lu_scholarship_programs.php" title="Scholarship Programs">Scholarship Programs</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>lu_schools.php" title="Schools">Schools</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>agencyprofile.php" title="Agency Profile">Agency Profile</a>
    </div>
    <?php
}

function page_projects_submenu(){
    global $pid;
    loadDBValues("vwpsi_projects", "SELECT * FROM vwpsi_projects WHERE prj_id = ".$pid);
    $timestamp = '
    Beneficiaries : '.getBeneficiaries($pid).'
    <br>
    Project Encoded on '.zeroDateTime($GLOBALS['date_encoded']).' by '.$GLOBALS['encoder'];
    /*
    <br>
    Last updated on '.zeroDateTime($GLOBALS['last_updated']).' by '.$GLOBALS['updater'];
    */

    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                Project # <span class="text-primary"><?php echo $GLOBALS['prj_code']; ?></span>
                <br>
                Project Name : <span class="text-primary"><?php echo $GLOBALS['prj_title']; ?></span>
                <br>
                <small>(<?php echo $GLOBALS['prj_type_name']; ?> Project)</small>
            </h3>
            <p>
                <?php echo $timestamp; ?>
            </p>
            <p>
                <a class="btn btn-primary" href="<?php echo WEBSITE_URL; ?>projects_view.php?pid=<?php echo $pid; ?>">Project Details</a>

                <?php
                if (can_access('Project PIS')){
                    ?>
                    <a class="btn btn-primary" href="<?php echo WEBSITE_URL; ?>project_pis.php?pid=<?php echo $pid; ?>">PIS</a>
                    <?php
                }

                if (can_access('Project Monitoring')){
                    ?>
                    <a class="btn btn-primary" href="<?php echo WEBSITE_URL; ?>project_monitoring.php?pid=<?php echo $pid; ?>">Monitoring</a>
                    <?php
                }

                if (can_access('Project Sites')){
                    if ($GLOBALS['prj_type_id'] == 8){
                        ?>
                        <a class="btn btn-primary" href="<?php echo WEBSITE_URL; ?>project_sites.php?pid=<?php echo $pid; ?>">Sites</a>
                        <?php
                    }
                }

                if (can_access('Project Documentation')){
                    ?>
                    <a class="btn btn-primary" href="<?php echo WEBSITE_URL; ?>project_documents.php?pid=<?php echo $pid; ?>">Documentation</a>
                    <?php
                }

                if (can_access('Project Photos')){
                    ?>
                    <a class="btn btn-primary" href="<?php echo WEBSITE_URL; ?>project_gallery.php?pid=<?php echo $pid; ?>">Photos</a>
                    <?php
                }

                if (can_access('Project Packaging & Labeling')){
                    ?>
                    <a class="btn btn-primary" href="<?php echo WEBSITE_URL; ?>project_packaging.php?pid=<?php echo $pid; ?>">Packaging &amp; Labeling</a>
                    <?php
                }

                if (can_access('Project Consultancies')){
                    ?>
                    <a class="btn btn-primary" href="<?php echo WEBSITE_URL; ?>project_consultancies.php?pid=<?php echo $pid; ?>">Consultancies</a>
                    <?php
                }

                if (can_access('Project Fora')){
                    ?>
                    <a class="btn btn-primary" href="<?php echo WEBSITE_URL; ?>project_trainings.php?pid=<?php echo $pid; ?>">Fora/Trainings/Seminars</a>
                    <?php
                }

                if (can_access('Project Equipment')){
                    ?>
                    <a class="btn btn-primary" href="<?php echo WEBSITE_URL; ?>project_equipment.php?pid=<?php echo $pid; ?>">Equipment</a>
                    <?php
                }

                if (can_access('Project Repayment')){
                    ?>
                    <a class="btn btn-primary" href="<?php echo WEBSITE_URL; ?>project_repayments.php?pid=<?php echo $pid; ?>">Repayment</a>
                    <?php
                }

                ?>
            </p>
        </div>
    </div>
    <?php
}

function page_services_submenu(){
    return;
    ?>
    <div id="nav-services" class="subnav">
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>consultancies.php" title="Consultancies">Consultancies</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>packaging.php" title="Packaging &amp; Labeling">Packaging &amp; Labeling</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>trainings.php" title="Trainings">Trainings</a>

        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>calibrations.php" title="Testing &amp; Calibrations">Testing &amp; Calibrations</a>

        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>scholarships.php" title="Scholarships">Scholarships</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>scholarship-monitoring.php" title="Scholarship Monitoring">Scholarship Monitoring</a>

        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>activities.php" title="Media Activities">Media Activities</a>
        <a class="btn btn-default btn-xs" href="<?php echo WEBSITE_URL; ?>library.php" title="Library">Library</a>
    </div>
    <?php
}


function print_header($page_name = "", $submenu = 0) {
    ?><!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo $page_name . " - " . WEBSITE_TITLE; ?></title>
    <link rel="shortcut icon" href="<?php echo WEBSITE_URL; ?>favicon.ico">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>chosen.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>tablesorter/style.css" type="text/css">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>bootstrap-datepicker3.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>roboto.css" type="text/css">
    <link rel="stylesheet" href="<?php echo CSS_PATH; ?>print.css" type="text/css">
</head>
<body>
    <div id="header-wrapper" class="container-fluid">
        <header id="header">
            <img class="img-responsive" src="images/impression.png" alt="Impression">
        </header>
    </div>
    <?php if ($submenu == 1) { 
        print_projects_submenu();
    }
}


function print_footer() { ?>
    <script src="<?php echo JS_PATH; ?>jquery-2.1.3.min.js"></script>
    <script src="<?php echo JS_PATH; ?>bootstrap.min.js"></script>
    <script src="<?php echo JS_PATH; ?>bootstrap-datepicker.min.js"></script>
    <script src="<?php echo JS_PATH; ?>moment-with-locales.js"></script>
    <script src="<?php echo JS_PATH; ?>bootstrap-datetimepicker.js"></script>
    <script src="<?php echo JS_PATH; ?>chosen.jquery.min.js"></script>
    <script src="<?php echo JS_PATH; ?>jquery.tablesorter.min.js"></script>
    <script src="<?php echo JS_PATH; ?>jquery.tablesorter.pager.js"></script>
    <script src="<?php echo JS_PATH; ?>impression.js"></script>
</body>
</html>
<?php 
}

function print_projects_submenu() {
    global $pid;
    loadDBValues("vwpsi_projects", "SELECT * FROM vwpsi_projects WHERE prj_id = ".$pid);
    $timestamp = '
    Beneficiaries : '.getBeneficiaries($pid).'
    <br>
    Encoded on '.zeroDateTime($GLOBALS['date_encoded']).' by '.$GLOBALS['encoder'].'
    <br>
    Last updated on '.zeroDateTime($GLOBALS['last_updated']).' by '.$GLOBALS['updater'];

    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                Project # <span class="text-primary"><?php echo $GLOBALS['prj_code']; ?></span>
                <br>
                Project Name : <span class="text-primary"><?php echo $GLOBALS['prj_title']; ?></span>
                <br>
                <small>(<?php echo $GLOBALS['prj_type_name']; ?> Project)</small>
            </h3>
            <p>
                <?php echo $timestamp; ?>
            </p>
        </div>
    </div>
    <?php
}

function download_header($page_name = "", $submenu = 0) { 
    ?><!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo $page_name . " - " . WEBSITE_TITLE; ?></title>
</head>
<body>
<?php 
}

function download_footer(){ ?>
</body>
</html> 
<?php 
}

function navbar() {
?>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo WEBSITE_URL; ?>index.php" title="Impression">
                <img src="<?php echo IMAGES_PATH; ?>brand_white.png">
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <?php if (can_access('Projects')) { ?>
                <li class="drop-down">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Projects"><span class="fa fa-industry"></span> Projects <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?php echo WEBSITE_URL; ?>projects.php" title="All Projects">All Projects</a></li>
                        <li><a href="<?php echo WEBSITE_URL; ?>project_monitoring_summary.php" title="Status Reports">Status Reports</a></li>
                        <li><a href="<?php echo WEBSITE_URL; ?>project_summaries.php" title="Summaries"> Summaries</a></li>
                        <!-- <li><a href="<?php echo WEBSITE_URL; ?>projects_notices.php" title="Project Notices"> Project Notices</a></li> -->
                    </ul>                    
                </li>
                <?php }

                $services_menu = array();
                $services_menu[] = 'Consultancies';
                $services_menu[] = 'Packaging & Labeling';
                $services_menu[] = 'Trainings';
                $services_menu[] = 'Fora';
                $services_menu[] = 'Testings & Calibrations';
                $services_menu[] = 'Scholarships';
                $services_menu[] = 'Scholarship Monitoring';
                $services_menu[] = 'Media Activities';
                $services_menu[] = 'Library Monitoring';

                if (can_access_any($services_menu)) { ?>
                <li class="drop-down">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Services"><span class="fa fa-wrench"></span> Services <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <?php if (can_access('Consultancies')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>consultancies.php" title="Consultancies">Consultancies</a></li>
                        <?php }
                        if (can_access('Packaging & Labeling')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>packaging.php" title="Packaging &amp; Labeling">Packaging &amp; Labeling</a></li>
                        <?php }
                        if (can_access('Fora')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>fora.php" title="Fora/Trainings/Seminars">Fora/Training/Seminars</a></li>
                        <?php }
                        if (can_access('Testings & Calibrations')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>calibrations.php" title="Testing &amp; Calibrations">Testings &amp; Calibrations</a></li>
                        <?php }
                        if (can_access('Scholarships')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>scholarships.php" title="Scholarships">Scholarships</a></li>
                        <?php }
                        if (can_access('Scholarship Monitoring')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>scholarship_monitoring.php" title="Scholarship Monitoring">Scholarship Monitoring</a></li>
                        <?php }
                        if (can_access('Media Activities')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>activities.php" title="Media Activities">Media Activities</a></li>
                        <?php }
                        if (can_access('Library Monitoring')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>library.php" title="Library">Library Monitoring</a></li>
                        <?php } ?>
                    </ul>
                </li>
                <?php }

                $contacts_menu = array();
                $contacts_menu[] = 'Cooperators';
                $contacts_menu[] = 'Service Providers';

                if (can_access_any($contacts_menu)){ ?>
                <li class="drop-down">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Contacts"><span class="fa fa-address-card"></span> Contacts <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <?php if (can_access('Cooperators')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>cooperators.php" title="Cooperators / Beneficiaries">Cooperators / Beneficiaries</a></li>
                        <?php }
                        if (can_access('Service Providers')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>service_providers.php" title="Service Providers">Service Providers</a></li>
                        <?php } ?>
                    </ul>                    
                </li>
                <?php } 

                $settings_menu = array();
                $settings_menu[] = 'Media Activity Categories';
                $settings_menu[] = 'Collaborating Agencies';
                $settings_menu[] = 'Consultancy Categories';
                $settings_menu[] = 'Document Categories';
                $settings_menu[] = 'Equipment Names';
                $settings_menu[] = 'Organization Categories';
                $settings_menu[] = 'Project Categories';
                $settings_menu[] = 'Sectors';
                $settings_menu[] = 'Location Listings';
                $settings_menu[] = 'Course Categories';
                $settings_menu[] = 'Courses';
                $settings_menu[] = 'Scholarship Programs';
                $settings_menu[] = 'Schools';
                $settings_menu[] = 'Users';
                $settings_menu[] = 'User Groups';
                $settings_menu[] = 'Agency Profile';

                if (can_access_any($settings_menu)) { ?>
                <li class="drop-down">
                    <a href="<?php echo WEBSITE_URL; ?>#" class="dropdown-toggle" data-toggle="dropdown" title="Settings"><span class="fa fa-cog"></span> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <?php if (can_access('Media Activity Categories')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>lu_activity_categories.php" title="Media Activity Categories">Media Activity Categories</a></li>
                        <?php } 

                        $divider1 = array();
                        $divider1[] = 'Collaborating Agencies';
                        $divider1[] = 'Consultancy Categories';
                        $divider1[] = 'Document Categories';
                        $divider1[] = 'Equipment Names';
                        $divider1[] = 'Location Listings';
                        $divider1[] = 'Organization Categories';
                        $divider1[] = 'Project Categories';
                        $divider1[] = 'Sectors';

                        if (can_access('Media Activity Categories') && can_access_any($divider1)) { ?>
                        <li class="divider" role="presentation"></li>
                        <?php }
                        if (can_access('Collaborating Agencies')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>lu_collaborators.php" title="Collaborating Agencies">Collaborating Agencies</a></li>
                        <?php }
                        if (can_access('Consultancy Categories')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>lu_consultancy_types.php" title="Consultancy Categories">Consultancy Categories</a></li>
                        <?php }
                        if (can_access('Document Categories')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>lu_document_types.php" title="Document Categories">Document Categories</a></li>
                        <?php } 
                        if (can_access('Equipment Names')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>lu_brands.php" title="Equipment Names">Equipment Names</a></li>
                        <?php }
                        if (can_access('Location Listings')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>lu_location_regions.php" title="Location Listings">Location Listings</a></li>
                        <?php }
                        if (can_access('Organization Categories')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>lu_organization_types.php" title="Organization Categories">Organization Categories</a></li>
                        <?php }
                        if (can_access('Project Categories')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>lu_project_types.php" title="Project Categories">Project Categories</a></li>
                        <?php }
                        if (can_access('Sectors')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>lu_sectors.php" title="Sectors">Sectors</a></li>
                        <?php }

                        $divider2 = array();
                        $divider2[] = 'Course Categories';
                        $divider2[] = 'Courses';
                        $divider2[] = 'Scholarship Programs';
                        $divider2[] = 'Schools';

                        if (can_access_any($divider1) && can_access_any($divider2)) { ?>
                        <li class="divider" role="presentation"></li>
                        <?php }
                        if (can_access('Course Categories')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>lu_course_categories.php" title="Course Categories">Course Categories</a></li>
                        <?php }
                        if (can_access('Courses')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>lu_courses.php" title="Courses">Courses</a></li>
                        <?php }
                        if (can_access('Scholarship Programs')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>lu_scholarship_programs.php" title="Scholarship Programs">Scholarship Programs</a></li>
                        <?php }
                        if (can_access('Schools')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>lu_schools.php" title="Schools">Schools</a></li>
                        <?php }

                        $divider3 = array();
                        $divider3[] = 'Users';
                        $divider3[] = 'UserGroups';
                        if (can_access_any($divider2) && can_access_any($divider3)) { ?>
                        <li class="divider" role="presentation"></li>
                        <?php }
                        if (can_access('Users')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>users.php" title="Logout">Users</a></li>
                        <?php }
                        if (can_access('UserGroups')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>usergroups.php" title="Logout">User Groups</a></li>
                        <?php } 
                        if (can_access_any($divider3) && can_access('Agency Profile')) { ?>
                        <li class="divider" role="presentation"></li>
                        <?php } 
                        if (can_access('Agency Profile')) { ?>
                        <li><a href="<?php echo WEBSITE_URL; ?>agency_view.php" title="Agency Profile">Agency Profile</a></li>
                        <?php } ?>
                    </ul>
                </li>
                <?php } ?>
                <li class="drop-down">
                    <a href="<?php echo WEBSITE_URL; ?>#" class="dropdown-toggle" data-toggle="dropdown" title="Settings"><span class="fa fa-user-circle"></span> <?php echo $GLOBALS['ad_u_username']; ?><span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?php echo WEBSITE_URL; ?>usersprofile_form.php" title="Logout"><span class="fa fa-user"></span> Profile</a></li>
                        <li><a href="<?php echo WEBSITE_URL; ?>logout.php" title="Logout"><span class="fa fa-power-off"></span> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php 
}

function navbar_public(){
?>
    <div id="header" class="container-fluid">
        <a href="index.php" title="Impression"> <img class="header-icon" src="<?php echo IMAGES_PATH ?>site-icon.png" alt="Impression"> <span class="header-title">Impression</span></a>
    </div>
    <?php if ($GLOBALS['under_maintenance'] == 1) { ?>
    <div class="container-fluid">
        <div class="maintenance_msg alert alert-danger">Service is currently under maintenace.</div>
    </div>
    <?php } ?>
<?php
}


?>
