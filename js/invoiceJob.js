var editor; // use a global for the submit and return data rendering in the examples


$(document).ready(function() {


  editor = new $.fn.dataTable.Editor( {
    ajax: "controller/editor-invoices.php",        template: '#customForm',
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

    invoice_val=0;
    invoice_val_subc=0;
    notInv_val=0;
    notInv_val_subc=0;
    inv_subc=0;

    $('.totalUser').parents().find(".splitInfo").each(function(i) {
      if ($(this).data('st')==1) {                  //SubC
        $(this).find('.totalUser').find('input').each( function (i) {
          var num = parseFloat(this.value);
          if (!isNaN(num)) {
            notInv_val_subc=num+notInv_val_subc;
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
            notInv_val=num+notInv_val;
            if (num>0) {
              invoice_val=num+invoice_val;
            }
          }
        });
      }
    });


    $('.inv_mrsas').each( function (i) {
      notInv_val-=parseFloat($(this).text());
    });
    $('.inv_subc').each( function (i) {
      inv_subc+=parseFloat($(this).text());
      notInv_val_subc-=parseFloat($(this).text());
    });


    $('#invoice_val').text(invoice_val.toFixed(2));
    $('#invoice_val_subc').text(invoice_val_subc.toFixed(2));
    $('#invoice_val_total').text((invoice_val+invoice_val_subc).toFixed(2));

    $('#notInv_val').text(notInv_val.toFixed(2));
    $('#notInv_val_subc').text(notInv_val_subc.toFixed(2));
    $('#UBRMRSAS').text(notInv_val.toFixed(2));
    $('#ubr_total').text((notInv_val+notInv_val_subc).toFixed(2));

    $('#invMRSAS').text((invoice_val-notInv_val).toFixed(2));
    $('#invSubC').text(inv_subc.toFixed(2));
    $('#inv_total').text((invoice_val-notInv_val+inv_subc).toFixed(2));

    order_val=parseFloat($('#order_val').val());
    order_est=parseFloat($('#order_est').val());
    order_est_subc=parseFloat($('#order_est_subc').val());
    UBRMRSAS=parseFloat($('#UBRMRSAS').text());
    sumPayables=parseFloat($('#sumPayables').text());

    order_est_total=order_est+order_est_subc;
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

    if((invoice_val+invoice_val_subc)>order_val) {  //estimated > po
      $('#order_val').addClass('outTolerance');
        $('#invoice_val_total').addClass('outTolerance2');
    }
    else {
      $('#order_val').removeClass('outTolerance');
        $('#invoice_val_total').removeClass('outTolerance2');
    }

    if((invoice_val - order_est)>0) {  //reached > estimated MRSAS
      $('#order_est').addClass('outTolerance');
    }
    else {
      $('#order_est').removeClass('outTolerance');
    }

    if((invoice_val_subc - order_est_subc)>0) {  //reached > estimated SubC
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
    $("#invoiceJob").append('<input type="hidden" name="order_est" value="'+$('#order_est').val()+'"></input>');
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
