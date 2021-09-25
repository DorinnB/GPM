
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
      { label: "Date", name: "purchaserequests.purchaserequest_date", type:  'datetime', def:   function () { return new Date(); }  },
      { label: "Supplier", name: "purchaserequests.supplier"  },
      { label: "Description", name: "purchaserequests.description"},
      { label: "Job", name: "purchaserequests.job"},
      { label: "USD HT", name: "purchaserequests.usd"},
      { label: "EURO HT", name: "purchaserequests.euro"},
      { label: "Comments", name: "purchaserequests.comments"}
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
      { label: "Do you validate this POR ?", name: "purchaserequests.id_validator",
      type:  'radio',
      options: [
        { label: 'Accept',  value: iduser },
        { label: 'Undecided', value: 0 },
        { label: 'Refused', value: -iduser }
      ]}
    ]
  } );

  editorPo = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-purchases.php",
      type: "POST",
      data: {"dateStartPurchase" : $('#dateStartPurchase').text()}
    },
    table: "#table_purchases",
    fields: [
      { label: "PO Number", name: "purchases.generate",
      type:  'radio',
      options: [
        { label: 'Generate a PO Number',  value: 1 }
      ]},
      { label: "Do you approve this reception", name: "purchases.id_receipt",
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
    dom: "Brtip",
    ajax: {
      url : "controller/editor-purchases.php",
      type: "POST",
      data: {"dateStartPurchase" : $('#dateStartPurchase').text()}
    },
    order: [ 0, "desc" ],
    columns: [
      { data: "purchaserequests.id_purchaserequest",
      render: function ( data, type, row ) {
        return 'POR'+data
      } },
      { data: "purchaserequests.purchaserequest_date",
      render: function ( data, type, row ) {
        return ($.datepicker.formatDate('yy-mm-dd', new Date(data)));
      } },
      { data: "techniciens.technicien"  },
      { data: "purchaserequests.supplier" },
      { data: "purchaserequests.description"  },
      { data: "purchaserequests.job" },
      { data: "purchaserequests.usd", className: "sumDol",
      render: function ( data, type, row ) {
        return (data>0 ? "$"+data.replace(/(\d)(?=(\d{3})+\b)/g,'$1 '): "" );
      }  },
      { data: "purchaserequests.euro", className: "sumEur",
      render: function ( data, type, row ) {
        return (data>0 ? data.replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+" €" : "" );
      }  },
      { data: "purchaserequests.comments"  },
      { data: "purchaserequests.id_validator",
      render: function ( data, type, row) {
        if (row.t2.technicien) {
          return (data<0?'-':'')+row.t2.technicien;
        }
        else {
          return '';
        }
      }},
      { data: "purchases.generate",
      render: function ( data, type, row ) {
        return row.purchases.id_purchase;
      } },
      { data: "purchases.id_receipt",
      render: function ( data, type, row) {
        if (row.t3.technicien) {
          return (data<0?'-':'')+row.t3.technicien;
        }
        else {
          return '';
        }
      }},
      { data: "payables.invoice",
      render: function ( data, type, row ) {
        if (data) {
if (row['payables']['HT']) {
  payable=" ("+row['payables']['HT']+"€)";
}
else if ((row['payables']['USD'])) {

    payable=" ($"+row['payables']['USD']+")";
}
else {
  payable='';
}
          return 'Yes' + payable;

        }
        else {
          return '';
        }
        return row.payables.invoice;
      } }
    ],
    columnDefs: [
      {
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
        if (data.purchaserequests.id_validator < 0) {
          $(row).addClass('refused')
        }
        else if ( data.purchaserequests.id_receipt  > 0 ) {
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
        blurable: true
      },
      buttons: [
        { extend: "create", editor: editor },
        { extend: "edit",   editor: editor }
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


  table
  .buttons()
  .container()
  .appendTo( '#btn' );


  $('#container').css('display', 'block');
  table.columns.adjust().draw();


  // Activate the bubble editor on click of a table cell
  $('#table_purchases').on( 'click', 'tbody td:not(:first-child)', function (e) {
    var index = $(this).index();
    if (index == 9) {
      editorValid.bubble( this );
    }
    else if (index == 10 || index == 11) {
      editorPo.bubble( this );
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

//On retracte le tbl des jobs, et une fois retracté, on repurchasequeste le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    $('#table_purchases').DataTable().draw();
  });
