<!--<link href="css/listeEssais.css" rel="stylesheet">-->
<link href="css/etatmachines.css" rel="stylesheet">
<script type="text/javascript" src="js/etatmachines.js"></script>
<script src="lib/plotly/plotly-latest.min.js"></script>

<?php include('controller/etatmachines-controller.php'); ?>

<div id="page-content-wrapper" style="height:100%">
	<div class="container-fluid" style="height:100%">
		<div id="chartEtatMachine" style="height:95%; margin:20px;"></div>


		<?php
		$traceX= "";
		$traceY1= "";
		$traceY2= "";
		$traceY3= "";
		$traceY4= "";
		$traceY5= "";
		$traceY6= "";


		foreach ($oEtatMachines->getAllEtatMachines_machine((isset($_GET['filtre'])?$_GET['filtre']:'')) as $row)	{

			if (isset($_GET['filtre']) AND $_GET['filtre']=="Year") {
				$traceX .=',"'.date("Y", strtotime($row['periode'])).'"';
			}
			elseif (isset($_GET['filtre']) AND $_GET['filtre']=="Month") {
				$traceX .=',"'.date("M-Y", strtotime($row['periode'])).'"';
			}
			else {
				$traceX .=',"'.$row['machine'].'"';
			}
			$total=$row['cycling']+$row['rampToTemp']+$row['noncycling']+$row['noTest']+$row['waitingCustomer']+$row['waitingLab'];

			$traceY1 .=','.number_format($row['cycling']/$total*100,1);
			$traceY2 .=','.number_format($row['rampToTemp']/$total*100,1);
			$traceY3 .=','.number_format($row['noncycling']/$total*100,1);
			$traceY4 .=','.number_format($row['noTest']/$total*100,1);
			$traceY5 .=','.number_format($row['waitingCustomer']/$total*100,1);
			$traceY6 .=','.number_format($row['waitingLab']/$total*100,1);

		}
		?>

		<script>
		var trace1 = {
			x: [''<?= $traceX	?>],
			y: [''<?= $traceY1	?>],
			name: 'Cycling',
			marker: {color: 'darkgreen'},
			type: 'bar'
		};

		var trace2 = {
			x: [''<?= $traceX	?>],
			y: [''<?= $traceY2	?>],
			name: 'RampToTemp',
			marker: {color: 'yellowgreen'},
			type: 'bar'
		};

		var trace3 = {
			x: [''<?= $traceX	?>],
			y: [''<?= $traceY3	?>],
			name: 'nonCycling',
			marker: {color: '#999900'},
			type: 'bar'
		};

		var trace4 = {
			x: [''<?= $traceX	?>],
			y: [''<?= $traceY4	?>],
			name: 'noTest',
			marker: {color: 'darkred'},
			type: 'bar'
		};

		var trace5 = {
			x: [''<?= $traceX	?>],
			y: [''<?= $traceY5	?>],
			name: 'waitingCustomer',
			marker: {color: 'coral'},
			type: 'bar'
		};

		var trace6 = {
			x: [''<?= $traceX	?>],
			y: [''<?= $traceY6	?>],
			name: 'waitingLab',
			marker: {color: 'tomato'},
			type: 'bar'
		};


		var data = [trace1, trace2, trace3, trace4, trace5, trace6];

		var layout = {
			barmode: 'stack',
			title:'Frame Occupancy',
			xaxis: {
				title: '<?=	isset($_GET['filtre'])?$_GET['filtre']:'Frame'	?>',
				tickformat :".0f"
			},
			yaxis: {title: 'Occupancy Time (%)'},
			paper_bgcolor:"#44546A",
			plot_bgcolor:"#44546A",
			font:{color:"#FFF"},
		};

		Plotly.newPlot('chartEtatMachine', data, layout);

	</script>


	<!--
	<table id="table_etatmachines" class="table table-condensed table-striped table-hover table-bordered" cellspacing="0" width="100%"  style="height:100%; white-space:nowrap;">
	<thead>
	<tr>
	<th><acronym title='etatmachine'>Etat</acronym></th>
	<th><acronym title='Machine'>Machine</acronym></th>
	<th><acronym title='Test Type'>Test Type</acronym></th>
	<th><acronym title='Split'>Split</acronym></th>
	<th><acronym title='Statut'>Statut</acronym></th>
	<th><acronym title='Period'>Period</acronym></th>
	<th><acronym title='Cumul'>Cumul</acronym></th>
</tr>
</thead>
<tfoot>
<tr>
<th><acronym title='etatmachine'>Etat</acronym></th>
<th><acronym title='Machine'>Machine</acronym></th>
<th><acronym title='Test Type'>Test Type</acronym></th>
<th><acronym title='Split'>Split</acronym></th>
<th><acronym title='Statut'>Statut</acronym></th>
<th><acronym title='Period'>Period</acronym></th>
<th><acronym title='Cumul'>Cumul</acronym></th>
</tr>
</tfoot>
<tbody>
<php foreach ($oEtatMachines->getAllEtatMachines() as $row): ?>
<tr>
<td><= $row['etatmachine'] ?></td>
<td><= $row['machine'] ?></td>
<td><= $row['test_type_abbr'] ?></td>
<td><= $row['id_tbljob'] ?></td>
<td><= $row['statut'] ?></td>
<td><= $row['periode'] ?></td>
<td><= $row['cumul'] ?></td>
</tr>
<php endforeach ?>
</tbody>
</table>
</div>
-->
</div>
<?php
include('controller/splitChart-controller.php');
require('views/login-view.php');
?>
