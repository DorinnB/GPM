
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-KPI.php",
      type: "POST",
    },
    table: "#table_KPI",
    fields: [
      { label: "backlogMRSAS", name: "kpi.backlogMRSAS"},
      { label: "backlogTotal", name: "kpi.backlogTotal"},
      { label: "cdeMRSAS", name: "kpi.cdeMRSAS"},
      { label: "obj_prodMRSAS", name: "kpi.obj_prodMRSAS"},
      { label: "obj_invTotal", name: "kpi.obj_invTotal"}
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_KPI tfoot th').each( function (i) {
    var title = $('#table_KPI thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );

  var table = $('#table_KPI').DataTable( {
    dom: "rtp",
    columns: [
      { data: "kpi.date_kpi" },
      { data: "ubrMRSAS" },
      { data: "ubrTotal" },
      { data: "invMRSAS" },
      { data: "invTotal" },
      { data: "payable_A" },
      { data: "payable_B" },
      { data: "payable_C" },
      { data: "payable_D" },
      { data: "payable_E" },
      { data: "payable_F" },
      { data: "payable_G" },
      { data: "payable_Total" },
      { data: "workingDay" },
      { data: "sickDay" },
      { data: "kpi.backlogMRSAS" },
      { data: "kpi.backlogTotal" },
      { data: "kpi.cdeMRSAS" },
      { data: "prodMRSAS" },
      { data: "prodTotal" },
      { data: "c_prodMRSAS" },
      { data: "c_prodTotal" },
      { data: "c_invMRSAS" },
      { data: "c_invTotal" },
      { data: "ratioProd" },
      { data: "nbTest" },
      { data: "occupancy" },
      { data: "occupancyExtented" },

      { data: "kpi.obj_prodMRSAS" },
      { data: "kpi.obj_invTotal" },
      { data: "y_prodMRSAS" },
      { data: "y_invMRSAS" }
    ],
    order: [ 0, "desc" ],
    scrollY: '55vh',
    scrollX : true,
    scrollCollapse: true,
    paging: false,
    info: false,
    fixedColumns:   {leftColumns: 1},
    autoFill: {
      columns: [15, 16, 17, 28, 29],
      editor:  editor
    },
    keys: {
      columns: [15, 16, 17, 28, 29],
      editor:  editor
    },
    select: {
      style:    'os',
      blurable: true
    }
  } );

  // Filter event handler
  $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
    if (this.value.substr(0,1)=='!') {
      search='^((?!'+this.value.substring(1)+').)*$';
    }
    else {
      search=this.value;
    }
    table
    .column( $(this).data('index') )
    .search( search, true, false )
    .draw();
  } );




  function addCommas(nStr)  { //fonction espace millier
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + ' ' + '$2');
    }
    return x1 + x2;
  }


  $('.decimal2').each( function (i) { //ajouter 2 digit sur le nombre
    var num = parseFloat($(this).text());
    if (!isNaN(num)) {
      deci=num.toFixed(2)
      val=addCommas(deci);
      $(this).html(val);
    }
  });



  $("#tab_ubrMRSAS").click(function(e) {

    //clear variable
    $('#table_KPI').find('tr.chartTR').eq(0).find('input').each( function (i) {
      window[$(this).attr('name') + '_data']= [];
    });

    //pour chaque ligne du tableau eprouvettes
    $('#table_KPI').find('tr.chartTR').each( function (i) {
      $(this).find('input').each( function (i) {
        if (!window[$(this).attr('name') + '_data']) {
          window[$(this).attr('name') + '_data']= [];
        }
        window[$(this).attr('name') + '_data'].push($(this).val()); //on crée une variable array par element
      });
    });

    var traceubrMRSAS = {
      x: dateKPI_data,
      y: ubrMRSAS_data,
      base: 0,
      name: 'var UBR MRSAS',
      text: dateKPI_data,
      marker: {color: 'lightgreen'},
      type: 'bar'
    };

    var traceinvMRSAS = {
      x: dateKPI_data,
      y: invMRSAS_data,
      base: invMRSAS_base_data,
      name: 'INV MRSAS',
      text: dateKPI_data,
      marker: {color: 'lightblue'},
      type: 'bar'
    };

    var traceprodMRSAS = {
      x: dateKPI_data,
      y: prodMRSAS_data,
      name: 'Production MRSAS',
      text: dateKPI_data,
      type: 'bar',
      base: 0,
      marker: {
        color: 'transparent',
        opacity: 0.6,
        line: {
          color: 'rgb(8,48,107)',
          width: 1.5
        }
      }
    };

    var traceprodMRSAS_OLD = {
      x: dateKPI_data,
      y: prodMRSAS_y_data,
      base: 0,
      name: 'PROD N-1',
      text: dateKPI_data,
      marker: {color: 'orange'},
      mode: 'lines'
    };

    var dataubrMRSAS = [ traceinvMRSAS, traceubrMRSAS, traceprodMRSAS, traceprodMRSAS_OLD ];

    var layoutubrMRSAS = {
      title:'MRSAS Testing - Production',
      autosize: true,
      barmode: 'stack',
      xaxis: {
        title: 'Date (Y-mm)'
      },
      yaxis: {title: '€'}
    };

    var config = {responsive: true}

    Plotly.newPlot('ubrMRSAS', dataubrMRSAS, layoutubrMRSAS, config);

  });

  $("#tab_sales").click(function(e) {

    //clear variable
    $('#table_KPI').find('tr.chartTR').eq(0).find('input').each( function (i) {
      window[$(this).attr('name') + '_data']= [];
    });

    //pour chaque ligne du tableau eprouvettes
    $('#table_KPI').find('tr.chartTR').each( function (i) {
      $(this).find('input').each( function (i) {
        if (!window[$(this).attr('name') + '_data']) {
          window[$(this).attr('name') + '_data']= [];
        }
        window[$(this).attr('name') + '_data'].push($(this).val()); //on crée une variable array par element
      });
    });

    var layoutubrMRSAS = {
      title:'Sales',
      autosize: true,
      barmode: 'stack',
      xaxis: {
        title: 'Date (Y-mm)'
      },
      yaxis: {title: '€'},
      yaxis2: {
        title: '%',
        titlefont: {color: 'rgb(148, 103, 189)'},
        tickfont: {color: 'rgb(148, 103, 189)'},
        overlaying: 'y',
        side: 'right'
      }
    };


    var t_obj_invTotal = {
      x: dateKPI_data,
      y: obj_invTotal_data,
      name: 'obj invTotal',
      text: dateKPI_data,
      fill: 'tozeroy',
      type: 'scatter',
      mode: 'none',
    };

    var t_ubrTotal = {
      x: dateKPI_data,
      y: ubrTotal_data,
      name: 'ubrTotal',
      text: dateKPI_data,
      fill: 'tozeroy',
      type: 'scatter',
      mode: 'none',
    };

    var t_sales = {
      x: dateKPI_data,
      y: c_inv_Total_data,
      name: 'Sales',
      text: dateKPI_data,
      marker: {color: 'blue'},
      type: 'bar'
    };

    var t_c_sales = {
      x: dateKPI_data,
      y: c_inv_Total_y_data,
      name: 'Sales N-1',
      text: dateKPI_data,
      type: 'line'
    };

    var t_invTotal_ratio = {
      x: dateKPI_data,
      y: invTotal_ratio_data,
      yaxis: 'y2',
      name: '% Sales N-1',
      text: dateKPI_data,
      type: 'scatter',
      mode: 'markers'
    };


    var datasales = [ t_obj_invTotal, t_ubrTotal, t_sales, t_c_sales, t_invTotal_ratio ];

    var config = {responsive: true}


    Plotly.newPlot('sales', datasales, layoutubrMRSAS, config);

  });

  $("#tab_cde").click(function(e) {

    //clear variable
    $('#table_KPI').find('tr.chartTR').eq(0).find('input').each( function (i) {
      window[$(this).attr('name') + '_data']= [];
    });

    //pour chaque ligne du tableau eprouvettes
    $('#table_KPI').find('tr.chartTR').each( function (i) {
      $(this).find('input').each( function (i) {
        if (!window[$(this).attr('name') + '_data']) {
          window[$(this).attr('name') + '_data']= [];
        }
        window[$(this).attr('name') + '_data'].push($(this).val()); //on crée une variable array par element
      });
    });

    var layoutubrMRSAS = {
      title:'Sales',
      autosize: true,
      barmode: 'stack',
      xaxis: {
        title: 'Date (Y-mm)'
      },
      yaxis: {title: '€'},
      yaxis2: {
        title: '%',
        titlefont: {color: 'rgb(148, 103, 189)'},
        tickfont: {color: 'rgb(148, 103, 189)'},
        overlaying: 'y',
        side: 'right'
      }
    };


    var t_backlogMRSAS = {
      x: dateKPI_data,
      y: backlogMRSAS_data,
      name: 'backlogMRSAS',
      text: dateKPI_data,
      fill: 'tozeroy',
      type: 'scatter',
      mode: 'none',
    };

    var t_backlogTotal = {
      x: dateKPI_data,
      y: backlogTotal_data,
      name: 'backlogTotal',
      text: dateKPI_data,
      fill: 'tozeroy',
      type: 'scatter',
      mode: 'none',
    };


    var t_cdeMRSAS = {
      x: dateKPI_data,
      y: cdeMRSAS_data,
      name: 'cdeMRSAS',
      text: dateKPI_data,
      type: 'line'
    };


    var datasales = [ t_backlogMRSAS,t_backlogTotal, t_cdeMRSAS ];

    var config = {responsive: true}


    Plotly.newPlot('sales', datasales, layoutubrMRSAS, config);

  });

  $("#tab_testingProd").click(function(e) {

    //clear variable
    $('#table_KPI').find('tr.chartTR').eq(0).find('input').each( function (i) {
      window[$(this).attr('name') + '_data']= [];
    });

    //pour chaque ligne du tableau eprouvettes
    $('#table_KPI').find('tr.chartTR').each( function (i) {
      $(this).find('input').each( function (i) {
        if (!window[$(this).attr('name') + '_data']) {
          window[$(this).attr('name') + '_data']= [];
        }
        window[$(this).attr('name') + '_data'].push($(this).val()); //on crée une variable array par element
      });
    });

    var layoutubrMRSAS = {
      title:'Testing Production',
      autosize: true,
      barmode: 'stack',
      xaxis: {
        title: 'Date (Y-mm)'
      },
      yaxis: {title: '€'},
      yaxis2: {
        title: '%',
        titlefont: {color: 'rgb(148, 103, 189)'},
        tickfont: {color: 'rgb(148, 103, 189)'},
        overlaying: 'y',
        side: 'right'
      }
    };


    var t_obj_prodMRSAS = {
      x: dateKPI_data,
      y: obj_prodMRSAS_data,
      name: 'obj_prodMRSAS',
      text: dateKPI_data,
      fill: 'tozeroy',
      type: 'scatter',
      mode: 'none',
    };

    var t_c_prodMRSAS = {
      x: dateKPI_data,
      y: c_prodMRSAS_data,
      name: 'prodMRSAS',
      text: c_prodMRSAS_data,
      marker: {color: 'blue'},
      type: 'bar'
    };

    var t_c_prodMRSAS_y = {
      x: dateKPI_data,
      y: c_prodMRSAS_y_data,
      name: 'prodMRSAS N-1',
      text: dateKPI_data,
      type: 'line'
    };

    var t_prod_MRSAS_ratio = {
      x: dateKPI_data,
      y: prod_MRSAS_ratio_data,
      yaxis: 'y2',
      name: '% ProdMRSAS N-1',
      text: dateKPI_data,
      type: 'scatter',
      mode: 'markers'
    };


    var datatestingProd = [ t_obj_prodMRSAS, t_c_prodMRSAS, t_c_prodMRSAS_y, t_prod_MRSAS_ratio ];

    var config = {responsive: true}


    Plotly.newPlot('testingProd', datatestingProd, layoutubrMRSAS, config);

  });



});




/*
  var layoutubrMRSAS = {
    title:'MRSAS Testing - Production',
    autosize: true,
    barmode: 'stack',
    xaxis: {
      title: 'Date (Y-mm)'
    },
    yaxis: {title: '€'},
    showlegend: true,
    legend: {"orientation": "h", x: 0.4, y: 1.2}
*/
