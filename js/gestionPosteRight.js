//charge les differentes informations des blocs
$(function () {
  $("#id_cell_load").change();
  $("#id_cell_displacement").change();
  $("#posteMachine").change();
});

function hideAll()  {
  $('#loadCell').css('display','none');
  $('#displacementCell').css('display','none');
  $('#posteMachine').css('display','none');
}




//load Cell
$("#id_cell_load").change(function() {
  $.get("controller/lstCellLoad-controller.php?&id_cell_load=" + $("#id_cell_load").val(),function(result)  {
    $("#Load_Model").attr("placeholder", result.cell_load_model);
    $("#Load_Capacity").attr("placeholder", result.cell_load_capacity);
    $("#Load_Gamme").attr("placeholder", result.cell_load_gamme);
  }, "json");
});

//displacement Cell
$("#id_cell_displacement").change(function() {
  $.get("controller/lstCelldisplacement-controller.php?&id_cell_displacement=" + $("#id_cell_displacement").val(),function(result)  {
    $("#displacement_Model").attr("placeholder", result.cell_displacement_model);
    $("#displacement_Capacity").attr("placeholder", result.cell_displacement_capacity);
    $("#displacement_Gamme").attr("placeholder", result.cell_displacement_gamme);
  }, "json");
});
