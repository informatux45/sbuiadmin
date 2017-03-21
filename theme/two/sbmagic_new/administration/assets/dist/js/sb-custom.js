/**
 * Admin Startbootstrap
 * JAVASCRIPT Functions
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 * 
 * -----------------------------------------
 *
 * getLastPartOfUrl
 * sbTransfert
 * sbGetFileExtension
 * sbOpenPopup
 * sbTransfert
 * sbTransfertCkeditor
 * sbAllUploadsCompleted
 * sbEnabledInput
 * sbUgrade
 * sbToggleFullScreen
 * sbInsertText
 *
 * *************************************** */


function getLastPartOfUrl($url) {
    var url = $url;
    var urlsplit = url.split("/");
    var lastpart = urlsplit[urlsplit.length-1];
    if(lastpart==='') {
        lastpart = urlsplit[urlsplit.length-2];
    }
    return lastpart;
}

// Helper function to get parameters from the query string.
function getUrlParam( paramName ) {
	var reParam = new RegExp( '(?:[\?&]|&)' + paramName + '=([^&]+)', 'i' );
	var match = window.location.search.match( reParam );

	return ( match && match.length > 1 ) ? match[1] : null;
}

function sbTransfert(data, id) {
	// Recuperer le dernier element IMAGE
	split_data = getLastPartOfUrl(data);
	// Injecter dans le champs de la fenetre PARENT la valeur voulue
	window.opener.document.getElementById(id).value = split_data;
	// Verifier si le document est un PDF
	if (sbGetFileExtension(split_data) != 'pdf') {
		// Injecter l'image dans la visu Thumb
		window.opener.document.getElementById(id+'Thumb').className = "icon-transfert";
		window.opener.document.getElementById(id+'Thumb').innerHTML = '<img width="200px" src="'+data+'" title="" />';
	}
	// Fermer le popup (fenetre enfant)
	window.close();
}

function sbTransfertTiny(data, id, url) {
	// Recuperer le dernier element IMAGE
	split_data = getLastPartOfUrl(data);
	// Injecter dans le champs de la fenetre PARENT la valeur voulue
	parent.document.getElementById(id).value = url + split_data;
	// Close the current window
	top.tinymce.activeEditor.windowManager.close();
}

function sbGetFileExtension(file) {
	return file.substr((~-file.lastIndexOf(".") >>> 0) + 2);
}

function sbOpenPopup(id, extension = '', custom = '', nomfenetre = 'MEDIAS', largeur = '800', hauteur = '600') {
	var top = (screen.height-hauteur)/2;
	var left = (screen.width-largeur)/2;
	var config = ' toolbar=no, menubar=no, scrollbars=yes, resizable=no, location=no, directories=no, status=no';
	if (extension != '') {
		Popup = window.open("index.php?p=transfert"+custom+"&id="+id+"&ext="+extension, nomfenetre, "top="+top+",left="+left+",width="+largeur+",height="+hauteur+","+config);
	} else {
		Popup = window.open("index.php?p=transfert"+custom+"&id="+id, nomfenetre, "top="+top+",left="+left+",width="+largeur+",height="+hauteur+","+config);
	}
	Popup.focus();
}

// Simulate user action of selecting a file to be returned to CKEditor
function sbTransfertCkeditor(file) {
	var funcNum = getUrlParam( 'CKEditorFuncNum' );
	var fileUrl = file;
	window.opener.CKEDITOR.tools.callFunction( funcNum, fileUrl );
	window.close();
}

// Check all uploads completed Fine Uploader
function sbAllUploadsCompleted() {
	// If and only if all of Fine Uploader's uploads are in a state of 
	// completion will this function fire the provided callback.

	// If there are 0 uploads in progress...
	if (uploader.getInProgress() === 0) {
		var failedUploads = uploader.getUploads({ status: qq.status.UPLOAD_FAILED });
		// ... and none have failed
		if (failedUploads.length === 0) {
			// They all have completed.
			return true;
		}
	}        
	return false;
}

// Enable disabled input
function sbEnabledInput(field) {
	$("input[name=video],input[name=youtube],input[name=photo],input[name=pdf]").attr('disabled','disabled').val('');
	$('input[name='+field+']').removeAttr('disabled');
}

// Upgrade Ajax
function sbUgrade(mode = 'core', url) {
	// Remove upgrade button
	$('#upgrade-core').remove();
	// Remove upgrade file list
	$('.sbupgrade-filelist').remove();
	// Show upgrade in progress
	$('#sbupgrade-inprogress').attr('style','display: block');
	
	// --- Action
	$.post( url + "upgrade.php", { m: mode })
	.done(function( data ) {
		var res = data.split("|");
		//alert( res[1] );
		$('#sbupgrade-inprogress').text(res[1]);
	});
}

// Full screen
function sbToggleFullScreen() {
	if (!document.mozFullScreen && !document.webkitFullScreen) {
		if (videoElement.mozRequestFullScreen) {
			videoElement.mozRequestFullScreen();
		} else {
			videoElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
		}
	} else {
		if (document.mozCancelFullScreen) {
			document.mozCancelFullScreen();
		} else {
			document.webkitCancelFullScreen();
		}
	}
}

// Insert Text at cursor
function sbInsertText(element, value) {
	var element_dom = document.getElementsByName(element)[0];
	if (document.selection) {
		element_dom.focus();
		sel = document.selection.createRange();
		sel.text = value;
		return;
	 }
	if (element_dom.selectionStart || element_dom.selectionStart == "0") {
		var t_start = element_dom.selectionStart;
		var t_end = element_dom.selectionEnd;
		var val_start = element_dom.value.substring(value, t_start);
		var val_end = element_dom.value.substring(t_end, element_dom.value.length);
		element_dom.value = val_start + value + val_end;
	} else {
		element_dom.value += value;
	}
}