
idJob = ($('#table_ep').attr('data-idJob'));


var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {


  // Setup - add a text input to each footer cell
  $('#table_ep tfoot th').each( function (i) {
    var title = $('#table_ep thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );


  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-splitEprouvetteConsigne.php",
      type: "POST",
      data: {"idJob" : idJob}
    },
    table: "#table_ep",
    template: '#customForm',
    fields: [
      {       label: "eprouvettes.id_eprouvette",       name: "eprouvettes.id_eprouvette"     },
      {       label: "master_eprouvettes.prefixe",       name: "master_eprouvettes.prefixe"     },
      {       label: "master_eprouvettes.nom_eprouvette",       name: "master_eprouvettes.nom_eprouvette"     },
      {       label: "eprouvettes.n_essai",       name: "eprouvettes.n_essai"     },
      {       label: "eprouvettes.c_temperature",       name: "eprouvettes.c_temperature"     },
      {       label: "eprouvettes.c_frequence",       name: "eprouvettes.c_frequence"     },
      {       label: "eprouvettes.c_cycle_STL",       name: "eprouvettes.c_cycle_STL"     },
      {       label: "eprouvettes.c_frequence_STL",       name: "eprouvettes.c_frequence_STL"     },
      {       label: "eprouvettes.c_type_1_val",       name: "eprouvettes.c_type_1_val"     },
      {       label: "eprouvettes.c_type_2_val",       name: "eprouvettes.c_type_2_val"     },
      {       label: "eprouvettes.Cycle_min",       name: "eprouvettes.Cycle_min"     },
      {       label: "eprouvettes.runout",       name: "eprouvettes.runout"     },
      {       label: "eprouvettes.cycle_estime",       name: "eprouvettes.cycle_estime"     },
      {       label: "",       name: "eprouvettes.c_commentaire", type:  "textarea",     },
      {       label: "eprouvettes.c_checked",       name: "eprouvettes.c_checked"     },
      {       label: "enregistrementessais.n_fichier",       name: "enregistrementessais.n_fichier"     },
      {       label: "machines.machine",       name: "machines.machine"     },
      {       label: "enregistrementessais.date",       name: "enregistrementessais.date"     },
      {       label: "eprouvettes.waveform",       name: "eprouvettes.waveform"     },
      {       label: "eprouvettes.Cycle_STL",       name: "eprouvettes.Cycle_STL"     },
      {       label: "eprouvettes.Cycle_final",       name: "eprouvettes.Cycle_final"     },
      {       label: "eprouvettes.Rupture",       name: "eprouvettes.Rupture"     },
      {       label: "eprouvettes.Fracture",       name: "eprouvettes.Fracture"     }
    ]
  } );


  var table = $('#table_ep').DataTable( {
    ajax: {
      url : "controller/editor-splitEprouvetteConsigne.php",
      type: "POST",
      data: {"idJob" : idJob}
    },
    columns: [
      { data: "master_eprouvettes.id_master_eprouvette" },
      { data: "master_eprouvettes.prefixe" },
      { data: "master_eprouvettes.nom_eprouvette" },
      { data: "eprouvettes.c_temperature" },
      { data: "eprouvettes.c_frequence" },
      { data: "eprouvettes.c_cycle_STL" },
      { data: "eprouvettes.c_frequence_STL" },
      { data: "eprouvettes.c_type_1_val" },
      { data: "eprouvettes.c_type_2_val" },
      { data: "eprouvettes.Cycle_min" },
      { data: "eprouvettes.runout" },
      { data: "eprouvettes.cycle_estime" },
      {  data: "eprouvettes.c_commentaire",
      render : function(data, type, full, meta){
        test=data+"a";
        return type === 'display' && test.length > 5 ?data.substr(0,5) + '[...]' : data;
      }},
      { data: "eprouvettes.n_essai" },
      { data: "enregistrementessais.n_fichier" },
      { data: "eprouvettes.Cycle_final" }
    ],
    scrollY: '49vh',
    scrollCollapse: true,
    "scrollX": true,
    paging: false,
    info: false,
    fixedColumns:   {leftColumns: 3},
    columnDefs: [
      {
        "targets": [ 0 ],
        "visible": false,
        "searchable": false
      }
    ],
    autoFill: {
      columns: [3, 4, 5, 6, 7, 8, 9, 10, 11],
      editor:  editor
    },
    keys: {
      columns: [3, 4, 5, 6, 7, 8, 9, 10, 11],
      editor:  editor
    },
    select: {
      style:    'os',
      blurable: true
    }
  } );

  $('#table_ep').on( 'click', 'tbody td', function (e) {
    var index = $(this).index();

    if ( index === 11 ) {
      editor.bubble( this,
        ['eprouvettes.c_commentaire'],
        { title: 'Order Comments :' ,
        submitOnBlur: true,
        buttons: false
      }
    );
  }
}
);





$('#container').css('display', 'block');
table.columns.adjust().draw();

// Filter event handler
$( table.table().container() ).on( 'keyup', 'tfoot input', function () {
  table
  .column( $(this).data('index') )
  .search( this.value )
  .draw();
} );

document.getElementById("table_ep_filter").style.display = "none";



} );

function save() {
  $.ajax({
    type: "POST",
    url: 'controller/updateSplitCommentaire.php',
    dataType: "json",
    data:  {
      "id_tbljob" : idJob,
      "tbljob_commentaire" : $("textarea[name='tbljob_commentaire']").val()
    }
    ,
    success : function(data, statut){
      location.assign("index.php?page=split&id_tbljob="+$("#id_tbljob").val());
    },
    error : function(resultat, statut, erreur) {
      console.log(Object.keys(resultat));
      alert('ERREUR lors de la modification des données du split. Veuillez prevenir au plus vite le responsable SI. \n Sauf si vous venez de valider une non modification.');
    }
  });
}
