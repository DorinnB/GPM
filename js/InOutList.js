
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {


  // Setup - add a text input to each footer cell
  $('#table_InOutList tfoot th').each( function (i) {
    var title = $('#table_InOutList thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );




  var table = $('#table_InOutList').DataTable( {
    dom: 'Bfrtip',
    ajax: {
      url : "controller/editor-InOutList.php",
      type: "POST"
    },
    order: [[ 1, "desc" ]],
    columns: [
      { data: "info_jobs.customer" },
        { data: null,
        render : function(data, type, full, meta){
          test=data+"a";
          return '<a href="index.php?page=inOut&id_infojob='+data.info_jobs.id_info_job+'">'+data.info_jobs.job+'</a>';
        }},
      { data: "info_jobs.instruction", "width": "40%" },
      { data: "master_eprouvettes.prefixe" },
      { data: "master_eprouvettes.nom_eprouvette" },
      { data: "master_eprouvettes.master_eprouvette_inOut_A" },
      { data: "master_eprouvettes.master_eprouvette_inOut_B" }
    ],
    scrollY: '70vh',
    scrollCollapse: true,
    paging: false,
    buttons: [
           {
               extend: 'collection',
               text: 'Export',
               buttons: [
                   'excel'
               ]
           }
       ]
  } );






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
    $('#table_InOutList').DataTable().draw();
  });
