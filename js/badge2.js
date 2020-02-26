
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-badge2.php",
      type: "POST"
    },
    table: "#table_badge",
    fields: [
      { label: "Date", name: "badges.date"  },
      { label: "id_user", name: "badges.id_user"  },
      { label: "validation", name: "badges.validation2"  },
      { label: "comments", name: "badges.comments"  }
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_badge tfoot th').each( function (i) {
    var title = $('#table_badge thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_badge').DataTable( {
    ajax: {
      url : "controller/editor-badge2.php",
      type: "POST"
    },
    order: [[ 1, "desc" ],[2,"asc"]],
    columns: [
      {  data: null,
        render: function ( data, type, row ) {
          day=new Date(data.badges.date);
          Date.prototype.getWeek = function() {
            var onejan = new Date(this.getFullYear(),0,1);
            //return Math.ceil((((this - onejan) / 86400000) + onejan.getDay()+1)/7);
            return Math.ceil((((this - onejan) / 86400000) + onejan.getDay())/7);
          }
          return day.getWeek();
        }
      },
      { data: "badges.date" },
      { data: "techniciens.technicien" },
      { data: null,
        render: function ( data, type, row ) {
          var date=data.badges.in1;
          if (date) {
            return date.split(' ')[1];
          }
          return "";
        }
      },
      { data: null,
        render: function ( data, type, row ) {
          var date=data.badges.in2;
          if (date) {
            return date.split(' ')[1];
          }
          return "";
        }
      },
      { data: null,
        render: function (data,type,row) {

          if (data.badges.in2) {
            return 1;
          }
          else if (data.badges.in1) {
            return 0.5;
          }
          else {
            return 0
          }
        }
      },
      { data: "badges.validation2" },
      { data: "badges.comments" },
      { data: "t2.technicien" }
    ],
    scrollY: '65vh',
    scrollCollapse: true,
    paging: false,
    fixedColumns:   {leftColumns: 3},
    autoFill: {
      columns: [6, 7],
      editor:  editor
    },
    keys: {
      columns: [6, 7],
      editor:  editor
    },
    select: {
      style:    'os',
      blurable: true
    }
  } );


  table
  .column( '6' )
  .search( '^$', true, false )
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

//On retracte le tbl des jobs, et une fois retracté, on redessine le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    $('#table_listeFlagQualite').DataTable().draw();
  });
