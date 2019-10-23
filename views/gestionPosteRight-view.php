


<style>.poste:after  {  content: "POSTE";}</style>
<div class="bs-example poste" id="posteMachine" data-example-id="basic-forms" style="display:none;">

	<div class="form-group">
		<label for="poste">Poste</label>
		<select class="form-control" id="poste" name="poste">
			<option value="">-</option>
			<?php for ($j=0; $j <=5; $j++):	?>
				<?php for ($i=0; $i <30; $i++):	?>
					<option value="<?= ($j*100+$i) ?>" <?=  ($poste['poste']== ($j*100+$i))?"selected":""  ?>><?= ($j*100+$i) ?></option>
				<?php endfor ?>
			<?php endfor ?>
		</select>
	</div>
</div>

<style>.loadCell:after  {  content: "LOAD CELL";}</style>
<div class="bs-example loadCell" id="loadCell" data-example-id="basic-forms" style="display:none;">

	<div class="form-group">
		<label for="id_cell_load">Load Cell S/N</label>
		<select class="form-control" id="id_cell_load" name="id_cell_load">
			<option value="">-</option>
			<?php foreach ($lstCellLoad as $row): ?>
				<option value="<?= $row['id_cell_load'] ?>" <?=  ($poste['id_cell_load']== $row['id_cell_load'])?"selected":""  ?>><?= $row['cell_load_serial'] ?></option>
			<?php endforeach ?>
		</select>
	</div>
	<div class="form-group">
		<label for="Load_Model">Model</label>
		<input class="form-control" id="Load_Model" value="" type="text" disabled>
	</div>
	<div class="form-group">
		<label for="Load_Capacity">Capacity (kN)</label>
		<input class="form-control" id="Load_Capacity" value="" type="text" disabled>
	</div>
	<div class="form-group">
		<label for="Load_Gamme">Gamme</label>
		<input class="form-control" id="Load_Gamme" value="" type="text" disabled>
	</div>
</div>

<style>.displacementCell:after  {  content: "DISPLACEMENT CELL";}</style>
<div class="bs-example displacementCell" id="displacementCell" data-example-id="basic-forms" style="display:none;">

	<div class="form-group">
		<label for="id_cell_displacement">displacement Cell S/N</label>
		<select class="form-control" id="id_cell_displacement" name="id_cell_displacement">
			<option value="">-</option>
			<?php foreach ($lstCellDisplacement as $row): ?>
				<option value="<?= $row['id_cell_displacement'] ?>" <?=  ($poste['id_cell_displacement']== $row['id_cell_displacement'])?"selected":""  ?>><?= $row['cell_displacement_serial'] ?></option>
			<?php endforeach ?>
		</select>
	</div>
	<div class="form-group">
		<label for="displacement_Model">Model</label>
		<input class="form-control" id="displacement_Model" value="" type="text" disabled>
	</div>
	<div class="form-group">
		<label for="displacement_Capacity">Capacity (mm)</label>
		<input class="form-control" id="displacement_Capacity" value="" type="text" disabled>
	</div>
	<div class="form-group">
		<label for="displacement_Gamme">Gamme</label>
		<input class="form-control" id="displacement_Gamme" value="" type="text" disabled>
	</div>
</div>


<style>.servovalve:after  {  content: "SERVOVALVES";}</style>
<div class="bs-example servovalve" id="servovalve" data-example-id="basic-forms" style="display:none;">
<div style="width: 45%; display: inline-block;">
	<div class="form-group">
		<label for="id_servovalve">Servovalve 1 S/N</label>
		<select class="form-control" id="id_servovalve1" name="id_servovalve1">
			<option value="">-</option>
			<?php foreach ($lstServovalve as $row): ?>
				<option value="<?= $row['id_servovalve'] ?>" <?=  ($poste['id_servovalve1']== $row['id_servovalve'])?"selected":""  ?>><?= $row['servovalve'] ?></option>
			<?php endforeach ?>
		</select>
	</div>
	<div class="form-group">
		<label for="servovalve1_model">Model</label>
		<input class="form-control" id="servovalve1_model" value="" type="text" disabled>
	</div>
	<div class="form-group">
		<label for="servovalve1_capacity">Capacity (l/mn)</label>
		<input class="form-control" id="servovalve1_capacity" value="" type="text" disabled>
	</div>
	<div class="form-group">
		<label for="fixing_type1">Fixing Type</label>
		<input class="form-control" id="fixing_type1" value="" type="text" disabled>
	</div>
</div>

<div style="width: 5%; display: inline-block;">
</div>

<div style="width: 45%; display: inline-block;">
	<div class="form-group">
		<label for="id_servovalve2">Servovalve 2 S/N</label>
		<select class="form-control" id="id_servovalve2" name="id_servovalve2">
			<option value="">-</option>
			<?php foreach ($lstServovalve as $row): ?>
				<option value="<?= $row['id_servovalve'] ?>" <?=  ($poste['id_servovalve2']== $row['id_servovalve'])?"selected":""  ?>><?= $row['servovalve'] ?></option>
			<?php endforeach ?>
		</select>
	</div>
	<div class="form-group">
		<label for="servovalve2_model">Model</label>
		<input class="form-control" id="servovalve2_model" value="" type="text" disabled>
	</div>
	<div class="form-group">
		<label for="servovalve2_capacity">Capacity (l/mn)</label>
		<input class="form-control" id="servovalve2_capacity" value="" type="text" disabled>
	</div>
	<div class="form-group">
		<label for="fixing_type2">Fixing Type</label>
		<input class="form-control" id="fixing_type2" value="" type="text" disabled>
	</div>
</div>
</div>


<script type="text/javascript" src="js/gestionPosteRight.js"></script>
