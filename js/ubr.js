/* Formatting function for row details - modify as you need */
function format ( d ) {
  // `d` is the original data object for the row
  return '<div class="row">'+
  '<div class="col-md-6 col-md-offset-3">'+
  '<table class="table table-condensed table-bordered dataTable" cellspacing="0" width="100%" style="background:rgb(68, 84, 106);">'+
  '<tr>'+
  '<td style="font-weight:bold;">Job '+d.info_jobs.job+'</td>'+
  '<td style="font-weight:bold;">'+$.datepicker.formatDate('yy M', new Date(d.ubrold.date_UBR))+'</td>'+
  '<td style="font-weight:bold;">'+$.datepicker.formatDate('yy M', new Date(d.ubr.date_UBR))+'</td>'+
  '<td style="font-weight:bold;">Delta</td>'+
  '</tr>'+
  '<tr>'+
  '<td>MRSAS</td>'+
  '<td>'+d.ubrold.ubrMRSAS+'</td>'+
  '<td>'+d.ubr.ubrMRSAS+'</td>'+
  '<td>'+(d.ubr.ubrMRSAS-d.ubrold.ubrMRSAS)+'</td>'+
  '</tr>'+
  '<tr>'+
  '<td>SubC</td>'+
  '<td>'+d.ubrold.ubrSubC+'</td>'+
  '<td>'+d.ubr.ubrSubC+'</td>'+
  '<td>'+(d.ubr.ubrSubC-d.ubrold.ubrSubC)+'</td>'+
  '</tr>'+
  '<tr>'+
  '<td>TOTAL</td>'+
  '<td>'+(parseFloat(d.ubrold.ubrMRSAS)+parseFloat(d.ubrold.ubrSubC)).toFixed(2)+'</td>'+
  '<td>'+(parseFloat(d.ubr.ubrMRSAS)+parseFloat(d.ubr.ubrSubC)).toFixed(2)+'</td>'+
  '<td>'+(parseFloat(d.ubr.ubrMRSAS)+parseFloat(d.ubr.ubrSubC)-parseFloat(d.ubrold.ubrMRSAS)+parseFloat(d.ubrold.ubrSubC))+'</td>'+
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
      url : "controller/editor-ubr.php",
      type: "POST",
      data: {"dateStartUBR" : $('#dateStartUBR').text()}
    },
    table: "#table_ubr",
    fields: [
      { label: "date_UBR (please write last day of a month)", name: "ubr.date_UBR" , type:  'datetime' },
      { label: "date_creation", name: "ubr.date_creation" , type:  'datetime',   def:   function () { return new Date(); } },
      { label: "Info_Job", name: "info_jobs.job"},
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
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-ubr.php",
      type: "POST",
      data: {"dateStartUBR" : $('#dateStartUBR').text()}
    },
    order: [[0,"desc"],[2,"asc"]],
    columns: [
      { data: "ubr.date_UBR",
      render: function ( data, type, row ) {
        return ($.datepicker.formatDate('yy mm - MM', new Date(data)));

      } },
      { data: "ubr.date_creation"  },
      { data: "info_jobs.job",
      render: function ( data, type, row ) {
        return '<a href="index.php?page=invoiceJob&id_infojob='+data+'">'+data+'</a>';
      } },
      { data: "ubr.ubrMRSAS",
      className: "sum",
      render: function ( data, type, row ) {
        if (data>0) {
          return '$'+data.replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €';
        }
        else {
          return '';
        }
      }  },
      { data: "ubr.ubrSubC",
      className: "sum",
      render: function ( data, type, row ) {
        if (data>0) {
          return '$'+data.replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €';
        }
        else {
          return '';
        }
      }  },
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
    scrollCollapse: true,
    paging: false,
    info: true,
    select: {
      selector:'td:not(:first-child)',
      style:    'os',
      blurable: true
    },
    buttons: [
      { extend: "create", editor: editor },
      { extend: "edit",   editor: editor },
      { extend: "remove", editor: editor },
      { text: "Accounting File",
      action: function() {
        location.assign("controller/createInvoicablePayables-controller.php?dateStart="+$('#dateStartUBR').text());
      } },
      { text: "Payables",
      action: function() {
        location.assign("index.php?page=payables");
      } },
      { text: "Invoices",
      action: function() {
        location.assign("index.php?page=invoices");
      } },
      { text: "Backlog",
      action: function() {
        location.assign("index.php?page=backlog");
      } }
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
    },
    "columnDefs": [
      { "visible": false, "targets": 0 }
    ],
    "drawCallback": function ( settings ) {
      var api = this.api();
      var rows = api.rows( {page:'current'} ).nodes();
      var last=null;

      api.column(0, {page:'current'} ).data().each( function ( group, i ) {
        if ( last !== group ) {
          $(rows).eq( i ).before(
            '<tr class="group"><td colspan="6" style="background-color:black;">'+($.datepicker.formatDate('yy mm - MM', new Date(group)))+'</td></tr>'
          );

          last = group;
        }
      } );
    }
  } );

  // Order by the grouping
  $('#table_ubr tbody').on( 'click', 'tr.group', function () {
    var currentOrder = table.order()[0];
    if ( currentOrder[0] === 0 && currentOrder[1] === 'asc' ) {
      table.order( [ 0, 'desc' ] ).draw();
    }
    else {
      table.order( [ 0, 'asc' ] ).draw();
    }
  } );

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
    table
    .column( $(this).data('index') )
    .search( this.value )
    .draw();
  } );


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
