/**
 * [Quill](http://quilljs.com/) is a lightweight WYSIWYG editing library that
 * will provides an attractive input where end users and easily edit complex
 * information in a style familiar to all word processor users.
 *
 * Quill is different from the majority of other WYSIWYG libraries in that it
 * does not use iframes. This makes it more approachable for extension
 * developers and easier to style.
 *
 * It also has support for multiple modules such as toolbars, authorship
 * highlighting and multiple cursors. The toolbar module is used by default by
 * this Editor plug-in (see options below). Please see the [Quill
 * documentation](http://quilljs.com/docs/modules/) for more information.
 *
 * @name Quill
 * @summary Quill WYSIWYG editor
 * @requires [Quill](http://quilljs.com)
 * 
 * @depcss //cdn.quilljs.com/latest/quill.snow.css
 * @depjs //cdn.quilljs.com/latest/quill.min.js
 *
 * @scss editor.quill.scss
 *
 * @opt `e-type string`, `e-type node`, `e-type boolean` **`toolbar`**: Show
 *   toolbar (`true` - default) or not (`false`). A custom toolbar can be
 *   defined, per the Quill documentation, by passing the string or node for
 *   the toolbar's HTML to this parameter.
 * @opt `e-type object` **`opts`**: Options passed directly to the Quill
 *   configuration object. Please refer to the [Quill
 *   documentation](http://quilljs.com/docs/configuration/) for the full range
 *   of options.
 *
 * @method **`quill`**: Get the Quill instance used for this field, so you can
 *   add additional modules, or perform other API operations on it directly.
 *
 * @example
 *
 * new $.fn.dataTable.Editor( {
 *   "ajax": "/api/documents",
 *   "table": "#documents",
 *   "fields": [ {
 *       "label": "Description:",
 *       "name": "description",
 *       "type": "quill"
 *     }, 
 *     // additional fields...
 *   ]
 * } );
 */

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


