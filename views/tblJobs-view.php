<script type="text/javascript"src="js/tblJobs.js"></script>
<link rel="stylesheet" href="css/tblJobs.css">


<div id="tbljob-nav" style="height:100%">
	<div class="container-fluid">

		<table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th><acronym title="Delai">D</acronym></th>
					<th><acronym title="Phase">Ph</acronym></th>
					<th><acronym title="Number of Test">N T</acronym></th>
					<th><acronym title="Number of Specimen">N S</acronym></th>
					<th><acronym title="Percentage of Test done">T %</acronym></th>
					<th><acronym title="Customer">Cust.</acronym></th>
					<th><acronym title="Job">Job</acronym></th>
					<th><acronym title="Split">Split</acronym></th>
					<th><acronym title="Type of Test">Type</acronym></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>D</th>
					<th>Ph</th>
					<th>N T</th>
					<th>N S</th>
					<th>T %</th>
					<th>Cust.</th>
					<th>Job</th>
					<th>Split</th>
					<th>Type</th>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach ($lstJobs->getAllJobs($filtre) as $row): ?>
					<tr data-id_tbljob="<?= $row['id_tbljob'] ?>">
						<td class="delay<?= $row['delay'] ?>"><?= $row['delay'] ?></td>
						<td style="background-color : <?= $row['statut_color'] ?>"><?= $row['etape'] ?></td>
						<td><?= $row['nbtest'] ?></td>
						<td><?= $row['nbep'] ?></td>
						<td color-statut="<?= $row['nbpercent'] ?>"><?= $row['nbpercent'] ?></td>
						<td><?= $row['customer'] ?></td>
						<td><?= $row['job'] ?></td>
						<td><?= $row['split'] ?></td>
						<td><?= $row['test_type_abbr'] ?></td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
