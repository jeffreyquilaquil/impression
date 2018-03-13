<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Packaging & Labeling', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$q_key = 'q_packaging';
$qc_key = 'qc_packaging';

$q = '';
$qc = 0;

if (!postEmpty('search')){
    if ($_POST['search'] == 'Search'){
        if (!postEmpty('q')){
            $_SESSION[$q_key] = safeString($_POST['q']);
        } else {
            $_SESSION[$q_key] = '';
        }
        if (!postEmpty('qc')){
            $_SESSION[$qc_key] = safeString($_POST['qc']);
        } else {
            $_SESSION[$qc_key] = -1;
        }

    } else if ($_POST['search'] == 'Show All'){
        $_SESSION[$q_key] = '';
        $_SESSION[$qc_key] = 0;
    }
}

if (!sessionEmpty($q_key)){
    $q = $_SESSION[$q_key];
}

if (!sessionEmpty($qc_key)){
    $qc = intval($_SESSION[$qc_key]);
}

$sql = "SELECT * FROM vwpsi_packaging";
$where = '';

if (strlen($q) > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    $where .= "(pkg_product_name like '%$q%')";
}

if ($qc > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    $where .= "(coop_id = $qc)";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY pkg_product_name ASC';

$res = mysqli_query($GLOBALS['cn'], $sql);
$sel_coops = getOptions('psi_cooperators', 'coop_name', 'coop_id', $qc, 'All', 'ORDER BY coop_name ASC');

page_header('Packaging &amp; Labeling', 2);

if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Packaging &amp; Labeling</h3>
        <div class="pull-right">
            <?php
            if (can_access('Packaging & Labeling', 'add')){
            ?>
            <a class="btn btn-primary btn-sm" href="packaging_form.php?op=0&amp;id=0" title="Add Packaging &amp; Labeling Job"><span class="fa fa-plus"></span> Add</a>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="packaging.php" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Beneficiary</span>
                    <select class="form-control input-sm" id="qc" name="qc">
                    <?php echo $sel_coops; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <input class="form-control input-sm" placeholder="Product name ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
                    <span class="input-group-btn">
                        <input class="btn btn-primary btn-sm" type="submit" name="search" id="search" value="Search">
                    </span>
                </div>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table id="grid_table" class="table table-striped table-hover table-condensed tablesorter">
            <thead>
            	<tr>
                    <th class="hidden-print">&nbsp;</th>
                    <th>#</th>
                    <th>Product</th>
                    <th>Beneficiary</th>
                    <th>Remarks</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
        	if ($res) {
                $ctr = 0;
                while($row = mysqli_fetch_array($res)) {
                    $date = strtotime($row['pkg_date']);
                    $date = date('m/d/Y', $date);
        			$remarks = nl2br($row['pkg_remarks']);
        			$action = '';

                    if (can_access('Packaging & Labeling', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'packaging_view.php?pid='.$row['pkg_id'].'" title="View Details"><span class="fa fa-folder-open"></span></a>  ';
                    }

                    if (can_access('Packaging & Labeling Designs', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'packaging_designs.php?pid='.$row['pkg_id'].'" title="View Designs"><span class="fa fa-file-image-o"></span></a>  ';
                    }

                    if (can_access('Packaging & Labeling', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'packaging_form.php?op=1&amp;id='.$row['pkg_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }

                    if (can_access('Packaging & Labeling', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'packaging_delete.php?op=2&amp;id='.$row['pkg_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>  ';
                    }

                    $ctr++;
?>                <tr>
                    <td class="nowrap text-right hidden-print"><?php echo $action; ?></td>
                    <td class="nowrap text-right"><?php echo $ctr; ?></td>
                    <td class="nowrap" width="50%"><?php echo $row['pkg_product_name']; ?></td>
                    <td class="nowrap" width="50%"><?php echo $row['coop_name']; ?></td>
                    <td><?php echo $remarks; ?></td> 
                    <td class="nowrap"><?php echo $date; ?></td> 
                  </tr><?php
        		}
                mysqli_free_result($res);
        	}
?>         
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <div id="grid_pager">
            <form class="form-inline" style="text-align:center;" role="form">
                <div class="form-group form-group-sm">
                    <a class="btn btn-primary btn-sm first" title="First"><span class="glyphicon glyphicon-step-backward"></span></a>
                    <a class="btn btn-primary btn-sm prev" title="Previous"><span class="glyphicon glyphicon-backward"></span></a>
                    <input type="text" class="form-control input-sm text-center pagedisplay" disabled>
                    <a class="btn btn-primary btn-sm next" title="Next"><span class="glyphicon glyphicon-forward"></span></a>
                    <a class="btn btn-primary btn-sm last" title="Last"><span class="glyphicon glyphicon-step-forward"></span></a>
                    <select class="form-control input-sm pagesize" title="No. of items per page.">
                        
                        <option selected="selected" value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </form>
        </div>
    </div>


</div>
<script type="text/javascript">
var _tsOptions = {
    headers:{
        0: { sorter: false}
        }
    };
</script>
<?php
    page_footer();
    deleteFormCache();
?>