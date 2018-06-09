$(document).ready(function() {


  var tableJob = $('#table_planningJob').DataTable({

    order: [[ 1, "desc" ]],
        scrollY:        '80vh',
    scrollCollapse: true,
    paging: false,
    scrollX: true,
    info:false,
  });
  var tableJobFrame = $('#table_planningJobFrame').DataTable({
    order: [[ 0, "asc" ]],
            scrollY:        '80vh',
    scrollCollapse: true,
    paging: false,
    scrollX: true,
    info:false,
    fixedColumns:   {leftColumns: 1}
  });
  var tableJobFrame2 = $('#table_planningJobFrame2').DataTable({

    order: [[ 0, "asc" ]],
            scrollY:        '100vh',
    scrollCollapse: true,
    paging: false,
    scrollX: true,
    info:false,
 ordering: false,
    fixedColumns:   {leftColumns: 1}
  });

  $('#container').css('display', 'block');


  // Setup - add a text input to each footer cell
  $(".dataTables_scrollFootInner tfoot th").each(function() {
    var title = $(this).text();
    $(this).html('<input type="text" placeholder="' + title + '" / style="width:100%">');
  });

  tableJob.columns().every(function() {
    var that = this;

    $('input', this.footer()).on('keyup change', function() {
      if (that.search() !== this.value) {
        that
        .search(this.value)
        .draw();
      }
    });
  });

  if (document.getElementById("table_planningJob_filter")) {
    document.getElementById("table_planningJob_filter").style.display = "none";
  }
  if (document.getElementById("table_planningJobFrame_filter")) {
    document.getElementById("table_planningJobFrame_filter").style.display = "none";
  }
  if (document.getElementById("table_planningJobFrame2_filter")) {
    document.getElementById("table_planningJobFrame2_filter").style.display = "none";
  }



  $('.popover-markup').hover(function(){
    //mise en couleur des cases de ce split
      val=$(this).attr('data-id_tbljob');
      $('.popover-markup').each(function() {
          if ($(this).attr('data-id_tbljob')==val) {
            $(this).toggleClass('highlight');
          }
          else {
            $(this).removeClass('highlight');
          }
     });
  });

  $('.machine').click(function(){
    $('#id_tbljob_actif').attr('data-id_tbljob',$(this).attr('data-id_tbljob'));
    $('#id_tbljob_actif').attr('data-customer',$(this).attr('data-customer'));
    $('#id_tbljob_actif').attr('data-job',$(this).attr('data-job'));
    $('#id_tbljob_actif').attr('data-split', $(this).attr('data-split'));
    $('#id_tbljob_actif').attr('data-color',$(this).attr('data-color'));

if (($(this).attr('data-id_tbljob')>=10) & ($(this).attr('data-id_tbljob')<=15)) {
  $('#id_tbljob_actif').html($(this).html());
}
else {

    $('#id_tbljob_actif').html($(this).attr('data-customer')+'-'+$(this).attr('data-job')+'-'+$(this).attr('data-split'));
}

    //mise en couleur des cases de ce split
      val=$(this).attr('data-id_tbljob');
      $('.selectable').each(function() {
          if ($(this).attr('data-id_tbljob')==val) {
            $(this).toggleClass('highlight');
          }
          else {
            $(this).removeClass('highlight');
          }
     });
  });
  $('#gomme').click(function(){
    $('#id_tbljob_actif').attr('data-id_tbljob','');
    $('#id_tbljob_actif').attr('data-customer','');
    $('#id_tbljob_actif').attr('data-job','');
    $('#id_tbljob_actif').attr('data-split', '');
    $('#id_tbljob_actif').attr('data-color','');

    $('#id_tbljob_actif').html('');


  });




  $('#table_planningJobFrame').selectable ({
    filter:"td.selectable",
    distance:0,
    selected: function( event, ui ) {
      if ($(ui.selected).attr('data-id_tbljob')!=$('#id_tbljob_actif').attr('data-id_tbljob')) {
        $(ui.selected).attr('data-id_tbljob',$('#id_tbljob_actif').attr('data-id_tbljob'));
        if ($.isNumeric( $('#id_tbljob_actif').attr('data-job') )) {
          $(ui.selected).html($('#id_tbljob_actif').attr('data-job').slice(-2)+"-"+$('#id_tbljob_actif').attr('data-split'));
        }
        else {
          $(ui.selected).html($('#id_tbljob_actif').attr('data-split'));
        }
        $(ui.selected).attr('data-color',$('#id_tbljob_actif').attr('data-job').slice(-1));
      }
      else {
        $(ui.selected).attr('data-id_tbljob','');
        $(ui.selected).html('');
        $(ui.selected).attr('data-color','');
      }
      $(ui.selected).addClass('newVal');
      calculateEstimatedDayJob();
    }
  });



  $("#save").click(function(e) {

    e.preventDefault();

    var formJob = $.param($('td').map(function() {
      if ($(this).hasClass('newVal')){
        return {
          name: $(this).attr('data-date')+'_'+$(this).attr('data-id_machine'),
          value: $(this).attr('data-id_tbljob')
        };
      }
    }));


    $.ajax({
      type: "POST",
      url: 'controller/updatePlanningLab.php',
      dataType: "json",
      data:  {
        planningLab : formJob
      },
      success : function(data, statut){
        location.reload();
      },
      error : function(resultat, statut, erreur) {

        console.log(Object.keys(resultat));
        location.reload();
        //alert('ERREUR lors de la mise a jour du planning. Veuillez prevenir au plus vite le responsable SI.');
      }
    });
  });




  tableJob.columns.adjust().draw();



  $('#table_planningJobFrame2 tbody tr td:not(:first-child)').each(function(){
    var colSpan=1;
    while( $(this).text() == $(this).next().text() ){
      $(this).next().remove();
      colSpan++;
    }
    $(this).attr('colSpan',colSpan);
    $(this).css('text-align','center');
//je recup le customer, si on veut un code couleur customer    
$(this).attr('data-customer',$(this).children('div').eq(0).attr('data-customer'));

    $(this).children('div').eq(0).removeClass('hide');
  });


  $('.popover-markup').popover({
    html: true,
    container:'body',
    trigger: "hover",
    title: function () {
      return $(this).find('.head').html();
    },
    content: function () {
      return $(this).find('.content').html();
    }
  });

} );




//calcul des nombre de jour planifié
function calculateEstimatedDayJob() {
  $('#table_planningJob > tbody  > tr').each(function() {
    //pour chaque job on compte le nombre de jours planifié (sauf les jours avant aujourdhui)
    a=$('td[data-id_tbljob="'+$(this).attr('data-id_tbljob')+'"][data-past="0"]').length;
    //on ecrit la valeur dans le tableau
    $(this).find( "td:eq(9)" ).html(a);
    //si le nombre de jours planifié n'est pas suffisant, on colore

    if ($.isNumeric($(this).find( "td:eq(8)" ).html())) {
      if ($(this).find( "td:eq(8)" ).html()>$(this).find( "td:eq(9)" ).html()) {
        $(this).find( "td:eq(9)" ).addClass("unrated");
      }
      else {
        $(this).find( "td:eq(9)" ).removeClass("unrated");
      }
    }
    else {
      $(this).find( "td:eq(9)" ).addClass("unknow");
    }


  });

}
calculateEstimatedDayJob();
