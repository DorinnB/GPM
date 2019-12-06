

<div class="row" style="height:25%; padding-top:10px; padding-bottom:10px;">

	<?php	foreach ($lstCalib as $key => $value) :		?>
		<div class="col-md-2">
			<a href="index.php?page=calibration&type=<?= $value['id_calibration_type'] ?>" class="btn btn-primary btn-block <?= ($type==$value['id_calibration_type'])?"active":"" 	?>" role="button"><?= $value['calibration_type'] ?></a>
		</div>
	<?php endforeach ?>
</div>


<div class="row" style="height:25%; padding-top:10px; padding-bottom:10px;">
	<div class="dropdown col-md-2 col-md-offset-3">
		<button class="btn btn-primary dropdown-toggle btn-block" type="button" data-toggle="dropdown">Frame <?= isset($poste['machine'])?$poste['machine']:"N/A" ?>
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li class="<?= ($idPoste==0)?"active":"" 	?>"><a href="index.php?page=calibration&type=<?= $type ?>&idposte=0&idElement=0">N/A</a></li>
			<?php foreach ($postes as $row): ?>
				<li class="<?= ($idPoste==$row['id_poste'])?"active":"" 	?>"><a href="index.php?page=calibration&type=<?= $type ?>&idposte=<?=	$row['id_poste'] ?>"><?= $row['machine'] ?></a></li>
			<?php endforeach ?>
		</ul>
	</div>

	<div class="dropdown col-md-2 col-md-offset-2">
		<button class="btn btn-primary dropdown-toggle btn-block" type="button" data-toggle="dropdown">ID : <?= $element ?>
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<?php foreach ($lstIdElement as $row): ?>
				<li class="<?= ($idElement==$row['id_element'])?"active":"" 	?>"><a href="index.php?page=calibration&type=<?= $type ?>&idposte=<?=	$idPoste ?>&idElement=<?=	$row['id_element'] ?>"><?= $row['element'] ?></a></li>
			<?php endforeach ?>
		</ul>
	</div>
</div>


<div class="row" style="height:50%; padding-top:10px; padding-bottom:10px;">
	<div class="col-md-2 col-md-offset-4" style="height:100%;">
		<a href="controller/createCalibration-controller.php?type=<?= $type ?>&idposte=<?=	$idPoste ?>&idElement=<?=	$idElement ?>" class="btn btn-default btn-lg" title="New Calibration File" style="width:100%; height:100%; padding:0px; border-radius:10px;">
			<p style="font-size:small; height:100%">
				<img type="image" src="img/nextJob.png"  style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
			</p>
		</a>
	</div>

	<div class="col-md-2" style="height:100%;">
		 <?php if(isset($_COOKIE['id_user']) AND ($_COOKIE['id_user']!=0)) : ?>
		<form id="uploadCal" action="#" method="post" enctype="multipart/form-data" style="height:100%">
			<label for="calToUpload" title="Upload a Calibration File" style="height:100%; width:100%; padding:0px; border-radius:10px;" class="btn btn-default btn-lg">
				<img type="image" src="img/upload.png" style="max-width:100%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
			</label>
			<input name="calToUpload" id="calToUpload" style="display:none;" type="file">
		</form>
	<?php endif ?>
	</div>
</div>
