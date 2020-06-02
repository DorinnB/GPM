<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link href="lib/dropdown-with-search-using-jquery/select2.min.css" rel="stylesheet" />
<script src="lib/dropdown-with-search-using-jquery/select2.min.js"></script>

<link href="css/calibration.css" rel="stylesheet">
<?php
include('controller/accounting-controller.php');
?>
<!-- Page Content -->
<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">
		<div class="row" style="height:100%;">
			<?php
			include('views/accounting-view.php');
			?>
		</div>
	</div>
</div>



<?php require('views/login-view.php');	?>
