<link href="css/listeFlagQualite.css" rel="stylesheet">


<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">

		<div class="col-md-12" style="height:50%">

			<table id="table_machineforecast" class="table table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%;">
				<thead>
					<tr>
						<th>Machine</th>
						<th>Text</th>
						<th>Icone</th>
						<th>Priority</th>
					</tr>
				</thead>

			</table>

</div>
		<div class="col-md-12" style="height:50%">
			<table id="table_labforecast" class="table table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%;">
				<thead>
					<tr>
						<th>Text</th>
						<th>Icone</th>
						<th>Priority</th>
					</tr>
				</thead>

			</table>

		</div>

		<script type="text/javascript" src="js/labforecast.js"></script>
		<script type="text/javascript" src="js/machineforecast.js"></script>



	</div>
</div>
<?php
require('views/login-view.php');
?>
