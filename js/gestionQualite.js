
var editor; // use a global for the submit and return data rendering in the examples
var editor2;
$(document).ready(function() {

  editor2 = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-listeTemperatureCorrection.php",
      type: "POST",
      data: function ( d ) {
        d.id_temperature_correction_parameter = $("#id_temperature_correction_parameter").attr("data-id_temperature_correction_parameter");
      }
    },
    table: "#table_temperature_corrections",
    fields: [
      { label: "Temperature",       name: "temperature_corrections.temperature" },
      { label: "Correction",       name: "temperature_corrections.correction" }
    ]
  } );

  var table = $('#table_temperature_corrections').DataTable( {

    ajax: {
      url : "controller/editor-listeTemperatureCorrection.php",
      type: "POST",
      data: function ( d ) {
        d.id_temperature_correction_parameter = $("#id_temperature_correction_parameter").attr("data-id_temperature_correction_parameter");
      }
    },
    paging: false,
    searching: false,
    info:false,
    order: [[ 1, "asc" ]],
    columns: [
      {
        data: null,
        defaultContent: '',
        className: 'select-checkbox',
        orderable: false
      },
      { data: "temperature_corrections.temperature" },
      { data: "temperature_corrections.correction" }
    ],
    select: {
      style:    'os',
      selector: 'td:first-child',
      blurable: true
    }
  } );


  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-listeTemperatureCorrectionParameter.php",
      type: "POST",
      data: function ( d ) {
        console.log(d);
      }
    },
    fields: [
      { label: "TC Type",       name: "temperature_correction_parameters.tc_type" },
      { label: "Supplier",       name: "temperature_correction_parameters.supplier" },
      { label: "Spool",       name: "temperature_correction_parameters.spool" }

    ]
  } );

  $('button.create2').on( 'click', function () {
    editor
    .title('Create new record')
    .buttons(      { extend: "create", editor: editor })
    .create();

  } );

} );



function chgtParameter(id,spool){
  $("#id_temperature_correction_parameter").html(spool+' <span class="caret"></span>');
  $("#id_temperature_correction_parameter").attr('data-id_temperature_correction_parameter',id);

  table=$('#table_temperature_corrections').DataTable( {
    ajax: {
      url : "controller/editor-listeTemperatureCorrection.php",
      type: "POST",
      data: function ( d ) {
        d.id_temperature_correction_parameter = $("#id_temperature_correction_parameter").attr("data-id_temperature_correction_parameter");
      }
    },
    destroy:true,
    paging: false,
    searching: false,
    info:false,
    order: [[ 1, "asc" ]],
    columns: [
      {
        data: null,
        defaultContent: '',
        className: 'select-checkbox',
        orderable: false
      },
      { data: "temperature_corrections.temperature" },
      { data: "temperature_corrections.correction" }
    ],
    select: {
      style:    'os',
      selector: 'td:first-child',
      blurable: true
    }
  } );

  table=$('#table_temperature_corrections').DataTable().ajax.reload(
    function () {
      if ( ! table.data().any() ) {
        newEntry();
      }
    }
  );
}

function newEntry(){
  table=$('#table_temperature_corrections').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-listeTemperatureCorrection.php",
      type: "POST",
      data: function ( d ) {
        d.id_temperature_correction_parameter = $("#id_temperature_correction_parameter").attr("data-id_temperature_correction_parameter");
      }
    },
    destroy:true,
    paging: false,
    searching: false,
    info:false,
    order: [[ 1, "asc" ]],
    columns: [
      {
        data: null,
        defaultContent: '',
        className: 'select-checkbox',
        orderable: false
      },
      { data: "temperature_corrections.temperature" },
      { data: "temperature_corrections.correction" }
    ],
    select: {
      style:    'os',
      selector: 'td:first-child',
      blurable: true
    },
    buttons: [
      { extend: "create", editor: editor2 }
    ]
  } );
}

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
    $('#table_temperature_corrections').DataTable().draw();
  });
