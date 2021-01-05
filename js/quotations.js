
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
      { label: "quotation.quotation_actif", name: "quotation.quotation_actif",
      type: "radio",
      options: [
        { label: "-", value: 1 },
        { label: "Price",    value: -1 },
        { label: "DyT",    value: -2 },
        { label: "Aborted",    value: -3 },
        { label: "Unspecified",    value: -4 }
      ]  }
    ]
  } );

  // Activate an inline edit on click of a table cell
  $('#table_quotations').on( 'click', 'tbody td.editable', function (e) {
    editor.inline( this, {
      onBlur: 'submit'
    } );
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
    order: [ 0, "desc" ],
    columns: [
      { data: 'quotation.id_quotation',
      render: function ( data, type, row ) {
        dateForm=new Date(row.quotation.creation_date);
        return '<a href="index.php?page=quotation&id_quotation='+data+'">'+"D"+dateForm.getFullYear().toString().substr(-2)+"-"+data.padStart(5,"0")+'</a>';
      }  },
      { data: 'quotation.customer',
      render: function ( data, type, row ) {
        return data+' - '+row.entreprises.entreprise_abbr;
      }  },
      { data: 'quotation.id_contact',
      render: function ( data, type, row ) {
        if (data) {
          return row.contacts.prenom.charAt(0)+'. '+row.contacts.nom;
        }
        else {
          return "";
        }
      }  },
      { data: 'quotation.rfq' },
      { data: 'techniciens.technicien' },
      { data: 'quotation.creation_date',
      render: function ( data, type, row ) {
        dateDue = new Date(data);
        return $.datepicker.formatDate('yy-mm-dd', dateDue);
      }    },
      { data: 'quotation.quotation_date',
      render: function ( data, type, row ) {
        dateDue = new Date(data);
        return $.datepicker.formatDate('yy-mm-dd', dateDue);
      }  },
      { data: 'quotation.quotationlist', className: "sumEstimated",
      render: function ( data, type, row ) {
        var unit=0;
        var price=0;
        total=0;

        if (data) {

          dataSplit = data.split('&');

          dataSplit.forEach(function(entry) {
            var newData = entry.split('=');
            var newName = newData[0].split('_');
            var newValue = newData[1];

            if (newName[2]=='unit') {
              unit= newValue;
            }
            if (newName[2]=='price') {
              price= newValue;
              total += unit * price;
            }

          });

        }
        if (total!=0) {
          return total.toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ');
        }
        return "";

      } },
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
      { data: 'quotation.mrsasComments' },
      { data: null,
        render: function ( data, type, row ) {

          if (row.info_jobs.job) {
            return '4 - Accepted';
          }
          else if (row.quotation.quotation_date) {
            return '3 - Sent';
          }
          else if (row.quotation.id_checker>0) {
            return '2 - Checked';
          }
          else if (row.techniciens.id_technicien>0) {
            return '1 - Awaiting Check';
          }
          else {
            return '0 - Created';
          }

        }
      },
      { data: 'quotation.quotation_actif',
      render: function ( data, type, row) {
        if (data==1) {
          return '';
        }
        else if (data==-1) {
          return 'Price';
        }
        else if (data==-2) {
          return 'DyT';
        }
        else if (data==-3) {
          return 'Aborted';
        }
        else if (data==-4) {
          return 'Unspecified';
        }
        else {
          return "CONTACT IT PLEASE."
        }
      }, className: 'editable' }
    ],
    scrollY: '65vh',
    scrollX : true,
    scrollCollapse: true,
    paging: false,
    info: true,
    buttons: [
      { extend: "edit", editor: editor },
      { text: 'New Quotation',
      action: function ( e, dt, node, config ) {
        document.location.replace('index.php?page=quotation&id_quotation=0');
      } },
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
