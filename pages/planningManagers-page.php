<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">
<?php include('controller/planningUsers-controller.php');	?>
<!--<link href="css/listeEssais.css" rel="stylesheet">-->
<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">

		<link href="css/planningUsers.css" rel="stylesheet">
		<?php if (isset($oUser['id_technicien'])) :	?>
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
								<th id="<?= $value->format("Y-m-d") ?>" class="<?= ($value->format("Y-m-d")==date("Y-m-d")?'today':'')?>"><?= $value->format("d M") ?></th>
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

				<a href="index.php?page=planningManagers&begin=<?= date("Y-m-d", strtotime("first day of previous month")) ?>&end=<?= date("Y-m-d", strtotime('+1 day ', strtotime("last day of previous month"))) ?>" class="btn btn-default glyphicon glyphicon-step-backward"> Last Month</a>
				<a class="btn btn-default glyphicon glyphicon-screenshot" onClick="date = '<?= date("Y-m-d"); ?>'; $('div.dataTables_scrollBody').scrollLeft($('#'+date).position().left-$( window ).width()/4) ;"> TODAY</a>
				<a href="index.php?page=planningManagers&begin=<?= date("Y-m-d", strtotime('+1 day ', strtotime("last day of previous month"))) ?>&end=<?= date("Y-m-d", strtotime("first day of next month")) ?>" class="btn btn-default glyphicon glyphicon-circle-arrow-down"> This Month</a>
				<a href="index.php?page=planningManagers" class="btn btn-default glyphicon glyphicon-circle-arrow-down"> This Year</a>

			</div>

			<div class="col-md-12" style="height:60%;">
				<div class="col-md-2" style="overflow-y:auto;">
					<table class="table table-condensed table-striped table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">

						<thead>
							<tr>
								<th>User</th>
								<th>Work</th>
								<th>CP</th>
								<th><abbr title="Extra CP (birth, wedding...)">CP'</abbr></th>
								<th>Mal.</th>
								<th>Sat.</th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ($lstUsers as $oUser) : //$lstUsers $lstUsersManaged?>
								<tr>
									<td><?= $oUser['technicien'] ?></td>
									<td>
										<?php
										if ($completeYear==1 AND round($lstSummary[$oUser['id_technicien']]['Q1']+$lstSummary[$oUser['id_technicien']]['Q5']+$lstSummary[$oUser['id_technicien']]['Q9'],2)>$lstWorkingTimeUser[$oUser['id_technicien']]['working']) {
											echo '<abbr title="Work: '.$lstSummary[$oUser['id_technicien']]['C1'].' Days / Planned: '.$lstWorkingTimeUser[$oUser['id_technicien']]['working'].' Day/Hrs">'.round($lstSummary[$oUser['id_technicien']]['Q1']+$lstSummary[$oUser['id_technicien']]['Q5']+$lstSummary[$oUser['id_technicien']]['Q9'],2).' <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></abbr>';
										}
										elseif ($completeYear==1 AND round($lstSummary[$oUser['id_technicien']]['Q1']+$lstSummary[$oUser['id_technicien']]['Q5']+$lstSummary[$oUser['id_technicien']]['Q9'],2)<$lstWorkingTimeUser[$oUser['id_technicien']]['working']) {
											echo '<abbr title="Work: '.$lstSummary[$oUser['id_technicien']]['C1'].' Days / Planned: '.$lstWorkingTimeUser[$oUser['id_technicien']]['working'].' Day/Hrs">'.round($lstSummary[$oUser['id_technicien']]['Q1']+$lstSummary[$oUser['id_technicien']]['Q5']+$lstSummary[$oUser['id_technicien']]['Q9'],2).' <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span></abbr>';
										}
										else {
											echo '<abbr title="Work: '.$lstSummary[$oUser['id_technicien']]['C1'].' Days">'.round($lstSummary[$oUser['id_technicien']]['Q1']+$lstSummary[$oUser['id_technicien']]['Q5']+$lstSummary[$oUser['id_technicien']]['Q9'],2).'</abbr>';
										}
										?>
									</td>
									<td>								<?php
									if ($completeYear==1 AND $lstSummary[$oUser['id_technicien']]['C2']>$lstWorkingTimeUser[$oUser['id_technicien']]['vacation']) {
										echo '<abbr title="Day/Hrs: '.$lstSummary[$oUser['id_technicien']]['Q2'].' / Planned: '.$lstWorkingTimeUser[$oUser['id_technicien']]['vacation'].'">'.$lstSummary[$oUser['id_technicien']]['C2'].' <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></abbr>';
									}
									elseif ($completeYear==1 AND $lstSummary[$oUser['id_technicien']]['C2']<$lstWorkingTimeUser[$oUser['id_technicien']]['vacation']) {
										echo '<abbr title="Day/Hrs: '.$lstSummary[$oUser['id_technicien']]['Q2'].' / Planned: '.$lstWorkingTimeUser[$oUser['id_technicien']]['vacation'].'">'.$lstSummary[$oUser['id_technicien']]['C2'].' <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span></abbr>';
									}
									else {
										echo '<abbr title="Day/Hrs: '.$lstSummary[$oUser['id_technicien']]['Q2'].'">'.$lstSummary[$oUser['id_technicien']]['C2'].'</abbr>';
									}
									?></td>
									<td>
										<?php
										if ($completeYear==1 AND $lstSummary[$oUser['id_technicien']]['C9']>$lstWorkingTimeUser[$oUser['id_technicien']]['extraCP']) {
											echo '<abbr title="Day/Hrs: '.$lstSummary[$oUser['id_technicien']]['Q9'].' / Planned: '.$lstWorkingTimeUser[$oUser['id_technicien']]['extraCP'].'">'.$lstSummary[$oUser['id_technicien']]['C9'].' <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></abbr>';
										}
										elseif ($completeYear==1 AND $lstSummary[$oUser['id_technicien']]['C9']<$lstWorkingTimeUser[$oUser['id_technicien']]['extraCP']) {
											echo '<abbr title="Day/Hrs: '.$lstSummary[$oUser['id_technicien']]['Q9'].' / Planned: '.$lstWorkingTimeUser[$oUser['id_technicien']]['extraCP'].'">'.$lstSummary[$oUser['id_technicien']]['C9'].' <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span></abbr>';
										}
										else {
											echo '<abbr title="Day/Hrs: '.$lstSummary[$oUser['id_technicien']]['Q9'].'">'.$lstSummary[$oUser['id_technicien']]['C9'].'</abbr>';
										}
										?>
									</td>
									<td><abbr title="<?= $lstSummary[$oUser['id_technicien']]['Q5'] ?> Day/Hrs"> <?= $lstSummary[$oUser['id_technicien'] ]['C5'] ?></abbr></td>
									<td><abbr title="<?= $lstSummary[$oUser['id_technicien']]['CSaturdayON'] ?>"> <?= $lstSummary[$oUser['id_technicien'] ]['QSaturdayON'] ?></abbr></td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>

					<h4>
						<p class="type_1 border dateHighlight">Work</p>
						<p class="type_6 border">Déplacement</p>
						<p class="type_2 border">CP</p>
						<p class="type_9 border">CP Exceptionnel</p>
						<p class="type_3 border">Absence</p>
						<p class="type_5 border">Maladie</p>
						<p class="notWorkable border" style="color:white;">Closed</p>
					</h4>

					<a href="controller/createPlanningXLS-controller.php?begin=<?= $getBegin ?>&end=<?= $getEnd ?>" class="btn btn-default glyphicon glyphicon-list-alt"> XLS</a>

				</div>


				<div class="col-md-10" style="overflow-y:auto;">
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
		<?php else : ?>
			<div>you have to be logged !</div>
		<?php endif ?>
	</div>
</div>
<script type="text/javascript" src="js/planningManagers.js"></script>
<?php
require('views/login-view.php');
?>
