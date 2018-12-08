function validateForm() {

  var x = readCookie('id_user')
  if (!x) {
    alert("You are no longer connected.");
    return false;
  }

}


function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}





//Selon le navigateur utilisé, on detecte le style de transition utilisé
function whichTransitionEvent(){
  var t,
  el = document.createElement("fakeelement");

  var transitions = {
    "transition"      : "transitionend",
    "OTransition"     : "oTransitionEnd",
    "MozTransition"   : "transitionend",
    "WebkitTransition": "webkitTransitionEnd"
  }

  for (t in transitions){
    if (el.style[t] !== undefined){
      return transitions[t];
    }
  }
}

var transitionEvent = whichTransitionEvent();

//On retracte le tbl des jobs, et une fois retracté, on redessine le tableau history
$("#wrapper").addClass("toggled");
$("#wrapper").one(transitionEvent,
  function(event) {
    $('#table_GestionEp').DataTable().draw();
  });





  $(document).ready(function(e) {
    $('img[usemap]').rwdImageMaps();


    //selection outillage + search
    $("#id_outillage_top").select2();
    $("#id_outillage_bot").select2();


    //STOCK
    $("#id_extensometre").select2();
    $(".extensoPill").click(function(e){
      id=$(this).attr("data-id_extensometre");
      $("#id_extensometre").val(id).trigger("change");
    });
    $('#id_extensometre').on("change", function(e) {
      extenso=$("#id_extensometre").find(":selected");
      $("#summary").html("");
      $('#summary').append('<input type="hidden" name="item" value="id_extensometre">'
      +'<input type="hidden" name="id" value="'+extenso.attr("data-id_extensometre")+'">'
      +'<h3 class="list-group-item-heading">'+extenso.attr("data-extensometre")+'</h3>'
      +'<p class="list-group-item-text">Lo : '+extenso.attr("data-Lo")+'</p>'
      +'<p class="list-group-item-text">Type : '+extenso.attr("data-type_extensometre")+'</p>'
      +'<div class="form-group" style="height:100%;"><textarea name="extensometre_comment" class="form-control" placeholder="Device comments" style="height:100%;">'+extenso.attr('data-extensometre_comment')+'</textarea></div>');
    });

    $("#id_chauffage").select2();
    $(".chauffagePill").click(function(e){
      id=$(this).attr("data-id_chauffage");
      $("#id_chauffage").val(id).trigger("change");
    });
    $('#id_chauffage').on("change", function(e) {
      chauffage=$("#id_chauffage").find(":selected");
      $("#summary").html("");
      $('#summary').append('<input type="hidden" name="item" value="id_chauffage">'
      +'<input type="hidden" name="id" value="'+chauffage.attr("data-id_chauffage")+'">'
      +'<h3 class="list-group-item-heading">'+chauffage.attr("data-chauffage")+'</h3>'
      +'<p class="list-group-item-text">Type : '+chauffage.attr("data-type_chauffage")+'</p>'
      +'<div class="form-group" style="height:100%;"><textarea name="chauffage_comment" class="form-control" placeholder="Device comments" style="height:100%;">'+chauffage.attr('data-chauffage_comment')+'</textarea></div>');
    });

    $("#id_outillage").select2();
    $(".outillagePill").click(function(e){
      id=$(this).attr("data-id_outillage");
      $("#id_outillage").val(id).trigger("change");
    });
    $('#id_outillage').on("change", function(e) {
      outillage=$("#id_outillage").find(":selected");
      $("#summary").html("");
      $('#summary').append('<input type="hidden" name="item" value="id_outillage_top">'
      +'<input type="hidden" name="id" value="'+outillage.attr("data-id_outillage")+'">'
      +'<h3 class="list-group-item-heading">'+outillage.attr("data-outillage")+'</h3>'
      +'<p class="list-group-item-text">Type : '+outillage.attr("data-outillage_type")+'</p>'
      +'<p class="list-group-item-text">Material : '+outillage.attr("data-matiere")+'</p>'
      +'<div class="form-group" style="height:100%;"><textarea name="comments" class="form-control" placeholder="Device comments" style="height:100%;">'+outillage.attr('data-comments')+'</textarea></div>');
    });

    $("#id_servovalve").select2();
    $(".servovalvePill").click(function(e){
      id=$(this).attr("data-id_servovalve");
      $("#id_servovalve").val(id).trigger("change");
    });
    $('#id_servovalve').on("change", function(e) {
      servovalve=$("#id_servovalve").find(":selected");
      $("#summary").html("");
      $('#summary').append('<input type="hidden" name="item" value="id_servovalve1">'
      +'<input type="hidden" name="id" value="'+servovalve.attr("data-id_servovalve")+'">'
      +'<h3 class="list-group-item-heading">'+servovalve.attr("data-servovalve")+'</h3>'
      +'<p class="list-group-item-text">Type : '+servovalve.attr("data-servovalve_model")+'</p>'
      +'<p class="list-group-item-text">Capacity : '+servovalve.attr("data-servovalve_capacity")+'</p>'
      +'<div class="form-group" style="height:100%;"><textarea name="servovalve_comment" class="form-control" placeholder="Device comments" style="height:100%;">'+servovalve.attr('data-servovalve_comment')+'</textarea></div>');
    });

    $("#id_cell_load").select2();
    $(".cell_loadPill").click(function(e){
      id=$(this).attr("data-id_cell_load");
      $("#id_cell_load").val(id).trigger("change");
    });
    $('#id_cell_load').on("change", function(e) {
      cell_load=$("#id_cell_load").find(":selected");
      $("#summary").html("");
      $('#summary').append('<input type="hidden" name="item" value="id_cell_load">'
      +'<input type="hidden" name="id" value="'+cell_load.attr("data-id_cell_load")+'">'
      +'<h3 class="list-group-item-heading">'+cell_load.attr("data-cell_load_serial")+'</h3>'
      +'<p class="list-group-item-text">Capacity : '+cell_load.attr("data-servovalve_capacity")+'</p>'
      +'<div class="form-group" style="height:100%;"><textarea name="cell_load_comment" class="form-control" placeholder="Device comments" style="height:100%;">'+cell_load.attr('data-cell_load_comment')+'</textarea></div>');
    });
  });
