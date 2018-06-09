<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">
<script type="text/javascript" src="js/labo.js"></script>

<!-- Page Content -->
<div id="split-nav" style="height:100%">
	<div class="container-fluid">
		<div class="col-md-12 carre" id="carre" style="height:100%; display:block; background-color:#44546A;">
			<div class="row" style="height:50%;">
				<div class="col-md-6" style="height:100%; overflow: auto;"><?php include 'controller/lab-small-controller.php'; ?></div>
				<div class="col-md-6" style="height:100%; overflow: auto;"><?php include 'controller/todo_lab-controller.php'; ?></div>
			</div>
			<div class="row" style="height:50%;">
				<div class="col-md-6" style="height:100%; overflow: auto;"><?php include 'controller/checkList-controller.php'; ?></div>
				<div class="col-md-6" style="height:100%"><?php include 'controller/frameUtilization-controller.php'; ?></div>
			</div>
		</div>
		<div class="col-md-12 unique" id="pageunique" style="height:100%; display:none;">
			<?php
			//$_GET['id_tbljob'] = 1226;
			//include('controller/split-controller.php');
			?>
		</div>
	</div>
</div>
<!-- /#page-content-wrapper -->

<!--modal gestion ep ici pour avoir un fond gris sur toute la page-->
<?php
require('views/splitGestionEpBlank-view.php');
require('views/login-view.php');
?>



<?php //affichage du job directement depuis l'url
if (isset($_GET['id_tbljob'])) {
	echo "<script> goto('split','id_tbljob',".$_GET['id_tbljob'].",'noModif');</script>";
}
 ?>
