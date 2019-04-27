
<link href="css/weeklyReport.css" rel="stylesheet">
<?php
include('controller/weeklyReportSubC-controller.php');
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
							<li onclick="location.href='index.php?page=WeeklyReportSubC&customer=<?= $row['id_entreprise'] ?>';"><a href="#"><?= $row['id_entreprise'].' '.$row['entreprise_abbr'] ?></a></li>
						<?php endforeach ?>
					</ul>
				</div>
			</div>
		</div>
		<form id="formWeeklyReportSubC" method="POST" style="height:95%;">
			<div style="height:93%; overflow:auto; width:100%;">
				<table class="table table-condensed table-bordered" id="tableWeeklyReport">
					<thead>
						<tr>
							<td>PO/MRSAS Job</td>
							<td>Customer</td>
							<td>Cust Ref</td>
							<td>Material</td>
							<td>MRI Job</td>
							<td>Split</td>
							<td>Phase</td>
							<td>Done</td>
							<td>Planned</td>
							<td>Status</td>
							<td>DyT Cust MRSAS</td>
							<td>DyT SubC</td>
							<td>DyT Expected</td>
							<td style="min-width:30%;">Comments</td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($lstJobSubC as $key => $value) :?>
							<?php if ($value['nbuncompleted']!=0 OR $value['nbEpNotReceived']>0) :?>
								<tr style="height:100%;">

									<td rowspan="<?=	count($infoJobs[$value['id_info_job']])+1	?>"><?=	$value['job']	?></td>
									<td rowspan="<?=	count($infoJobs[$value['id_info_job']])+1	?>"><?=	$value['customer']	?></td>
									<td rowspan="<?=	count($infoJobs[$value['id_info_job']])+1	?>"><?=	$value['po_number']	?><br/><?=	$value['instruction']	?></td>
									<td rowspan="<?=	count($infoJobs[$value['id_info_job']])+1	?>"><?=	$value['ref_matiere']	?></td>

									<td></td>
									<td>0</td>
									<td>Shipment</td>
									<td><?=	$value['nbsent']	?></td>
									<td><?=	$value['nbep']	?></td>
									<td><?=	(isset($value['firstSent'])?'Shipped on '.$value['firstSent']:'Not Shipped')	?></td>
									<td><?=	$value['available_expected']	?></td>
									<td></td>
									<td></td>

									<td rowspan="<?=	count($infoJobs[$value['id_info_job']])+1	?>" style="height:100%;"><textarea class="commentaire" name="SubCComment_<?=	$value['id_info_job']	?>" ><?= $value['SubCComment'] ?></textarea></td>
								</tr>
								<?php foreach ($infoJobs[$value['id_info_job']] as $k => $v) :?>
									<tr class="clickable-row" data-id="<?=	$v['id_tbljob']	?>">
										<td><?=	$v['refSubC']	?></td>
										<td><?=	$v['split']	?></td>
										<td><?=	$v['test_type_abbr']	?></td>
										<td><?=	$v['nbtest']	?></td>
										<td><?=	$v['nbtestplanned']	?></td>
										<td style=" white-space:nowrap;"><?=	$v['statut_SubC']	?></td>
										<td><?=	$v['DyT_Cust']	?></td>
										<td><?=	$v['DyT_SubC']	?></td>
										<td><?=	$v['DyT_expected']	?></td>
									</tr>
								<?php endforeach	?>
								<tr><td colspan="14" style="background-color:black;line-height:30%;">&nbsp;</td></tr>
							<?php endif ?>
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
