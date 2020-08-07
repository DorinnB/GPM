
var editor; // use a global for the submit and return data rendering in the examples


$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-invoices.php",
      type: "POST",
      data: {"dateStartInvoice" : $('#dateStartInvoice').text()}
    },
    table: "#table_invoices",
    fields: [
      { label: "invoice date", name: "invoices.inv_date"},
      { label: "MRSAS", name: "invoices.inv_mrsas"},
      { label: "SubC", name: "invoices.inv_subc"},
      { label: "TVA", name: "invoices.inv_tva"},
      { label: "USDRate if applicable", name: "invoices.USDRate"},
      { label: "Payement date", name: "invoices.datepayement", type:  'datetime'}
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_invoices tfoot th').each( function (i) {
    var title = $('#table_invoices thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_invoices').DataTable( {
    dom: "Brtip",
    ajax: {
      url : "controller/editor-invoices.php",
      type: "POST",
      data: {"dateStartInvoice" : $('#dateStartInvoice').text()}
    },
    order: [ 7, "desc" ],
    columns: [
      { data: 'info_jobs.invoice_type',
      render: function ( data, type, row ) {
        if (data==2) {
          return "INV.";
        }
        else {
          return "Part.";
        }
      }
    },
    { data: "info_jobs.customer"  },
    { data: "info_jobs.job",
    render: function ( data, type, row ) {
      return '<a href="index.php?page=invoiceJob&job='+data+'">'+data+'</a>';
    }  },
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
    className: "sumDol",
    render: function ( data, type, row ) {
      return (row.info_jobs.invoice_currency==1 ? '$'+row.invoices.inv_subc.replace(/(\d)(?=(\d{3})+\b)/g,'$1 ') : " " );
    }  },
    { data: "invoices.inv_mrsas",
    className: "sumDol",
    render: function ( data, type, row ) {
      return (row.info_jobs.invoice_currency==1 ? '$'+row.invoices.inv_mrsas : " " ).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ');
    } },
    { data: null,
      className: "sumDol",
      render: function ( data, type, row ) {
        return (row.info_jobs.invoice_currency==1 ? '$'+(parseFloat(row.invoices.inv_subc)+parseFloat(row.invoices.inv_mrsas)).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ') : " " );
      }
    },
    { data: "invoices.inv_tva",
    className: "sumDol",
    render: function ( data, type, row ) {
      return (row.info_jobs.invoice_currency==1 ? '$'+row.invoices.inv_tva.replace(/(\d)(?=(\d{3})+\b)/g,'$1 ') : " " );
    } },
    { data: null,
      className: "sumDol",
      render: function ( data, type, row ) {
        return (row.info_jobs.invoice_currency==1 ? '$'+(parseFloat(row.invoices.inv_subc)+parseFloat(row.invoices.inv_mrsas)+parseFloat(row.invoices.inv_tva)).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ') : " " );
      }
    },
    { data: "invoices.USDRate",
    render: function ( data, type, row ) {
      return (row.info_jobs.invoice_currency==1 ? data : " " );
    }      },
    { data: "invoices.inv_subc",
    className: "sumEur",
    render: function ( data, type, row ) {
      return (row.info_jobs.invoice_currency==0 ? row.invoices.inv_subc.replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €' : (row.invoices.inv_subc*row.invoices.USDRate).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €' );
    }  },
    { data: "invoices.inv_mrsas",
    className: "sumEur",
    render: function ( data, type, row ) {
      return (row.info_jobs.invoice_currency==0 ? row.invoices.inv_mrsas.replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €' : (row.invoices.inv_mrsas*row.invoices.USDRate).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €' );
    }},
    { data: null,
      className: "sumEur",
      render: function ( data, type, row ) {
        return (row.info_jobs.invoice_currency==0 ? (parseFloat(row.invoices.inv_subc)+parseFloat(row.invoices.inv_mrsas)).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €' :  ((parseFloat(row.invoices.inv_subc)+parseFloat(row.invoices.inv_mrsas))*row.invoices.USDRate).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €' );
      }
    },
    { data: "invoices.inv_tva",
    className: "sumEur",
    render: function ( data, type, row ) {
      return (row.info_jobs.invoice_currency==0 ? row.invoices.inv_tva.replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €' : (row.invoices.inv_tva*row.invoices.USDRate).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €' );
    } },
    { data: null,
      className: "sumEur",
      render: function ( data, type, row ) {
        return (row.info_jobs.invoice_currency==0 ? (parseFloat(row.invoices.inv_subc)+parseFloat(row.invoices.inv_mrsas)+parseFloat(row.invoices.inv_tva)).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €' : ((parseFloat(row.invoices.inv_subc)+parseFloat(row.invoices.inv_mrsas)+parseFloat(row.invoices.inv_tva))*row.invoices.USDRate).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €' ) ;
      }
    },
    { data: "invoices.datepayement"  }
  ],
  scrollY: '65vh',
  scrollX : true,
  scrollCollapse: true,
  paging: false,
  info: true,
  select: {
    style:    'os',
    blurable: true
  },
  buttons: [
    { extend: "edit",   editor: editor },
    { extend: "remove", editor: editor }
  ],
  headerCallback: function ( row, data, start, end, display ) {
    var api = this.api();

    api.columns('.sumDol', { page: 'current' }).every(function () {
      var sum = api
      .cells( null, this.index(), { page: 'current'} )
      .render('display')
      .reduce(function (a, b) {
        var x = parseFloat(a) || 0;
        var y = parseFloat(b.replace(/[$ €]+/g, '')) || 0;
        return x + y;
      }, 0);
      $(this.header()).html('$'+sum.toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 '));
    });
    api.columns('.sumEur', { page: 'current' }).every(function () {
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
  columnDefs: [ {
    targets: [14,15,16,17,18],
    createdCell: function (td, cellData, rowData, row, col) {
      if ( rowData.info_jobs.invoice_currency == 1 ) {
        $(td).css('color', 'blue')
      }
    }
  } ]
});


table
.buttons()
.container()
.appendTo( '#btn' );



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

//On retracte le tbl des jobs, et une fois retracté, on reinvoicee le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    $('#table_invoices').DataTable().draw();
  });
