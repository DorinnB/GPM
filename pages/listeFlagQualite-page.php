<link href="css/listeFlagQualite.css" rel="stylesheet">
<script> filtre=<?=	$_GET['filtre']	?>;	</script>


<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">

		<div class="col-md-12" style="height:100%">

			<table id="table_listeFlagQualite" class="table table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%;">
				<thead>
					<tr>
						<th><acronym title="customer">Cust.</acronym></th>
						<th><acronym title="job">Job</acronym></th>
						<th><acronym title="split">Split</acronym></th>
						<th><acronym title="n_fichier">File</acronym></th>
						<th><acronym title="">Machine</acronym></th>
						<th><acronym title="">Lab Comm</acronym></th>
						<th><acronym title="">Tech</acronym></th>
						<th><acronym title="">Comm Qualite</acronym></th>
						<th><acronym title="">Valid</acronym></th>
						<th><acronym title="">Cause</acronym></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><acronym title="customer">Cust.</acronym></th>
						<th><acronym title="job">Job</acronym></th>
						<th><acronym title="split">Split</acronym></th>
						<th><acronym title="n_fichier">File</acronym></th>
						<th><acronym title="">Machine</acronym></th>
						<th><acronym title="">Lab Comm</acronym></th>
						<th><acronym title="">Tech</acronym></th>
						<th><acronym title="">Comm Qualite</acronym></th>
						<th><acronym title="">Valid</acronym></th>
						<th><acronym title="">Cause</acronym></th>
					</tr>
				</tfoot>

			</table>


		</div>

		<script type="text/javascript" src="js/listeFlagQualite.js"></script>




	</div>
</div>
<?php
require('views/login-view.php');
?>
