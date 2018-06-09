
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-matieres.php",
      type: "POST"
    },
    table: "#table_matieres",
    fields: [
      { label: "Material", name: "matieres.matiere"  },
      { label: "Type", name: "matieres.type_matiere"},
      { label: "Young Modulus", name: "matieres.young"  },
      { label: "Actif", name: "matieres.matiere_actif" },
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_matieres tfoot th').each( function (i) {
    var title = $('#table_matieres thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_matieres').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-matieres.php",
      type: "POST"
    },
    order: [ 0, "asc" ],
    columns: [
      { data: "matieres.matiere" },
      { data: "matieres.type_matiere" },
      { data: "matieres.young" },
      { data: "matieres.matiere_actif" }
    ],
    scrollY: '65vh',
    scrollCollapse: true,
    paging: false,
    keys: {
      columns: [2,3],
      editor:  editor
    },
    select: {
      style:    'os',
      blurable: true
    },
    buttons: [
      { extend: "create", editor: editor }
    ]
  } );


  table
  .column( '4' )
  .search( '1' )
  .draw();


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
