
var editor; // use a global for the submit and return data rendering in the examples



$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-purchases.php",
      type: "POST",
      data: {"dateStartPurchase" : $('#dateStartPurchase').text()}
    },
    table: "#table_purchases",
    fields: [
      { label: "Date", name: "purchases.purchase_date", type:  'datetime'  },
      { label: "Supplier", name: "purchases.supplier"  },
      { label: "Description", name: "purchases.description"},
      { label: "Job", name: "purchases.job"},
      { label: "USD HT", name: "purchases.usd"},
      { label: "EURO HT", name: "purchases.euro"},
      { label: "Comments", name: "purchases.comments"}
    ]
  } );

  editorValid = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-purchases.php",
      type: "POST",
      data: {"dateStartPurchase" : $('#dateStartPurchase').text()}
    },
    table: "#table_purchases",
    fields: [

      { label: "Validator", name: "purchases.id_validator",
      type:  'radio',
      options: [
        { label: 'Accept',  value: iduser },
        { label: 'Undecided', value: 0 },
        { label: 'Refused', value: -iduser }
      ]},
      { label: "Receipt", name: "purchases.id_receipt",
      type:  'radio',
      options: [
        { label: 'Conform',  value: iduser },
        { label: 'Undecided', value: 0 },
        { label: 'Unconform', value: -iduser }
      ]}
    ]
  } );
  // Setup - add a text input to each footer cell
  $('#table_purchases tfoot th').each( function (i) {
    var title = $('#table_purchases thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_purchases').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-purchases.php",
      type: "POST",
      data: {"dateStartPurchase" : $('#dateStartPurchase').text()}
    },
    order: [ 1, "desc" ],
    columns: [

      { data: "purchases.id_purchase",
    render: function ( data, type, row ) {
      return 'POR'+data
    } },
      { data: "purchases.purchase_date",
      render: function ( data, type, row ) {
        return ($.datepicker.formatDate('yy-mm-dd', new Date(data)));
      } },
      { data: "techniciens.technicien"  },
      { data: "purchases.supplier" },
      { data: "purchases.description"  },
      { data: "purchases.job" },
      { data: "purchases.usd", className: "sumDol",
      render: function ( data, type, row ) {
        return (data>0 ? "$"+data.replace(/(\d)(?=(\d{3})+\b)/g,'$1 '): " " );
      }  },
      { data: "purchases.euro", className: "sumEur",
      render: function ( data, type, row ) {
        return (data>0 ? data.replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+" €" : " " );
      }  },
      { data: "purchases.comments"  },
      { data: "purchases.id_validator",
      render: function ( data, type, row) {
        if (row.t2.technicien) {
          return (data<0?'-':'')+row.t2.technicien;
        }
        else {
          return '';
        }
      }},
      { data: "purchases.purchase_number"  },
      { data: "purchases.id_receipt",
      render: function ( data, type, row) {
        if (row.t3.technicien) {
          return (data<0?'-':'')+row.t3.technicien;
        }
        else {
          return '';
        }
      }},
    ],
    columnDefs: [ {
      targets: [9, 11],
      createdCell: function (td, cellData, rowData, row, col) {
        if ( cellData < 0 ) {
          $(td).addClass('refused')
        }
        else if ( cellData > 0 ) {
          $(td).addClass('validated')
        }
      }
    } ],
    createdRow: function( row, data, dataIndex ) {
      if (data.purchases.id_validator < 0) {
        $(row).addClass('refused')
      }
      else if ( data.purchases.id_receipt  > 0 ) {
        $(row).addClass('validated')
      }
    },

    scrollY: '65vh',
    scrollCollapse: true,
    paging: false,
    info: true,
    fixedColumns:   {leftColumns: 3},
    select: {
      style:    'os',
      selector: ['td:first-child']
    },
    buttons: [
      { extend: "create", editor: editor },
      { extend: "edit",   editor: editor },
      { extend: "remove", editor: editor },
      { text: "Payables",
      action: function() {
        location.assign("index.php?page=payables");
      } },
      { text: "UBR",
      action: function() {
        location.assign("index.php?page=ubr");
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
  }
);




$('#container').css('display', 'block');
table.columns.adjust().draw();


// Activate the bubble editor on click of a table cell
$('#table_purchases').on( 'click', 'tbody td:not(:first-child)', function (e) {
  editorValid.bubble( this );
} );

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

//On retracte le tbl des jobs, et une fois retracté, on repurchasee le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    $('#table_purchases').DataTable().draw();
  });
