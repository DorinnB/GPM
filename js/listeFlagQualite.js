
var editorQ; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editorQ = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-listeFlagQualite.php",
      type: "POST"
    },
    table: "#table_listeFlagQualite",
    fields: [
      { label: "Customer",       name: "info_jobs.customer", type:  "readonly"     },
      { label: "Job",       name: "info_jobs.job", type:  "readonly" },
      { label: "Split",       name: "tbljobs.split", type:  "readonly" },
      //{ label: "File Number",       name: "enregistrementessais.n_fichier", type:  "readonly" },
      { label: "Frame",       name: "machines.machine", type:  "readonly" },
      { label: "Lab Comments",       name: "eprouvettes.d_commentaire", type: "textarea" },
      { label: "Quality Comment",       name: "eprouvettes.q_commentaire",  type: "textarea"},
      { label: "Test Valid ?",       name: "eprouvettes.valid",
      type: "radio",
      options: [
        { label: "Valid", value: 1 },
        { label: "Unvalid", value: 2 },
        { label: "Void", value: 0 }      ]
      },
      { label: "Unvalidity",       name: "eprouvettes.unvalidity",
      type:  "radio",
      options: [
        { label: "N/A", value: null},
        { label: "SI", value: "SI"},
        { label: "EC", value: "EC"},
        { label: "ENC", value: "ENC"},
        { label: "EVC", value: "EVC"}
      ]},
      { label: "Valid up to ? (if EVC)",       name: "eprouvettes.validupto"},
      { label: "Reason:",              name: "incident_causes[].id_incident_cause",
      type: "select",
      multiple: true    },
      { label: "Flag Quality",       name: "eprouvettes.flag_qualite",
      type:  "radio",
      options: [
        { label: "Cancel", value: 0},
        { label: "Warning", value: iduser},
        { label: "Close", value: -iduser}
      ]}
    ]
  } );

  // Setup - add a text input to each footer cell
  $('#table_listeFlagQualite tfoot th').each( function (i) {
    var title = $('#table_listeFlagQualite thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );



  var table = $('#table_listeFlagQualite').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-listeFlagQualite.php",
      type: "POST",
      data: {"filtre" : filtre}
    },
    order: [[ 1, "desc" ]],
    columns: [
      { data: "info_jobs.customer" },
      { data: null,
        render : function(data, type, full, meta){
          return '<a href="index.php?page=split&id_tbljob='+data.tbljobs.id_tbljob+'">'+data.info_jobs.job+'</a>';
        }},
        { data: "tbljobs.split" },
        { data: null,
          render : function(data, type, full, meta){
            return '<a href="#" class="open-GestionEp" data-toggle="modal" data-target="#gestionEp" data-id="'+data.eprouvettes.id_eprouvette+'" onclick="gestionEp('+data.eprouvettes.id_eprouvette+');">'+data.enregistrementessais.n_fichier+'</a>';
          }},
          { data: "op.technicien", "visible": false },
          { data: "chk.technicien", "visible": false },
          { data: "machines.machine" },
          { data: "enregistrementessais.date" },
          { data: "eprouvettes.d_commentaire", width: "40%" },
          { data: "eprouvettes.flag_qualite" },
          { data: "eprouvettes.q_commentaire", width: "30%" },
          { data: "eprouvettes.valid" },
          { data: "TDR_types", render: "[, ].TDR_type" },
          { data: "incident_causes", render: "[, ].incident_cause" },
          { data: "test_type.test_type_abbr" }
        ],
        scrollY: '70vh',
        scrollCollapse: true,
        paging: false,
        select: {
          style:    'os',
          blurable: true
        },
        buttons: [
          {
            extend: "edit",
            editor: editorQ,
            formButtons: [
              'Edit',
              { label: 'Cancel', fn: function () { this.close(); } }
            ]
          },
          {
            extend: 'excel',
            text: 'Export Excel',
            exportOptions: {
            }
          }
        ]
      } );






      $('#container').css('display', 'block');
      table.columns.adjust().draw();

      // Filter event handler
      $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
        if (this.value.substr(0,1)=='!') {
          search='^((?!'+this.value.substring(1)+').)*$';
        }
        else {
          search=this.value;
        }
        table
        .column( $(this).data('index') )
        .search( search, true, false )
        .draw();
      } );

      //table.columns.adjust().draw();






    } );


    function gestionEp(idEp) {
      $('#gestionEp').load('controller/splitGestionEp-controller.php?idEp='+idEp);
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
        $('#table_listeFlagQualite').DataTable().draw();
      });
