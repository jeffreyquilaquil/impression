<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Cooperators', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'cooperators.php?dedo=1');

if (!dbValueExists('psi_cooperators', 'coop_id', $pid, false)){
    redirect(WEBSITE_URL.'cooperators.php');
    die();
}

loadDBValues("vwpsi_cooperators", "SELECT * FROM vwpsi_cooperators WHERE coop_id = ".$pid);

$page_title = 'Cooperators Details';
page_header($page_title);
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <h3 class="panel-title"><?php echo $page_title; ?></h3>
            <div>
                <h3 class="detail-name text-primary">
                <?php
                    echo $GLOBALS['coop_name'];
                ?>
                </h3>
            </div>
            <?php
                echo '
                    Encoded on '.zeroDateTime($GLOBALS['date_encoded']).' by '.$GLOBALS['encoder'].'
                    <br>
                    Last updated on '.zeroDateTime($GLOBALS['last_updated']).'
                ';
            ?>

        </div>
        <div class="pull-right">
            <!-- <a class="btn btn-primary btn-sm" href="cooperators_projects.php?pid=<?php echo $pid; ?>" title="View Projects"><span class="fa fa-file-image-o"></span> View Projects</a> -->
            <a class="btn btn-primary btn-sm" href="cooperators.php" title="Cooperators"><span class="fa fa-arrow-circle-left"></span> Back</a>
        </div>
    </div>
    <div class="panel-body">

        <div class="row-fluid clearfix">
            <h5>Year Established</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['coop_year_established']; ?>
            </div>

            <h5>Contact Person</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['coop_p_name']; ?>
            </div>

            <h5>Address</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['coop_address'].''); ?>
            </div>

            <h5>Phone</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['coop_phone'].''; ?>
            </div>

            <h5>Fax</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['coop_fax'].''; ?>
            </div>

            <h5>Mobile</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['coop_mobile'].''; ?>
            </div>

            <h5>Email</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['coop_email'].''; ?>
            </div>

            <h5>Website</h5>
            <div class="well well-sm">
                <?php echo $GLOBALS['coop_website'].''; ?>
            </div>

            <h3><span class="label label-default full-width">
            Type of Organization
            </span></h3>

            <div class="well well-sm">
                <?php echo $GLOBALS['ot_cat1_name'].''; ?><br>
                <?php echo $GLOBALS['ot_cat2_name'].''; ?><br>
                <?php echo $GLOBALS['ot_cat3_name'].''; ?><br>
            </div>

            <h3><span class="label label-default full-width">
                Business Registration
            </span></h3>


            <div class="col-sm-6">
                <h5>DTI Registration #</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['coop_reg_dti']; ?>&nbsp;
                </div>
            </div>
            <div class="col-sm-6">
                <h5>Date Of Registration (DTI)</h5>
                <div class="well well-sm">
                    <?php echo zeroDate($GLOBALS['coop_reg_dti_date'].''); ?>
                </div>
            </div>
        </div>

        <div class="row-fluid clearfix">
            <div class="col-sm-6">
                <h5>SEC Registration #</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['coop_reg_sec']; ?>&nbsp;
                </div>
            </div>
            <div class="col-sm-6">
                <h5>Date Of Registration (SEC)</h5>
                <div class="well well-sm">
                    <?php echo zeroDate($GLOBALS['coop_reg_sec_date'].''); ?>
                </div>
            </div>
        </div>

        <div class="row-fluid clearfix">
            <div class="col-sm-6">
                <h5>CDA Registration #</h5>
                <div class="well well-sm">
                    <?php echo $GLOBALS['coop_reg_cda']; ?>&nbsp;
                </div>
            </div>
            <div class="col-sm-6">
                <h5>Date Of Registration (CDA)</h5>
                <div class="well well-sm">
                    <?php echo zeroDate($GLOBALS['coop_reg_cda_date'].''); ?>
                </div>
            </div>
        </div>

        <div class="row-fluid clearfix">
            <div class="col-sm-12">
            <h5>Other Registrations #</h5>
            <div class="well well-sm">
                <?php echo nl2br($GLOBALS['coop_reg_others'].''); ?>&nbsp;
            </div>
            </div>
        </div>


        <h3><span class="label label-default full-width">
        Sector / Business Activities
        </span></h3>


        <div class="well well-sm">
            <?php 
                echo $GLOBALS['coop_sector_list'];
             ?>

        </div>


    </div>
    <div class="panel-footer">
    </div>
