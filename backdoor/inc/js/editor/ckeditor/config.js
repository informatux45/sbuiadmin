/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	// -----------------------------------------------------
	//                      ADD PLUGINS
	// -----------------------------------------------------
	// Add Ckawesome Plugin
	// Add Justify Plugin
	// Add Youtube Plugin
	// Add Color Buttons Plugin (Dependencies: panelbutton, floatpanel)
	// Add Enhanced Image Plugin
	// Add Code Snippet Plugin
	// Add HTML5 Video / Audio Plugins
	// Add Color Dialog Plugin
	config.fontawesomePath = 'assets/bower_components/font-awesome/css/font-awesome.min.css';
	config.extraPlugins = 'lineutils,colordialog,ckawesome,justify,youtube,panelbutton,floatpanel,colorbutton,image2,codesnippet,html5video,html5audio,colordialog';
    config.allowedContent = true;
	// -----------------------------------------------------
		
	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';
	
	// Change behavior enter key pressed in CKeditor
	config.enterMode = CKEDITOR.ENTER_BR;
	//config.enterMode = CKEDITOR.ENTER_DIV; // inserts `<div></div>`
	//config.enterMode = CKEDITOR.ENTER_P; // inserts `<p>...</p>`
};
