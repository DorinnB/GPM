<link href="css/followup.css" rel="stylesheet">



<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">


<?php
if (isset($_GET['tool'])) {
	include 'views/IT-'.$_GET['tool'].'-view.php';
}
else {
echo '<script>$("#wrapper").removeClass("toggled");</script>';
}
?>


	</div>

</div>
<?php
require('views/login-view.php');
?>
