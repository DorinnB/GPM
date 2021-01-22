
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
    },
    buttons: [
      'excel'
    ]
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



  table
  .buttons()
  .container()
  .appendTo( '#btn' );




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













  $("#tab_graphs").click(function(e) {

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


    var layout_ubrMRSAS = {
      title:'MRSAS Testing - Production',
      autosize: true,
      barmode: 'stack',
      xaxis: {
        tickformat: '%b-%y'
      },
      yaxis: {title: '€'},
      showlegend: true,
      legend: {"orientation": "h", x: 0, y: 1.2, 'traceorder':'normal'}
    };






    var layout_sales = {
      title:'Sales',
      autosize: true,
      barmode: 'stack',
      xaxis: {
        tickformat: '%b-%y'
      },
      yaxis: {title: '€'},
      yaxis2: {
        title: '%',
        titlefont: {color: 'rgb(148, 103, 189)'},
        tickfont: {color: 'rgb(148, 103, 189)'},
        overlaying: 'y',
        side: 'right'
      },
      showlegend: true,
      legend: {"orientation": "h", x: 0, y: 1.2, 'traceorder':'normal'}
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
      type: 'bar',
      width: 10*(1000*3600*24)
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




    var layout_cde = {
      title:'Carnet de Commande',
      autosize: true,
      barmode: 'stack',
      xaxis: {
        tickformat: '%b-%y'
      },
      yaxis: {title: '€'},
      yaxis2: {
        title: '%',
        titlefont: {color: 'rgb(148, 103, 189)'},
        tickfont: {color: 'rgb(148, 103, 189)'},
        overlaying: 'y',
        side: 'right'
      },
      showlegend: true,
      legend: {"orientation": "h", x: 0, y: 1.2, 'traceorder':'normal'}
    };


    var t_backlogMRSAS = {
      x: dateKPI_data,
      y: backlogMRSAS_data,
      name: 'backlogMRSAS',
      text: dateKPI_data,
      fill: 'tozeroy',
      //fillcolor: 'blue',
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




    var layout_testingProd = {
      title:'Testing Production',
      autosize: true,
      barmode: 'stack',
      xaxis: {
        tickformat: '%b-%y'
      },
      yaxis: {title: '€'},
      yaxis2: {
        title: '%',
        titlefont: {color: 'rgb(148, 103, 189)'},
        tickfont: {color: 'rgb(148, 103, 189)'},
        overlaying: 'y',
        side: 'right'
      },
      showlegend: true,
      legend: {"orientation": "h", x: 0, y: 1.2, 'traceorder':'normal'}
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
      type: 'bar',
      width: 10*(1000*3600*24)
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




    var t_prodMRSASTech = {
      x: dateKPI_data,
      y: prodMRSASTech_data,
      name: 'prodMRSASTech',
      text: dateKPI_data,
      type: 'bar'
    };


    var t_occupancy = {
      x: dateKPI_data,
      y: occupancy_data,
      yaxis: 'y2',
      name: 'occupancy',
      text: dateKPI_data,
      marker: {color: 'orange'},
      mode: 'lines'
    };


    var layout_prodTech = {
      title:'Ratio € Producted Test / Technician Day vs Occupancy Rate',
      autosize: true,
      barmode: 'stack',
      xaxis: {
        tickformat: '%b-%y'
      },
      yaxis: {title: '€'},
      yaxis2: {
        title: '%',
        titlefont: {color: 'rgb(148, 103, 189)'},
        tickfont: {color: 'rgb(148, 103, 189)'},
        overlaying: 'y',
        side: 'right',
        range: [0,100]
      },
      showlegend: true,
      legend: {"orientation": "h", x: 0, y: 1.2, 'traceorder':'normal'}
    };








    var t_test_type_cat_Strain_ET = {
      x: dateKPI_data,
      y: test_type_cat_Strain_ET_data,
      name: 'Strain_ET',
      text: dateKPI_data,
      type: 'bar'
    };

    var t_test_type_cat_Strain_RT = {
      x: dateKPI_data,
      y: test_type_cat_Strain_RT_data,
      name: 'Strain RT',
      text: dateKPI_data,
      type: 'bar'
    };

    var t_test_type_cat_Load_ET = {
      x: dateKPI_data,
      y: test_type_cat_Load_ET_data,
      name: 'Load_ET',
      text: dateKPI_data,
      type: 'bar'
    };

    var t_test_type_cat_Load_RT = {
      x: dateKPI_data,
      y: test_type_cat_Load_RT_data,
      name: 'Load RT',
      text: dateKPI_data,
      type: 'bar'
    };

    var t_test_type_cat_Other_ET = {
      x: dateKPI_data,
      y: test_type_cat_Other_ET_data,
      name: 'Other_ET',
      text: dateKPI_data,
      type: 'bar'
    };

    var t_test_type_cat_Other_RT = {
      x: dateKPI_data,
      y: test_type_cat_Other_RT_data,
      name: 'Other RT',
      text: dateKPI_data,
      type: 'bar'
    };


    var layout_testNumber = {
      title:'Test Type',
      autosize: true,
      barmode: 'stack',
      xaxis: {
        tickformat: '%b-%y'
      },
      yaxis: {title: '€'},
      showlegend: true,
      legend: {"orientation": "h", x: 0, y: 1.2, 'traceorder':'normal'}
    };




    var t_occupancyProd = {
      x: prodMRSAS_data,
      y: occupancy_data,
        text: prodMRSASTech_data,
      mode: 'markers',
      marker: {
        size: prodMRSASTech_data,
        sizeref: 2.0 * Math.max(...prodMRSASTech_data) / (80**2),
        sizemode: 'area'
      },
      name: 'prodMRSAS'
    };

    var layout_occupancyProd = {
      title:'Occupation / Production E - Taille Ratio Prod',
      autosize: true,
      showlegend: true,
      xaxis: {title: 'Production €'},
      yaxis: {title: 'Occupancy %'},
      legend: {"orientation": "h", x: 0, y: 1.2, 'traceorder':'normal'}
    };





    var config = {responsive: true}






    var data_ubrMRSAS = [ traceinvMRSAS, traceubrMRSAS, traceprodMRSAS, traceprodMRSAS_OLD ];
    Plotly.newPlot('ubrMRSAS', data_ubrMRSAS, layout_ubrMRSAS, config);

    var data_sales = [ t_obj_invTotal, t_ubrTotal, t_sales, t_c_sales, t_invTotal_ratio ];
    Plotly.newPlot('sales', data_sales, layout_sales, config);

    var data_cde = [ t_backlogTotal, t_backlogMRSAS, t_cdeMRSAS ];
    Plotly.newPlot('cde', data_cde, layout_cde, config);

    var data_testingProd = [ t_obj_prodMRSAS, t_c_prodMRSAS, t_c_prodMRSAS_y, t_prod_MRSAS_ratio ];
    Plotly.newPlot('testingProd', data_testingProd, layout_testingProd, config);

    var data_prodTech = [ t_prodMRSASTech, t_occupancy ];
    Plotly.newPlot('prodTech', data_prodTech, layout_prodTech, config);

    var data_testNumber = [ t_test_type_cat_Strain_RT, t_test_type_cat_Strain_ET, t_test_type_cat_Load_RT, t_test_type_cat_Load_ET, t_test_type_cat_Other_RT, t_test_type_cat_Other_ET ];
    Plotly.newPlot('testNumber', data_testNumber, layout_testNumber, config);

    var data_occupancyProd = [ t_occupancyProd ];
    Plotly.newPlot('occupancyProd', data_occupancyProd, layout_occupancyProd, config);
  });



























});
