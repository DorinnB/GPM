<div class="row splitBot" style="height:20%">
	<div class="col-md-4" style="height:100%">
		<div class="row" style="height:100%">
			<div class="col-md-4" style="height:100%">
				<label for="Dy T">Date :</label>
				<input type="text" class="form-control" name="dateSchedule" id="dateSchedule" value="<?= date("Y-m-d")	?>">
				<input type="hidden" name="id_info_job" id="id_info_job" value="<?=	$split['id_info_job']	?>">

			</div>
			<div class="col-md-8" style="height:100%">
				<p class="">Comments :</p>
				<textarea class="commentaireEditable" id="schedule_commentaire" name="schedule_commentaire"></textarea>
			</div>
		</div>
	</div>
	<div class="col-md-4" style="height:100%;overflow-y:scroll;overflow-x:hidden;" >
		<p class="">History :</p>

		<table class="table table-striped table-condensed table-bordered">
			<tr>
				<th style="width:5%">Date</th>
				<th>Comments</th>
				<th style="width:5%">ID</th>
			</tr>
			<?php foreach ($lstSchedule as $key => $value) :	?>
				<tr data-date="<?=	$value['schedule_date']	?>">
					<td><?=	$value['schedule_date']	?></td>
					<td><?=	$value['schedule_commentaire']	?></td>
					<td><?=	$value['technicien']	?></td>
				</tr>
			<?php endforeach ?>
		</table>
	</div>
	<div class="col-md-3" style="height:100%">
		<p class="" id="flipRecommendation">Recommendation : <span class="glyphicon glyphicon-pencil"></span></p>
		<div id="schedule_recommendation_alt"><?=	$split['schedule_recommendation']	?></div>
		<textarea class="commentaireEditable flip" id="schedule_recommendation" name="schedule_recommendation" value="<?=	$split['schedule_recommendation']	?>"></textarea>
	</div>

	<div class="col-md-1" id="split-bouton" style="height:100%">
		<div class="row" style="height:100%">
			<div class="col-md-12" id="save" style="height:33%">
				<img type="image" src="img/save.png" style="max-width:100%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
			</div>

			<div class="col-md-12" style="height:33%">
				???
			</div>


			<div class="col-md-12" id="export" style="height:33%">
				??
			</div>
		</div>



	</div>
</div>
