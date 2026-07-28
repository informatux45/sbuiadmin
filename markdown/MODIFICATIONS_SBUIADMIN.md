# MODIFICATIONS SBUIADMIN

## 2026-07-22

### Fix erreur fatale à l'accès du backdoor admin

- **Fichier** : `backdoor/inc/class/sbuiadmin-users.php`
- **Problème** : Erreur fatale `Uncaught Error: Call to a member function displayText() on null` dans `updateAccessLog()` (ligne 86), qui bloquait totalement l'accès à la page d'administration (backdoor).
- **Cause** : La méthode utilisait l'objet global `$sbsanitize` sans le déclarer avec `global $sbsanitize;`, contrairement aux autres méthodes de la classe (ex: `getUserInfo()`). PHP 8 lève une erreur fatale sur un appel de méthode sur `null` (auparavant silencieux/warning en PHP 7).
- **Correctif** : Ajout de `global $sbsanitize;` en début de méthode `updateAccessLog()`.
- Commit : `81e05b5`

### Fix alerte vide sur la page de login

- **Fichier** : `backdoor/core/tpls/tpl/system/login.tpl`
- **Problème** : Un encadré d'alerte rouge (`alert alert-danger alert-danger-custom`) s'affichait vide sur la page de connexion, même sans avoir rien saisi ni soumis le formulaire.
- **Cause** : `backdoor/index.php` initialise `sbuiadmin_access_code` à `false` à chaque chargement de la page (avant toute tentative de connexion). Le template testait `{if isset($sbuiadmin_access_code)}`, qui est vrai même quand la valeur vaut `false` (Smarty `isset` teste l'existence, pas la véracité) — d'où l'affichage du cadre sans texte (aucun des cas E1/E2/E3/E4 ne correspondant à `false`).
- **Correctif** : Remplacement du test par `{if $sbuiadmin_access_code}`, qui n'affiche le bloc que si le code vaut réellement une des valeurs d'erreur.
- Commit : `81e05b5`

## 2026-07-22 (suite) — Refonte charte graphique du backdoor (thème Adminator)

Début d'un chantier par étapes visant à remplacer entièrement l'ancien thème Bootstrap 3 / jQuery (~2016, "SB Admin 2") du backdoor par le thème moderne **Adminator** (CSS Grid + variables CSS, mode sombre natif). Plan détaillé en 8 phases conservé dans `/home/patrice/.claude-patrice/plans/ethereal-bouncing-volcano.md`.

### Phase 0 — Infrastructure

- **Nouveau** : `backdoor/assets/adminator/` — assets du thème Adminator (CSS compilé, bundle JS `2026.js`/`vendors.js`/`vendor-chartjs.js`/`vendor-fullcalendar.js`/`runtime.js`, polices, images).
- Polices Google Fonts (Inter, Inter Tight, JetBrains Mono, sous-ensembles latin + latin-ext) **auto-hébergées** dans `assets/adminator/fonts/` — suppression de l'`@import` externe vers `fonts.googleapis.com` dans `style.css` (plus de dépendance CDN à l'exécution).
- `backdoor/core/tpls/tpl/header.tpl` : ajout d'un script inline de bootstrap du mode sombre (`data-theme`, lu depuis `localStorage`/préférence système) exécuté avant le premier rendu pour éviter le flash de mauvais thème.
- Chargement des assets Adminator activé uniquement pour les pages déjà migrées (`{if $page == 'login'}` pour l'instant), en parallèle de l'ancien stack Bootstrap 3/jQuery/Font Awesome CDN (conservé pour les pages pas encore portées) — migration additive, page par page.
- Effet de bord corrigé : l'ancien `assets/dist/css/sb-admin-custom-login.css` contenait une règle globale `* { margin: 0px auto; }` qui cassait la nouvelle mise en page en grille — son chargement est désormais désactivé pour la page de connexion.

### Phase 1 — Pages autonomes : login, 404, 500

- `backdoor/core/tpls/tpl/system/login.tpl` : reconstruit avec le markup Adminator (`.auth-shell`/`.auth-card`), toute la logique Smarty existante conservée (codes d'erreur E1-E4, détection navigateur IE, reCAPTCHA conditionnel, noms de champs de formulaire inchangés côté backend).
- `backdoor/core/tpls/tpl/404.tpl` : reconstruit sur le même principe (`.error-shell`/`.error-card`).
- **Nouvelle page 500** (`backdoor/500.html`) : page statique autonome, indépendante de Smarty (reste affichable même si Smarty est en cause dans l'erreur fatale).
- `backdoor/inc/sbuiadmin-header.php` (`__fatalHandler()`) : sur erreur fatale, affiche désormais la nouvelle page 500 avec le message d'erreur réel intégré (encart rouge, échappé HTML) au lieu du `var_dump()` brut ; le `var_dump()` complet reste affiché uniquement en mode debug (`_AM_SITE_DEBUG`) ; le détail complet (message/fichier/ligne) est dans tous les cas tracé via `error_log()`.
- Testé et validé en conditions réelles : login (clair/sombre), 404, et 500 (déclenchée volontairement puis retirée).
- Commit : `c6e0a4b`

### Phase 2 — Chrome partagé (header/footer/sidebar/topbar)

Cette phase impacte toutes les pages connectées d'un coup (sidebar/topbar partagées par l'ensemble du backdoor).

- `backdoor/core/tpls/tpl/navigation.tpl` : reconstruit intégralement — sidebar Adminator (`.d-sidebar`/`.nav-section`/`.nav-link`/`.nav-item-group`) et topbar (`.d-topbar`) avec dropdowns notifications/mises à jour, infos, menu compte (déconnexion), bouton mode sombre.
- `backdoor/inc/sbuiadmin-functions.php` (`sbGetMenuModule()`) : seule la sortie HTML est migrée vers le markup Adminator (`is-active`/`is-open`) — logique d'autorisation et de calcul d'état actif strictement inchangée.
- `backdoor/core/tpls/tpl/main_menu.tpl` : entrées Dashboard/Sandbox portées sur le nouveau markup. **Bug latent corrigé au passage** : le surlignage actif de ces deux entrées ne fonctionnait jamais (comparaison sur une variable Smarty `$active` jamais assignée nulle part) — remplacé par un test sur `$smarty.get.p`, cohérent avec `sbGetMenuModule()`.
- `backdoor/core/tpls/tpl/header_tpl.tpl` : réduit aux messages flash + debug (le titre/breadcrumb est déplacé dans la topbar).
- `backdoor/core/tpls/tpl/sb_header.tpl` / `sb_footer.tpl` : `#page-wrapper` devient `<main class="content">`.
- `backdoor/core/tpls/tpl/header.tpl` / `scripts.tpl` : Font Awesome repassé en auto-hébergé (fin du CDN), suppression de MetisMenu et `sb-admin-2.js` (vestiges confirmés, plus aucune référence après la réécriture du chrome), `bootstrap.min.js` conservé (encore requis par plusieurs pages de contenu pas encore migrées : dropdowns/onglets/modals Bootstrap), bundle JS Adminator chargé globalement.
- **Nouveau** `backdoor/assets/adminator/bridge.css` : adapte les classes Bootstrap 3 encore utilisées par les pages de contenu pas migrées (Phases 4-6) aux tokens visuels d'Adminator, y compris le mode sombre.
- **Correctif suite retour utilisateur** : le panneau sticky `.sbmenufixed` (bouton d'action fixe dans les longs formulaires) passait sous la nouvelle topbar — repositionné à `top: 70px` avec un `z-index` sous celui de la topbar, dans `bridge.css`.
- Testé et validé en conditions réelles.
- Commit : `d8dc5e6`

### Hors-plan — Restyle des tableaux DataTables (demande directe, en avance sur les Phases 4-6)

Le client a demandé de restyler tous les tableaux utilisant DataTables selon `datatable.html` d'Adminator, avant l'exécution dans l'ordre des Phases 4-6. Fait en une passe dédiée.

- **Nouveau** `backdoor/assets/adminator/datatable.js` : composant vanilla JS générique (tri au clic sur les colonnes, recherche texte côté client, pagination) qui remplace jQuery DataTables — le thème Adminator ne fournit que le markup statique de démo, aucune logique. Opère directement sur les lignes déjà rendues par PHP/Smarty, aucun changement à la récupération des données.
- **17 tableaux migrés** vers le markup `.data-table`/`.data-row`/`.data-cell-actions`/`.btn--icon` sur 14 fichiers : `system/{users,logaccess,menu,sandbox,blocs}.tpl`, `datas/modules/tpls/{contact,pages,tabbs,news,slider,table}.tpl` (câblés au nouveau composant JS), plus `system/{settings,dashboard}.tpl` (2 tables statiques de récapitulatif de configuration — restylées visuellement seulement, elles n'étaient jamais réellement pilotées par DataTables à l'origine).
- Suppression de `jquery.dataTables.min.js` et `dataTables.bootstrap.min.js` de `scripts.tpl` (plus aucune page ne les utilise).
- Tri chronologique correct sur les colonnes de date via `data-sort-value` (valeur brute avant `date_format`), au lieu d'un tri alphabétique incorrect sur le texte affiché.
- **Bugs latents corrigés au passage** (découverts en travaillant, hors scope initial) : mismatch d'id `dataTables-menu`/`dataTables-menus` dans `menu.tpl` (le tri ne s'appliquait jamais) ; id HTML dupliqué `dataTables-tablestructure` utilisé par deux tableaux différents dans `table.tpl` (renommé en `dataTables-tabledata` pour le second) ; balise `<h5>` invalide directement enfant d'un `<table>` dans `table.tpl` (déplacée au-dessus).
- Testé et validé en conditions réelles (tri, recherche, pagination, actions).
- Commit : `2ac77a2`

### Phase 3 — Constructeur de formulaires partagé

- `backdoor/inc/class/sbuiadmin-form.php` : migration du constructeur de formulaires partagé (utilisé par ~10 pages) vers le markup Adminator — `.field`/`.field-label`/`.input-icon`/`.ico` pour les champs texte (icônes Font Awesome conservées, ~25 icônes utilisées dans l'app dont des logos de marques), `.select`/`.textarea` pour les listes déroulantes et zones de texte, `.check` pour les cases à cocher et boutons radio, boutons submit/reset restylés en `btn btn--primary`/`btn btn--ghost`.
- **Découverte** : le risque d'asymétrie dans l'imbrication des div évoqué dans le plan initial n'existait pas réellement — code mort (`open_div`/`close_div`) jamais consommé par le rendu final. Supprimé. Vérifié par un test de rendu autonome que le HTML généré reste bien équilibré.
- **Bug latent corrigé au passage** : dans `addTextarea()`, les attributs `required`/`bname` étaient par erreur concaténés à l'intérieur de la valeur de l'attribut `class`, produisant du HTML invalide sur les champs textarea obligatoires.
- CKEditor et Tagify laissés en logique inchangée (confirmés indépendants de Bootstrap) — surcouche CSS ajoutée dans `assets/adminator/bridge.css` pour eux ainsi que les widgets jQuery UI (datepicker, colorpicker), afin qu'ils restent lisibles en mode sombre.
- **Hors scope, signalé pour plus tard** : le widget "Page Builder" (`addPageBuilder()`, utilisé par `sandbox.php` et le module Pages) a son propre chrome Bootstrap complet, plus lourd que CKEditor — laissé tel quel pour l'instant.
- Commit : `3c7b212`

### Hors-plan — Bandeau bienvenue + alertes Adminator sur tout l'admin (demandes directes)

- `backdoor/core/tpls/tpl/system/index.tpl` (le vrai dashboard d'accueil, `p=index` — distinct de `system/dashboard.tpl` qui est une page "Database Dashboard") : ajout d'un bandeau `.hero` avec la date du jour en français (calculée en PHP dans `index.php`, sans dépendance à la locale système) et un message de bienvenue personnalisé avec le nom de l'utilisateur connecté.
- Les 3 alertes de sécurité du dashboard (répertoire INSTALL, fichier INSTALL.PHP, utilisateur ADMIN par défaut) migrées vers `.alert.danger` d'Adminator.
- Étendu à l'ensemble de l'administration sur demande : audit exhaustif de toute alerte à l'ancien format Bootstrap dans les templates et le code PHP — une seule oubliée trouvée (`system/users.tpl`, encart informatif "Anti-Flood"), restylée en `.alert.warning`. Confirmé qu'aucune autre alerte ancien format ne subsiste nulle part.
- Testé et validé en conditions réelles.
- Commit : `3c7b212`

### Phase 4 — Dashboard d'accueil

- `backdoor/core/tpls/tpl/system/index.tpl` : les 4 cartes statistiques admin (utilisateurs, version PHP, DB host, limite d'upload) et les 4 cartes de statuts configurables migrées de `.panel`/`.huge` vers `.kpi-grid`/`.kpi-card` (icônes SVG dédiées, carte entière cliquable au lieu d'un simple lien en pied de carte, icône Font Awesome dynamique conservée pour les statuts configurables).
- Les listes "éléments récents" associées migrées de `.list-group` vers `.card`/`.card-head`/`.card-title`/`.card-action` — fusion des deux `.row` Bootstrap (contrainte 3+1 colonnes) en une seule `.grid` (CSS Grid gère nativement le retour à la ligne).
- **Gap comblé dans le thème** : Adminator ne fournit que `.col-6`/`.col-12` pour sa grille 12 colonnes — `.col-3`/`.col-4` ajoutés dans `bridge.css`, réutilisables pour les phases suivantes.
- Testé et validé en conditions réelles.
- Commit : `12d9cf0`

### Phase 5 (partiel) — Modules de contenu : confirm.js + barres d'outils

- **Nouveau** `backdoor/assets/adminator/confirm.js` : dialogue de confirmation vanilla JS (auto-injecte son propre CSS, event delegation sur `document` via l'attribut `data-confirm="message"` — fonctionne automatiquement sur tout élément présent ou futur) qui remplace jQuery jConfirm. Ancienne librairie retirée de `scripts.tpl` ; tous les boutons de suppression (pages système, modules, et `medias.tpl` bien que non encore migré visuellement — corrigé quand même pour ne pas perdre silencieusement la confirmation) migrés vers `data-confirm`.
- Les 6 barres d'outils de modules (`contact_bar`, `pages_bar`, `tabbs_bar`, `news_bar`, `slider_bar`, `table_bar`) : `.well.well-sm` + dropdowns Bootstrap migrés vers `.data-toolbar` + dropdowns Adminator (`.dd-wrap`, pilotés par le JS vanilla déjà en place, plus par Bootstrap JS).
- `news_bar.tpl` : petit tableau de statistiques converti en carte avec `.stat-cell`.
- `shared-panel-actions.tpl` (panneau sticky "Actions") et `shared-slider-4col.tpl` : migrés vers `.card`.
- **Correctif suite retour utilisateur** : la flèche de sous-menu des barres d'outils s'affichait sous le texte au lieu d'à côté — cause identifiée : la classe `.chev` du thème n'est stylée que dans des contextes spécifiques (menu latéral, accordéon), utilisée seule dans ces boutons elle ne produisait aucun effet. Remplacée par un positionnement flex explicite sur les 6 boutons concernés.
- Testé et validé en conditions réelles (suppression, barres d'outils, flèches).
- Commit : `7cbabfb`

### Phase 5 (complet) — Enveloppe de page des 6 modules

- `contact.tpl`, `pages.tpl`, `tabbs.tpl`, `slider.tpl`, `table.tpl`, `news.tpl` : grille Bootstrap `.row`/`.col-lg-*` migrée vers `.grid`/`.col-N` d'Adminator, panneaux `.panel panel-default` vers `.card`/`.card-head`/`.card-title`. C'était la dernière pièce visuelle de ces pages (formulaires, tableaux, barres d'outils déjà migrés lors des passes précédentes) — panneaux d'aide, panneaux d'information et panneaux conditionnels compris. Les dialogues Bootstrap encore utilisés (aide "Tableau pleine page", shortcodes news) restent fonctionnels tels quels.
- `bridge.css` : ajout de `.col-8` (pattern formulaire 8/4 colonnes très fréquent dans ces modules).
- **Bugs latents corrigés au passage** (détectés via le contrôle systématique d'équilibre des balises appliqué après chaque fichier) : une div jamais refermée dans `slider.tpl` (bug pré-existant, confirmé sur le commit d'avant-session) ; deux fermetures de div orphelines que j'ai moi-même introduites par erreur lors d'une première passe sur `table.tpl`, repérées et corrigées avant de committer.
- Testé et validé en conditions réelles (liste, formulaire d'ajout/édition, aide, sur les 6 modules).
- Commit : `aa4da8d`
- **Phase 5 entièrement close.**

## 2026-07-27 — Point 3 : refonte du dashboard en widgets configurables

Remplace l'ancien mécanisme de dashboard (fichier plat `inc/admin/dashboard.txt`, 4 emplacements fixes codés en dur dans `index.tpl`) par un vrai système de widgets configurables (`sb_dashboard_widgets`), avec CRUD complet (`dashboard.php`) et 5 types de widgets.

- **Nouvelle table** `sb_dashboard_widgets` : `type` (`table`/`system`/`weather`/`html`/`text`), `position`, `table_name`/`value_column`/`date_column` (type `table`), `widget_key` (type `system`), `location` (type `weather`, résolue une seule fois au géocodage), `content` (types `html`/`text`), `title`/`link`/`icon`/`color`/`show_chart`/`active`. Réordonnancement par flèches ↑/↓ dans la liste.
- **Type `table`** : widget branché sur n'importe quelle table SQL du cœur SBUIADMIN (whitelistée via un nouveau `sbGetDbSchema()`) — compteur, liste des 10 plus récents, tendance 7 jours vs 7 précédents, graphique optionnel (14 jours) si une colonne date est choisie. Rendu SVG dépendance-zéro (`assets/adminator/dashboard-chart.js`), pas de Chart.js (le bundle webpack du thème n'est pas extensible pour des données dynamiques).
- **Type `system`** : 7 métriques serveur au choix (nombre d'utilisateurs, version PHP, DB host, limite d'upload, espace disque libre, espace utilisé par les médias, sessions actives) — remplace les 4 anciennes tuiles en dur, qui n'existent plus nulle part dans le code.
- **Type `weather`** : météo d'une ville au choix (température + icône selon le code météo WMO), via l'API gratuite Open-Meteo (géocodage à l'enregistrement du widget, prévisions à l'affichage du dashboard, timeout court avec repli "Météo indisponible" en cas d'échec réseau). Réutilise le pattern cURL déjà en place dans `sbuiadmin-account.php` (géo-IP).
- **Types `html`/`text`** : bloc de contenu libre, soit code HTML brut (textarea simple), soit texte enrichi (CKEditor, même moteur que les champs "Header/Footer CODE" de `cmsconfig.php`) — rendus en carte de contenu à côté des cartes "Récent".
- **Nouveau** `inc/class/sbuiadmin-form.php` (`addIconFA()`) : sélecteur d'icônes Font Awesome (549 icônes du bundle embarqué, recherche incluse), sur le modèle des méthodes `addColor()`/`addTagify()` existantes — nouveau composant `assets/adminator/icon-picker.js`.
- Formulaire : sélecteur de type en tête, groupes de champs conditionnels affichés/masqués en JS selon le type (`dashboard-widget-form.js`), lien pré-rempli automatiquement selon la table SQL choisie, correspondance table SQL → titre français dans les menus déroulants (plutôt que le nom technique de la table).
- `index.tpl` : grille de tuiles KPI et cartes "Récent" entièrement pilotées par `$sb_dashboard_widgets` (`index.php`, dispatch par type) — plus aucune tuile ni logique codée en dur.
- **Bugs corrigés en cours de route** : icône "Actif" du tableau des widgets disproportionnée (mauvais contexte CSS) ; formulaire d'ajout/modification totalement vide (`{if isset($all)}` toujours vrai car `index.php` assigne `'all' => false` par défaut sur toute page — remplacé par `{if $all}`, cohérent avec `users.tpl`) ; erreur MySQL stricte `Incorrect integer value` sur `show_chart` quand la colonne est désactivée côté JS (radio jamais soumis) ; erreur `mysqli_real_escape_string(): Argument #1 must be of type mysqli, int given` sur `sql::escape_string()` en connexion paresseuse.
- Testé et validé en conditions réelles (mécanisme de base, types système/météo/HTML/texte, tuiles en dur retirées).
- Commit : `6c6329b`

### Suite — 4 types de widgets supplémentaires + UX du formulaire

- **Type `rss`** : URL de flux (RSS 2.0 ou Atom, parsé via SimpleXML) + nombre d'articles, liste des N plus récents en carte de contenu, aucune mise en cache (lu à chaque affichage, comme la météo).
- **Type `iframe`** : URL http(s) à intégrer, rendu en `<iframe sandbox="allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox allow-forms">` (surface réduite au strict nécessaire).
- **Type `logs`** : dernières lignes d'un fichier de log - **nouveau dossier** `backdoor/logs/` (protégé par `.htaccess deny from all`, aucun accès web direct), nom de fichier systématiquement réduit à `basename()` avant toute lecture (aucune traversée de chemin possible quelle que soit la saisie, vérifié par test).
- **Type `logaccess`** ("Dernières connexions") : les N dernières connexions réussies (`sb_logaccess`, `logaccess_type = 'login'` uniquement - les tentatives échouées ne sont pas mélangées), avec l'avatar de chaque utilisateur (jointure sur `sb_users`, repli Gravatar générique si l'utilisateur a été supprimé depuis).
- `rss`/`iframe`/`logs`/`logaccess` réutilisent les colonnes `location`/`value_column` existantes (URL/fichier + nombre d'éléments selon le type) - aucune migration SQL nécessaire pour ce lot.
- **UX** : le sélecteur "Type de widget" (9 choix) passe d'un `<select>` à une rangée de boutons cliquables avec icône (composant `.tabs.pills` du thème Adminator, jusque-là inutilisé nulle part), piloté par un input hidden en JS. Bouton "Retour aux widgets" ajouté dans le bandeau `.hero` des pages d'ajout/modification.
- **Bugs corrigés en cours de route** : `addIconFA()` (classe `form`) n'émettait pas le `</div>` fermant `.field` avant le texte d'aide, le laissant imbriqué au lieu d'être un frère (espacement cassé) ; les groupes de champs conditionnels par type (`data-widget-type-fields`) faisaient perdre le `display:flex;gap:14px` du `<form>` aux champs qu'ils contiennent, l'aide `margin-top:-10px` (calibrée pour ce gap de 14px) chevauchait alors le champ juste au-dessus faute de gap à compenser - même `display:flex;gap:14px` appliqué à ces groupes.
- Testé et validé en conditions réelles (4 nouveaux types, avatars, boutons de type, retour aux widgets).
- Commit : `17f5db4`

## 2026-07-27 — Point 15 (phase 1/2) : Page Builder réparé et modernisé (sandbox uniquement)

`addPageBuilder()` (`inc/class/sbuiadmin-form.php`) et son plugin tiers (`inc/plugins/pagebuilder/`) étaient laissés tels quels depuis la Phase 3 de la refonte Adminator — jamais finis ni modernisés. Audit préalable : seul `sandbox.php` l'utilise réellement (l'appel dans `datas/modules/pages.php` est commenté, Pages a déjà CKEditor qui fonctionne). Cadré en 2 phases avec le client : phase 1 (cette entrée) = réparer le fonctionnement + moderniser le look sur `sandbox.php` uniquement ; phase 2 (plus tard) = intégration dans les vrais modules avec réglage par module.

- **Sauvegarde globale entièrement réparée** : `addPageBuilder()` n'émettait jusqu'ici aucun champ réellement soumis avec le formulaire (seule la zone d'édition visuelle `.htmlpage` existait) - ajout d'un `<textarea>` caché, synchronisé automatiquement à la soumission du formulaire (`pagebuilder.js`). Plus de bouton "Save"/"Edit" propre au widget (demande explicite du client) : la sauvegarde suit exactement le même comportement que n'importe quel autre champ, via le bouton Ajouter/Modifier standard.
- **Bug racine du blocage de soumission**, le plus long à isoler (aucune erreur console, aucune requête réseau émise) : la modale de réglages par bloc contenait 2 balises `<form>` imbriquées (onglets Youtube/Carte) qui empêchaient silencieusement toute soumission du formulaire principal dans Firefox - remplacées par de simples `<div>`.
- **Contenu rechargé en édition** : d'abord affiché en HTML échappé ("soupe de code") à cause d'un double encodage HTML (`sbsanitize->displayText()` à la sauvegarde + `html_entity_decode()` au rechargement, décalés d'une passe) - puis, une fois l'encodage corrigé, contenu rechargé propre mais plus du tout modifiable : le nettoyage du HTML avant stockage (repris de l'ancien mécanisme d'export, jamais pensé pour un usage "recharger et continuer à éditer") retirait les éléments d'interaction (poignées drag/remove/clone/réglages). Solution : ne plus nettoyer avant stockage - le contenu stocké garde tout son chrome d'édition, qui reste pleinement interactif au rechargement sans réhydratation nécessaire (vérifié).
- **Bugs latents corrigés au passage** : `addPageBuilder()` (comme `addCountry()` avant elle, voir Point 2) n'enregistrait jamais sa place dans `formElementArr` - le champ suivant écrasait silencieusement tout son HTML ; aucun échappement SQL sur l'ensemble du formulaire `sandbox.php` (`escape_string()` absent partout) ; `sb_sandbox` (table de démo) entièrement redéfinie - ses colonnes ne correspondaient plus du tout aux champs réellement affichés par le formulaire (vestiges d'un ancien template "élevage de chevaux") ; `$formAction` ne portait jamais l'`id` en édition ; champ "Date de naissance" jamais réaffiché (mauvaise variable) ; **Tagify** (librairie tierce vendée) mis à jour vers v4.38.0 suite à un bug connu non protégé (`document.getSelection()` retournant `null`, [issue #1338](https://github.com/yairEO/tagify/issues/1338)) qui plantait sans être intercepté et bloquait toute la file d'initialisation JS de la page.
- **Look modernisé** : nouvelle feuille `assets/adminator/pagebuilder-bridge.css`, palette/rayons/ombres Adminator (clair + sombre) sur navbar/boutons/panneau latéral/canevas/modale, sans toucher au moteur Bootstrap 3/jQuery UI (drag & drop, modales) qui reste inchangé.
- Testé et validé en conditions réelles par le client (construction, sauvegarde, rechargement, ré-édition, drag & drop, tous types de blocs).
- Commit : `1dbcad7`

### Suite — Sélecteur d'image branché sur le vrai média du CMS

- Le bouton "Browse" du bloc "Image" ouvrait une fausse galerie AJAX pointant vers un fichier inexistant (`media-popup.php`, jamais relié à ce CMS) - branché sur le vrai sélecteur de médias partagé (`sbOpenPopup()`/`sbTransfert()`, `assets/dist/js/sb-custom.js`, le même mécanisme que les champs Photo/Pdf ailleurs dans l'admin). Bouton renommé "Choisir une image ...".
- L'URL injectée dans le champ est désormais l'URL absolue complète du site (`_AM_MEDIAS_URL` + nom de fichier, exposée en JS via `window.sbMediasUrl`) plutôt qu'un chemin relatif à la popup de sélection (inutilisable une fois la page affichée hors de l'admin) ou le simple nom de fichier que range `sbTransfert()` par défaut (comportement partagé avec les autres champs médias, volontairement pas modifié).
- **Bug corrigé au passage** : la première tentative de brancher `sbOpenPopup()` plantait en JS (`invalid escape sequence`) - le bloc concerné de `addPageBuilderTags()` est un HEREDOC PHP, où `\'` n'a aucune signification spéciale (contrairement à une chaîne à guillemets simples) et laissait une vraie barre oblique inverse dans le HTML généré.
- **Régression en cours, pas encore diagnostiquée** : le double encodage HTML ("soupe de code" au rechargement, voir plus haut) est réapparu après une sauvegarde en modification, repéré en toute fin de session. Piste à vérifier en priorité à la reprise : lien éventuel avec le nouveau contenu d'URL d'image absolue (`https://...`), jamais testé dans le cycle encodage/décodage jusqu'ici. Détails dans la mémoire de session.
- Commit : `b83eeb2`

## 2026-07-28 — Point 15 (suite) : régression corrigée, bouton "Code généré", classes `.sb*` pour le front

### Régression "soupe de code" diagnostiquée et corrigée

- **Cause réelle** : `sandbox.php` n'a aucune redirection HTTP après un Ajouter/Modifier (réussi ou en échec) - le formulaire se réaffiche dans la même requête. Le bloc qui décode le contenu (`html_entity_decode(utf8_encode(...))`) n'est exécuté que côté GET (`!$_POST['form_submit']`), donc jamais sur ce réaffichage : `addPageBuilder()` recevait encore la version encodée en entités destinée au stockage. Rien à voir avec l'URL d'image (fausse piste de timing) - le bug touchait aussi bien Ajouter en échec que Modifier, avec ou sans image.
- **Correctif** : re-décodage de `$page_builder_content` juste après l'échappement SQL (qui a déjà capturé la version entités pour la requête), avant que le formulaire soit reconstruit.
- Testé et validé en conditions réelles (sauvegarde puis observation sans rechargement, puis rechargement).

### Bug annexe : blocs glissés-déposés visuellement "opacifiés"

- jQuery UI (`connectToSortable` + `helper: 'clone'`) laissait un style inline résiduel (`opacity`, `position`, `z-index`, largeur forcée à 400px pendant le drag) sur le bloc une fois déposé, jamais nettoyé - visible en permanence, pas seulement pendant le drag.
- **Correctif** : nettoyage explicite (`.css({...})`) dans les gestionnaires `stop` des 2 `draggable()` concernés (`pagebuilder.js`). Les blocs déjà enregistrés avant ce correctif restent opacifiés en base tant qu'ils ne sont pas re-glissés/resauvegardés.

### Nouveau bouton "Code généré" + classes `.sb*` pour le front

- Bouton dans la navbar du Page Builder (`sbuiadmin-form.php`) ouvrant une modale en lecture seule avec le HTML qui serait réellement inséré sur la page front.
- `generatePageBuilderCode()` (`pagebuilder.js`) : travaille sur une copie hors-DOM de `.htmlpage` (jamais le canevas d'édition réel) - `cleanRow()` retire tout le chrome d'édition, puis les classes Bootstrap qui survivent dans le contenu exporté (grille `.row`/`.col-md-X`/`.clearfix`, boutons `.btn`/`.btn-*`, `.img-responsive`) sont renommées en équivalents préfixés `.sb*`, pour ne jamais entrer en conflit avec le Bootstrap (même version, autre version, ou absence de Bootstrap) du thème front qui affichera ce contenu. Résultat indenté (`style_html()`, déjà présent mais inutilisé).
- **Nouvelle feuille** `assets/adminator/pagebuilder-front.css` : équivalents `.sb*` de la grille/boutons/média Bootstrap 3 (valeurs reprises du Bootstrap vendorisé, couleurs déjà celles du thème Adminator) - à charger uniquement sur les pages front qui afficheront du contenu Page Builder (Phase 2, pas encore commencée).
- Cache-busting (`?v=filemtime()`) ajouté sur `pagebuilder-bridge.css` et `pagebuilder.js`, modifiés régulièrement sur ce chantier - le `.htaccess` racine impose `Cache-Control: proxy-revalidate, max-age=3600`, qu'un hard-refresh navigateur ne suffit pas toujours à contourner selon l'hébergement.
- **Bug de layout trouvé et corrigé au passage** (alignement du bouton dans la navbar) : le clearfix Bootstrap (`.navbar-header:before/:after { content:" " }`) devient 2 items flex à part entière une fois le conteneur passé en `display:flex` - `justify-content:space-between` répartissait alors l'espace entre 4 éléments au lieu de 2, poussant titre et bouton vers le centre. Neutralisé (`content:none`). Modale "Code généré" centrée verticalement (même technique que la modale `#preferences`) et titre recentré (bouton close sorti du flux en `position:absolute`).
- Testé et validé en conditions réelles par le client.
- Commit : `d19843f`

## 2026-07-28 — Point 15 (Phase 2) : intégration Page Builder dans les modules de contenu

### Nouveau réglage "Modules utilisant le Page Builder"

- **Configuration générale** : champ Tagify à choix restreints (dropdown de suggestions, saisie libre bloquée via `enforceWhitelist`) listant les champs éligibles - un seul champ par module, celui qui porte le contenu principal : Pages (Contenu), Actualités (Article - pas Intro), Tabbs (Contenu), FAQ (Réponse), Blocs (Contenu). Sauvegardé position 36 de `settings.txt`.
- **Nouvelle méthode** `addTagifyWhitelist()` (`sbuiadmin-form.php`), sans toucher à `addTagify()` existante (doit rester en saisie libre pour les champs qui en ont besoin, ex: mots-clés SEO).
- **Incompatible avec le multilangue** (décision actée) : le Page Builder ne remplace que le champ FR/principal, jamais l'EN - éviter le chantier de support multi-instance du Page Builder sur une même page (la modale de réglages d'un bloc a une quinzaine d'ID fixes non scopés par instance, chantier bien plus gros que prévu). Le réglage est désactivé et grisé (texte d'aide en rouge) si le multilangue est actif, et sa sélection existante est préservée à la sauvegarde plutôt qu'effacée (un champ HTML `disabled` n'est jamais soumis par le navigateur - sans cette précaution, activer le multilangue aurait silencieusement vidé le réglage à la prochaine sauvegarde de la configuration).
- Démo de `addTagifyWhitelist()` ajoutée dans `sandbox.php` (non persistée).

### 5 modules câblés (Pages, Tabbs, Actualités, FAQ, Blocs)

- Bascule conditionnelle CKEditor ↔ Page Builder sur le champ éligible de chaque module, pilotée par le nouveau réglage (`sbModuleUsesPageBuilder()`, `sbuiadmin-functions.php`).
- **Bug trouvé et corrigé en cours de route** : ces 5 modules n'ont aucun `escape_string()` sur ce champ (lacune préexistante). Le re-décodage du contenu (nécessaire pour réafficher du HTML brut au Page Builder après une sauvegarde sans redirection, voir Point 15 précédent) doit donc se faire *après* l'exécution de la requête SQL, jamais avant - décoder avant y aurait injecté du HTML brut, donc des apostrophes potentielles, directement dans la requête.
- `addPageBuilder()` ne charge plus ses assets (Bootstrap/jQuery UI touch-punch/pagebuilder.js) qu'une seule fois par page (protection statique, défensif - devenu superflu vu la décision multilangue ci-dessus, mais sans risque à garder).

### Bugs de drag & drop trouvés en testant le module Pages

- **jQuery UI ne liait jamais le glisser-déposer sur cette page précise** : `connectToSortable` (jQuery UI 1.11.4) ne déclenchait aucun événement `sortover`/`sortreceive` en glissant depuis la barre du haut vers le canevas, sans aucune erreur console, alors que le même mécanisme fonctionnait parfaitement sur `sandbox.php`. Cause exacte non élucidée malgré investigation poussée (écarté : conflit de version jQuery UI, débordement de conteneur, canevas vide vs rempli - détails dans la mémoire de session). **Corrigé** par un filet de sécurité manuel dans le `stop` du glisser-déposer : si les coordonnées réelles de la souris au relâchement sont dans la zone de dépôt mais que jQuery UI n'a rien absorbé, on insère le contenu nous-mêmes.
- **Ce filet de sécurité échouait lui-même silencieusement** : jQuery UI supprime son propre élément de glisser-déposer juste après avoir déclenché l'événement "stop" quand aucune zone de tri ne l'a formellement accepté - ce qui effaçait ce qu'on venait d'insérer manuellement, sans erreur visible. Corrigé en insérant un clone indépendant de l'élément plutôt que l'élément original.
- Testé et validé en conditions réelles sur le module Pages (round-trip complet, dépôt d'une ligne et dépôt d'un widget dans une colonne).
- **Reste à tester** : Tabbs, Actualités, FAQ, Blocs (même mécanisme, pas encore vérifiés en navigateur).
- Commit : `8d58f1e`

## 2026-07-28 (suite) — Point 15 (suite) : rendu front du Page Builder + bloc Carte en Leaflet

### Nettoyage HTML côté front

- **Nouveau** (`inc/functions.php`, front) : portage PHP (`DOMDocument`/`DOMXPath`) de `cleanRow()`/`sbRenameClasses()` (JS, bouton "Code généré") - `sbCleanPageBuilderContent()` retire tout le chrome d'édition (poignées, boutons remove/clone/réglages, marqueurs de largeur) et renomme les classes Bootstrap survivantes en `.sb*`, exactement comme l'aperçu "Code généré" côté admin.
- Branché sur les 5 modules concernés : Pages (`pages.php`), Tabbs (`tabbs/inc/functions.php`), Actualités (`news_display_article.tpl`), FAQ (`faq/inc/functions.php`), et les Blocs (5 gabarits d'affichage : Pages/Contact/Utilisateur/Actualités/Recherche) - sans ce nettoyage, le HTML brut d'édition (icônes, boutons, inputs de taille) s'affichait tel quel sur le site public.
- **Nouvelle feuille** `assets/pagebuilder-front.css` (racine front, déplacée de `backdoor/assets/adminator/`) et **nouveaux fichiers vendorisés** `assets/leaflet/` (Leaflet 1.9.4) - injectés dynamiquement via `insert_sbGetHeaders()` (déjà appelé par les 5 thèmes front) plutôt que par le champ "CSS" de Configuration générale, qui enveloppe systématiquement son contenu dans `<style>` et ne peut donc pas porter une balise `<link>`. URL absolues (`SB_URL`/`SB_PATH`, jamais `SB_ADMIN_URL`/`SB_ADMIN_DIR`) : une page publique ne doit jamais référencer un chemin qui renseigne sur l'emplacement/le nom du répertoire d'administration.
- **Bug corrigé au passage** : `box-sizing: border-box` manquant sur `.sbcol-md-*`, ce qui faisait passer deux colonnes de 6 à la ligne au lieu de les afficher côte à côte (le padding s'ajoutait à la largeur au lieu d'être inclus dedans).

### Bloc "Carte" remplacé par Leaflet (abandon de Google Maps)

- L'ancien bloc (iframe Google Maps) ne fonctionnait pas du tout côté admin (glisser-déposer impossible à relâcher) et n'avait aucun rendu défini côté front. Remplacé par **Leaflet + tuiles OpenStreetMap** (demande explicite du client, plus léger et plus libre) - fichiers vendorisés en local (`assets/leaflet/` côté front, `backdoor/inc/plugins/pagebuilder/js/leaflet/` côté admin), pas de CDN.
- Réglages inchangés (latitude/longitude/zoom), avec en plus un **aperçu interactif** dans la modale (marqueur déplaçable à la souris, carte qui suit les champs) et un nouveau champ **texte/HTML pour le marqueur** (popup), stocké en attribut `data-popup` sur le conteneur (et non en enfant caché, qui serait détruit par l'initialisation de Leaflet).
- **Bug de sauvegarde corrigé** : le champ générique "Class CSS" (`#class`, commun à tous les types de blocs) écrasait la classe `sb-pagebuilder-map` elle-même à la confirmation des réglages - la 1ère confirmation "réussissait" silencieusement, mais la réglage suivant sur ce même bloc ne le retrouvait plus (`part.find()` vide) et plantait au clic sur "Confirmer". Corrigé en masquant ce champ (sans objet pour une Carte) au lieu de le laisser écraser la classe.
- Testé et validé des deux côtés (admin + front), y compris double confirmation successive avec/sans description.

### Autres correctifs trouvés en testant le module Actualités

- **Row/Cell** : `loadRowSettings()`/`loadColumnSettings()` lisaient la valeur CSS *calculée* (`.css('X')`, toujours non-vide) au lieu du style inline réel de l'élément - ouvrir les réglages de n'importe quel bloc (même juste pour configurer une image) figeait silencieusement un fond/padding/marge par défaut qui n'avait jamais été choisi. Corrigé en lisant `.style.X` (vide si non défini).
- **Champ "Css class"** (Row/Cell) : se remplissait avec les classes structurelles (`row clearfix`, `col-md-X column`) et, si l'utilisateur les retirait, cassait l'affichage dans le Page Builder lui-même. Corrigé en traitant ce champ comme des classes *en plus* uniquement - les classes structurelles sont désormais toujours réappliquées par le code, quel que soit le contenu du champ.
- **Bloc "Code"** : un script `ace.edit('code')` mort dans `news.tpl` (jamais réellement relié à un champ Actualités) entrait en collision avec le panneau de réglages du Page Builder (même id `#code`), empêchant la sauvegarde de ce bloc spécifiquement sur Actualités (fonctionnait sur `sandbox.php`, qui n'a pas ce script). Retiré.
- Commit : `a83c6e1`
