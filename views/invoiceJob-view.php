
<link href="css/invoiceJob.css" rel="stylesheet">
<input id="id_info_job" style="display:none;" value="<?= $split['id_info_job'] ?>">
<input id="customer" style="display:none;" value="<?= $split['customer'] ?>">
<input id="job" style="display:none;" value="<?= $split['job'] ?>">
<!-- Page Content -->
<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">
		<div class="row" style="height:100%">
			<div class="col-md-3" style="height:100%; overflow:auto;">
				<H1>
					<?=	$split['customer'].'-'.$split['job']	?>
					<br/>
					<div style="font-size:initial">
						<?=	$split['entreprise']	?>
					</div>
				</H1>
				<div class="bs-example splitInfo" data-example-id="basic-forms" data-content="Numbers">
					<p class="title">
						<span class="name">Invoice :</span>
						<span class="value"></span>
					</p>
					<p class="title">
						<span class="name">Quote :</span>
						<span class="value">
							<a href="#" class="openDocument" data-type="quote" data-file="<?= $split['devis'].'-'.$split['customer'] ?>.pdf"><?= $split['devis'] ?></a>
						</span>
					</p>
					<p class="title">
						<span class="name">Purchase Order :</span>
						<span class="value"><?= $split['po_number']	?></span>
					</p>
					<p class="title">
						<span class="name">Customer VAT :</span>
						<span class="value"><?= $split['VAT']	?></span>
					</p>
					<p class="title">
						<span class="name">MRSAS Customer's Ref :</span>
						<span class="value"><?= $split['MRSASRef']	?></span>
					</p>
					<p class="title">
						<span class="name">MRSAS Customer's Ref :</span>
						<span class="value"><?= $split['info_jobs_instruction']	?></span>
					</p>
				</div>



				<div class="bs-example splitInfo" data-example-id="basic-forms" data-content="Amount">

					<p class="title">
						<span class="name">PO Amount:</span>
						<span class="value"><input type="text" class="form-control" name="order_val" id="order_val" value="<?= $split['order_val']	?>" style="text-align: right;padding: 0px;"></span>
					</p>
					<p class="title">
						<span class="name"><abbr title="MRSAS / SubC">Estimated :</abbr></span>
						<span class="value2"><input type="text" class="form-control" name="order_est" id="order_est" value="<?= $split['order_est']	?>" style="text-align: right;padding: 0px;"></span>
						<span class="value2"><input type="text" class="form-control" name="order_est_subc" id="order_est_subc" value="<?= $split['order_est_subc']	?>" style="text-align: right;padding: 0px;"></span>
					</p>
					<p class="title">
						<span class="name"><abbr title="MRSAS / SubC">Reached Amount:</abbr></span>
						<span class="value2" id="invoice_val"></span>
						<span class="value2" id="invoice_val_subc"></span>
					</p>

					<p class="title">
						<span class="name"><abbr title="MRSAS / Payables">Invoicable :</abbr></span>
						<span class="value2" id="UBRMRSAS"> </span>
						<span class="value2" id="notInv_val_subc"></span>
					</p>
<!--
					<p class="title" style="border-bottom: dotted 1px;">
						<span class="name"><abbr title="MRSAS / SubC">Not Invoice :</abbr></span>
						<span class="value2" id="notInv_val"></span>
						<span class="value2" id="notInv_val_subc"></span>
					</p>
-->
<p class="title">
	<span class="name"><abbr title="MRSAS / Payables">Invoice</abbr></span>
	<span class="value2" >6050</span>
	<span class="value2" >5400</span> <!-- moins deja payé-->
