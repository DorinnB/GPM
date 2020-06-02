
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-payables.php",
      type: "POST"
    },
    table: "#table_ubr",
    fields: [
      { label: "date_UBR (please write last day of a month)", name: "ubr.date_UBR" , type:  'datetime' },
      { label: "date_creation", name: "ubr.date_creation" , type:  'datetime',   def:   function () { return new Date(); } },
      { label: "Job", name: "info_jobs.job"},
      { label: "type2", name: "payables_job.type2"  },
      { label: "ubr.UBR_GPM", name: "ubr.UBR_GPM"},
      { label: "ubr.UBR", name: "ubr.UBR"}
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
