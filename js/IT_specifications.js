
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-specifications.php",
      type: "POST"
    },
    table: "#table_specifications",
    fields: [
      { label: "specification", name: "specifications.specification"  },
      { label: "Version", name: "specifications.version" },
      { label: "test_type", name: "specifications.id_test_type", type: "select" },
      { label: "Actif", name: "specifications.specification_actif" }
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_specifications tfoot th').each( function (i) {
    var title = $('#table_specifications thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_specifications').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-specifications.php",
      type: "POST"
    },
    order: [[ 1, "desc" ]],
    columns: [
      { data: "specifications.specification" },
      { data: "specifications.version" },
      { data: "test_type.test_type_abbr" },
      { data: "specifications.specification_actif" }
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
      { extend: "edit",   editor: editor }
    ]
  } );



    table
    .column( '3' )
    .search( '1' )
    .draw();



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
