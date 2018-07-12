<link href="css/frameUtilization.css" rel="stylesheet">

<script src="lib/plotly/plotly-latest.min.js"></script>



  <div id="chartPareto" style="height:100%;"></div>


  <?php
  $traceX= "";
  $traceY= "";


  foreach ($oQualite->getFlagPareto($_GET['startDate'],$_GET['endDate']) as $row)	{

    $traceX .=',"'.$row['incident_cause'].'"';
    $traceY .=','.$row['nb'];

  }
  ?>

  <script>
  var data = [{
    x: [''<?= $traceX	?>],
    y: [''<?= $traceY	?>],
    name: 'Cycling',
    marker: {color: 'darkgreen'},
    type: 'bar'
  }];



  var layout = {
    barmode: 'stack',
    title:'Quality Flag Reason : <?=	$_GET['startDate'].' to '.$_GET['endDate']	?>',


    yaxis: {
      title: 'Occurrence',
      gridcolor:"#5B9BD5"
    },
    paper_bgcolor:"#44546A",
    plot_bgcolor:"#44546A",
    font:{color:"#FFF"},
    showlegend: false,
  };

  Plotly.newPlot('chartPareto', data, layout);

  </script>
