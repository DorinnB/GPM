
var editor; // use a global for the submit and return data rendering in the examples


$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-quotations.php",
      type: "POST",
      data: {"dateStartQuotation" : $('#dateStartQuotation').text()}
    },
    table: "#table_quotations",
    fields: [
      { label: "quotation date", name: "quotations.inv_date"},
      { label: "Customer", name: "quotations.id_customer", type: "select" },

      { label: "MRSAS", name: "quotations.id_user", type: "select" },
      { label: "Quotation Date", name: "quotations.quotation_date", type:  'datetime'},
      { label: "State", name: "quotations.quotation_actif",
          type:  "radio",
          options: [
            { label: "Cancelled",  value: 0 },
            { label: "Created", value: 1 },
            { label: "Accepted",  value: 2 }
          ],
          def: 0 }
    ]
  } );


  // Setup - add a text input to each footer cell
  $('#table_quotations tfoot th').each( function (i) {
    var title = $('#table_quotations thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_quotations').DataTable( {
    dom: "Brtip",
    ajax: {
      url : "controller/editor-quotations.php",
      type: "POST",
      data: {"dateStartQuotation" : $('#dateStartQuotation').text()}
    },
    order: [ 1, "desc" ],
    columns: [
      { data: 'quotations.id_quotation' },
      { data: 'quotations.id_customer' },
      { data: 'entreprises.entreprise_abbr' },
      { data: 'quotations.id_contact',
    render: function ( data, type, row ) {
      return row.contacts.prenom.charAt(0)+'. '+row.contacts.nom;
    }  },
      { data: 'techniciens.technicien' },
      { data: 'quotations.quotation_date',
      render: function ( data, type, row ) {
        dateDue = new Date(data);
        return $.datepicker.formatDate('yy-mm-dd', dateDue);
      }
     },
      { data: 'quotations.quotation_actif',
      render: function ( data, type, row ) {
        if (data=0) {
          return "0 - Cancelled";
        }
        else if (date=1) {
          return "1 - Created";
        }
        else {
          return "2 - Accepted";
        }
      }
    }
    ],
    scrollY: '65vh',
    scrollX : true,
    scrollCollapse: true,
    paging: false,
    info: true,
    select: {
      style:    'os',
      blurable: true
    },
    buttons: [
      { extend: "create", editor: editor },
      { extend: "edit",   editor: editor },
      { extend: "remove", editor: editor }
    ]
  });


  table
  .buttons()
  .container()
  .appendTo( '#btn' );



  $('#container').css('display', 'block');
  table.columns.adjust().draw();





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

//On retracte le tbl des jobs, et une fois retracté, on requotatione le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    $('#table_quotations').DataTable().draw();
  });