</div>
<?php 
    //showProjects($pid);

    page_footer();
    // *************************************************************************************************************

    function showProjects($pid){

        $qg_key = 'qg_coop_projects';
        $q_key = 'q_coop_projects';

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

        $sql = "SELECT * FROM vwpsi_cooperator_projects";
        $where = "(coop_id = $pid)";

        if (strlen($q) > 0){
            if (strlen($where) > 0){
                $where .= " AND ";    
            }
            $where .= "(prj_title like '%$q%')";
        }

        if ($qg > 0){
            if (strlen($where) > 0){
                $where .= " AND ";    
            }
            $where .= "(prj_type_id = $qg)";
        }

        if (strlen($where) > 0){
            $sql .= ' WHERE '.$where;
        }

        $sql .= ' ORDER BY prj_title ASC';

        $res = mysqli_query($GLOBALS['cn'], $sql);
        $sel_projecttypes = getOptions('psi_project_types', 'prj_type_name', 'prj_type_id', $qg, 'All');


        if (strlen($GLOBALS['errmsg']) > 0){
            ?>
                <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
            <?php 
        }
        ?>
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Projects</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="cooperators_view.php?pid=<?php echo $pid; ?>" accept-charset="UTF-8" class="form-inline" role="form">
                    <div class="form-group">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">Project Type</span>
                            <select class="form-control input-sm" id="qg" name="qg">
                            <?php echo $sel_projecttypes; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-sm">
                            <input class="form-control input-sm" placeholder="Project Title ..." type="text" maxlength="255" name="q" id="q" value="<?php echo $q; ?>">
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
                            <th>Project</th>
                            <th>Type</th>
                            <th>Year Approved</th>
                            <th>Beneficiaries</th>
                            <th>Collaborators</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead> 
                    <tbody>
                    <?php 
                    if ($res) {
                        while($row = mysqli_fetch_array($res)) {
                            $action             = '';
                            $action             .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'projects_view.php?pid='.$row['prj_id'].'" title="View Details"><span class="fa fa-folder-open"></span></a>  ';
                            //$action             .= '<a class="btn btn-primary btn-xs" href="'.WEBSITE_URL.'projects_form.php?op=1&amp;id='.$row['prj_id'].'" title="Edit"><span class="fa fa-pencil"></span></a>  ';
                            //$action             .= '<a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="confirmDialog(\'Delete this record?\', \'Confirm Delete\', \''.WEBSITE_URL.'projects_delete.php?op=2&amp;id='.$row['prj_id'].'\');" title="Delete"><span class="fa fa-close"></span></a>';

                            $objective = nl2br($row['prj_objective'].'');
                            $expected_output = nl2br($row['prj_expected_output'].'');
                            $beneficiaries = get_beneficiaries($row['prj_id']);
                            $collaborators = get_collaborators($row['prj_id']);


        ?>                <tr>
                            <td class="nowrap"><?php echo $row['prj_title']; ?></td>
                            <td><?php echo $row['prj_type_name']; ?></td>
                            <td class="nowrap"><?php echo $row['prj_year_approved']; ?></td>
                            <td><?php echo $beneficiaries; ?></td>
                            <td><?php echo $collaborators; ?></td>
                            <td class="nowrap text-right"><?php echo $action; ?></td>
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
                5: { sorter: false}
                }
            };
        </script>

    <?php
    }

    function get_beneficiaries($id){
        $sql = "SELECT * FROM vwpsi_project_beneficiaries WHERE prj_id = $id";
        $res = mysqli_query($GLOBALS['cn'], $sql);

        if (!$res) return '';
        $s = '';
        while ($row = mysqli_fetch_array($res)){
            if (strlen($s) > 0){
                $s .= '<br>';
            }
            $s .= $row['coop_name'];
        }
        mysqli_free_result($res);
        return $s;
    }

    function get_collaborators($id){
        $sql = "SELECT * FROM vwpsi_project_collaborators WHERE prj_id = $id";
        $res = mysqli_query($GLOBALS['cn'], $sql);

        if (!$res) return '';
        $s = '';
        while ($row = mysqli_fetch_array($res)){
            if (strlen($s) > 0){
                $s .= '<br>';
            }
            $s .= '<abbr title="'.$row['col_name'].'">'.$row['col_abbr'].'</abbr>';
        }
        mysqli_free_result($res);
        return $s;
    }

?>