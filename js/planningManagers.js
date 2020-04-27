var datevalidation;
datevalidation = new Date();
datevalidation = datevalidation.getUTCFullYear() + '-' +
('00' + (datevalidation.getUTCMonth()+1)).slice(-2) + '-' +
('00' + datevalidation.getUTCDate()).slice(-2) + ' ' +
('00' + datevalidation.getUTCHours()).slice(-2) + ':' +
('00' + datevalidation.getUTCMinutes()).slice(-2) + ':' +
('00' + datevalidation.getUTCSeconds()).slice(-2);

var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  $('[data-toggle="tooltipChanged"]').tooltip();
  $('[data-toggle="tooltipNOK"]').tooltip();

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-planningmodifManagers.php",
      type: "POST"
    },
    table: "#table_planningModif",
    submit: 'allIfChanged',
    fields: [

      { label: "Applicant", name: "planning_modif.id_modifier", type:  "select", placeholder : "You"  },

      { label: "Who ?", name: "planning_modif.id_user", type:  "select", def: iduser  },
      {
        label:      'Date modified:',
        name:       'planning_modif.datemodif',
        type:       'date',
        def:        function () { return new Date(); },
        dateFormat: $.datepicker.ISO_8601
      },
      { label: "Type of modification", name: "planning_modif.id_type" , type: "select"  },

      { label: "Quantity (j / hrs)", name: "planning_modif.quantity"  },
      { label: "Validator", name: "planning_modif.id_validator", type: "hidden" },
      { label: "Comments", name: "planning_modif.comments",  type: "textarea" },
      { label: "Date validation:", name: 'planning_modif.datevalidation' }

    ]
  } );
  editor.disable( ['planning_modif.id_modifier', 'planning_modif.id_validator', 'planning_modif.datevalidation'] );


  $('#table_planningModif').on( 'click', 'tbody td', function (e) {
    var index = $(this).index();

    if ( index === 7 ) {
      editor.bubble( this , {
        submit: 'allIfChanged'
      });
    }

  } );



  // Setup - add a text input to each footer cell
  $('#table_planningModif tfoot th').each( function (i) {
    var title = $('#table_planningModif thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );


  var table = $('#table_planningModif').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-planningmodifManagers.php",
      type: "POST"
    },
    columns: [
      { data: "planning_modif.id_planning_modif" },
      { data: "modifier.technicien" },
      { data: "planning_modif.dateinitiale" },
      { data: "user.technicien" },
      { data: "planning_modif.datemodif" },
      { data: "planning_types.planning_type" },
      { data: "planning_modif.quantity" },
      { data: "planning_modif.comments" },
      { data: null, render: function ( data, type, row ) {
        if (data.planning_modif.id_validator==null) {
          return 'Awaiting';
        }
        else if (data.planning_modif.id_validator<0 ) {
          return '-&nbsp;'+data.validator.technicien
        }
        else {
          return '&nbsp;&nbsp;'+data.validator.technicien
        }
      } },
      { data: "planning_modif.datevalidation" }
    ],
    createdRow: function( row, data, dataIndex ) {
      if ( data.planning_modif.id_validator  == null ) {
        $(row).addClass('awaiting');
      }
      else if (data.planning_modif.id_validator.startsWith('-')) {
        $(row).addClass('refused');
      }
      else {
        $(row).addClass('validated');
      }
    },
    scrollY: '40vh',
    scrollCollapse: true,
    paging: false,
    select: true,
    buttons:
    [
      { extend: "create", editor: editor, className: 'createSpace'},
      { extend: "selected", text: "Copy",
      enabled: false,
      className: 'buttons-copy btn-primary createSpace',
      action: function ( e, d, node, config ) {

        var mode = editor.mode();
        console.log( 'Editor form displayed for a '+mode+' action' );

        editor.edit( table.rows( {selected: true} ).indexes(), {
          title: 'Copy Request',
          buttons: 'Save'
        } );
        editor.mode( 'create' );
      }
    },
    {
      extend: "selected",
      text: 'Disapprovation',
      action: function ( e, dt, node, config ) {
        var rows = table.rows( {selected: true} ).indexes();
        editor
        .hide( editor.fields() )
        .one( 'close', function () {
          setTimeout( function () { // Wait for animation
            editor.show( editor.fields() );
          }, 500 );
        } )
        .edit( rows, {
          title: 'Disapprobation',
          message: rows.length === 1 ?
          'Are you sure you wish to disapprove this row?' :
          'Are you sure you wish to disapprove these '+rows.length+' rows',
          buttons: 'Disapprove'
        } )
        .val( 'planning_modif.id_validator', -iduser ).val( 'planning_modif.datevalidation', datevalidation );
      }
    },
    {
      extend: "selected",
      text: 'Validation',
      action: function ( e, dt, node, config )    {
        var rows = table.rows( {selected: true} ).indexes();
        editor
        .hide( editor.fields() )
        .one( 'close', function () {
          setTimeout( function () { // Wait for animation
            editor.show( editor.fields() );
          }, 500 );
        } )
        .edit( rows, {
          title: 'Validation',
          message: rows.length === 1 ?
          'Are you sure you wish to validate this row?' :
          'Are you sure you wish to validate these '+rows.length+' rows',
          buttons: 'Validate'
        } )
        .val( 'planning_modif.id_validator', iduser ).val( 'planning_modif.datevalidation', datevalidation );
      }
    }]
  } );

  document.getElementById("table_planningModif_filter").style.display = "none";



  editor.on( 'postEdit', function ( e, json, data ) { //recalcul de la class après modif (validé ou non)
    //console.log(data['planning_modif']['id_validator']);

    $('#'+data['DT_RowId']).removeClass("refused");
    $('#'+data['DT_RowId']).removeClass("validated");
    $('#'+data['DT_RowId']).removeClass("awaiting");

    if (data['planning_modif']['id_validator']>0) {
      $('#'+data['DT_RowId']).addClass("validated");
    }
    else if (data['planning_modif']['id_validator']<0) {
      $('#'+data['DT_RowId']).addClass("refused");
    }
    else {
      $('#'+data['DT_RowId']).addClass("awaiting");
    }


  } );


  table
  .column( '8' )
  .search( 'Awaiting' )
  .draw();


  $('#container').css('display', 'block');
  table.columns.adjust().draw();

  // Filter event handler
  $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
    table
    .column( $(this).data('index') )
    .search( this.value )
    .draw();
  } );

  //table.columns.adjust().draw();




  // DataTable
  var table2 = $('#table_planningUser').DataTable( {

    scrollX:        true,
    scrollCollapse: true,
    paging:         false,
    info: false,
    ordering: false,
    fixedColumns:   {leftColumns: 1}
  } );


  document.getElementById("table_planningUser_filter").style.display = "none";


  //déplacement et mise en couleur de la date de changement demandée
  $('#table_planningModif tbody').on( 'mouseover', 'td', function () {
    if (table.cell( this ).index() != null) {
      if (table.cell( this ).index().column==4) {
        row = table.row( this ).data();
        date = row.planning_modif.datemodif;
        cellule=$('#'+date);

        $('div.dataTables_scrollBody').scrollLeft(cellule.position().left-$( window ).width()/4) ;
        $(cellule).toggleClass('highlight');
      }
    }
  } );
  $('#table_planningModif tbody').on( 'mouseleave', 'td', function () {
    if (table.cell( this ).index() != null) {
      if (table.cell( this ).index().column==4) {
        row = table.row( this ).data();
        date = row.planning_modif.datemodif;
        cellule=$('#'+date);

        $(cellule).toggleClass('highlight');
      }
    }
  } );



} );
