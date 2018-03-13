<?php
require_once('inc_page.php');
require_once('inc_secure.php');

if (!can_access('Project Repayment', 'view')){
    redirect(WEBSITE_URL.'index.php');
}

$pid = requestInteger('pid', 'location: '.WEBSITE_URL.'projects.php');
$cid = requestInteger('cid');

if (!dbValueExists('psi_projects', 'prj_id', $pid, false)){
    redirect(WEBSITE_URL.'project_repayments.php');
    die();
}

$page_title = 'Project Repayment';
print_header($page_title, 1);

if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}

$rep = getRepayment($pid);

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
                <h4>
                <?php 
                    if ($rep){
                        
                        $s = '
                            Project Cost : <span class="text-primary">'.zeroCurr($rep['rep_total_amount']).'</span>
                            <br>
                            Current Amount Due : <span class="text-primary">'.zeroCurr($rep['rep_total_due']).'</span>
                            <br>
                            Total Amount Refunded : <span class="text-primary">'.zeroCurr($rep['rep_total_paid']).'</span>
                            <br>
                            Refund Rate : <span class="text-primary">'.zeroNumber($rep['rep_refund_rate'], 0).'%</span>
                            <br>
                            Balance : <span class="text-primary">'.zeroCurr($rep['rep_balance']).'</span>
                            <br>
                            <br>
                            Option to Purchase Amount Due: <span class="text-primary">'.zeroCurr($rep['rep_otb_amount']).'</span>
                            <br>
                            Option to Purchase Amount Paid : <span class="text-primary">'.zeroCurr($rep['rep_otb_amount_paid']).'</span>
                        ';
                        echo $s;
                    }
                ?>    
                </h4>
            </div>
            <div class="pull-right hidden-print">
                <a id="print-page-btn" name="print-page-btn" class="btn btn-primary btn-sm hidden-print" href="javascript:void(0);" title="Print Page"><span class="fa fa-print"></span> Print</a>
            </div>
        </div>
    </div>
    <?php 
        dispay_schedule($pid, $rep);
    ?>
    <div class="panel-footer">
    </div>
</div>
<?php
    print_footer();
    

function inPeriod($year, $month, $start_year, $start_month, $end_year, $end_month){
    if ($year < $start_year) return false;
    if ($year > $end_year) return false;
    if ($year == $start_year){
        if ($month < $start_month) return false;
    }
    if ($year == $end_year){
        if ($month > $end_month) return false;
    }
    return true;
}

function lateMonth($tyr, $tmo){

    $yr = intval(date('Y'));
    $mo = intval(date('j'));

    if ($tyr > $yr) return true;
    if ($tyr == $yr){
        if ($tmo > $mo){
            return true;
        }
    }
    return false;
}

