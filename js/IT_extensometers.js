
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-extensometers.php",
      type: "POST"
    },
    table: "#table_extensometers",
    fields: [
      { label: "Extensometre", name: "extensometres.extensometre"  },
      { label: "Model", name: "extensometres.extensometre_model"},
      { label: "Serial Number", name: "extensometres.extensometre_sn"  },
      { label: "Type", name: "extensometres.type_extensometre"},
      { label: "Lo", name: "extensometres.Lo"  },
      { label: "Comments", name: "extensometres.extensometre_comment"  },
      { label: "Actif", name: "extensometres.extensometre_actif" },
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_extensometers tfoot th').each( function (i) {
    var title = $('#table_extensometers thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_extensometers').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-extensometers.php",
      type: "POST"
    },
    order: [ 0, "asc" ],
    columns: [
      { data: "extensometres.extensometre" },
      { data: "extensometres.extensometre_model" },
      { data: "extensometres.extensometre_sn" },
      { data: "extensometres.type_extensometre" },
      { data: "extensometres.Lo" },
      { data: "extensometres.extensometre_comment" },
      { data: "machines.machine" },
      { data: "extensometres.extensometre_actif" }
    ],
    scrollY: '65vh',
    scrollCollapse: true,
    paging: false,

    fixedColumns:   {leftColumns: 1},
    keys: {
      columns: [4, 5, 7],
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
  .column( '9' )
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
