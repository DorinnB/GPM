
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-tempConvertors.php",
      type: "POST"
    },
    table: "#table_tempConvertors",
    fields: [
      { label: "Serial", name: "ind_temps.ind_temp"  },
      { label: "Model", name: "ind_temps.ind_temp_model"},
      { label: "Actif", name: "ind_temps.ind_temp_actif" },
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_tempConvertors tfoot th').each( function (i) {
    var title = $('#table_tempConvertors thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_tempConvertors').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-tempConvertors.php",
      type: "POST"
    },
    order: [ 0, "asc" ],
    columns: [
      { data: "ind_temps.ind_temp" },
      { data: "ind_temps.ind_temp_model" },
      { data: "ind_temps.ind_temp_actif" }
    ],
    scrollY: '65vh',
    scrollCollapse: true,
    paging: false,
    keys: {
      columns: [2],
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
