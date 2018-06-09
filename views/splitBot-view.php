<div class="row splitBot" style="height:20%">
	<div class="col-md-3" style="height:100%"><p class="">Instructions :</p><textarea class="commentaire<?= ((isset($_GET['modif']) AND $_GET['modif']=="dataInput")?"Editable":"")	?>" name="tbljob_instruction" form="updateData"><?= $split['tbljob_instruction'] ?></textarea></div>
	<div class="col-md-3" style="height:100%">
		<p class="">Commentaires :
			<?php if ((isset($_GET['modif']) AND $_GET['modif']=="eprouvetteConsigne")):	?>
				<button id="deleteTechSplit" type="button" class="btn btn-default btn-xs">
					<span class="glyphicon glyphicon-erase"></span> Force a re-check
				</button>
			<?php endif ?>
		</p><textarea class="commentaire<?= ((isset($_GET['modif']) AND $_GET['modif']=="eprouvetteConsigne")?"Editable":"")	?>" name="tbljob_commentaire"><?= $split['tbljob_commentaire'] ?></textarea>
	</div>
	<div class="col-md-4" style="height:100%"><p class="">Commentaires Qualité :</p><textarea class="commentaire<?= ((isset($_GET['modif']) AND $_GET['modif']=="eprouvetteValue")?"Editable":"")	?>" name="tbljob_commentaire_qualite"><?= $split['tbljob_commentaire_qualite'] ?></textarea></div>

	<div class="col-md-2" id="split-bouton" style="height:100%">

		<div class="row" style="height:100%">
			<div class="col-md-4" id="myChart" style="height:33%">
				<acronym title="Chart">
					<a href="#myModal" role="button" data-toggle="modal" data-target=".bs-example-modal-lg">
						<img type="image" src="img/chart.png" style="max-width:100%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
					</a>
				</acronym>


			</div>
			<div class="col-md-4" style="height:33%">
				<label>?</label>
			</div>
			<div class="col-md-4" id="save" style="height:33%">
				<img type="image" src="img/save.png" style="max-width:100%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
			</div>


			<div class="col-md-4" id="email" style="height:33%">
				<acronym title="Send Email to Check the Split">
					<a href="
					<?php include 'controller/checkSplitEmail-controller.php'; ?>
					">
					<img type="image" src="img/new-email.png" style="max-width:100%; max-height:100%; padding:5px 0px;display: block; margin: auto;" /></a>
				</acronym>
			</div>
			<div class="col-md-4 planning<?=	$split['planning']	?>" id="planning" style="height:33%" data-planning="<?=	$split['planning']	?>">
				<acronym title="Planning Integration (<?= $split['planning']?>)">
					<img type="image" src="img/calendar_<?=	($split['planning']<=0)?'no.png':'yes.png'	?>" style="max-width:100%; max-height:100%;padding:5px 0px;display: block; margin: auto;" />
				</acronym>
			</div>
			<div class="col-md-4 check<?=	$split['checked']	?>" id="check" style="height:33%">
				<acronym title="Split Check (<?= $split['checked']?>)">
					<img type="image" src="img/<?=	($split['checked']==0)?'cross.png':'check.png'	?>" style="max-width:100%; max-height:100%;padding:5px 0px;display: block; margin: auto;" />
				</acronym>
			</div>




			<div class="col-md-4" id="OT" style="height:33%">
				<acronym title="Create OT">
					<a href="controller/createOT-controller.php?id_tbljob=<?=	$split['id_tbljob']	?>">
						<img type="image" src="img/create-txt.png" style="max-width:100%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
					</a>
				</acronym>
			</div>
			<div class="col-md-4" id="report" style="height:33%">
				<acronym title="Create Report">
					<a href="#">
						<img type="image" src="img/export.png" style="max-width:100%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
					</a>
				</acronym>
			</div>
			<div class="col-md-4 report_send<?=	(($split['report_send']<0)?0:$split['report_send'])	?>" id="report_send" data-report_send="<?=	$split['report_send']	?>" style="height:33%; cursor:pointer;">
				<acronym title="End of Job (<?= $split['report_send']?>)">
					<img type="image" src="img/send.png" style="max-width:100%; max-height:100%; padding:5px 0px;display: block; margin: auto;"  />
				</acronym>
			</div>
		</div>


	</div>
</div>