function dispay_schedule($pid, $rep){
    if (!$rep) return;

    $payments = getAllPayments($rep['rep_id']);

    $defcount = $rep['rep_deferment_monthcount'];

    // build table header ****************************************************************************************************

    $rep_month_count = $rep['rep_month_count'];

    $month_count = $rep_month_count;
    $month_count += $defcount;

    $remaining_months = $rep_month_count - $rep['rep_payment_count'];

    $month_count += 2;
 
    $start_year = $rep['rep_start_year'];
    $start_month = $rep['rep_start_month'];

    $end_time = strtotime($start_year.'-'.$start_month.'-'.'1 + '.($month_count).' Months');
    $end_year = date('Y', $end_time);
    $end_month = date('m', $end_time);
    $max_cols = $end_year - $start_year + 1;
    $max_rows = 12;

    $month = $start_month;
    $year = $start_year;
    $table = '';
    $row_skip = 0;
    $defer_level = 0;

    $row_due = 0;
    $row_balance = 0;

    $col_count = 11;
    $col_count1 = $col_count - 1;

    $curr_yr = intval(date('Y'));
    $curr_mo = intval(date('j'));

    $col_due = 0;
    $col_paid = 0;
    $col_penalty = 0;
    $col_overdue = 0;
    for ($ctr = 0; $ctr < $month_count; $ctr++){

        // year month stamp
        $period = '
                    <td class="text-center">'.($ctr + 1).'</td>
                    <td class="text-center">'.$year.'</td>
                    <td class="text-center" title="'.getMonthName($month).'"><abbr title="'.getMonthName($month).'">'.getMonthName($month, 'M').'</abbr></td>
        ';

        // handle transactions spanning multiple consecutive months
        $rowspan = '';
        if ($row_skip > 1) {
            $row_skip--;
            $table .= '
                <tr>
                    '.$period.'
                </tr>
            ';
            // messy
            $month++;
            if ($month > 12){
                $month = 1;
                $year++; 
            }
            continue;
        }

        // handle months with no transactions
        if (!month_exists($payments, $year, $month)){
            $row_due += $rep['rep_monthly_payment'];
            $row_balance = $rep['rep_total_amount'] - $row_due;
            $price = $rep['rep_monthly_payment'];

            


            /*
            if ($row_balance < $rep['rep_monthly_payment']){
                $price = $row_balance;
            }
            */

            $table .= '
                <tr>
                    '.$period.'
                    <td class="nowrap text-center" colspan="2">
            ';
            $action = '';
            if ($rep['rep_balance'] > 0){
            }


            if (($rep['rep_balance'] == 0) && ($rep['rep_otb_amount_paid'] == 0)){
            }

            if (strlen($action) > 0){
                $table .= $action;

                $table .= '
                        </td>
                        <td class="nowrap" colspan="'.$col_count1.'">&nbsp;</td>
                    </tr>
            ';
            } else {
                $table .= '
                        <td class="nowrap" colspan="'.$col_count.'">&nbsp;</td>
                    </tr>
                ';
            }

            // messy
            $month++;
            if ($month > 12){
                $month = 1;
                $year++; 
            }
            continue;
        }

        // start transactions spanning multiple consecutive months
        if ($payments[$year][$month]['pay_count'] > 1){
            $rowspan = ' rowspan="'.$payments[$year][$month]['pay_count'].'"';
            $row_skip = $payments[$year][$month]['pay_count'];
        }

        //<td class="nowrap text-right"'.$rowspan.'>'.zeroCurr($payments[$year][$month]['pay_overdue_amount_due']).'</td>
        //<td class="nowrap text-right"'.$rowspan.'>'.zeroCurr($payments[$year][$month]['pay_overdue_amount_paid']).'</td>
        //<td class="nowrap text-center"'.$rowspan.'>'.zeroDate($payments[$year][$month]['pay_overdue_date_paid']).'</td>
        
        if ($payments[$year][$month]['pay_otb'] == 0){ // handle payments
            $col_due = $payments[$year][$month]['pay_amount_due'] +  $col_overdue;
            $col_overdue = $col_due - $payments[$year][$month]['pay_amount_paid'];

            $table .= '
                <tr>
                    '.$period.'
                    <td class="nowrap text-center"'.$rowspan.'>&nbsp;</td>
                    <td class="nowrap text-center"'.$rowspan.'>'.zeroDate($payments[$year][$month]['pay_amount_date_paid']).'</td>
                    <td class="text-center" '.$rowspan.'>'.$payments[$year][$month]['pay_receipt_no'].'</td>
                    <td class="text-center" '.$rowspan.'>'.$payments[$year][$month]['pay_check_no'].'</td>
                    <td class="nowrap text-center"'.$rowspan.'>'.zeroDate($payments[$year][$month]['pay_check_date']).'</td>

                    <td class="nowrap text-right"'.$rowspan.'>'.zeroCurr($payments[$year][$month]['pay_amount_due']).'</td>
                    <td class="nowrap text-right"'.$rowspan.'>'.zeroCurr($payments[$year][$month]['pay_amount_paid']).'</td>

                    <td class="nowrap text-right"'.$rowspan.'>'.zeroCurr($payments[$year][$month]['pay_overdue_amount_due']).'</td>

                    <td class="nowrap text-right"'.$rowspan.'>'.zeroCurr($payments[$year][$month]['pay_penalty_amount_due']).'</td>
                    <td class="nowrap text-right"'.$rowspan.'>'.zeroCurr($payments[$year][$month]['pay_penalty_amount_paid']).'</td>
                    <td class="nowrap text-center"'.$rowspan.'>'.zeroDate($payments[$year][$month]['pay_penalty_date_paid']).'</td>
                    <td class="nowrap text-center"'.$rowspan.'>';

            if (can_access('Project Repayment', 'edit')){
            }
            if (can_access('Project Repayment', 'delete')){
            }
            $table .= '
                    </td>
                </tr>
            ';
        } elseif ($payments[$year][$month]['pay_otb'] == 2){ // handle deferments
            $defer_level++;
            $table .= '
                <tr>
                    '.$period.'
                    <td'.$rowspan.' colspan="'.$col_count.'">'.getDefermentLabel($defer_level).'</td>
                    <td class="nowrap text-center"'.$rowspan.'>';
            if (can_access('Project Repayment', 'edit')){
            }
            if (can_access('Project Repayment', 'delete')){
            }

            $table .= '
                    </td>
                </tr>
            ';
        } elseif ($payments[$year][$month]['pay_otb'] == 1){ // handle option to purchase
            $table .= '
                <tr>
                    '.$period.'
                    <td class="nowrap text-center"'.$rowspan.'>OTP</td>
                    <td class="nowrap text-center"'.$rowspan.'>'.zeroDate($payments[$year][$month]['pay_amount_date_paid']).'</td>
                    <td'.$rowspan.'>'.$payments[$year][$month]['pay_receipt_no'].'</td>
                    <td class="nowrap text-right"'.$rowspan.'>'.zeroCurr($payments[$year][$month]['pay_amount_due']).'</td>
                    <td class="nowrap text-right"'.$rowspan.'>'.zeroCurr($payments[$year][$month]['pay_amount_paid']).'</td>
                    <td'.$rowspan.'>'.$payments[$year][$month]['pay_check_no'].'</td>
                    <td class="nowrap text-center"'.$rowspan.'>'.zeroDate($payments[$year][$month]['pay_check_date']).'</td>

                    <td class="nowrap text-right"'.$rowspan.'>'.zeroCurr($payments[$year][$month]['pay_overdue_amount_due']).'</td>

                    <td class="nowrap text-right"'.$rowspan.'>'.zeroCurr($payments[$year][$month]['pay_penalty_amount_due']).'</td>
                    <td class="nowrap text-right"'.$rowspan.'>'.zeroCurr($payments[$year][$month]['pay_penalty_amount_paid']).'</td>
                    <td class="nowrap text-center"'.$rowspan.'>'.zeroDate($payments[$year][$month]['pay_penalty_date_paid']).'</td>
                    <td class="nowrap text-center"'.$rowspan.'>';
            if (can_access('Project Repayment', 'edit')){
            }
            if (can_access('Project Repayment', 'delete')){
            }

            $table .= '
                    </td>
                </tr>
            ';
        }
        // messy
        $month++;
        if ($month > 12){
            $month = 1;
            $year++; 
        }
    }

?>
    <div class="panel-body">
        <?php //echo $texts; ?>
        <?php //echo $links; ?>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover table-condensed">
            <thead>
                <tr>
                    <td class="text-center">#</td>
                    <td class="text-center">Year</td>
                    <td class="text-center">Month</td>
                    <td class="text-center">&nbsp;</td>
                    <td class="text-center">Date Paid</td>
                    <td class="text-center">Receipt No.</td>
                    <td class="text-center">Check No.</td>
                    <td class="text-center">Check Date.</td>

                    <td class="text-center">Amount Due</td>
                    <td class="text-center">Amount Paid</td>

                    <td class="text-center">Overdue Amount</td>
                    <!--
                    <td class="text-center">Overdue Amount Paid</td>
                    <td class="text-center">Overdue Date Paid</td>
                    -->
                    <td class="text-center">Penalty Amount</td>
                    <td class="text-center">Penalty Amount Paid</td>
                    <td class="text-center">Penalty Date Paid</td>
                    <td>&nbsp;</td>
                </tr>
            </thead>
            <tbody>
                <?php
                    echo $table;
                ?>
            </tbody>
        </table>
    </div>
<?php     
}


?>