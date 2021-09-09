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
							<th id="<?= $value->format("Y-m-d") ?>" class="<?= ($value->format("Y-m-d")==date("Y-m-d")?'today':'')?>"><?= $value->format("d M") ?></th>
						<?php endforeach ?>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($lstUsers as $oUser) : ?>
						<tr>
							<td class="<?= (isset($_COOKIE['id_user']) AND $oUser['id_technicien']==$_COOKIE['id_user'])?"user":""?>"><?= $oUser['technicien'] ?></td>
							<?php foreach ($period as $key => $value) : ?>
								<td class="<?=   $td[$oUser['id_technicien']][$value->format("Y-m-d")]['class'] ?> <?= (isset($_COOKIE['id_user']) AND $oUser['id_technicien']==$_COOKIE['id_user'])?"user":""?>" <?= $td[$oUser['id_technicien']][$value->format("Y-m-d")]['tooltip'] ?>>
									<?=   $td[$oUser['id_technicien']][$value->format("Y-m-d")]['value'] ?>
								</td>
							<?php endforeach ?>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>

			<a href="index.php?page=planningUsers&begin=<?= date("Y-m-d", strtotime("first day of previous month")) ?>&end=<?= date("Y-m-d", strtotime('+1 day ', strtotime("last day of previous month"))) ?>" class="btn btn-default glyphicon glyphicon-step-backward"> Last Month</a>
			<a class="btn btn-default glyphicon glyphicon-screenshot" onClick="date = '<?= date("Y-m-d"); ?>'; $('div.dataTables_scrollBody').scrollLeft($('#'+date).position().left-$( window ).width()/4) ;"> TODAY</a>
			<a href="index.php?page=planningUsers&begin=<?= date("Y-m-d", strtotime('+1 day ', strtotime("last day of previous month"))) ?>&end=<?= date("Y-m-d", strtotime("first day of next month")) ?>" class="btn btn-default glyphicon glyphicon-circle-arrow-down"> This Month</a>
			<a href="index.php?page=planningUsers" class="btn btn-default glyphicon glyphicon-circle-arrow-down"> This Year</a>

		</div>



		<?php if (isset($_COOKIE['id_user']) AND isset($lstSummary[$_COOKIE['id_user']])) :	?>
			<div class="col-md-12" style="height:60%;">
				<div class="col-md-2" style="overflow-y:auto;">
					<table class="table table-condensed table-striped table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">

						<thead>
							<tr>
								<th>Type</th>
								<th><abbr title="Days or Hours">D./Hrs</abbr></th>
								<th><abbr title="Days">Days</abbr></th>
							</tr>
						</thead>

						<tbody>
							<tr>
								<td>Work</td>
								<td>
									<?php
									if ($completeYear==1 AND round($lstSummary[$_COOKIE['id_user']]['Q1']+$lstSummary[$_COOKIE['id_user']]['Q5']+$lstSummary[$_COOKIE['id_user']]['Q9'],2)>$lstWorkingTimeUser[$_COOKIE['id_user']]['working']) {
										echo '<abbr title="Planned: '.$lstWorkingTimeUser[$_COOKIE['id_user']]['working'].'">'.round($lstSummary[$_COOKIE['id_user']]['Q1']+$lstSummary[$_COOKIE['id_user']]['Q5']+$lstSummary[$_COOKIE['id_user']]['Q9'],2).' <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></abbr>';
									}
									elseif ($completeYear==1 AND round($lstSummary[$_COOKIE['id_user']]['Q1']+$lstSummary[$_COOKIE['id_user']]['Q5']+$lstSummary[$_COOKIE['id_user']]['Q9'],2)<$lstWorkingTimeUser[$_COOKIE['id_user']]['working']) {
										echo '<abbr title="Planned: '.$lstWorkingTimeUser[$_COOKIE['id_user']]['working'].'">'.round($lstSummary[$_COOKIE['id_user']]['Q1']+$lstSummary[$_COOKIE['id_user']]['Q5']+$lstSummary[$_COOKIE['id_user']]['Q9'],2).' <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span></abbr>';
									}
									else {
										echo round($lstSummary[$_COOKIE['id_user']]['Q1']+$lstSummary[$_COOKIE['id_user']]['Q5']+$lstSummary[$_COOKIE['id_user']]['Q9'],2);
									}
									?>
								</td>
								<td><?= $lstSummary[$_COOKIE['id_user']]['C1'] + $lstSummary[$_COOKIE['id_user']]['C5'] + $lstSummary[$_COOKIE['id_user']]['C9'] ?></td>
							</tr>
							<tr>
								<td>CP</td>
								<td>
									<abbr title="Should be 0">
										<?= $lstSummary[$_COOKIE['id_user']]['Q2']>"0"?'<span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span> '.$lstSummary[$_COOKIE['id_user']]['Q2']:'0'	 ?>
									</abbr>
								</td>
								<td>
									<?php
									if ($completeYear==1 AND $lstSummary[$_COOKIE['id_user']]['C2']>$lstWorkingTimeUser[$_COOKIE['id_user']]['vacation']) {
										echo '<abbr title="Planned: '.$lstWorkingTimeUser[$_COOKIE['id_user']]['vacation'].'">'.$lstSummary[$_COOKIE['id_user']]['C2'].' <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></abbr>';
									}
									elseif ($completeYear==1 AND $lstSummary[$_COOKIE['id_user']]['C2']<$lstWorkingTimeUser[$_COOKIE['id_user']]['vacation']) {
										echo '<abbr title="Planned: '.$lstWorkingTimeUser[$_COOKIE['id_user']]['vacation'].'">'.$lstSummary[$_COOKIE['id_user']]['C2'].' <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span></abbr>';
									}
									else {
										echo $lstSummary[$_COOKIE['id_user']]['C2'];
									}
									?>
								</td>
							</tr>
							<tr>
								<td><abbr title="Extra CP (birth, wedding...)">CP'</abbr></td>
								<td>
									<abbr title="Day/Hrs during Extra CP">
										<?= $lstSummary[$_COOKIE['id_user']]['Q9'] ?>
									</abbr>
								</td>
								<td>
									<?php
									if ($completeYear==1 AND $lstSummary[$_COOKIE['id_user']]['C9']>$lstWorkingTimeUser[$_COOKIE['id_user']]['extraCP']) {
										echo '<abbr title="Planned: '.$lstWorkingTimeUser[$_COOKIE['id_user']]['extraCP'].'">'.$lstSummary[$_COOKIE['id_user']]['C9'].' <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></abbr>';
									}
									elseif ($completeYear==1 AND $lstSummary[$_COOKIE['id_user']]['C9']<$lstWorkingTimeUser[$_COOKIE['id_user']]['extraCP']) {
										echo '<abbr title="Planned: '.$lstWorkingTimeUser[$_COOKIE['id_user']]['extraCP'].'">'.$lstSummary[$_COOKIE['id_user']]['C9'].' <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span></abbr>';
									}
									else {
										echo $lstSummary[$_COOKIE['id_user']]['C9'];
									}
									?>
								</td>
							</tr>
							<tr>
								<td>Maladie</td>
								<td> <?= $lstSummary[$_COOKIE['id_user']]['Q5'] ?></td>
								<td> <?= $lstSummary[$_COOKIE['id_user']]['C5'] ?></td>
							</tr>
							<tr>
								<td>Sat.</td>
								<td> <?= $lstSummary[$_COOKIE['id_user']]['CSaturdayON'] ?></td>
								<td> <?= $lstSummary[$_COOKIE['id_user']]['QSaturdayON'] ?></td>
							</tr>
						</tbody>

					</table>

					<h4>
						<p class="type_1 border">Work <span class="badge"><?= (isset($lstModifSummary[$_COOKIE['id_user']]['C1'])?$lstModifSummary[$_COOKIE['id_user']]['C1']:"") ?></span></p>
						<p class="type_6 border">Déplacement <span class="badge"><?= (isset($lstModifSummary[$_COOKIE['id_user']]['C6'])?$lstModifSummary[$_COOKIE['id_user']]['C6']:"") ?></span></p>
						<p class="type_2 border">CP <span class="badge"><?= (isset($lstModifSummary[$_COOKIE['id_user']]['C2'])?$lstModifSummary[$_COOKIE['id_user']]['C2']:"") ?></span></p>
						<p class="type_9 border">CP Exceptionnel <span class="badge"><?= (isset($lstModifSummary[$_COOKIE['id_user']]['C9'])?$lstModifSummary[$_COOKIE['id_user']]['C9']:"") ?></span></p>
						<p class="type_3 border">Absence <span class="badge"><?= (isset($lstModifSummary[$_COOKIE['id_user']]['C3'])?$lstModifSummary[$_COOKIE['id_user']]['C3']:"") ?></span></p>
						<p class="type_5 border">Maladie <span class="badge"><?= (isset($lstModifSummary[$_COOKIE['id_user']]['C5'])?$lstModifSummary[$_COOKIE['id_user']]['C5']:"") ?></span></p>
						<p class="notWorkable border" style="color:white;">Closed <span class="badge"><?= (isset($lstModifSummary[$_COOKIE['id_user']]['C7'])?$lstModifSummary[$_COOKIE['id_user']]['C7']:"") ?></span></p>
					</h4>

					<a href="controller/createPlanningUsersICS-controller.php?begin=<?= $getBegin ?>&end=<?= $getEnd ?>" class="btn btn-default glyphicon glyphicon-calendar"> ICS</a>
					<a href="controller/createPlanningUsersICSinv-controller.php?begin=<?= $getBegin ?>&end=<?= $getEnd ?>" class="btn btn-default glyphicon glyphicon-calendar"> ICSinv</a>
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
		<?php endif ?>
	</div>
</div>
<script type="text/javascript" src="js/planningUsers.js"></script>
<?php
require('views/login-view.php');
?>
