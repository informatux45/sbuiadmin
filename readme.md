<p align="center">
  <a href="https://github.com/informatux45/sbuiadmin/">
    <img src="backdoor/img/sbuiadmin-logo-big.png" alt="SBUIADMIN" width="140">
  </a>
</p>

<h1 align="center">SBUIADMIN</h1>

<p align="center">
  <strong>A lightweight, secure and modular PHP CMS</strong><br>
  Bootstrap front themes · Adminator administration · signed updates
</p>

<p align="center">
  <a href="https://github.com/informatux45/sbuiadmin/releases/latest"><img src="https://img.shields.io/github/v/release/informatux45/sbuiadmin?display_name=tag&logo=github&style=flat-square&label=stable" alt="Stable version"></a>
  <a href="https://github.com/informatux45/sbuiadmin/commits/master"><img src="https://img.shields.io/github/last-commit/informatux45/sbuiadmin?style=flat-square" alt="Last commit"></a>
  <br>
  <img src="https://img.shields.io/badge/PHP-8.4%20%7C%208.5-777BB4?logo=php&logoColor=white&style=flat-square" alt="PHP 8.4 | 8.5">
  <img src="https://img.shields.io/badge/MySQL-5.7%2B-4479A1?logo=mysql&logoColor=white&style=flat-square" alt="MySQL 5.7+">
  <img src="https://img.shields.io/badge/MariaDB-10.3%2B-003545?logo=mariadb&logoColor=white&style=flat-square" alt="MariaDB 10.3+">
  <img src="https://img.shields.io/badge/releases-signed%20Ed25519-2ea44f?logo=letsencrypt&logoColor=white&style=flat-square" alt="Signed releases (Ed25519)">
  <a href="http://www.gnu.org/licenses/gpl-3.0.html"><img src="https://img.shields.io/badge/license-GPLv3-blue?style=flat-square" alt="License GPLv3"></a>
</p>

<p align="center">
  <a href="https://github.com/informatux45/sbuiadmin/releases/latest"><strong>Download</strong></a> ·
  <a href="#-getting-started"><strong>Getting Started</strong></a> ·
  <a href="#-features"><strong>Features</strong></a> ·
  <a href="#-changelog"><strong>Changelog</strong></a>
</p>

