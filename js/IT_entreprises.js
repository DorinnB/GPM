var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-entreprises.php",
      type: "POST"
    },
    table: "#table_entreprises",
    fields: [
      { label: "N° Cust/SubC", name: "entreprises.id_entreprise"  },
      { label: "Companie", name: "entreprises.entreprise"  },
      { label: "Abbreviation", name: "entreprises.entreprise_abbr"  },
      { label: "Activity", name: "entreprises.activity_area", type: "select",
      options: [
        "A",
        "B"
      ] },
      { label: "VAT", name: "entreprises.VAT"  },
      { label: "MRSASRef", name: "entreprises.MRSASRef"  },
      { label: "Street 1", name: "entreprises.billing_rue1"  },
      { label: "Street 2", name: "entreprises.billing_rue2"  },
      { label: "Zipcode & City", name: "entreprises.billing_ville"  },
      { label: "Country", name: "entreprises.billing_pays"  },
      { label: "Additional Weekly Email", name: "entreprises.weeklyemail"  },

      { label: "Actif", name: "entreprises.entreprise_actif", def: "1" },
    ]
  } );



  // Setup - add a text input to each footer cell
  $('#table_entreprises tfoot th').each( function (i) {
    var title = $('#table_entreprises thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_entreprises').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-entreprises.php",
      type: "POST"
    },
    order: [0,"asc"],
    columns: [
      { data: "entreprises.id_entreprise" },
      { data: "entreprises.entreprise" },
      { data: "entreprises.entreprise_abbr" },
      { data: "entreprises.activity_area" },
      { data: "entreprises.VAT" },
      { data: "entreprises.MRSASRef" },
      { data: "entreprises.billing_rue1" },
      { data: "entreprises.billing_rue2" },
      { data: "entreprises.billing_ville" },
      { data: "entreprises.billing_pays" },
      { data: "entreprises.weeklyemail" },
      { data: "entreprises.entreprise_actif" }
    ],
    scrollY: '65vh',
    scrollCollapse: true,
    paging: false,
    keys: {
      columns: [2,3,4,5,6,7,8,9,10,11],
      editor:  editor
    },
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
  .column( '12' )
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
