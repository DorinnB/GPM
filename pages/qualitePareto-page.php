<div id="page-content-wrapper" style="height:100%">
	<div style="height:5%;text-align: center; padding-top:5px; background-color:#44546A; color:white;">

		<form type="GET" class="form-inline" action="index.php">
			<input type="hidden" name="page" value="qualitePareto">

			<div class="form-group">
				<label for="startDate">Start :</label>
				<input type="text" class="form-control" name="startDate" id="startDate" value="<?= isset($_GET['startDate'])?$_GET['startDate']:date('Y-m-d',strtotime("-3 months"))	?>">
			</div>
			<div class="form-group">
				<label for="endDate">End :</label>
				<input type="text" class="form-control" name="endDate" id="endDate" value="<?= isset($_GET['endDate'])?$_GET['endDate']:date('Y-m-d');	?>">
			</div>

			<button type="submit" class="btn btn-default">Submit</button>
		</div>


	<div style="height:95%">
		<?php include('controller/qualitePareto-controller.php'); ?>
	</div>
</div>
<?php
require('views/login-view.php');
?>