> [!NOTE]
> Install SBUIADMIN with the single file `sbuiadmin_netinstall.php` from the [latest release](https://github.com/informatux45/sbuiadmin/releases/latest): it downloads the release, checks its signature and every file, then starts the installation wizard. Once installed, the core and each module are updated from the administration (**Configuration › Mise à jour**).

---

## ✨ Features

<details>
  <summary>🧩 <strong>Modular</strong> — install, update, uninstall each module on its own</summary>

> Articles, Pages, Slider, Contact forms, Tables, Tabs, FAQ, Downloads, Galleries, Search, and a Shop back office. Each module has its own version, can be installed from GitHub, updated, uninstalled (data kept or removed) and rolled back, independently of the core.
</details>

<details>
  <summary>🔐 <strong>Secure by design</strong> — 2FA, ALTCHA, encrypted secrets</summary>

> Two-factor authentication by email, ALTCHA anti-bot (self-hosted, no Google), temporary lockout after failed logins, per-module user rights, CSRF protection, settings stored in database with encrypted secrets, database credentials outside the web root, renamable administration directory.
</details>

<details>
  <summary>🔄 <strong>Signed updates</strong> — net install and one-click updates with rollback</summary>

> Every release is signed (Ed25519) and every file is checked (SHA-256) before anything is written. Core and modules update from the administration, with a database backup and a rollback; a database left behind its files is detected and upgraded on demand.
</details>

<details>
  <summary>🎨 <strong>Themes</strong> — 5 responsive front themes, Adminator administration</summary>

> HTML 5 / CSS 3, Bootstrap front themes, Adminator administration theme with dark mode, responsive design on both sides.
</details>

<details>
  <summary>🧱 <strong>Content</strong> — Page Builder, content blocks, shortcodes</summary>

> Page Builder (drag and drop blocks: text, image, gallery, accordion, tabs, quote, icon…), content blocks, shortcodes, additional HTML content (Smarty ready), rewrite URLs.
</details>

<details>
  <summary>🌐 <strong>Multilanguage</strong></summary>

> Content and interface in several languages.
</details>

<details>
  <summary>🛠️ <strong>For developers</strong> — Smarty, sandbox, PHP 9 ready</summary>

> Smarty templates, sandbox page, Kint debug, module registry (`module.php`), PHP audit log for PHP upgrades. Code kept free of PHP 8.x deprecations, ready for PHP 9.
</details>

<details>
  <summary>⚙️ <strong>Requirements</strong></summary>

> PHP 8.4+ (PHP 8.5 ready) · MySQL 5.7+ / MariaDB 10.3+ · Linux / Apache servers (recommended).
</details>

---

## 🚀 Getting Started

### Prerequisites

| | Required | Recommended |
|---|---|---|
| **PHP** | 8.4 or later | 8.5 (tested version) |
| **Extensions** | `mysqli`, `pdo_mysql` (installer), `session`, `json`, `mbstring`, `sodium`, `openssl`, `curl`, `gd`, `fileinfo`, `filter`, `hash`, `zip` | `exif`, `intl`, `iconv`, `dom`, `simplexml`, `mysqlnd`, `memcache`/`memcached` (login anti-flood) |
| **Database** | MySQL 5.7+ or MariaDB 10.3+ | |
| **Web server** | Apache with `mod_rewrite` and `mod_headers` | Apache 2.4 |
| **php.ini** | `file_uploads = On` | `upload_max_filesize` ≥ 8M, `memory_limit` ≥ 128M, `display_errors = Off` |

No Composer needed: `vendor/` is shipped in every release. The installation wizard checks all of the above before doing anything.

> [!TIP]
> The `.htaccess` files must be read: allow them in your virtual host.
>
> ```apache
> <VirtualHost *:80>
>     ServerName example.com
>     DocumentRoot /var/www/example
>     <Directory /var/www/example>
>         AllowOverride All
>         Require all granted
>     </Directory>
> </VirtualHost>
> ```
>
> On nginx the `.htaccess` files are ignored: the equivalent rules are in `help.txt`.

### Net install (recommended)

1. Upload `sbuiadmin_netinstall.php` **alone** to the (empty) folder of your future site, then open it in your browser.
2. Click *Install SBUIADMIN*: the latest release published on [GitHub Releases](https://github.com/informatux45/sbuiadmin/releases) is downloaded and **verified before anything is written**:
   - HTTPS with certificate verification, GitHub hosts only;
   - Ed25519 signature of the release manifest (the public key is embedded in `sbuiadmin_netinstall.php`, the private key is never published);
   - exact size and SHA-256 of the archive;
   - every archive entry is checked (no `..`, absolute path or symbolic link, nothing written outside the folder).

   If any check fails, nothing is installed.
3. The netinstall file deletes itself, then the installation wizard (`backdoor/`) takes over: database, administrator account, installation key.

Net install refuses to run where SBUIADMIN is already installed: delete it from your server.

### Manual install

1. Download `sbuiadmin-X.Y.zip` from a [release](https://github.com/informatux45/sbuiadmin/releases/latest) and check its SHA-256 against `sbuiadmin-release.json`.
2. Extract it into your site folder.
3. Make these paths writable by the web server: `upload/`, `backdoor/datas/cache/core/`, `backdoor/datas/cache/medias/`, `backdoor/datas/cache/tpls_c/`, `backdoor/install/installer/` and `backdoor/install.php`.
4. Open `backdoor/` in your browser and follow the wizard.

> [!IMPORTANT]
> Do not install from a `git clone`: only release archives are signed and verified.

Installing in a sub-folder: see `help.txt`.

### Usage

| URL | Purpose |
|---|---|
| `/` | Public site |
| `/?lang=en` · `/?lang=fr` | Switch language (kept in session) |
| `/backdoor/` | Administration (login, then two-factor authentication if enabled) |
| `/backdoor/index.php?p=dashboard` | Dashboard: system widgets and widgets shipped by modules |
| `/backdoor/index.php?p=<module>` | A module's administration (`pages`, `news`, `faq`, `users`, `menu`…) |
| `/backdoor/index.php?p=update` | *Configuration › Mise à jour*: updates of the core and of each module (signed releases only) |
| `/?maintenance=apercu` | Preview of the maintenance page (administrators only) |

The administration folder is named `backdoor` by default. You can rename it after installation (e.g. to hide the login page): rename the folder, then set the same name in `SBADMIN` in `sbconfig.php`; updates keep it. Third-party modules live in `datas/modules/<name>/`; see `help.txt` for their structure, rights and dashboard widgets.

---

## 📝 Changelog

**4.62**
- Store module renamed to "E-commerce" with a star icon (display name and sidebar menu only; identifier, routes and tables unchanged)

**4.61**
- Page Builder: a dropped row/block no longer sometimes sticks in the middle of the canvas (leftover inline position from jQuery UI connectToSortable); inline styles are cleaned after every drop

**4.60**
- Sentinelle 1.6.0: automatic bans now count every serious attempt (even repeats collapsed in the log), slow-scan detection (too many 404s per IP), and the attacker's country in the log (CF-IPCountry header, or optional ipstack key)

<details>
  <summary><strong>Older versions</strong></summary>

**4.59**
- Sentinelle 1.5.0: file integrity check against the signed file list of the installed version (core and official modules modified or missing, added PHP files, scripts in upload/), on demand and daily, critical event and alert on any change; advisor point

**4.58**
- Sentinelle 1.4.0: request burst limit per IP (no Memcache needed), search engine bots verified by reverse DNS, automatic bans after repeated serious events (longer for repeat offenders), protection mode only; member area login failures reported to Sentinelle

**4.57**
- Sentinelle 1.3.0: security advisor (PHP settings, HTTPS and security headers, exposed files such as .git or the installer, accounts, Sentinelle and update settings), each point with the fix to apply, score out of 100 and "Security score" dashboard tile

**4.56**
- Sentinelle 1.2.0: banned IPs and ranges (duration or permanent, 403 in both modes, ban from the log), CSV export, dashboard widgets (attacks tile, most active IPs card), e-mail alerts (at most one per 15 min) and daily summary
- Modules card: new "Update database" action when a module's files are newer than its registered version (files copied by hand), with encrypted backup and undo

**4.55**
- Sentinelle 1.1.0: attack filters (SQL injection, XSS, path traversal, file inclusion, null bytes, globals overwrite, vulnerability scanners, attack tools, dangerous uploads), each off / log only / by mode (403 in protection mode); trusted IPs, logged-in administrators, passwords and tokens are never inspected; excluded parameters

**4.54**
- New official security module Sentinelle 1.0.0 (installed with new sites, from the Modules card on existing ones): security log of refused logins, ALTCHA/2FA/CSRF failures, rights denials, blocked IPs and anti-flood bans, observation/protection mode, trusted IPs, rescue file; sbSecurityEvent() for third-party modules

**4.53**
- Visitor IP address: proxy headers (X-Forwarded-For, CF-Connecting-IP) are only trusted from a declared proxy (Users > Blocked IPs > Settings: none, Cloudflare or custom list); IP blocking, login lock and logs could be bypassed or misdirected by forged headers

**4.52**
- Readme: Getting Started section (prerequisites, net and manual install, usage); zip extension now required by the installer; renaming the administration folder documented (help.txt, sbconfig.php)

**4.51**
- Module widgets: tiles can show a comparison line and a trend badge (favorable direction chooses the color), cards can be 4/6/8/12 columns wide (set by the widget or when adding it), scripts declared by widgets are loaded once

**4.50**
- Dashboard widgets shipped by modules: datas/modules/<name>/widgets/<key>.php (tile or card, optional .tpl, settings: text, number, select, yes/no), new "Module widget" type on the Dashboard page, hidden without the module's view right

**4.49**
- FAQ 1.0.1: missing ID cell in the questions table (columns were shifted); help text spacing under fields placed in an openRow() line

**4.48**
- Form class: the 'time' input type is accepted again by addInput() (HH:MM, same look as text fields); example Time field in the sandbox page (new sb_sandbox.hour column)

**4.47**
- A module can ship its own admin controller and templates in its folder (datas/modules/<name>/admin/<name>.php and admin/tpls/), used instead of backdoor/datas/modules/ with the same rights and CSRF checks; core templates still take precedence

**4.46**
- Admin login page settings (Configuration > Admin Login): gradient colors, SBUIADMIN version shown or hidden, square logo picked from the media library; native color picker in the form class

**4.45**
- Sites updated from an old version: database port/socket and ALTCHA no longer depend on the site's own sbconfig.php; the Update page lists vendor/ folders that are not part of SBUIADMIN (vendor/.htaccess blocks web access to them)

**4.44**
- CSRF token required on every admin POST, added automatically to forms, fetch and jQuery requests; modules can declare sensitive GET actions ('csrf_get') and Smarty modifiers ('modifiers'); more PHP functions usable in templates (null-safe); new date formats US1, FRM, FRH2, MONTH

**4.43**
- Database catch-up for old sites: missing core tables and columns (and those of installed modules) added from the installer schema, no data changed; a database without version and without sb_modules/sb_users_rights is now upgraded instead of being marked current; option to hide the GitHub banner on the maintenance page

**4.42**
- Security audit (part 3): installer closed once installed, escaped URL values in the Volton 404 page, fixed template choice in News and User, logout links require the session token, HTTP 403 for access denied, single install dump, front language kept across pages, clean download file names; News 1.0.3, Downloads 1.0.1

**4.41**
- Security audit (part 2): admin-only pages refused before running, per-action rights read from the controller's own parameter, CSRF token on delete links, SQL escaping hardened (backslash, multi-select ids), non-admin accounts get modules only by default (Media to be granted); News module 1.0.2

**4.40**
- Shop module renamed "store" 2.0.0 (sb_store_* tables, no routes): an installed official "boutique" is taken over on install, a site's own shop is never touched; 'origin' => 'site' marker for site-specific modules (never installed or updated from GitHub)

**4.39**
- Security audit (part 1): public maintenance access code replaced by a random one, unused AJAX endpoints removed, CSRF token on sitemap generation, thumbnail cache capped, direct-access guards restored

**4.38**
- Contact module 1.1.0: received messages kept and listed per form (retention setting, 12 months by default), unread count shown in the side menu, several recipients, real error shown on send failure

**4.37**
- PHP 8 / PHP 9 readiness: fatal errors and deprecations fixed (sanitize, forms, CSV, media, thumbnails, search), wrong docblocks corrected; Shop and Tabs modules 1.0.1

**4.36**
- Update page: database left behind the files (FTP copy…) detected, upgraded on demand with a backup restored on failure; Modules table centred

**4.35**
- Update page: Reload button fixed, clearer module messages (module name, plurals), better spacing in the Modules table

**4.34**
- Modules: Cancel button to undo the last operation (update, uninstall, removal, install); backups limited to the module tables
- Update page: progress spinner and results where the action happens; module files are downloaded once

**4.33**
- PHP 8: no more warnings on the site and in the administration (templates, pages, news, logout, forms)
- Dashboard: the widget form title is shown again

**4.32**
- Update page: progress and errors now show where the action happens (update card, module row)
- Stray system files removed (.DS_Store, Thumbs.db, .orig); module files no longer reported as modified when they are not

**4.31**
- Modules card simplified: Install (downloads the module if needed), Update, Uninstall
- Uninstall can now remove the whole module (data, tables, files), listing what will be lost before confirming

**4.30**
- Fixed: updating from 4.28 or earlier stopped on the 4.29 database migration (it was rolled back automatically)
- Update page: version and status side by side, green when up to date; the update icon now counts core and module updates

**4.29**
- Modules (news, contact, faq, gallery, download, slider, tabbs, table, shop) are now separate from the core, each with its own version
- New "Modules" card in Configuration > Update: download, install, uninstall, delete files
- PHP 8: no more undefined index warnings in modules

**4.28**
- Text conversion no longer drops strings with characters outside Latin-1 (euro, curly quotes, emoji)
- Maintenance page: local fonts linked, social icons no longer doubled on desktop
- Form class: no more PHP 8 "Undefined variable" warnings

**4.27**
- Media folder and URL now set by the updated core, so sites keeping an older sbconfig.php need no manual change

**4.26**
- Media uploads hardened: safe file names (duplicates included), content must match the extension, size limit enforced server-side, CSRF token
- Media folder fixed to upload/ (no longer set at install or in Configuration), media URL derived from the site URL; no more double slashes

**4.25**
- Configuration > Server rebuilt: shared / VPS / dedicated detected (or forced), disk ring, live CPU load chart, "not detected" instead of zeros
- Container load read from /proc/loadavg (not the host); old status iframe and Chart.js removed

**4.24**
- Font Awesome 7 Free served locally (themes, admin, editor, icon picker), old icon names still accepted
- Blocked IPs and front anti-flood active again, 403 redirect fixed; local Chart.js 4.5.1

**4.23**
- Google Analytics 4 (G-) instead of Universal Analytics, Google verification meta fixed
- Google Fonts served locally by the themes, CMS config > Fonts removed
- Pinkrio: ColorBox no longer opens by itself (jQuery 4)

**4.22**
- Activebox, Editorial and Volton themes: local jQuery 4, Waypoints 4.0.1
- Shorter help.txt and changelog

**4.21**
- Saxo and Pinkrio themes: local jQuery 4

**4.20**
- Plugins: local jQuery 3.7.1, Lightbox 2.12, CookieConsent, back to top, AOS, highlight.js
- Fancybox, Appear, Prism removed

**4.19**
- Security: no direct access to backdoor/inc files, nginx rules in help.txt
- Howto windows: long lines wrap

**4.18**
- Form class: several fields per row (openRow / closeRow)

**4.17**
- Maintenance mode (HTTP 503, no redirect) replaces Coming soon

**4.16**
- Fixed installer detection on installed sites

**4.15**
- Front: theme jQuery no longer overwritten, sample JavaScript fixed

**4.14**
- Theme setting in database, optional 2FA, one sbdbconfig.php per site

**4.13**
- Update from the administration (signed releases, migrations, encrypted backups, rollback)

**4.12**
- Signed releases and secure net install
- ALTCHA replaces reCAPTCHA, login lockout
- PHP 8.4 minimum, PHP 8.5 ready, Smarty 4.5.8

**4.11**
- Settings in database, credentials in sbdbconfig.php, encrypted secrets

**4.10**
- New administration theme (Adminator, dark mode)
- Legacy JS libraries replaced with vanilla JS
- New 500 error page

**4.00**
- Update Smarty libraries
- Add Smarty plugins / modifiers
- PHP 8.2 Ready
- SQL Class reporting
- Clean up code

**3.01**
- Update many libraries (kint, smarty, ...)
- PHP 8.0 and 8.1 Ready
- New admin theme colors
- Admin menu grouping (Dropdown buttons)
- Clean up code

**2.31**
- SMTP Configuration (Account module)
- Add classes (Account, Cart, CSV)

**2.30**
- PHP 7.3 and 7.4 Ready

**2.20**
- PHP 7.1 and 7.2 Ready
- Change login encrypt/decrypt for PHP 7.1 and more
- Upgrade Installer
- Remove Recaptcha by default (login)

**2.12**
- Add session management

**2.11**
- Update ALL queries
- Clean up code

**2.10**
- Change all constants and variables for SBUIADMIN
- Add more General Settings (debugs front, rewrite url, Smarty caching, Smarty cache lifetime, Smarty force compile TPLs)

**2.04**
- Add Google INVISIBLE Recaptcha (Login Admin)

**2.03**
- Change version of CKEditor to 4.8.0.
- Add new plugins to CKEditor (ckawesome, justify, youtube, panelbutton, floatpanel, colorbutton, image2, codesnippet, html5video, html5audio)

**2.02**
- FORM Input text MEDIAS : Add possibility to display a subdirectory of MEDIAS Directory.
- FORM Input text MEDIAS : Add possibility to limit the number of medias to display.

**2.01**
- Second version.

**1.00**
- First version.

</details>

---

## 🤝 Contributing & support

- Found a bug or have an idea? Open an [issue](https://github.com/informatux45/sbuiadmin/issues).
- Pull requests are welcome: please keep the code free of PHP 8.x deprecations (PHP 9 ready) and describe how you tested it.

> [!IMPORTANT]
> Please do not report security vulnerabilities in public issues: contact the maintainer privately through [GitHub](https://github.com/informatux45) first.

---

<p align="center">
  Made by <a href="https://github.com/informatux45">informatux45</a> · <a href="http://www.gnu.org/licenses/gpl-3.0.html">GPLv3</a>
</p>
