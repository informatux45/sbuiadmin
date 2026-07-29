/*! HtmlEditor app.js
 * ================

 * @Author  Antonio Reina

 * @Email   <paoloantonioreina@gmail.com>
 * @version 0.0.2
 * @license MIT <http://opensource.org/licenses/MIT>
 */
/* global path */

$.cssHooks.backgroundColor = {
    get: function (elem) {
        if (elem.currentStyle)
            var bg = elem.currentStyle["background-color"];
        else if (window.getComputedStyle)
            var bg = document.defaultView.getComputedStyle(elem,
                    null).getPropertyValue("background-color");
        if (bg.search("rgb") == -1)
            return bg;
        else {
            bg = bg.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
            function hex(x) {
                return ("0" + parseInt(x).toString(16)).slice(-2);
            }

        }
    }
}

'use strict';
//Make sure jQuery has been loaded before app.js
if (typeof jQuery === "undefined") {
    throw new Error("HtmlEditor requires jQuery");
}


$(function () {
    //Set up the object
    _init();
});

/* ----------------------------------
 * ----------------------------------
 * All HTMLeditor functions are implemented below.
 */

// Instance Leaflet de l'aperçu affiché dans la modale de réglages du bloc
// "Carte" (#map-content) - une seule à la fois, voir case 'map' ci-dessous.
var sbLeafletPreviewMap = null;

