$("#calToUpload").change(function(e) {

  var formData = new FormData();
  formData.append('calToUpload', $('#calToUpload')[0].files[0]);


  $.ajax({
    type: "POST",
    url: 'controller/updateCalibration.php',
    data : formData,
    processData: false,  // tell jQuery not to process the data
    contentType: false,  // tell jQuery not to set contentType
    success : function(data, statut){
      console.log(data);
      if (data) {
        alert(data);
      }
      else {
        alert('Calibration uploaded successfully');
        location.reload();
      }

    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de l\'insertion de la calibration. Veuillez prevenir au plus vite le responsable SI.');
    }
  });
});


$(document).ready(function() {

  $(".check").click(function(e) {
    td=$(this);
    e.preventDefault();

    $.ajax({
      type: "POST",
      url: 'controller/updateCalibration.php',
      dataType: "json",
      data:  {

        type : "check",
        idCalibration : $(this).data('id')
      },
      success : function(data, statut){
        if (data['result']=="ok") {
          td.closest('td').prev('td').text("1");
          td.remove();
        }
        else {
          alert(data['message']);
        }
      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de la modification du statut de la calibration. Veuillez prevenir au plus vite le responsable SI.');
      }
    });
  });



  var table = $('#table_calibration').DataTable({
    scrollY: '30vh',
    scrollCollapse: true,
    "scrollX": true,
    paging: false,
    info: false
  });


  // Setup - add a text input to each footer cell
  $(".dataTables_scrollFootInner tfoot th, .DTFC_LeftFootWrapper tfoot th").each(function() {
    var title = $(this).text();
    $(this).html('<input type="text" placeholder="' + title + '" / style="width:100%">');
  });

  table.columns().every(function() {
    var that = this;

    $('input', this.footer()).on('keyup change', function() {
      if (that.search() !== this.value) {
        that
        .search(this.value)
        .draw();
      }
    });
  });

  document.getElementById("table_calibration_filter").style.display = "none";


  table.columns.adjust().draw();

  $('#table_calibration tr td:not(:last-child)').click(function () {
    // on ouvre dans une fenêtre le fichier passé en paramètre.
tr=$(this).closest('tr');

    window.open("controller/openDocument-controller?file_type=calibration"+tr.attr('data-compliant')+"&file_name="+tr.attr('data-id'),'Document','width=670,height=930,top=50,left=50');
      });
});




//affichage et disparition automatique du popover en mouse hover
$('.popover-markup').popover({
  html: true,
  container:'body',
  trigger: "manual",
  title: function () {
    return $(this).find('.head').html();
  },
  content: function () {
    return $(this).find('.content').html();
  }
})
.on("mouseenter", function () {
  var _this = this;
  $(this).popover("show");
  $(".popover").on("mouseleave", function () {
    $(_this).popover('hide');
  });
}).on("mouseleave", function () {
  var _this = this;
  setTimeout(function () {
    if (!$(".popover:hover").length) {
      $(_this).popover("hide");
    }
  });
});
