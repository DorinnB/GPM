



<table id="table_planningJob" class="table table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
	<thead>
		<tr>

			<th><acronym title='Cust.'>C.</acronym></th>
			<th><acronym title='Job'>Job</acronym></th>
			<th><acronym title='Tests type'>T</acronym></th>
			<th><acronym title='Temperature °C'>T°</acronym></th>
			<th><acronym title='No Test MRSAS'>T</acronym></th>
			<th><acronym title='Availability'>Av..</acronym></th>
			<th><acronym title='Delivery Time'>DyT</acronym></th>
			<th><acronym title='Expected Time'>Exp</acronym></th>

			<th><acronym title='Estimated Tests (days)'>E.</acronym></th>
			<th><acronym title='Planned Days'>P.</acronym></th>
		</tr>
	</thead>
	<tfoot>
		<tr>

			<th><acronym title='Cust.'>C.</acronym></th>
			<th><acronym title='Job'>Job</acronym></th>
			<th><acronym title='Tests type'>Type</acronym></th>
			<th><acronym title='Temperature °C'>T°C</acronym></th>
			<th><acronym title='No Test MRSAS'>NS</acronym></th>
			<th><acronym title='Availability'>Avail.</acronym></th>
			<th><acronym title='Delivery Time'>Dy T.</acronym></th>
			<th><acronym title='Expected Time'>Exp. T.</acronym></th>

			<th><acronym title='Estimated Tests (days)'>Est. D.</acronym></th>
			<th><acronym title='Planned Days'>Pla. D.</acronym></th>
		</tr>
	</tfoot>
	<tbody>
		<?php foreach ($lstJobs as $row): ?>
			<tr class="machine" data-id_tbljob="<?= $row['id_tbljob']  ?>" data-customer="<?= $row['customer']  ?>" data-job="<?= $row['job']  ?>" data-split="<?= $row['split']  ?>"
				data-color="<?=	substr($row['job'], -1)	?>">

				<td><?= $row['customer'] ?></td>
				<td><a href="index.php?page=split&id_tbljob=<?= $row['id_tbljob'] ?>"><?= $row['job'].'-'.$row['split'] ?></a></td>

				<td><?= $row['test_type_abbr'] ?></td>

				<td><?= round($row['temperature'],1) ?></td>
				<td><?= $row['nbep'] ?></td>

				<td>
					<acronym title='<?=	$row['available']	?>'><?=	date('m-d', strtotime($row['available']))	?></acronym>
				</td>
				<td><?=	date('m-d', strtotime($row['DyT_Cust']))	?></td>
				<td><?=	date('m-d', strtotime($row['DyT_expected']))	?></td>


				<td><?=	$nb[$row['id_tbljob']]	?></td>
				<td></td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>

<!--
<?php foreach ($lstJobs as $key => $value) : ?>

<div class="col-md-12 machine" data-id_tbljob="<?= $value['id_tbljob']  ?>" data-customer="<?= $value['customer']  ?>" data-job="<?= $value['job']  ?>" data-split="<?= $value['split']  ?>" style="border:1px solid black; margin:1px 0px;">
<?= $value['customer'].'-'.$value['job'].'-'. $value['split']  ?><br/>
<?= $value['test_type_abbr'].'-'.$value['temperature'].'-'. $value['nbep']  ?><br/>
</div>

<?php endforeach ?>
-->