</p>
				</div>

				<div class="bs-example splitInfo" data-example-id="basic-forms" data-content="Invoices">
					<?php foreach ($oInvoices->getAllInvoiceRecorded($split['id_tbljob']) as $inv) : ?>
						<p class="title">
							<span class="name"><?=	$inv['inv_number']	?></span>
							<span class="value2 inv_mrsas"><?=	$inv['inv_mrsas']	?></span>
							<span class="value2 inv_subc"><?=	$inv['inv_subc']	?></span>
						</p>
					<?php endforeach ?>
				</div>

				<div class="bs-example splitInfo" data-example-id="basic-forms" data-content="Payables <?= $sumPayables	?> / -500">
					<?php foreach ($oInvoices->getAllPayablesJob($split['id_tbljob']) as $payable) : ?>
						<p class="title">
							<span class="name"><?=	$payable['payable']	?></span>
							<span class="value"><?= (($payable['USD']>0)?$payable['USD']*$payable['taux']:$payable['HT']) ?></span>
						</p>
					<?php endforeach ?>
				</div>

				<div class="bs-example splitInfo" data-example-id="basic-forms" data-content="Internationalization">
					<p class="title">
						<span class="name">Invoice Language :</span>
						<span class="value">
							<input <?=	($split['invoice_lang']==1)?'checked':''	?> id="invoice_lang" name="invoice_lang" data-toggle="toggle" data-on="<img src='img/FlagUSA.png' style='max-width: auto;max-height: 20px;'>" data-off="<img src='img/FlagFrench.png' style='max-width: auto;max-height: 20px;'>" type="checkbox">
						</span>
					</p>
					<p class="title">
						<span class="name">Currency :</span>
						<span class="value">
							<input <?=	($split['invoice_currency']==1)?'checked':'a'	?> id="invoice_currency" name="invoice_currency" data-toggle="toggle" data-on="<img src='img/dollar.png' style='max-width: auto;max-height: 20px;'>" data-off="<img src='img/euro.png' style='max-width: auto;max-height: 20px;'>" type="checkbox">
						</span>
					</p>
				</div>

				<div class="bs-example splitInfo" data-example-id="basic-forms" data-content="Contacts">
					<p class="title">
						<span class="name">Contact :</span>
						<span class="value">
							<acronym title="Tel : <?=	$split['telephone']	?>"><?= $split['prenom'].' '.$split['nom'] ?></acronym>
							<acronym title="Send Email to Customer(s)">
								<a href="
								mailto: <?= $split['email']	?>
								?subject=Job : <?= $split['customer'].'&nbsp;-&nbsp;'.$split['job'] ?>
								&cc=<?= $split['email2']	?>;<?= $split['email3']	?>;<?= $split['email4']	?>
								&body="><span class="glyphicon glyphicon-envelope"></span></a>
							</acronym>
						</span>
					</p>
					<p class="title">
						<span class="name">Contact :</span>
						<span class="value"><acronym title="Tel : <?=	$split['telephone2']	?>"><?= $split['prenom2'].' '.$split['nom2'] ?></acronym></span>
					</p>
				</div>

				<div class="bs-example splitInfo" data-example-id="basic-forms" data-content="Adress">
					<p class="title">
						<span class="name">Billing Adress :</span>
						<span class="value">
							<?php for ($addr=0; $addr < count($adresse) ; $addr++) {
								echo $split[$adresse[$addr]].'<br/>';
							}
							?>
						</span>
					</p>
				</div>

				<div class="bs-example splitInfo" data-example-id="basic-forms" data-content="Comments">
					<textarea class="form-control" name="invoice_commentaire" id="invoice_commentaire" style="width:100%;" rows="5"><?=	$split['invoice_commentaire'] ?></textarea>
				</div>

			</div>


			<div class="col-md-9" style="height:100%">
				<div style="height:93%; overflow:auto; width:100%;">
					<?php foreach ($splits as $key => $value) :?>
						<div class="bs-example splitInfo" data-example-id="basic-forms" data-content="<?=	$value['split'].'-'.$value['test_type_abbr'].' ('.$value['nbep'].' specimen(s) - '.$value['statut'].')'	?>" data-ST="<?= $value['ST']?>">
							<p class="title">
								<div class="splitInvLine">
									<div class="row titre">
										<div class="col-md-1 code">
											Metcut Code
										</div>
										<div class="col-md-6 pricingList">
											Description
										</div>
										<div class="col-md-1 qteGPM">
											Qté GPM
										</div>
										<div class="col-md-1 qteUser">
											Qté User
										</div>
										<div class="col-md-1 priceUnit">
											Prix Unitaire
										</div>
										<div class="col-md-1 totalUser">
											Total
										</div>
										<div class="col-md-1 delete">
											Delete
										</div>
									</div>
									<?php foreach ($oInvoices->getInvoiceListSplit($value['id_tbljob']) as $invoicelines) : ?>
										<div class="row invoiceLine">
											<form>
												<input class="newEntry" name="newEntry" value="" type="hidden">
												<input class="id_invoiceLine" name="id_invoiceLine" value="<?= $invoicelines['id_invoiceline'] ?>" type="hidden">
												<input class="id_info_job" name="id_info_job" value="<?= $invoicelines['id_info_job'] ?>" type="hidden">
												<input class="id_tbljob" name="id_tbljob" id="id_tbljob" value="<?= $invoicelines['id_tbljob'] ?>" type="hidden">
												<input class="id_pricingList" name="id_pricingList" value="<?= $invoicelines['id_pricingList'] ?>" type="hidden">
												<div class="col-md-1 code"><input class="form-control" name="code" value="<?= (($invoicelines['prodCode']=="")?"":$invoicelines['prodCode']."-").$invoicelines['OpnCode'] ?>" type="text" disabled></div>
												<div class="col-md-6 pricingList"><input class="form-control" name="pricingList" value="<?= $invoicelines['pricingList'] ?>" type="text" readonly></div>
												<div class="col-md-1 qteGPM"><input class="form-control decimal0" name="qteGPM" value="<?= $invoicelines['qteGPM'] ?>" type="text" disabled></div>
												<div class="col-md-1 qteUser"><input class="form-control decimal0" name="qteUser" value="<?= $invoicelines['qteUser'] ?>" type="text"></div>
												<div class="col-md-1 priceUnit"><input class="form-control decimal2" name="priceUnit" value="<?= $invoicelines['priceUnit'] ?>" type="text"></div>
												<div class="col-md-1 totalUser"><input class="form-control decimal2" name="totalUser" value="<?= (($invoicelines['qteUser']!="")?$invoicelines['qteUser']:$invoicelines['qteGPM'])*$invoicelines['priceUnit'] ?>" type="text" disabled></div>
												<div class="col-md-1 delete">
													<input class="toDelete" name="toDelete" value="" type="hidden">
													<button type="button" class="btn btn-danger deleteInvoiceLine" aria-label="Left Align">
														<span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
													</button>
												</div>
											</form>
										</div>
									<?php endforeach ?>
								</div>
							</p>
							<p class="title">
								<div class="splitInvLine2">
									<div class="row">
										<div class="col-md-1">
											<button type="button" class="btn btn-success" aria-label="Left Align">
												<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
											</button>
										</div>
										<div class="col-md-6">
											<select class="form-control addInvLine" data-id_info_job="<?=	$split['id_info_job']	?>" data-id_tbljob="<?=	$value['id_tbljob']	?>">
												<option value="No" data-code ="" data-pricingListUS="Add an Invoice Line" data-pricingListFR="Add an Invoice Line">Add an Invoice Line</option>
												<?php foreach ($oInvoices->getAllInvoiceList($value['id_tbljob']) as $row): ?>
													<option value="<?= $row['id_pricingList'] ?>" data-id_pricingList="<?= $row['id_pricingList'] ?>" data-code="<?= $row['prodCode'].'-'.$row['OpnCode'] ?>" data-prodCode="<?= $row['prodCode'] ?>" data-OpnCode="<?= $row['OpnCode'] ?>" data-type="<?= $row['type'] ?>" data-pricingListUS="<?= $row['pricingListUS'] ?>" data-pricingListFR="<?= $row['pricingListFR'] ?>" data-usd="<?= $row['USD'] ?>" data-euro="<?= $row['EURO'] ?>"><?=  $row['prodCode'].'-'.$row['OpnCode'].' '.$row['pricingList'] ?></option>
												<?php endforeach ?>
											</select>
										</div>
									</div>
								</div>
							</p>
						</div >
					<?php endforeach ?>

					<div class="bs-example splitInfo" data-example-id="basic-forms" data-content="OTHERS"  data-ST="1">
						<p class="title">
							<div class="splitInvLine">
								<div class="row">
									<div class="col-md-1 code">
										Metcut Code
									</div>
									<div class="col-md-6 pricingList">
										Description
									</div>
									<div class="col-md-1 qteGPM">
										Qté GPM
									</div>
									<div class="col-md-1 qteUser">
										Qté User
									</div>
									<div class="col-md-1 priceUnit">
										Prix Unitaire
									</div>
									<div class="col-md-1 totalUser">
										Total
									</div>
									<div class="col-md-1 delete">
										Delete
									</div>
								</div>

								<?php foreach ($oInvoices->getInvoiceListJob($_GET['id_tbljob']) as $invoicelines) : ?>
									<div class="row invoiceLine">
										<form>
											<input class="newEntry" name="newEntry" value="" type="hidden">
											<input class="id_invoiceLine" name="id_invoiceLine" value="<?= $invoicelines['id_invoiceline'] ?>" type="hidden">
											<input class="id_info_job" name="id_info_job" value="<?= $invoicelines['id_info_job'] ?>" type="hidden">
											<input class="id_tbljob" name="id_tbljob" value="<?= $invoicelines['id_tbljob'] ?>" type="hidden">
											<input class="id_pricingList" name="id_pricingList" value="<?= $invoicelines['id_pricingList'] ?>" type="hidden">
											<input class="prodCode" name="prodCode" value="<?= $invoicelines['prodCode'] ?>" type="hidden">
											<input class="OpnCode" name="OpnCode" value="<?= $invoicelines['OpnCode'] ?>" type="hidden">
											<input class="type" name="type" value="<?= $invoicelines['type'] ?>" type="hidden">
											<div class="col-md-1 code"><input class="form-control" name="code" value="<?= (($invoicelines['prodCode']=="")?"":$invoicelines['prodCode']."-").$invoicelines['OpnCode'] ?>" type="text" disabled></div>
											<div class="col-md-6 pricingList"><input class="form-control" name="pricingList" value="<?= $invoicelines['pricingList'] ?>" type="text" readonly></div>
											<div class="col-md-1 qteGPM"><input class="form-control decimal0" name="qteGPM" value="" type="text" disabled></div>
											<div class="col-md-1 qteUser"><input class="form-control decimal0" name="qteUser" value="<?= $invoicelines['qteUser'] ?>" type="text"></div>
											<div class="col-md-1 priceUnit"><input class="form-control decimal2" name="priceUnit" value="<?= $invoicelines['priceUnit'] ?>" type="text"></div>
											<div class="col-md-1 totalUser"><input class="form-control decimal2" name="totalUser" value="<?= $invoicelines['qteUser']*$invoicelines['priceUnit'] ?>" type="text" disabled></div>
											<div class="col-md-1 delete">
												<input class="toDelete" name="toDelete" value="" type="hidden">
												<button type="button" class="btn btn-danger deleteInvoiceLine" aria-label="Left Align">
													<span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
												</button>
											</div>
										</form>
									</div>
								<?php endforeach ?>
							</div>
						</p>
						<p class="title">
							<div class="splitInvLine2">
								<div class="row">
									<div class="col-md-1">
										<button type="button" class="btn btn-success" aria-label="Left Align">
											<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
										</button>
									</div>
									<div class="col-md-6">
										<select class="form-control addInvLine" data-id_info_job="<?=	$split['id_info_job']	?>" data-id_tbljob="">
											<option value="No" data-code ="" data-pricingListUS="Add an Invoice Line" data-pricingListFR="Add an Invoice Line">Add an Invoice Line</option>
											<?php foreach ($oInvoices->getAllInvoiceList() as $row): ?>
												<option value="<?= $row['id_pricingList'] ?>" data-id_pricingList="<?= $row['id_pricingList'] ?>" data-code="<?= $row['prodCode'].'-'.$row['OpnCode'] ?>" data-prodCode="<?= $row['prodCode'] ?>" data-OpnCode="<?= $row['OpnCode'] ?>" data-type="<?= $row['type'] ?>" data-pricingListUS="<?= $row['pricingListUS'] ?>" data-pricingListFR="<?= $row['pricingListFR'] ?>" data-usd="<?= $row['USD'] ?>" data-euro="<?= $row['EURO'] ?>"><?=  $row['prodCode'].'-'.$row['OpnCode'].' '.$row['pricingList'] ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
							</div>
						</p>
					</div >

				</div>

				<div style="height:7%; width:100%; padding:10px 0px;">

					<div class="col-md-12" id="printInvoiceJob" style="height:100%; padding:0px;">
						<a href="#" id="createInvoice" class="btn btn-default btn-lg" style="width:19%; height:100%; padding:0px; border-radius:10px;">
							<p style="font-size:small;height:100%;">
								Ne pas cliquer
								<!--<img type="image" src="img/onenodte.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;">-->
							</p>
						</a>

						<a href="controller/createInvoice-controller.php?id_tbljob=<?=	$_GET['id_tbljob']	?>" class="btn btn-default btn-lg" style="width:80%; height:100%; padding:0px; border-radius:10px;">
							<p style="font-size:small;height:100%;">
								<img type="image" src="img/print.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
							</p>
						</a>
					</div>
					<div class="col-md-12" id="saveInvoiceJob" style="height:100%; padding:0px; display:none;">
						<a href="" class="btn btn-default btn-lg" style="width:100%; height:100%; padding:0px; border-radius:10px;">
							<p style="font-size:small;height:100%;">
								<img type="image" src="img/save.png" style="max-width:50%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
							</p>
						</a>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>