// Default toolbar, as Quill doesn't provide one
var defaultToolbar =
    '<span class="ql-format-group">'+
    '  <select title="Font" class="ql-font">'+
    '    <option value="sans-serif" selected="">Sans Serif</option>'+
    '    <option value="serif">Serif</option>'+
    '    <option value="monospace">Monospace</option>'+
    '  </select>'+
    '  <select title="Size" class="ql-size">'+
    '    <option value="10px">Small</option>'+
    '    <option value="13px" selected="">Normal</option>'+
    '    <option value="18px">Large</option>'+
    '    <option value="32px">Huge</option>'+
    '  </select>'+
    '</span>'+
    '<span class="ql-format-group">'+
    '  <span title="Bold" class="ql-format-button ql-bold"></span>'+
    '<span class="ql-format-separator"></span>'+
    '<span title="Italic" class="ql-format-button ql-italic"></span>'+
    '<span class="ql-format-separator"></span>'+
    '<span title="Underline" class="ql-format-button ql-underline"></span>'+
    '<span class="ql-format-separator"></span>'+
    '<span title="Strikethrough" class="ql-format-button ql-strike"></span>'+
    '</span>'+
    '<span class="ql-format-group">'+
    '  <select title="Text Color" class="ql-color">'+
    '    <option value="rgb(0, 0, 0)" label="rgb(0, 0, 0)" selected=""></option>'+
    '    <option value="rgb(230, 0, 0)" label="rgb(230, 0, 0)"></option>'+
    '    <option value="rgb(255, 153, 0)" label="rgb(255, 153, 0)"></option>'+
    '    <option value="rgb(255, 255, 0)" label="rgb(255, 255, 0)"></option>'+
    '    <option value="rgb(0, 138, 0)" label="rgb(0, 138, 0)"></option>'+
    '    <option value="rgb(0, 102, 204)" label="rgb(0, 102, 204)"></option>'+
    '    <option value="rgb(153, 51, 255)" label="rgb(153, 51, 255)"></option>'+
    '    <option value="rgb(255, 255, 255)" label="rgb(255, 255, 255)"></option>'+
    '    <option value="rgb(250, 204, 204)" label="rgb(250, 204, 204)"></option>'+
    '    <option value="rgb(255, 235, 204)" label="rgb(255, 235, 204)"></option>'+
    '    <option value="rgb(255, 255, 204)" label="rgb(255, 255, 204)"></option>'+
    '    <option value="rgb(204, 232, 204)" label="rgb(204, 232, 204)"></option>'+
    '    <option value="rgb(204, 224, 245)" label="rgb(204, 224, 245)"></option>'+
    '    <option value="rgb(235, 214, 255)" label="rgb(235, 214, 255)"></option>'+
    '    <option value="rgb(187, 187, 187)" label="rgb(187, 187, 187)"></option>'+
    '    <option value="rgb(240, 102, 102)" label="rgb(240, 102, 102)"></option>'+
    '    <option value="rgb(255, 194, 102)" label="rgb(255, 194, 102)"></option>'+
    '    <option value="rgb(255, 255, 102)" label="rgb(255, 255, 102)"></option>'+
    '    <option value="rgb(102, 185, 102)" label="rgb(102, 185, 102)"></option>'+
    '    <option value="rgb(102, 163, 224)" label="rgb(102, 163, 224)"></option>'+
    '    <option value="rgb(194, 133, 255)" label="rgb(194, 133, 255)"></option>'+
    '    <option value="rgb(136, 136, 136)" label="rgb(136, 136, 136)"></option>'+
    '    <option value="rgb(161, 0, 0)" label="rgb(161, 0, 0)"></option>'+
    '    <option value="rgb(178, 107, 0)" label="rgb(178, 107, 0)"></option>'+
    '    <option value="rgb(178, 178, 0)" label="rgb(178, 178, 0)"></option>'+
    '    <option value="rgb(0, 97, 0)" label="rgb(0, 97, 0)"></option>'+
    '    <option value="rgb(0, 71, 178)" label="rgb(0, 71, 178)"></option>'+
    '    <option value="rgb(107, 36, 178)" label="rgb(107, 36, 178)"></option>'+
    '    <option value="rgb(68, 68, 68)" label="rgb(68, 68, 68)"></option>'+
    '    <option value="rgb(92, 0, 0)" label="rgb(92, 0, 0)"></option>'+
    '    <option value="rgb(102, 61, 0)" label="rgb(102, 61, 0)"></option>'+
    '    <option value="rgb(102, 102, 0)" label="rgb(102, 102, 0)"></option>'+
    '    <option value="rgb(0, 55, 0)" label="rgb(0, 55, 0)"></option>'+
    '    <option value="rgb(0, 41, 102)" label="rgb(0, 41, 102)"></option>'+
    '    <option value="rgb(61, 20, 102)" label="rgb(61, 20, 102)"></option>'+
    '  </select>'+
    '  <span class="ql-format-separator"></span>'+
    '<select title="Background Color" class="ql-background">'+
    '   <option value="rgb(0, 0, 0)" label="rgb(0, 0, 0)"></option>'+
    '   <option value="rgb(230, 0, 0)" label="rgb(230, 0, 0)"></option>'+
    '   <option value="rgb(255, 153, 0)" label="rgb(255, 153, 0)"></option>'+
    '   <option value="rgb(255, 255, 0)" label="rgb(255, 255, 0)"></option>'+
    '   <option value="rgb(0, 138, 0)" label="rgb(0, 138, 0)"></option>'+
    '   <option value="rgb(0, 102, 204)" label="rgb(0, 102, 204)"></option>'+
    '   <option value="rgb(153, 51, 255)" label="rgb(153, 51, 255)"></option>'+
    '   <option value="rgb(255, 255, 255)" label="rgb(255, 255, 255)" selected=""></option>'+
    '   <option value="rgb(250, 204, 204)" label="rgb(250, 204, 204)"></option>'+
    '   <option value="rgb(255, 235, 204)" label="rgb(255, 235, 204)"></option>'+
    '   <option value="rgb(255, 255, 204)" label="rgb(255, 255, 204)"></option>'+
    '   <option value="rgb(204, 232, 204)" label="rgb(204, 232, 204)"></option>'+
    '   <option value="rgb(204, 224, 245)" label="rgb(204, 224, 245)"></option>'+
    '   <option value="rgb(235, 214, 255)" label="rgb(235, 214, 255)"></option>'+
    '   <option value="rgb(187, 187, 187)" label="rgb(187, 187, 187)"></option>'+
    '   <option value="rgb(240, 102, 102)" label="rgb(240, 102, 102)"></option>'+
    '   <option value="rgb(255, 194, 102)" label="rgb(255, 194, 102)"></option>'+
    '   <option value="rgb(255, 255, 102)" label="rgb(255, 255, 102)"></option>'+
    '   <option value="rgb(102, 185, 102)" label="rgb(102, 185, 102)"></option>'+
    '   <option value="rgb(102, 163, 224)" label="rgb(102, 163, 224)"></option>'+
    '   <option value="rgb(194, 133, 255)" label="rgb(194, 133, 255)"></option>'+
    '   <option value="rgb(136, 136, 136)" label="rgb(136, 136, 136)"></option>'+
    '   <option value="rgb(161, 0, 0)" label="rgb(161, 0, 0)"></option>'+
    '   <option value="rgb(178, 107, 0)" label="rgb(178, 107, 0)"></option>'+
    '   <option value="rgb(178, 178, 0)" label="rgb(178, 178, 0)"></option>'+
    '   <option value="rgb(0, 97, 0)" label="rgb(0, 97, 0)"></option>'+
    '   <option value="rgb(0, 71, 178)" label="rgb(0, 71, 178)"></option>'+
    '   <option value="rgb(107, 36, 178)" label="rgb(107, 36, 178)"></option>'+
    '   <option value="rgb(68, 68, 68)" label="rgb(68, 68, 68)"></option>'+
    '   <option value="rgb(92, 0, 0)" label="rgb(92, 0, 0)"></option>'+
    '   <option value="rgb(102, 61, 0)" label="rgb(102, 61, 0)"></option>'+
    '   <option value="rgb(102, 102, 0)" label="rgb(102, 102, 0)"></option>'+
    '   <option value="rgb(0, 55, 0)" label="rgb(0, 55, 0)"></option>'+
    '   <option value="rgb(0, 41, 102)" label="rgb(0, 41, 102)"></option>'+
    '   <option value="rgb(61, 20, 102)" label="rgb(61, 20, 102)"></option>'+
    '</select>'+
    '</span>'+
    '<span class="ql-format-group">'+
    '  <span title="List" class="ql-format-button ql-list"></span>'+
    '<span class="ql-format-separator"></span>'+
    '<span title="Bullet" class="ql-format-button ql-bullet"></span>'+
    '<span class="ql-format-separator"></span>'+
    '<select title="Text Alignment" class="ql-align">'+
    '   <option value="left" label="Left" selected=""></option>'+
    '   <option value="center" label="Center"></option>'+
    '   <option value="right" label="Right"></option>'+
    '   <option value="justify" label="Justify"></option>'+
    '</select>'+
    '</span>'+
    '<span class="ql-format-group">'+
    '      <span title="Link" class="ql-format-button ql-link"></span>'+
    '</span>';


_fieldTypes.quill = {
    create: function ( conf ) {
        var safeId = DataTable.Editor.safeId( conf.id );
        var input = $(
            '<div id="'+safeId+'" class="quill-wrapper">'+
                '<div class="editor"></div>'+
            '</div>'
        );

        conf._quill = new Quill( input.find('.editor')[0], $.extend( true, {
            theme: 'snow'
        }, conf.opts ) );

        var toolbar = conf.toolbar === undefined ?
            true :
            conf.toolbar;

        if ( toolbar ) {
            $('<div class="toolbar"/>' )
                .append( toolbar === true ?
                    defaultToolbar :
                    toolbar
                )
                .prependTo( input );

            conf._quill.addModule('toolbar', {
                container: input.find('.toolbar')[0]
            } );
        }

        return input;
    },
 
    get: function ( conf ) {
        return conf._quill.getHTML();
    },
 
    set: function ( conf, val ) {
        conf._quill.setHTML( val !== null ? val : '' );
    },
 
    enable: function ( conf ) {}, // not supported by Quill
 
    disable: function ( conf ) {}, // not supported by Quill
 
    // Get the Quill instance
    quill: function ( conf ) {
        return conf._quill;
    }
};


}));
