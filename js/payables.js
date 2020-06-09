
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-payables.php",
      type: "POST"
    },
    table: "#table_payables",
    fields: [
      { label: "supplier", name: "payables.supplier"  },
      { label: "Description", name: "payables.payable"  },
      { label: "type", name: "payables.id_payable_list", type: "select" },
      { label: "capitalize", name: "payables.capitalize"  },
      { label: "date_due", name: "payables.date_due", type:  'datetime'},
      { label: "date_invoice", name: "payables.date_invoice", type:  'datetime'},
      { label: "postedDate", name: "payables.postedDate", type:  'datetime'},
      { label: "invoice", name: "payables.invoice"  },
      { label: "job", name: "payables.job"  },
      { label: "USD", name: "payables.USD"  },
      { label: "taux", name: "payables.taux"  },
      { label: "HT", name: "payables.HT"  },
      { label: "TVA", name: "payables.TVA"  },
      { label: "date_payable", name: "payables.date_payable", type:  'datetime'},
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_payables tfoot th').each( function (i) {
    var title = $('#table_payables thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );




  var table = $('#table_payables').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-payables.php",
      type: "POST",
      data: {"dateStartPayable" : $('#dateStartPayable').text()}
    },
    order: [ 7, "desc" ],
    columns: [
      { data: "payables.id_payable","visible": false  },
      { data: "payables.invoice"  },
      { data: "payables.supplier"  },
      { data: "payables.payable"  },
      { data: "payable_lists.payable_list" },
      { data: "payables.capitalize"  },
      { data: "payables.postedDate"  },
      { data: "payables.date_invoice"  },
      { data: "payables.date_due"  },
      { data: "payables.job"  },
      { data: "payables.USD",
      className: "sum"  },
      { data: "payables.taux"  },
      { data: "payables.HT",
      className: "sum"  },
      { data: "payables.TVA",
      className: "sum"  },
      { data: function ( row, type, val, meta ) {
        dollar=(row.payables.USD*row.payables.taux).toFixed(2);
        euro=(row.payables.HT*1+row.payables.TVA*1).toFixed(2);
        //selon si c'est en dollar ou euro, et si le champ TTC est rempli, on compare TTC et le calcul
        if (row.payables.USD > 0) {
          return (row.payables.USD*row.payables.taux).toFixed(2);
        }
        else {
          return (row.payables.HT*1+row.payables.TVA*1).toFixed(2);
        }
      },
      className: "sum"
    },
    { data: "payables.date_payable"  },
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
    { extend: "remove", editor: editor }
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
      $(this.header()).html('&Sigma; '+sum.toFixed(2));
    });
  },
}
);




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
    $('#table_payables').DataTable().draw();
  });
