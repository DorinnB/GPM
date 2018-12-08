<?php
include('controller/gestionPosteFrame-controller.php');
?>


<div class="row" style="overflow:auto; height:95%;">
	<form action="index.php?page=update&type=PosteOther" method='POST' style="height:100%;">
		<div class="col-md-2" style="height:100%;">
			<ul class="nav nav-pills nav-stacked">
				<li><a data-toggle="tab" href="#extenso">Extensometers</a></li>
				<li><a data-toggle="tab" href="#heating">Heating</a></li>
				<li><a data-toggle="tab" href="#tooling">Tooling</a></li>
				<li><a data-toggle="tab" href="#servovalve">Servovalve</a></li>
				<li><a data-toggle="tab" href="#grip"><i style="font-size:smaller;">Grip</i></a></li>
				<li><a data-toggle="tab" href="#cell_load">Load Cell</a></li>
				<li><a data-toggle="tab" href="#computer"><i style="font-size:smaller;">Computer</i></a></li>
			</ul>
		</div>

		<div class="col-md-7 tab-content" style="height:100%;overflow:auto;">



			<div id="extenso" class="tab-pane fade in" style="height:95%">
				<h3>Extensometers</h3>
				<select class="form-control" id="id_extensometre" name="id_extensometre" style="margin-left:auto; margin-right:auto; width:50%;">
					<option value="" disabled selected>Add an element to this location</option>
					<?php foreach ($lstExtensometre as $row): ?>
						<option value="<?= $row['id_extensometre'] ?>"  <?php foreach ($row as $key => $value) { echo 'data-'.$key.'="'.$value.'" '; }	?>><?= $row['extensometre'] ?></option>
					<?php endforeach ?>
				</select>
				<ul class="nav nav-pills nav-stacked" style="margin-top:10px;">
					<?php foreach ($oLstExtensometre->getAllExtensometreLocation() as $row): ?>
						<?php if ($row['machine']==$poste['machine']) : ?>
							<a href="#" class="list-group-item col-md-2 extensoPill" <?php foreach ($row as $key => $value) { echo 'data-'.$key.'="'.$value.'" '; }	?>>
								<h4 class="list-group-item-heading"><?= $row['extensometre'] ?> <?= ($row['extensometre_comment']=="")?"":"*" ?></h4>
								<p class="list-group-item-text">Lo : <?= $row['Lo'] ?> | Type : <?= $row['type_extensometre'] ?></p>
							</a>
						<?php endif ?>
					<?php endforeach ?>
				</ul>
			</div>

			<div id="heating" class="tab-pane fade in" style="height:95%">
				<h3>Heatings</h3>
				<select class="form-control" id="id_chauffage" name="id_chauffage" style="margin-left:auto; margin-right:auto; width:50%;">
					<option value="" disabled selected>Add an element to this location</option>
					<?php foreach ($lstChauffage as $row): ?>
						<option value="<?= $row['id_chauffage'] ?>"  <?php foreach ($row as $key => $value) { echo 'data-'.$key.'="'.$value.'" '; }	?>><?= $row['chauffage'] ?></option>
					<?php endforeach ?>
				</select>
				<ul class="nav nav-pills nav-stacked" style="margin-top:10px;">
					<?php foreach ($oLstChauffage->getAllHeatingLocation() as $row): ?>
						<?php if ($row['machine']==$poste['machine']) : ?>
							<a href="#" class="list-group-item col-md-2 chauffagePill" <?php foreach ($row as $key => $value) { echo 'data-'.$key.'="'.$value.'" '; }	?>>
								<h4 class="list-group-item-heading"><?= $row['chauffage'] ?> <?= ($row['chauffage_comment']=="")?"":"*" ?></h4>
								<p class="list-group-item-text">Type : <?= $row['type_chauffage'] ?></p>
							</a>
						<?php endif ?>
					<?php endforeach ?>
				</ul>
			</div>


			<div id="tooling" class="tab-pane fade in" style="height:95%">
				<h3>Heatings</h3>
				<select class="form-control" id="id_outillage" name="id_outillage" style="margin-left:auto; margin-right:auto; width:50%;">
					<option value="" disabled selected>Add an element to this location</option>
					<?php foreach ($lstOutillage as $row): ?>
						<option value="<?= $row['id_outillage'] ?>"  <?php foreach ($row as $key => $value) { echo 'data-'.$key.'="'.$value.'" '; }	?>><?= $row['outillage'] ?></option>
					<?php endforeach ?>
				</select>
				<ul class="nav nav-pills nav-stacked" style="margin-top:10px;">
					<?php foreach ($oLstOutillage->getAllOutillageLocation() as $row): ?>
						<?php if ($row['machine']==$poste['machine']) : ?>
							<a href="#" class="list-group-item col-md-2 outillagePill" <?php foreach ($row as $key => $value) { echo 'data-'.$key.'="'.$value.'" '; }	?>>
								<h4 class="list-group-item-heading"><?= $row['outillage'] ?> <?= ($row['comments']=="")?"":"*" ?></h4>
								<p class="list-group-item-text">Type : <?= $row['outillage_type'] ?></p>
								<p class="list-group-item-text">Material : <?= $row['matiere'] ?></p>
							</a>
						<?php endif ?>
					<?php endforeach ?>
				</ul>
			</div>

			<div id="servovalve" class="tab-pane fade in" style="height:95%">
				<h3>Servovalve</h3>
				<select class="form-control" id="id_servovalve" name="id_servovalve" style="margin-left:auto; margin-right:auto; width:50%;">
					<option value="" disabled selected>Add an element to this location</option>
					<?php foreach ($lstServovalve as $row): ?>
						<option value="<?= $row['id_servovalve'] ?>"  <?php foreach ($row as $key => $value) { echo 'data-'.$key.'="'.$value.'" '; }	?>><?= $row['servovalve'] ?></option>
					<?php endforeach ?>
				</select>
				<ul class="nav nav-pills nav-stacked" style="margin-top:10px;">
					<?php foreach ($oLstServovalve->getAllServovalveLocation() as $row): ?>
						<?php if ($row['machine']==$poste['machine']) : ?>
							<a href="#" class="list-group-item col-md-2 servovalvePill" <?php foreach ($row as $key => $value) { echo 'data-'.$key.'="'.$value.'" '; }	?>>
								<h4 class="list-group-item-heading"><?= $row['servovalve'] ?> <?= ($row['servovalve_comment']=="")?"":"*" ?></h4>
								<p class="list-group-item-text">Type : <?= $row['servovalve_model'] ?></p>
								<p class="list-group-item-text">Capacity : <?= $row['servovalve_capacity'] ?></p>
							</a>
						<?php endif ?>
					<?php endforeach ?>
				</ul>
			</div>

			<div id="cell_load" class="tab-pane fade in" style="height:95%">
				<h3>Load Cell</h3>
				<select class="form-control" id="id_cell_load" name="id_cell_load" style="margin-left:auto; margin-right:auto; width:50%;">
					<option value="" disabled selected>Add an element to this location</option>
					<?php foreach ($lstCellLoad as $row): ?>
						<option value="<?= $row['id_cell_load'] ?>"  <?php foreach ($row as $key => $value) { echo 'data-'.$key.'="'.$value.'" '; }	?>><?= $row['cell_load_serial'] ?></option>
					<?php endforeach ?>
				</select>
				<ul class="nav nav-pills nav-stacked" style="margin-top:10px;">
					<?php foreach ($oLstCellLoad->getAllCell_loadLocation() as $row): ?>
						<?php if ($row['machine']==$poste['machine']) : ?>
							<a href="#" class="list-group-item col-md-2 cell_loadPill" <?php foreach ($row as $key => $value) { echo 'data-'.$key.'="'.$value.'" '; }	?>>
								<h4 class="list-group-item-heading"><?= $row['cell_load_serial'] ?> <?= ($row['cell_load_comment']=="")?"":"*" ?></h4>
								<p class="list-group-item-text">Capacity : <?= $row['cell_load_capacity'] ?></p>
							</a>
						<?php endif ?>
					<?php endforeach ?>
				</ul>
			</div>

		</div>

		<div class="col-md-3" style="height:100%;">
			<div style="height:90%;">
				<div id="summary">
				</div>
			</div>
			<div class="row" id="save" style="height:10%;">
				<input type="hidden" name="id_machine" value="<?=	$poste['id_machine']	?>">
				<input type="hidden" name="id_poste" value="<?=	$poste['id_poste']	?>">
				<input type="image" alt="Submit" src="img/save.png" style="max-width:100%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
			</div>
		</div>
	</form>
</div>
