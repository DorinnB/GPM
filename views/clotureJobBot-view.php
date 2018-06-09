<div class="row splitBot" style="height:20%" id="invoice" data-editor_id="row_<?=	$split['id_info_job']?>" data-editor-id="row_<?=	$split['id_info_job']?>">

	<div class="col-md-2" style="height:100%">


		<label for="invoice_type">Invoice :</label>
		<select  class="form-control" id="invoice_type">
			<option value="0" <?=	(($split['invoice_type']==0)?'selected':'')	?>>Not Invoiced</option>
			<option value="1" <?=	(($split['invoice_type']==1)?'selected':'')	?>>Partialy Invoiced</option>
			<option value="2" <?=	(($split['invoice_type']==2)?'selected':'')	?>>Total Invoiced</option>
		</select>


	</div>
	<div class="col-md-3" style="height:100%">


		<label for="invoice_date">Invoice Date :</label>
		<input type="text" class="form-control" name="invoice_date" id="invoice_date" value="<?=	$split['invoice_date']	?>">
		<input type="hidden" name="id_info_job" id="id_info_job" value="<?=	$split['id_info_job']	?>">


	</div>
	<div class="col-md-6" style="height:100%">


		<p for="invoice_commentaire">Comments :</p>
		<textarea class="commentaireEditable" id="invoice_commentaire" name="invoice_commentaire"><?=	$split['invoice_commentaire']	?></textarea>


	</div>
	<div class="col-md-1" id="split-bouton" style="height:100%">
		<div class="row" style="height:100%">
			<div class="col-md-12" id="save" style="height:33%">
				<img type="image" src="img/save.png" style="max-width:100%; max-height:100%; padding:5px 0px;display: block; margin: auto;" />
			</div>

			<div class="col-md-12" style="height:33%">

				<a href="index.php?page=invoiceJob&id_tbljob=<?=	$split['id_tbljob']?>">
				<img border="0" src="img/invoice.png" style="max-width:100%; max-height:100%; padding:5px 0px;display: block; margin: auto;">
				</a>

			</div>


			<div class="col-md-12" id="export" style="height:33%">
				??
			</div>
		</div>



	</div>

</div>
<script type="text/javascript" src="js/clotureJob_invoice.js"></script>
