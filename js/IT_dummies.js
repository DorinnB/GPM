
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-dummies.php",
      type: "POST"
    },
    table: "#table_dummies",
    fields: [
      { label: "Dummy D", name: "dummies.id_dummie"  },
      { label: "ID", name: "dummies.ID"  },
    { label: "Drawing", name: "dummies.id_drawing", type: "select" },
  { label: "Material", name: "dummies.id_mat", type: "select" },
    { label: "PO / Job or Date", name: "dummies.ref"  },
    { label: "dim1", name: "dummies.dim1"  },
    { label: "dim2", name: "dummies.dim2"  },
    { label: "dim3", name: "dummies.dim3"  },
    { label: "tc", name: "dummies.tc"  },
    { label: "Comments", name: "dummies.comments"  },
    { label: "Actif", name: "dummies.dummie_actif" }

  ]
} );

// Setup - add a text input to each footer cell
$('#table_dummies tfoot th').each( function (i) {
  var title = $('#table_dummies thead th').eq( $(this).index() ).text();
  $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
} );



var table = $('#table_dummies').DataTable( {
  dom: "Bfrtip",
  ajax: {
    url : "controller/editor-dummies.php",
    type: "POST"
  },
  order: [[0,"asc"]],
  columns: [
    { data: "dummies.id_dummie",
    render: function ( data, type, row) {

        return "D"+('00' + data).slice(-3);

    }},
    { data: "dummies.ID" },
    { data: "dessins.dessin" },
    { data: "dessin_types.dessin_type" },
    { data: "dessins.gripDimension" },
    { data: "matieres.matiere" },
    { data: "matieres.type_matiere" },
    { data: "dummies.ref" },
    { data: "dummies.dim1" },
    { data: "dummies.dim2" },
    { data: "dummies.dim3" },
    { data: "dummies.tc" },
    { data: "dummies.comments" },
    { data: "dummies.dummie_actif" }
  ],
  scrollY: '65vh',
  scrollCollapse: true,
  paging: false,
    fixedColumns:   {leftColumns: 1},
  select: {
    style:    'os',
    blurable: true
  },
  buttons: [
    { extend: "create", editor: editor },

      { extend: "edit", editor: editor }
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

//On retracte le tbl des jobs, et une fois retracté, on redummiese le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    $('#table_listeFlagQualite').DataTable().draw();
  });
