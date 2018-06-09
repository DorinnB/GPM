
<div class="row" style="height:100%;">
	<div class="col-md-4" style="height:100%;">
		<div class="row" >

			<div class="bs-example sensors" data-example-id="basic-forms">

				<div class="form-group">
					<label for="cartouche_stroke">Displacement (<?=	$poste['cartouche_stroke']	?> mm)</label>
					<input class="form-control" id="cartouche_stroke" placeholder="<?=	$poste['cell_displacement_gamme']	?>" type="text" disabled>
				</div>
				<div class="form-group">
					<label for="cartouche_load">Load (<?=	$poste['cartouche_load']	?> kN)</label>
					<input class="form-control" id="cartouche_load" placeholder="<?=	$poste['cell_load_gamme']	?>" type="text" disabled>
				</div>
				<div class="form-group">
					<label for="id_extensometre">Strain (<?=	$poste['cartouche_strain']	?> %)</label>
					<select class="form-control" id="id_extensometre" name="id_extensometre">
												<option value="">-</option>
						<?php foreach ($lstExtensometre as $row): ?>
							<option value="<?= $row['id_extensometre'] ?>" <?=  ($poste['id_extensometre']== $row['id_extensometre'])?"selected":""  ?>><?= $row['extensometre'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>
		<div class="row" >
			<div class="bs-example PiD" data-example-id="basic-forms">
				<div class="row">
					<div class="col-xs-2"></div>
					<div class="col-xs-2">P</div>
					<div class="col-xs-2">i</div>
					<div class="col-xs-2">D</div>
					<div class="col-xs-2">PVC</div>
					<div class="col-xs-2">PVC</div>
				</div>
				<div class="row">
					<div class="col-xs-2">
						<label>Disp.</label>
					</div>
					<div class="col-xs-2">
						<input type="text" class="form-control" placeholder="P" value="<?=	$poste['Disp_P']	?>" name="Disp_P">
					</div>
					<div class="col-xs-2">
						<input type="text" class="form-control" placeholder="i" value="<?=	$poste['Disp_i']	?>" name="Disp_i">
					</div>
					<div class="col-xs-2">
						<input type="text" class="form-control" placeholder="D" value="<?=	$poste['Disp_D']	?>" name="Disp_D">
					</div>
					<div class="col-xs-2">
						<input type="text" class="form-control" placeholder="Conv" value="<?=	$poste['Disp_Conv']	?>" name="Disp_Conv">
					</div>
					<div class="col-xs-2">
						<input type="text" class="form-control" placeholder="Sens" value="<?=	$poste['Disp_Sens']	?>" name="Disp_Sens">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-2">
						<label>Load</label>
					</div>
					<div class="col-xs-2">
						<input type="text" class="form-control" placeholder="P" value="<?=	$poste['Load_P']	?>" name="Load_P">
					</div>
					<div class="col-xs-2">
						<input type="text" class="form-control" placeholder="i" value="<?=	$poste['Load_i']	?>" name="Load_i">
					</div>
					<div class="col-xs-2">
						<input type="text" class="form-control" placeholder="D" value="<?=	$poste['Load_D']	?>" name="Load_D">
					</div>
					<div class="col-xs-2">
						<input type="text" class="form-control" placeholder="Conv" value="<?=	$poste['Load_Conv']	?>" name="Load_Conv">
					</div>
					<div class="col-xs-2">
						<input type="text" class="form-control" placeholder="Sens" value="<?=	$poste['Load_Sens']	?>" name="Load_Sens">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-2">
						<label>Strain</label>
					</div>
					<div class="col-xs-2">
						<input type="text" class="form-control" placeholder="P" value="<?=	$poste['Strain_P']	?>" name="Strain_P">
					</div>
					<div class="col-xs-2">
						<input type="text" class="form-control" placeholder="i" value="<?=	$poste['Strain_i']	?>" name="Strain_i">
					</div>
					<div class="col-xs-2">
						<input type="text" class="form-control" placeholder="D" value="<?=	$poste['Strain_D']	?>" name="Strain_D">
					</div>
					<div class="col-xs-2">
						<input type="text" class="form-control" placeholder="Conv" value="<?=	$poste['Strain_Conv']	?>" name="Strain_Conv">
					</div>
					<div class="col-xs-2">
						<input type="text" class="form-control" placeholder="Sens" value="<?=	$poste['Strain_Sens']	?>" name="Strain_Sens">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4" style="height:100%;">
		<div class="row">
			<div class="bs-example tooling" data-example-id="basic-forms">
				<div class="form-group">
					<label for="id_outillage_top">Grip TOP</label>
					<select class="form-control" id="id_outillage_top" name="id_outillage_top">
						<option value="">-</option>
						<?php foreach ($lstOutillage as $row): ?>
							<option value="<?= $row['id_outillage'] ?>" <?=  ($poste['id_outillage_top']== $row['id_outillage'])?"selected":""  ?>><?= $row['outillage'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group">
					<label for="id_outillage_bot">Grip BOT</label>
					<select class="form-control" id="id_outillage_bot" name="id_outillage_bot">
												<option value="">-</option>
						<?php foreach ($lstOutillage as $row): ?>
							<option value="<?= $row['id_outillage'] ?>" <?=  ($poste['id_outillage_bot']== $row['id_outillage'])?"selected":""  ?>><?= $row['outillage'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="form-group">
					<label for="id_enregistreur">Computer</label>
					<select class="form-control" id="id_enregistreur" name="id_enregistreur">
												<option value="">-</option>
						<?php foreach ($lstComputer as $row): ?>
							<option value="<?= $row['id_enregistreur'] ?>" <?=  ($poste['id_enregistreur']== $row['id_enregistreur'])?"selected":""  ?>><?= $row['enregistreur'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="bs-example heating" data-example-id="basic-forms">
				<div class="form-group">
					<label for="id_chauffage">Heating</label>
					<select class="form-control" id="id_chauffage" name="id_chauffage">
												<option value="">-</option>
						<?php foreach ($lstChauffage as $row): ?>
							<option value="<?= $row['id_chauffage'] ?>" <?=  ($poste['id_chauffage']== $row['id_chauffage'])?"selected":""  ?>><?= $row['chauffage'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<label for="id_extensometre">Temp Rec. Top/Center/Bot</label>
				<div class="form-inline">
					<div class="form-group">
						<select class="form-control input-sm" id="id_ind_temp_top" name="id_ind_temp_top">
													<option value="">-</option>
							<?php foreach ($lstIndTemp as $row): ?>
								<option value="<?= $row['id_ind_temp'] ?>" <?=  ($poste['id_ind_temp_top']== $row['id_ind_temp'])?"selected":""  ?>><?= $row['ind_temp'] ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="form-group">
						<select class="form-control input-sm" id="id_ind_temp_strap" name="id_ind_temp_strap">
													<option value="">-</option>
							<?php foreach ($lstIndTemp as $row): ?>
								<option value="<?= $row['id_ind_temp'] ?>" <?=  ($poste['id_ind_temp_strap']== $row['id_ind_temp'])?"selected":""  ?>><?= $row['ind_temp'] ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="form-group">
						<select class="form-control input-sm" id="id_ind_temp_bot" name="id_ind_temp_bot">
													<option value="">-</option>
							<?php foreach ($lstIndTemp as $row): ?>
								<option value="<?= $row['id_ind_temp'] ?>" <?=  ($poste['id_ind_temp_bot']== $row['id_ind_temp'])?"selected":""  ?>><?= $row['ind_temp'] ?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="compressor">Compressor
					</label>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4" style="height:100%;">
		<div class="row">
			<div class="bs-example date" data-example-id="basic-forms">
				<div class="form-group">
					<label for="cartouche_stroke">Date</label>
					<input type="text" class="form-control" placeholder="<?= $poste['date']	?>" disabled>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="bs-example reason" data-example-id="basic-forms" style="height:100%;">
				<div class="form-group" style="height:100%;">
					<textarea required name="poste_reason" id="poste_reason" class="form-control" placeholder="PREVIOUS REASON : <?= $poste['poste_reason']	?>" style="height:100%;"></textarea>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="bs-example commentaire" data-example-id="basic-forms" style="height:100%;">
				<div class="form-group" style="height:100%;">
					<textarea name="poste_commentaire" class="form-control" placeholder="Frame Issues" style="height:100%;"><?= $poste['poste_commentaire']	?></textarea>
				</div>
			</div>
		</div>
	</div>
</div>
