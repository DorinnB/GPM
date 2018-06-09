// Gestion Eprouvette
function gestionEp(idEp) {
  $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+idEp);
}

$('#table_ep').selectable ({
  filter:"td.selectable",
  distance:0,
  selecting: function( event, ui ) {
    if ($(ui.selecting).hasClass('open-GestionEp')) {
      $('#gestionEp').modal('show');
      gestionEp($(ui.selecting).data('id'));
    }
  },
  selected: function( event, ui ) {
    if ($(ui.selected).attr('data-idepdchecked')) {
      dChecked($(ui.selected));
    }
    else if ($(ui.selected).attr('data-idepchecked')) {
      checked($(ui.selected));
    }
    else if ($(ui.selected).attr('data-flagQualite')) {
      flagQualite($(ui.selected));
    }
  }

});




$(document).ready(function() {

  //activation des tooltip
 $('[data-toggle="tooltip"]').tooltip();


  // Setup - add a text input to each footer cell
  $('#table_ep tfoot th').each( function (i) {
    var title = $('#table_ep thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );

  // DataTable
  var table = $('#table_ep').DataTable( {
    scrollY:        "50vh",
    scrollX:        true,
    scrollCollapse: true,
    paging:         false,
    info: false,
    fixedColumns:   {leftColumns: 10},
    order: [[ 0, "asc" ],[3, "asc" ]],
    columnDefs: [
      {
        "targets": [ 0 ],
        "visible": false,
        "searchable": false
      }
    ]
    //          ,
    //          columnDefs: [
    //                  {targets: 13, render: $.fn.dataTable.render.number( ' ', '.', 0 )}
    //              ]
  } );

//pour le prestraining, on masque la mi-vie/1cycle de la 2eme rotation s'il n'y a pas de rotation
  if ($("#rotation").html()=='No') {
    table.columns( '.rotation' ).visible( false );
    //table.columns( [ 45,46,47,48,49 ] ).visible( false, false );
    //table.columns.adjust().draw( false ); // adjust column sizing and redraw
  }

  // Filter event handler
  $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
    table
    .column( $(this).data('index') )
    .search( this.value )
    .draw();
    checkValueModule();
    checkValueC1Stress();
    checkValueC2Stress()
  } );
  document.getElementById("table_ep_filter").style.display = "none";
} );







// Check Eprouvette

function checked(e) {
  $.ajax({
    type: "POST",
    url: 'controller/updateCheckEp.php',
    dataType: "json",
    data:  {
      idEp : e.attr('data-idepchecked'),
      checked : e.attr('data-checked')
    }
    ,
    success : function(data, statut){
      if(data['id_user']==0)  {
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").css("background-color","darkred");
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").css("color","darkred");
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").html('0');
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").attr("data-checked","0");
      }
      else {
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").css("background-color","darkgreen");
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").css("color","darkgreen");
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").html(data['id_user']);
        $( "tr" ).find("[data-idepchecked='" + data['id_eprouvette'] + "']").attr("data-checked",data['id_user'] );
      }

    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la modification du check de l\'eprouvette. Veuillez prevenir au plus vite le responsable SI.');
    }
  });
}



function dChecked(e) {

  $.ajax({
    type: "POST",
    url: 'controller/updateDCheckEp.php',
    dataType: "json",
    data:  {
      idEp : e.attr('data-idepdchecked'),
      dchecked : e.attr('data-dchecked'),
      iduser: iduser
    }
    ,
    success : function(data, statut){
      if(data['id_user']==0)  {
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").css("background-color","darkred");
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").css("color","darkred");
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").html('0');
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").attr("data-dchecked","0");
      }
      else {
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").css("background-color","darkgreen");
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").css("color","darkgreen");
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").html(data['id_user']);
        $( "tr" ).find("[data-idepdchecked='" + data['id_eprouvette'] + "']").attr("data-dchecked",data['id_user'] );
      }

    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la modification du check des valeurs de l\'eprouvette. Veuillez prevenir au plus vite le responsable SI.');
    }
  });
}



