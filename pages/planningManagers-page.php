<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">
<?php include('controller/planningUsers-controller.php');	?>
<!--<link href="css/listeEssais.css" rel="stylesheet">-->
<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">

		<link href="css/planningUsers.css" rel="stylesheet">

		<div class="col-md-12" style="height:40%; overflow-y:auto;">
			<table id="table_planningUser" class="table table-condensed table-striped table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
				<thead>
					<tr>
						<th></th>
						<?php foreach ($period as $key => $value) : ?>
							<th><?= $value->format("Y") ?></th>
						<?php endforeach ?>
					</tr>
					<tr>
						<th>Users</th>
						<?php foreach ($period as $key => $value) : ?>
							<th><?= $value->format("d M") ?></th>
						<?php endforeach ?>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($lstUsers as $oUser) : ?>
						<tr>
							<td><?= $oUser['technicien'] ?></td>
							<?php foreach ($period as $key => $value) : ?>
								<td class="<?=   $td[$oUser['id_technicien']][$value->format("Y-m-d")]['class'] ?>" <?= $td[$oUser['id_technicien']][$value->format("Y-m-d")]['tooltip'] ?>>
									<?=   $td[$oUser['id_technicien']][$value->format("Y-m-d")]['value'] ?>
								</td>
							<?php endforeach ?>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>

		<div class="col-md-12" style="height:60%;">
			<div class="col-md-2" style="overflow-y:auto;">
				<table class="table table-condensed table-striped table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">

					<thead>
						<tr>
							<th>User</th>
							<th>Work</th>
							<th>CP</th>
							<th><abr title="RRT or Absence">Abs.</abbr></th>
							<th>Mal.</th>
							<th>Fer.</th>
						</tr>
					</thead>

					<tbody>
						<?php foreach ($lstUsersManaged as $oUser) : ?>
							<tr>
								<td><?= $oUser['technicien'] ?></td>
								<td><abbr title="<?= $lstSummary[$oUser['id_technicien']]['C1'] ?>"> <?= $lstSummary[$oUser['id_technicien'] ]['Q1'] ?></abbr></td>
								<td><abbr title="<?= $lstSummary[$oUser['id_technicien']]['Q2'] ?>"> <?= $lstSummary[$oUser['id_technicien'] ]['C2'] ?></abbr></td>
								<td><abbr title="<?= $lstSummary[$oUser['id_technicien']]['Q3'] ?>"> <?= $lstSummary[$oUser['id_technicien'] ]['C3'] ?></abbr></td>
								<td><abbr title="<?= $lstSummary[$oUser['id_technicien']]['Q4'] ?>"> <?= $lstSummary[$oUser['id_technicien'] ]['C4'] ?></abbr></td>
								<td><abbr title="<?= $lstSummary[$oUser['id_technicien']]['Q5'] ?>"> <?= $lstSummary[$oUser['id_technicien'] ]['C5'] ?></abbr></td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>


			<div class="col-md-10" style="overflow-y:auto; padding: 15px 0px;">
				<table id="table_planningModif" class="table table-condensed table-striped table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
					<thead>
						<tr>
							<th>Modification Number</th>
							<th>Applicant</th>
							<th>Date de la demande</th>
							<th>User</th>
							<th>Jour à modifier</th>
							<th>Type de modification</th>
							<th>Quantity (j / hrs)</th>
							<th>Commentaire</th>
							<th>Status</th>
							<th>Date Reponse</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>N° Demande</th>
							<th>demandeur</th>
							<th>Date de la demande</th>
							<th>user</th>
							<th>Jour à modifier</th>
							<th>new type</th>
							<th>quantity</th>
							<th>comments</th>
							<th>Status</th>
							<th>Date Reponse</th>
						</tr>
					</tfoot>
				</table>


			</div>
		</div>

	</div>
</div>
<script type="text/javascript" src="js/planningManagers.js"></script>
<?php
require('views/login-view.php');
?>
