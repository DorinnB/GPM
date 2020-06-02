<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">
<script type="text/javascript" src="js/invoiceJob2.js"></script>

<!-- Page Content -->
<div id="split-nav" style="height:100%">
	<div class="container-fluid">

			<?php
			include('controller/invoiceJob2-controller.php');
			?>

	</div>
</div>
<!-- /#page-content-wrapper -->

<!--modal gestion ep ici pour avoir un fond gris sur toute la page-->
<?php
require('views/login-view.php');
?>
