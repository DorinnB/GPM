<?php include_once 'models/lstPoste-model.php';
// Création d'une instance
$oPostes = new LstPosteModel($db);
$machines=$oPostes->getAllMachine();
?>


<div id="page-content-wrapper" style="height:100%">
	<div id="btnList" style="height:5%;text-align: center; padding-top:5px;">



		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Day Frame <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="index.php?page=frameUtilization&group=Day&filtre=Lab">Lab</a></li>
				<li role="separator" class="divider"></li>
				<?php foreach ($machines as $key => $value) :	?>
					<li><a href="index.php?page=frameUtilization&group=Day&filtre=<?=	$value['id_machine'] ?>"><?=	$value['machine'] ?></a></li>
				<?php endforeach	?>

			</ul>
		</div>


		<a href="index.php?page=frameUtilization&group=Month&filtre=Frame" class="btn btn-default" role="button">This Month</a>

		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Month Frame <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="index.php?page=frameUtilization&group=Month&filtre=Lab">Lab</a></li>
				<li role="separator" class="divider"></li>
				<?php foreach ($machines as $key => $value) :	?>
					<li><a href="index.php?page=frameUtilization&group=Month&filtre=<?=	$value['id_machine'] ?>"><?=	$value['machine'] ?></a></li>
				<?php endforeach	?>
			</ul>
		</div>


		<a href="index.php?page=frameUtilization&group=Year&filtre=Frame" class="btn btn-default" role="button">This Year</a>

		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Year Frame<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="index.php?page=frameUtilization&group=Year&filtre=Lab">Lab</a></li>
				<li role="separator" class="divider"></li>
				<?php foreach ($machines as $key => $value) :	?>
					<li><a href="index.php?page=frameUtilization&group=Year&filtre=<?=	$value['id_machine'] ?>"><?=	$value['machine'] ?></a></li>
				<?php endforeach	?>
			</ul>
		</div>


	</div>
	<div style="height:95%">
		<?php include('controller/frameUtilization-controller.php'); ?>
	</div>
</div>
<?php
require('views/login-view.php');
?>
