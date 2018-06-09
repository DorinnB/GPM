
$(document).ready(function() {

  // DataTable
  var table = $('#table_group').DataTable( {

    scrollX:        true,
    scrollCollapse: true,
    paging:         false,
    filter:false,
    info: false,

    order: [ 0, "asc" ]
  } );

  var table = $('#table_Report').DataTable( {

    scrollX:        true,
    scrollCollapse: true,
    paging:         false,
    filter:false,
    info: false,

    order: [ 0, "asc" ]
  } );

  $( "#invoice_date" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });





  $("#save").click(function(e) {

    e.preventDefault();

    $.ajax({

      type: "POST",
      url: 'controller/updateReportFlow.php',
      dataType: "json",
      data:  {
        idtbljob : $('#id_tbljob').val(),
        invoice_type : $('#invoice_type').val(),
        invoice_date : $('#invoice_date').val(),
        invoice_commentaire : $('#invoice_commentaire').val(),
        role : 'invoice'
      },
      success : function(data, statut){
        location.reload();
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de la modification de l\'invoice. Veuillez prevenir au plus vite le responsable SI.');
      }
    });
  });








} );



// Check Qualité
$(".report_rev").click(function(e) {

  if ($(this).attr('data-report_rev')>=0) {
    var confirmation = confirm('Increase the revision number on this Report ? Only Quality Manager should do this');
}
else {
  var confirmation = confirm('Set revision number to 0 on this Report ? Only Quality Manager should do this');
}
    if (confirmation) {

      $.ajax({
        type: "POST",
        url: 'controller/updateReportFlow.php',
        dataType: "json",
        data:  {
          idtbljob : $(this).attr('data-idtbljob'),
          role : 'rev'
        }
        ,
        success : function(data, statut){
          location.reload();
        },
        error : function(resultat, statut, erreur) {
          console.log(Object.keys(resultat));
          alert('ERREUR lors de la modification du check Qualité du rapport. Veuillez prevenir au plus vite le responsable SI.');
        }
      });
    }

});

// Check Qualité
$(".report_Q").click(function(e) {
  if ($(this).attr('data-report_Q')>0) {

    var confirmation = confirm('UnCheck this Report ? Only Quality Manager should do this');
  }
  else {
    var confirmation = confirm('Have you signed the Final Report  ? Only Quality Manager should do this');
  }

  if (confirmation) {

    $.ajax({
      type: "POST",
      url: 'controller/updateReportFlow.php',
      dataType: "json",
      data:  {
        idtbljob : $(this).attr('data-idtbljob'),
        role : 'Q'
      }
      ,
      success : function(data, statut){
        location.reload();
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de la modification du check Qualité du rapport. Veuillez prevenir au plus vite le responsable SI.');
      }
    });
  }
});

// Check TM
$(".report_TM").click(function(e) {
  if ($(this).attr('data-report_TM')>0) {

    var confirmation = confirm('UnCheck this Report ? Only Technical Manager should do this');
  }
  else {
    var confirmation = confirm('Have you signed the Final Report ?  ? Only Technical Manager  should do this');
  }

  if (confirmation) {

    $.ajax({
      type: "POST",
      url: 'controller/updateReportFlow.php',
      dataType: "json",
      data:  {
        idtbljob : $(this).attr('data-idtbljob'),
        role : 'TM'
      }
      ,
      success : function(data, statut){
        location.reload();
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de la modification du check TM du rapport. Veuillez prevenir au plus vite le responsable SI.');
      }
    });
  }
});



$(".report_send").click(function(e) {
  var confirmation = confirm('Have you send the Final Report ?');
  if (confirmation) {
    $.ajax({
      type: "POST",
      url: 'controller/updateReportSend.php',
      dataType: "json",
      data:  {
        id_tbljob : $(this).attr('data-idtbljob'),
        id_reportSend : $(this).attr('data-report_send')
      }
      ,
      success : function(data, statut){
        location.reload();
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de l enregistrement de l envoi du rapport. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
      }
    });
  }
});

// Raw Data
$(".report_rawdata").click(function(e) {
  if ($(this).attr('data-report_rawdata')>0) {

    var confirmation = confirm('Unflag the Raw Data on this Report ? Only Quality Manager should do this');
  }
  else {
    var confirmation = confirm('Did you sent the Raw Data on this Report ? Only Quality Manager should do this');
  }

  if (confirmation) {

    $.ajax({
      type: "POST",
      url: 'controller/updateReportFlow.php',
      dataType: "json",
      data:  {
        idtbljob : $(this).attr('data-idtbljob'),
        role : 'RawData'
      }
      ,
      success : function(data, statut){
        location.reload();
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de la modification des RawData. Veuillez prevenir au plus vite le responsable SI.');
      }
    });
  }
});
