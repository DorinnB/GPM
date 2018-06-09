
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-pricinglists.php",
      type: "POST"
    },
    table: "#table_pricinglists",
    fields: [
      { label: "prodCode", name: "pricinglists.prodCode"},
      { label: "OpnCode", name: "pricinglists.OpnCode"},
      { label: "pricingList", name: "pricinglists.pricingList"  },
      { label: "pricingListFR", name: "pricinglists.pricingListFR"},
      { label: "USD", name: "pricinglists.USD"},
      { label: "EURO", name: "pricinglists.EURO"},
      { label: "Type 0=comments, 1=nbtest, 2=hrsup", name: "pricinglists.type"},
      { label: "Actif", name: "pricinglists.pricinglist_actif" },
    ]
  } );


  // Setup - add a text input to each footer cell
  $('#table_pricinglists tfoot th').each( function (i) {
    var title = $('#table_pricinglists thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_pricinglists').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-pricinglists.php",
      type: "POST"
    },
    order: [ 0, "asc" ],
    columns: [
      { data: "pricinglists.prodCode" },
      { data: "pricinglists.OpnCode" },
      { data: "pricinglists.pricingList" },
      { data: "pricinglists.pricingListFR" },
      { data: "pricinglists.USD" },
      { data: "pricinglists.EURO" },
      { data: "pricinglists.type" },
      { data: "pricinglists.pricingList_actif" }
    ],
    scrollY: '65vh',
    scrollCollapse: true,
    paging: false,
    select: {
      style:    'os',
      blurable: true
    },
    buttons: [
      { extend: "create", editor: editor },
      { extend: "edit",   editor: editor },
      { extend: "remove", editor: editor }
    ]
  } );


  table
  .column( '8' )
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
