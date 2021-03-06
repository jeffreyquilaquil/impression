<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Project Fora', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'projects.php');
    die();
}

$quarter_start[] = array();
$quarter_end[] = array();

$quarter_start[1] = 1;
$quarter_end[1] = 3;

$quarter_start[2] = 4;
$quarter_end[2] = 6;

$quarter_start[3] = 7;
$quarter_end[3] = 9;

$quarter_start[4] = 10;
$quarter_end[4] = 12;

$q_key = 'q_fora';
$q1_key = 'q1_fora';
$q2_key = 'q2_fora';
$q3_key = 'q3_fora';

$q = '';
$q1 = 0;
$q2 = 0;
$q3 = getQuarterIndex();

if (!postEmpty('search')){
    if ($_POST['search'] == 'Search'){
        if (!postEmpty('q')){
            $_SESSION[$q_key] = safeString($_POST['q']);
        } else {
            $_SESSION[$q_key] = '';
        }
        if (!postEmpty('q1')){
            $_SESSION[$q1_key] = safeString($_POST['q1']);
        } else {
            $_SESSION[$q1_key] = -1;
        }

        if (!postEmpty('q2')){
            $_SESSION[$q2_key] = safeString($_POST['q2']);
        } else {
            $_SESSION[$q2_key] = 0;
        }

        if (!postEmpty('q3')){
            $_SESSION[$q3_key] = safeString($_POST['q3']);
        } else {
            $_SESSION[$q3_key] = getQuarterIndex();
        }

    } else if ($_POST['search'] == 'Show All'){
        $_SESSION[$q_key] = '';
        $_SESSION[$q1_key] = -1;
        $_SESSION[$q2_key] = 0;
        $_SESSION[$q3_key] = getQuarterIndex();
    }
}

if (!sessionEmpty($q_key)){
    $q = $_SESSION[$q_key];
}

if (!sessionEmpty($q1_key)){
    $q1 = intval($_SESSION[$q1_key]);
}

if (!sessionEmpty($q2_key)){
    $q2 = intval($_SESSION[$q2_key]);
}

if (!sessionEmpty($q3_key)){
    $q3 = intval($_SESSION[$q3_key]);
}

$sql = "SELECT * FROM vwpsi_fora";
$where = "(prj_id = $pid)";

if (strlen($q) > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    $where .= "(fr_title like '%$q%')";
}

if ($q1 > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    $where .= "(fr_type_id = $q1)";
}

if ($q2 > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    $where .= "(fr_end_yr = $q2)";
}

if ($q3 > 0){
    if (strlen($where) > 0){
        $where .= " AND ";
    }
    
    if ($q2 > 0){
        $where .= "(fr_end_yr = $q2) AND ";
    }
    $where .= '((fr_end_mo >= '.$quarter_start[$q3].') AND (fr_end_mo <= '.$quarter_end[$q3].'))';
}

if (strlen($where) > 0){
    $sql .= ' WHERE '.$where;
}

$sql .= ' ORDER BY fr_title ASC';

//echo $sql;

$rows = mysqli_query($GLOBALS['cn'], $sql);

$sel_types = getOptions('psi_fora_types', 'fr_type_name', 'fr_type_id', $q1, 'All');
$sel_years = getImpYearOptions($q2);
$sel_quarters = getOptions('psi_quarters', 'quarter_name', 'quarter_id', $q3, 'ALL');


$page_title = 'Project Fora/Trainings/Seminars';
page_header($page_title, 1);

