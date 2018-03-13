<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>Projects (Add) | Impression</title>
<link rel="shortcut icon" href="http://localhost/impression/favicon.ico">
<link rel="stylesheet" href="http://localhost/impression/css/chosen.min.css" type="text/css">
<link rel="stylesheet" href="http://localhost/impression/css/tablesorter/style.css" type="text/css">
<link rel="stylesheet" href="http://localhost/impression/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="http://localhost/impression/css/datepicker3.css" type="text/css">
<link rel="stylesheet" href="http://localhost/impression/css/font-awesome.min.css" type="text/css">
<link rel="stylesheet" href="http://localhost/impression/css/roboto.css" type="text/css">
<link rel="stylesheet" href="http://localhost/impression/css/impression.css" type="text/css">
</head>
<body>
<div id="header-wrapper" class="container-fluid">
  <header id="header"> <a href="index.php" title="Impression"><img src="images/impression.png" class="img-responsive" alt="Impression"></a> </header>
</div>
<div id="nav-wrapper" class="container-fluid">
  <nav id="nav" class="navbar navbar-inverse"  role="navigation">
    <ul class="nav navbar-nav navbar-right">
      <li><a href="http://localhost/impression/index.php" title="Home">Home</a></li>
      <li><a href="http://localhost/impression/cooperators.php" title="Cooperators">Cooperators</a></li>
      <li><a href="http://localhost/impression/projects.php" title="Projects">Projects</a></li>
      <li class="drop-down"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Services">Services <span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="http://localhost/impression/trainings.php" title="Training">Training</a></li>
          <li><a href="http://localhost/impression/packaging.php" title="Packaging &amp; Labeling">Packaging &amp; Labeling</a></li>
          <li><a href="http://localhost/impression/consultancy.php" title="Consultancy">Consultancy</a></li>
          <li><a href="http://localhost/impression/calibration.php" title="Testing &amp; Calibration">Testing &amp; Calibration</a></li>
          <li><a href="http://localhost/impression/scholarship.php" title="Scholarship">Scholarship</a></li>
          <li><a href="http://localhost/impression/scholarship-monitoring.php" title="Scholarship Monitoring">Scholarship Monitoring</a></li>
          <li><a href="http://localhost/impression/science-promo.php" title="Science Promotion">Science Promotion</a></li>
          <li><a href="http://localhost/impression/library.php" title="Library">Library</a></li>
        </ul>
      </li>
      <li><a href="http://localhost/impression/service_providers.php" title="Providers">Providers</a></li>
      <li><a href="http://localhost/impression/planning.php" title="Planning">Planning</a></li>
      <li class="drop-down"> <a href="http://localhost/impression/#" class="dropdown-toggle" data-toggle="dropdown" title="Settings">Settings <span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="http://localhost/impression/agency.php" title="Profile">Profile</a></li>
          <li><a href="http://localhost/impression/resp_unit.php" title="Implementor">Implementor</a></li>
          <li><a href="http://localhost/impression/collab_agency.php" title="Agencies">Agencies</a></li>
          <li><a href="http://localhost/impression/status.php" title="Status">Status</a></li>
          <li><a href="http://localhost/impression/sector_type.php" title="Sectors">Sectors</a></li>
          <li><a href="http://localhost/impression/project_type.php" title="Projects">Projects</a></li>
          <li><a href="http://localhost/impression/org_type.php" title="Beneficiary">Beneficiary</a></li>
          <li><a href="http://localhost/impression/docu_type.php" title="Document">Document</a></li>
          <li><a href="http://localhost/impression/equip_type.php" title="Equipment">Equipment</a></li>
          <li><a href="http://localhost/impression/activity_type.php" title="Activity">Activity</a></li>
          <li><a href="http://localhost/impression/train_type.php" title="Training">Training</a></li>
          <li><a href="http://localhost/impression/package_type.php" title="Packaging">Packaging</a></li>
          <li><a href="http://localhost/impression/interven_type.php" title="Intervention">Intervention</a></li>
          <li><a href="http://localhost/impression/consul_type.php" title="Services">Services</a></li>
          <li><a href="http://localhost/impression/course_category.php" title="Course Category">Course Category</a></li>
          <li><a href="http://localhost/impression/course.php" title="Course">Course</a></li>
          <li><a href="http://localhost/impression/expertise.php" title="Expertise">Expertise</a></li>
          <li><a href="http://localhost/impression/school.php" title="School">School</a></li>
          <li><a href="http://localhost/impression/scholarship_prog.php" title="Scholarship Program">Scholarship Program</a></li>
          <li><a href="http://localhost/impression/scholarship_stat.php" title="Scholarship Status">Scholarship Status</a></li>
          <li><a href="http://localhost/impression/sci_promo_type.php" title="Science Promotion">Science Promotion</a></li>
        </ul>
      </li>
      <li><a href="http://localhost/impression/users.php" title="Logout">Users</a></li>
      <li><a href="http://localhost/impression/logout.php" title="Logout">Logout</a></li>
    </ul>
  </nav>
