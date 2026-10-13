/**
 *   CKAwesome 
 *   =========
 *   http://blackdevelop.com/io/ckawesome/
 *   
 *   Copyright (C) 2017 by Blackdevelop.com
 *   Licence under GNU GPL v3.
 */

CKEDITOR.on('instanceReady',function () { CKEDITOR.document.appendStyleSheet(CKEDITOR.plugins.getPath('ckawesome') + 'resources/select2/select2.full.min.css');   });
CKEDITOR.on('instanceReady',function () { CKEDITOR.document.appendStyleSheet(CKEDITOR.plugins.getPath('ckawesome') + 'dialogs/ckawesome.css');   });
CKEDITOR.scriptLoader.load(CKEDITOR.plugins.getPath('ckawesome') + 'resources/select2/select2.full.min.js');
CKEDITOR.dtd.$removeEmpty.span = 0;
CKEDITOR.plugins.add('ckawesome', {
    requires: 'colordialog',
    icons: 'ckawesome',
    
    init: function(editor) {
    	var config = editor.config;
    	// SBUIADMIN : une ou plusieurs feuilles Font Awesome (FA7 + v4-shims), voir config.js
    	editor.fontawesomePath = [].concat(config.fontawesomePath || []);
    	editor.fontawesomePath.forEach(function (path) {
    		CKEDITOR.document.appendStyleSheet(path);
    		editor.addContentsCss(path);
    	});
    	
        CKEDITOR.dialog.add('ckawesomeDialog', this.path + 'dialogs/ckawesome.js');
        editor.addCommand( 'ckawesome', new CKEDITOR.dialogCommand( 'ckawesomeDialog', { allowedContent: 'span[class,style]{color,font-size}(*);' }));
        editor.ui.addButton( 'ckawesome', {
              label: 'Insert CKAwesome',
              command: 'ckawesome',
              toolbar: 'insert',
        });
    }
});