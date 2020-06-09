
var editor; // use a global for the submit and return data rendering in the examples


$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-invoices.php",
      type: "POST"
    },
    table: "#table_invoices",
    fields: [
      { label: "invoices.datepayement", name: "invoices.datepayement", type:  'datetime'}
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_invoices tfoot th').each( function (i) {
    var title = $('#table_invoices thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_invoices').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-invoices.php",
      type: "POST",
      data: {"dateStartInvoice" : $('#dateStartInvoice').text()}
    },
    order: [ 7, "desc" ],
    columns: [
      { data: null,
        render: function ( data, type, row ) {
          return "*";
        }
      },
      { data: "info_jobs.customer"  },
      { data: "info_jobs.job"  },
      { data: "info_jobs.order_val"  },
      { data: "info_jobs.order_est"  },
      { data: "invoices.inv_number"  },
      { data: "invoices.inv_date"  },
      { data: null,
        render: function ( data, type, row ) {
          dateDue = new Date(data.invoices.inv_date);
          dateDue.setDate(dateDue.getDate()+30);
          return $.datepicker.formatDate('yy mm dd', dateDue);
        }
      },
      { data: "invoices.inv_subc",
        className: "sum",
        render: function ( data, type, row ) {
          return (row.info_jobs.invoice_currency==1 ? row.invoices.inv_subc : " " );
        }
      },
      { data: "invoices.inv_mrsas",
        className: "sum",
        render: function ( data, type, row ) {
          return (row.info_jobs.invoice_currency==1 ? row.invoices.inv_mrsas : " " );
        }
      },
      { data: null,
        className: "sum",
        render: function ( data, type, row ) {
          return (row.info_jobs.invoice_currency==1 ? (parseFloat(row.invoices.inv_subc)+parseFloat(row.invoices.inv_mrsas)).toFixed(2) : " " );
        }
      },
      { data: "invoices.inv_tva",
        className: "sum",
        render: function ( data, type, row ) {
          return (row.info_jobs.invoice_currency==1 ? row.invoices.inv_tva : " " );
        }
      },
      { data: null,
        className: "sum",
        render: function ( data, type, row ) {
          return (row.info_jobs.invoice_currency==1 ? (parseFloat(row.invoices.inv_subc)+parseFloat(row.invoices.inv_mrsas)+parseFloat(row.invoices.inv_tva)).toFixed(2) : " " );
        }
      },
      { data: "invoices.USDRate",
        render: function ( data, type, row ) {
          return (row.info_jobs.invoice_currency==1 ? data : " " );
        }
      },
      { data: "invoices.inv_subc",
        className: "sum",
        render: function ( data, type, row ) {
          return (row.info_jobs.invoice_currency==0 ? row.invoices.inv_subc : (row.invoices.inv_subc*row.invoices.USDRate).toFixed(2) );
        }
      },
      { data: "invoices.inv_mrsas",
        className: "sum",
        render: function ( data, type, row ) {
          return (row.info_jobs.invoice_currency==0 ? row.invoices.inv_mrsas : (row.invoices.inv_mrsas*row.invoices.USDRate).toFixed(2) );
        }
      },
      { data: null,
        className: "sum",
        render: function ( data, type, row ) {
          return (row.info_jobs.invoice_currency==0 ? (parseFloat(row.invoices.inv_subc)+parseFloat(row.invoices.inv_mrsas)).toFixed(2) :  ((parseFloat(row.invoices.inv_subc)+parseFloat(row.invoices.inv_mrsas))*row.invoices.USDRate).toFixed(2) );
        }
      },
      { data: "invoices.inv_tva",
        className: "sum",
        render: function ( data, type, row ) {
          return (row.info_jobs.invoice_currency==0 ? row.invoices.inv_tva : (row.invoices.inv_tva*row.invoices.USDRate).toFixed(2) );
        }
      },
      { data: null,
        className: "sum",
        render: function ( data, type, row ) {
          return (row.info_jobs.invoice_currency==0 ? (parseFloat(row.invoices.inv_subc)+parseFloat(row.invoices.inv_mrsas)+parseFloat(row.invoices.inv_tva)).toFixed(2) : ((parseFloat(row.invoices.inv_subc)+parseFloat(row.invoices.inv_mrsas)+parseFloat(row.invoices.inv_tva))*row.invoices.USDRate).toFixed(2) ) ;
        }
      },
      { data: "invoices.datepayement"  }
    ],
    scrollY: '65vh',
    scrollCollapse: true,
    paging: false,
    info: true,
    select: {
      style:    'os',
      blurable: true
    },
    buttons: [
      { extend: "create", editor: editor },
      { extend: "edit",   editor: editor },
      { extend: "remove", editor: editor },
      { text: "Accounting File",
      action: function() {
        location.assign("controller/createInvoicablePayables-controller.php?dateStart="+$('#dateStartInvoice').text());
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
          var y = parseFloat(b) || 0;
          return x + y;
        }, 0);
        $(this.header()).html('&Sigma; '+sum.toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 '));
      });
    },
    columnDefs: [ {
    targets: [14,15,16,17,18],
    createdCell: function (td, cellData, rowData, row, col) {
      if ( rowData.info_jobs.invoice_currency == 1 ) {
        $(td).css('color', 'lightgreen')
      }
    }
  } ]
});



  $('#container').css('display', 'block');
  table.columns.adjust().draw();

  // Filter event handler
  $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
    table
    .column( $(this).data('index') )
    .search( this.value )
    .draw();
  } );


console.log(table.column( 9 ).data().sum());

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

//On retracte le tbl des jobs, et une fois retracté, on reinvoicee le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    $('#table_invoices').DataTable().draw();
  });
