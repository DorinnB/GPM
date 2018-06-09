<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

<link rel="stylesheet" href="css/planningLab<?= (isset($_GET['color']))?$_GET['color']:''	?>.css">
<?php
include('controller/planningLab-controller.php');
?>
<!-- Page Content -->
<div id="split-nav" style="height:100%">
	<div class="container-fluid" id="page-content-wrapper" style="height:100%;">

					<?php include('views/planningLab-view.php'); ?>


</div>
<!-- /#page-content-wrapper -->

<!--modal gestion ep ici pour avoir un fond gris sur toute la page-->
<?php
require('views/login-view.php');
?>
<script type="text/javascript" src="js/planningLab.js"></script>
