//charge les differentes informations des blocs
$(function () {
  $("#posteMachine").change();
  $("#id_cell_load").change();
  $("#id_cell_displacement").change();
  $("#id_servovalve1").change();
  $("#id_servovalve2").change();
});

function hideAll()  {
  $('#posteMachine').css('display','none');
  $('#loadCell').css('display','none');
  $('#displacementCell').css('display','none');
  $('#servovalve').css('display','none');
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

//servovalve
$("#id_servovalve1").change(function() {
  $.get("controller/lstServovalve-controller.php?&id_servovalve=" + $("#id_servovalve1").val(),function(result)  {
    $("#servovalve1_model").attr("placeholder", result.servovalve_model);
    $("#servovalve1_capacity").attr("placeholder", result.servovalve_capacity);
    $("#fixing_type1").attr("placeholder", result.fixing_type);
  }, "json");
});
//servovalve
$("#id_servovalve2").change(function() {
  $.get("controller/lstServovalve-controller.php?&id_servovalve=" + $("#id_servovalve2").val(),function(result)  {
    $("#servovalve2_model").attr("placeholder", result.servovalve_model);
    $("#servovalve2_capacity").attr("placeholder", result.servovalve_capacity);
    $("#fixing_type2").attr("placeholder", result.fixing_type);
  }, "json");
});
