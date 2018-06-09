<!--<link href="css/listeEssais.css" rel="stylesheet">-->
<link href="css/followup.css" rel="stylesheet">
<script type="text/javascript" src="js/followupJob.js"></script>

<?php include('controller/followup-controller.php'); ?>

<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">

		<table id="table_followup" class="table table-condensed table-striped table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
			<thead>
				<tr>
					<th><acronym title='Phase'>Phase</acronym></th>
					<th><acronym title='Nb Specimen'>NS</acronym></th>
					<th><acronym title='Test MRSAS'>I.</acronym></th>
					<th><acronym title='SubC'>S.</acronym></th>

					<th><acronym title='Customer Name'>Cie</acronym></th>
					<th><acronym title='Cust.'>Cust.</acronym></th>
					<th><acronym title='Job'>Job</acronym></th>
					<th><acronym title='Tests type'>Type</acronym></th>

					<th><acronym title='Contacts'>Contacts</acronym></th>

					<th><acronym title='Pricing'>Pricing</acronym></th>
					<th><acronym title='Quote'>Quote</acronym></th>
					<th><acronym title='PO Number'>PO</acronym></th>

					<th><acronym title='Instruction'>Instruction</acronym></th>
					<th><acronym title='Comments'>Comments</acronym></th>


					<th><acronym title='Material'>Material</acronym></th>
					<th><acronym title='Drawing'>Drawing</acronym></th>

					<th><acronym title='Available Expected'>Available</acronym></th>
					<th><acronym title='Last DyT'>DyT</acronym></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><acronym title='Phase'>Phase</acronym></th>
					<th><acronym title='Nb Specimen'>NS</acronym></th>
					<th><acronym title='Test MRSAS'>I.</acronym></th>
					<th><acronym title='SubC'>S.</acronym></th>

					<th><acronym title='Customer Name'>Cie</acronym></th>
					<th><acronym title='Cust.'>Cust.</acronym></th>
					<th><acronym title='Job'>Job</acronym></th>
					<th><acronym title='Tests type'>Type</acronym></th>

					<th><acronym title='Contacts'>Contacts</acronym></th>

					<th><acronym title='Pricing'>Pricing</acronym></th>
					<th><acronym title='Quote'>Quote</acronym></th>
					<th><acronym title='PO Number'>PO</acronym></th>

					<th><acronym title='Instruction'>Instruction</acronym></th>
					<th><acronym title='Comments'>Comments</acronym></th>


					<th><acronym title='Material'>Material</acronym></th>
					<th><acronym title='Drawing'>Drawing</acronym></th>

					<th><acronym title='Available Expected'>Available</acronym></th>
					<th><acronym title='Last DyT'>DyT</acronym></th>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach ($oFollowup->getAllFollowupJob($filtreFollowup) as $row): ?>
					<tr>
						<td class="popover-markup" data-placement="right" style="background-color:<?= $row['statut_color'] ?>;"><a href="index.php?page=split&id_tbljob=<?= $row['id_tbljob'] ?>"><?= $row['id_statut'] ?></a>
							<div class="content hide">
								<div class="form-group">
									<?= $row['statut'] ?>
								</div>
							</div>
						</td>

						<td><?= $row['nbep'] ?></td>
						<td class="location<?= $row['mrsas'] ?>"><?= $row['mrsas'] ?></td>
						<td class="location<?= $row['subc'] ?>"><?= $row['subc'] ?></td>
<td><acronym title='<?= $row['entreprise'] ?>'><?= $row['entreprise_abbr'] ?></acronym></td>
						<td><?= $row['customer'] ?></td>
						<td><a href="index.php?page=split&id_tbljob=<?= $row['id_tbljob'] ?>"><?= $row['job'] ?></a></td>

					<td><?= $row['test_type_abbr'] ?></td>

						<td><?= (isset($row['nom'])?$row['prenom'][0].'. '.$row['nom']:"")
						.(isset($row['nom2'])?" - ".$row['prenom2'][0].'. '.$row['nom2']:"")
						.(isset($row['nom3'])?" - ".$row['prenom3'][0].'. '.$row['nom3']:"")
						.(isset($row['nom4'])?" - ".$row['prenom4'][0].'. '.$row['nom4']:"")
						?></td>

						<td><?= $row['ref_pricing'] ?></td>

						<?php if (strlen($row['devis'])>15):  ?>
							<td class="popover-markup" data-placement="left"><?= ($row['devis']=="" OR strlen($row['devis'])<15)?$row['devis']:substr($row['devis'],0,15)." [...]" ?>
								<div class="head hide">Instructions</div>
								<div class="content hide">
									<div class="form-group">
										<textarea class"bubble_instruction" rows="5" cols="50" style="width:100%;" disabled><?= $row['devis'] ?></textarea>
									</div>
								</div>
							<?php else: ?>
								<td><?= $row['devis'] ?></td>
							<?php endif ?>
						</td>

						<?php if (strlen($row['po_number'])>15):  ?>
							<td class="popover-markup" data-placement="left"><?= ($row['po_number']=="" OR strlen($row['po_number'])<15)?$row['po_number']:substr($row['po_number'],0,15)." [...]" ?>
								<div class="head hide">PO Number</div>
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

						<?php if (strlen($row['commentaire'])>15):  ?>
							<td class="popover-markup" data-placement="left"><?= ($row['commentaire']=="" OR strlen($row['commentaire'])<15)?$row['commentaire']:substr($row['commentaire'],0,15)." [...]" ?>
								<div class="head hide">Comments</div>
								<div class="content hide">
									<div class="form-group">
										<textarea class"bubble_instruction" rows="5" cols="50" style="width:100%;" disabled><?= $row['commentaire'] ?></textarea>
									</div>
								</div>
							<?php else: ?>
								<td><?= $row['commentaire'] ?></td>
							<?php endif ?>
						</td>


						<td><?= $row['ref_matiere'] ?></td>
						<td><?= $row['dessin'] ?></td>




						<td><?= $row['available_expected'] ?></td>
						<td><?= $row['DyT_Cust'] ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>

</div>
<?php
require('views/login-view.php');
?>
