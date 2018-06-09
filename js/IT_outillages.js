
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-outillages.php",
      type: "POST"
    },
    table: "#table_outillages",
    fields: [
      { label: "Tool Name", name: "outillages.outillage"  },
      { label: "Tool Type", name: "outillages.id_outillage_type", type: "select" },

      { label: "Dimensional Value A", name: "outillages.dimA"  },
      { label: "Dimensional Value B", name: "outillages.dimB"  },
      { label: "Dimensional Value C", name: "outillages.dimC"  },
      { label: "Grip", name: "outillages.mors"  },
      { label: "Id", name: "outillages.ref"  },
      { label: "Material", name: "outillages.matiere"  },
      { label: "Cooling", name: "outillages.cooling",
                type:  "radio",
                options: [
                    { label: "Without", value: 0 },
                    { label: "With",  value: 1 }
                ],
                def: 0
            },
      { label: "Jam Pin Diameter", name: "outillages.diam_percage"  },
      { label: "Copper Shim", name: "outillages.cuivre",
                type:  "radio",
                options: [
                    { label: "Without", value: 0 },
                    { label: "With",  value: 1 }
                ],
                def: 0
            },
      { label: "MRSAS's Purchase Order", name: "outillages.po"  },
      { label: "Created for Job", name: "outillages.jobnumber"  },
      { label: "Date of Commissioning", name: "outillages.dateService"  },
      { label: "Date Out of Order", name: "outillages.dateHS"  },
      { label: "Comments", name: "outillages.comments"  },
      { label: "Active", name: "outillages.outillage_actif",
                type:  "radio",
                options: [
                    { label: "No", value: 0 },
                    { label: "Yes",  value: 1 }
                ],
                def: 0
            }
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_outillages tfoot th').each( function (i) {
    var title = $('#table_outillages thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_outillages').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-outillages.php",
      type: "POST"
    },
    order: [[ 1, "asc" ],[0,"asc"]],
    columns: [
      { data: "outillages.outillage" },
      { data: "outillage_types.outillage_type" },

      { data: "outillages.dimA" },
      { data: "outillages.dimB" },
      { data: "outillages.dimC" },
      { data: "outillages.mors" },
      { data: "outillages.ref" },
      { data: "outillages.matiere" },
      { data: "outillages.cooling" },
      { data: "outillages.diam_percage" },
      { data: "outillages.cuivre" },
      { data: "outillages.po" },
      { data: "outillages.jobnumber" },
      { data: "outillages.dateService" },
      { data: "outillages.dateHS" },
      { data: "outillages.comments" },
      { data: "outillages.outillage_actif" }

    ],
    scrollY: '65vh',
    scrollCollapse: true,
    paging: false,
    keys: {
      columns: [11,12,14,15,16],
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
  .column( '16' )
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

//On retracte le tbl des jobs, et une fois retracté, on reoutillagee le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    $('#table_listeFlagQualite').DataTable().draw();
  });
