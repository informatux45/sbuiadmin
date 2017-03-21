<?php
/**
 * Admin Startbootstrap
 * CKEDITOR Extension (custom)
 *
 * @link http://dev.informatux.com/
 * @link http://dollar.fr/
 *
 * Agence DOLLAR
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 *
 * ----------------------------------------------------
 * Example :
 * 
 * $chaineTemp .= "CKEDITOR.replace( '$validNameEditor', {
 * 				filebrowserBrowseUrl: 'index.php?p=transfert&editor=ck&type=Files',
 * 				toolbarGroups: [
 * 					{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
 * 					{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
 * 					{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
 * 					{ name: 'insert', groups: [ 'insert' ] },
 * 					{ name: 'links', groups: [ 'links' ] },
 * 					{ name: 'forms', groups: [ 'forms' ] },
 * 					{ name: 'tools', groups: [ 'tools' ] },
 * 					{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
 * 					{ name: 'others', groups: [ 'others' ] },
 * 					{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
 * 					{ name: 'styles', groups: [ 'styles' ] },
 * 					{ name: 'colors', groups: [ 'colors' ] },
 * 					{ name: 'about', groups: [ 'about' ] }
 * 				],
 * 				stylesSet: [ { name: 'Red', element: 'span', attributes: {'class': 'red'}, styles: {'color': 'red'} },
 * 							   { name: 'Green', element: 'span', attributes: {'class': 'green'}, styles: {'color': 'green'} },
 * 							   { name: 'Pink', element: 'span', attributes: {'class': 'pink'}, styles: {'color': 'pink'} },
 * 							   { name: 'Custom', element: 'span', attributes: {'class': 'color-custom'}, styles: {'color': '#8e3b3b'} }
 * 						   ],
 * 				removeButtons: 'Save,NewPage,Preview,Print,Templates,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Subscript,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,NumberedList,BulletedList,Outdent,Indent,Strike,Blockquote,JustifyCenter,JustifyRight,JustifyBlock,Image,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,ShowBlocks,Maximize,About,TextColor,BGColor,Font,FontSize,Format,JustifyLeft,Cut,Copy,Paste'
 * 				});
 * 				CKEDITOR.config.allowedContent = true;
 * 			   ";
 * 
 */

$chaineTemp .= "CKEDITOR.replace( '$validNameEditor', {
				filebrowserBrowseUrl: 'index.php?p=transfert&editor=ck&type=Files',
				toolbarGroups: [
					{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
					{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
					{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
					{ name: 'insert', groups: [ 'insert' ] },
					{ name: 'links', groups: [ 'links' ] },
					{ name: 'forms', groups: [ 'forms' ] },
					{ name: 'tools', groups: [ 'tools' ] },
					{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
					{ name: 'others', groups: [ 'others' ] },
					{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
					{ name: 'styles', groups: [ 'styles' ] },
					{ name: 'colors', groups: [ 'colors' ] },
					{ name: 'about', groups: [ 'about' ] }
				],
				stylesSet: [ { name: 'Red', element: 'span', attributes: {'class': 'red'}, styles: {'color': 'red'} },
							   { name: 'Green', element: 'span', attributes: {'class': 'green'}, styles: {'color': 'green'} },
							   { name: 'Pink', element: 'span', attributes: {'class': 'pink'}, styles: {'color': 'pink'} },
							   { name: 'Custom', element: 'span', attributes: {'class': 'color-custom'}, styles: {'color': '#8e3b3b'} }
						   ],
				removeButtons: 'Save,NewPage,Preview,Print,Templates,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Subscript,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,NumberedList,BulletedList,Outdent,Indent,Strike,Blockquote,JustifyCenter,JustifyRight,JustifyBlock,Image,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,ShowBlocks,Maximize,About,TextColor,BGColor,Font,FontSize,Format,JustifyLeft,Cut,Copy,Paste'
				});
				CKEDITOR.config.allowedContent = true;
			   ";

?>