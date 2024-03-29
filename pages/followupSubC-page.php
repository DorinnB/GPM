<!--<link href="css/listeEssais.css" rel="stylesheet">-->
<link href="css/followup.css" rel="stylesheet">
<script type="text/javascript" src="js/followup.js"></script>

<?php include('controller/followup-controller.php'); ?>


<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">

		<table id="table_followup" class="table table-condensed table-striped table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
			<thead>
				<tr>
					<th><acronym title='Phase'>Phase</acronym></th>
					<th><acronym title='Nb Test Sent'>NS</acronym></th>
					<th><acronym title='Nb Test Planned'>NP</acronym></th>
					<th><acronym title='Nb Test Done'>ND</acronym></th>
					<th><acronym title='Customer Name'>Cie</acronym></th>
					<th><acronym title='Cust.'>Cust.</acronym></th>
					<th><acronym title='Job'>Job</acronym></th>
					<th><acronym title='Split'>Split</acronym></th>
					<th><acronym title='Tests type'>Type</acronym></th>
					<th><acronym title='SubC Companie'>SubC</acronym></th>
					<th><acronym title='MRSAS Key Project Manager'>KPM</acronym></th>
					<th><acronym title='PO Number'>PO</acronym></th>
					<th><acronym title='Customer Instructions'>Cust. Inst</acronym></th>

					<th><acronym title='Material'>Material</acronym></th>
					<th><acronym title='Drawing'>Drawing</acronym></th>
					<th><acronym title='SubC Report Reference'>Ref SubC</acronym></th>

					<th><acronym title='Availability'>Avail.</acronym></th>
					<th><acronym title='Delivery Time SubC'>DyT SubC</acronym></th>
					<th><acronym title='Expected Time'>Expected</acronym></th>


				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><acronym title='Phase'>Phase</acronym></th>
					<th><acronym title='Nb Test Sent'>NS</acronym></th>
					<th><acronym title='Nb Test Planned'>NP</acronym></th>
					<th><acronym title='Nb Test Done'>ND</acronym></th>
					<th><acronym title='Customer Name'>Cie</acronym></th>
					<th><acronym title='Cust.'>Cust.</acronym></th>
					<th><acronym title='Job'>Job</acronym></th>
					<th><acronym title='Split'>Split</acronym></th>
					<th><acronym title='Tests type'>Type</acronym></th>
					<th><acronym title='SubC Companie'>SubC</acronym></th>
					<th><acronym title='MRSAS Key Project Manager'>KPM</acronym></th>
					<th><acronym title='PO Number'>PO</acronym></th>
					<th><acronym title='Customer Instructions'>Cust. Inst</acronym></th>
					<th><acronym title='Material'>Material</acronym></th>
					<th><acronym title='Drawing'>Drawing</acronym></th>

					<th><acronym title='SubC Report Reference'>Ref SubC</acronym></th>

					<th><acronym title='Availability'>Avail.</acronym></th>
					<th><acronym title='Delivery Time SubC'>DyT SubC</acronym></th>
					<th><acronym title='Expected Time'>Expected</acronym></th>


				</tr>
			</tfoot>
			<tbody>
				<?php foreach ($oFollowup->getAllFollowup($filtreFollowup) as $row): ?>
					<tr>

						<td class="popover-markup" data-placement="right" style="background-color:<?= $row['statut_color'] ?>;"><a href="index.php?page=split&id_tbljob=<?= $row['id_tbljob'] ?>"><?= $row['etape'] ?></a>
							<div class="content hide">
								<div class="form-group">
									<?= $row['statut'] ?>
								</div>
							</div>
						</td>


						<td><?= $row['nbsent'] ?></td>
						<td><?= $row['nbep'] ?></td>
						<td><?= $row['nbtest'] ?></td>
						<td><acronym title='<?= $row['entreprise'] ?>'><?= $row['entreprise_abbr'] ?></acronym></td>
						<td><?= $row['customer'] ?></td>
						<td><a href="index.php?page=split&id_tbljob=<?= $row['id_tbljob'] ?>"><?= $row['job'] ?></a></td>
						<td><?= $row['split'] ?></td>
						<td><?= $row['test_type_abbr'] ?></td>
						<td><?= $row['entreprise_abbrST'] ?></td>
						<td><?= $row['resp'] ?></td>
						<?php if (strlen($row['po_number'])>15):  ?>
							<td class="popover-markup" data-placement="left"><?= ($row['po_number']=="" OR strlen($row['po_number'])<15)?$row['po_number']:substr($row['po_number'],0,15)." [...]" ?>
								<div class="head hide">Instructions</div>
								<div class="content hide">
									<div class="form-group">
										<textarea class"bubble_instruction" rows="5" cols="50" style="width:100%;" disabled><?= $row['po_number'] ?></textarea>
									</div>
								</div>
							<?php else: ?>
								<td><?= $row['po_number'] ?></td>
							<?php endif ?>
						</td>

						<?php if (strlen($row['instruction'])>15):  ?>
							<td class="popover-markup" data-placement="left"><?= ($row['instruction']=="" OR strlen($row['instruction'])<15)?$row['instruction']:substr($row['instruction'],0,15)." [...]" ?>
								<div class="head hide">Instructions</div>
								<div class="content hide">
									<div class="form-group">
										<textarea class"bubble_instruction" rows="5" cols="50" style="width:100%;" disabled><?= $row['instruction'] ?></textarea>
									</div>
								</div>
							<?php else: ?>
								<td><?= $row['instruction'] ?></td>
							<?php endif ?>
						</td>

						<td><?= $row['ref_matiere'] ?></td>
						<td><?= $row['dessin'] ?></td>
						<td><?= $row['refSubC'] ?></td>

						<td><?= $row['available'] ?></td>
						<td><?= $row['DyT_SubC'] ?></td>
						<td><?= $row['DyT_expected'] ?></td>

					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>

</div>
<?php
require('views/login-view.php');
?>