if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="pull-right">
                <?php
                if (can_access('Project Fora', 'add')){
                ?>
                <a class="btn btn-primary btn-sm" href="project_trainings_form.php?op=0&amp;id=0&amp;pid=<?php echo $pid;?>" title="Add Training"><span class="fa fa-plus"></span> Add Training</a>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <form method="POST" action="project_trainings.php?pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form-inline" role="form">
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Type</span>
                    <select class="form-control input-sm" id="q1" name="q1">
                    <?php echo $sel_types; ?>
                    </select>
                </div>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Year</span>
                    <select class="form-control input-sm" id="q2" name="q2">
                    <?php echo $sel_years; ?>
                    </select>
                </div>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">Quarter</span>
                    <select class="form-control input-sm" id="q3" name="q3">
                    <?php echo $sel_quarters; ?>
                    </select>
                </div>

                <div class="input-group input-group-sm">
                    <input class="form-control input-sm" placeholder="Training Title ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
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
                    <th class="hidden-print">&nbsp;</th>
                    <th>#</th>
                    <th>Type</th>
                    <th class="text-center">Start</th>
                    <th class="text-center">End</th>
                    <th>Title</th>
                    <th class="text-center">Implementor</th>
                    <th class="text-center"># of Masculine</th>
                    <th class="text-center"># of Feminine</th>
                    <th class="text-center"># of PWD</th>
                    <th class="text-center"># of Seniors</th>
                    <th class="text-center"># of Firms</th>
                    <th class="text-center">Total Participants</th>
                    <th class="text-center">CSF Rating</th>
                    <th>Encoded</th>
                    <th>Last Updated</th>
                </tr>
            </thead> 
            <tbody>
            <?php 
            $female = 0;
            $male = 0;
            $pwd = 0;
            $senior = 0;
            $total = 0;
            $firms = 0;
            $ctr = 0;
        	if ($rows) {
                while($row = mysqli_fetch_array($rows)) {
                    $ctr++;
        			$action = '';

                    if (can_access('Project Fora', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'project_trainings_view.php?pid='.$pid.'&amp;did='.$row['fr_id'].'" title="View Details"><span class="fa fa-folder-open"></span></a>  ';
                    }

                    if (can_access('Project Fora Documents', 'view')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'project_trainings_documents.php?pid='.$pid.'&amp;did='.$row['fr_id'].'" title="Documents"><span class="fa fa-file"></span></a>  ';
                    }

                    if (can_access('Project Fora', 'edit')){
                        $action .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'project_trainings_form.php?op=1&amp;pid='.$pid.'&amp;id='.$row['fr_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                    }

                    if (can_access('Project Fora', 'delete')){
                        $action .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'project_trainings_delete.php?op=2&amp;pid='.$pid.'&amp;id='.$row['fr_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>';
                    }
                    $female += $row['fr_no_feminine'];
                    $male += $row['fr_no_masculine'];
                    $pwd += $row['fr_no_pwd'];
                    $senior += $row['fr_no_seniors'];

                    $firms += $row['fr_no_firms'];

                    $sum = $row['fr_no_masculine'] + $row['fr_no_feminine'];
                    $total += $sum;


?>                <tr>
                    <td class="nowrap text-left"><?php echo $action; ?></td>
                    <td class="text-center"><?php echo $ctr; ?></td>
                    <td class="nowrap"><?php echo $row['fr_type_name']; ?></td>
                    <td class="nowrap text-center"><?php echo zeroDate($row['fr_start'], 1, 'm/d/Y h:i A'); ?></td>
                    <td class="nowrap text-center"><?php echo zeroDate($row['fr_end'], 1, 'm/d/Y h:i A'); ?></td>
                    <td><?php echo $row['fr_title']; ?></td>
                    <td><?php echo $row['ug_name']; ?></td>
                    <td class="text-center"><?php echo zeroNumber($row['fr_no_masculine'], 0); ?></td>
                    <td class="text-center"><?php echo zeroNumber($row['fr_no_feminine'], 0); ?></td>
                    <td class="text-center"><?php echo zeroNumber($row['fr_no_pwd'], 0); ?></td>
                    <td class="text-center"><?php echo zeroNumber($row['fr_no_seniors'], 0); ?></td>
                    <td class="text-center"><?php echo zeroNumber($row['fr_no_firms'], 0); ?></td>
                    <td class="text-center"><?php echo zeroNumber($sum, 0); ?></td>
                    <td class="nowrap text-center"><?php echo zeroNumber($row['fr_csf']); ?></td>
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
        <div class="table-responsive">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th class="text-center">Total No. of Releases</th>
                <th class="text-center">Total No. of Feminine</th>
                <th class="text-center">Total No. of Masculine</th>
                <th class="text-center">Total No. of PWD</th>
                <th class="text-center">Total No. of Seniors</th>
                <th class="text-center">Total No. of Firms</th>
                <th class="text-center">Total No. of Participants</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center">
                    <?php echo $ctr; ?>
                </td>
                <td class="text-center">
                    <?php echo $female; ?>
                </td>
                <td class="text-center">
                    <?php echo $male; ?>
                </td>
                <td class="text-center">
                    <?php echo $pwd; ?>
                </td>
                <td class="text-center">
                    <?php echo $senior; ?>
                </td>
                <td class="text-center">
                    <?php echo $firms; ?>
                </td>
                <td class="text-center">
                    <?php echo $total; ?>
                </td>
            </tr>
            </tbody>
        </table>
        </div>
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

  function getImpYearOptions($value){
        $s = '';
        $sql = "SELECT DISTINCT fr_end_yr FROM vwpsi_fora ORDER BY fr_end_yr DESC";
        $res = mysqli_query($GLOBALS['cn'], $sql);

        if (!$res) return $s;
        $found = false;
        while ($row = mysqli_fetch_array($res)){
            if (intval($row['fr_end_yr']) == intval($value) ){
                $s .= '<option value="'.$row['fr_end_yr'].'" selected="selected">'.$row['fr_end_yr'].'</option>';
                $found = true;
            } else {
                $s .= '<option value="'.$row['fr_end_yr'].'">'.$row['fr_end_yr'].'</option>';
            }
        }
        mysqli_free_result($res);

        if (!$found){
            $s = '<option value="0" select="selected">All</option>'.$s;
        } else {
            $s = '<option value="0">All</option>'.$s;
        }
        return $s;
    }
?>