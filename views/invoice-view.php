
<div class="col-md-12" style="height:100%">

	<table id="table_invoice" class="table table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:95%; white-space:nowrap;">
		<thead>
			<tr>
				<th colspan="5">Info</th>
				<th colspan="3">Inv.</th>
				<th colspan="6">INV. USD</th>
				<th colspan="5">INV. EUR</th>
				<th colspan="1"></th>
			</tr>
			<tr>
				<th><acronym title="Statut">Statut</acronym></th>
				<th><acronym title="Customer">Cust.</acronym></th>
				<th><acronym title="Job Number">Job</acronym></th>
				<th><acronym title="PO Amount">PO</acronym></th>
				<th><acronym title="Estimated MRSAS">Est.MRSAS</acronym></th>
				<th><acronym title="Invoice N°">Inv N°</acronym></th>
				<th><acronym title="Invoice Date">Inv Date</acronym></th>
				<th><acronym title="Invoice Due Date">Due Date</acronym></th>
				<th><acronym title="HT SubC">HT SubC</acronym></th>
				<th><acronym title="HT MRSAS">HT MRSAS</acronym></th>
				<th><acronym title="HT Total">HT Total</acronym></th>
				<th><acronym title="TVA">TVA</acronym></th>
				<th><acronym title="TTC">TTC</acronym></th>
				<th><acronym title="USD/€ Exchange Rate">USD Rate</acronym></th>
				<th><acronym title="HT SubC">HT SubC</acronym></th>
				<th><acronym title="HT MRSAS">HT MRSAS</acronym></th>
				<th><acronym title="HT Total">HT Total</acronym></th>
				<th><acronym title="TVA">TVA</acronym></th>
				<th><acronym title="TTC">TTC</acronym></th>
				<th><acronym title="Invoice Payment Date">Payment Date</acronym></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th><acronym title="Statut">Statut</acronym></th>
				<th><acronym title="Customer">Cust.</acronym></th>
				<th><acronym title="Job Number">Job</acronym></th>
				<th><acronym title="PO Amount">PO</acronym></th>
				<th><acronym title="Estimated MRSAS">Est.MRSAS</acronym></th>
				<th><acronym title="Invoice N°">Inv N°</acronym></th>
				<th><acronym title="Invoice Date">Inv Date</acronym></th>
				<th><acronym title="Invoice Due Date">Due Date</acronym></th>
				<th><acronym title="HT SubC">HT SubC</acronym></th>
				<th><acronym title="HT MRSAS">HT MRSAS</acronym></th>
				<th><acronym title="HT Total">HT Total</acronym></th>
				<th><acronym title="TVA">TVA</acronym></th>
				<th><acronym title="TTC">TTC</acronym></th>
				<th><acronym title="USD/€ Exchange Rate">USD Rate</acronym></th>
				<th><acronym title="HT SubC">HT SubC</acronym></th>
				<th><acronym title="HT MRSAS">HT MRSAS</acronym></th>
				<th><acronym title="HT Total">HT Total</acronym></th>
				<th><acronym title="TVA">TVA</acronym></th>
				<th><acronym title="TTC">TTC</acronym></th>
				<th><acronym title="Invoice Payment Date">Payment Date</acronym></th>
			</tr>
		</tfoot>

		<tbody>
			<?php foreach ($lstJobs as $value) : ?>
				<tr>
					<td><?= $value['invoice_type'] ?></td>
					<td><?= $value['customer'] ?></td>
					<td><?= $value['job'] ?></td>
					<td><?= $value['order_val'] ?></td>
					<td><?= $value['order_est'] ?></td>
					<td><?= $value['inv_number'] ?></td>
					<td><?= $value['inv_date'] ?></td>
					<td><?= $value['dueDate'] ?></td>
					<td><?= $value['invSubCUSD'] ?></td>
					<td><?= $value['invMRSASUSD'] ?></td>
					<td><?= $value['invHTUSD'] ?></td>
					<td><?= $value['invTVAUSD'] ?></td>
					<td><?= $value['invTTCUSD'] ?></td>
					<td><?= $value['USDRate'] ?></td>
					<td><?= $value['invSubCEUR'] ?></td>
					<td><?= $value['invMRSASEUR'] ?></td>
					<td><?= $value['invHTEUR'] ?></td>
					<td><?= $value['invTVAEUR'] ?></td>
					<td><?= $value['invTTCEUR'] ?></td>
					<td>*</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
<script type="text/javascript" src="js/invoice.js"></script>

</div>
