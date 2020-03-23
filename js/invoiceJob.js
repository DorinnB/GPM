$(document).ready(function() {

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
    var id_pricingList = $('option:selected', this).attr('data-id_pricingList');
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
    b.find('.id_pricingList').val(id_pricingList);
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

    order_val=parseFloat($('#order_val').val());
    order_val_subc=parseFloat($('#order_val_subc').val());

    invoice_val=0;
    invoice_val_subc=0;
    ubr_val=0;
    ubr_val_subc=0;

    $('.totalUser').parents().find(".splitInfo").each(function(i) {
      if ($(this).data('st')==1) {                  //SubC
        $(this).find('.totalUser').find('input').each( function (i) {
          var num = parseFloat(this.value);
          if (!isNaN(num)) {
            ubr_val_subc=num+ubr_val_subc;
            if (num>0) {
              invoice_val_subc=num+invoice_val_subc;
            }
          }
        });
      }
      else if ($(this).data('st')==0) {                  //MRSAS
        $(this).find('.totalUser').find('input').each( function (i) {
          var num = parseFloat(this.value);
          if (!isNaN(num)) {
            ubr_val=num+ubr_val;
            if (num>0) {
              invoice_val=num+invoice_val;
            }
          }
        });
      }
    });

    $('#invoice_val').text(invoice_val.toFixed(2));
    $('#invoice_val_subc').text(invoice_val_subc.toFixed(2));
    $('#ubr_val').text(ubr_val.toFixed(2));
    $('#ubr_val_subc').text(ubr_val_subc.toFixed(2));

    if((invoice_val - order_val)>0) {
      $('#invoice_val').css("background-color", "darkred");
    }    else {
      $('#invoice_val').css("background-color", "inherit");
    }
    if((invoice_val_subc - order_val_subc)>0) {
      $('#invoice_val_subc').css("background-color", "darkred");
    }
    else {
      $('#invoice_val_subc').css("background-color", "inherit");
    }

  }

  //calcul automatique des sommes après changement
  $(".qteUser, .priceUnit, #order_val, #order_val_subc").change(function(e){
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

    //On ajoute aussi langue, currency et invoice_commentaire
    $("#invoiceJob").append('<input type="hidden" name="order_val" value="'+$('#order_val').val()+'"></input>');
    $("#invoiceJob").append('<input type="hidden" name="order_val_subc" value="'+$('#order_val_subc').val()+'"></input>');
    $("#invoiceJob").append('<input type="hidden" name="montant_commande" value="'+$('#montant_commande').val()+'"></input>');
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
