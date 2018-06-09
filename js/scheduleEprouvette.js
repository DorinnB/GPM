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
    fixedColumns:   {leftColumns: 1},
    order: [ 0, "asc" ]
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
      if ($(ui.selected).html()==$('#dateSchedule').val()) {
        if ($(ui.selected).attr('data-oldValue')==$('#dateSchedule').val()) {
          $(ui.selected).html('').css("background-color", "rgb(128, 0, 128)");
        }
        else {
          $(ui.selected).html($(ui.selected).attr('data-oldValue')).css("background-color", "inherit");
        }
      }
      else {
        if ($(ui.selected).attr('data-oldValue')==$('#dateSchedule').val()) {
          $(ui.selected).html($('#dateSchedule').val()).css("background-color", "inherit");
        }
        else {
          $(ui.selected).html($('#dateSchedule').val()).css("background-color", "rgb(128, 0, 128)");
        }
      }
      //On ecrit les memes informations (et css) pour toutes les autres cases du meme split.
      $( ".selectable" ).each(function(index, e) {
        if ($(ui.selected).attr('data-id')==$(e).attr('data-id') && $(ui.selected).attr('data-IO')==$(e).attr('data-IO') && $(ui.selected)!=$(e)) {
          $(e).html($(ui.selected).html());
          $(e).css("background-color",$(ui.selected).css("background-color"));
        }
      });
      $( ".selectableTitle" ).each(function(index, e) {
        if ($(ui.selected).attr('data-id')==$(e).attr('data-id') && $(ui.selected).attr('data-IO')==$(e).attr('data-IO')) {
          $(e).attr('data-value',$(ui.selected).html());
        }
      });
    }
    else if ($(ui.selected).attr('data-idJob')) {
      if ($(ui.selected).html()==$('#dateSchedule').val()) {
        if ($(ui.selected).attr('data-oldValue')==$('#dateSchedule').val()) {
          $(ui.selected).html('').css("background-color", "rgb(128, 0, 128)");
        }
        else {
          $(ui.selected).html($(ui.selected).attr('data-oldValue')).css("background-color", "inherit");
        }
      }
      else {
        if ($(ui.selected).attr('data-oldValue')==$('#dateSchedule').val()) {
          $(ui.selected).html($('#dateSchedule').val()).css("background-color", "inherit");
        }
        else {
          $(ui.selected).html($('#dateSchedule').val()).css("background-color", "rgb(128, 0, 128)");
        }
      }
      //On ecrit les mêmes informations (et css) pour toutes les autres cases du meme split.
      $( ".selectable" ).each(function(index, e) {
        if ($(ui.selected).attr('data-idJob')==$(e).attr('data-idJob') && $(ui.selected).attr('data-IO')==$(e).attr('data-IO') && $(ui.selected)!=$(e)) {
          $(e).html($(ui.selected).html());
          $(e).css("background-color",$(ui.selected).css("background-color"));
        }
      });
      $( ".selectableTitle" ).each(function(index, e) {
        if ($(ui.selected).attr('data-idJob')==$(e).attr('data-idJob') && $(ui.selected).attr('data-IO')==$(e).attr('data-IO')) {
          $(e).attr('data-value',$(ui.selected).html());
        }
      });
    }
  }

} );
