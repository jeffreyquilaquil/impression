<?php
require_once('inc_page.php');
require_once('inc_secure.php');

$page_title = 'Project Summaries';
page_header($page_title, 0);
if (strlen($GLOBALS['errmsg']) > 0){
    ?>
        <div class="alert alert-info"><?php echo $GLOBALS['errmsg']; ?></div>
    <?php 
}


?>
<div class="panel panel-primary">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
        </div>
    </div>
</div>
<?php
// Repayment **********************************************************************************
loadDBValues("vwpsi_repayments_all", "SELECT * FROM vwpsi_repayments_all");

$rep_due = $GLOBALS['rep_all_due'];
$rep_paid = $GLOBALS['rep_all_paid'];
$ref_rate = 0;
if (($rep_paid > 0) && ($rep_due > 0)) {
    $ref_rate = ($rep_paid / $rep_due) * 100;
}

?>

<!-- row start -->
<div class="row">
    <div class="col-sm-6">
        <div class="graph-container">
            <div class="caption">Number of Projects Per Year Approved</div>
            <canvas id="year-approved-graph" class="graph"></canvas>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="graph-container">
            <div class="caption">Number of Projects Per Sector</div>
            <canvas id="sector-graph" class="graph" ></canvas>
        </div>
    </div>
</div> 
<!-- row end -->

<!-- row start -->
<div class="row">
    <div class="col-sm-6">
        <div class="graph-container">
            <div class="caption">Number of Projects Per Province</div> 
            <canvas id="province-graph" class="graph" ></canvas>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="graph-container">
            <div class="caption">Number of Projects Per Project Status</div>
            <canvas id="status-graph" class="graph"></canvas>
        </div>
    </div>
</div> 
<!-- row end -->

<!-- row start -->
<div class="row">
    <div class="col-sm-6">
        <div class="graph-container">
            <div class="caption">Repayment Totals Per Province</div> 
            <canvas id="repayment-province-graph" class="graph" ></canvas>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="graph-container">
            <div class="caption">Number of Projects Per Project Type</div>
            <canvas id="project-type-graph" class="graph"></canvas>
        </div>
    </div>
</div> 
<!-- row end -->

<div class="panel panel-primary">
    <div class="panel-heading clearfix">
        <div class="clearfix">
            <div class="pull-left">
                <h3 class="panel-title">Repayment</h3>
            </div>
        </div>
    </div>
</div>    
<div class="row">
    <div class="col-sm-4 text-center">
        <div class="panel panel-success">
            <div class="panel-heading">
                <span class="panel-title">Total Amount Due</span>
            </div>                    
            <div class="panel-body">
                <?php
                    echo zeroCurr($rep_due);
                 ?>
            </div>                    
        </div>
    </div>
    <div class="col-sm-4 text-center">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <span class="panel-title">Total Amount Refunded</span>
            </div>                    
            <div class="panel-body">
                <?php
                    echo zeroCurr($rep_paid);
                 ?>
            </div>                    
        </div>
    </div>
    <div class="col-sm-4 text-center">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <span class="panel-title">Refund Rate</span>
            </div>                    
            <div class="panel-body">
                <?php
                    echo zeroNumber($ref_rate, 0).'%';
                 ?>
            </div>                    
        </div>
    </div>
</div>



<script type="text/javascript">
var _tsOptions = {
        headers:{
        }
    };

var graphColors = [
    '#8B103E',
    '#E44856',
    '#FDA21E',
    '#68CFE0',
    '#3BB3E8',
    '#035483',

    '#8B103E',
    '#E44856',
    '#FDA21E',
    '#68CFE0',
    '#3BB3E8',
    '#035483',

    '#8B103E',
    '#E44856',
    '#FDA21E',
    '#68CFE0',
    '#3BB3E8',
    '#035483',
];

var graphColors1 = [
    '#888888',
    '#888888',
    '#888888',
    '#888888',
    '#888888',
    '#888888',
    '#888888',
    '#888888',
    '#888888',
    '#888888',
    '#888888',
    '#888888',
];
var graphColors2 = [
    '#8B103E'
];

<?php
echo getByProvinceData(); 

echo getByStatusData();

echo getByYearApprovedData();

echo getBySectorData();

echo getByProjectType();

echo getRepaymentByProvinceData();
?>
    
</script>

<?php
    page_footer();
    deleteFormCache();

function getByProvinceData(){

    $labels = '';
    $data = '';

    $sql = "SELECT * FROM vwpsi_prjcount_per_province";
    $rows = mysqli_query($GLOBALS['cn'], $sql);
    if (!$rows) return '';
    while($row = mysqli_fetch_array($rows)) {
        if (strlen($labels) > 0) $labels .= ', ';
        if (strlen($data) > 0) $data .= ', ';

        $labels .= '\''.$row['province_name'].'\'';
        $data .= '\''.$row['prj_count'].'\'';
        
    }
    mysqli_free_result($rows);
    $s = '
    var province_graph = {
        type : \'bar\',
        data : {
            labels : ['.$labels.'],
            datasets : [{
                data : ['.$data.'],
                backgroundColor: graphColors,
            }]
        },
        options : {
            legend : {
                display : false
            },
            scales : {
                yAxes : [{
                    ticks : {
                        beginAtZero:true
                    }
                }]
            }
        }        
    }
    ';

    return $s;
}

