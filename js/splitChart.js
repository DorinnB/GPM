$("#myChart").click(function(e) {


  chartFile=  [];


  //pour chaque ligne du tableau eprouvettes
  $('#table_ep').find('tr.chartTR').each( function (i) {
    //on cherche ceux ou il y a un nombre de cycle (en otant les espaces)
    if ($(this).find('.chartFile').html().replace(/ /g,'')>0) {
      chartFile.push('File : '+$(this).find('.chartFile').html().replace(/ /g,''));
    }
  });


  //console.log(chartFile);



  //Graph Dimensionel 1
  if ($('.chartIQCdim1')[0]) {
    chartFile=  [];
    endLevel_IQCdim1_Y=  [];
    //pour chaque ligne du tableau eprouvettes
    $('#table_ep').find('tr.chartTR').each( function (i) {
      //on cherche ceux ou il y a un dimensionnel (en otant les espaces)
      chartFile.push('Specimen : '+$(this).find('.chartFile').html().replace(/ /g,''));
      endLevel_IQCdim1_Y.push($(this).find('.chartIQCdim1').html().replace(/ /g,''));
    });

    var traceIQCdim1 = {
      y: endLevel_IQCdim1_Y,
      text: chartFile,
      mode: 'markers'
    };

    var dataIQCdim1 = [ traceIQCdim1 ];

    var layoutIQCdim1 = {
      title:'Dimensional',
      xaxis: {
        autorange: true,
        showgrid: false,
        zeroline: false,
        showline: false,
        autotick: true,
        ticks: '',
        showticklabels: false
      },
      yaxis: {title: 'mm'}
    };

    if ($( "#chartIQCdim1" ).length == 0) {
      $( "#chart" ).append( "<div class='item active'><div id='chartIQCdim1' class='chart'></div></div>" );
    }

    Plotly.newPlot('chartIQCdim1', dataIQCdim1, layoutIQCdim1);
  }

  //Graph Dimensionel 2
  if ($('.chartIQCdim2')[0]) {
    chartFile=  [];
    endLevel_IQCdim2_Y=  [];
    //pour chaque ligne du tableau eprouvettes
    $('#table_ep').find('tr.chartTR').each( function (i) {
      //on cherche ceux ou il y a un dimensionnel (en otant les espaces)
      chartFile.push('Specimen : '+$(this).find('.chartFile').html().replace(/ /g,''));
      endLevel_IQCdim2_Y.push($(this).find('.chartIQCdim2').html().replace(/ /g,''));
    });

    var traceIQCdim2 = {
      y: endLevel_IQCdim2_Y,
      text: chartFile,
      mode: 'markers'
    };

    var dataIQCdim2 = [ traceIQCdim2 ];

    var layoutIQCdim2 = {
      title:'Dimensional',
      xaxis: {
        autorange: true,
        showgrid: false,
        zeroline: false,
        showline: false,
        autotick: true,
        ticks: '',
        showticklabels: false
      },
      yaxis: {title: 'mm'}
    };

    if ($( "#chartIQCdim2" ).length == 0) {
      $( "#chart" ).append( "<div class='item'><div id='chartIQCdim2' class='chart'></div></div>" );
    }

    Plotly.newPlot('chartIQCdim2', dataIQCdim2, layoutIQCdim2);
  }

  //Graph Dimensionel 2
  if ($('.chartIQCdim3')[0]) {
    chartFile=  [];
    endLevel_IQCdim3_Y=  [];
    //pour chaque ligne du tableau eprouvettes
    $('#table_ep').find('tr.chartTR').each( function (i) {
      //on cherche ceux ou il y a un dimensionnel (en otant les espaces)
      chartFile.push('Specimen : '+$(this).find('.chartFile').html().replace(/ /g,''));
      endLevel_IQCdim3_Y.push($(this).find('.chartIQCdim3').html().replace(/ /g,''));
    });

    var traceIQCdim3 = {
      y: endLevel_IQCdim3_Y,
      text: chartFile,
      mode: 'markers'
    };

    var dataIQCdim3 = [ traceIQCdim3 ];

    var layoutIQCdim3 = {
      title:'Dimensional',
      xaxis: {
        autorange: true,
        showgrid: false,
        zeroline: false,
        showline: false,
        autotick: true,
        ticks: '',
        showticklabels: false
      },
      yaxis: {title: 'mm'}
    };

    if ($( "#chartIQCdim3" ).length == 0) {
      $( "#chart" ).append( "<div class='item active'><div id='chartIQCdim3' class='chart'></div></div>" );
    }

    Plotly.newPlot('chartIQCdim3', dataIQCdim3, layoutIQCdim3);
  }



  //Graph Nb cycle
  if ($('.chartNiveau')[0]) {

    endLevel_Cycle_X=  [];
    endLevel_Cycle_Y=  [];
    //pour chaque ligne du tableau eprouvettes
    $('#table_ep').find('tr.chartTR').each( function (i) {
      //on cherche ceux ou il y a un nombre de cycle (en otant les espaces)
      endLevel_Cycle_X.push($(this).find('.chartCycle').html().replace(/ /g,''));
      endLevel_Cycle_Y.push($(this).find('.chartNiveau').html().replace(/ /g,''));
    });

    var traceCycle = {
      x: endLevel_Cycle_X,
      y: endLevel_Cycle_Y,
      text: chartFile,
      mode: 'markers'
    };

    var dataCycle = [ traceCycle ];

    var layoutCycle = {
      title:'Cycle vs Requirement Endlevel',
      xaxis: {title: 'Cycle', type: 'log'},
      yaxis: {title: $('#ChartTitreCons').attr('data-titre')},
    };

    if ($( "#chartCycle" ).length == 0) {
      $( "#chart" ).append( "<div class='item active'><div id='chartCycle' class='chart'></div></div>" );
    }

    Plotly.newPlot('chartCycle', dataCycle, layoutCycle);
  }



  //Graph Module ambiant
  if ($('.chartErt')[0]) {

    endLevel_Ert_X=  [];
    endLevel_Ert_Y=  [];
    //pour chaque ligne du tableau eprouvettes
    $('#table_ep').find('tr.chartTR').each( function (i) {
      //on cherche ceux ou il y a un nombre de cycle (en otant les espaces)
      endLevel_Ert_Y.push('1');
      endLevel_Ert_X.push($(this).find('.chartErt').html().replace(/ /g,''));
    });

    var traceErt = {
      x: endLevel_Ert_X,
      y: endLevel_Ert_Y,
      text: chartFile,
      mode: 'markers'
    };

    var dataErt = [ traceErt ];

    var layoutErt = {
      title:'Modulus',
      xaxis: {title: 'Modulus (GPa)'},
      yaxis:{
        autorange: true,
        showgrid: false,
        zeroline: false,
        showline: false,
        autotick: true,
        ticks: '',
        showticklabels: false
      }
    };

    if ($( "#chartErt" ).length == 0) {
      $( "#chart" ).append( "<div class='item'><div id='chartErt' class='chart'></div></div>" );
    }

    Plotly.newPlot('chartErt', dataErt, layoutErt);
  }


  //Graph stress def mi vie
  if ($('.chartNiveau')[0]) {

    endLevel_StressStrain_X=  [];
    endLevel_StressStrain_Y=  [];
    //pour chaque ligne du tableau eprouvettes
    $('#table_ep').find('tr.chartTR').each( function (i) {
      //on cherche ceux ou il y a un nombre de cycle (en otant les espaces)
      endLevel_StressStrain_Y.push($(this).find('.chartStressMax').html().replace(/ /g,''));
      endLevel_StressStrain_X.push($(this).find('.chartStrainRange').html().replace(/ /g,''));
    });

    var traceStressStrain = {
      x: endLevel_StressStrain_X,
      y: endLevel_StressStrain_Y,
      text: chartFile,
      mode: 'markers'
    };

    var dataStressStrain = [ traceStressStrain ];

    var layoutStressStrain = {
      title:'Strain vs Stress at half life',
      xaxis: {title: 'Strain (%)'},
      yaxis: {title: 'Stress Max (MPa)'},
    };

    if ($( "#chartStressStrain" ).length == 0) {
      $( "#chart" ).append( "<div class='item'><div id='chartStressStrain' class='chart'></div></div>" );
    }

    Plotly.newPlot('chartStressStrain', dataStressStrain, layoutStressStrain);
  }



  //  var data = [ trace1, trace2, trace3 ];



});
