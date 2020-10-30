
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
      { label: "Customer", name: "quotations.id_customer", type: "select", def : "8000" },
      { label: "Contact", name: "quotations.id_contact", type: "select"},
      { label: "MRSAS", name: "quotations.id_user", type: "select" },
      { label: "Quotation Date", name: "quotations.quotation_date", type:  'datetime', def:    function () { return new Date(); } },
      { label: "Estimated", name: "quotations.quotation_estimated"},
      { label: "State", name: "quotations.quotation_actif",
          type:  "radio",
          options: [
            { label: "Cancelled",  value: 0 },
            { label: "Created", value: 1 }
          ],
          def: 0 }
    ]
  } );

editor.dependent( 'quotations.id_customer', 'controller/editor-contactsPerCustomer.php' );

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
    order: [ 0, "desc" ],
    columns: [
      { data: 'quotations.id_quotation',
    render: function ( data, type, row ) {
      return "D"+data;
    }  },
      { data: 'quotations.id_customer',
    render: function ( data, type, row ) {
      return data+' - '+row.entreprises.entreprise_abbr;
    }  },
      { data: 'quotations.id_contact',
    render: function ( data, type, row ) {
      if (data) {
        return row.contacts.prenom.charAt(0)+'. '+row.contacts.nom;
      }
      else {
        return "";
      }
    }  },
      { data: 'techniciens.technicien' },
      { data: 'quotations.quotation_date',
      render: function ( data, type, row ) {
        dateDue = new Date(data);
        return $.datepicker.formatDate('yy-mm-dd', dateDue);
      }
     },
      { data: 'quotations.quotation_estimated', className: "sumEstimated" },
      { data: 'info_jobs.job',
      render: function ( data, type, row ) {
        text="";
        if (data) {
          job=data.split('-');
          job.forEach(element => text+= '<a href="index.php?page=invoiceJob&job='+element+'">'+element+'</a> ');
          return text;
        }
        else {
          return '';
        }
      }    },
      { data: 'quotations.quotation_actif',
      render: function ( data, type, row ) {
        if (data=0) {
          return "0 - Cancelled";
        }
        else if (data=1) {
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
      { extend: "edit",   editor: editor }
    ],
  headerCallback: function ( row, data, start, end, display ) {
    var api = this.api();

    api.columns('.sumEstimated', { page: 'current' }).every(function () {
      var sum = api
      .cells( null, this.index(), { page: 'current'} )
      .render('display')
      .reduce(function (a, b) {
        var x = parseFloat(a) || 0;
        var y = parseFloat(b.replace(/[$ €]+/g, '')) || 0;
        return x + y;
      }, 0);
      //$(this.header()).html('$ '+sum.toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €');
      $(this.header()).html(sum.toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 '));
    });
  }
  });


  table
  .buttons()
  .container()
  .appendTo( '#btn' );




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
