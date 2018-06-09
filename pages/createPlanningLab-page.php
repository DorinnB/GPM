<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<link rel="stylesheet" href="css/planningLab<?= (isset($_GET['color']))?$_GET['color']:''	?>.css">
<?php
include('controller/planningLab-controller.php');
?>
<!-- Page Content -->
<div id="split-nav" style="height:100%">
	<div class="container-fluid" id="page-content-wrapper">

			<div class="col-md-4" style="height:100%;overflow:auto;">
					<?php include('views/planningLabJob-view.php'); ?>
			</div>
			<div class="col-md-8" style="height:100%;">
				<div class="row" style="height:90%;overflow:auto;padding:15px;">
					<?php include('views/planningLabFrame-view.php'); ?>
				</div>
				<div class="row" style="height:10%;">
					<?php include('views/planningLabBot-view.php'); ?>
				</div>

	</div>
</div>
<!-- /#page-content-wrapper -->

<!--modal gestion ep ici pour avoir un fond gris sur toute la page-->
<?php
require('views/login-view.php');
?>
<script type="text/javascript" src="js/planningLab.js"></script>
