var editor; // use a global for the submit and return data rendering in the examples


$(document).ready(function() {


  editor = new $.fn.dataTable.Editor( {
    ajax: "controller/editor-invoiceJob.php",        template: '#customForm',
    fields: [
      {
        label: "Invoice Number:",
        name:  "inv_number"
      },
      {
        label: "Job:",
        name:  "inv_job",
        def: function() {
          return $("#job").val();
        },
        type:  "readonly"
      },
      {
        label: "Invoice Date:",
        name: "inv_date",
        def: function () { return new Date(); },
        type: "datetime"
      },
      {
        label: "USD Rate:",
        name: "USDRate",
        def: "0.00"
      },
      {
        label: "MRSAS:",
        name:  "inv_mrsas",
        def: function() {
          return parseFloat(0+$('#UBRMRSAS').text()).toFixed(2);
        }
      },
      {
        label: "SubC:",
        name:  "inv_subc",
        def: function() {
          return parseFloat(0+$('#notInv_val_subc').text()).toFixed(2);
        }
      },
      {
        label: "TVA:",
        name: "inv_TVA",
        def: "0.00"
      },
      {
        label: "Invoice Total:",
        name: "inv_total",
        type:  "readonly"
      }
    ]
  } );

  $('#createInvoice').on( 'click', function () {
    editor
    .buttons( {
      label: "New invoice",
      fn: function () { this.submit(); }
    } )
    .create();
  } );

  //mise a jour de la somme total
  editor.dependent( 'inv_mrsas', function ( val, data, callback ) {
    inv_total=parseFloat(data['values']['inv_mrsas'])+parseFloat(data['values']['inv_subc'])+parseFloat(data['values']['inv_TVA']);
    $('#DTE_Field_inv_total').val(inv_total.toFixed(2));
  } );
  editor.dependent( 'inv_subc', function ( val, data, callback ) {
    inv_total=parseFloat(data['values']['inv_mrsas'])+parseFloat(data['values']['inv_subc'])+parseFloat(data['values']['inv_TVA']);
    $('#DTE_Field_inv_total').val(inv_total.toFixed(2));
  } );
  editor.dependent( 'inv_TVA', function ( val, data, callback ) {
    inv_total=parseFloat(data['values']['inv_mrsas'])+parseFloat(data['values']['inv_subc'])+parseFloat(data['values']['inv_TVA']);
    $('#DTE_Field_inv_total').val(inv_total.toFixed(2));
  } );





  //changement de l'icone print en save
  $(document).on('input', function() {
    $('#printInvoiceJob').css('display','none');
    $('#saveInvoiceJob').css('display','block');
  });


  newEntry=0; //nb de nouveau invoiceLine

  //Insertion InvoiceLine
  $(".addInvLine").change(function(e){

    //changement de l'icone print en save
    $('#printInvoiceJob').css('display','none');
    $('#saveInvoiceJob').css('display','block');


    //on récupère les valeurs
    var id_info_job = $(this).attr('data-id_info_job');
    var id_tbljob = $(this).attr('data-id_tbljob');
    var value = $('option:selected', this).attr('value');
    var prodCode = $('option:selected', this).attr('data-prodCode');
    var OpnCode = $('option:selected', this).attr('data-OpnCode');
    var type = $('option:selected', this).attr('data-type');
    var pricingList = $('option:selected', this).attr('data-pricingList');

    if ($('#invoice_lang').parents().hasClass('off')) { //off = euro
      var pricingList = $('option:selected', this).attr('data-pricingListFR');
    }
    else {
      var pricingList = $('option:selected', this).attr('data-pricingListUS');
    }

    if ($('#invoice_currency').parents().hasClass('off')) { //off = euro
      var price = $('option:selected', this).attr('data-euro');
    }
    else {
      var price = $('option:selected', this).attr('data-USD');
    }


    //on cherche où placer la nouvelle ligne
    a=$(this).parents().eq(3).find('div.splitInvLine');

    //on clone la ligne vierge, en effacant l'id
    b=$( "#invLineVierge" ).clone(true, true).prop('id', '' ).appendTo( a );
    //on affiche la ligne vierge copié avec son numéro et on rempli les champs
    b.css('display','block');
    b.addClass('invoiceLine');

    b.find('.newEntry').val(newEntry);
    b.find('.id_info_job').val(id_info_job);
    b.find('.id_tbljob').val(id_tbljob);
    b.find('.prodCode').val(prodCode);
    b.find('.OpnCode').val(OpnCode);
    b.find('.type').val(type);
    b.find('.code').find('input').val((prodCode=="" ? "" : prodCode+"-") + OpnCode);
    b.find('.pricingList').find('input').val(pricingList);
    b.find('.priceUnit').find('input').val(price);


    b.find('.pricingList').find('input').prop("readonly", false);

    //on reinitialise les select de new invoice line
    $(".addInvLine option[value='No']").prop('selected', true);

    newEntry+=1;
  });



  //delete invoiceLine
  $('.deleteInvoiceLine').click(function () {

    //changement de l'icone print en save
    $('#printInvoiceJob').css('display','none');
    $('#saveInvoiceJob').css('display','block');

    a=$(this).parent().parent();  //on remonte jusqu'a la div de la ligne
    if (a.find('newEntry').val()>0) {  //si on a newEntry, on supprime
      a.parent().remove();
    }
    else {  //sinon on flag toDelete
      a.find('.toDelete').val('1');
      a.parent().css('display','none');
    }
  } );




  $('.decimal0').each( function (i) { //ajouter 2 digit sur le nombre
    var num = parseFloat(this.value);
    if (!isNaN(num)) {
      this.value = parseFloat(this.value).toFixed(0);
    }
  });
  $('.decimal2').each( function (i) { //ajouter 2 digit sur le nombre
    var num = parseFloat(this.value);
    if (!isNaN(num)) {
      this.value = parseFloat(this.value).toFixed(2);
    }
  });


  //fonction de calcul auto du totalinvoice
  function calculAuto() {

    reachedMRSAS=0; //total reached
    reachedSubC=0; //total reached
    invoiceMRSAS=0;  //already invoiced
    invoiceSubC=0;  //already invoiced
    invoicableMRSAS=0;  //"UBR" based on this invoicejob
    invoicableSubC=0; //payables
    invMRSAS=0;    //next invoice MRSAS
    invSubC=0;    //next invoice SubC



    $('.totalUser').parents().find(".splitInfo").each(function(i) {
      if ($(this).data('st')==1) {                  //SubC
        $(this).find('.totalUser').find('input').each( function (i) {
          var num = parseFloat(this.value);
          if (!isNaN(num)) {
            if (num>0) {
              invSubC=num+invSubC;
          }
        }
      });
      }
      else if ($(this).data('st')==0) {                  //MRSAS
        $(this).find('.totalUser').find('input').each( function (i) {
          var num = parseFloat(this.value);
          if (!isNaN(num)) {
            invoicableMRSAS=num+invoicableMRSAS;
          }
        });
      }
    });


    $('.invmrsas').each( function (i) {
      invoicableMRSAS-=parseFloat($(this).text());
      invoiceMRSAS+=parseFloat($(this).text());
    });
    $('.invsubc').each( function (i) {
      invSubC-=parseFloat($(this).text());
      invoiceSubC+=parseFloat($(this).text());
    });


    $('.payables').each( function (i) {
      invoicableSubC+=($(this).data('applied')==1)?0:parseFloat($(this).text());
      reachedSubC+=parseFloat($(this).text());
    });

    invMRSAS=invoicableMRSAS;
    reachedMRSAS=invoiceMRSAS+invMRSAS;
    reachedSubC=invoicableSubC+invoicableSubC;


    $('#reachedMRSAS').text(reachedMRSAS.toFixed(2));
    $('#reachedSubC').text(reachedSubC.toFixed(2));
    $('#reachedTotal').text((reachedMRSAS+reachedSubC).toFixed(2));

    $('#invoiceMRSAS').text(invoiceMRSAS.toFixed(2));
    $('#invoiceSubC').text(invoiceSubC.toFixed(2));
    $('#invoiceTotal').text((invoiceMRSAS+invoiceSubC).toFixed(2));

    $('#invoicableMRSAS').text((invoicableMRSAS).toFixed(2));
    $('#invoicableSubC').html('<acronym title="To be invoiced: '+invSubC.toFixed(2)+'">'+invoicableSubC.toFixed(2)+'</acronym>');
    $('#invoicableTotal').text((invoicableMRSAS+invoicableSubC).toFixed(2));




    order_val=parseFloat($('#order_val').val());
    order_est_mrsas=parseFloat($('#order_est_mrsas').val());
    order_est_subc=parseFloat($('#order_est_subc').val());
    order_est_total=order_est_mrsas+order_est_subc;

    $('#order_est_total').text(order_est_total.toFixed(2));


    //alarm
    if((order_est_total - order_val)>0) {  //estimated > po
      $('#order_val').addClass('outTolerance');
        $('#order_est_total').addClass('outTolerance');
    }
    else {
      $('#order_val').removeClass('outTolerance');
      $('#order_est_total').removeClass('outTolerance');
    }

    if((reachedMRSAS+reachedSubC)>order_val) {  //reached > po
      $('#order_val').addClass('outTolerance');
        $('#invoiceTotal').addClass('outTolerance2');
    }
    else {
      $('#order_val').removeClass('outTolerance');
        $('#invoiceTotal').removeClass('outTolerance2');
    }

    if((reachedMRSAS - order_est_mrsas)>0) {  //reached > estimated MRSAS
      $('#order_est_mrsas').addClass('outTolerance');
    }
    else {
      $('#order_est_mrsas').removeClass('outTolerance');
    }

    if((reachedSubC - order_est_subc)>0) {  //reached > estimated SubC
      $('#order_est_subc').addClass('outTolerance');
    }
    else {
      $('#order_est_subc').removeClass('outTolerance');
    }

  }

  //calcul automatique des sommes après changement
  $(".qteUser, .priceUnit, #order_val, #order_est, #order_est_subc").change(function(e){
    qteUser=$(this).closest('form').find('.qteUser').find('input').val();
    qteGPM=$(this).closest('form').find('.qteGPM').find('input').val();
    priceUnit=$(this).closest('form').find('.priceUnit').find('input').val();
    totalUser=(qteUser ? qteUser : qteGPM)*priceUnit;
    $(this).parent().find('.totalUser').find('input').val(totalUser);

    //on remet 2 chiffres après la virgule (ou 0)
    $('.decimal0').each( function (i) { //ajouter 2 digit sur le nombre
      var num = parseFloat(this.value);
      if (!isNaN(num)) {
        this.value = parseFloat(this.value).toFixed(0);
      }
    });
    $('.decimal2').each( function (i) { //ajouter 2 digit sur le nombre
      var num = parseFloat(this.value);
      if (!isNaN(num)) {
        this.value = parseFloat(this.value).toFixed(2);
      }
    });

    calculAuto();
  });


  $('#invoice_lang').change(function(e){
    //changement de l'icone print en save
    $('#printInvoiceJob').css('display','none');
    $('#saveInvoiceJob').css('display','block');
  });

  $('#invoice_currency').change(function(e){
    //changement de l'icone print en save
    $('#printInvoiceJob').css('display','none');
    $('#saveInvoiceJob').css('display','block');

  });


  //invoice line + search
  $(".addInvLine").select2();


  //après chargement de la page, on calcul la somme total de l'invoice
  calculAuto();




  //Lors du submit du job, on recupere les information du WORKFLOW avant l'envoi
  $('#saveInvoiceJob').click( function(e) {

    e.preventDefault();

    //pour chaque invoiceLine
    $('.invoiceLine').each(function(){

      //on crée un input dans le formulaire d'envoi en newEntry ou id_invoice si existant
      //avec le serialize de la ligne en value
      if ($(this).find('.newEntry').val()!="") {
        $("#invoiceJob").append('<input type="hidden" name="newEntry_'+$(this).find('.newEntry').val()+'" value="'+$(this).find('form').serialize()+'"></input>');
      }
      else {
        $("#invoiceJob").append('<input type="hidden" name="id_invoiceLine_'+$(this).find('.id_invoiceLine').val()+'" value="'+$(this).find('form').serialize()+'"></input>');
      }

    });

    //pour chaque payable
    $('.payables_applied').each(function(){
      //on crée un input dans le formulaire d'envoi
          $("#invoiceJob").append('<input type="hidden" name="payable_'+$(this).attr("name")+'" value="id_payable='+$(this).attr("name")+'&checked='+$(this).is(':checked')+'"></input>');
    });

    //On ajoute aussi langue, currency et invoice_commentaire
    $("#invoiceJob").append('<input type="hidden" name="order_val" value="'+$('#order_val').val()+'"></input>');
    $("#invoiceJob").append('<input type="hidden" name="order_est_mrsas" value="'+$('#order_est_mrsas').val()+'"></input>');
    $("#invoiceJob").append('<input type="hidden" name="order_est_subc" value="'+$('#order_est_subc').val()+'"></input>');
    $("#invoiceJob").append('<input type="hidden" name="invoice_lang" value="'+$('#invoice_lang').parents().hasClass('off')+'"></input>');  //a cause de bootstrapToggle, on doit chercher la div au dessus si elle a la class off (ou rien)
    $("#invoiceJob").append('<input type="hidden" name="invoice_currency" value="'+$('#invoice_currency').parents().hasClass('off')+'"></input>');
    $("#invoiceJob").append('<input type="hidden" name="invoice_commentaire" value="'+$('#invoice_commentaire').val()+'"></input>');



    //on envoi le formulaire d'envoi
    $.ajax({
      type: "POST",
      url: "controller/updateInvoiceJob.php",
      data: $("#invoiceJob").serialize(), // serializes the form's elements.
      success: function(data)
      {
        //window.location.href = 'controller/createInvoice-controller.php?id_tbljob='+$('#id_tbljob').val();
        location.reload();
      }
    });
  } );



  $(".openDocument").click(function(e) {
    // on ouvre dans une fenêtre le fichier passé en paramètre.
    window.open("controller/openDocument-controller?file_type="+$(this).attr('data-type')+"&file_name="+$(this).attr('data-file'),'Document','width=670,height=930,top=50,left=50');
  });


});
