<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Cooperators', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$q_key = 'q_cooperators';
$qg_key = 'qg_cooperators';

$q = '';
$qg = 0;

if (!postEmpty('search')){
    if ($_POST['search'] == 'Search'){
        if (!postEmpty('q')){
            $_SESSION[$q_key] = safeString($_POST['q']);
        } else {
            $_SESSION[$q_key] = '';
        }
        if (!postEmpty('qg')){
            $_SESSION[$qg_key] = safeString($_POST['qg']);
        } else {
            $_SESSION[$qg_key] = -1;
        }

    } else if ($_POST['search'] == 'Show All'){
        $_SESSION[$q_key] = '';
        $_SESSION[$qg_key] = 0;
    }
}

if (!sessionEmpty($q_key)){
    $q = $_SESSION[$q_key];
}

if (!sessionEmpty($qg_key)){
    $qg = intval($_SESSION[$qg_key]);
}

$sql = "SELECT * FROM vwpsi_cooperators";
$where = '';
$ug_id = 0;

if (in_pstc($GLOBALS['ad_ug_name'])){
    $ug_id = $GLOBALS['ad_ug_id'];
    //$where .= "(ug_id = $GLOBALS[ad_ug_id])";
}


if (strlen($q) > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "((coop_name like '%$q%') OR (coop_p_name like '%$q%') OR (coop_p_fname like '%$q%')  OR (coop_p_mname like '%$q%') OR (coop_p_lname like '%$q%'))";
}

if ($qg > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(ot_id = $qg)";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY coop_name ASC';

//echo $sql;
//die();

$res = mysqli_query($GLOBALS['cn'], $sql);
$sel_orgtypes = getOptions('psi_organization_types', 'ot_name', 'ot_id', $qg, 'All', 'ORDER BY ot_name ASC');

$page_title = 'Cooperators / Beneficiaries';
page_header($page_title);
if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left"><?php echo $page_title; ?></h3>
        <div class="pull-right">
            <?php
            if (can_access('Cooperators', 'add')){
            ?>
            <a class="btn btn-primary btn-sm" href="cooperators_form.php?op=0&amp;id=0" title="Add Cooperator"><span class="fa fa-plus"></span> Add Cooperator</a>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="cooperators.php" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <input class="form-control input-sm" placeholder="Cooperators ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
                    <span class="input-group-btn">
                        <input class="btn btn-primary btn-sm" type="submit" name="search" id="search" value="Search">
                    </span>
                </div>
            </div>
        </form>
 </div>
    <div class="table-responsive">
        <table id="grid_table" class="table table-bordered table-striped table-hover table-condensed tablesorter">
            <thead>
            	<tr>
                    <th></th>
                    <th>#</th>
                    <th>Organization</th>
                    <th>Sectors</th>
                    <th>Contact Person</th>
                    <th>Contact #s</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Org. Type</th>
                    <th>Assets</th>
                    <th>Unit</th>
                    <th class="text-center">--</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
        	if ($res) {
                $ctr = 0;
                while($row = mysqli_fetch_array($res)) {
        			$action				= '';
                    if (can_access('Cooperators', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'cooperators_view.php?pid='.$row['coop_id'].'" title="View Details"><span class="fa fa-folder-open"></span></a>  ';
                    }

                    if (($ug_id == 0) || ($ug_id == $row['ug_id'])){

                        if (can_access('Cooperators', 'edit')){
                			$action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'cooperators_form.php?op=1&amp;id='.$row['coop_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                        }

                        if (can_access('Cooperators', 'delete')){
                            $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'cooperators_delete.php?op=2&amp;id='.$row['coop_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>  ';
                        }

                    }

                    $nos = '';
                    $tmp = trim($row['coop_phone'].'');
                    if (strlen($tmp) > 0){
                        $nos .= 'Phone : '.$tmp;
                    }

                    $tmp = trim($row['coop_mobile'].'');
                    if (strlen($tmp) > 0){
                        if (strlen($nos) > 0){
                            $nos .= '<br>';
                        }
                        $nos .= 'Mobile : '.$tmp;
                    }

                    $tmp = trim($row['coop_fax'].'');
                    if (strlen($tmp) > 0){
                        if (strlen($nos) > 0){
                            $nos .= '<br>';
                        }
                        $nos .= 'Fax : '.$tmp;
                    }

                    $addr = trim($row['coop_address'].'');
                    $addr = nl2br($addr);


                    $stamp = 'Encoded on '.zeroDateTime($row['date_encoded']).' by '.$row['encoder'].'<br>
                     Last updated on '.zeroDateTime($row['last_updated']).' by '.$row['updater'];
                    $ctr++;

?>                <tr>
                    <td class="nowrap text-left"><?php echo $action; ?></td>
                    <td><?php echo $ctr; ?></td>
                    <td><?php echo $row['coop_name']; ?></td>
                    <td><?php echo $row['coop_sector_list']; ?></td>
                    <td><?php echo $row['coop_p_name']; ?></td>
                    <td><?php echo $nos; ?></td>
                    <td><?php echo $row['coop_email']; ?></td>
                    <td><?php echo $addr; ?></td>
                    <td><?php echo $row['ot_cat1_name']; ?></td>
                    <td><?php echo $row['ot_cat3_name']; ?></td>
                    <td><?php echo $row['ug_name']; ?></td>
                    <td><?php echo $stamp; ?></td>
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