</div>
<div id="body-wrapper" class="container-fluid"> 
  <script>
    var _city_id = 0;
    var _barangay_id = 0;
</script>
  <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <h3 class="panel-title pull-left">Projects (Add) </h3>
      <div class="pull-right"> <a class="btn btn-primary btn-sm" href="projects.php" title="Projects"><span class="fa fa-arrow-circle-left"></span> Back</a> </div>
    </div>
    <div class="panel-body">
      <form method="POST" action="projects_save.php?op=0&amp;id=0" accept-charset="UTF-8" class="form" role="form">
        <div class="form-group">
          <label for="prj_title" class="control-label">Project Title *</label>
          &nbsp;&nbsp;<span class="text-danger"><small></small></span>
          <input class="form-control input-sm" placeholder="Project Title" maxlength="255" required="required" name="prj_title" id="prj_title" type="text" value="">
          <span class="glyphicon form-control-feedback"></span> </div>
        <div class="form-group">
          <label for="prj_code" class="control-label">Project Code</label>
          &nbsp;&nbsp;<span class="text-danger"><small></small></span>
          <input class="form-control input-sm" placeholder="Project Code" maxlength="255" name="prj_code" id="prj_code" type="text" value="">
          <span class="glyphicon form-control-feedback"></span> </div>
        <div class="form-group form-group-sm">
          <label for="prj_type_id" class="control-label">Project Type</label>
          <select class="form-control input-sm" id="prj_type_id" name="prj_type_id">
            <option value="8">Roll-out</option>
            <option value="7">Grants-In-Aid (GIA)</option>
            <option value="6">Small Enterprise Technology Upgrading Program (SETUP)</option>
            <option value="9">TAPI-assisted</option>
            <option value="10">Others</option>
          </select>
        </div>
        <div class="form-group form-group-sm">
          <label for="sector_id" class="control-label">Sector</label>
          <select class="form-control input-sm" id="sector_id" name="sector_id">
            <option value="6">Food</option>
            <option value="5">Metals and Engineering</option>
            <option value="7">Gifts, Decors and Housewares</option>
            <option value="8">Furniture</option>
            <option value="9">Information and Communication </option>
          </select>
        </div>
        <div class="form-group form-group-sm">
          <label for="coop_id" class="control-label">Beneficiaries</label>
          <select class="form-control input-sm chosen-select" id="coop_id" name="coop_id[]" multiple="multiple" required="required">
            <option value="1">Test Coop</option>
            <option value="3">Circuit-Help Inc</option>
            <option value="4">company a</option>
          </select>
        </div>
        <div class="form-group form-group-sm">
        <label for="agency_id" class="control-label">Collaborating Agencies</label>
        <div class="form-group form-group-sm">
          <select class="form-control input-sm chosen-select" id="agency_id" name="agency_id[]" multiple="multiple">
            <option value="1">Department of Science and Technology (CALABARZON)</option>
          </select>
        </div>
        <div class="form-group form-group-sm">
          <label for="ug_id" class="control-label">Implementor</label>
          <select class="form-control input-sm" id="ug_id" name="ug_id">
            <option value="1">Administrators</option>
            <option value="2">RO-SETUP</option>
            <option value="3">RO-GIA</option>
            <option value="4">Scholarship</option>
            <option value="5">Laboratory</option>
            <option value="6">PARCU</option>
            <option value="7">Packaging & Labeling</option>
            <option value="8">Planning</option>
            <option value="9">PSTC-BATANGAS</option>
            <option value="10">PSTC-CAVITE</option>
            <option value="11">PSTC-LAGUNA</option>
            <option value="12">PSTC-QUEZON</option>
            <option value="13">PSTC-RIZAL</option>
          </select>
        </div>
        <div class="form-group">
          <label for="prj_year_approved" class="control-label">Year Approved *</label>
          <input class="form-control input-sm" placeholder="Year Approved" maxlength="4" min="1800" max="2015" required="required" name="prj_year_approved" id="prj_year_approved" type="number" value="2015">
          <span class="glyphicon form-control-feedback"></span> </div>
        <div class="form-group">
          <label for="prj_objective" class="control-label">Objective *</label>
          <textarea class="form-control input-sm" placeholder="Objective" required="required" name="prj_objective" id="prj_objective" cols="50" rows="4"></textarea>
        </div>
        <div class="form-group">
          <label for="prj_expected_output" class="control-label">Expected Output *</label>
          <textarea class="form-control input-sm" placeholder="Expected Output" required="required" name="prj_expected_output" id="prj_expected_output" cols="50" rows="4"></textarea>
        </div>
        <div class="form-group">
          <label for="prj_product_line" class="control-label">Products *</label>
          <textarea class="form-control input-sm" placeholder="Products" required="required" name="prj_product_line" id="prj_product_line" cols="50" rows="4"></textarea>
        </div>
        <div class="form-group form-group-sm">
          <label for="prj_status_id" class="control-label">Project Status</label>
          <select class="form-control input-sm" id="prj_status_id" name="prj_status_id">
            <option value="1">On-going</option>
            <option value="3">New</option>
            <option value="4">Graduated</option>
            <option value="5">Deferred</option>
            <option value="6">Terminated</option>
          </select>
        </div>
        <h3><span class="label label-default full-width">Project Location</span></h3>
        <div class="form-group form-group-sm">
          <label for="province_id" class="control-label">Province *</label>
          <select class="form-control input-sm" id="province_id" name="province_id" required="required">
            <option value="18">Abra</option>
            <option value="104">Agusan del Norte</option>
            <option value="105">Agusan del Sur</option>
            <option value="58">Aklan</option>
            <option value="52">Albay</option>
            <option value="59">Antique</option>
            <option value="19">Apayao</option>
            <option value="35">Aurora</option>
            <option value="60">Bacolod City</option>
            <option value="20">Baguio City</option>
            <option value="110">Basilan</option>
            <option value="36">Bataan</option>
            <option value="30">Batanes</option>
            <option value="42">Batangas</option>
            <option value="21">Benguet</option>
            <option value="73">Biliran</option>
            <option value="66">Bohol</option>
            <option value="86">Bukidnon</option>
            <option value="37">Bulacan</option>
            <option value="106">Butuan City</option>
            <option value="31">Cagayan</option>
            <option value="87">Cagayan de Oro City</option>
            <option value="1">Caloocan City</option>
            <option value="53">Camarines Norte</option>
            <option value="54">Camarines Sur</option>
            <option value="88">Camiguin</option>
            <option value="61">Capiz</option>
            <option value="55">Catanduanes</option>
            <option value="43">Cavite</option>
            <option value="67">Cebu</option>
            <option value="68">Cebu City</option>
            <option value="93">Compostela Valley</option>
            <option value="98">Cotabato</option>
            <option value="99">Cotabato City</option>
            <option value="25">Dagupan City</option>
            <option value="94">Davao City</option>
            <option value="95">Davao del Norte</option>
            <option value="96">Davao del Sur</option>
            <option value="97">Davao Oriental</option>
            <option value="107">Dinagat Islands</option>
            <option value="74">Eastern Samar</option>
            <option value="100">General Santos City</option>
            <option value="62">Guimaras</option>
            <option value="22">Ifugao</option>
            <option value="89">Iligan City</option>
            <option value="26">Ilocos Norte</option>
            <option value="27">Ilocos Sur</option>
            <option value="63">Iloilo</option>
            <option value="64">Iloilo City</option>
            <option value="32">Isabela</option>
            <option value="81">Isabela City</option>
            <option value="23">Kalinga</option>
            <option value="28">La Union</option>
            <option value="46">Laguna</option>
            <option value="90">Lanao del Norte</option>
            <option value="111">Lanao del Sur</option>
            <option value="69">Lapu-Lapu City</option>
            <option value="2">Las Pinas City</option>
            <option value="75">Leyte</option>
            <option value="112">Maguindanao</option>
            <option value="3">Makati City</option>
            <option value="4">Malabon City</option>
            <option value="5">Mandaluyong City</option>
            <option value="70">Mandaue City</option>
            <option value="6">Manila</option>
            <option value="7">Marikina City</option>
            <option value="47">Marinduque</option>
            <option value="56">Masbate</option>
            <option value="91">Misamis Occidental</option>
            <option value="92">Misamis Oriental</option>
            <option value="24">Mountain Province</option>
            <option value="8">Muntinlupa City</option>
            <option value="9">Navotas City</option>
            <option value="65">Negros Occidental</option>
            <option value="71">Negros Oriental</option>
            <option value="76">Northern Samar</option>
            <option value="38">Nueva Ecija</option>
            <option value="33">Nueva Vizcaya</option>
            <option value="48">Occidental Mindoro</option>
            <option value="49">Oriental Mindoro</option>
            <option value="77">Ormoc City</option>
            <option value="50">Palawan</option>
            <option value="39">Pampanga</option>
            <option value="29">Pangasinan</option>
            <option value="10">Paranaque City</option>
            <option value="11">Pasay City</option>
            <option value="12">Pasig City</option>
            <option value="13">Pateros</option>
            <option value="44">Quezon</option>
            <option value="14">Quezon City</option>
            <option value="34">Quirino</option>
            <option value="45">Rizal</option>
            <option value="51">Romblon</option>
            <option value="78">Samar</option>
            <option value="15">San Juan City</option>
            <option value="101">Sarangani</option>
            <option value="72">Siquijor</option>
            <option value="57">Sorsogon</option>
            <option value="102">South Cotabato</option>
            <option value="79">Southern Leyte</option>
            <option value="103">Sultan Kudarat</option>
            <option value="113">Sulu</option>
            <option value="108">Surigao del Norte</option>
            <option value="109">Surigao del Sur</option>
            <option value="80">Tacloban City</option>
            <option value="16">Taguig City</option>
            <option value="40">Tarlac</option>
            <option value="114">Tawi-Tawi</option>
            <option value="17">Valenzuela City</option>
            <option value="41">Zambales</option>
            <option value="82">Zamboanga City</option>
            <option value="83">Zamboanga del Norte</option>
            <option value="84">Zamboanga del Sur</option>
            <option value="85">Zamboanga Sibugay</option>
          </select>
        </div>
        <div class="form-group form-group-sm">
          <label for="city_id" class="control-label">City/Town *</label>
          <select class="form-control input-sm" id="city_id" name="city_id" required="required">
          </select>
        </div>
        <div class="form-group form-group-sm">
          <label for="barangay_id" class="control-label">Barangay *</label>
          <select class="form-control input-sm" id="barangay_id" name="barangay_id" required="required">
          </select>
        </div>
        <h3><span class="label label-default full-width">Costs</span></h3>
        <div class="form-group">
          <label for="prj_cost_setup" class="control-label">SETUP Project Cost</label>
          <input class="form-control input-sm" placeholder="SETUP Project Cost" min="0" step="any" name="prj_cost_setup" id="prj_cost_setup" type="number" value="0">
          <span class="glyphicon form-control-feedback"></span> </div>
        <div class="form-group">
          <label for="prj_cost_gia" class="control-label">GIA Project Cost</label>
          <input class="form-control input-sm" placeholder="GIA Project Cost" min="0" step="any" name="prj_cost_gia" id="prj_cost_gia" type="number" value="0">
          <span class="glyphicon form-control-feedback"></span> </div>
        <div class="form-group">
          <label for="prj_cost_rollout" class="control-label">Roll-out Project Cost</label>
          <input class="form-control input-sm" placeholder="Roll-out Project Cost" min="0" step="any" name="prj_cost_rollout" id="prj_cost_rollout" type="number" value="0">
          <span class="glyphicon form-control-feedback"></span> </div>
        <div class="form-group">
          <label for="prj_cost_benefactor" class="control-label">Beneficiaries&rsquo; Counterpart Project Cost</label>
          <input class="form-control input-sm" placeholder="Beneficiaries&rsquo; Counterpart Project Cost" min="0" step="any" name="prj_cost_benefactor" id="prj_cost_benefactor" type="number" value="0">
          <span class="glyphicon form-control-feedback"></span> </div>
        <div class="form-group">
          <label for="prj_cost_other" class="control-label">Other Project Cost</label>
          <input class="form-control input-sm" placeholder="Other Project Cost" min="0" step="any" name="prj_cost_other" id="prj_cost_other" type="number" value="0">
          <span class="glyphicon form-control-feedback"></span> </div>
        <h3><span class="label label-default full-width">Project Map Coordinates</span></h3>
        <div class="form-group">
          <label for="prj_longitude" class="control-label">Longitude</label>
          <input class="form-control input-sm" placeholder="Longitude" min="0" step="any" name="prj_longitude" id="prj_longitude" type="number" value="">
          <span class="glyphicon form-control-feedback"></span> </div>
        <div class="form-group">
          <label for="prj_latitude" class="control-label">Latitude</label>
          <input class="form-control input-sm" placeholder="Latitude" min="0" step="any" name="prj_latitude" id="prj_latitude" type="number" value="">
          <span class="glyphicon form-control-feedback"></span> </div>
        <div class="form-group">
          <label for="prj_elevation" class="control-label">Elevation</label>
          <input class="form-control input-sm" placeholder="Elevation" min="0" step="any" name="prj_elevation" id="prj_elevation" type="number" value="">
          <span class="glyphicon form-control-feedback"></span> </div>
        <input class="btn btn-primary btn-block" type="submit" name="save" id="save" value="Save">
        <input type="hidden" name="prj_id" value="0">
      </form>
    </div>
    <div class="panel-footer"> </div>
  </div>
