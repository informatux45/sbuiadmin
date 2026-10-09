# [SBUIADMIN](https://github.com/informatux45/sbuiadmin/)
- CMS SBootstrap Admin Responsive
- Contributors: [informatux45](https://github.com/informatux45)
- Stable version: 4.12
- License: [GPLv3](http://www.gnu.org/licenses/gpl-3.0.html "GNU General Public License v3")

---

### Specifications

Ready | Features
--- | ---
*✓* | PHP 8.1+ (PHP 8.4 ready) / MySQL 5.7+ / MariaDB 10.3+
*✓* | HTML 5 / CSS 3
*✓* | Bootstrap (front themes), Adminator administration theme (dark mode)
*✓* | Online installer (installation key, server requirements check)
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

### Screenshots

![A theme (front)](https://informatux.ddns.net:744/home/tools/demo_github/sbuiadmin-theme-2.jpg "A theme (front)")

![A theme (front)](https://informatux.ddns.net:744/home/tools/demo_github/sbuiadmin-theme-1.jpg "A theme (front)")

![Administration login](https://informatux.ddns.net:744/home/tools/demo_github/sbuiadmin-login-1.jpg "Administration login")

![Administration](https://informatux.ddns.net:744/home/tools/demo_github/sbuiadmin-admin-2.jpg "Administration")

[More screenshots...](https://informatux.ddns.net:744/home/tools/demo_img/ "SBUIADMIN Screenshots")

---

### Changelog

**4.12**
- ALTCHA replaces Google reCAPTCHA (admin login, user module, contact forms), single core API
- Temporary lockout after failed logins (per login and per IP, 2FA codes included)
- Settings merged into the sb_config table, with an updated_at column
- PHP 8.4 Ready

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



