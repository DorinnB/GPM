
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
      url : "controller/editor-splitEprouvetteValue.php",
      type: "POST",
      data: {"idJob" : idJob}
    },
    table: "#table_ep",
    //template: '#customForm',
    fields: [
      {       label: 'Prefixe',       name: 'master_eprouvettes.prefixe', type:  "readonly"     },
      {       label: 'ID',       name: 'master_eprouvettes.nom_eprouvette', type:  "readonly"     },
      {       label: 'Temperature',       name: 'eprouvettes.c_temperature'     },
      {       label: 'Frequency',       name: 'eprouvettes.c_frequence'     },
      {       label: 'STL Cycle',       name: 'eprouvettes.c_cycle_STL'     },
      {       label: 'Load Frequency',       name: 'eprouvettes.c_frequence_STL'     },
      {       label: 'Consigne 1',       name: 'eprouvettes.c_type_1_val'     },
      {       label: 'Consigne 2',       name: 'eprouvettes.c_type_2_val'     },
      {       label: 'Cycle Min',       name: 'eprouvettes.Cycle_min'     },
      {       label: 'Runout',       name: 'eprouvettes.runout'     },
      {       label: 'Estimated cycle',       name: 'eprouvettes.cycle_estime'     },
      {       label: 'Order Comment',       name: 'eprouvettes.c_commentaire'     },
      {       label: 'Lab Observation',       name: 'eprouvettes.d_commentaire'     },
      {       label: 'Quality Review',       name: 'eprouvettes.flag_qualite'     },
      {       label: 'Quality Observation',       name: 'eprouvettes.q_commentaire'     },
      {       label: 'Test N°',       name: 'eprouvettes.n_essai'     },
      {       label: 'Files N°',       name: 'enregistrementessais.n_fichier'     },
      {       label: 'Dim 1',       name: 'eprouvettes.dim_1'     },
      {       label: 'Dim 2',       name: 'eprouvettes.dim_2'     },
      {       label: 'Dim 3',       name: 'eprouvettes.dim_3'     },
      {       label: 'Machine',       name: 'machines.machine'     },
      {       label: 'Date of Test',       name: 'enregistrementessais.date'     },
      {       label: 'Waveform',       name: 'eprouvettes.waveform'     },
      {       label: 'STL Cycles',       name: 'eprouvettes.Cycle_STL'     },
      {       label: 'Final Cycles',       name: 'eprouvettes.Cycle_final'     },
      {       label: 'Rupture',       name: 'eprouvettes.Rupture'     },
      {       label: 'Fracture',       name: 'eprouvettes.Fracture'     },
      {       label: 'Test Duration',       name: 'eprouvettes.temps_essais'     },
      {       label: 'Dilatation',       name: 'eprouvettes.dilatation'     },
      {       label: 'E_RT',       name: 'eprouvettes.E_RT'     },
      {       label: 'c1_E_montant',       name: 'eprouvettes.c1_E_montant'     },
      {       label: 'c1_max_strain',       name: 'eprouvettes.c1_max_strain'     },
      {       label: 'c1_min_strain',       name: 'eprouvettes.c1_min_strain'     },
      {       label: 'c1_max_stress',       name: 'eprouvettes.c1_max_stress'     },
      {       label: 'c1_min_stress',       name: 'eprouvettes.c1_min_stress'     },
      {       label: 'c2_cycle',       name: 'eprouvettes.c2_cycle'     },
      {       label: 'c2_E_montant',       name: 'eprouvettes.c2_E_montant'     },
      {       label: 'c2_max_stress',       name: 'eprouvettes.c2_max_stress'     },
      {       label: 'c2_min_stress',       name: 'eprouvettes.c2_min_stress'     },
      {       label: 'c2_max_strain',       name: 'eprouvettes.c2_max_strain'     },
      {       label: 'c2_min_strain',       name: 'eprouvettes.c2_min_strain'     },
      {       label: 'c2_calc_inelastic_strain',       name: 'eprouvettes.c2_calc_inelastic_strain'     },
      {       label: 'c2_meas_inelastic_strain',       name: 'eprouvettes.c2_meas_inelastic_strain'     },
      {       label: 'Ni',       name: 'eprouvettes.Ni'     },
      {       label: 'Nf75',       name: 'eprouvettes.Nf75'     },
      {       label: 'CurrentBlock',       name: 'eprouvettes.currentBlock'     },
      {       label:     "Check Value:",
                  name:      "eprouvettes.d_checked",
                  type:      "checkbox",
                  separator: "|",
                  options:   [
                      { label: '', value: 1 }
                  ]
              }
    ]
  } );

  var table = $('#table_ep').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-splitEprouvetteValue.php",
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
      {  data: "eprouvettes.d_commentaire",
      render : function(data, type, full, meta){
        test=data+"a";
        return type === 'display' && test.length > 5 ?data.substr(0,5) + '[...]' : data;
      }},
      { data: "eprouvettes.flag_qualite" },
      {  data: "eprouvettes.q_commentaire",
      render : function(data, type, full, meta){
        test=data+"a";
        return type === 'display' && test.length > 5 ?data.substr(0,5) + '[...]' : data;
      }},
      { data: "eprouvettes.n_essai" },
      { data: "enregistrementessais.n_fichier" },
      { data: "eprouvettes.dim_1" },
      { data: "eprouvettes.dim_2" },
      { data: "eprouvettes.dim_3" },
      { data: "machines.machine" },
      { data: "enregistrementessais.date" },
      { data: "eprouvettes.waveform" },
      { data: "eprouvettes.Cycle_STL" },
      { data: "eprouvettes.Cycle_final" },
      { data: "eprouvettes.Rupture" },
      { data: "eprouvettes.Fracture" },
      { data: "eprouvettes.temps_essais" },
      { data: "eprouvettes.dilatation" },
      { data: "eprouvettes.E_RT" },
      { data: "eprouvettes.c1_E_montant" },
      { data: "eprouvettes.c1_max_strain" },
      { data: "eprouvettes.c1_min_strain" },
      { data: "eprouvettes.c1_max_stress" },
      { data: "eprouvettes.c1_min_stress" },
      { data: "eprouvettes.c2_cycle" },
      { data: "eprouvettes.c2_E_montant" },
      { data: "eprouvettes.c2_max_stress" },
      { data: "eprouvettes.c2_min_stress" },
      { data: "eprouvettes.c2_max_strain" },
      { data: "eprouvettes.c2_min_strain" },
      { data: "eprouvettes.c2_calc_inelastic_strain" },
      { data: "eprouvettes.c2_meas_inelastic_strain" },
      { data: "eprouvettes.Ni" },
      { data: "eprouvettes.Nf75" }
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
    select: {
      style:    'os',
      selector: 'td',
      blurable: true
    },
    buttons: [
      {
        extend: "edit",
        editor: editor,
        formButtons: [
          'Edit',
          { label: 'Cancel', fn: function () { this.close(); } }
        ]
      }
    ]
  } );

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
    url: 'controller/updateSplitQuality.php',
    dataType: "json",
    data:  {
      "id_tbljob" : idJob,
      "tbljob_commentaire_qualite" : $("textarea[name='tbljob_commentaire_qualite']").val()
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
