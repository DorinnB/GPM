
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-payables.php",
      type: "POST"
    },
    table: "#table_payables",
    fields: [
      { label: "payable", name: "payables.payable"  },
      { label: "type", name: "payables.id_payable_list", type: "select" },
      { label: "capitalize", name: "payables.capitalize"  },
      { label: "date_due", name: "payables.date_due", type:  'datetime'},
      { label: "date_invoice", name: "payables.date_invoice", type:  'datetime'},
      { label: "invoice", name: "payables.invoice"  },
      { label: "description", name: "payables.description"  },
      { label: "job", name: "payables.job"  },
      { label: "USD", name: "payables.USD"  },
      { label: "taux", name: "payables.taux"  },
      { label: "HT", name: "payables.HT"  },
      { label: "TVA", name: "payables.TVA"  },
      { label: "TTC", name: "payables.TTC"  },
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
      type: "POST"
    },
    order: [[ 0, "asc" ],[5,"asc"]],
    columns: [
      { data: "payables.id_payable","visible": false  },
      { data: "payables.payable"  },
      { data: "payable_lists.payable_list" },
      { data: "payables.capitalize"  },
      { data: "payables.date_due"  },
      { data: "payables.date_invoice"  },
      { data: "payables.invoice"  },
      { data: "payables.description"  },
      { data: "payables.job"  },
      { data: "payables.USD"  },
      { data: "payables.taux"  },
      { data: "payables.HT"  },
      { data: "payables.TVA"  },
      { data: function ( row, type, val, meta ) {
        dollar=(row.payables.USD*row.payables.taux).toFixed(2);
        euro=(row.payables.HT*1+row.payables.TVA*1).toFixed(2);
//selon si c'est en dollar ou euro, et si le champ TTC est rempli, on compare TTC et le calcul
          if (row.payables.USD > 0) {
            if (row.payables.TTC>0 && row.payables.TTC==dollar) {
              return row.payables.TTC;
            }
            else if ((row.payables.TTC>0 && row.payables.TTC!=dollar)) {
              return row.payables.TTC+"*";
            }
            else {
              return (row.payables.USD*row.payables.taux).toFixed(2);
            }
          }
          else {
            if (row.payables.TTC>0 && row.payables.TTC==euro) {
              return row.payables.TTC;
            }
            else if ((row.payables.TTC>0 && row.payables.TTC!=euro)) {
              return row.payables.TTC+"*";
            }
            else {
              return (row.payables.HT*1+row.payables.TVA*1).toFixed(2);
            }

          }
        }
      },
      { data: "payables.date_payable"  },
    ],
    scrollY: '65vh',
    scrollCollapse: true,
    paging: false,
        info:false,
    select: {
      style:    'os',
      blurable: true
    },
    buttons: [
      { extend: "create", editor: editor },
      { extend: "edit",   editor: editor },
      { extend: "remove", editor: editor }
    ]
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
    $('#table_payables').DataTable().draw();
  });