// Flag Qualité
function flagQualite(e) {
  if (e.attr('data-flagQualite')>0) {

    var confirmation = confirm('Unflag this test ? Only Quality Manager should do this');
    if (confirmation) {

      $.ajax({
        type: "POST",
        url: 'controller/updateFlagQualite.php',
        dataType: "json",
        data:  {
          idEp : e.attr('data-idepflagqualite'),
          flagQualite : e.attr('data-flagQualite')
        }
        ,
        success : function(data, statut){
          if(data['id_user']==0)  {
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("background-color","#44546A");
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("color","#44546A");
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").html("0");
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").attr("data-flagqualite",data['id_user']);
          }
          else {
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("background-color","#b96500");
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("color","#b96500");
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").html(data['id_user']);
            $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").attr("data-flagqualite",data['id_user'] );
          }

        },
        error : function(resultat, statut, erreur) {
          console.log(Object.keys(resultat));
          alert('ERREUR lors de la modification du flag qualité de l\'eprouvette. Veuillez prevenir au plus vite le responsable SI.');
        }
      });
    }
  }
  else {

    $.ajax({
      type: "POST",
      url: 'controller/updateFlagQualite.php',
      dataType: "json",
      data:  {
        idEp : e.attr('data-idepflagqualite'),
        flagQualite : e.attr('data-flagQualite')
      }
      ,
      success : function(data, statut){
        if(data['id_user']==0)  {
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("background-color","#44546A");
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("color","#44546A");
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").html("0");
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").attr("data-flagqualite",data['id_user']);
        }
        else {
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("background-color","#b96500");
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").css("color","#b96500");
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").html(data['id_user']);
          $( "tr" ).find("[data-idepflagqualite='" + data['id_eprouvette'] + "']").attr("data-flagqualite",data['id_user'] );
        }

      },
      error : function(resultat, statut, erreur) {
        console.log(Object.keys(resultat));
        alert('ERREUR lors de la modification du flag qualité de l\'eprouvette. Veuillez prevenir au plus vite le responsable SI.');
      }
    });

  }



}

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





