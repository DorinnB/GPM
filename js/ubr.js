
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-payables.php",
      type: "POST"
    },
    table: "#table_ubr",
    fields: [
      { label: "supplier", name: "payables.supplier"  },
      { label: "payable", name: "payables.payable"  },
      { label: "type", name: "payables.id_payable_list", type: "select" },
      { label: "capitalize", name: "payables.capitalize"  },
      { label: "date_due", name: "payables.date_due", type:  'datetime'},
      { label: "date_invoice", name: "payables.date_invoice", type:  'datetime'},
      { label: "postedDate", name: "payables.postedDate", type:  'datetime'},
      { label: "invoice", name: "payables.invoice"  },
      { label: "job", name: "payables.job"  },
      { label: "USD", name: "payables.USD"  },
      { label: "USD", name: "payables.dontMach"  },
      { label: "taux", name: "payables.taux"  },
      { label: "HT", name: "payables.HT"  },
      { label: "TVA", name: "payables.TVA"  },
      { label: "TTC", name: "payables.TTC"  },
      { label: "date_payable", name: "payables.date_payable", type:  'datetime'},
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
      type: "POST"
    },
    order: [[ 0, "asc" ],[5,"asc"]],
    columns: [
      { data: "ubr.id_ubr","visible": false  },
      { data: "ubr.date_UBR"  },
      { data: "ubr.date_creation"  },
      { data: "info_jobs.job"  },
      { data: "payables_job.type2"  },
      { data: "ubr.UBR_GPM"  },
      { data: "ubr.UBR" },
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
    $('#table_ubr').DataTable().draw();
  });
