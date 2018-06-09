$(document).ready(function() {

  var table = $('#table_GestionEp').DataTable({
    scrollY: '30vh',
    scrollCollapse: true,
    "scrollX": true,
    paging: false,
    info: false,
    order: [[ 0, "desc" ]],
    columnDefs: [
      {
        "targets": [ 0 ],
        "visible": false,
        "searchable": false
      }]
  });


  // Setup - add a text input to each footer cell
  $(".dataTables_scrollFootInner tfoot th, .DTFC_LeftFootWrapper tfoot th").each(function() {
    var title = $(this).text();
    $(this).html('<input type="text" placeholder="' + title + '" / style="width:100%">');
  });

  table.columns().every(function() {
    var that = this;

    $('input', this.footer()).on('keyup change', function() {
      if (that.search() !== this.value) {
        that
        .search(this.value)
        .draw();
      }
    });
  });

  document.getElementById("table_GestionEp_filter").style.display = "none";


  table.columns.adjust().draw();

});




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
