<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Service Providers', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$q_key = 'q_providers';
$qg_key = 'qg_providers';

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

$sql = "SELECT * FROM vwpsi_service_providers";
$where = '';

if (strlen($q) > 0){
    $where .= "((sp_name like '%$q%') OR (sp_contact_name like '%$q%') OR (sp_fname like '%$q%') OR (sp_mname like '%$q%') OR (sp_lname like '%$q%'))";
}

if ($qg > 0){
    if (strlen($where) > 0){
        $where .= " AND ";    
    }
    $where .= "(sp_id IN (SELECT sp_id FROM psi_service_provider_services WHERE service_id = $qg))";
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY sp_name ASC';

$rows = mysqli_query($GLOBALS['cn'], $sql);
$sel_services = getOptions('psi_services', 'service_name', 'service_id', $qg, 'All');

page_header('Service Providers');
if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Service Providers</h3>
        <div class="pull-right">
            <?php
            if (can_access('Service Providers', 'add')){
            ?>
            <a class="btn btn-primary btn-sm" href="service_providers_form.php?op=0&amp;id=0" title="Add Service Provider"><span class="fa fa-plus"></span> Add Service Provider</a>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="service_providers.php" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Service</span>
                    <select class="form-control input-sm" id="qg" name="qg">
                    <?php echo $sel_services; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <input class="form-control input-sm" placeholder="Providers ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
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
                    <th>&nbsp;</th>
                    <th>#</th>
                    <th>Service Provider</th>
                    <th>Services</th>
                    <th>Contact Person</th>
                    <th>Contact #s</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th class="text-center">Encoded</th>
                    <th class="text-center">Last Updated</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
        	if ($rows) {
                $ctr = 0;
                while($row = mysqli_fetch_array($rows)) {
        			$action				= '';

                    if (can_access('Service Providers', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'service_providers_view.php?pid='.$row['sp_id'].'" title="View Details"><span class="fa fa-folder-open"></span></a>  ';
                    }

                    if (can_access('Service Providers', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'service_providers_form.php?op=1&amp;id='.$row['sp_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }

                    if (can_access('Service Providers', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'service_providers_delete.php?op=2&amp;id='.$row['sp_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>';
                    }

                    $nos = '';
                    $tmp = trim($row['sp_phone'].'');
                    if (strlen($tmp) > 0){
                        $nos .= 'Phone : '.$tmp;
                    }

                    $tmp = trim($row['sp_mobile'].'');
                    if (strlen($tmp) > 0){
                        if (strlen($nos) > 0){
                            $nos .= '<br>';
                        }
                        $nos .= 'Mobile : '.$tmp;
                    }

                    $addr = trim($row['sp_address'].'');
                    $addr = nl2br($addr);
                    $services = get_services($row['sp_id']);
                    if (strlen($row['sp_other_service']) > 0){
                        if (strlen($services)){
                            $services .= '<br>';
                        }
                        $services .= $row['sp_other_service'];
                    }

                    $stamp = 'Encoded on '.zeroDateTime($row['date_encoded']).' by '.$row['encoder'].'<br>
                     Last updated on '.zeroDateTime($row['last_updated']).' by '.$row['updater'];

                    $ctr++;

?>                <tr>
                    <td class="nowrap text-right"><?php echo $action; ?></td>
                    <td class="nowrap text-right"><?php echo $ctr; ?></td>
                    <td ><?php echo $row['sp_name']; ?></td>
                    <td ><?php echo $services; ?></td>
                    <td ><?php echo $row['sp_contact_name']; ?></td>
                    <td ><?php echo $nos; ?></td>
                    <td ><?php echo $row['sp_email']; ?></td>
                    <td><?php echo $addr; ?></td>
                    <td class="nowrap"><?php echo zeroDateTime($row['date_encoded']); ?><br>by <?php echo $row['encoder']; ?></td>
                    <td class="nowrap"><?php echo zeroDateTime($row['last_updated']); ?><br>by <?php echo $row['updater']; ?></td>
                  </tr><?php
        		}
                mysqli_free_result($rows);
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

    function get_services($id){
        $sql = "SELECT * FROM vwpsi_service_provider_services WHERE sp_id = $id";
        $res = mysqli_query($GLOBALS['cn'], $sql);

        if (!$res) return '';
        $s = '';
        while ($row = mysqli_fetch_array($res)){
            if (strlen($s) > 0){
                $s .= '<br>';
            }
            $s .= $row['service_name'];
        }
        mysqli_free_result($res);
        return $s;
    }
?>