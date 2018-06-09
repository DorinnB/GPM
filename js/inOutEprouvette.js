$(document).ready(function() {
  // Setup - add a text input to each footer cell
  $('#table_ep tfoot th').each( function (i) {
    var title = $('#table_ep thead th').eq( $(this).index() ).text();
    $(this).html( '<input type="text" placeholder="" data-index="'+i+'" style="width:100%;"/>' );
  } );

  // DataTable
  var table = $('#table_ep').DataTable( {
    scrollY:        "50vh",
    scrollX:        true,
    scrollCollapse: true,
    paging:         false,
    info: false,
    fixedColumns:   {leftColumns: 4},
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

  // Filter event handler
  $( table.table().container() ).on( 'keyup', 'tfoot input', function () {
    table
    .column( $(this).data('index') )
    .search( this.value )
    .draw();
  } );
  document.getElementById("table_ep_filter").style.display = "none";
} );




//pour chaque click ou selection, on update la date
$('#table_ep').selectable ({
  filter:"td.selectable",
  distance:0,
  selected: function( event, ui ) {
    if ($(ui.selected).attr('data-id')) {
      if ($(ui.selected).html()==$('#dateInOut').val()) {
        if ($(ui.selected).attr('data-oldValue')==$('#dateInOut').val()) {
          $(ui.selected).html('').css("background-color", "rgb(128, 0, 128)");
        }
        else {
          $(ui.selected).html($(ui.selected).attr('data-oldValue')).css("background-color", "inherit");
        }
      }
      else {
        if ($(ui.selected).attr('data-oldValue')==$('#dateInOut').val()) {
          $(ui.selected).html($('#dateInOut').val()).css("background-color", "inherit");
        }
        else {
          $(ui.selected).html($('#dateInOut').val()).css("background-color", "rgb(128, 0, 128)");
        }
      }
    }
    else if ($(ui.selected).attr('data-idMaster')) {      if ($(ui.selected).html()==$('#dateInOut').val()) {
      if ($(ui.selected).attr('data-oldValue')==$('#dateInOut').val()) {
        $(ui.selected).html('').css("background-color", "rgb(128, 0, 128)");
      }
      else {
        $(ui.selected).html($(ui.selected).attr('data-oldValue')).css("background-color", "inherit");
      }
    }
    else {
      if ($(ui.selected).attr('data-oldValue')==$('#dateInOut').val()) {
        $(ui.selected).html($('#dateInOut').val()).css("background-color", "inherit");
      }
      else {
        $(ui.selected).html($('#dateInOut').val()).css("background-color", "rgb(128, 0, 128)");
      }
    }
  }
}

});
