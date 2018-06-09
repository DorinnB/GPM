<link rel="stylesheet" href="css/split.css">

<div class="container-fluid" style="height:100%;padding-right: 0px;padding-left: 0px;">
	<?php include('views/jobTop-view.php'); ?>

	<div class="container-fluid"  id="exTab3"  style="height:80%;padding-right: 0px;padding-left: 0px;">
		<?php include('controller/splitTab-controller.php'); ?>

		<div class="row splitCenter tab-content clearfix" style="height:100%">
			<div class="col-md-2" style="height:100%">
				<div class="row" style="height:100%">
					<?php include('controller/splitStatut-controller.php'); ?>
					<?php include $splitData_ctrl; ?>
				</div>
			</div>
			<div class="col-md-10 " style="height:100%">
				<div class="row" style="height:80%">
					<?php include $splitEp_ctrl; ?>
				</div>
				<?php include('views/splitBot-view.php'); ?>
			</div>
		</div>
	</div>
	<div id='report-contextual-menu'>
	</div>
	<?php include('controller/splitChart-controller.php'); ?>
</div>
<script type="text/javascript" src="js/split.js"></script>
