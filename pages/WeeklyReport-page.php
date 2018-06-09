
<link href="css/weeklyReport.css" rel="stylesheet">
<?php
include('controller/weeklyReport-controller.php');
?>
<!-- Page Content -->
<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">
		<div class="row" style="height:5%;">
			<div class="col-md-1 col-centered" style="height:100%;float: none;margin: 0 auto; padding-top:2px;">
				<div class="btn-group" style="width:100%;">
					<button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="customer" data-id="<?=	$_GET['customer']	?>" style="float:none;">
						<?= $customer['id_entreprise'].' '.$customer['entreprise'] ?> <span class="caret"></span>
					</button>
					<ul class="dropdown-menu statut" style="max-height: 500px;overflow-y: auto;overflow-x: hidden;">
						<?php foreach ($entreprises as $row): ?>
							<li onclick="location.href='index.php?page=WeeklyReport&customer=<?= $row['id_entreprise'] ?>';"><a href="#"><?= $row['id_entreprise'].' '.$row['entreprise_abbr'] ?></a></li>
						<?php endforeach ?>
					</ul>
				</div>
			</div>
		</div>
		<form id="formWeeklyReport" method="POST" style="height:95%;">
			<div style="height:93%; overflow:auto; width:100%;">
				<table class="table table-condensed table-bordered" id="tableWeeklyReport">
					<thead>
						<tr>
							<td>PO/Instructions</td>
							<td>Material</td>
							<td>Metcut Job</td>
							<td>Split</td>
							<td>Phase</td>
							<td>Done</td>
							<td>Planned</td>
							<td>Status</td>
							<td>DyT</td>
							<td style="min-width:30%;">Comments</td>
							<td>Contact</td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($lstJobCust as $key => $value) :?>
							<tr style="height:100%;">
								<td rowspan="<?=	count($infoJobs[$value['id_info_job']])+1	?>"><?=	$value['po_number']	?><br/><?=	$value['instruction']	?></td>
								<td rowspan="<?=	count($infoJobs[$value['id_info_job']])+1	?>"><?=	$value['ref_matiere']	?></td>
								<td rowspan="<?=	count($infoJobs[$value['id_info_job']])+1	?>"><?=	$value['job']	?></td>
								<td>0</td>
								<td>Reception Matière</td>
								<td><?=	$value['nbreceived']	?></td>
								<td><?=	$value['nbep']	?></td>
								<td><?=	(isset($value['firstReceived'])?'Receipt '.$value['firstReceived']:'')	?></td>
								<td><?=	$value['available_expected']	?></td>
								<td rowspan="<?=	count($infoJobs[$value['id_info_job']])+1	?>" style="height:100%;"><textarea class="commentaire" name="weeklycomment_<?=	$value['id_info_job']	?>" ><?= $value['weeklyComment'] ?></textarea></td>
								<td rowspan="<?=	count($infoJobs[$value['id_info_job']])+1	?>"><?=	$value['contacts']	?></td>
							</tr>
							<?php foreach ($infoJobs[$value['id_info_job']] as $k => $v) :?>
								<tr class="clickable-row" data-id="<?=	$v['id_tbljob']	?>">
									<td><?=	$v['split']	?></td>
									<td><?=	$v['test_type_abbr']	?></td>
									<td><?=	$v['nbtest']	?></td>
									<td><?=	$v['nbtestplanned']	?></td>
									<td><?=	$v['statut_client']	?></td>
									<td><?=	$v['DyT_Cust']	?></td>
								</tr>
							<?php endforeach	?>
							<tr><td colspan="11" style="background-color:black;line-height:30%;">&nbsp;</td></tr>
						<?php endforeach	?>
					</tbody>
				</table>
			</div>
			<div style="height:7%; width:100%; padding:10px 0px;">
			<input type="submit" value="SAVE & PRINT" style="width:100%;" >
		</div>
		</form>
	</div>
</div>

<script type="text/javascript" src="js/weeklyReport.js"></script>

<?php require('views/login-view.php');	?>
