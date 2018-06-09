
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-test_type.php",
      type: "POST"
    },
    table: "#table_test_type",
    fields: [
      { label: "Designation", name: "test_type.test_type"  },
      { label: "Test Type Abbrevation", name: "test_type.test_type_abbr"  },
      { label: "Final Split (0 or 1)", name: "test_type.final"  },
      { label: "Auxiliary Type (0 or 1)", name: "test_type.auxilaire"  },
      { label: "SubC Test  (0 or 1) (Abbr should start with a dot)", name: "test_type.ST"  },
      { label: "Pricing list:",              name: "pricinglists[].id_pricingList",
          type: "select",
          multiple: true
      },
      { label: "Active (0 or 1)", name: "test_type.test_type_actif"  },
  ]
} );

// Setup - add a text input to each footer cell
$('#table_test_type tfoot th').each( function (i) {
  var title = $('#table_test_type thead th').eq( $(this).index() ).text();
  $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
} );



var table = $('#table_test_type').DataTable( {
  dom: "Bfrtip",
  ajax: {
    url : "controller/editor-test_type.php",
    type: "POST"
  },
  order: [[ 1, "asc" ],[0,"asc"]],
  columns: [
    { data: "test_type.test_type" },
    { data: "test_type.test_type_abbr" },
    { data: "test_type.final" },
    { data: "test_type.auxilaire" },
    { data: "test_type.ST" },

    {data: null,
  render: function(data, type, row, meta) {
            var pricinglists = '';
            //loop through all the row pricinglists to build output string
            for (var item in row.pricinglists) {
               var pricinglist = row.pricinglists[item];
                pricinglists = pricinglists + pricinglist.prodCode + '-' + pricinglist.OpnCode + '  ';
            }
            return pricinglists;

          }
      },
    { data: "test_type.test_type_actif" }
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

//On retracte le tbl des jobs, et une fois retracté, on retest_typee le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    $('#table_listeFlagQualite').DataTable().draw();
  });
