idEpVal = ($('#idEp').val());


(function( factory ){
    if ( typeof define === 'function' && define.amd ) {
        // AMD
        define( ['jquery', 'datatables', 'datatables-editor'], factory );
    }
    else if ( typeof exports === 'object' ) {
        // Node / CommonJS
        module.exports = function ($, dt) {
            if ( ! $ ) { $ = require('jquery'); }
            factory( $, dt || $.fn.dataTable || require('datatables') );
        };
    }
    else if ( jQuery ) {
        // Browser standard
        factory( jQuery, jQuery.fn.dataTable );
    }
}(function( $, DataTable ) {
'use strict';


if ( ! DataTable.ext.editorFields ) {
    DataTable.ext.editorFields = {};
}

var _fieldTypes = DataTable.Editor ?
    DataTable.Editor.fieldTypes :
    DataTable.ext.editorFields;

_fieldTypes.select2 = {
    _addOptions: function ( conf, opts ) {
        var elOpts = conf._input[0].options;

        elOpts.length = 0;

        if ( opts ) {
            DataTable.Editor.pairs( opts, conf.optionsPair, function ( val, label, i ) {
                elOpts[i] = new Option( label, val );
            } );
        }
    },

    create: function ( conf ) {
        conf._input = $('<select/>')
            .attr( $.extend( {
                id: DataTable.Editor.safeId( conf.id )
            }, conf.attr || {} ) );

        var options = $.extend( {
                width: '100%'
            }, conf.opts );

        _fieldTypes.select2._addOptions( conf, conf.options || conf.ipOpts );
        conf._input.select2( options );

        var open;
        conf._input
            .on( 'select2:open', function () {
                open = true;
            } )
            .on( 'select2:close', function () {
                open = false;
            } );

        // On open, need to have the instance update now that it is in the DOM
        this.one( 'open.select2-'+DataTable.Editor.safeId( conf.id ), function () {
            conf._input.select2( options );

            if ( open ) {
                conf._input.select2( 'open' );
            }
        } );

        return conf._input[0];
    },

    get: function ( conf ) {
        var val = conf._input.val();
        val =  conf._input.prop('multiple') && val === null ?
            [] :
            val;

        return conf.separator ?
            val.join( conf.separator ) :
            val;
    },

    set: function ( conf, val ) {
        if ( conf.separator && ! $.isArray( val ) ) {
            val = val.split( conf.separator );
        }

        // Clear out any existing tags
        if ( conf.opts && conf.opts.tags ) {
            conf._input.val([]);
        }

        // The value isn't present in the current options list, so we need to add it
        // in order to be able to select it. Note that this makes the set action async!
        // It doesn't appear to be possible to add an option to select2, then change
        // its label and update the display
        var needAjax = false;

        if ( conf.opts && conf.opts.ajax ) {
            if ( $.isArray( val ) ) {
                for ( var i=0, ien=val.length ; i<ien ; i++ ) {
                    if ( conf._input.find('option[value="'+val[i]+'"]').length === 0 ) {
                        needAjax = true;
                        break;
                    }
                }
            }
            else {
                if ( conf._input.find('option[value="'+val+'"]').length === 0 ) {
                    needAjax = true;
                }
            }
        }

        if ( needAjax ) {
            $.ajax( $.extend( {
                beforeSend: function ( jqXhr, settings ) {
                    // Add an initial data request to the server, but don't
                    // override `data` since the dev might be using that
                    var initData = 'initialValue=true&value='+
                        JSON.stringify(val);

                    if ( settings.type === 'GET' ) {
                        settings.url += settings.url.indexOf('?') === -1 ?
                            '?'+initData :
                            '&'+initData;
                    }
                    else {
                        settings.data = settings.data ?
                            settings.data +'&'+ initData :
                            initData;
                    }
                },
                success: function ( json ) {
                    var addOption = function ( option ) {
                        if ( conf._input.find('option[value="'+option.id+'"]').length === 0 ) {
                            $('<option/>')
                                .attr('value', option.id)
                                .text( option.text )
                                .appendTo( conf._input );
                        }
                    }

                    if ( $.isArray( json ) ) {
                        for ( var i=0, ien=json.length ; i<ien ; i++ ) {
                            addOption( json[i] );
                        }
                    }
                    else if ( json.results && $.isArray( json.results ) ) {
                        for ( var i=0, ien=json.results.length ; i<ien ; i++ ) {
                            addOption( json.results[i] );
                        }
                    }
                    else {
                        addOption( json );
                    }

                    conf._input
                        .val( val )
                        .trigger( 'change', {editor: true} );
                }
            }, conf.opts.ajax ) );
        }
        else {
            conf._input
                .val( val )
                .trigger( 'change', {editor: true} );
        }
    },

    enable: function ( conf ) {
        $(conf._input).removeAttr( 'disabled' );
    },

    disable: function ( conf ) {
        $(conf._input).attr( 'disabled', 'disabled' );
    },

    // Non-standard Editor methods - custom to this plug-in
    inst: function ( conf ) {
        var args = Array.prototype.slice.call( arguments );
        args.shift();

        return conf._input.select2.apply( conf._input, args );
    },

    update: function ( conf, data ) {
        var val = _fieldTypes.select2.get( conf );

        _fieldTypes.select2._addOptions( conf, data );

        // Restore null value if it was, to let the placeholder show
        if ( val === null ) {
            _fieldTypes.select2.set( conf, null );
        }

        $(conf._input).trigger('change', {editor: true} );
    },

    focus: function ( conf ) {
        if ( conf._input.is(':visible') && conf.onFocus === 'focus' ) {
            conf._input.select2('open');
        }
    },

    owns: function ( conf, node ) {
        if ( $(node).closest('.select2-container').length || $(node).closest('.select2').length || $(node).hasClass('select2-selection__choice__remove') ) {
            return true;
        }
        return false;
    }
};


}));

