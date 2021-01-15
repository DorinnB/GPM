
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
      { label: "backlogTOTAL", name: "kpi.backlogTOTAL"},
      { label: "cdeMRSAS", name: "kpi.cdeMRSAS"},
        { label: "obj_prodMRSAS", name: "kpi.obj_prodMRSAS"},
        { label: "obj_invMRSAS", name: "kpi.obj_invMRSAS"}
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
      { data: "ubrTOTAL" },
      { data: "invMRSAS" },
      { data: "invTOTAL" },
      { data: "payable_A" },
      { data: "payable_B" },
      { data: "payable_C" },
      { data: "payable_D" },
      { data: "payable_E" },
      { data: "payable_F" },
      { data: "payable_G" },
      { data: "payable_TOTAL" },
      { data: "workingDay" },
      { data: "sickDay" },
      { data: "kpi.backlogMRSAS" },
      { data: "kpi.backlogTOTAL" },
      { data: "kpi.cdeMRSAS" },
      { data: "prodMRSAS" },
      { data: "prodTOTAL" },
      { data: "c_prodMRSAS" },
      { data: "c_prodTOTAL" },
      { data: "c_invMRSAS" },
      { data: "c_invTOTAL" },
      { data: "ratioProd" },
      { data: "nbTest" },
      { data: "occupancy" },
      { data: "occupancyExtented" },

      { data: "kpi.obj_prodMRSAS" },
      { data: "kpi.obj_invMRSAS" },
      { data: "y_prodMRSAS" },
      { data: "y_invMRSAS" }
    ],
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



  $("#graphs1Toggle").click(function(e) {

    //Graph Dimensionel 1
    if ($('.ubrMRSAS')[0]) {


      chartFile=  [];
      ubrMRSAS_Y=  [];
      invMRSAS_Y=  [];
      invMRSAS_base=  [];
      prodMRSAS_Y=  [];
      prodMRSAS_OLD_Y=  [];
      //pour chaque ligne du tableau eprouvettes
      $('#table_KPI').find('tr.chartTR').each( function (i) {
        //on cherche ceux ou il y a un dimensionnel (en otant les espaces)
        chartFile.push($(this).find('.key').html().replace(/ /g,''));
        ubrMRSAS_Y.push($(this).find('.ubrMRSAS').data('val'));
        invMRSAS_Y.push($(this).find('.invMRSAS').data('val'));
        invMRSAS_base.push($(this).find('.ubrMRSAS').data('val')<=0?0:$(this).find('.ubrMRSAS').data('val'));
        prodMRSAS_Y.push($(this).find('.prodMRSAS').data('val'));
        prodMRSAS_OLD_Y.push($(this).find('.prodMRSAS').data('y'));
      });

      var traceubrMRSAS = {
        x: chartFile,
        y: ubrMRSAS_Y,
        base: 0,
        name: 'var UBR MRSAS',
        text: chartFile,
        marker: {color: 'lightgreen'},
        type: 'bar'
      };

      var traceinvMRSAS = {
        x: chartFile,
        y: invMRSAS_Y,
        base: invMRSAS_base,
        name: 'INV MRSAS',
        text: chartFile,
        marker: {color: 'lightblue'},
        type: 'bar'
      };

      var traceprodMRSAS = {
        x: chartFile,
        y: prodMRSAS_Y,
        name: 'Production MRSAS',
        text: chartFile,
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
        x: chartFile,
        y: prodMRSAS_OLD_Y,
        base: 0,
        name: 'PROD N-1',
        text: chartFile,
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

      if ($( "#ubrMRSAS" ).length == 0) {
        $( "#chart" ).append( "<div class='item'><div id='ubrMRSAS' class='chart'></div></div>" );
      }

      Plotly.newPlot('ubrMRSAS', dataubrMRSAS, layoutubrMRSAS, config);
    }
  });
});
