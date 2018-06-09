
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
      url : "controller/editor-splitEprouvetteConsigne_IQC.php",
      type: "POST",
      data: {"idJob" : idJob}
    },
    table: "#table_ep",
    //template: '#customForm',
    fields: [
      {       label: 'Prefixe',       name: 'master_eprouvettes.prefixe', type:  "readonly",     },
      {       label: 'ID',       name: 'master_eprouvettes.nom_eprouvette', type:  "readonly",     },
      {       label: 'Dim 1',       name: 'annexe_iqc.dim1'     },
      {       label: 'Dim 2',       name: 'annexe_iqc.dim2'     },
      {       label: 'Dim 3',       name: 'annexe_iqc.dim3'     },
      {       label: 'Order Comment',       name: 'eprouvettes.c_commentaire'     },
      {       label: 'Lab Observation',       name: 'eprouvettes.d_commentaire'     },
      {       label: 'Quality Review',       name: 'eprouvettes.flag_qualite'     },
      {       label: 'Quality Observation',       name: 'eprouvettes.q_commentaire'     },

      {       label: 'marquage',       name: 'annexe_iqc.marquage',
      type:  "radio",
      options: [
        { label: "No", value: 0 },
        { label: "Yes",  value: 1 },
        { label: "Cancel",  value: "" }
      ],
      def: ""
    }     ,
    {       label: 'surface',       name: 'annexe_iqc.surface',
    type:  "radio",
    options: [
      { label: "No", value: 0 },
      { label: "Yes",  value: 1 },
      { label: "Cancel",  value: "" }
    ],
    def: ""
  }     ,
  {       label: 'grenaillage',       name: 'annexe_iqc.grenaillage',
  type:  "radio",
  options: [
    { label: "No", value: 0 },
    { label: "Yes",  value: 1 },
    { label: "Cancel",  value: "" }
  ],
  def: ""
}     ,
{       label: 'revetement',       name: 'annexe_iqc.revetement',
type:  "radio",
options: [
  { label: "No", value: 0 },
  { label: "Yes",  value: 1 },
  { label: "Cancel",  value: "" }
],
def: ""
}     ,
{       label: 'protection',       name: 'annexe_iqc.protection',
type:  "radio",
options: [
  { label: "No", value: 0 },
  { label: "Yes",  value: 1 },
  { label: "Cancel",  value: "" }
],
def: ""
}    ,
{       label: 'autre',       name: 'annexe_iqc.autre'     }

]
} );

var table = $('#table_ep').DataTable( {
  ajax: {
    url : "controller/editor-splitEprouvetteConsigne_IQC.php",
    type: "POST",
    data: {"idJob" : idJob}
  },
  columns: [
    { data: "master_eprouvettes.id_master_eprouvette" },
    { data: "master_eprouvettes.prefixe" },
    { data: "master_eprouvettes.nom_eprouvette" },
    { data: "annexe_iqc.dim1" },
    { data: "annexe_iqc.dim2" },
    { data: "annexe_iqc.dim3" },
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
    { data: 'annexe_iqc.marquage',
    "render": function (val, type, row) {
      return val == 0 ? "No" : (val == 1 ? "Yes" : "");
    } },
    { data: 'annexe_iqc.surface',
    "render": function (val, type, row) {
      return val == 0 ? "No" : (val == 1 ? "Yes" : "");
    } },
    { data: 'annexe_iqc.grenaillage',
    "render": function (val, type, row) {
      return val == 0 ? "No" : (val == 1 ? "Yes" : "");
    } },
    { data: 'annexe_iqc.revetement',
    "render": function (val, type, row) {
      return val == 0 ? "No" : (val == 1 ? "Yes" : "");
    } },
    { data: 'annexe_iqc.protection',
    "render": function (val, type, row) {
      return val == 0 ? "No" : (val == 1 ? "Yes" : "");
    } },
    { data: 'annexe_iqc.autre' }

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
    columns: [3, 4, 5, 6, 10,11,12,13,14,15],
    editor:  editor
  },
  keys: {
    columns: [3, 4, 5, 6, 10,11,12,13,14,15],
    editor:  editor
  },
  select: {
    style:    'os',
    blurable: true
  }
} );

$('#table_ep').on( 'click', 'tbody td', function (e) {
  var index = $(this).index();

  if ( index === 5 ) {
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
