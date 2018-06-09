$(document).ready(function() {

    var tableJob = $('#table_id').DataTable({
        "columnDefs": [{
            targets: [1],
            orderData: [1, 6]
        }, {
            targets: [6],
            orderData: [6, 7]
        }],
        "order": [[ 6, "desc" ],[7, "asc" ]],
        scrollY: '79vh',
        scrollCollapse: true,
        paging: false,
        info: false
    });

    $('#container').css('display', 'block');
    tableJob.columns.adjust().draw();

    // Setup - add a text input to each footer cell
    $(".dataTables_scrollFootInner tfoot th").each(function() {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" / style="width:100%">');
    });

    tableJob.columns().every(function() {
        var that = this;

        $('input', this.footer()).on('keyup change', function() {
            if (that.search() !== this.value) {
                that
                    .search(this.value)
                    .draw();
            }
        });
    });

    document.getElementById("table_id_filter").style.display = "none";



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
      $("#wrapper").on(transitionEvent,
                  function(event) {
        $('#table_id').DataTable().draw();
      });



    $('#table_id tr td').each(function() {
        if ($(this).attr("color-statut") >= 100) $(this).css('background-color', 'rgb(37, 204, 134)');
        if ($(this).attr("color-statut") < 100) $(this).css('background-color', 'rgb(37, 204, 134)');
        if ($(this).attr("color-statut") < 90) $(this).css('background-color', 'rgb(37, 204, 99)');
        if ($(this).attr("color-statut") < 80) $(this).css('background-color', 'rgb(45, 204, 37)');
        if ($(this).attr("color-statut") < 70) $(this).css('background-color', 'rgb(95, 204, 37)');
        if ($(this).attr("color-statut") < 60) $(this).css('background-color', 'rgb(166, 204, 37)');
        if ($(this).attr("color-statut") < 50) $(this).css('background-color', 'rgb(181, 204, 37)');
        if ($(this).attr("color-statut") < 40) $(this).css('background-color', 'rgb(204, 157, 37)');
        if ($(this).attr("color-statut") < 30) $(this).css('background-color', 'rgb(204, 112, 37)');
        if ($(this).attr("color-statut") < 30) $(this).css('background-color', 'rgb(204, 112, 37)');
        if ($(this).attr("color-statut") < 20) $(this).css('background-color', 'rgb(204, 72, 37)');
        if ($(this).attr("color-statut") <= 10) $(this).css('background-color', 'darkred');
    });

});
