/* Formatting function for row details - modify as you need */
function format ( d ) {
  // `d` is the original data object for the row
  return '<div class="row">'+
  '<div class="col-md-6 col-md-offset-3">'+
  '<table class="table table-condensed table-bordered dataTable" cellspacing="0" width="100%">'+
  '<tr>'+
  '<td style="font-weight:bold;">Job '+d.ubr.job+'</td>'+
  '<td style="font-weight:bold;">'+$.datepicker.formatDate('yy M', new Date(d.ubrold.date_UBR))+'</td>'+
  '<td style="font-weight:bold;">'+$.datepicker.formatDate('yy M', new Date(d.ubr.date_UBR))+'</td>'+
  '<td style="font-weight:bold;">Delta</td>'+
  '</tr>'+
  '<tr>'+
  '<td>MRSAS</td>'+
  '<td>'+(isNaN(d.ubrold.ubrMRSAS)?d.ubrold.ubrMRSAS.toFixed(2):d.ubrold.ubrMRSAS)+'</td>'+
  '<td>'+(isNaN(d.ubr.ubrMRSAS)?d.ubr.ubrMRSAS.toFixed(2):d.ubr.ubrMRSAS)+'</td>'+
  '<td>'+(isNaN(d.ubr.ubrMRSAS-d.ubrold.ubrMRSAS)?(d.ubr.ubrMRSAS-d.ubrold.ubrMRSAS).toFixed(2):(d.ubr.ubrMRSAS-d.ubrold.ubrMRSAS))+'</td>'+
  '</tr>'+
  '<tr>'+
  '<td>SubC</td>'+
  '<td>'+(isNaN(d.ubrold.ubrSubC)?d.ubrold.ubrSubC.toFixed(2):d.ubrold.ubrSubC)+'</td>'+
  '<td>'+(isNaN(d.ubr.ubrSubC)?d.ubr.ubrSubC.toFixed(2):d.ubr.ubrSubC)+'</td>'+
  '<td>'+(isNaN(d.ubr.ubrSubC-d.ubrold.ubrSubC)?(d.ubr.ubrSubC-d.ubrold.ubrSubC).toFixed(2):(d.ubr.ubrSubC-d.ubrold.ubrSubC))+'</td>'+
  '</tr>'+
  '<tr>'+
  '<td>TOTAL</td>'+
  '<td>'+(parseFloat(d.ubrold.ubrMRSAS)+parseFloat(d.ubrold.ubrSubC)).toFixed(2)+'</td>'+
  '<td>'+(parseFloat(d.ubr.ubrMRSAS)+parseFloat(d.ubr.ubrSubC)).toFixed(2)+'</td>'+
  '<td>'+(parseFloat(d.ubr.ubrMRSAS)+parseFloat(d.ubr.ubrSubC)-parseFloat(d.ubrold.ubrMRSAS)+parseFloat(d.ubrold.ubrSubC)).toFixed(2)+'</td>'+
  '</tr>'+
  '</tbody>'+
  '</table>'+
  '</div>'+
  '</div>';
}