var editor; // use a global for the submit and return data rendering in the examples

// Template function to display the information TDRs. Editor will
// automatically keep the values up-to-date with any changes due to the use of
// the `data-editor-field` attribute. It knows which TDR to update for each
// record through the use of `data-editor-id` in the container element.
function createTDR ( data )
{
  var id = data.DT_RowId;

  $(
    '<div class="TDR" data-editor-id="'+id+'">'+
    '<i class="edit fa fa-pencil" data-id="'+id+'"/>'+
    '<i class="remove fa fa-times" data-id="'+id+'"/>'+
    '<i><a href="controller/createTDR-controller.php?TDR='+id+'"><span class="glyphicon glyphicon-list-alt"></span></a></i>'+
    '<div class="col-md-8 titre">Type:</div>'+
    '<div class="col-md-3 titre">Cycles:</div>'+
    '<div class="col-md-8" data-editor-field="TDRs.TDR_type">'+data.TDR_types.TDR_type+'</div>'+
    '<div class="col-md-3" data-editor-field="TDRs.cyclenumber">'+data.TDRs.cyclenumber+'</div>'+
    '<div class="col-md-10 titre">Observations:</div>'+
    '<div class="col-md-10" data-editor-field="TDRs.TDR_text" style="white-space:pre-wrap;">'+data.TDRs.TDR_text+'</div> '+
    '</div>'
  ).appendTo( '#1f' );
}

  //affichage de la liste initiale des TDRs
  $.ajax( {
    url: 'controller/editor-TDRs.php',
    dataType: 'json',
    type:"POST",
    data: {"idEp" : idEpVal},
    success: function ( json ) {
      for ( var i=0, ien=json.data.length ; i<ien ; i++ ) {
        createTDR( json.data[i] );
      }
    }
  } );


//je recupère une requete Editor vide avec uniquement les options du select
//je vais chercher dans les sous-sous-sous-objets les label/value et je modifie
//le champ du select avec ces info (editor ne permet pas de le faire en standalone automatiquement)
  var optionsA = [];
  $.getJSON("controller/editor-TDRs.php", {
    term: "-1"
  },
  function(data) {
    var option = {};
    $.each(data, function(i, e) {
      if (i=="options") {
        $.each(e, function(inn, en) {
          $.each(en, function(innn, enn) {
            option.label = enn.label;
            option.value = enn.value;
            optionsA.push(option);
            option = {};
          });
        });
      }
    });
  }
  ).done(function() {
    editor.field('TDRs.id_TDR_type').update(optionsA);

  });



$(document).ready(function() {

  editor = new $.fn.dataTable.Editor( {
    ajax: {
      url : "controller/editor-TDRs.php",
      type: "POST",
      data: {"idEp" : idEpVal}
    },
    fields: [
      { label: "Typology", name: "TDRs.id_TDR_type", type:"select2"},
      { label: "Cycle Occuring", name: "TDRs.cyclenumber", def:$('#cycleFinal').text()  },
      { label: "Observation", name: "TDRs.TDR_text", type: "textarea"  }
    ]
  } );

  // Create record - on create we insert a new TDR
  editor.on( 'postCreate', function (e, json) {
    createTDR( json.data[0] );
  } );

  $('button.create').on( 'click', function () {
    editor
    .title('Create new record')
    .buttons('Create')
    .create();
  } );

  // Edit
  $('#1f').on( 'click', 'i.edit', function () {
    editor
    .title('Edit record')
    .buttons('Save changes')
    .edit( $(this).data('id') );
  } );

  // Remove
  $('#1f').on( 'click', 'i.remove', function () {
    editor
    .title('Delete record')
    .buttons('Delete')
    .message('Are you sure you wish to delete this record?')
    .remove( $(this).data('id') );
  } );


} );
