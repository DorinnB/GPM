
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {


  // Setup - add a text input to each footer cell
  $('#table_listeEssais tfoot th').each( function (i) {
    var title = $('#table_listeEssais thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );




  var table = $('#table_listeEssais').DataTable( {
    ajax: {
      url : "controller/editor-listeEssais.php",
      type: "POST"
    },
    order: [[ 0, "desc" ]],
    columns: [
      { data: null,
      render : function(data, type, full, meta){
        test=data+"a";
        return '<a href="index.php?page=split&id_tbljob='+data.tbljobs.id_tbljob+'">'+data.enregistrementessais.n_fichier+'</a>';
      }},
      { data: "test_type.test_type_abbr" },
      { data: "eprouvettes.c_temperature" },
      { data: "info_jobs.customer" },
      { data: "info_jobs.job" },
      { data: "tbljobs.split" },
        { data: "eprouvettes.n_essai" },
      { data: "master_eprouvettes.prefixe" },
      { data: "master_eprouvettes.nom_eprouvette" },
      { data: "machines.machine" },
      { data: "enregistrementessais.date" },
      { data: "op.technicien" },
      { data: "chk.technicien" },
      { data: "extensometres.extensometre" },
      { data: null,
      render : function(data, type, full, meta){
          return (parseInt(data.eprouvettes.temps_essais)||' ');
      }},
      { data: "eprouvettes.flag_qualite" }
    ],
    scrollY: '70vh',
    scrollCollapse: true,
    paging: false
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
    $('#table_listeEssais').DataTable().draw();
  });
