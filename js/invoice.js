$(document).ready(function() {

  var tableAccounting = $('#table_accounting').DataTable({


  order: [ 2, "DESC" ],
  scrollY: '75vh',
  scrollCollapse: true,
  paging: false,
  scrollX: true,

  select: true,
  buttons: [
    {
      extend: 'collection',
      text: 'Export',
      buttons: [
        'copy',
        'excel',
        'csv',
        'pdf',
        'print'
      ]
    }
  ]
});


tableAccounting
.buttons()
.container()
.appendTo( '#button' );



$('#container').css('display', 'block');


// Setup - add a text input to each footer cell
$(".dataTables_scrollFootInner tfoot th").each(function() {
  var title = $(this).text();
  $(this).html('<input type="text" placeholder="' + title + '" / style="width:100%">');
});

tableAccounting.columns().every(function() {
  var that = this;

  $('input', this.footer()).on('keyup change', function() {
    if (that.search() !== this.value) {
      that
      .search(this.value)
      .draw();
    }
  });
});

document.getElementById("table_accounting_filter").style.display = "none";



tableAccounting.columns.adjust().draw();
});
