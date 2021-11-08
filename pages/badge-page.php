<!--<link href="css/badgeValidation.css" rel="stylesheet">-->
<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">

		<link href="css/badge.css" rel="stylesheet">
		<div class="col-md-12" style="height:100%">
			<?php $ini = parse_ini_file('var/config.ini'); ?>
			<div style="display:none;" id="dayhours" data-value="<?= $ini['Badge']['dayhours']	?>"></div>
			<div style="display:none;" id="resthours" data-value="<?= $ini['Badge']['resthours']	?>"></div>
			<table id="table_badge" class="table table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="white-space:nowrap;">
				<caption style="color:white; font-size:200%;">Badging validation (filtered on Delta by default)</caption>
				<thead>
					<tr>
						<th><acronym title="Week Number">W.</acronym></th>
						<th><acronym title="Date">Date</acronym></th>
						<th><acronym title="Date">Date</acronym></th>
						<th><acronym title="Technician">T.</acronym></th>

						<th><acronym title="First In">In</acronym></th>
						<th><acronym title="First Out">Out</acronym></th>
						<th><acronym title="Second In">In</acronym></th>
						<th><acronym title="Second Out">Out</acronym></th>

						<th><acronym title="In-Site Time">I.T.</acronym></th>
						<th><acronym title="Working Time">W.T.</acronym></th>
						<th><acronym title="Adjusted Time">Ad.T.</acronym></th>
						<th><acronym title="Validation Time">V.T.</acronym></th>
						<th><acronym title="Delta Planning">Delta.</acronym></th>
						<th><acronym title="Planning Time">Planning</acronym></th>
						<th><acronym title="Tech's Comments">T. Comments</acronym></th>
						<th><acronym title="Manager's Comments">M. Comments</acronym></th>
						<th><acronym title="Validating User">V.U.</acronym></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><acronym title="Week Number">W.</acronym></th>
						<th><acronym title="Date">Date</acronym></th>
						<th><acronym title="Date">Date</acronym></th>
						<th><acronym title="Technician">T.</acronym></th>

						<th><acronym title="First In">In</acronym></th>
						<th><acronym title="First Out">Out</acronym></th>
						<th><acronym title="Second In">In</acronym></th>
						<th><acronym title="Second Out">Out</acronym></th>

						<th><acronym title="In-Site Time">I.T.</acronym></th>
						<th><acronym title="Working Time">W.T.</acronym></th>
						<th><acronym title="Adjusted Time">Ad.T.</acronym></th>
						<th><acronym title="Validation Time">V.T.</acronym></th>
						<th><acronym title="Delta Planning">Delta.</acronym></th>
						<th><acronym title="Planning Time">P.T.</acronym></th>
						<th><acronym title="Tech's Comments">T. Comments</acronym></th>
						<th><acronym title="Manager's Comments">M. Comments</acronym></th>
						<th><acronym title="Validating User">V.U.</acronym></th>
					</tr>
				</tfoot>

			</table>
		</div>





	</div>
</div>

<script type="text/javascript" src="js/badge.js"></script>
<?php
require('views/login-view.php');
?>
