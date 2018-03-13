<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Packaging & Labeling', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'packaging.php?dedo=1');

if (!dbValueExists('psi_packaging', 'pkg_id', $pid, false)){
    redirect(WEBSITE_URL.'packaging.php');
    die();
}

loadDBValues("vwpsi_packaging", "SELECT * FROM vwpsi_packaging WHERE pkg_id = ".$pid);

$page_title = 'Packaging &amp; Labeling Details';
page_header($page_title, 2);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title"><?php echo $page_title; ?></h3>
            <div>
                <h4>
                <?php
                    echo $GLOBALS['pkg_product_name'];
                    if (strlen($GLOBALS['pkg_brand_name']) > 0){
                        echo ' ('.$GLOBALS['pkg_brand_name'].')'; 
                    }
                ?>
                </h4>
            </div>
            <div><h5><?php echo $GLOBALS['coop_name']; ?></h5></div>
        </div>
        <div class="pull-right">

            <?php
            if (can_access('Packaging & Labeling Designs', 'view')){
            ?>
            <a class="btn btn-primary btn-sm" href="packaging_designs.php?pid=<?php echo $pid; ?>" title="View Designs"><span class="fa fa-file-image-o"></span> View Designs</a>
            <?php
            }
            ?>
            <a class="btn btn-primary btn-sm" href="packaging.php" title="Packaging &amp; Labeling"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">
        <div class="row-fluid">
            <h5>Product Description</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['pkg_product_description']); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>What are the product's major competitors?</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['pkg_competitors']); ?>
            </div>

            <h5>What is your product's selling point?</h5>
            <div class="well well-sm">
                <?php
                    if ($GLOBALS['pkg_selling_point'] < 6){
                        echo $GLOBALS['sp_label'];
                    } else {
                        echo nl2br($GLOBALS['pkg_selling_point_others']); 
                    }

                ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>How does your product perform against it's competitors in terms of sales?</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['pkg_performance']); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Where do you intend to sell your product?</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['dist_label']; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h3 ><span class="label label-default full-width">
                If Applicable (Food, Chemicals...etc)
            </span></h3>
            <h5>Ingredients</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['pkg_ingredients']); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Net Weight/Volume</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['pkg_volume']; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Packaging Material to be used</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['pkg_packaging_material']; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Size of Label/Box</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['pkg_label_size']; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Preferred Colors</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['pkg_preferred_colors']; ?>
            </div>
        </div>

        <div class="row-fluid">
            <h5>Other Specifications/Preference</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['pkg_other_details']); ?>
            </div>
        </div>

        <div class="row-fluid">
            <h3 ><span class="label label-default full-width">
                New Markets Penetrated
            </span></h3>

            <div class="col-sm-4">
                <h5>Location</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['pkg_market_location']; ?>
                </div>
            </div>

            <div class="col-sm-4">
                <h5>Products Sold</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['pkg_market_products_sold']; ?>
                </div>
            </div>

            <div class="col-sm-4">
                <h5>Date Established</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['pkg_market_date_established']; ?>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <h3 ><span class="label label-default full-width">
                Sales
            </span></h3>
            <div class="col-sm-6">
                <h5>Before Intervention</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['pkg_sales_before_intervention']; ?>
                </div>
            </div>

            <div class="col-sm-6">
                <h5>After Intervention</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['pkg_sales_after_intervention']; ?>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <h3 ><span class="label label-default full-width">
                Employment After
            </span></h3>

            <div class="col-sm-4">
                <h5>Direct</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['pkg_employment_after_direct']; ?>
                </div>
            </div>

            <div class="col-sm-4">
                <h5>Indirect</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['pkg_employment_after_indirect']; ?>
                </div>
            </div>

            <div class="col-sm-4">
                <h5>Months Employed</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['pkg_employment_after_months_employed']; ?>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <h3 ><span class="label label-default full-width">
                Average Productivity Improvement
            </span></h3>
            <div class="col-sm-12">
                <div class="well well-sm">
                    <?php echo $GLOBALS['pkg_avg_productivity_improvement']; ?>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <h3 ><span class="label label-default full-width">
                Document/image Attachment
            </span></h3>
            <div class="col-sm-12">
                <div class="well well-sm">
                    <?php
                    if (strlen($GLOBALS['pkg_file']) > 0){
                        echo '<a href="'.PACKAGING_DOCS_LINK_PATH.$GLOBALS['pkg_file'].'" title="'.$GLOBALS['pkg_filename'].'" target="_blank">'.$GLOBALS['pkg_filename'].'</a>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <h3 ><span class="label label-default full-width">
                Remarks
            </span></h3>
            <div class="col-sm-12">
                <div class="well well-sm">
                    <?php echo nl2br($GLOBALS['pkg_remarks']); ?>
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