
var editor; // use a global for the submit and return data rendering in the examples

$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-planningmodif.php",
      type: "POST"
    },
    table: "#table_planningModif",
    fields: [
      { label: "Who ?", name: "planning_modif.id_user", type:  "select", def: iduser  },
      {
        label:      'Date modified:',
        name:       'planning_modif.datemodif',
        type:       'date',
        def:        function () { return new Date(); },
        dateFormat: $.datepicker.ISO_8601
      },
      { label: "Type", name: "planning_modif.id_type" , type: "select"  },

      { label: "Quantity", name: "planning_modif.quantity"  },
      { label: "Last Modifier", name: "planning_modif.id_modifier", type:  "select", placeholder : "You"  },
      { label: "Validator", name: "planning_modif.id_validator", type: "hidden" },
      { label: "Comments", name: "planning_modif.comments",  type: "textarea" }

    ]
  } );
  editor.disable( ['planning_modif.id_modifier', 'planning_modif.id_validator'] );

  // Edit record
  $('#table_planningModif').on('click', 'a.editor_validation', function (e) {
    e.preventDefault();

    editor.edit( $(this).closest('tr'), {
      title: 'Edit record',
      buttons: 'Update'
    } );
  } );

  // Delete a record
  $('#table_planningModif').on('click', 'a.editor_cancel', function (e) {
    e.preventDefault();

    editor.remove( $(this).closest('tr'), {
      title: 'Delete record',
      message: 'Are you sure you wish to remove this record?',
      buttons: 'Delete'
    } );
  } );




  // Setup - add a text input to each footer cell
  $('#table_planningModif tfoot th').each( function (i) {
    var title = $('#table_planningModif thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" style="width:100%;"/>' );
  } );


  var table = $('#table_planningModif').DataTable( {
    dom: "Bfrtip",
    ajax: {
      url : "controller/editor-planningmodif.php",
      type: "POST"
    },
    columns: [
      { data: "planning_modif.id_planning_modif" },
      { data: "user.technicien" },
      { data: "planning_modif.datemodif" },
      { data: "planning_types.planning_type" },
      { data: "planning_modif.quantity" },
      { data: "modifier.technicien" },
      { data: "planning_modif.comments" },
      { data: null, render: function ( data, type, row ) {
        // Combine the first and last names into a single table field
        if (data.planning_modif.id_validator==null) {
          return 'Awaiting';
        }
        else if (data.planning_modif.id_validator<0 ) {
          return '-&nbsp;'+data.validator.technicien
        }
        else {
          return '&nbsp;&nbsp;'+data.validator.technicien
        }
      } }
    ],
    createdRow: function( row, data, dataIndex ) {
      if ( data.planning_modif.id_validator  == null ) {
          $(row).addClass('awaiting')
      }
      else if (data.planning_modif.id_validator.startsWith('-')) {
            $(row).addClass('refused')
      }
      else {
            $(row).addClass('validated')
      }
    },
    scrollY: '65vh',
    scrollCollapse: true,
    paging: false,
    select: true,
    buttons: [
      { extend: "create", editor: editor },
      { extend: "edit",   editor: editor },
      {
        extend: "selected",
        text: 'Delete',
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
            title: 'Delete',
            message: rows.length === 1 ?
            'Are you sure you wish to delete this row?' :
            'Are you sure you wish to delete these '+rows.length+' rows',
            buttons: 'Delete'
          } )
          .val( 'planning_modif.id_validator', -iduser );
        }
      },
      {
        extend: "selected",
        text: 'Validation',
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
            title: 'Validation',
            message: rows.length === 1 ?
            'Are you sure you wish to validate this row?' :
            'Are you sure you wish to validate these '+rows.length+' rows',
            buttons: 'Validation'
          } )
          .val( 'planning_modif.id_validator', iduser );
        }
      }
    ]
  } );



  table
  .column( '7' )
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


} );
