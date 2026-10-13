
CKEDITOR.dialog.add('ckawesomeDialog', function( editor ) {
	// SBUIADMIN : liste des icônes Font Awesome 7 Free du sélecteur d'icônes
	// de l'admin (assets/adminator/icon-picker.js, window.SB_FA_ICON_LIST)
	// au lieu de lire les classes dans la feuille FA4 ; valeur = classe FA7
	// complète (ex: "fa-solid fa-star").
	function getCKAwesomeIcons(selectList ){
	     $.each(window.SB_FA_ICON_LIST || [], function( index, icon ) {
	     	selectList.add(icon.name + ' (' + icon.cls.split(' ')[0].substring(3) + ')', icon.cls);
	     });
	}

	function getSelectionOptions(selectList, start, inc, many){
	    var result = [];
	    var val = start; 
	    
	    result.push(start);
	    
	    many = many > 0 ? many : 5;
	    for(var i = 0; i < many; i++){
	    	val += inc;
	    	result.push(val);
	    }
	    
	    $.each(result, function( index, value ) {
	   		selectList.add(value, value);
	   	})
	}

	function formatCKAwesome (icon) {
	  if (!icon.id) { return icon.text; }
	  var text = icon.text.replace(/-/g, " ");
	  var icon = $('<span class="ckawesome_options"><i class="' + icon.element.value + ' fa-fw"></i> ' + text + "</span>");
	  return icon;
	};
	
    return {
        title: 'Insert CKAwesome',
        minWidth: 200,
        minHeight: 200,

        contents: [
            {
                id: 'options',
                label: 'Basic Settings',
                elements: [
                    {
					    type: 'select',
					    id: 'ckawesomebox',
					    label: 'Select font Awesome',
					    validate: CKEDITOR.dialog.validate.notEmpty( "Font Awesome field cannot be empty." ),
					    items: [[ editor.lang.common.notSet, '' ]],
					    onLoad: function () {
						   	getCKAwesomeIcons(this);
						   	var selectbx = $('#' + this.getInputElement().getAttribute('id'));  
						   	$(selectbx).select2({ width: "100%", templateResult: formatCKAwesome, templateSelection: formatCKAwesome});
					    },
					    onShow: function(){
					    	var selectbx = $('#' + this.getInputElement().getAttribute('id'));  
					    	$(selectbx).val('').trigger('change') ;
					    }
                    },
                    {
                        type: 'select',
                        id: 'textsize',
                        label: 'Select  size',
                        items: [[ editor.lang.common.notSet, '' ]],
                        onLoad: function (widget) {
                        	getSelectionOptions(this, 8, 1, 42);
                        }
                    },
                    {   
                        type: "hbox",
                        padding: 0,
                        widths: ["80%", "20%"],
                        children: [
                            {
                                id: 'fontcolor',
                                type: 'text',
                                label: 'Select color',
                                onChange: function( element ) {
                                	var idEl = $('#' +this.getInputElement().getAttribute('id'));
                                	idEl.css("background-color", idEl.val());
                                },
                                onKeyUp: function( element ) {
                                	var idEl = $('#' + this.getInputElement().getAttribute('id'));
                                	idEl.css("background-color", idEl.val());
                                },
        					    onShow: function(){
        					    	var idEl = $('#' + this.getInputElement().getAttribute('id'));  
                                	idEl.css("background-color", "");
        					    }
                            },
                            {
                                type: "button",
                                id: "fontcolorChooser",
                                "class": "colorChooser",
                                label: "Color",
                                style: "margin-left: 8px",
                                onLoad: function () {
                                    this.getElement().getParent().setStyle("vertical-align", "bottom")
                                },
                                onClick: function () {
                                    editor.getColorFromDialog(function (color) {
                                        color && this.getDialog().getContentElement("options", "fontcolor").setValue( color );
                                        this.focus()
                                    }, this)
                                }
                            }
                        ]
                    }
                ]
            },
        ],
        onOk: function() {
            var dialog = this;

            var cka = editor.document.createElement( 'span' );
            var cka_size = dialog.getValueOf( 'options', 'textsize' );
            var cka_color = dialog.getValueOf( 'options', 'fontcolor' );
            var cka_class = dialog.getValueOf( 'options', 'ckawesomebox' ) + " fa-fw";
            var cka_style = ( cka_size != '' ? 'font-size: '+cka_size+'px;' : '' ) + ( cka_color != '' ? 'color: '+cka_color+';' : '' ) ;
            
            cka.setAttribute( 'class', cka_class );
            if ( cka_style ) cka.setAttribute( 'style', cka_style );
            
            editor.insertElement( cka );
        }
    };
});

