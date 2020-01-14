<!--<link href="css/badgeValidation.css" rel="stylesheet">-->
<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid">

		<link href="css/gestionPoste.css" rel="stylesheet">
		<div class="col-md-12" style="height:100%">
			<?php $ini = parse_ini_file('var/config.ini'); ?>
			<div style="display:none;" id="dayhours" data-value="<?= $ini['Badge']['dayhours']	?>"></div>
			<div style="display:none;" id="resthours" data-value="<?= $ini['Badge']['resthours']	?>"></div>
			<table id="table_badge" class="table table-condensed table-hover table-bordered" cellspacing="0" width="100%"  style="white-space:nowrap;">
				<caption style="color:white; font-size:200%;">Technician check in/out (filtered on last week by default)</caption>
				<thead>
					<tr>
						<th><acronym title="Week Number">W.</acronym></th>
						<th><acronym title="Date">Date</acronym></th>
						<th><acronym title="Technician">T.</acronym></th>

						<th><acronym title="Badge on AM">AM</acronym></th>
						<th><acronym title="Badge on PM">PM</acronym></th>

						<th><acronym title="Full or half day">Day</acronym></th>
						<th><acronym title="Validation Day">V.D.</acronym></th>
						<th><acronym title="Comments">Comments</acronym></th>
						<th><acronym title="Validating User">V.U.</acronym></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><acronym title="Week Number">W.</acronym></th>
						<th><acronym title="Date">Date</acronym></th>
						<th><acronym title="Technician">T.</acronym></th>

						<th><acronym title="Badge on AM">AM</acronym></th>
						<th><acronym title="Badge on PM">PM</acronym></th>

						<th><acronym title="Full or half day">Day</acronym></th>
						<th><acronym title="Validation Day">V.D.</acronym></th>
						<th><acronym title="Comments">Comments</acronym></th>
						<th><acronym title="Validating User">V.U.</acronym></th>
					</tr>
				</tfoot>

			</table>
		</div>





	</div>
</div>

<script type="text/javascript" src="js/badge2Users.js"></script>
<?php
require('views/login-view.php');
?>
