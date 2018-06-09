<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css" rel="stylesheet">
<link href="lib/dropdown-with-search-using-jquery/select2.min.css" rel="stylesheet" />
<script src="lib/dropdown-with-search-using-jquery/select2.min.js"></script>

<link href="css/updateJob.css" rel="stylesheet">
<?php
include('controller/updateJob-controller.php');
?>
<!-- Page Content -->
<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">
		<div class="col-md-12" style="height:6%;">
			<?php
			include('views/updateJobTop-view.php');
			?>
		</div>
		<div class="col-md-12" style="height:94%;">
			<div class="row" style="height:100%;">
				<div class="col-md-3" style="height:100%;">
					<?php
					include('views/updateJobLeft-view.php');
					?>
				</div>
				<div class="col-md-9" style="height:100%;">
					<?php
					include('controller/updateJobWorkflow-controller.php');
					?>
				</div>
				<!--<div class="col-md-2" style="height:100%">newJobRight-controller</div>-->
			</div>
		</div>
	</div>
</div>
<?php require('views/login-view.php');	?>
