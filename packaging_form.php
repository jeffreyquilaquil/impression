<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$id = requestInteger('id', 'location: '.WEBSITE_URL.'packaging.php');
$op = requestInteger('op', 'location: '.WEBSITE_URL.'packaging.php');

if ($op == 1){
    if (!can_access('Packaging & Labeling', 'edit')){
        redirect(WEBSITE_URL.'index.php');
    }
} else {
    if (!can_access('Packaging & Labeling', 'add')){
        redirect(WEBSITE_URL.'index.php');
    }
}

$opstr = 'Add';
if ($op == 1){
    $opstr = 'Edit';
    loadDBValues("psi_packaging", "SELECT * FROM psi_packaging WHERE pkg_id = ".$id);
} else {
    initFormValues('psi_packaging');
    $GLOBALS['pkg_selling_point'] = 1;
    $GLOBALS['pkg_distribution'] = 1;
}
loadFormCache("psi_packaging");

$sel_coops = getOptions('psi_cooperators', 'coop_name', 'coop_id', $GLOBALS['coop_id'], '', 'ORDER BY coop_name ASC');

page_header('Packaging &amp; Labeling ('.$opstr.')', 2);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Packaging &amp; Labeling (<?php echo $opstr; ?>) </h3>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="packaging.php" title="Packaging &amp; Labeling"><span class="fa fa-arrow-circle-left"></span> Back</a>        
        </div>
    </div>
    <div class="panel-body">
        <?php if (strlen($GLOBALS['errmsg']) > 0){
                ?><div class="alert alert-danger"><?php echo $GLOBALS['errmsg']; ?></div><?php
            } ?>
        <form method="POST" action="packaging_save.php?op=<?php echo $op; ?>&amp;id=<?php echo $id; ?>" accept-charset="UTF-8" class="form" role="form" enctype="multipart/form-data">
        <div class="form-group form-group-sm">
        <label for="coop_id" class="control-label">Cooperators / Beneficiary</label>
        <select class="form-control input-sm" id="coop_id" name="coop_id">
        <?php echo $sel_coops; ?>
        </select>
        </div>
    
        <div class="form-group has-feedback">
        <label for="pkg_product_name" class="control-label">Product Name *</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Project Name" maxlength="255" required="required" name="pkg_product_name" id="pkg_product_name" type="text" value="<?php echo $GLOBALS['pkg_product_name']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="pkg_brand_name" class="control-label">Brand Name</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Brand Name" maxlength="255" name="pkg_brand_name" id="pkg_brand_name" type="text" value="<?php echo $GLOBALS['pkg_brand_name']; ?>">
                </div>

        <div class="form-group">
        <label for="pkg_product_description" class="control-label">Product Description</label>
        <textarea class="form-control input-sm" placeholder="Product Description" name="pkg_product_description" id="pkg_product_description" cols="50" rows="4"><?php echo $GLOBALS['pkg_product_description']; ?></textarea>
        </div>        

        <div class="form-group">
        <label for="pkg_competitors" class="control-label">What are the product's major competitors?</label>
        <textarea class="form-control input-sm" placeholder="What are the product's major competitors?" name="pkg_competitors" id="pkg_competitors" cols="50" rows="4"><?php echo $GLOBALS['pkg_competitors']; ?></textarea>
        </div>        

        <div class="form-group has-feedback">
        <label for="pkg_selling_point" class="control-label">What is your product's selling point?</label>
        <div class="radio"><label><input type="radio" name="pkg_selling_point" id="selling_point0" value="1" <?php echo radio('pkg_selling_point', 1); ?>> High Overall Quality</label></div>
        <div class="radio"><label><input type="radio" name="pkg_selling_point" id="selling_point1" value="2" <?php echo radio('pkg_selling_point', 2); ?>> Health / Safety Factor</label></div>
        <div class="radio"><label><input type="radio" name="pkg_selling_point" id="selling_point2" value="3" <?php echo radio('pkg_selling_point', 3); ?>> High Value</label></div>
        <div class="radio"><label><input type="radio" name="pkg_selling_point" id="selling_point3" value="4" <?php echo radio('pkg_selling_point', 4); ?>> Convenience</label></div>
        <div class="radio"><label><input type="radio" name="pkg_selling_point" id="selling_point4" value="5" <?php echo radio('pkg_selling_point', 5); ?>> Unique (No Competition)</label></div>
        <div class="radio"><label><input type="radio" name="pkg_selling_point" id="selling_point5" value="6" <?php echo radio('pkg_selling_point', 6); ?>> Others</label></div>
        <label for="pkg_selling_point_others" class="control-label">If "Others" is chosen, please specify...</label>
        <textarea class="form-control input-sm" placeholder="If Others is chosen, please specify..." name="pkg_selling_point_others" id="pkg_selling_point_others" cols="50" rows="4"><?php echo $GLOBALS['pkg_selling_point_others']; ?></textarea>
        </div>

        <div class="form-group has-feedback">
        <label for="pkg_performance" class="control-label">How does your product perform against it's competitors in terms of sales?</label>
        <textarea class="form-control input-sm" placeholder="How does your product perform against it's competitors in terms of sales?" name="pkg_performance" id="pkg_performance" cols="50" rows="4"><?php echo $GLOBALS['pkg_performance']; ?></textarea>
                </div>
    
        <div class="form-group">
        <label for="pkg_distribution" class="control-label">Where do you intend to sell your product?</label>
        <div class="radio"><label><input type="radio" name="pkg_distribution" id="distribution0" value="1" <?php echo radio('pkg_distribution', 1); ?>> Nationwide</label></div>
        <div class="radio"><label><input type="radio" name="pkg_distribution" id="distribution1" value="2" <?php echo radio('pkg_distribution', 2); ?>> Local Province/Region</label></div>
        <div class="radio"><label><input type="radio" name="pkg_distribution" id="distribution2" value="3" <?php echo radio('pkg_distribution', 3); ?>> Export</label></div>
        </div>

        <br>
        <h4>If Applicable (Food, Chemicals...etc)</h4>

        <div class="form-group has-feedback">
        <label for="performance" class="control-label">Ingredients</label>
        <textarea class="form-control input-sm" placeholder="Ingredients" name="pkg_ingredients" id="pkg_ingredients" cols="50" rows="4"><?php echo $GLOBALS['pkg_ingredients']; ?></textarea>
                </div>

        <div class="form-group has-feedback">
        <label for="pkg_volume" class="control-label">Net Weight/Volume</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Net Weight/Volume" maxlength="255" name="pkg_volume" id="pkg_volume" type="text" value="<?php echo $GLOBALS['pkg_volume']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="pkg_packaging_material" class="control-label">Packaging Material to be used</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Packaging Material to be used" maxlength="255" name="pkg_packaging_material" id="pkg_packaging_material" type="text" value="<?php echo $GLOBALS['pkg_packaging_material']; ?>">
                </div>
    
        <div class="form-group has-feedback">
        <label for="pkg_label_size" class="control-label">Size of Label/Box</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Size of Label/Box" maxlength="255" name="pkg_label_size" id="pkg_label_size" type="text" value="<?php echo $GLOBALS['pkg_label_size']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="pkg_preferred_colors" class="control-label">Preferred Colors</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Preferred Colors" maxlength="255" name="pkg_preferred_colors" id="pkg_preferred_colors" type="text" value="<?php echo $GLOBALS['pkg_preferred_colors']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="pkg_other_details" class="control-label">Other Specifications/Preference</label>
        <textarea class="form-control input-sm" placeholder="Other Specifications/Preference" name="pkg_other_details" id="pkg_other_details" cols="50" rows="4"><?php echo $GLOBALS['pkg_other_details']; ?></textarea>
                </div>

        <br>
        <h4>New Markets Penetrated</h4>

        <div class="form-group has-feedback">
        <label for="pkg_market_location" class="control-label">Location</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Location" maxlength="255" name="pkg_market_location" id="pkg_market_location" type="text" value="<?php echo $GLOBALS['pkg_market_location']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="pkg_market_products_sold" class="control-label">Products Sold</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Products Sold" maxlength="255" required="required" name="pkg_market_products_sold" id="pkg_market_products_sold" type="text" min="0" step="any" value="<?php echo $GLOBALS['pkg_market_products_sold']; ?>">
                </div>

        <div class="form-group has-feedback">
        <label for="pkg_market_date_established" class="control-label">Date Established</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm date-picker" placeholder="Date Established" maxlength="10" name="pkg_market_date_established" id="pkg_market_date_established" type="text" value="<?php echo $GLOBALS['pkg_market_date_established']; ?>">
                </div>

        <br>
        <h4>Sales</h4>

        <div class="form-group">
        <label for="pkg_sales_before_intervention" class="control-label">Before Intervention</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Before Intervention" maxlength="255" required="required" name="pkg_sales_before_intervention" id="pkg_sales_before_intervention" type="number" min="0" step="any" value="<?php echo $GLOBALS['pkg_sales_before_intervention']; ?>">
                </div>

        <div class="form-group">
        <label for="pkg_sales_after_intervention" class="control-label">After Intervention</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="After Intervention" maxlength="255" required="required" name="pkg_sales_after_intervention" id="pkg_sales_after_intervention" type="number" min="0" step="any" value="<?php echo $GLOBALS['pkg_sales_after_intervention']; ?>">
                </div>

        <br>
        <h4>Employment After</h4>

        <div class="form-group">
        <label for="pkg_employment_after_direct" class="control-label">Direct</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Direct" maxlength="255" required="required" name="pkg_employment_after_direct" id="pkg_employment_after_direct" type="number" min="0" step="1" value="<?php echo $GLOBALS['pkg_employment_after_direct']; ?>">
                </div>

        <div class="form-group">
        <label for="pkg_employment_after_indirect" class="control-label">Indirect</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Indirect" maxlength="255" required="required" name="pkg_employment_after_indirect" id="pkg_employment_after_indirect" type="number" min="0" step="1" value="<?php echo $GLOBALS['pkg_employment_after_indirect']; ?>">
                </div>

        <div class="form-group">
        <label for="pkg_employment_after_months_employed" class="control-label">Months Employed</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Months Employed" maxlength="255" required="required" name="pkg_employment_after_months_employed" id="pkg_employment_after_months_employed" type="number" min="0" step="1" value="<?php echo $GLOBALS['pkg_employment_after_months_employed']; ?>">
                </div>

        <br>
        <div class="form-group">
        <label for="pkg_avg_productivity_improvement" class="control-label">Average Productivity Improvement</label>
        &nbsp;&nbsp;<span class="text-danger"><small></small></span>
        <input class="form-control input-sm" placeholder="Average Productivity Improvement" maxlength="255" required="required" name="pkg_avg_productivity_improvement" id="pkg_avg_productivity_improvement" type="number" min="0" step="any" value="<?php echo $GLOBALS['pkg_avg_productivity_improvement']; ?>">
                </div>

        <br>
       <div class="form-group">
            <label for="pkg_file" class="control-label">Document/Image Attachments</label><br>
            Current File : <a href="<?php echo PACKAGING_DOCS_LINK_PATH.$GLOBALS['pkg_file']; ?>" title="<?php echo $GLOBALS['pkg_filename']; ?>"><?php echo $GLOBALS['pkg_filename']; ?></a><br>
            <input class="form-control input-sm" placeholder="Document" name="pkg_file" id="pkg_file" type="file" accept="application/ms*,application/vnd.ms*,image/*">
        </div>


        <br>
        <div class="form-group">
        <label for="pkg_remarks" class="control-label">Remarks</label>
        <textarea class="form-control input-sm" placeholder="Remarks" name="pkg_remarks" id="pkg_remarks" cols="50" rows="4"><?php echo $GLOBALS['pkg_remarks']; ?></textarea>
        </div>
  
        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="pkg_id" value="<?php echo $GLOBALS['pkg_id']; ?>">
        </form>
    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    page_footer();
?>