//detection si le module est > ou < a 5% de la moyenne des autres essais
function checkValueModule() {
  var checkValueArray=['.checkValue_E_RT','.checkValue_c1_E_montant','.checkValue_c2_E_montant'];

  $(checkValueArray).each(function(i,v){
    var sum=0;
    var nb=0;
    $(v).each( function (i) {
      if ($(this).html() !=='') {
        sum+=parseFloat($(this).html());
        nb+=1;
      }
    } );

    checkValue_max=sum/nb + sum/nb*5/100;
    checkValue_min=sum/nb - sum/nb*5/100;

    $(v).each( function (i) {
      if ($(this).html()>checkValue_max && $(this).html() !=='') {
        $(this).addClass("checkValue_max");
      }
      else if ($(this).html()<checkValue_min && $(this).html() !=='') {
        $(this).addClass("checkValue_min");
      }
      else {
        $(this).removeClass("checkValue_max");
        $(this).removeClass("checkValue_min");
      }
    });
  });
}
//detection si le module est > ou < a 5% de la moyenne des autres essais
function checkValueC1Stress() {
  var checkValueArray=['.checkValue_c1_max_stress','.checkValue_c1_min_stress','.checkValue_c2_max_stress','.checkValue_c2_min_stress'];

  var sumMax=0;
  var sumMin=0;
  var nb=0;
  $('.checkValue_c1_max_stress').each( function (i) {
    if ($(this).html() !=='') {
      sumMax+=parseFloat($(this).html().replace(/ /g,''));
      nb+=1;
    }
  } );
  $('.checkValue_c1_min_stress').each( function (i) {
    if ($(this).html() !=='') {
      sumMin+=parseFloat($(this).html().replace(/ /g,''));
    }
  } );

  tolerance= Math.max(Math.abs(sumMax/nb),Math.abs(sumMin/nb))*5/100;

  $('.checkValue_c1_max_stress').each( function (i) {
    if ($(this).html()>sumMax/nb + tolerance && $(this).html() !=='') {
      $(this).addClass("checkValue_max");
    }
    else if ($(this).html()<sumMax/nb - tolerance && $(this).html() !=='') {
      $(this).addClass("checkValue_min");
    }
    else {
      $(this).removeClass("checkValue_max");
      $(this).removeClass("checkValue_min");
    }
  });
  $('.checkValue_c1_min_stress').each( function (i) {
    if ($(this).html()>sumMin/nb + tolerance && $(this).html() !=='') {
      $(this).addClass("checkValue_max");
    }
    else if ($(this).html()<sumMin/nb - tolerance && $(this).html() !=='') {
      $(this).addClass("checkValue_min");
    }
    else {
      $(this).removeClass("checkValue_max");
      $(this).removeClass("checkValue_min");
    }
  });
}
function checkValueC2Stress() {
  var checkValueArray=['.checkValue_c2_max_stress','.checkValue_c2_min_stress','.checkValue_c2_max_stress','.checkValue_c2_min_stress'];

  var sumMax=0;
  var sumMin=0;
  var nb=0;
  $('.checkValue_c2_max_stress').each( function (i) {
    if ($(this).html() !=='') {
      sumMax+=parseFloat($(this).html().replace(/ /g,''));
      nb+=1;
    }
  } );
  $('.checkValue_c2_min_stress').each( function (i) {
    if ($(this).html() !=='') {
      sumMin+=parseFloat($(this).html().replace(/ /g,''));
    }
  } );

  tolerance= Math.max(Math.abs(sumMax/nb),Math.abs(sumMin/nb))*5/100;

  $('.checkValue_c2_max_stress').each( function (i) {
    if ($(this).html()>sumMax/nb + tolerance && $(this).html() !=='') {
      $(this).addClass("checkValue_max");
    }
    else if ($(this).html()<sumMax/nb - tolerance && $(this).html() !=='') {
      $(this).addClass("checkValue_min");
    }
    else {
      $(this).removeClass("checkValue_max");
      $(this).removeClass("checkValue_min");
    }
  });
  $('.checkValue_c2_min_stress').each( function (i) {
    if ($(this).html()>sumMin/nb + tolerance && $(this).html() !=='') {
      $(this).addClass("checkValue_max");
    }
    else if ($(this).html()<sumMin/nb - tolerance && $(this).html() !=='') {
      $(this).addClass("checkValue_min");
    }
    else {
      $(this).removeClass("checkValue_max");
      $(this).removeClass("checkValue_min");
    }
  });
}


function addCommas(nStr)  { //fonction espace millier
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ' ' + '$2');
	}
	return x1 + x2;
}

$('.decimal0').each( function (i) { //ajouter 2 digit sur le nombre
  var num = parseFloat($(this).text());
  if (!isNaN(num)) {
  deci=num.toFixed(0)
  val=addCommas(deci);
  $(this).html(val);
}
});
$('.decimal1').each( function (i) { //ajouter 2 digit sur le nombre
  var num = parseFloat($(this).text());
    if (!isNaN(num)) {
  deci=num.toFixed(1)
  val=addCommas(deci);
  $(this).html(val);
}
});
$('.decimal2').each( function (i) { //ajouter 2 digit sur le nombre
  var num = parseFloat($(this).text());
    if (!isNaN(num)) {
  deci=num.toFixed(2)
  val=addCommas(deci);
  $(this).html(val);
}
});
$('.decimal3').each( function (i) { //ajouter 2 digit sur le nombre
  var num = parseFloat($(this).text());
    if (!isNaN(num)) {
  deci=num.toFixed(3)
  val=addCommas(deci);
  $(this).html(val);
}
});
