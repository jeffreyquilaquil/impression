<?php
require_once("inc_conn.php");

function page_header($page_name = "", $submenu = 0, $container = true){
?><!DOCTYPE html>
    <html>
    <head>
        <meta name="charset" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title><?php echo $page_name . " - " . WEBSITE_TITLE; ?></title>
        <link rel="shortcut icon" href="<?php echo WEBSITE_URL; ?>favicon.ico?v=6">
        <link rel="stylesheet" href="<?php echo CSS_PATH; ?>chosen.min.css" type="text/css">
        <link rel="stylesheet" href="<?php echo CSS_PATH; ?>tablesorter/style.css" type="text/css">
        <link rel="stylesheet" href="<?php echo CSS_PATH; ?>bootstrap.min.css" type="text/css">
        <link rel="stylesheet" href="<?php echo CSS_PATH; ?>bootstrap-datepicker3.min.css" type="text/css">
        <link rel="stylesheet" href="<?php echo CSS_PATH; ?>bootstrap-datetimepicker.min.css" type="text/css">
        <link rel="stylesheet" href="<?php echo CSS_PATH; ?>font-awesome.min.css" type="text/css">
        <link rel="stylesheet" href="<?php echo CSS_PATH; ?>fonts.css" type="text/css">
        <link rel="stylesheet" href="<?php echo CSS_PATH; ?>public.css" type="text/css">
        <link rel="stylesheet" href="<?php echo CSS_PATH; ?>map-filter-panel.css" type="text/css">
    </head>
    <body>
    <nav class="navbar navbar-default">

    </nav>
<?php
    }
    function page_footer($container = true, $fb = true){
?>
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
        </div>
        <!-- / #info-modal -->
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
        </div>
        <!-- / #info-modal -->


        <!-- END FOOTER -->
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
?>