var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-monthlyStatement.php",
      type: "POST",
      data: {"dateStartMonthlyStatement" : $('#dateStartMonthlyStatement').text()}
    },
    table: "#table_ubr",
    fields: [
      { label: "date_UBR (please write last day of a month)", name: "ubr.date_UBR" , type:  'datetime' },
      { label: "Insertion date (today)", name: "ubr.date_creation" , type:  'datetime',   def:   function () { return new Date(); } },
      { label: "Job", name: "ubr.job"},
      { label: "UBR MRSAS", name: "ubr.ubrMRSAS"  },
      { label: "UBR SubC", name: "ubr.ubrSubC"}
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_ubr tfoot th').each( function (i) {
    var title = $('#table_ubr thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_ubr').DataTable( {
    dom: "Brtip",
    ajax: {
      url : "controller/editor-monthlyStatement.php",
      type: "POST",
      data: {"dateStartMonthlyStatement" : $('#dateStartMonthlyStatement').text()}
    },
    order: [1,"desc"],
    columns: [
      { data: "info_jobs.customer"  },
      { data: "info_jobs.job",
      render: function ( data, type, row ) {
        return '<a href="index.php?page=invoiceJob&job='+data+'">'+data+'</a>';
      } },
      { data: "info_jobs.datecreation",
      render: function ( data, type, row ) {
        return ($.datepicker.formatDate('yy-mm', new Date(data)));
      } },
      { data: null,
        render: function ( data, type, row ) {

          dateEndMonth=$('#dateStartUBR').text()
          var date = new Date(dateEndMonth);
          var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
          var lastDay = new Date(date.getFullYear(), date.getMonth()+1, 0);


          if (data.info_jobs.invoice_type==2) {
            return 'INV';
          }
          else if (!isNaN(parseFloat(data.ubr.ubrMRSAS)+parseFloat(data.ubr.ubrSubC)) || data.info_jobs.invoice_type==1) {
            return 'UBR';
          }
          else {
            return 'Error'
          }
        }
      },
      { data: "info_jobs.invoice_date"},
      { data: null,
        render: function ( data, type, row ) {

          inv_mrsas=parseFloat((data.invoices.inv_mrsas>0) ? (data.invoices.inv_mrsas) : 0);
          inv_subc=parseFloat((data.invoices.inv_subc>0) ? (data.invoices.inv_subc) : 0);
          usdrate=parseFloat((data.invoices.USDRate>0) ? (data.invoices.USDRate) : 1);
          inv_TVA=parseFloat((data.invoices.inv_TVA>0) ? (data.invoices.inv_TVA) : 0);

          total=(inv_mrsas+inv_subc+inv_TVA)*usdrate;
          total=(inv_mrsas+inv_subc)*usdrate;

          return total==0?"": total.toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €';

        }
      },
      { data: null,
        className: "sum",
        render: function ( data, type, row ) {
          return (parseFloat(data.ubrold.ubrMRSAS)+parseFloat(data.ubrold.ubrSubC)).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €';
        }
      },
      { data: null,
        className: "sum",
        render: function ( data, type, row ) {
          return (parseFloat(data.ubr.ubrMRSAS)+parseFloat(data.ubr.ubrSubC)).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €';
        }
      },
      {
        className:      'details-control',
        orderable:      false,
        data:           null,
        defaultContent: ''
      }
    ],
    scrollY: '65vh',
    scrollX : true,
    scrollCollapse: true,
    paging: false,
    info: true,
    select: {
      selector:'td:not(:first-child)',
      style:    'os',
      blurable: true
    },    buttons: [
      'copy', 'excel', 'pdf'
    ],
    headerCallback: function ( row, data, start, end, display ) {
      var api = this.api();

      api.columns('.sum', { page: 'current' }).every(function () {
        var sum = api
        .cells( null, this.index(), { page: 'current'} )
        .render('display')
        .reduce(function (a, b) {
          var x = parseFloat(a) || 0;
          var y = parseFloat(b.replace(/[$ €]+/g, '')) || 0;
          return x + y;
        }, 0);
        $(this.header()).html(sum.toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €');
      });
    }
  } );


  // Collapse / Expand Click Groups
  $('tbody').on( 'click', 'tr.group', function () {
    var rowsCollapse = $(this).nextUntil('.group');
    $(rowsCollapse).toggleClass('hidden');
  });



  table
  .buttons()
  .container()
  .appendTo( '#btn' );


  // Add event listener for opening and closing details
  $('#table_ubr tbody').on('click', 'td.details-control', function () {
    var tr = $(this).closest('tr');
    var row = table.row( tr );

    if ( row.child.isShown() ) {
      // This row is already open - close it
      row.child.hide();
      tr.removeClass('shown');
    }
    else {
      // Open this row
      row.child( format(row.data()) ).show();
      tr.addClass('shown');
    }
  } );







  $('#container').css('display', 'block');
  table.columns.adjust().draw();

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


  $( "#dateStart" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });
  $( "#dateEnd" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });



} );



//Selon le navigateur utilisé, on detecte le style de transition utilisé
function whichTransitionEvent(){
  var t,
  el = document.createElement("fakeelement");

  var transitions = {
    "transition"      : "transitionend",
    "OTransition"     : "oTransitionEnd",
    "MozTransition"   : "transitionend",
    "WebkitTransition": "webkitTransitionEnd"
  }

  for (t in transitions){
    if (el.style[t] !== undefined){
      return transitions[t];
    }
  }
}

var transitionEvent = whichTransitionEvent();

//On retracte le tbl des jobs, et une fois retracté, on repayablee le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    $('#table_ubr').DataTable().draw();
  });