<link href="lib/bootstrap-toggle-master/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="lib/bootstrap-toggle-master/js/bootstrap-toggle.min.js"></script>


<form id="invoiceJob" method="POST" action="controller/updateInvoiceJob.php" style="display:none;">
	<input type="hidden" name="id_tbljob" id="id_tbljob" value="<?=	$split['id_tbljob'] ?>"></input>
</form>

<div class="row" id="invLineVierge" style="display:none;">
	<form>
		<input class="newEntry" name="newEntry" value="" type="hidden">
		<input class="id_invoiceLine" name="id_invoiceLine" value="" type="hidden">
		<input class="prodCode" name="prodCode" value="" type="hidden">
		<input class="OpnCode" name="OpnCode" value="" type="hidden">
		<input class="type" name="type" value="" type="hidden">
		<input class="id_info_job" name="id_info_job" value="" type="hidden">
		<input class="id_tbljob" name="id_tbljob" value="" type="hidden">
		<input class="id_pricingList" name="id_pricingList" value="" type="hidden">
		<div class="col-md-1 code"><input class="form-control" name="code" value="" type="text" disabled></div>
		<div class="col-md-6 pricingList"><input class="form-control" name="pricingList" value="" type="text" readonly></div>
		<div class="col-md-1 qteGPM"><input class="form-control" name="qteGPM" value="" type="text" disabled></div>
		<div class="col-md-1 qteUser"><input class="form-control" name="qteUser" value="" type="text"></div>
		<div class="col-md-1 priceUnit"><input class="form-control" name="priceUnit" value="" type="text"></div>
		<div class="col-md-1 totalUser"><input class="form-control" name="totalUser" value="" type="text" disabled></div>
		<div class="col-md-1 delete">
			<input class="toDelete" name="toDelete" value="" type="hidden">
			<button type="button" class="btn btn-danger deleteInvoiceLine" aria-label="Left Align">
				<span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
			</button>
		</div>
	</form>
</div>


<div id="customForm">
						<fieldset class="informations">
								<legend>Informations</legend>
								<editor-field name="inv_number"></editor-field>
								<editor-field name="inv_job"></editor-field>
								<editor-field name="inv_date"></editor-field>
						</fieldset>
						<fieldset class="amount">
								<legend>Amount</legend>
								<editor-field name="inv_mrsas"></editor-field>
								<editor-field name="inv_subc"></editor-field>
								<editor-field name="inv_total"></editor-field>
						</fieldset>
				</div>
