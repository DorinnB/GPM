<div class="row" style="height:5%;margin-top:10px;">
	<div id="btn" class="col-md-2" style="height:100%;">
	</div>
	<div id="" class="col-md-1" style="height:100%;">
		<a href="index.php?page=purchases" class="btn btn-default" style="width:100%; margin: 0px; padding:0px; border-radius:10px;" role="button"><img src="img/purchaserequest.png" style="height:40px;" > POR</a>
	</div>
	<?php if($user->is_accounting()) : ?>
		<div id="" class="col-md-1" style="height:100%;">
			<a href="index.php?page=payables" class="btn btn-info" style="width:100%; margin: 0px; padding:0px; border-radius:10px;" role="button"><img src="img/payable.png" style="height:40px;" > Payables</a>
		</div>
		<div id="" class="col-md-1" style="height:100%;">
			<a  href="index.php?page=invoices" class="btn btn-info" style="width:100%; margin: 0px; padding:0px; border-radius:10px;"role="button"><img src="img/invoice.png" style="height:40px;"> Invoices</a>
		</div>
		<div id="" class="col-md-1" style="height:100%;">
			<a  href="index.php?page=quotations" class="btn btn-default" style="width:100%; margin: 0px; padding:0px; border-radius:10px;"role="button"><img src="img/quotation.png" style="height:40px;"> Quotations</a>
		</div>
		<div id="" class="col-md-1" style="height:100%;">
			<a  href="index.php?page=UBR" class="btn btn-default" style="width:100%; margin: 0px; padding:0px; border-radius:10px;"role="button"><img src="img/ubr.png" style="height:40px;"> UBR</a>
		</div>
		<div id="" class="col-md-1" style="height:100%;">
			<a  href="index.php?page=backlog" class="btn btn-default" style="width:100%; margin: 0px; padding:0px; border-radius:10px;"role="button"><img src="img/backlog.png" style="height:40px;"> Backlog</a>
		</div>
		<div id="" class="col-md-1" style="height:100%;">
			<a  href="index.php?page=monthlyStatement" class="btn btn-default" style="width:100%; margin: 0px; padding:0px; border-radius:10px;"role="button"><img src="img/statement.png" style="height:40px;"> Monthly Stat.</a>
		</div>
		<?php if($user->is_bu()) : ?>
			<div id="" class="col-md-1" style="height:100%;">
				<a  href="index.php?page=kpi" class="btn btn-default" style="width:100%; margin: 0px; padding:0px; border-radius:10px;"role="button"><img src="img/statement.png" style="height:40px;"> KPI</a>
			</div>
		<?php endif ?>
		<div id="" class="col-md-1" style="height:100%;">
			<a  href="#" class="btn btn-default" style="width:100%; margin: 0px; padding:0px; border-radius:10px;" role="button" title="Accounting Files" data-toggle="modal" data-target="#AccountingFileModal" ><img src="img/export.png" style="height:40px;"> Acc. File</a>
		</div>
	<?php endif ?>
</div>
