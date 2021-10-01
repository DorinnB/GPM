
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

  $("#archive").click(function(e) {

    e.preventDefault();

    $.ajax({

      type: "POST",
      url: 'controller/archiveJob-controller.php',
      dataType: "json",
      data:  {
        id_tbljob : $('#id_tbljob').val(),
        type: "check"
      },
      success : function(data, statut){

        $("#splitStatus" ).empty();
        if (data['splitStatus']) {
          data['splitStatus'].forEach((element) => {
            $('#splitStatus').append(element);
          });
        }


        $("#Unchecked" ).empty();
        if (data['unchecked']) {
          data['unchecked'].forEach((element) => {
            $('#Unchecked').append(element);
          });
        }
        $("#MissingTrans" ).empty();
        if (data['missingTrans']) {
          data['missingTrans'].forEach((element) => {
            $('#MissingTrans').append(element+'<br/>');
          });
        }
        $("#MissingTestFile" ).empty();
        if (data['missingTestFile']) {
          data['missingTestFile'].forEach((element) => {
            $('#MissingTestFile').append(element+'<br/>');
          });
        }
      //  $("#MissingTestFile" ).css('background-color','sienna');


        $("#MissingReport" ).empty();
        if (data['missingReport']) {
          data['missingReport'].forEach((element) => {
            $('#MissingReport').append(element);
          });
        }
        $("#MissingShipped" ).empty();
        if (data['missingShipped']) {
          data['missingShipped'].forEach((element) => {
            $('#MissingShipped').append(element);
          });
        }
        $("#MissingInvoice" ).empty();
        if (data['missingInvoice']) {
          $('#MissingInvoice').append(data['missingInvoice']);
        }
        $("#OneNote" ).empty();
        if (data['oneNote']) {
          $('#OneNote').append(data['oneNote']);
        }


        $('#ArchivingModal').modal('show');
      },
      error : function(resultat, statut, erreur) {
        //console.log(Object.keys(resultat));
        alert('ERREUR lors de l\'archivage. Veuillez prevenir au plus vite le responsable SI.');
      }
    });
  });

  $("#closeJob").click(function(e) {

    e.preventDefault();
    var confirmation = confirm('Are you sure you want to Close this job ?');
    if (confirmation) {
    $.ajax({

      type: "POST",
      url: 'controller/archiveJob-controller.php',
      dataType: "json",
      data:  {
        id_tbljob : $('#id_tbljob').val(),
        type: "closeJob"
      },
      success : function(data, statut){
        console.log(data);
        alert('The job ' + data['job'] + ' was successfully Closed and the split\'s status where updated.');
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de l\'archivage. Veuillez prevenir au plus vite le responsable SI.');
      }
    } );
  }
  });

  $("#copyTestFile").click(function(e) {

    e.preventDefault();

    $.ajax({

      type: "POST",
      url: 'controller/archiveJob-controller.php',
      dataType: "json",
      data:  {
        id_tbljob : $('#id_tbljob').val(),
        type: "copyTestFile"
      },
      success : function(data, statut){
        console.log(data['nb']);
        alert(data['nb'] +' folders were transfered.');
      },
      error : function(data, statut, erreur) {
        console.log(Object.keys(data));
        alert('ERREUR lors de la copie de Trans. Fichiers trop volumineux (à faire manuellement), trop nombreux (relancer la copie) ou fichier ouvert. Dernier fichier copié : '+data['lastFile']);
      }
    } );
  });

  $("#zipJob").click(function(e) {

    e.preventDefault();
    var confirmation = confirm('Are you sure you want to Zip this job ?');
    if (confirmation) {
    $.ajax({

      type: "POST",
      url: 'controller/archiveJob-controller.php',
      dataType: "json",
      data:  {
        id_tbljob : $('#id_tbljob').val(),
        type: "zipJob"
      },
      success : function(data, statut){
        console.log(data);
        alert('The job ' + data['job'] + ' was successfully archive (zip) and the folder was deleted.');
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de l\'archivage. Veuillez prevenir au plus vite le responsable SI.');
      }
    } );
  }
  });

  $("#archiveJob").click(function(e) {

    e.preventDefault();
    var confirmation = confirm('Are you sure you want to Archive this job ?');
    if (confirmation) {
    $.ajax({

      type: "POST",
      url: 'controller/archiveJob-controller.php',
      dataType: "json",
      data:  {
        id_tbljob : $('#id_tbljob').val(),
        type: "archiveJob"
      },
      success : function(data, statut){
        console.log(data);
        alert('The job ' + data['job'] + ' was successfully archive and the split\'s status where updated.');
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de l\'archivage. Veuillez prevenir au plus vite le responsable SI.');
      }
    } );
  }
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
