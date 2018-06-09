<link href="css/frameUtilization.css" rel="stylesheet">

<script src="lib/plotly/plotly-latest.min.js"></script>


<a href="index.php?page=frameUtilization" style="height:100%;">

  <div id="chartEtatMachine" style="height:100%;"></div>


  <?php
  $traceX= "";
  $traceY1= "";
  $traceY2= "";
  $traceY3= "";
  $traceY4= "";
  $traceY5= "";
  $traceY6= "";


  foreach ($oEtatMachines->getAllFrameUtilization($_GET['group'],$_GET['filtre']) as $row)	{


    if ($_GET['filtre']=="Frame") {
      $traceX .=',"'.$row['machine'].'"';
    }
    else {
      if ($_GET['group']=="Year") {
        $traceX .=',"'.date("Y", strtotime($row['periode'])).'"';
      }
      elseif ($_GET['group']=="Month") {
        $traceX .=',"'.date("Y-m", strtotime($row['periode'])).'"';
      }
      elseif ($_GET['group']=="Day") {
        $traceX .=',"'.date("M-d", strtotime($row['periode'])).'"';
      }
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
    title:'Frame Utilization : <?=	$_GET['group'].' '.$_GET['filtre']	?>',





    <?php     if ($_GET['filtre']=="Frame") : ?>
    xaxis: {
      title: 'Frame',
      tickformat :".0f"
    },
    <?php     else :  ?>
      <?php if ($_GET['group']=="Year") : ?>
      xaxis: {
        title: 'Date (Year)'
      },
      <?php       elseif ($_GET['group']=="Month") :  ?>
      xaxis: {
        title: 'Date (Y-mm)'
      },
      <?php   elseif ($_GET['group']=="Day") :  ?>
      xaxis: {
        title: 'Date (mm-dd)'
      },
      <?php endif ?>
    <?php endif ?>






    yaxis: {
      title: 'Occupancy Time (%)',
      gridcolor:"#5B9BD5"
    },
    paper_bgcolor:"#44546A",
    plot_bgcolor:"#44546A",
    font:{color:"#FFF"},
    showlegend: false,
  };

  Plotly.newPlot('chartEtatMachine', data, layout);

  </script>






</a>
