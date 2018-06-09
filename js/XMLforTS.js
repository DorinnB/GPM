
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-XMLforTS.php",
      type: "POST"
    },
    table: "#table_XMLforTS",
    fields: [
      { label: "GPM", name: "xmlforts.xml"  },
      { label: "TS", name: "xmlforts.ts" },
      { label: "unit", name: "xmlforts.unit" },
      { label: "test_type", name: "xmlforts.id_test_type", type: "select" }
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_XMLforTS tfoot th').each( function (i) {
    var title = $('#table_XMLforTS thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_XMLforTS').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-XMLforTS.php",
      type: "POST"
    },
    order: [[ 1, "desc" ]],
    columns: [
      { data: "xmlforts.xml" },
      { data: "xmlforts.ts" },
      { data: "xmlforts.unit" },      
      { data: "test_type.test_type_abbr" }
    ],
    scrollY: '65vh',
    scrollCollapse: true,
    paging: false,
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

  //table.columns.adjust().draw();






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

//On retracte le tbl des jobs, et une fois retracté, on redessine le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    $('#table_listeFlagQualite').DataTable().draw();
  });
