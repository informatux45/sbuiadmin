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

The administration folder is shipped as `backdoor`. A new installation proposes a random name (e.g. `vokadilumi47`) and renames the folder at the very end, so the login page is not at a known address; write the new address down. To rename it later: rename the folder, then set the same name in `SBADMIN` in `sbconfig.php`; updates keep it. Third-party modules live in `datas/modules/<name>/`; see `help.txt` for their structure, rights and dashboard widgets.

---

## 📝 Changelog

**4.68**
- Login log purged once a day, and right after saving the retention

**4.67**
- Admin AJAX calls without a session: 401, no log entry
- Messages script loaded only with the Messages right
- Login log purge (Configuration > Features), newest entries first

**4.66**
- Fixes: older MariaDB collation, theme migration, CLI database config, php.ini session options
- Anti-flood and IP bans fixed, lightbox CSS
- Theme option various_in_theme; Sentinelle 1.7.2

<details>
  <summary><strong>Older versions</strong></summary>

**4.65**
- Random admin folder name on new installs
- Sentinelle 1.7.1: retired official modules recognised

**4.64**
- Sentinelle 1.7.0: approved site files, watched prefixes
- Admin XSS fixed; News, Gallery, Slider, Tabbs, Table updated
- Admin breadcrumb in French

**4.63**
- 8 default dashboard widgets on new installs; Contact 1.1.1
- Confirmation before leaving an unsaved form
- Theme read from the database only

**4.62**
- Store module renamed "E-commerce"

**4.61**
- Page Builder: dropped blocks no longer stuck

**4.60**
- Sentinelle 1.6.0: stronger auto-ban, slow scans, country

**4.59**
- Sentinelle 1.5.0: file integrity check

**4.58**
- Sentinelle 1.4.0: burst limit, bot check, auto-ban

**4.57**
- Sentinelle 1.3.0: security advisor and score

**4.56**
- Sentinelle 1.2.0: IP bans, CSV export, widgets, e-mail alerts
- Modules: "Update database" action

**4.55**
- Sentinelle 1.1.0: attack filters

**4.54**
- New security module Sentinelle 1.0.0

**4.53**
- Proxy headers trusted only from a declared proxy

**4.52**
- Readme: Getting Started; zip extension required

**4.51**
- Module widgets: trends, card widths

**4.50**
- Dashboard widgets shipped by modules

**4.49**
- FAQ 1.0.1: table fix

**4.48**
- Form class: time field again

**4.47**
- Modules can ship their own admin pages

**4.46**
- Admin login page settings (colors, logo, version)

**4.45**
- Old sbconfig.php supported; foreign vendor/ folders listed

**4.44**
- CSRF token on every admin POST; new date formats

**4.43**
- Database catch-up for old sites

**4.42**
- Security audit (part 3); News 1.0.3, Downloads 1.0.1

**4.41**
- Security audit (part 2); News 1.0.2

**4.40**
- Shop module renamed "store" 2.0.0

**4.39**
- Security audit (part 1)

**4.38**
- Contact 1.1.0: received messages kept and listed

**4.37**
- PHP 8 / PHP 9 readiness fixes

**4.36**
- Update page: database behind the files upgraded

**4.35**
- Update page fixes

**4.34**
- Modules: undo the last operation
- Update page: progress where the action happens

**4.33**
- PHP 8 warnings removed

**4.32**
- Update page: progress and errors in place
- Stray system files removed

**4.31**
- Modules card simplified; full module removal

**4.30**
- Fixed update from 4.28 or earlier
- Update page layout and counter

**4.29**
- Modules separated from the core, each with its own version
- New Modules card in Configuration > Update

**4.28**
- Text conversion keeps non Latin-1 characters
- Maintenance page and form class fixes

**4.27**
- Media folder and URL set by the core

**4.26**
- Media uploads hardened
- Media folder fixed to upload/

**4.25**
- Configuration > Server rebuilt

**4.24**
- Font Awesome 7 served locally
- Blocked IPs and anti-flood fixed; local Chart.js

**4.23**
- Google Analytics 4, local Google Fonts
- Pinkrio: ColorBox fix

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
