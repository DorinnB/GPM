
$(document).ready(function() {

  // DataTable
  var table = $('#table_group').DataTable( {

    scrollX:        true,
    scrollCollapse: true,
    paging:         false,
    filter:         false,
    info:           false,

    order: [ 0, "asc" ]
  } );

  var table = $('#table_Report').DataTable( {

    scrollX:        true,
    scrollCollapse: true,
    paging:         false,
    filter:         false,
    info:           false,

    order: [ 0, "asc" ]
  } );


  $( "#invoice_date" ).datepicker({
    showWeek: true,
    firstDay: 1,
    showOtherMonths: true,
    selectOtherMonths: true,
    dateFormat: "yy-mm-dd"
  });

  $( "#report_date" ).datepicker({
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



// revision du rapport
$(".report_rev").click(function(e) {
  var $this = $(this);
  $( "#dialog-rev" ).dialog({
    resizable: false,
    height: "auto",
    width: 400,
    modal: true,
    buttons: {
      "Increase": function() {
        $( this ).dialog( "close" );

        $.ajax({
          type: "POST",
          url: 'controller/updateReportFlow.php',
          dataType: "json",
          data:  {
            idtbljob : $this.attr('data-idtbljob'),
            idJob : $this.attr('data-idJob'),
            role : 'revAdd'
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

      },
      "Reset": function() {
        $( this ).dialog( "close" );

        $.ajax({
          type: "POST",
          url: 'controller/updateReportFlow.php',
          dataType: "json",
          data:  {
            idtbljob : $this.attr('data-idtbljob'),
            idJob : $this.attr('data-idJob'),
            role : 'revReset'
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
    }
  });



});

// Check Qualité
$(".report_Q").click(function(e) {
  if ($(this).attr('data-report_Q')>0) {

    var confirmation = confirm('UnCheck this Report ?\nOnly Quality Manager should do this');
  }
  else {
    var confirmation = confirm('Have you signed the Final Report ?\nOnly Quality Manager should do this');
  }

  if (confirmation) {

    $.ajax({
      type: "POST",
      url: 'controller/updateReportFlow.php',
      dataType: "json",
      data:  {
        idtbljob : $(this).attr('data-idtbljob'),
        idJob : $(this).attr('data-idJob'),
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

    var confirmation = confirm('UnCheck this Report ?\nOnly Technical Manager should do this');
  }
  else {
    var confirmation = confirm('Have you signed the Final Report ?\nOnly Technical Manager should do this');
  }

  if (confirmation) {

    $.ajax({
      type: "POST",
      url: 'controller/updateReportFlow.php',
      dataType: "json",
      data:  {
        idtbljob : $(this).attr('data-idtbljob'),
        idJob : $(this).attr('data-idJob'),
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

  //affichage de la date précédente
  if ($(this).text()!="") {
    $("#report_date").val($(this).text());
  }
  else {
    $("#report_date").val($.datepicker.formatDate('yy-mm-dd', new Date()));
  }


  var $this = $(this);

  $( "#dialog-report_date" ).dialog({
    resizable: false,
    height: "auto",
    width: 400,
    modal: true,
    buttons: {
      "Set": function() {
        $( this ).dialog( "close" );

        $.ajax({
          type: "POST",
          url: 'controller/updateReportFlow.php',
          dataType: "json",
          data:  {
            idtbljob : $this.attr('data-idtbljob'),
            idJob : $this.attr('data-idJob'),
            report_date : $('#report_date').val(),
            role : 'reportDateSet'
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

      },
      "Reset": function() {
        $( this ).dialog( "close" );

        $.ajax({
          type: "POST",
          url: 'controller/updateReportFlow.php',
          dataType: "json",
          data:  {
            idtbljob : $this.attr('data-idtbljob'),
            idJob : $this.attr('data-idJob'),
            role : 'reportDateReset'
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
    }
  });

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
        idJob : $(this).attr('data-idJob'),
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
