


$("#file-input").change(function(e) {
  var formData = new FormData();
  formData.append('file-input', $('#file-input')[0].files[0]);

  formData.append('id', $('#id').val());
  formData.append('tbl', $('#tbl').val());
  formData.append('type', $('#type').val());

  $.ajax({
    type: "POST",
    url: 'controller/uploadDocument.php',
    data : formData,
    processData: false,  // tell jQuery not to process the data
    contentType: false,  // tell jQuery not to set contentType
    success : function(data, statut){
      console.log(data);
      if (data) {
        alert(data);
        }
      else {
alert('File uploaded succesfully');
      }
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de l\'upload. Veuillez prevenir au plus vite le responsable SI.');
    }
  });
});
