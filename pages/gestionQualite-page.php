<link href="css/gestionQualite.css" rel="stylesheet">
<script type="text/javascript" src="js/gestionQualite.js"></script>
<?php include('controller/gestionQualite-controller.php'); ?>

<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">
		<div class="col-md-12" style="height:100%">



			<div class="col-md-3" style="height:100%;; overflow:auto;>
				<div class="row">
					<div class="bs-example temperature" data-example-id="basic-forms">
						<div class="form-group">
							<button class="create2 btn-default glyphicon glyphicon-plus-sign"> New</button>

							<div class="btn-group btnstatut" style="width:100%;">
								<button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="id_temperature_correction_parameter" style="float:none;" data-id_temperature_correction_parameter="<?= $lstTemperatureCorrectionParameters[0]['id_temperature_correction_parameter'] ?>">
									TC Type : <?= $lstTemperatureCorrectionParameters[0]['tc_type']	?><br />
									Supplier : <?= $lstTemperatureCorrectionParameters[0]['supplier']	?><br />
									Spool : <?= $lstTemperatureCorrectionParameters[0]['spool']	?><br />
									Date : <?= $lstTemperatureCorrectionParameters[0]['date_temperature_correction_parameter']	?>
								</button>
								<ul class="dropdown-menu" id="temperatureCorrectionParameters">
									<?php foreach ($lstTemperatureCorrectionParameters as $row): ?>
										<li onclick="chgtParameter('<?=  $row['id_temperature_correction_parameter'] ?>','TC Type : <?= $row['tc_type']	?><br />Supplier : <?= $row['supplier']	?><br />Spool : <?= $row['spool']	?><br />Date : <?= $row['date_temperature_correction_parameter']	?>');"><a href="#"><?= $row['spool'].' ('.$row['date_temperature_correction_parameter'].')' ?></a></li>
									<?php endforeach ?>
								</ul>
							</div>



							<table id="table_temperature_corrections" class="table table-condensed table-hover table-bordered dataTable" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th></th>
										<th>Temp. (°C)</th>
										<th>Temp. Corrigé (°C)</th>
									</tr>
								</thead>
							</table>

						</div>

					</div>
				</div>

			</div>











		</div>
	</div>
</div>
<?php
require('views/login-view.php');
?>