// Initialise (ou réinitialise) les cartes Leaflet du bloc "Carte" -
// remplace l'ancien iframe Google Maps (statique, en HTTP, qui gênait le
// suivi de la souris pendant le drag & drop). Scopé à .htmlpage (jamais
// .sidebar-nav, dont le gabarit de la carte reste caché/non dimensionné -
// initialiser Leaflet sur un conteneur caché produit une carte grise
// vide tant qu'on ne force pas invalidateSize()).
// Idempotent (marqueur data-leaflet-init) : peut être rappelée sans
// risque après chaque glisser-déposer, seules les cartes réellement
// nouvelles sont initialisées.
function sbInitPageBuilderMaps() {
    if (typeof L === 'undefined') return;
    $('.htmlpage .sb-pagebuilder-map').each(function () {
        var el = this;
        if (el.getAttribute('data-leaflet-init')) return;
        el.setAttribute('data-leaflet-init', '1');

        var lat  = parseFloat(el.getAttribute('data-lat'))  || 48.8566;
        var lng  = parseFloat(el.getAttribute('data-lng'))  || 2.3522;
        var zoom = parseInt(el.getAttribute('data-zoom'), 10) || 13;

        // Le texte/HTML du marqueur vit dans l'attribut data-popup du
        // conteneur (pas un enfant : L.map(el) vide le conteneur pour y
        // placer ses propres panneaux de tuiles, un enfant caché n'y
        // survivrait pas - un attribut sur l'élément lui-même si).
        var popupHtml = el.getAttribute('data-popup') || '';

        var map = L.map(el).setView([lat, lng], zoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(map);
        var marker = L.marker([lat, lng]).addTo(map);
        if (popupHtml.trim() !== '') marker.bindPopup(popupHtml);

        el._sbLeafletMap = map;
        el._sbLeafletMarker = marker;
    });
}

function _init() {

    $(window).resize(function () {
        $("body").css("min-height", $(window).height() - 90);
        $(".htmlpage").css("min-height", $(window).height() - 160)
    });

    // Choose Editor by getting value "editor_toolbar"
    var editor_toolbar = $('input#editor_toolbar').val();
    switch (editor_toolbar) {
        default:
            CKEDITOR.replace( 'html5editor', {
                filebrowserBrowseUrl: 'index.php?p=transfert&editor=ck&type=Files'
            });
        break;
        case 'basic':
            CKEDITOR.replace( 'html5editor', {
                filebrowserBrowseUrl: 'index.php?p=transfert&editor=ck&type=Files',
                toolbarGroups: [
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                    { name: 'links', groups: [ 'links' ] },
                    { name: 'insert', groups: [ 'insert' ] },
                    { name: 'forms', groups: [ 'forms' ] },
                    { name: 'tools', groups: [ 'tools' ] },
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                    { name: 'others', groups: [ 'others' ] },
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                    { name: 'styles', groups: [ 'styles' ] },
                    { name: 'colors', groups: [ 'colors' ] },
                    { name: 'about', groups: [ 'about' ] }
                ],
                removeButtons: 'Underline,Subscript,Superscript,Styles,Format,About,Scayt,Anchor,Table,SpecialChar,Maximize,Cut,Copy,Outdent,Indent,Blockquote'
            });
        break;
        case 'simple':
            CKEDITOR.replace( 'html5editor', {
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
                removeButtons: 'Underline,Subscript,Superscript,Styles,Format,About,Scayt,Anchor,Table,SpecialChar,Maximize,Cut,Copy,Outdent,Indent,Blockquote,Undo,Redo,Paste,PasteText,PasteFromWord,NumberedList,BulletedList'
            });
        break;
    }
    CKEDITOR.config.allowedContent = true;

    $("body").css("min-height", $(window).height() - 90);
    $(".htmlpage").css("min-height", $(window).height() - 160);
    // $(".htmlpage").sortable({connectWith: ".lyrow", opacity: .35, handle: ".drag"});
    $(".htmlpage, .htmlpage .column").sortable({connectWith: ".column", opacity: .35, handle: ".drag"});
    // zIndex explicite : sans lui, le clone glissé (helper) hérite de
    // l'ordre d'empilement de son parent d'origine (la sidebar, ajoutée
    // via appendTo:"parent" par défaut) - sur une page où un panneau
    // voisin opaque chevauche visuellement le canevas (ex: pages.tpl,
    // colonne "Choix du template" à côté du Page Builder), le clone
    // passait dessous pendant le drag, invisible et raté au dépôt.
    $(".sidebar-nav .lyrow").draggable({connectToSortable: ".htmlpage", helper: "clone", handle: ".drag", zIndex: 9999, drag: function (e, t) {
            t.helper.width(400)
        }, stop: function (e, t) {
            // connectToSortable réutilise le helper de drag comme élément
            // sortable réel une fois déposé (pas un clone jetable) - le
            // style inline appliqué pendant le drag (opacity/position/
            // inset/z-index/width forcé à 400 par le "drag" ci-dessus)
            // reste donc figé sur le bloc pour de bon si on ne le retire
            // pas ici : le bloc apparaissait visuellement "opacifié" en
            // permanence après un simple glisser-déposer.
            t.helper.css({position: '', top: '', left: '', inset: '', opacity: '', zIndex: '', width: '', height: ''});
            $(".htmlpage .column").sortable({opacity: .35, connectWith: ".column"})

            // Repli manuel : sur certaines pages, connectToSortable de
            // jQuery UI ne détecte jamais le survol de .htmlpage (cause
            // précise non élucidée après investigation poussée - même
            // version jQuery UI 1.11.4, mêmes options, reproductible
            // uniquement sur certaines pages avec un canevas vide - voir
            // feedback_pagebuilder_debugging_lessons). cancelHelperRemoval
            // reste alors à true et le helper n'est jamais absorbé par le
            // sortable. Si le point de relâchement de la souris est bien
            // dans .htmlpage, on l'y ajoute nous-mêmes.
            var $canvas = $(".htmlpage");
            var canvasContainsHelper = $canvas.length && ($canvas[0] === t.helper[0] || $.contains($canvas[0], t.helper[0]));
            var offset = $canvas.offset();
            if ($canvas.length && !canvasContainsHelper) {
                if (offset && e.pageX >= offset.left && e.pageX <= offset.left + $canvas.outerWidth()
                    && e.pageY >= offset.top && e.pageY <= offset.top + $canvas.outerHeight()) {
                    // jQuery UI Draggable supprime lui-même son helper juste
                    // après avoir déclenché "stop" quand aucun sortable ne
                    // l'a formellement accepté (le helper porte encore
                    // .ui-draggable-dragging à ce stade) - on insère donc un
                    // CLONE indépendant plutôt que le helper original, que
                    // jQuery UI va faire disparaître juste après.
                    var $clone = t.helper.clone();
                    $clone.removeClass('ui-draggable ui-draggable-dragging ui-draggable-handle')
                        .css({position: '', top: '', left: '', inset: '', opacity: '', zIndex: '', width: '', height: ''});
                    $canvas.append($clone);
                    $(".htmlpage .column").sortable({opacity: .35, connectWith: ".column"});
                }
            }
            sbInitPageBuilderMaps();
        }});

    $(".sidebar-nav .box").draggable({connectToSortable: ".column", helper: "clone", handle: ".preview", zIndex: 9999, drag: function (e, t) {
            t.helper.width(400)
        }, stop: function (e, t) {
            // Même nettoyage que ci-dessus, pour les widgets (image/texte/...)
            // déposés directement dans une colonne existante.
            t.helper.css({position: '', top: '', left: '', inset: '', opacity: '', zIndex: '', width: '', height: ''});

            // Même repli manuel que pour les lignes ci-dessus, ici pour un
            // widget déposé dans une colonne existante.
            var alreadyPlaced = false;
            $(".column").each(function () {
                if (this === t.helper[0] || $.contains(this, t.helper[0])) alreadyPlaced = true;
            });
            if (!alreadyPlaced) {
                $(".column").each(function () {
                    var $col = $(this);
                    var offset = $col.offset();
                    if (offset && e.pageX >= offset.left && e.pageX <= offset.left + $col.outerWidth()
                        && e.pageY >= offset.top && e.pageY <= offset.top + $col.outerHeight()) {
                        // Clone indépendant - voir le commentaire équivalent
                        // dans le repli des lignes ci-dessus (jQuery UI
                        // supprime son propre helper après "stop").
                        var $clone = t.helper.clone();
                        $clone.removeClass('ui-draggable ui-draggable-dragging ui-draggable-handle')
                            .css({position: '', top: '', left: '', inset: '', opacity: '', zIndex: '', width: '', height: ''});
                        $col.append($clone);
                        return false;
                    }
                });
            }
            // Initialise Leaflet sur une éventuelle carte fraîchement
            // déposée (voir sbInitPageBuilderMaps() - sans effet sur les
            // dépôts d'autres types de blocs).
            sbInitPageBuilderMaps();
        }});

    $(document).on('click', 'a.clone', function (e) {
        e.preventDefault();
        var _s = $(this);

        var _row = _s.parent().clone();
        _row.hide();
        _row.insertAfter(_s.parent());
        _row.slideDown();

    });

    $(document).on('click', 'a.settings', function (e) {
        e.preventDefault();
        var _s = $(this);

        var part_id = _s.parent().parent().assignId();

        var part = _s.parent().parent();
        var column = _s.parent().parent().parent('.column');
        var row = _s.parent().parent().parent().parent('.row');

        prepareEditor(part, row, column);
    });


    $('a.btnpropa').on('click', function () {
        var rel = $(this).attr('rel');
        $('#buttonContainer a.btn').removeClass('btn-default')
                .removeClass('btn-success')
                .removeClass('btn-info')
                .removeClass('btn-danger')
                .removeClass('btn-info')
                .removeClass('btn-primary')
                .removeClass('btn-link')
                .addClass(rel);

    });
    $('a.btnpropb').on('click', function () {
        var rel = $(this).attr('rel');
        $('#buttonContainer a.btn').removeClass('btn-lg')
                .removeClass('btn-md')
                .removeClass('btn-sm')
                .removeClass('btn-xs')
                .addClass(rel);

    });

    $('a.btnprop').on('click', function () {
        var rel = $(this).attr('rel');
        $('#buttonContainer a.btn').toggleClass(rel);

    });

    $('#preferences').on('hidden.bs.modal', function () {
        $('#youtube').hide();
        $('#map').hide();
        $('#image').hide();
        $('#text').hide();
        $('#code').hide();
        $('#buttons').hide();
        // $('.active').removeClass('active');
    });

    $("#clear").click(function (e) {
        e.preventDefault();
        clearDemo()
    });
    $("#devpreview").click(function () {
        $("body").removeClass("edit sourcepreview");
        $("body").addClass("devpreview");
        removeMenuClasses();
        $(this).addClass("active");
        return false
    });


    $("#edit").click(function () {
        $('#add').hide();
        $("body").removeClass("devpreview sourcepreview");
        $("body").removeClass("tablet mobile");
        $("body").addClass("edit");
        removeMenuClasses();
        $(this).addClass("active");
        return false
    });



    $("#sourcepreview").click(function () {
        $('#pc').addClass('active');
        $('#add').show();
        $("body").removeClass("edit");
        $("body").addClass("devpreview sourcepreview");
        removeMenuClasses();
        $(this).addClass("active");
        return false
    });



    $('#pc').click(function () {
        $("body").removeClass("tablet mobile");
        $('#app button').removeClass('active');
        $(this).addClass('active');
    });


    $('#mobile').click(function () {
        $("body").removeClass("tablet");
        $('#app button').removeClass('active');
        $(this).addClass('active');
        $("body").addClass("mobile");
    });


    $('#tablet').click(function () {
        $("body").removeClass("mobile");
        $('#app button').removeClass('active');
        $(this).addClass('active');
        $("body").addClass("tablet");
    });

    $(".nav-header").click(function () {
        $(".sidebar-nav .boxes, .sidebar-nav .rows").hide();
        $(this).next().slideDown()
    });


    removeElm();
    gridSystemGenerator();

    // Cartes déjà présentes au chargement (contenu existant en édition).
    sbInitPageBuilderMaps();
}

function loadRowSettings(row) {
    // RowSettings - lire le style INLINE (row[0].style.x), jamais .css()
    // (qui renvoie la valeur CALCULÉE par le navigateur, donc toujours
    // quelque chose même sans réglage explicite). Sinon, appliquer les
    // réglages d'un bloc quelconque (même juste une image) figeait ces
    // valeurs par défaut en style inline via saveRowSettings() ci-dessous,
    // y compris une couleur de fond que personne n'avait choisie.
    var s = row[0].style;
    // paddings
    $('#tabRow input[data-ref="padding-top"]').val(s.paddingTop);
    $('#tabRow input[data-ref="padding-left"]').val(s.paddingLeft);
    $('#tabRow input[data-ref="padding-right"]').val(s.paddingRight);
    $('#tabRow input[data-ref="padding-bottom"]').val(s.paddingBottom);
    // margin
    $('#tabRow input[data-ref="margin-top"]').val(s.marginTop);
    $('#tabRow input[data-ref="margin-left"]').val(s.marginLeft);
    $('#tabRow input[data-ref="margin-right"]').val(s.marginRight);
    $('#tabRow input[data-ref="margin-bottom"]').val(s.marginBottom);
    // backgroundColor
    $('#rowbg').val(s.backgroundColor);
    // image
    $('#rowbgimage').val((s.backgroundImage || '').replace(/^url\(['"]?/,'').replace(/['"]?\)$/,''));
    // Css class : ne montrer que les classes personnalisées AJOUTÉES par
    // l'utilisateur - jamais "row"/"clearfix" (structurelles, requises
    // pour que la grille fonctionne, toujours réappliquées telles quelles
    // par saveRowSettings() quel que soit le contenu de ce champ).
    $('#rowcss').val(row.attr('class').split(/\s+/).filter(function (c) {
        return c !== 'row' && c !== 'clearfix';
    }).join(' '));
}

function saveRowSettings(row) {
    //RowSettings
    //padding
    row.css('padding-top', $('#tabRow input[data-ref="padding-top"]').val());
    row.css('padding-left', $('#tabRow input[data-ref="padding-left"]').val());
    row.css('padding-right', $('#tabRow input[data-ref="padding-right"]').val());
    row.css('padding-bottom', $('#tabRow input[data-ref="padding-bottom"]').val());
    // margin
    row.css('margin-top', $('#tabRow input[data-ref="margin-top"]').val());
    row.css('margin-left', $('#tabRow input[data-ref="margin-left"]').val());
    row.css('margin-right', $('#tabRow input[data-ref="margin-right"]').val());
    row.css('margin-bottom', $('#tabRow input[data-ref="margin-bottom"]').val());
    // backgroundColor
    row.css('background-color', $('#rowbg').val());
    // image
    if($("#rowbgimage").val()!="none")
    row.css('background-image',  'url("'+$("#rowbgimage").val()+'")');
    // Css class : "row"/"clearfix" toujours préservées (voir
    // loadRowSettings()), le champ n'ajoute que des classes en plus.
    row.attr('class', ('row clearfix ' + $('#rowcss').val()).trim());
}

function loadColumnSettings(column) {
    // Même principe que loadRowSettings() ci-dessus : style inline
    // uniquement, jamais la valeur calculée.
    var s = column[0].style;
    // paddings
    $('#tabCol input[data-ref="padding-top"]').val(s.paddingTop);
    $('#tabCol input[data-ref="padding-left"]').val(s.paddingLeft);
    $('#tabCol input[data-ref="padding-right"]').val(s.paddingRight);
    $('#tabCol input[data-ref="padding-bottom"]').val(s.paddingBottom);
    // margin
    $('#tabCol input[data-ref="margin-top"]').val(s.marginTop);
    $('#tabCol input[data-ref="margin-left"]').val(s.marginLeft);
    $('#tabCol input[data-ref="margin-right"]').val(s.marginRight);
    $('#tabCol input[data-ref="margin-bottom"]').val(s.marginBottom);
    // backgroundColor
    $('#colbg').val(s.backgroundColor);
    // Css class : ne montrer que les classes personnalisées AJOUTÉES par
    // l'utilisateur - jamais "column" (marqueur admin, retiré de toute
    // façon au nettoyage front) ni "col-md-X" (largeur de la colonne :
    // la perdre casserait complètement la mise en page), toujours
    // réappliquées telles quelles par saveColumnSettings().
    $('#colcss').val(column.attr('class').split(/\s+/).filter(function (c) {
        return c !== 'column' && !/^col-md-\d+$/.test(c);
    }).join(' '));
}
function saveColumnSettings(column) {
    //CellSettings
    //padding
    column.css('padding-top', $('#tabCol input[data-ref="padding-top"]').val());
    column.css('padding-left', $('#tabCol input[data-ref="padding-left"]').val());
    column.css('padding-right', $('#tabCol input[data-ref="padding-right"]').val());
    column.css('padding-bottom', $('#tabCol input[data-ref="padding-bottom"]').val());
    // margin
    column.css('margin-top', $('#tabCol input[data-ref="margin-top"]').val());
    column.css('margin-left', $('#tabCol input[data-ref="margin-left"]').val());
    column.css('margin-right', $('#tabCol input[data-ref="margin-right"]').val());
    column.css('margin-bottom', $('#tabCol input[data-ref="margin-bottom"]').val());
    // backgroundColor
    column.css('background-color', $('#colbg').val());
    // Css class : "column" + la classe de largeur col-md-X en cours
    // toujours préservées (voir loadColumnSettings()), le champ n'ajoute
    // que des classes en plus.
    var widthClass = (column.attr('class').match(/\bcol-md-\d+\b/) || ['col-md-12'])[0];
    column.attr('class', (widthClass + ' column ' + $('#colcss').val()).trim());
}

// Popover d'icônes contigu au bouton "Choisir" du bloc "Icône" - reprend la
// liste d'icônes du sélecteur global (assets/adminator/icon-picker.js,
// window.SB_FA_ICON_LIST) mais PAS son modal : ce modal (système Adminator,
// .modal.is-open) s'ouvrait derrière la modale Bootstrap 3 #preferences du
// Page Builder (2 systèmes de modale distincts, piles de z-index
// différentes, empilement non fiable) - un popover ancré au bouton évite le
// problème plutôt que de le contourner.
var sbIconPopover = null;
function sbCloseIconPopover() {
    if (sbIconPopover) { sbIconPopover.remove(); sbIconPopover = null; }
    $(document).off('click.sbIconPopover');
}
function sbOpenIconPopover(anchorBtn, onPick) {
    sbCloseIconPopover();
    var icons = window.SB_FA_ICON_LIST || [];
    var pop = $(
        '<div class="sb-icon-popover">' +
            '<input type="search" class="sb-icon-popover-search" placeholder="Rechercher...">' +
            '<div class="sb-icon-popover-grid"></div>' +
        '</div>'
    );
    $('body').append(pop);

    var offset = anchorBtn.offset();
    pop.css({
        top: offset.top + anchorBtn.outerHeight() + 4,
        left: offset.left
    });

    var grid = pop.find('.sb-icon-popover-grid');
    function render(filter) {
        grid.empty();
        icons.forEach(function (name) {
            if (filter && name.indexOf(filter) === -1) return;
            $('<button type="button" class="sb-icon-popover-item" title="' + name + '"><i class="fa fa-' + name + '"></i></button>')
                .on('click', function () {
                    onPick(name);
                    sbCloseIconPopover();
                })
                .appendTo(grid);
        });
    }
    render('');

    pop.find('.sb-icon-popover-search').on('input', function () {
        render($(this).val().trim().toLowerCase());
    }).focus();

    sbIconPopover = pop;
    // setTimeout : sans lui, le même clic sur anchorBtn qui vient d'ouvrir
    // le popover serait aussi capté par ce handler document (délégation) et
    // le refermerait aussitôt.
    setTimeout(function () {
        $(document).on('click.sbIconPopover', function (e) {
            if (!$(e.target).closest('.sb-icon-popover, [data-icon-popover-trigger]').length) {
                sbCloseIconPopover();
            }
        });
    }, 0);
}
$(document).on('click', '[data-icon-popover-trigger]', function (e) {
    e.preventDefault();
    var targetId = $(this).attr('data-icon-popover-trigger');
    var input = $('#' + targetId);
    var preview = $('#' + targetId + 'Preview');
    sbOpenIconPopover($(this), function (name) {
        input.val(name).trigger('change');
        preview.html('<i class="fa fa-fw fa-' + name + '"></i>');
    });
});

// Éditeur "liste de titre+contenu" partagé par les blocs Accordéon (une
// question/réponse par ligne) et Onglets (un titre d'onglet/contenu par
// ligne) - remplace l'édition en HTML brut (peu user-friendly, retour
// utilisateur du 2026-07-29) par un vrai formulaire répétable. Pas de
// glisser-déposer pour réordonner (boutons Monter/Descendre à la place) -
// ce widget a déjà eu des soucis de drag-and-drop capricieux ailleurs dans
// ce même fichier (connectToSortable, voir feedback_pagebuilder_debugging_lessons).
// Monter/Descendre/Supprimer : logique commune aux 2 éditeurs répétables
// (texte pour Accordéon/Onglets, image pour Galerie) - même conteneur DOM
// fixe (issu du template PHP) réutilisé à chaque ouverture du panneau,
// namespacé + off() avant on() pour ne pas empiler les handlers à chaque
// ouverture (même principe que confirm.unbind('click') dans prepareEditor()).
function sbWireRepeaterReorder(container) {
    container.off('click.sbRepeater').on('click.sbRepeater', '.sb-repeater-remove', function (e) {
        e.preventDefault();
        if (container.children('.sb-repeater-item').length <= 1) return;
        var thisRow = $(this).closest('.sb-repeater-item');
        // confirm.js (assets/adminator/confirm.js, chargé globalement) -
        // repli sur la suppression directe si jamais indisponible plutôt
        // que de bloquer l'action.
        if (window.sbShowConfirm) {
            window.sbShowConfirm('Supprimer cet élément ?', 'Supprimer', 'Annuler').then(function (ok) {
                if (ok) thisRow.remove();
            });
        } else {
            thisRow.remove();
        }
    });
    container.on('click.sbRepeater', '.sb-repeater-up', function () {
        var thisRow = $(this).closest('.sb-repeater-item');
        var prev = thisRow.prev();
        if (prev.length) thisRow.insertBefore(prev);
    });
    container.on('click.sbRepeater', '.sb-repeater-down', function () {
        var thisRow = $(this).closest('.sb-repeater-item');
        var next = thisRow.next();
        if (next.length) thisRow.insertAfter(next);
    });
}

function sbBuildRepeaterEditor(container, items, opts) {
    container.empty();
    items.forEach(function (item) {
        addRow(item.title, item.content);
    });

    function addRow(title, content) {
        var row = $(
            '<div class="sb-repeater-item">' +
                '<div class="sb-repeater-item-head">' +
                    '<input type="text" class="form-control sb-repeater-title" placeholder="' + opts.titlePlaceholder + '">' +
                    '<button type="button" class="btn btn-default btn-xs sb-repeater-up" title="Monter"><i class="fa fa-arrow-up"></i></button>' +
                    '<button type="button" class="btn btn-default btn-xs sb-repeater-down" title="Descendre"><i class="fa fa-arrow-down"></i></button>' +
                    '<button type="button" class="btn btn-danger btn-xs sb-repeater-remove" title="Supprimer"><i class="fa fa-trash"></i></button>' +
                '</div>' +
                '<textarea class="form-control sb-repeater-content" rows="2" placeholder="' + opts.contentPlaceholder + '"></textarea>' +
            '</div>'
        );
        row.find('.sb-repeater-title').val(title || '');
        row.find('.sb-repeater-content').val(content || '');
        container.append(row);
    }

    sbWireRepeaterReorder(container);

    return {
        addRow: addRow,
        getItems: function () {
            return container.children('.sb-repeater-item').map(function () {
                return {
                    title: $(this).find('.sb-repeater-title').val(),
                    content: $(this).find('.sb-repeater-content').val()
                };
            }).get();
        }
    };
}

var sbGalleryImgCounter = 0;

// Éditeur répétable spécifique à la Galerie : chaque ligne pointe vers le
// vrai sélecteur média du CMS (sbOpenPopup(), assets/dist/js/sb-custom.js,
// déjà utilisé par le bloc Image) plutôt qu'un champ texte d'URL - la
// popup de sélection écrit sa réponse en DOM brut
// (document.getElementById(id).value = ...) sur un id précis, donc chaque
// ligne a besoin d'un id STABLE et UNIQUE (compteur global jamais réutilisé,
// pas un index de position qui se décale au Monter/Descendre/Supprimer).
function sbBuildGalleryRepeaterEditor(container, items) {
    container.empty();
    items.forEach(function (item) {
        addRow(item.url, item.caption);
    });

    function addRow(url, caption) {
        var imgId = 'gallery-img-' + (sbGalleryImgCounter++);
        // sbTransfert() (sb-custom.js) écrase le "className" (pas juste le
        // contenu) de l'élément id+"Thumb" - le vrai id doit donc être sur
        // un <div> INTERNE dédié (comme #img-urlThumb du bloc Image, dont
        // le seul rôle est justement d'être ce receveur), jamais sur la
        // case de taille fixe elle-même (.sb-gallery-thumb), sous peine de
        // perdre son style de mise en page à la 1ère sélection d'image.
        var row = $(
            '<div class="sb-repeater-item">' +
                '<div class="sb-repeater-item-head">' +
                    '<div class="sb-gallery-thumb"><div id="' + imgId + 'Thumb" class="icon-transfert"><i class="fa fa-picture-o"></i></div></div>' +
                    '<input type="hidden" id="' + imgId + '" class="sb-gallery-url">' +
                    '<input type="text" class="form-control sb-gallery-caption" placeholder="Légende (optionnel)">' +
                    '<button type="button" class="btn btn-default btn-xs sb-gallery-browse">Parcourir</button>' +
                    '<button type="button" class="btn btn-default btn-xs sb-repeater-up" title="Monter"><i class="fa fa-arrow-up"></i></button>' +
                    '<button type="button" class="btn btn-default btn-xs sb-repeater-down" title="Descendre"><i class="fa fa-arrow-down"></i></button>' +
                    '<button type="button" class="btn btn-danger btn-xs sb-repeater-remove" title="Supprimer"><i class="fa fa-trash"></i></button>' +
                '</div>' +
            '</div>'
        );
        row.find('#' + imgId).val(url || '');
        row.find('.sb-gallery-caption').val(caption || '');
        if (url) {
            row.find('#' + imgId + 'Thumb').html('<img src="' + url + '">');
        }
        row.find('.sb-gallery-browse').on('click', function () {
            sbOpenPopup(imgId, 'jpg,jpeg,jpe,png,gif,tif,tiff,bmp,ico,svg', '', '');
        });
        container.append(row);
    }

    sbWireRepeaterReorder(container);

    return {
        addRow: addRow,
        getItems: function () {
            return container.children('.sb-repeater-item').map(function () {
                return {
                    url: $(this).find('.sb-gallery-url').val(),
                    caption: $(this).find('.sb-gallery-caption').val()
                };
            }).get();
        }
    };
}

// Ne garde que les items non vides (titre ET contenu vides = ligne laissée
// de côté par l'utilisateur) ; si tout est vide, garde un item minimal
// plutôt qu'un bloc totalement vide/cassé une fois enregistré.
function sbFilterEmptyItems(items, fallbackTitle) {
    var kept = items.filter(function (it) {
        return (it.title || '').trim() !== '' || (it.content || '').trim() !== '';
    });
    return kept.length ? kept : [{ title: fallbackTitle + ' 1', content: '' }];
}

function sbParseAccordionItems(part) {
    var items = [];
    part.find('.sb-accordion-item').each(function () {
        items.push({
            title: $(this).find('.sb-accordion-q').text(),
            content: $(this).find('.sb-accordion-a').text()
        });
    });
    return items.length ? items : [{ title: '', content: '' }];
}

function sbParseTabsItems(part) {
    var items = [];
    part.find('.sb-tabs-nav .sb-tabs-btn').each(function () {
        var tabId = $(this).attr('data-tab');
        items.push({
            title: $(this).text(),
            // .html() et non .text() : le contenu d'un onglet est interprété
            // comme du HTML (voir sbBuildTabsHtml()) - relire le HTML brut
            // permet de le ré-éditer tel quel plutôt que de le voir aplati
            // en texte brut (et perdre le balisage) à la prochaine ouverture.
            content: part.find('.sb-tabs-panel[data-tab="' + tabId + '"]').html() || ''
        });
    });
    return items.length ? items : [{ title: '', content: '' }];
}

function sbBuildAccordionHtml(items) {
    var wrap = $('<div class="sb-accordion"></div>');
    items.forEach(function (item, i) {
        var details = $('<details class="sb-accordion-item"></details>');
        if (i === 0) details.attr('open', 'open');
        $('<summary class="sb-accordion-q"></summary>').text(item.title || ('Question ' + (i + 1))).appendTo(details);
        $('<div class="sb-accordion-a"></div>').text(item.content).appendTo(details);
        wrap.append(details);
    });
    return wrap;
}

function sbBuildTabsHtml(items) {
    var wrap = $('<div class="sb-tabs"></div>');
    var nav = $('<div class="sb-tabs-nav"></div>');
    var panels = [];
    items.forEach(function (item, i) {
        var tabId = 'tab' + (i + 1);
        var btn = $('<button type="button" class="sb-tabs-btn"></button>').attr('data-tab', tabId).text(item.title || ('Onglet ' + (i + 1)));
        // .html() et non .text() : le client veut pouvoir saisir du HTML
        // (gras, liens, listes...) dans le contenu d'un onglet, interprété
        // tel quel sur le front plutôt qu'échappé en texte brut.
        var panel = $('<div class="sb-tabs-panel"></div>').attr('data-tab', tabId).html(item.content);
        if (i === 0) { btn.addClass('is-active'); panel.addClass('is-active'); }
        nav.append(btn);
        panels.push(panel);
    });
    wrap.append(nav);
    panels.forEach(function (panel) { wrap.append(panel); });
    return wrap;
}

function sbParseGalleryItems(part) {
    var items = [];
    part.find('.sb-gallery-item-link').each(function () {
        items.push({
            url: $(this).attr('href') || '',
            caption: $(this).attr('data-title') || ''
        });
    });
    return items.length ? items : [{ url: '', caption: '' }];
}

function sbFilterEmptyGalleryItems(items) {
    return items.filter(function (it) { return (it.url || '').trim() !== ''; });
}

function sbBuildGalleryHtml(items, groupId) {
    var wrap = $('<div class="sb-gallery"></div>').attr('data-lightbox-group', groupId);
    items.forEach(function (item) {
        var a = $('<a class="sb-gallery-item-link"></a>')
            .attr('data-lightbox', groupId)
            .attr('href', item.url);
        if (item.caption) a.attr('data-title', item.caption);
        $('<img class="sb-gallery-img">').attr('src', item.url).attr('alt', item.caption || '').appendTo(a);
        wrap.append(a);
    });
    return wrap;
}

var SB_PB_TYPE_LABELS = {
    paragraph: 'Texte',
    image: 'Image',
    button: 'Bouton',
    youtube: 'Vidéo',
    map: 'Carte',
    code: 'Code',
    separator: 'Séparateur',
    icon: 'Icône',
    quote: 'Citation',
    accordion: 'Accordéon',
    tabs: 'Onglets',
    gallery: 'Galerie'
};

function prepareEditor(part, row, column) {
    var clone = part.clone();
    var confirm = $('#applyChanges');
    $('#preferencesTitle').html(SB_PB_TYPE_LABELS[part.data('type')] || part.data('type'));

    $('.column .box').removeClass('active');
    part.addClass('active');
    confirm.unbind('click');

    var clonedPart = clone.find('div.view').html();
    var type = part.data('type');
    var panel = $('#Settings');

    loadRowSettings(row);
    loadColumnSettings(column);

    var o = part.find('div.view').children();
    var oid = o.assignId();
    $('#id').val(oid);
    $('#class').val(o.parent().parent().css('class'));
    $('#class').parent().show();
    $('#id').parent().show();
    switch (type) {

        /*
        case 'header':
            var editor = tinyMCE.get('html5editor');
            editor.setContent(clonedPart);
            $('#text').show();

            confirm.bind('click', function (e) {
                e.preventDefault();
                saveRowSettings(row);
                saveColumnSettings(column);
                o.html(editor.getContent());
                o.attr('id', $('#id').val());
                o.attr('class', $('#class').val());
            });
            break;
        */
        case 'paragraph':
            CKEDITOR.instances['html5editor'].setData(clonedPart);
            $('#text').show();
            
            var o = part.find('div.view');
            confirm.bind('click', function (e) {
                e.preventDefault();
                saveRowSettings(row);
                saveColumnSettings(column);
                o.html(CKEDITOR.instances['html5editor'].getData());
                o.attr('id', $('#id').val());
                //o.attr('class', $('#class').val());
                $('#preferences').modal('hide');
            });

        break;

        case 'image':
            var img = part.find('img');

            $('#img-urlThumb').html(img.clone().attr('width', '200'));
            $('#img-url').val(img.attr('src'));
            $('#img-width').val(img.innerWidth());
            $('#img-height').val(img.innerHeight());
            $('#img-title').val(img.attr('title'));
            $('#class').val(img.attr('class'));
            $('#img-rel').val(img.attr('rel'));
            $('#img-title').val(img.attr('title'));
            // $('#img-clickurl').val(img.attr('onClick'));
            $('#img-lightbox').prop('checked', img.parent('a.sb-lightbox-link').length > 0);
            $('#image').show();

            confirm.bind('click', function (e) {
                e.preventDefault();
                saveRowSettings(row);
                saveColumnSettings(column);
                img.attr('title', $('#img-title').val());
                img.attr('src', $('#img-url').val());
                img.css('width', $('#img-width').val());
                img.css('height', $('#img-height').val());
                img.attr('class', $('#class').val());
                img.attr('rel', $('#img-rel').val());
                //    img.attr('onClick', $('#img-clickurl').val());
                o.attr('id', $('#id').val());
                o.removeClass();
                o.addClass($('#class').val());
                // Lightbox : dé-wrap puis re-wrap systématiquement plutôt que
                // de tester l'état courant, pour que le href reste toujours
                // synchronisé avec l'URL d'image éventuellement modifiée
                // au-dessus (sinon un ancien wrapper garderait un href périmé).
                if (img.parent('a.sb-lightbox-link').length) {
                    img.unwrap();
                }
                if ($('#img-lightbox').is(':checked')) {
                    img.wrap('<a class="sb-lightbox-link" data-lightbox="pb-gallery" href="' + img.attr('src') + '"></a>');
                }
                $('#preferences').modal('hide');
            });

        break;
    
        case 'youtube':
            var iframe = part.find('iframe');
            $('#youtube-video').html(iframe.clone().css('width', '100%'));
            $('#video-url').val(iframe.attr('src'));
            $('#video-width').val(iframe.innerWidth());
            $('#video-height').val(iframe.innerHeight());
            $('#youtube').show();

            confirm.bind('click', function (e) {
                e.preventDefault();
                saveRowSettings(row);
                saveColumnSettings(column);
                o.attr('src', $('#video-url').val());
                o.css('width', $('#video-width').val());
                o.css('height', $('#video-height').val());
                o.attr('id', $('#id').val());
                o.attr('class', $('#class').val());
                $('#preferences').modal('hide');
            });
        break;
    
        case 'map':
            // #id/#class sont génériques à tous les blocs, mais pour la
            // Carte l'écriture de #class sur o (= mapDiv) écraserait la
            // classe sb-pagebuilder-map elle-même : la 1ère confirmation
            // "réussit" silencieusement, puis la réouverture suivante des
            // réglages ne retrouve plus l'élément (part.find() vide) et
            // plante sur mapDiv[0] au clic Confirmer suivant.
            $('#class').parent().hide();
            $('#id').parent().hide();

            var mapDiv = part.find('.sb-pagebuilder-map');
            var mLat  = parseFloat(mapDiv.attr('data-lat'))  || 48.8566;
            var mLng  = parseFloat(mapDiv.attr('data-lng'))  || 2.3522;
            var mZoom = parseInt(mapDiv.attr('data-zoom'), 10) || 13;
            var mPopup = mapDiv.attr('data-popup') || '';

            $('#map').show();
            $('#map-width').val(mapDiv.width());
            $('#map-height').val(mapDiv.height());
            $('#latitude').val(mLat);
            $('#longitude').val(mLng);
            $('#zoom').val(mZoom);
            $('#map-popup').val(mPopup);

            // Un même #map-content ne peut pas être réinitialisé par
            // Leaflet une 2e fois sans nettoyer l'instance précédente
            // (sbLeafletPreviewMap, module-level) - sinon "Map container
            // is already initialized" à la prochaine ouverture de ce
            // panneau, y compris après un Annuler/fermeture sans Confirmer.
            if (sbLeafletPreviewMap) {
                sbLeafletPreviewMap.remove();
                sbLeafletPreviewMap = null;
            }
            sbLeafletPreviewMap = L.map('map-content').setView([mLat, mLng], mZoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19
            }).addTo(sbLeafletPreviewMap);
            var previewMarker = L.marker([mLat, mLng], {draggable: true}).addTo(sbLeafletPreviewMap);
            if (mPopup.trim() !== '') previewMarker.bindPopup(mPopup);
            previewMarker.on('dragend', function () {
                var pos = previewMarker.getLatLng();
                $('#latitude').val(pos.lat.toFixed(6));
                $('#longitude').val(pos.lng.toFixed(6));
            });
            // La modale (display:none avant ouverture) donne une taille
            // nulle au conteneur au moment de l'init - sans ce recalcul
            // après affichage, Leaflet ne charge qu'une partie des tuiles.
            setTimeout(function () { sbLeafletPreviewMap.invalidateSize(); }, 200);

            $('#latitude, #longitude, #zoom').off('change.sbmap').on('change.sbmap', function () {
                var newLat  = parseFloat($('#latitude').val()) || mLat;
                var newLng  = parseFloat($('#longitude').val()) || mLng;
                var newZoom = parseInt($('#zoom').val(), 10) || mZoom;
                sbLeafletPreviewMap.setView([newLat, newLng], newZoom);
                previewMarker.setLatLng([newLat, newLng]);
            });
            $('#map-popup').off('change.sbmap').on('change.sbmap', function () {
                var txt = $('#map-popup').val();
                if (txt.trim() !== '') { previewMarker.bindPopup(txt).openPopup(); }
            });

            confirm.bind('click', function (e) {
                e.preventDefault();
                saveRowSettings(row);
                saveColumnSettings(column);
                var finalLat  = parseFloat($('#latitude').val()) || mLat;
                var finalLng  = parseFloat($('#longitude').val()) || mLng;
                var finalZoom = parseInt($('#zoom').val(), 10) || mZoom;
                var finalPopup = $('#map-popup').val();
                mapDiv.attr('data-lat', finalLat);
                mapDiv.attr('data-lng', finalLng);
                mapDiv.attr('data-zoom', finalZoom);
                mapDiv.attr('data-popup', finalPopup);
                mapDiv.css({width: $('#map-width').val() || '100%', height: $('#map-height').val() || '300px'});
                // Répercute sur la carte déjà affichée dans le canevas
                // (sans la détruire/recréer) si elle est déjà initialisée.
                if (mapDiv[0]._sbLeafletMap) {
                    mapDiv[0]._sbLeafletMap.setView([finalLat, finalLng], finalZoom);
                    mapDiv[0]._sbLeafletMap.invalidateSize();
                    if (mapDiv[0]._sbLeafletMarker) {
                        mapDiv[0]._sbLeafletMarker.setLatLng([finalLat, finalLng]);
                        if (finalPopup.trim() !== '') mapDiv[0]._sbLeafletMarker.bindPopup(finalPopup);
                    }
                }
                if (sbLeafletPreviewMap) {
                    sbLeafletPreviewMap.remove();
                    sbLeafletPreviewMap = null;
                }
                $('#preferences').modal('hide');
            });


        break;
    
        case 'code':
            $('#class').parent().hide();
            $('#id').parent().hide();

            var txt = $('#code');
            $('#codeeditor').remove();
            txt.append('<textarea id="codeeditor" style="min-height:150px;width:100%; display:block;">'+style_html(part.find('div.view').html())+'</textarea>');
            txt.show();

            confirm.bind('click', function (e) {
                e.preventDefault();
                saveRowSettings(row);
                saveColumnSettings(column);
                part.find('div.view').html($('#codeeditor').val());
                $('#preferences').modal('hide');
            });
        break;

        case 'accordion':
            $('#class').parent().hide();
            $('#id').parent().hide();

            var accordionItems = sbParseAccordionItems(part);
            var accordionRepeater = sbBuildRepeaterEditor($('#accordion-items'), accordionItems, {
                titlePlaceholder: 'Question',
                contentPlaceholder: 'Réponse'
            });
            $('#accordion-add').off('click').on('click', function () {
                accordionRepeater.addRow('', '');
            });
            $('#accordion').show();

            confirm.bind('click', function (e) {
                e.preventDefault();
                saveRowSettings(row);
                saveColumnSettings(column);
                var items = sbFilterEmptyItems(accordionRepeater.getItems(), 'Question');
                part.find('div.view').empty().append(sbBuildAccordionHtml(items));
                $('#preferences').modal('hide');
            });
        break;

        case 'tabs':
            $('#class').parent().hide();
            $('#id').parent().hide();

            var tabsItems = sbParseTabsItems(part);
            var tabsRepeater = sbBuildRepeaterEditor($('#tabs-items'), tabsItems, {
                titlePlaceholder: 'Titre de l\'onglet',
                contentPlaceholder: 'Contenu (HTML autorisé)'
            });
            $('#tabs-add').off('click').on('click', function () {
                tabsRepeater.addRow('', '');
            });
            $('#tabs').show();

            confirm.bind('click', function (e) {
                e.preventDefault();
                saveRowSettings(row);
                saveColumnSettings(column);
                var items = sbFilterEmptyItems(tabsRepeater.getItems(), 'Onglet');
                part.find('div.view').empty().append(sbBuildTabsHtml(items));
                $('#preferences').modal('hide');
            });
        break;

        case 'gallery':
            $('#class').parent().hide();
            $('#id').parent().hide();

            // Groupe lightbox stable : conservé tel quel s'il existe déjà
            // (relecture depuis le bloc en cours d'édition), sinon généré
            // une seule fois - pour que ce ne soit jamais régénéré à
            // chaque ouverture des réglages (romprait le regroupement des
            // images dans la visionneuse) et que 2 blocs Galerie
            // différents sur la même page ne mélangent jamais leurs
            // images dans la même visionneuse.
            var galleryGroupId = part.find('.sb-gallery').attr('data-lightbox-group') || ('pbgal-' + s4() + s4());
            var galleryItems = sbParseGalleryItems(part);
            var galleryRepeater = sbBuildGalleryRepeaterEditor($('#gallery-items'), galleryItems);
            $('#gallery-add').off('click').on('click', function () {
                galleryRepeater.addRow('', '');
            });
            $('#gallery').show();

            confirm.bind('click', function (e) {
                e.preventDefault();
                saveRowSettings(row);
                saveColumnSettings(column);
                var items = sbFilterEmptyGalleryItems(galleryRepeater.getItems());
                part.find('div.view').empty().append(sbBuildGalleryHtml(items, galleryGroupId));
                $('#preferences').modal('hide');
            });
        break;

        case 'button':
            var btn = part.find('.view > a.btn');
            var btn_id = btn.assignId();
            var clone = btn.clone();
            $('#buttonContainer').html(clone);
            $('#buttonId').val(btn_id);
            $('#buttonLabel').val(btn.text());
            $('#buttonHref').val(btn.attr('href'));
            $('#buttons').show();

            confirm.bind('click', function (e) {
                e.preventDefault();
                saveRowSettings(row);
                saveColumnSettings(column);
                btn.text($('#buttonLabel').val());
                btn.attr('href', $('#buttonHref').val());
                btn.css('background', $('#colbtn').val());
                btn.css('width', $('#custombtnwidth').val());
                btn.css('height', $('#custombtnheight').val());
                btn.css('font-size', $('#custombtnfont').val());
                btn.css('padding-top', $('#custombtnpaddingtop').val());
                btn.css('color', $('#colbtncol').val());
                //btn.css('align', $('#btnalign').val());
                o.attr('id', $('#id').val());
                btn.attr('class', $('#buttonContainer > a.btn').attr('class'));
                o.attr('class', $('#class').val());
                $('#preferences').modal('hide');
            });
        break;

        case 'separator':
            $('#id').parent().hide();
            $('#class').parent().hide();

            var hr = part.find('hr.sb-separator');
            $('#sep-style').val(hr.css('border-top-style') || 'solid');
            $('#sep-margin').val(parseInt(hr.css('margin-top'), 10) || 20);
            $('#separator').show();

            confirm.bind('click', function (e) {
                e.preventDefault();
                saveRowSettings(row);
                saveColumnSettings(column);
                var margin = $('#sep-margin').val() || 20;
                hr.css('border-top-style', $('#sep-style').val());
                hr.css('margin-top', margin + 'px');
                hr.css('margin-bottom', margin + 'px');
                $('#preferences').modal('hide');
            });
        break;

        case 'icon':
            var iconEl = part.find('.sb-icon');
            // Convention alignée sur addIconFA()/icon-picker.js : nom brut
            // sans préfixe "fa-" (ex: "star") - le picker écrit/lit cette
            // valeur telle quelle dans #icon-class.
            var currentIconName = (iconEl.attr('class') || '').split(/\s+/).filter(function (c) {
                return c.indexOf('fa-') === 0 && c !== 'fa-2x';
            })[0];
            currentIconName = currentIconName ? currentIconName.replace(/^fa-/, '') : 'star';
            $('#icon-class').val(currentIconName);
            $('#icon-classPreview').html('<i class="fa fa-fw fa-' + currentIconName + '"></i>');
            $('#icon-size').val(parseInt(iconEl.css('font-size'), 10) || 32);
            $('#icon-color').val(iconEl.css('color'));
            $('#icon').show();

            confirm.bind('click', function (e) {
                e.preventDefault();
                saveRowSettings(row);
                saveColumnSettings(column);
                var newName = ($('#icon-class').val() || 'star').trim().replace(/^fa-/, '');
                iconEl.attr('class', 'sb-icon fa fa-' + newName);
                iconEl.css('font-size', ($('#icon-size').val() || 32) + 'px');
                iconEl.css('color', $('#icon-color').val());
                $('#preferences').modal('hide');
            });
        break;

        case 'quote':
            $('#id').parent().hide();
            $('#class').parent().hide();

            var quoteBlock = part.find('.sb-quote');
            var quoteText = part.find('.sb-quote-text');
            var quoteAuthor = part.find('.sb-quote-author');
            $('#quote-text').val(quoteText.text());
            $('#quote-author').val(quoteAuthor.text());
            $('#quote-color').val(quoteBlock.css('border-left-color'));
            $('#quote').show();

            confirm.bind('click', function (e) {
                e.preventDefault();
                saveRowSettings(row);
                saveColumnSettings(column);
                quoteText.text($('#quote-text').val());
                quoteAuthor.text($('#quote-author').val());
                var accent = $('#quote-color').val();
                if (accent) quoteBlock.css('border-left-color', accent);
                $('#preferences').modal('hide');
            });
        break;
    }
    $('#preferences').modal('show').draggable();
}

$(document).on('focusin', function(e) {
    if ($(e.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
    }
});

function handleSaveLayout() {
    var e = $(".htmlpage").html();
    if (e != window.htmlpageHtml) {
        saveLayout();
        window.htmlpageHtml = e
    }
}

function gridSystemGenerator() {
    $(".lyrow .preview input").bind("keyup", function () {
        var e = 0;
        var t = "";
        var n = false;
        var r = $(this).val().split(" ", 12);
        $.each(r, function (r, i) {
            if (!n) {
                if (parseInt(i) <= 0)
                    n = true;
                e = e + parseInt(i);
                t += '<div class="col-md-' + i + ' column"></div>'
            }
        });
        if (e == 12 && !n) {
            $(this).parent().next().children().html(t);
            $(this).parent().find('.drag').show()
        } else {
            $(this).parent().find('.drag').hide()
        }
    })
}
function removeElm() {
    $(".htmlpage").delegate(".remove", "click", function (e) {
        var b = $(this).parent().css('border');
        $(this).parent().css('border', '2px dotted red');

        if (confirm("Êtes-vous sûr de vouloir supprimer la partie sélectionnée ?")) {
            e.preventDefault();
            $(this).parent().remove();

            if (!$(".htmlpage .lyrow").length > 0) {
                clearDemo();
            }
        } else {
            $(this).parent().css('border', b);
        }
    })
}
function clearDemo() {
    $(".htmlpage").empty()
}
function removeMenuClasses() {
    $("#menu-htmleditor li button").removeClass("active")
}
function changeStructure(e, t) {
    $("#download-layout ." + e).removeClass(e).addClass(t)
}
function cleanHtml(e) {
    $(e).parent().append($(e).children().html());
}

function cleanRow(row) {

    row.children('.remove , .drag, .preview').remove();
    row.find('div.ui-sortable').removeClass('ui-sortable');

    row.children('.view').find('br').remove();

    row.children('.view').children('.row').children('.column').each(function () {
        // se ci dovessero essere righe nella colonna allora :
        var col = $(this);

        col.removeClass('column');
        col.children('.lyrow').each(function () {
            cleanRow($(this));
        });
        col.children('.box-element').each(function () {
            var element = $(this);
            element.children('.remove , .drag, .configuration, .preview').remove();
            element.parent().append(element.children('.view').html());
            element.remove();
        });
    });
    row.parent().append(row.children('.view').html());
    row.remove();
}

// Renommage des classes Bootstrap qui survivent dans le HTML exporté
// (grille .row/.col-md-X/.clearfix, boutons .btn/.btn-*, média
// .img-responsive - tout le reste, poignées drag/remove/configuration
// et modale de réglages, est du chrome d'édition retiré par cleanRow()
// avant même d'arriver ici) en équivalents préfixés .sb*, pour que ce
// HTML n'entre jamais en conflit avec le Bootstrap (même version, autre
// version, ou absence de Bootstrap) du thème front qui l'affichera.
// Feuille de style correspondante : assets/adminator/pagebuilder-front.css.
// Portage identique côté PHP : inc/functions.php, sbRenamePageBuilderClasses().
var SB_CLASS_RENAME_MAP = {
    'row': 'sbrow',
    'clearfix': 'sbclearfix',
    'img-responsive': 'sbimg-responsive',
    'btn': 'sbbtn'
};

function sbRenameClasses(html) {
    // N'agit que sur le contenu des attributs class="..." - jamais sur le
    // texte/contenu (un bouton libellé "Ma row info" ne doit pas devenir
    // "Ma sbrow info"). Comparaison par TOKEN entier (split sur les
    // espaces), pas par sous-chaîne \b...\b - un tiret compte comme limite
    // de mot en JS aussi, donc \bbtn\b matchait aussi le "btn" caché dans
    // un token composé comme "sb-tabs-btn" (bloc Onglets) et le corrompait
    // en "sb-tabs-sbbtn".
    return html.replace(/class="([^"]*)"/g, function (match, classes) {
        var renamed = classes.split(/\s+/).map(function (cls) {
            if (cls === '') return cls;
            if (SB_CLASS_RENAME_MAP.hasOwnProperty(cls)) return SB_CLASS_RENAME_MAP[cls];
            var colMatch = cls.match(/^col-md-(\d+)$/);
            if (colMatch) return 'sbcol-md-' + colMatch[1];
            var btnMatch = cls.match(/^btn-([a-z]+)$/);
            if (btnMatch) return 'sbbtn-' + btnMatch[1];
            return cls;
        });
        return 'class="' + renamed.join(' ') + '"';
    });
}

// Code final (chrome d'édition retiré + classes renommées + indenté),
// calculé à partir d'une COPIE hors-DOM de .htmlpage - cleanRow() retire
// des éléments du DOM, on ne l'appelle donc jamais sur le vrai canevas
// d'édition (voir bouton #pbShowCode plus bas).
function generatePageBuilderCode() {
    var copy = $('<div>').html($('.htmlpage').html());
    copy.children('.lyrow').each(function () {
        cleanRow($(this));
    });
    return style_html(sbRenameClasses(copy.html()));
}

$(function () {
    $(document).on('click', '#pbShowCode', function (e) {
        e.preventDefault();
        $('#pbCodeOutput').text(generatePageBuilderCode());
        $('#pbCodeModal').modal('show');
    });

    $(document).on('click', '#pbCodeCopy', function (e) {
        e.preventDefault();
        var code = $('#pbCodeOutput').text();
        var $btn = $(this);
        var restoreLabel = function () {
            setTimeout(function () { $btn.text('Copier'); }, 1500);
        };
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(code).then(function () {
                $btn.text('Copié !');
                restoreLabel();
            });
        } else {
            // Repli pour les contextes sans Clipboard API (HTTP non
            // sécurisé, navigateurs anciens) : textarea temporaire +
            // execCommand, seule méthode de copie encore disponible là.
            var $tmp = $('<textarea>').val(code).css({position: 'fixed', left: '-9999px'}).appendTo('body');
            $tmp.select();
            document.execCommand('copy');
            $tmp.remove();
            $btn.text('Copié !');
            restoreLabel();
        }
    });
});

// Sauvegarde basée sur le comportement standard des formulaires SBUIADMIN
// (bouton Ajouter/Modifier, comme n'importe quel autre champ) - pas de
// bouton "Save"/modale propre au widget. Le contenu du builder est
// synchronisé dans le vrai champ soumis (voir addPageBuilder()/
// sbuiadmin-form.php, data-pagebuilder-target) juste avant l'envoi du
// formulaire, comme CKEditor le fait déjà pour ses propres champs.
// $(function(){...}) et pas une IIFE immédiate : ce <script> est chargé
// par addPageBuilder() AVANT son propre HTML (CSS/JS d'abord, balisage
// ensuite) - un binding immédiat ne trouve donc ni data-pagebuilder-
// target ni .htmlpage (pas encore dans le DOM) et ne fait jamais rien,
// silencieusement. _init() plus haut a exactement le même besoin
// d'attendre le DOM, d'où le même $(function(){...}).
$(function () {
    var $target = $('[data-pagebuilder-target]');
    var targetId = $target.attr('data-pagebuilder-target');
    if (!targetId) return;

    $target.closest('form').on('submit', function () {
        // PAS de cleanRow() ici (contrairement à l'ancien downloadLayoutSrc(),
        // export à usage unique) : ce widget doit permettre de resauvegarder
        // et rééditer indéfiniment le même contenu, il faut donc conserver
        // tout le chrome d'édition (poignées drag/remove/clone/configuration)
        // dans ce qui est stocké - un nettoyage à la sauvegarde produit un
        // HTML propre mais définitivement plus éditable au rechargement.
        $('#' + targetId).val($('.htmlpage').html());
    });
});

// sbTransfert() (assets/dist/js/sb-custom.js, partagée par tout le CMS - ne
// pas y toucher, ça casserait les autres champs médias) range volontairement
// le nom de fichier seul dans le champ ciblé (ex: "xxx.png"), jamais un
// chemin utilisable une fois la page affichée hors de l'admin - la
// miniature (id+"Thumb") reçoit elle un chemin relatif à la popup de
// sélection ("../upload/xxx.png"), tout aussi inutilisable ailleurs. On
// reconstruit ici l'URL absolue du site (window.sbMediasUrl, voir
// addPageBuilder()/sbuiadmin-form.php - même constante _AM_MEDIAS_URL
// utilisée partout ailleurs dans le CMS) + le nom de fichier déjà correct.
//
// Généralisé à N'IMPORTE QUEL champ id+"Thumb"/id (pas seulement le
// img-url/img-urlThumb fixe du bloc Image) : les lignes de la Galerie
// (gallery-img-0, gallery-img-1...) sont créées/détruites dynamiquement,
// impossible d'observer un élément précis qui n'existe pas encore au
// chargement de la page - un seul observer délégué sur le document couvre
// tous les champs présents ET futurs.
$(function () {
    if (!window.MutationObserver || !window.sbMediasUrl) return;

    new MutationObserver(function (mutations) {
        mutations.forEach(function (m) {
            var thumb = m.target;
            if (!thumb.id || thumb.id.slice(-5) !== 'Thumb') return;
            var target = document.getElementById(thumb.id.slice(0, -5));
            if (!target) return;
            var filename = target.value;
            // Ne préfixe que si c'est encore un nom de fichier nu - évite
            // de re-préfixer une valeur déjà complète (relecture d'un item
            // existant à l'ouverture des réglages, ou mutation non liée à
            // une sélection média).
            if (filename && filename.indexOf('://') === -1 && filename.indexOf(window.sbMediasUrl) === -1) {
                target.value = window.sbMediasUrl + '/' + filename;
            }
        });
    }).observe(document.body, { childList: true, subtree: true });
});


function getIndent(level) {
    var result = '',
            i = level * 4;
    if (level < 0) {
        throw "Level is below 0";
    }
    while (i--) {
        result += ' ';
    }
    return result;
}

function style_html(html) {
    html = html.trim();
    var result = '',
            indentLevel = 0,
            tokens = html.split(/</);
    for (var i = 0, l = tokens.length; i < l; i++) {
        var parts = tokens[i].split(/>/);
        if (parts.length === 2) {
            if (tokens[i][0] === '/') {
                indentLevel--;
            }
            result += getIndent(indentLevel);
            if (tokens[i][0] !== '/') {
                indentLevel++;
            }

            if (i > 0) {
                result += '<';
            }

            result += parts[0].trim() + ">\n";
            if (parts[1].trim() !== '') {
                result += getIndent(indentLevel) + parts[1].trim().replace(/\s+/g, ' ') + "\n";
            }

            if (parts[0].match(/^(img|hr|br)/)) {
                indentLevel--;
            }
        } else {
            result += getIndent(indentLevel) + parts[0] + "\n";
        }
    }
    return result;
}

function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
}

function gup(name, url) {
    if (!url)
        url = location.href;
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(url);
    return results == null ? null : results[1];
}


(function ($) {

    $.fn.assignId = function () {
        var _self = $(this);
        var id = _self.attr('id');
        if (typeof id === typeof undefined || id === false) {

            //id = s4() + '-' + s4() + '-' + s4() + '-' + s4();
            id = '';
            _self.attr('id', id);

        }
        return id;
    };

})(jQuery);
