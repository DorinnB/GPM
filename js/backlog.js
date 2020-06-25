
var editor; // use a global for the submit and return data rendering in the examples


$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-backlog.php",
      type: "POST"
    },
    table: "#table_backlog",
    fields: [
      { label: "Estimated", name: "info_jobs.order_val"},
        { label: "Estimated", name: "info_jobs.order_est"},
          { label: "Estimated", name: "info_jobs.order_est_subc"}
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_backlog tfoot th').each( function (i) {
    var title = $('#table_backlog thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_backlog').DataTable( {
    dom: "rtip",
    ajax: {
      url : "controller/editor-backlog.php",
      type: "POST"
    },
    order: [ 1, "asc" ],
    columns: [
      { data: "info_jobs.invoice_type",
        render: function ( data, type, row ) {
          if (data==0) {
            return "UBR";
          }
          else {
            return 'PART.';
          }
        }},
      { data: "info_jobs.customer"  },
      { data: "info_jobs.job",
        render: function ( data, type, row ) {
        return '<a href="index.php?page=invoiceJob&id_infojob='+row.info_jobs.id_info_job+'">'+data+'</a>';
        }
     },
      { data: "info_jobs.order_val",      className: "sum"  },

      { data: null,        className: "sum",
        render: function ( data, type, row ) {
          invoices = (data.info_jobs.invoicesMRSAS ? parseFloat(data.info_jobs.invoicesMRSAS)+parseFloat(data.info_jobs.invoicesSubC) : 0);
          ubr = (parseFloat(data.ubr.ubrMRSAS)+parseFloat(data.ubr.ubrSubC)>0 ? parseFloat(data.ubr.ubrMRSAS)+parseFloat(data.ubr.ubrSubC) : 0);
          est = (parseFloat(data.info_jobs.order_est)+parseFloat(data.info_jobs.order_est_subc)>=0 ? parseFloat(data.info_jobs.order_est)+parseFloat(data.info_jobs.order_est_subc) : (parseFloat(data.info_jobs.order_val)>0 ? parseFloat(data.info_jobs.order_val) : "ERR"));

          if (est!='*') {
            return parseFloat(est-invoices-ubr).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €';
          }
          else {
            return "TBE";
          }
        }
      },


      { data: "info_jobs.order_est",      className: "sum"  },
      { data: null,
        className: "sum",
        render: function ( data, type, row ) {
          ubr=parseFloat(data.ubr.ubrMRSAS);
          if (ubr>0) {
            return (parseFloat(data.ubr.ubrMRSAS)).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €';
          }
          else {
            return '';
          }
        }
      },
      { data: "info_jobs.invoicesMRSAS",      className: "sum",
      render: function ( data, type, row ) {
        if (data>0) {
          return parseFloat(data).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €';
        }
        else {
          return '';
        }
      }    },
      { data: null,        className: "sum",
        render: function ( data, type, row ) {
          invoices = (data.info_jobs.invoicesMRSAS ? parseFloat(data.info_jobs.invoicesMRSAS) : 0);
          ubr = (parseFloat(data.ubr.ubrMRSAS)>0 ? parseFloat(data.ubr.ubrMRSAS) : 0);
          est = (parseFloat(data.info_jobs.order_est)>=0 ? parseFloat(data.info_jobs.order_est) : '*');

          if (est!='*') {
            return parseFloat(est-invoices-ubr).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €';
          }
          else {
            return "TBE";
          }
        }    },


        { data: "info_jobs.order_est_subc",        className: "sum"  },
        { data: null,          className: "sum",
          render: function ( data, type, row ) {
            ubr=parseFloat(data.ubr.ubrSubC);
            if (ubr>0) {
              return (parseFloat(data.ubr.ubrSubC)).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €';
            }
            else {
              return '';
            }
          }
        },
        { data: "info_jobs.invoicesSubC",        className: "sum",
        render: function ( data, type, row ) {
          if (data>0) {
            return parseFloat(data).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €';
          }
          else {
            return '';
          }
        }
      },
      { data: null,
        className: "sum",        render: function ( data, type, row ) {
          invoices = (data.info_jobs.invoicesSubC ? parseFloat(data.info_jobs.invoicesSubC) : 0);
          ubr = (parseFloat(data.ubr.ubrSubC)>0 ? parseFloat(data.ubr.ubrSubC) : 0);
          est = (parseFloat(data.info_jobs.order_est_subc)>=0 ? parseFloat(data.info_jobs.order_est_subc) : '*');

          if (est!='*') {
            return parseFloat(est-invoices-ubr).toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €';
          }
          else {
            return "TBE";
          }
        }  }
      ],
      scrollY: '60vh',
      scrollCollapse: true,
      paging: false,
      info: true,
      fixedColumns:   {leftColumns: 2},
      select: {
        style:    'os',
        blurable: true
      },
      keys: {
        columns: [3, 5, 9],
        editor:  editor
      },
      headerCallback: function ( row, data, start, end, display ) {
        var api = this.api();

        api.columns('.sum', { page: 'current' }).every(function () {
          var sum = api
          .cells( null, this.index(), { page: 'current'} )
          .render('display')
          .reduce(function (a, b) {
            var x = parseFloat(a) || 0;
            var y = parseFloat(b.replace(/[$ €]+/g, '')) || 0;
            return x + y;
          }, 0);
          $(this.header()).html(sum.toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g,'$1 ')+' €');
        });
      }
    });



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

  //On retracte le tbl des jobs, et une fois retracté, on rebackloge le tableau history
  $("#wrapper").addClass("toggled");
  $("#wrapper").one(transitionEvent,
    function(event) {
      $('#table_backlog').DataTable().draw();
    });
