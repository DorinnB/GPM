$("#fileToUpload").change(function(e) {

  var formData = new FormData();
  formData.append('fileToUpload', $('#fileToUpload')[0].files[0]);
  formData.append('id_tbljob', $('#table_ep').attr('data-idJob'));


  $.ajax({
    type: "POST",
    url: 'controller/updateIQC.php',
    data : formData,
    processData: false,  // tell jQuery not to process the data
    contentType: false,  // tell jQuery not to set contentType
    success : function(data, statut){
      console.log(data);
      if (data) {
        alert(data);
      }
      else {
        alert('Data uploaded succesfully');
      }
        location.reload();
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de l\'insertion du Dimensionnel. Veuillez prevenir au plus vite le responsable SI.');
    }
  });
});
