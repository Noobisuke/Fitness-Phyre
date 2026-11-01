# Fitness Phyre Website

Website for **Fitness Phyre**, a gym and fitness centre in Karnail Singh Nagar Phase-II, Ludhiana, Punjab.
Built with plain **PHP 8, MySQL/MariaDB, HTML, CSS and JavaScript**. No frameworks, no build step, no paid services.

- Public pages: Home, About, Programs, Membership, Gallery, Reviews, Contact, Book a Trial
- Contact and trial forms save to the database
- WhatsApp and call buttons, an OpenStreetMap map, directions
- A simple admin panel to read enquiries, mark them contacted, delete them, and manage gallery photos

> **Before going live, read [LAUNCH-CHECKLIST.md](LAUNCH-CHECKLIST.md).** It lists everything Fitness Phyre must confirm. Nothing unconfirmed has been invented: prices, trainers, hours, email, social accounts and reviews are empty or shown as "contact us".

---

## Contents

1. [Requirements](#1-requirements)
2. [Project structure](#2-project-structure)
3. [Local setup](#3-local-setup)
4. [Database creation](#4-database-creation)
5. [Database configuration](#5-database-configuration)
6. [Running locally](#6-running-locally)
7. [Admin setup](#7-admin-setup)
8. [Changing business information](#8-changing-business-information)
9. [Replacing images](#9-replacing-images)
10. [Deploying to hosting](#10-deploying-to-hosting)
11. [Production configuration](#11-production-configuration)
12. [HTTPS](#12-https)
13. [Security checklist](#13-security-checklist)
14. [Database backup](#14-database-backup)
15. [Troubleshooting](#15-troubleshooting)
16. [How it works (for your viva)](#16-how-it-works-for-your-viva)

---

## 1. Requirements

| Needed | Notes |
|--------|-------|
| PHP **8.0 or newer** | Extensions: `pdo_mysql`, `mbstring`, `fileinfo`, `gd` (all normally on by default). `exif` is optional (fixes sideways phone photos). |
| MySQL 5.7+ or MariaDB 10.3+ | Any normal shared hosting plan includes this. |
| Apache with `.htaccess` enabled | Almost all shared hosting. Nginx works too but needs equivalent rules (see section 15). |
| A browser | For testing. |

You do **not** need XAMPP, Composer, Node.js or any internet service. Leaflet (the map library) is already included in `assets/vendor/leaflet/`.

## 2. Project structure

```
fitness-phyre/
├── index.php, about.php, programs.php, membership.php,
│   gallery.php, reviews.php, contact.php, trial.php   <- the 8 public pages
├── 404.php               <- friendly "page not found"
├── sitemap.php           <- generates /sitemap.xml
├── robots.txt, .htaccess
├── config/
│   ├── site.php          <- ALL business details (edit this most)
│   ├── plans.php         <- membership plans/prices (empty until confirmed)
│   ├── reviews.php       <- real reviews to show (empty until confirmed)
│   ├── trainers.php      <- trainers (empty until confirmed)
│   └── db.sample.php     <- copy to db.php and add your database login
├── includes/             <- shared code (header, footer, forms, security, admin helpers)
├── admin/                <- admin panel (login, dashboard, enquiries, trials, gallery)
├── assets/
│   ├── css/, js/         <- styles and scripts
│   ├── vendor/leaflet/   <- the map library (self-hosted)
│   └── images/           <- gallery/, about/, trainers/ (+ reserved hero/, programs/)
├── database/
│   ├── schema.sql        <- the tables
│   ├── create_admin.php  <- make an admin account (command line)
│   └── reset_admin_password.php
├── README.md
└── LAUNCH-CHECKLIST.md
```

## 3. Local setup

Easiest way (no XAMPP): use PHP's built-in server.

1. Install PHP 8 (Windows: download from windows.php.net and add it to PATH; macOS: `brew install php`; Linux: `sudo apt install php php-mysql php-mbstring php-gd`).
2. Open a terminal in the project folder and check: `php -v` (must say 8.x).
3. Install MySQL or MariaDB, or use the one in XAMPP/Laragon if you already have it.

The public pages work **without** a database. Only the forms and admin panel need it.

## 4. Database creation

**With phpMyAdmin** (hosting or XAMPP)

1. Open phpMyAdmin > **New** > name it `fitness_phyre`, collation `utf8mb4_general_ci` > Create.
2. Click the new database > **Import** > choose `database/schema.sql` > **Go**.
3. You should now see the tables: `admins`, `login_attempts`, `enquiries`, `trial_requests`, `gallery`.

**With the command line**

```bash
mysql -u root -p -e "CREATE DATABASE fitness_phyre CHARACTER SET utf8mb4;"
mysql -u root -p fitness_phyre < database/schema.sql
```

> The `gallery` table is unused (gallery photos are plain files). It is kept in case you later want captions in the database. You can drop it.

## 5. Database configuration

1. Copy `config/db.sample.php` to `config/db.php`.
2. Edit `config/db.php` with your details:

```php
return [
    'host'    => 'localhost',
    'name'    => 'fitness_phyre',
    'user'    => 'your_db_user',
    'pass'    => 'your_db_password',
    'charset' => 'utf8mb4',
];
```

`config/db.php` is listed in `.gitignore`, so it is never uploaded to GitHub. **Never share it or commit it.**

## 6. Running locally

```bash
cd fitness-phyre
php -S localhost:8000
```

Open <http://localhost:8000>. Press `Ctrl + C` in the terminal to stop.

(With XAMPP instead: copy the folder into `htdocs`, open `http://localhost/fitness-phyre/`, and set `'base_url' => '/fitness-phyre'` in `config/site.php`.)

## 7. Admin setup

The admin panel is at `/admin/login.php`. There is **no default password**. You create the account yourself.

**Option A: command line (best; use on your computer or if the host gives SSH)**

```bash
php database/create_admin.php
```

Type a username and a password of **at least 10 characters**.

**Option B: shared hosting without command line**

1. In `config/db.php`, add a line with a long random key (12+ characters):
   `'setup_key' => 'put-a-long-random-phrase-here',`
2. Open `https://your-domain/admin/setup.php`, enter the setup key, a username and a password.
3. **Immediately delete `admin/setup.php` from the server and remove `setup_key` from `config/db.php`.**
   (The page switches itself off once an admin exists, but delete it anyway.)

**Forgot the password?** Run `php database/reset_admin_password.php`.

**What the admin can do:** see new enquiries and trial requests (with one-tap call and WhatsApp links), mark them contacted, delete them, and upload or delete gallery photos. Failed logins are limited to 5 per 15 minutes, and you are logged out after 1 hour of inactivity.

## 8. Changing business information

Everything is in **`config/site.php`**. Change a value once and it updates on every page.

| Setting | What it controls |
|---------|-----------------|
| `phone_display`, `phone_e164`, `whatsapp_number` | Phone shown, `tel:` links, WhatsApp links. `whatsapp_number` is digits only with country code, no `+`. |
| `address_line`, `city`, `state`, `postal_code` | Address everywhere (and in Google structured data) |
| `lat`, `lng`, `maps_url` | Map marker, "Get Directions", "View on Google Maps" |
| `rating` | The "4.6/5" shown (re-check it on Google before launch) |
| `hours` + `hours_verified` | Opening hours. Fill the times, then set `hours_verified` to `true`. Until then the site says "call to confirm". |
| `email` | Empty = hidden everywhere. Fill it = shown in the footer and Contact page. |
| `social` | Empty = hidden. Add full URLs only if the accounts exist. |
| `logo` | Empty = text logo. Else e.g. `'assets/images/logo.png'`. |
| `site_url` | Set to the real domain at launch, e.g. `'https://www.example.in'`. Used for canonical links, sitemap, share image. |
| `base_url` | `''` at a domain root. `'/fitness-phyre'` if the site sits in a sub-folder. |
| `programs` | The 9 program cards: names, text, "suited for". Set `confirmed` to `true` once the gym confirms it is offered. |
| `rate_limit_per_hour` | Max form submissions per visitor (IP) per hour. Default 5. |

Other files in `config/`:

- **`plans.php`**: add membership plans and prices when confirmed. They appear as cards automatically.
- **`reviews.php`**: add genuine reviews (with permission). Empty = only the rating and a Google link.
- **`trainers.php`**: add real trainers. Empty = no trainer section is shown.

Text on pages (About, Membership FAQ) is edited directly in the `.php` files. Edit only the plain text between tags.

## 9. Replacing images

| Where | File | Recommended size |
|-------|------|------------------|
| Gallery | Upload in **Admin > Gallery** (no file transfer needed) | Landscape about 1200 x 900 px. Photos wider than 1600 px are shrunk automatically. Max 5 MB, JPG/PNG/WebP. |
| Home page photo | `assets/images/about/gym-floor.jpg` | 1200 x 900 px |
| About page photo | `assets/images/about/about-main.jpg` | 1200 x 900 px |
| Trainer photos | `assets/images/trainers/<name>.jpg` + `photo` in `config/trainers.php` | 600 x 600 px, square |
| Share preview (WhatsApp, Facebook) | `assets/images/og-default.jpg` (needs `site_url` set) | 1200 x 630 px |
| Logo | `assets/images/logo.png` + set `logo` in `config/site.php` | about 80 px high, transparent PNG or SVG |
| Hero and Programs | Not used: the design is text-only there. Folders are reserved if you add photos later. | |

Drop the file in with the **exact name** and the placeholder disappears automatically. Keep photos under about 300 KB each (use a free tool like squoosh.app) so the site loads fast on mobile data.

Only use photos that really show Fitness Phyre. Do not use stock photos as if they were the gym.

## 10. Deploying to hosting

Beginner-friendly steps for typical cPanel shared hosting. Other hosts are similar.

1. **Choose hosting.** Any Indian or international shared host with PHP 8+, MySQL/MariaDB, free SSL (Let's Encrypt/AutoSSL) and `.htaccess` support is enough.
2. **Domain.** Buy a domain and point it to the host (the host will give you nameservers to set at the domain registrar). Wait up to a few hours.
3. **Choose PHP 8.** In cPanel open **Select PHP Version** (or MultiPHP Manager) and pick 8.1 or newer. Make sure `pdo_mysql`, `mbstring`, `fileinfo`, `gd` are ticked.
4. **Upload the files.** Zip the project **without `config/db.php`**. In **File Manager**, open `public_html`, upload the zip and **Extract**. The site files (`index.php` etc.) must be directly inside `public_html`, not in a sub-folder. (You can also use FTP with FileZilla.)
5. **Create the database.** cPanel > **MySQL Databases**: create a database, create a user with a strong password, and **add the user to the database with All Privileges**. Note the full names: they usually get a prefix such as `cpuser_fitness`.
6. **Import the tables.** cPanel > **phpMyAdmin** > select your database > **Import** > `database/schema.sql`.
7. **Add the database login.** In File Manager, copy `config/db.sample.php` to `config/db.php`, click **Edit**, and enter the host (`localhost`), full database name, user and password. Set the file permission to **640** (right-click > Permissions).
8. **Set the site address.** Edit `config/site.php`: set `'site_url' => 'https://www.your-domain.in'`. Edit `robots.txt`: remove the `#` from the `Sitemap:` line and put your domain.
9. **Gallery folder permission.** Make sure `assets/images/gallery/` is writable (permission 755; if uploads fail, try 775).
10. **Create the admin** (section 7, Option B), then **delete `admin/setup.php`**.
11. **Turn on HTTPS** (section 12).
12. **Test everything** with the checklist in `LAUNCH-CHECKLIST.md`, section 5.
13. **Make a first backup** (section 14).

## 11. Production configuration

- `config/db.php` exists on the server only, with a strong, unique database password.
- `config/site.php`: real `site_url`; `base_url` is `''`; confirmed details filled in.
- PHP error display is already **off** (errors go to the host's error log). Never turn it on for a live site.
- Uncomment the HTTPS redirect in `.htaccess` once SSL works (section 12).
- `admin/setup.php` deleted and `setup_key` removed from `config/db.php`.
- If the host keeps PHP's upload limit at 2 MB, large gallery photos will be refused. Ask the host to raise `upload_max_filesize` and `post_max_size`, or resize photos before uploading.
- If your host puts the site behind a proxy or CDN (such as Cloudflare), every visitor can appear to have the same IP and the form limit will treat them as one person. Ask your host or developer to handle that before enabling a CDN.

## 12. HTTPS

HTTPS is required for a real business site (the padlock, and it protects the admin login).

1. In cPanel open **SSL/TLS Status** (or **Let's Encrypt**) and run **AutoSSL** / issue a free certificate for the domain and `www`.
2. Open `https://your-domain` and check the padlock.
3. In `.htaccess`, remove the `#` from these three lines so everyone is sent to HTTPS:

```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

4. Once HTTPS works, the site automatically marks cookies "secure" and sends the HSTS header.

## 13. Security checklist

What the code already does, and where:

| Protection | How | Where |
|------------|-----|-------|
| SQL injection | Every query uses prepared statements; table names come from fixed lists | `includes/forms.php`, `includes/admin_auth.php`, `admin_records.php` |
| Cross-site scripting (XSS) | All output goes through `e()`; a Content-Security-Policy allows scripts only from this site | `includes/functions.php`, `includes/security.php` |
| CSRF | A secret token in every form, checked on every POST (including logout and delete) | `includes/security.php` |
| Passwords | `password_hash()`; never stored or logged in plain text; no default password | `includes/admin_auth.php` |
| Login attacks | 5 failed attempts per 15 minutes per IP, same error for wrong user or password | `includes/admin_auth.php` |
| Sessions | HttpOnly, SameSite, secure on HTTPS, new ID on login, strict mode, 1-hour idle timeout | `includes/security.php` |
| Spam | Hidden honeypot field, 5 submissions/hour/IP, duplicate-phone check, all server-validated | `includes/forms.php` |
| File uploads | Admin only; real type check (not the file name); size and pixel limits; random file name; re-encoded to remove hidden code; script execution disabled in the folder | `includes/gallery_admin.php`, `assets/images/gallery/.htaccess` |
| Exposed files | `.htaccess` blocks `config/`, `includes/`, `database/`, `.sql`, `.md`, `.log` | `.htaccess` in each folder |
| Error leaks | `display_errors` off; DB errors go to the log, visitors get a friendly message | `includes/security.php`, `includes/db.php` |
| Clickjacking, content sniffing | `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy` | `includes/security.php` |
| Search engines | Admin pages send `noindex`; `robots.txt` disallows `/admin/` | `includes/admin_auth.php`, `robots.txt` |

**Your checklist before and after launch**

- [ ] `config/db.php` has a strong password and permission 640 or 600
- [ ] `config/db.php` is NOT in any GitHub repository
- [ ] `admin/setup.php` deleted; `setup_key` removed
- [ ] Admin password is long and unique (a passphrase of 4+ random words is good)
- [ ] HTTPS works and the redirect is on
- [ ] `/config/db.php`, `/config/site.php`, `/database/schema.sql` and `/includes/` give an error page when opened in a browser (this proves `.htaccess` works on your host)
- [ ] PHP is 8.1 or newer and kept updated by the host
- [ ] Database backups are scheduled (section 14)
- [ ] Only trusted people have the admin password; change it when staff leave (`php database/reset_admin_password.php`, or ask a developer)
- [ ] Review enquiries regularly and delete old ones you no longer need (they contain phone numbers)

## 14. Database backup

**Using phpMyAdmin (easiest)**

1. Open phpMyAdmin, select the database.
2. **Export** > Method **Quick** > Format **SQL** > **Go**. A `.sql` file downloads.
3. Keep it somewhere safe off the server (Google Drive, your computer). Name it with the date, e.g. `fitness_phyre_2026-11-01.sql`.

**Command line**

```bash
mysqldump -u DB_USER -p DB_NAME > fitness_phyre_backup.sql
```

**Restore:** create an empty database, then **Import** the `.sql` file in phpMyAdmin (or `mysql -u DB_USER -p DB_NAME < fitness_phyre_backup.sql`).

**Also back up** the `assets/images/gallery/` folder and `config/` folder (contains `db.php` and your business details). cPanel has **Backup** > **Download a Full Account Backup** too.

How often: weekly is sensible; daily if the gym gets many enquiries. Test a restore once, because an untested backup is only a hope.

## 15. Troubleshooting

| Problem | Likely cause and fix |
|---------|----------------------|
| Blank white page | A PHP error (errors are hidden on purpose). Check the host's error log (cPanel > **Errors** / `error_log` file) or temporarily test with `php -l file.php`. |
| "Sorry, we could not save your request" | `config/db.php` missing or wrong, or tables not imported. Check sections 4 and 5. |
| Admin says "database is not available" | Same as above. |
| Forms say "session expired" | Cookies blocked, or the page was open for a very long time. Reload the page and try again. |
| Gallery upload refused as too large | Host upload limit is smaller than the photo. Resize it or ask the host to raise `upload_max_filesize`. |
| Gallery upload says "could not save" | `assets/images/gallery/` is not writable. Set permission to 755 or 775. |
| Map shows a grey box | No internet, or an ad blocker blocks OpenStreetMap tiles. The "View on Google Maps" button always works. |
| Pretty URLs (`/about`) give 404 | `mod_rewrite` is off or `.htaccess` is ignored. The normal `.php` links still work. |
| Locked out of admin | Wait 15 minutes, or run `php database/reset_admin_password.php`. |
| Page looks old after an update | Hard refresh (`Ctrl+F5`). Normally files refresh automatically. |
| Site is in a sub-folder and links break | Set `base_url` in `config/site.php` (and change `ErrorDocument` in `.htaccess`). |
| Nginx hosting | Nginx ignores `.htaccess`. Ask the host to deny `config/`, `includes/`, `database/` and `*.sql`, and to run no scripts in `assets/images/gallery/`. |

## 16. How it works (for your viva)

**Request flow.** A visitor opens `about.php`. That file sets the page title and description, includes `includes/header.php` (menu, SEO tags), prints its content, then includes `includes/footer.php`. All business details come from `config/site.php` through the helper `site('key')`. Because every page uses the same header and footer, changing one place changes the whole site.

**Form flow** (`contact.php`, `trial.php`):
1. The page calls `handle_form()` before any HTML.
2. On a normal visit it just shows the form with a hidden CSRF token.
3. On submit (POST) it checks: CSRF token > honeypot > server-side validation > rate limit > duplicate check.
4. It saves with a prepared statement (`INSERT ... VALUES (?, ?, ...)`), then **redirects** (Post/Redirect/Get) so refreshing cannot resend it.
5. The success message is shown once. For trials it says the request was **received**, never "confirmed", because a person must still call back.
6. JavaScript (`assets/js/forms.js`) validates in the browser for speed, but the server never trusts it.

**Admin flow.** Every admin page starts with `require_admin()`, which sends visitors without a valid session to the login page. Buttons (mark contacted, delete) are small POST forms with CSRF tokens. Table names are chosen from a fixed list, never from user input.

**Why these choices**
- *No framework:* the whole site is about 60 small files (43 of them PHP) that you can read in a day or two.
- *Plain files for the gallery:* no extra database table to keep in sync; the folder is the single source.
- *Self-hosted Leaflet + OpenStreetMap:* free, no API key, nothing private in the browser.
- *System fonts:* no font download, so pages load faster on mobile data.
- *Empty config instead of made-up data:* every unconfirmed fact is blank and hidden, so the site never states anything untrue.

**Design.** Deep green-charcoal with a saffron accent (not the usual black and red), strong condensed headings, small corner radii, 48 px tap targets, and a floating WhatsApp button, because most local enquiries in India arrive by WhatsApp.

---

*Map data &copy; OpenStreetMap contributors. Leaflet is &copy; its authors (BSD-2 licence, included in `assets/vendor/leaflet/LICENSE`).*
