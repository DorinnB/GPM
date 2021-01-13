

$(document).ready(function() {

  var table = $('#table_prodIndicator').DataTable( {
    dom: "rtp",
    scrollY: '65vh',
    scrollX : true,
    scrollCollapse: true,
    paging: false,
    info: true
  } );




  function addCommas(nStr)  { //fonction espace millier
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + ' ' + '$2');
    }
    return x1 + x2;
  }


  $('.decimal2').each( function (i) { //ajouter 2 digit sur le nombre
    var num = parseFloat($(this).text());
    if (!isNaN(num)) {
      deci=num.toFixed(2)
      val=addCommas(deci);
      $(this).html(val);
    }
  });



});
