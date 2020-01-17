<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link href="lib/dropdown-with-search-using-jquery/select2.min.css" rel="stylesheet" />
<script src="lib/dropdown-with-search-using-jquery/select2.min.js"></script>

<link href="css/calibration.css" rel="stylesheet">
<?php
include('controller/calibration-controller.php');
?>
<!-- Page Content -->
<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">
		<div class="row" style="height:25%;">
			<?php
			include('views/calibrationChoice-view.php');
			?>
		</div>
		<div class="row" style="height:75%;">
			<?php
			include('views/calibrationHistory-view.php');
			?>
		</div>
	</div>
</div>

<script type="text/javascript" src="js/calibration.js"></script>

<?php require('views/login-view.php');	?>