function getByStatusData(){
    $labels = '';
    $data = '';

    $sql = "SELECT * FROM vwpsi_prjcount_per_status";
    $rows = mysqli_query($GLOBALS['cn'], $sql);
    if (!$rows) return '';
    while($row = mysqli_fetch_array($rows)) {
        if (strlen($labels) > 0) $labels .= ', ';
        if (strlen($data) > 0) $data .= ', ';

        $labels .= '\''.$row['prj_status_name'].'\'';
        $data .= '\''.$row['prj_count'].'\'';
        
    }
    mysqli_free_result($rows);
    $s = '
    var status_graph = {
        type : \'horizontalBar\',
        data : {
            labels : ['.$labels.'],
            datasets : [{
                data : ['.$data.'],
                backgroundColor: graphColors,
            }]
        },
        options : {
            legend : {
                display : false
            },
            scales : {
                yAxes : [{
                    ticks : {
                        beginAtZero:true
                    }
                }]
            }
        }        
    }
    ';

    return $s;
}


function getByYearApprovedData(){
    $labels = '';
    $data = '';

    $sql = "SELECT * FROM vwpsi_prjcount_per_year_approved";
    $rows = mysqli_query($GLOBALS['cn'], $sql);
    if (!$rows) return '';
    while($row = mysqli_fetch_array($rows)) {
        if (strlen($labels) > 0) $labels .= ', ';
        if (strlen($data) > 0) $data .= ', ';

        $labels .= '\''.$row['prj_year_approved'].'\'';
        $data .= '\''.$row['prj_count'].'\'';
        
    }
    mysqli_free_result($rows);
    $s = '
    var year_approved_graph = {
        type : \'bar\',
        data : {
            labels : ['.$labels.'],
            datasets : [{
                data : ['.$data.'],
                backgroundColor: graphColors,
            }]
        },
        options : {
            legend : {
                display : false
            },
            scales : {
                yAxes : [{
                    ticks : {
                        beginAtZero:true
                    }
                }]
            }
        }        
    }
    ';

    return $s;
}


function getBySectorData(){
    $labels = '';
    $data = '';

    $sql = "SELECT * FROM vwpsi_prjcount_per_sector";
    $rows = mysqli_query($GLOBALS['cn'], $sql);
    if (!$rows) return '';
    while($row = mysqli_fetch_array($rows)) {
        if (strlen($labels) > 0) $labels .= ', ';
        if (strlen($data) > 0) $data .= ', ';

        $labels .= '\''.$row['sector_name'].'\'';
        $data .= '\''.$row['prj_count'].'\'';
        
    }
    mysqli_free_result($rows);
    $s = '
    var sector_graph = {
        type : \'horizontalBar\',
        data : {
            labels : ['.$labels.'],
            datasets : [{
                label : \'\',
                data : ['.$data.'],
                backgroundColor: graphColors,
            }]
        },
        options : {
            legend : {
                display : false
            },
            scales : {
                yAxes : [{
                    ticks : {
                        beginAtZero:true
                    }
                }]
            }
        }        
    }
    ';

    return $s;
}


function getByProjectType(){
    $labels = '';
    $data = '';

    $sql = "SELECT * FROM vwpsi_prjcount_per_projecttype";
    $rows = mysqli_query($GLOBALS['cn'], $sql);
    if (!$rows) return '';
    while($row = mysqli_fetch_array($rows)) {
        if (strlen($labels) > 0) $labels .= ', ';
        if (strlen($data) > 0) $data .= ', ';

        $labels .= '\''.$row['prj_type_name'].'\'';
        $data .= '\''.$row['prj_count'].'\'';
        
    }
    mysqli_free_result($rows);
    $s = '
    var project_type_graph = {
        type : \'horizontalBar\',
        data : {
            labels : ['.$labels.'],
            datasets : [{
                label : \'\',
                data : ['.$data.'],
                backgroundColor: graphColors,
            }]
        },
        options : {
            legend : {
                display : false
            },
            scales : {
                yAxes : [{
                    ticks : {
                        beginAtZero:true
                    }
                }]
            }
        }        
    }
    ';

    return $s;
}


function getRepaymentByProvinceData(){

    $labels = '';
    $data1 = '';
    $data2 = '';
    $data3 = '';

    $sql = "SELECT * FROM vwpsi_repayments_totals_per_province";
    $rows = mysqli_query($GLOBALS['cn'], $sql);
    if (!$rows) return '';
    while($row = mysqli_fetch_array($rows)) {
        if (strlen($labels) > 0) $labels .= ', ';
        if (strlen($data1) > 0) $data1 .= ', ';
        if (strlen($data2) > 0) $data2 .= ', ';
        if (strlen($data3) > 0) $data3 .= ', ';

        $rep_rate = 0;
        $rep_paid = $row['rep_total_paid'];
        $rep_due = $row['rep_total_due'];
        if (($rep_paid > 0) && ($rep_due > 0)) {
            $rep_rate = ($rep_paid / $rep_due) * 100;
        }

        $labels .= '\''.$row['province_name'].'\'';
        $data1 .= '\''.$rep_due.'\'';
        $data2 .= '\''.$rep_paid.'\'';
        $data3 .= '\''.$rep_rate.'\'';
        
    }
    mysqli_free_result($rows);
    $s = '
    var rep_province_graph = {
        type : \'bar\',
        data : {
            labels : ['.$labels.'],
            datasets : [{
                type : \'bar\',
                label: \'Total Amount Due\',
                data : ['.$data1.'],
                backgroundColor: graphColors1
            }, {
                type : \'bar\',
                label: \'Total Amount Refunded\',
                data : ['.$data2.'],
                backgroundColor: graphColors
            }
            ]
        },
        options : {
            legend : {
                display : false
            },
            scales : {
                yAxes : [{
                    ticks : {
                        beginAtZero:true
                    }
                }]
            }
        }        
    }
    ';

    return $s;
}


?>