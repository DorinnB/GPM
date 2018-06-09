<link rel="stylesheet" href="css/split.css">
<link rel="stylesheet" href="css/clotureJob.css">

<div class="container-fluid" style="height:100%;padding-right: 0px;padding-left: 0px;">
	<?php include('views/jobTop-view.php'); ?>

	<div class="container-fluid"  id="exTab3"  style="height:85%;padding-right: 0px;padding-left: 0px;">
		<div class="row splitCenter tab-content clearfix" style="height:100%">
			<div class="col-md-12 " style="height:100%">
				<div class="row" style="height:40%; overflow:auto;">
					<?php include('views/ClotureJobGroup-view.php'); ?>
				</div>
				<div class="row" style="height:40%; overflow:auto;">
					<?php include('views/ClotureJobReport-view.php'); ?>
				</div>
				<?php include('views/ClotureJobBot-view.php'); ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="js/clotureJob.js"></script>
