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
