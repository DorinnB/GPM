
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = "index.php?page=split&id_tbljob="+$(this).data("id");
    });
});

  

//Save comment on weeklyReport
$("#formWeeklyReport").submit(function(e) {
  $.ajax({
    type: "POST",
    url: "controller/updateWeeklyReport.php",
    data: $("#formWeeklyReport").serialize(), // serializes the form's elements.
    success: function(data)
    {
      location.assign("controller/createWeeklyReport-controller.php?customer="+$("#customer").data('id'));
      //location.assign("index.php?page=followup");
    }
  });

  e.preventDefault(); // avoid to execute the actual submit of the form.

});
