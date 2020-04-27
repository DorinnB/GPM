
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-badge.php",
      type: "POST"
    },
    table: "#table_badge",
    fields: [
      { label: "Date", name: "badges.date",
      type:  'date',
      def:   function () { return new Date(); }
  },
      { label: "id_user", name: "badges.id_user"  },
      { label: "validation", name: "badges.validation"  },
      { label: "comments", name: "badges.comments"  }
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_badge tfoot th').each( function (i) {
    var title = $('#table_badge thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_badge').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-badge.php",
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
      {  data: null,
        render: function ( data, type, row ) {
          day=new Date(data.badges.date);
          var tab_jour=new Array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
          return tab_jour[day.getDay()];
        }
      },
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
          var date=data.badges.out1;
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
        render: function ( data, type, row ) {
          var date=data.badges.out2;
          if (date) {
            return date.split(' ')[1];
          }
          return "";
        }
      },
      { data: null,
        render: function (data,type,row) {

          var diff2=0;
          var diff1=0;
          in1=new Date(data.badges.in1);
          out1=new Date(data.badges.out1);
          in2=new Date(data.badges.in2);
          out2=new Date(data.badges.out2);

          if (data.badges.out2) {
            diff2=out2-in2;
          }
          if (data.badges.out1) {
            diff1=out1-in1;
          }
          date=new Date(diff2+diff1);

          var hours = date.getUTCHours();
          // Minutes part from the timestamp
          var minutes = "0" + date.getUTCMinutes();
          // Seconds part from the timestamp
          var seconds = "0" + date.getUTCSeconds();

          // Will display time in 10:30:23 format
          var formattedTime = hours + ':' + minutes.substr(-2);

          return formattedTime;
        }
      },
      { data: null,
        render: function (data,type,row) {

          var diff2=0;
          var diff1=0;
          in1=new Date(data.badges.in1);
          out1=new Date(data.badges.out1);
          in2=new Date(data.badges.in2);
          out2=new Date(data.badges.out2);
          var resthours=$('#resthours').attr('data-value');

          if (data.badges.out2) {
            diff2=out2-in2-resthours*1000*3600/2;
          }
          if (data.badges.out1) {
            diff1=out1-in1-resthours*1000*3600/2;
          }
          date=new Date(diff2+diff1);

          var hours = date.getUTCHours();
          // Minutes part from the timestamp
          var minutes = "0" + date.getUTCMinutes();
          // Seconds part from the timestamp
          var seconds = "0" + date.getUTCSeconds();

          // Will display time in 10:30:23 format
          //var formattedTime = hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
          var formattedTime = hours + ':' + minutes.substr(-2);

          return formattedTime;
        }
      },
      { data: null,
        render: function (data,type,row) {

          var diff2=0;
          var diff1=0;
          in1=new Date(data.badges.in1);
          out1=new Date(data.badges.out1);
          in2=new Date(data.badges.in2);
          out2=new Date(data.badges.out2);

          //var dayhours=$('#dayhours').attr('data-value');
          var dayhours=data.badgeplanning.quantity;
          var resthours=$('#resthours').attr('data-value');
          malus=0;

          if (data.badges.out2) {
            diff2=out2-in2;
          }
          if (data.badges.out1) {
            diff1=out1-in1;
          }

          if((diff2+diff1)/1000 < dayhours*3600) {
            diff=Math.max(diff2+diff1-resthours*1000*3600,0);
          }
          else if ((diff2+diff1)/1000 >= dayhours*3600 && (diff2+diff1)/1000 <= dayhours*3600+resthours*3600) {
            diff=dayhours*3600*1000;
          }
          else {
            diff=diff2+diff1-resthours*1000*3600;
          }

          date=new Date(diff);

          var hours = date.getUTCHours();
          // Minutes part from the timestamp
          var minutes = "0" + date.getUTCMinutes();
          // Seconds part from the timestamp
          var seconds = "0" + date.getUTCSeconds();

          // Will display time in 10:30:23 format
          var formattedTime = hours + ':' + minutes.substr(-2) ;

          return formattedTime;
        }
      },
      { data: "badges.validation" ,
      createdCell: function (td, cellData, rowData, row, col) {

        var diff2=0;
        var diff1=0;
        in1=new Date(rowData.badges.in1);
        out1=new Date(rowData.badges.out1);
        in2=new Date(rowData.badges.in2);
        out2=new Date(rowData.badges.out2);

        var dayhours=rowData.badgeplanning.quantity;
        var resthours=$('#resthours').attr('data-value');

        if (rowData.badges.out2) {
          diff2=out2-in2;
        }
        if (rowData.badges.out1) {
          diff1=out1-in1;
        }

        if((diff2+diff1)/1000 < dayhours*3600) {
          diff=Math.max(diff2+diff1-resthours*1000*3600,0);
        }
        else if ((diff2+diff1)/1000 >= dayhours*3600 && (diff2+diff1)/1000 <= dayhours*3600+resthours*3600) {
          diff=dayhours*3600*1000;
        }
        else {
          diff=diff2+diff1-resthours*1000*3600;
        }



        if (cellData) {
          $(td).addClass('validatedBadge');
        }
        else if (diff/3600/1000-dayhours==0) {
          $(td).addClass('asPlanned');
        }
        else  {
          $(td).addClass('notPLanned');
        }

      }    },
      { data: null,
        render: function (data,type,row) {

          var diff2=0;
          var diff1=0;
          in1=new Date(data.badges.in1);
          out1=new Date(data.badges.out1);
          in2=new Date(data.badges.in2);
          out2=new Date(data.badges.out2);

          //var dayhours=$('#dayhours').attr('data-value');
          var dayhours=data.badgeplanning.quantity;
          var resthours=$('#resthours').attr('data-value');
          malus=0;

          if (data.badges.out2) {
            diff2=out2-in2;
          }
          if (data.badges.out1) {
            diff1=out1-in1;
          }

          if((diff2+diff1)/1000 < dayhours*3600) {
            diff=Math.max(diff2+diff1-resthours*1000*3600,0);
          }
          else if ((diff2+diff1)/1000 >= dayhours*3600 && (diff2+diff1)/1000 <= dayhours*3600+resthours*3600) {
            diff=dayhours*3600*1000;
          }
          else {
            diff=diff2+diff1-resthours*1000*3600;
          }



          if (data.badges.validation) {
            return "Valid";
          }
          else if (diff/3600/1000-dayhours==0) {
            return "OK";
          }
          else if (diff/3600/1000-dayhours>0) {
            return "Delta ↑";
          }
          else if (diff/3600/1000-dayhours<0) {
            return "Delta ↓";
          }
          else  {
            return "ERROR";
          }


        }
      },
      { data: "badgeplanning.quantity" },
      { data: "badges.comments" },
      { data: "t2.technicien" }
    ],
    scrollY: '65vh',
    scrollCollapse: true,
    paging: false,
    fixedColumns:   {leftColumns: 3},
    autoFill: {
      columns: [11, 14],
      editor:  editor
    },
    keys: {
      columns: [11, 14],
      editor:  editor
    },
    select: {
      style:    'os',
      blurable: true
    },
    buttons: [
    { extend: "create", editor: editor }
]
  } );


  table
  .column( '12' )
  .search( 'Delta', true, false )
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
