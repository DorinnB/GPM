
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-contacts.php",
      type: "POST"
    },
    table: "#table_contacts",
    fields: [
      { label: "First Name", name: "contacts.prenom"  },
      { label: "Last Name", name: "contacts.nom"  },
      { label: "Dpt", name: "contacts.departement"  },
      { label: "Street", name: "contacts.rue1"  },
      { label: "Street", name: "contacts.rue2"  },
      { label: "Town", name: "contacts.ville"  },
      { label: "Country", name: "contacts.pays"  },
      { label: "Email", name: "contacts.email"  },
      { label: "Cell nb", name: "contacts.telephone"  },
      { label: "Office (responsable...)", name: "contacts.poste" },
      { label: "entreprises", name: "contacts.ref_customer", type: "select" },
      { label: "Actif", name: "contacts.contact_actif", def: "1" },
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_contacts tfoot th').each( function (i) {
    var title = $('#table_contacts thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_contacts').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-contacts.php",
      type: "POST"
    },
    order: [[ 12, "desc" ],[0,"asc"]],
    columns: [
      { data: "entreprises.id_entreprise" },
      { data: "entreprises.entreprise" },
      { data: "contacts.prenom" },
      { data: "contacts.nom" },
      { data: "contacts.departement" },
      { data: "contacts.rue1" },
      { data: "contacts.rue2" },
      { data: "contacts.ville" },
      { data: "contacts.pays" },
      { data: "contacts.email" },
      { data: "contacts.telephone" },
      { data: "contacts.poste" },
      { data: "contacts.contact_actif" }
    ],
    scrollY: '65vh',
    scrollCollapse: true,
    paging: false,
    fixedColumns:   {leftColumns: 4},
    select: {
      style:    'os',
      blurable: true
    },
    buttons: [
      { extend: "create", editor: editor },
      { extend: "edit",   editor: editor },
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

//On retracte le tbl des jobs, et une fois retracté, on redessine le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    $('#table_listeFlagQualite').DataTable().draw();
  });
