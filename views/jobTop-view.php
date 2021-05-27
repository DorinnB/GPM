<?php
//date derniere mise a jour OneNote
$files = array_merge(glob("OneNote/15.0/Sauvegarder/Notebook-JOBS En Cours/*.one"));
$files = array_merge(glob("OneNote/16.0/Sauvegarder/Notebook-JOBS En Cours/*.one"));
$files = array_combine($files, array_map("filemtime", $files));
arsort($files);
$latest_file = key($files);

$ecartOneNote = floor((time()-filemtime($latest_file))/3600);
?>

<div class="row jobTop" style="height:15%">
	<div class="col-md-2" style="height:100%"><?php include('jobNumero-view.php');?></div>
	<div class="col-md-2" style="height:100%">
		<p class="titre">Material :</p><p class="commentaire2" ><?= $split['ref_matiere'] ?></p>
		<p class="titre"><acronym title="Material Reference">Mat. Ref. :</acronym></p><p class="commentaire2"><?= $split['matiere'] ?></p>
	</div>
	<div class="col-md-1" style="height:100%">
		<p class="titre">Activity :</p><p class="commentaire2" ><?= $split['activity_type'] ?></p>
		<p class="titre">Specific Test :</p><p class="commentaire2" ><?= $split['specific_test'] ?></p>
		</div>
	<div class="col-md-1" style="height:100%; overflow-y:auto;"><?php include('jobContact-view.php');?></div>
	<div class="col-md-2" style="height:100%"><p class="titre">Customer Reference :</p>
		<textarea class="commentaire" disabled><?= $split['info_jobs_instruction'] ?></textarea>
	</div>
	<div class="col-md-3" style="height:100%"><p class="titre">Job Comments :</p>
		<textarea class="commentaire" disabled><?= $split['info_jobs_commentaire'] ?></textarea>
	</div>
	<div class="col-md-1" style="height:100%">
		<div class="row" style="height:100%">


			<div class="col-md-6" style="height:50%; padding:0px;">

				<?php if ($_GET['page']!='inOut') : ?>
					<a href="index.php?page=inOut&id_tbljob=<?=	$_GET['id_tbljob']	?>" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
						<p style="font-size:small;">
							InOut
							<img type="image" src="img/plane.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
						</p>
					</a>
				<?php else:	?>
					<a href="index.php?page=split&id_tbljob=<?=	$_GET['id_tbljob']	?>" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
						<p style="font-size:small;">
							Split
							<img type="image" src="img/home.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
						</p>
					</a>
				<?php endif	?>

			</div>

			<div class="col-md-6" style="height:50%; padding:0px;">
				<?php if ($_GET['page']!='schedule') : ?>
					<a href="index.php?page=schedule&id_tbljob=<?=	$_GET['id_tbljob']	?>" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
						<p style="font-size:small;">
							Schedule
							<img type="image" src="img/calendar_yes.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
						</p>
					</a>
				<?php else:	?>
					<a href="index.php?page=split&id_tbljob=<?=	$_GET['id_tbljob']	?>" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
						<p style="font-size:small;">
							Split
							<img type="image" src="img/home.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
						</p>
					</a>
				<?php endif	?>
			</div>

			<div class="col-md-6" id="" style="height:50%; padding:0px;">
				<a data-toggle="tooltip" title="<?=	$ecartOneNote	?> hrs since the last update" href="controller/openOnenote-controller?id_tbljob=<?=	$_GET['id_tbljob']	?>" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
					<p style="font-size:small;">
						OneNote
						<img type="image" src="img/onenote.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
					</p>
				</a>
			</div>

			<div class="col-md-6" style="height:50%; padding:0px;">
				<?php if ($_GET['page']!='clotureJob') : ?>
					<a href="index.php?page=clotureJob&id_tbljob=<?=	$_GET['id_tbljob']	?>" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
						<p style="font-size:small;">
							Cloture
							<img type="image" src="img/sign-close.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
						</p>
					</a>
				<?php else:	?>
					<a href="index.php?page=split&id_tbljob=<?=	$_GET['id_tbljob']	?>" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
						<p style="font-size:small;">
							Split
							<img type="image" src="img/home.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
						</p>
					</a>
				<?php endif	?>
			</div>
		</div>
	</div>
</div>
