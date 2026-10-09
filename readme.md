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

**4.15**
- Front: theme jQuery plugins no longer overwritten (Page Builder lightbox and jQuery plugin load jQuery only when missing)
- Front: broken sample custom JavaScript from the installer fixed (migration), manifest.json loaded with credentials
- Update page: alerts layout and release notes formatting fixed

**4.14
- Theme setting moved to the database, theme switch validated and CSRF protected
- dashboard.txt and settings.txt removed (migration keeps their content)
- Two-factor authentication off by default, enabled in Configuration after a test code is received
- Several sites on one hosting account: a site never reads or rewrites another site's sbdbconfig.php
- Installer: settings written only after checking the database configuration it reads back

**4.13
- Update from the administration: daily check of signed GitHub releases, verified before install (signature, archive and per-file SHA-256), live progress
- Idempotent SQL migrations shipped with each release, database schema version tracked
- One-step rollback of the last update (files and database), automatic rollback on failure
- Encrypted backups (files and database) stored outside the web root when possible
- Old UPGRADE mode (unverified HTTP server) removed

**4.12**
- Signed releases: secure net install from GitHub (Ed25519 signature + SHA-256 verified)
- ALTCHA replaces Google reCAPTCHA (admin login, user module, contact forms), single core API
- Temporary lockout after failed logins (per login and per IP, 2FA codes included)
- Settings merged into the sb_config table, with an updated_at column
- PHP 8.4 minimum, PHP 8.5 Ready, PHP 9 preparation
- Smarty 4.5.8 (security fix)

**4.11**
- Settings stored in database, automatic migration of settings.txt
- Database credentials in sbdbconfig.php, outside the web root when possible
- Encrypted secrets, never sent back to the browser
- Installer: direct settings write, one-click removal of install/, server requirements check

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