</div>
<!-- BEGIN FOOTER -->
<div id="footer-wrapper">
  <div class="container-fluid">
    <footer id="footer" class="text-center"> &copy; 2015 Project &amp; Service Information System<br>
      Designed and Developed by DOST CALABARZON &middot; MIS Unit </footer>
  </div>
</div>
<div id="confirm-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="confirm-modal-label" aria-hidden="true">
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
<!-- / #confirm-modal --> 
<!-- END FOOTER --> 
<!--
    jquery-1.11.2.js
    jquery-2.1.1.min.js
    --> 

<script src="http://localhost/impression/js/jquery-1.11.2.min.js"></script> 
<script src="http://localhost/impression/js/jquery-ui.min.js"></script> 
<script src="http://localhost/impression/js/bootstrap.min.js"></script> 
<script src="http://localhost/impression/js/jquery.tablesorter.min.js"></script> 
<script src="http://localhost/impression/js/jquery.tablesorter.pager.js"></script> 
<script src="http://localhost/impression/js/chosen.jquery.min.js"></script> 
<script src="http://localhost/impression/js/bootstrap-datepicker.js"></script> 
<script src="http://localhost/impression/js/jquery.validate.min.js"></script> 
<script src="http://localhost/impression/js/jquery.datePicker.js"></script> 
<script src="http://localhost/impression/js/date.js"></script> 
<script src="http://localhost/impression/js/impression.js"></script>
</body>
</html>
