
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {


  // Setup - add a text input to each footer cell
  $('#table_badge tfoot th').each( function (i) {
    var title = $('#table_badge thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_badge').DataTable( {
    ajax: {
      url : "controller/editor-badgeUsers.php",
      type: "POST"
    },
    order: [[ 1, "desc" ],[2,"asc"]],
    columns: [
      {  data: null,
        class:"week",
        render: function ( data, type, row ) {
          day=new Date(data.badges.date);
          Date.prototype.getWeek = function() {
            var onejan = new Date(this.getFullYear(),0,1);
            //return Math.ceil((((this - onejan) / 86400000) + onejan.getDay()+1)/7);
            return Math.ceil((((this - onejan) / 86400000) + onejan.getDay())/7);
          }
          week=day.getWeek();
          filtre=day.getFullYear() + '-' + ((week<10)?"0":"") + week;
          return filtre;
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
    paging: false
  } );




  day=new Date();
  Date.prototype.getWeek = function() {
    var onejan = new Date(this.getFullYear(),0,1);
    //return Math.ceil((((this - onejan) / 86400000) + onejan.getDay()+1)/7);
    return Math.ceil((((this - onejan) / 86400000) + onejan.getDay())/7);
  }
  week=day.getWeek()-1;
  filtre=day.getFullYear() + '-' + ((week<10)?"0":"") + week;

  table
  .column( '0' )
  .search( filtre, true, false )
  .draw();


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
