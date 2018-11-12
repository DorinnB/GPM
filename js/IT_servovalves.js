
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-servovalves.php",
      type: "POST"
    },
    table: "#table_servovalves",
    fields: [
      { label: "Servovalve", name: "servovalves.servovalve"  },
      { label: "Model", name: "servovalves.servovalve_model"},
      { label: "Manufacture", name: "servovalves.manufacture"  },
      { label: "Capacity", name: "servovalves.servovalve_capacity"},
      { label: "Fixing Type", name: "servovalves.fixing_type"  },
      { label: "Manufacture Date", name: "servovalves.manufacture_date"  },
      { label: "Actif", name: "servovalves.servovalve_actif" },
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_servovalves tfoot th').each( function (i) {
    var title = $('#table_servovalves thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_servovalves').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-servovalves.php",
      type: "POST"
    },
    order: [ 0, "asc" ],
    columns: [
      { data: "servovalves.servovalve" },
      { data: "servovalves.servovalve_model" },
      { data: "servovalves.manufacture" },
      { data: "servovalves.servovalve_capacity" },
      { data: "servovalves.fixing_type" },
      { data: "servovalves.manufacture_date" },
      { data: null,
        render: function ( data ) {
          return '<button class="popoverButton" data-id="'+data.servovalves.id_servovalve+'">Click</button>';
        },
        defaultContent: ""
      },
      { data: "servovalves.servovalve_actif" }
    ],
    scrollY: '65vh',
    scrollCollapse: true,
    paging: false,
    keys: {
      columns: [8],
      editor:  editor
    },
    select: {
      style:    'os',
      blurable: true
    },
    buttons: [
      { extend: "create", editor: editor }
    ],
    drawCallback: function () {
      $('.popoverButton').each( function() {
                    $(this).popover({
                        "html": true,
                        trigger: 'manual',
                        placement: 'left',
                        "content": function () {
                          $.ajax({
                            type: "POST",
                            url: 'controller/lastSeenServovalve.php',
                            dataType: "json",
                            data:  {
                              id_servovalve : $(this).attr('data-id')
                            }
                            ,
                            success : function(data, statut){
                              $("#text_popover").text(test=data.machine);
                            },
                            error : function(resultat, statut, erreur) {
                              $("#text_popover").text("Unseen");
                            }
                          });
                            return "<div id='text_popover'>Unknown</div>";
                        }
                    });
                  })
                }
  } );


  table
  .column( '9' )
  .search( '1' )
  .draw();

  $('table').on('click', function(e){
          if($('.popoverButton').length>1)
          $('.popoverButton').popover('hide');
          $(e.target).popover('toggle');
        });


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
