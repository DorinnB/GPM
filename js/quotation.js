$(document).ready(function(){



  $("#sortable").sortable({
    handle: '.handle',
    stop : function(event, ui){
      //console.log($(this).sortable('serialize'));
            showSave();
    }
  });
  $("#sortable").disableSelection();


  // Setup - add a text input to each footer cell
  $('#table_pricinglists tfoot th').each( function (i) {
    var title = $('#table_pricinglists thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );

  // Activate an inline edit on click of a table cell
  $('#example').on( 'click', 'tbody td:not(:first-child)', function (e) {
    editor.inline( this );
  } );


  var table = $('#table_pricinglists').DataTable( {
    dom: "frtip",
    ajax: {
      url : "controller/editor-pricinglists.php",
      type: "POST"
    },
    order: [ 1, "asc" ],
    columns: [
      {
        data: null,
        defaultContent: '',
        className: 'select-checkbox',
        orderable: false
      },
      { data: null,
        className: "sum",
        render: function ( data, type, row ) {
          a=data.pricinglists.prodCode?data.pricinglists.prodCode:"";
          b=data.pricinglists.OpnCode?data.pricinglists.OpnCode:"";
          return a + '-' + b;
        }
      },
      { data: "test_type", render: "[, ].test_type_abbr" },
      { data: "pricinglists.pricingList" },
      { data: "pricinglists.pricingListUS" },
      { data: "pricinglists.pricingListFR" },
      { data: "pricinglists.USD" },
      { data: "pricinglists.EURO" },
      { data: "pricinglists.type" },
      { data: "pricinglists.pricingList_actif" }
    ],

    scrollY: '50vh',
    scrollCollapse: true,
    paging: false,
    select: {
      style:    'os',
      selector: 'td:first-child'
    },
  } );

  table
  .column( '9' )
  .search( '1' )
  .draw();


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

  //recalcul la taille des colonnes après ouverture du modal. Par défaut elles ne sont pas calculés car modal caché.
  $("#NewCodeModal").on('shown.bs.modal', function(){
    $($.fn.dataTable.tables(true)).DataTable()
    .columns.adjust();
  });




  //Lors du save de la quotation
  $('#saveQuotation').click( function(e) {

    e.preventDefault();

    quotationlist=$("#sortable").find("select,textarea, input").serialize();

    $("#quotation").append('<input type="hidden" name="quotationlist" value="'+quotationlist+'"></input>');



    //on envoi le formulaire d'envoi
    $.ajax({
      type: "POST",
      url: "controller/updateQuotation.php",
      data: $("#quotation").serialize(), // serializes the form's elements.
      dataType: "json",
      success: function(data)
      {
        window.location.href = 'index.php?page=quotation&id_quotation='+data.id_quotation;
      }
    });
  } );


});

//changement de l'icone print en save
function showSave() {
  $('#printQuotation').css('display','none');
  $('#saveQuotation').css('display','block');
}
//au démarrage
$(document).on('input', function() {
  showSave();
});



$( "#dateQuotation" ).datepicker({
  showWeek: true,
  firstDay: 1,
  showOtherMonths: true,
  selectOtherMonths: true,
  dateFormat: "yy-mm-dd"
});



var id=0;


function addNewTitle(){
  showSave();

  id++;
  $('#sortable').last().append($('#newTitle').clone().prop('id', 'quotationlist_' + id ).toggle());
  $('#quotationlist_' + id).find('#type').prop('id', 'quotationlist_' + id + '_type' ).attr('name','quotationlist_' + id + '_type');
  $('#quotationlist_' + id).find('#description').prop('id', 'quotationlist_' + id + '_description' ).attr('name','quotationlist_' + id + '_description');
}

function addNewComment(){
  showSave();

  id++;
  $('#sortable').last().append($('#newComment').clone().prop('id', 'quotationlist_' + id ).toggle());
  $('#quotationlist_' + id).find('#type').prop('id', 'quotationlist_' + id + '_type' ).attr('name','quotationlist_' + id + '_type');
  $('#quotationlist_' + id).find('#comments').prop('id', 'quotationlist_' + id + '_comments' ).attr('name','quotationlist_' + id + '_comments');
}

function addNewCode(){
  showSave();

  $('#table_pricinglists').find('tr.selected').each(function(){
    id++;

    row=$(this).find('td').map(function() {
      return $(this).text();
    }).get();


    $('#sortable').last().append($('#newCode').clone().prop('id', 'quotationlist_' + id ).toggle());

    $('#quotationlist_' + id).find('#type').prop('id', 'quotationlist_' + id + '_type' ).attr('name','quotationlist_' + id + '_type');
    $('#quotationlist_' + id).find('.prodCode').prop('id', 'quotationlist_' + id + '_prodCode' ).attr('name','quotationlist_' + id + '_prodCode').val(row['1']);

    if ($('#lang').parents().hasClass('off')) { //off = euro
      $('#quotationlist_' + id).find('.description').prop('id', 'quotationlist_' + id + '_description' ).attr('name','quotationlist_' + id + '_description').val(row['5']);
    }
    else {
      $('#quotationlist_' + id).find('.description').prop('id', 'quotationlist_' + id + '_description' ).attr('name','quotationlist_' + id + '_description').val(row['4']);
    }

    if ($('#currency').parents().hasClass('off')) { //off = euro
      $('#quotationlist_' + id).find('.price').prop('id', 'quotationlist_' + id + '_price' ).attr('name','quotationlist_' + id + '_price').val(row['7']);
    }
    else {
      $('#quotationlist_' + id).find('.price').prop('id', 'quotationlist_' + id + '_price' ).attr('name','quotationlist_' + id + '_price').val(row['6']);
    }

    $('#quotationlist_' + id).find('.comments').prop('id', 'quotationlist_' + id + '_comments' ).attr('name','quotationlist_' + id + '_comments');
    $('#quotationlist_' + id).find('.unit').prop('id', 'quotationlist_' + id + '_unit' ).attr('name','quotationlist_' + id + '_unit');

  });

  $("#NewCodeModal").modal("hide");
}

function addHourlyCharge(){

  hourlychargeComment='';
  totalHourlyCharge=0;

  $('#table_hourlycharge > tbody  > tr').each(function() {

    nb=$(this).find("td:eq(0)").text();
    cy=$(this).find("td:eq(1)").text();
    freq=$(this).find("td:eq(2)").text();
    stl=$(this).find("td:eq(3)").text();
    fstl=$(this).find("td:eq(4)").text();
    tps=$(this).find("td:eq(5)").text();
    hrsuptest=$(this).find("td:eq(6)").text();


    if (nb>0) {
      if (stl>0 & (cy-stl)>0) {
        if ($('#lang').parents().hasClass('off')){
          hourlychargeComment+=nb + ' tests à '+freq+' Hz ('+stl+'/'+fstl+'Hz): cycles estimés '+cy+' => '+hrsuptest+' hrs supp/test\n';
        }
        else {
          hourlychargeComment+=nb + ' tests at '+freq+' Hz ('+stl+'/'+fstl+'Hz): cycles estimated '+cy+' => '+hrsuptest+' hrs additional per test\n';
        }
      }
      else {
        if ($('#lang').parents().hasClass('off')){
          hourlychargeComment+=nb + ' tests à '+freq+' Hz: cycles estimés '+cy+' => '+hrsuptest+' hrs supp/test\n';
        }
        else {
          hourlychargeComment+=nb + ' tests at '+freq+' Hz: cycles estimated '+cy+' => '+hrsuptest+' hrs additional per test\n';
        }
      }
          totalHourlyCharge+=nb * hrsuptest;
    }
  });

      $('textarea[name ="'+$('#hourlycharge').val()+'_comments"]').val(hourlychargeComment);
      $('input[name ="'+$('#hourlycharge').val()+'_unit"]').val(totalHourlyCharge);
      showSave();
      $('#HourlyChargeModal').modal('toggle');
}


function calcHourlyCharge(){
  $('#table_hourlycharge > tbody  > tr').each(function() {

    nb=$(this).find("td:eq(0)").text();
    cy=$(this).find("td:eq(1)").text();
    freq=$(this).find("td:eq(2)").text();
    stl=$(this).find("td:eq(3)").text();
    fstl=$(this).find("td:eq(4)").text();
    if (nb>0) {
      if (stl>0 & (cy-stl)>0) {
        tps=((cy-stl)/fstl+stl/freq)/3600;
      }
      else {
        tps=(cy/freq)/3600;
      }
      if (tps>24) {
        hrsuptest=tps-24;
      }
      else {
        hrsuptest=0;
      }

      $(this).find("td:eq(5)").text(tps.toFixed(2));
      $(this).find("td:eq(6)").text(Math.ceil(hrsuptest));
    }
  });
}


//nom des contacts selon le customer
$("#ref_customer").change(function() {
  $.get("controller/lstClient-controller.php?&ref_customer=" + $("#ref_customer").val(),function(result)  {
    $("#id_contact").load("controller/lstContact-controller.php?id_contact=" + $("#idcontact").html() + "&ref_customer=" + $("#ref_customer").val());
    $("#nomclient").val(result);
  });
});
$.get("controller/lstClient-controller.php?&ref_customer=" + $("#ref_customer").val(),function(result)  {
  $("#id_contact").load("controller/lstContact-controller.php?id_contact=" + $("#idcontact").html() + "&ref_customer=" + $("#ref_customer").val());
  $("#nomclient").val(result);
});



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
