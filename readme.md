# [SBUIADMIN](https://github.com/informatux45/sbuiadmin/)
- CMS SBootstrap Admin Responsive
- Contributors: [informatux45](https://github.com/informatux45)
- Stable version: 4.13
- License: [GPLv3](http://www.gnu.org/licenses/gpl-3.0.html "GNU General Public License v3")

---

### Specifications

Ready | Features
--- | ---
*✓* | PHP 8.4+ (PHP 8.5 ready) / MySQL 5.7+ / MariaDB 10.3+
*✓* | HTML 5 / CSS 3
*✓* | Bootstrap (front themes), Adminator administration theme (dark mode)
*✓* | Online installer (installation key, server requirements check), signed net install and admin updates with rollback
*✓* | Responsive design (front / administration)
*✓* | 5 themes included
*✓* | User management (per-module rights), two-factor authentication by email
*✓* | ALTCHA anti-bot (self-hosted, no Google), temporary lockout after failed logins
*✓* | Settings stored in database, secrets encrypted, database credentials outside the web root
*✓* | Rewrite URLs
*✓* | Multilanguage
*✓* | Modules (Articles, Pages, Slider, Contact forms, Tables, Tabs, FAQ, Downloads, Galleries, Search)
*✓* | Content blocks, Page Builder
*✓* | Additional HTML content (Smarty ready)
*✓* | Renamable and standalone administration directory
*✓* | Developers (Smarty templates, Sandbox, Kint debug, Modules, ...)
*✓* | Linux / Apache servers (recommended)


---

### Installation

**Net install (recommended)**
1. Upload `sbuiadmin_netinstall.php` **alone** to the (empty) folder of your future site, then open it in your browser.
2. Click *Install SBUIADMIN*: the latest release published on [GitHub Releases](https://github.com/informatux45/sbuiadmin/releases) is downloaded and **verified before anything is written**:
   - HTTPS with certificate verification, GitHub hosts only;
   - Ed25519 signature of the release manifest (the public key is embedded in `sbuiadmin_netinstall.php`, the private key is never published);
   - exact size and SHA-256 of the archive;
   - every archive entry is checked (no `..`, absolute path or symbolic link, nothing written outside the folder).

   If any check fails, nothing is installed.
3. The netinstall file deletes itself, then the installation wizard (`backdoor/`) takes over: database, administrator account, installation key.

Net install refuses to run where SBUIADMIN is already installed: delete it from your server.

**Manual install**
Download `sbuiadmin-X.Y.zip` from a release, check its SHA-256 against `sbuiadmin-release.json`, extract it into your site folder and open `backdoor/` in your browser.

Installing in a sub-folder: see `help.txt`.

---

### Screenshots

![A theme (front)](https://informatux.ddns.net:744/home/tools/demo_github/sbuiadmin-theme-2.jpg "A theme (front)")

![A theme (front)](https://informatux.ddns.net:744/home/tools/demo_github/sbuiadmin-theme-1.jpg "A theme (front)")

![Administration login](https://informatux.ddns.net:744/home/tools/demo_github/sbuiadmin-login-1.jpg "Administration login")

![Administration](https://informatux.ddns.net:744/home/tools/demo_github/sbuiadmin-admin-2.jpg "Administration")

[More screenshots...](https://informatux.ddns.net:744/home/tools/demo_img/ "SBUIADMIN Screenshots")

---

### Changelog

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



