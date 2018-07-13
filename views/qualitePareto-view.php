<script type="text/javascript" src="jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<link rel="stylesheet" href="jquery/jquery-ui-1.12.1.custom/jquery-ui.css">
<script src="lib/plotly/plotly-latest.min.js"></script>


<a href="index.php?page=qualitePareto<?=  isset($_GET['startDate'])?'&startDate='.$_GET['startDate']:''  ?><?=  isset($_GET['endDate'])?'&endDate='.$_GET['endDate']:''  ?>" style="height:100%;">
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
  $( "#startDate" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });
  $( "#endDate" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });

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

</a>
