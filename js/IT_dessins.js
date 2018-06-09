
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-dessins.php",
      type: "POST"
    },
    table: "#table_dessins",
    fields: [
      { label: "Dessin", name: "dessins.dessin"  },
      { label: "Grip", name: "dessins.gripType", type: "select",
      options: [
        "Cylindrical",
        "Hydraulic Flat",
        "Hydraulic",
        "Other"
      ]
    },
    { label: "Grip Dimension", name: "dessins.gripDimension"  },
    { label: "Format Type", name: "dessins.id_dessin_type", type: "select" },
    { label: "nominal_1", name: "dessins.nominal_1"  },
    { label: "tolerance_plus_1", name: "dessins.tolerance_plus_1"  },
    { label: "tolerance_moins_1", name: "dessins.tolerance_moins_1"  },
    { label: "nominal_2", name: "dessins.nominal_2"  },
    { label: "tolerance_plus_2", name: "dessins.tolerance_plus_2"  },
    { label: "tolerance_moins_2", name: "dessins.tolerance_moins_2"  },
    { label: "nominal_3", name: "dessins.nominal_3"  },
    { label: "tolerance_plus_3", name: "dessins.tolerance_plus_3"  },
    { label: "tolerance_moins_3", name: "dessins.tolerance_moins_3"  },

    { label: "Actif", name: "dessins.dessin_actif" },
  ]
} );

// Setup - add a text input to each footer cell
$('#table_dessins tfoot th').each( function (i) {
  var title = $('#table_dessins thead th').eq( $(this).index() ).text();
  $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
} );



var table = $('#table_dessins').DataTable( {
  dom: "Bfrtip",
  ajax: {
    url : "controller/editor-dessins.php",
    type: "POST"
  },
  order: [[ 1, "asc" ],[0,"asc"]],
  columns: [
    { data: "dessins.dessin" },
    { data: "dessins.gripType" },
    { data: "dessins.gripDimension" },
    { data: "dessin_types.dessin_type" },
    { data: "dessins.nominal_1" },
    { data: "dessins.tolerance_plus_1" },
    { data: "dessins.tolerance_moins_1" },
    { data: "dessins.nominal_2" },
    { data: "dessins.tolerance_plus_2" },
    { data: "dessins.tolerance_moins_2" },
    { data: "dessins.nominal_3" },
    { data: "dessins.tolerance_plus_3" },
    { data: "dessins.tolerance_moins_3" },

    { data: "dessins.dessin_actif" }
  ],
  scrollY: '65vh',
  scrollCollapse: true,
  paging: false,
  keys: {
    columns: [4,5,6,7,8,9,10,11,12,13],
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
.column( '13' )
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

//On retracte le tbl des jobs, et une fois retracté, on redessine le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    $('#table_listeFlagQualite').DataTable().draw();
  });
