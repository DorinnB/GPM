
<?php include('controller/searchInfo-controller.php'); ?>

<link href="css/searchInfo.css" rel="stylesheet">

<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">

		<div class="col-md-12" style="height:100%; overflow: auto;">

			<div class="row" style="border-top:2px solid white;">
				<table id="table_jobList" class="table table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
					<caption>Job List</caption>
					<thead>
						<tr>
							<th><acronym title="customer">Cust.</acronym></th>
							<th><acronym title="job">Job</acronym></th>
							<th><acronym title="split">Split</acronym></th>
							<th><acronym title="Test Type">Type</acronym></th>
							<th><acronym title="Specification">Spec.</acronym></th>
							<th><acronym title="PO Number">PO</acronym></th>
							<th><acronym title="Customer Instruction">Cust. Inst.</acronym></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($searchJobs as $key => $value) : ?>
							<tr onclick="document.location='index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
								<td><?=	$value['customer']	?></td>
								<td class="titre"><?=	$value['job']	?></td>
								<td><?=	$value['split']	?></td>
								<td><?=	$value['test_type_abbr']	?></td>
								<td><?=	$value['specification']	?></td>
								<td><?=	$value['po_number']	?></td>
								<td><?=	$value['instruction']	?></td>
							</tr>
						<?php endforeach  ?>
					</tbody>
					<tbody style="border-top:5px solid white;">
						<?php foreach ($searchSpecification as $key => $value) : ?>
							<tr onclick="document.location='index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
								<td><?=	$value['customer']	?></td>
								<td><?=	$value['job']	?></td>
								<td><?=	$value['split']	?></td>
								<td><?=	$value['test_type_abbr']	?></td>
								<td class="titre"><?=	$value['specification']	?></td>
								<td><?=	$value['po_number']	?></td>
								<td><?=	$value['instruction']	?></td>
							</tr>
						<?php endforeach  ?>
					</tbody>
					<tbody style="border-top:5px solid white;">
						<?php foreach ($searchPO as $key => $value) : ?>
							<tr onclick="document.location='index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
								<td><?=	$value['customer']	?></td>
								<td><?=	$value['job']	?></td>
								<td><?=	$value['split']	?></td>
								<td><?=	$value['test_type_abbr']	?></td>
								<td><?=	$value['specification']	?></td>
								<td class="titre"><?=	$value['po_number']	?></td>
								<td><?=	$value['instruction']	?></td>
							</tr>
						<?php endforeach  ?>
					</tbody>
					<tbody style="border-top:5px solid white;">
						<?php foreach ($searchInst as $key => $value) : ?>
							<tr onclick="document.location='index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
								<td><?=	$value['customer']	?></td>
								<td><?=	$value['job']	?></td>
								<td><?=	$value['split']	?></td>
								<td><?=	$value['test_type_abbr']	?></td>
								<td><?=	$value['specification']	?></td>
								<td><?=	$value['po_number']	?></td>
								<td class="titre"><?=	$value['instruction']	?></td>
							</tr>
						<?php endforeach  ?>
					</tbody>
				</table>
			</div>


			<div class="row" style="border-top:2px solid white;">
				<table id="table_epList" class="table table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
					<caption>Specimen List</caption>
					<thead>
						<tr>
							<th><acronym title="customer">Cust.</acronym></th>
							<th><acronym title="job">Job</acronym></th>
							<th><acronym title="split">Split</acronym></th>
							<th><acronym title="Test Type">Type</acronym></th>
							<th><acronym title="prefixe">Prefixe</acronym></th>
							<th><acronym title="nom_eprouvette">ID</acronym></th>
							<th><acronym title="PO Number">PO</acronym></th>
							<th><acronym title="Customer Instruction">Cust. Inst.</acronym></th>
							<th><acronym title="N File">File N°</acronym></th>
						</tr>
					</thead>
					<tbody style="border-top:5px solid white;">
						<?php foreach ($searchEprouvettes as $key => $value) : ?>
							<tr onclick="document.location='index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
								<td><?=	$value['customer']	?></td>
								<td><?=	$value['job']	?></td>
								<td><?=	$value['split']	?></td>
								<td><?=	$value['test_type_abbr']	?></td>
								<td><?=	$value['prefixe']	?></td>
								<td class="titre"><?=	$value['nom_eprouvette']	?></td>
								<td><?=	$value['po_number']	?></td>
								<td><?=	$value['instruction']	?></td>
								<td><?=	$value['n_fichier']	?></td>
							</tr>
						<?php endforeach  ?>
					</tbody>
					<tbody style="border-top:5px solid white;">
						<?php foreach ($searchPrefixe as $key => $value) : ?>
							<tr onclick="document.location='index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
								<td><?=	$value['customer']	?></td>
								<td><?=	$value['job']	?></td>
								<td><?=	$value['split']	?></td>
								<td><?=	$value['test_type_abbr']	?></td>
								<td class="titre"><?=	$value['prefixe']	?></td>
								<td><?=	$value['nom_eprouvette']	?></td>
								<td><?=	$value['po_number']	?></td>
								<td><?=	$value['instruction']	?></td>
								<td><?=	$value['n_fichier']	?></td>
							</tr>
						<?php endforeach  ?>
					</tbody>
					<tbody style="border-top:5px solid white;">
						<?php foreach ($searchFile as $key => $value) : ?>
							<tr onclick="document.location='index.php?page=split&amp;id_tbljob=<?= $value['id_tbljob'] ?>'" style="cursor:help">
								<td><?=	$value['customer']	?></td>
								<td><?=	$value['job']	?></td>
								<td><?=	$value['split']	?></td>
								<td><?=	$value['test_type_abbr']	?></td>
								<td><?=	$value['prefixe']	?></td>
								<td><?=	$value['nom_eprouvette']	?></td>
								<td><?=	$value['po_number']	?></td>
								<td><?=	$value['instruction']	?></td>
								<td class="titre"><?=	$value['n_fichier']	?></td>
							</tr>
						<?php endforeach  ?>
					</tbody>
				</table>
			</div>





		</div>

		<script type="text/javascript" src="js/searchInfo.js"></script>





	</div>
</div>
<?php
require('views/login-view.php');
?>
