
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-filePaths.php",
      type: "POST"
    },
    table: "#table_filePaths",
    fields: [
      { label: "Type", name: "file_paths.file_type"  },
      { label: "Path", name: "file_paths.file_path"  }

    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_filePaths tfoot th').each( function (i) {
    var title = $('#table_filePaths thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_filePaths').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-filePaths.php",
      type: "POST"
    },
    order: [[0,"asc"]],
    columns: [
      { data: "file_paths.file_type" },
      { data: "file_paths.file_path" }
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
    $('#table_filePaths').DataTable().draw();
  });
