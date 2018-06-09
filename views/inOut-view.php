<link rel="stylesheet" href="css/split.css">

<div class="container-fluid" style="height:100%;padding-right: 0px;padding-left: 0px;">
	<?php include('views/jobTop-view.php'); ?>

	<div class="container-fluid"  id="exTab3"  style="height:85%;padding-right: 0px;padding-left: 0px;">
		<div class="row splitCenter tab-content clearfix" style="height:100%">
			<div class="col-md-12 " style="height:100%">
				<div class="row" style="height:80%">
					<?php include('views/inOutEprouvette-view.php'); ?>
				</div>
				<?php include('views/inOutBot-view.php'); ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="js/inOut.js"></script>
