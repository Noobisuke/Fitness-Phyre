# Fitness Phyre: Launch Checklist

Nothing below has been invented. Everything in section 1 is **unconfirmed** and is currently empty, hidden, or shown as "ask us / contact us" on the website. Get each item confirmed by Fitness Phyre, then enter it in the place shown.

## 1. Information Fitness Phyre must provide

| # | Item | Where to enter it | What the site shows until then |
|---|------|-------------------|--------------------------------|
| 1 | Official business name and spelling | `config/site.php` > `name` | "Fitness Phyre" (as you provided) |
| 2 | Confirmed full address and **PIN code** | `config/site.php` > `address_line`, `postal_code` | Address as provided; PIN left out |
| 3 | Confirmed phone number (and that it works on WhatsApp) | `config/site.php` > `phone_display`, `phone_e164`, `whatsapp_number` | +91 98031 58000 (as provided) |
| 4 | Business email | `config/site.php` > `email` | Not shown anywhere |
| 5 | **Opening hours** (every day, or "closed") | `config/site.php` > `hours`, then set `hours_verified` to `true` | "Please call to confirm timings" |
| 6 | **Membership plans and prices**, and what each includes | `config/plans.php` | "Contact us for current membership plans and pricing" |
| 7 | Which programs are **actually running** | `config/site.php` > `programs`: set `confirmed` to `true`, delete the ones not offered | "Ask us if this is running now" on every program |
| 8 | Trainer names, roles, photos (with their permission) | `config/trainers.php` | No trainer section at all |
| 9 | Real photos of the gym, equipment, classes | Admin panel > Gallery; also `assets/images/about/` | Labelled placeholders |
| 10 | Logo (PNG/SVG, transparent background) | Save in `assets/images/`, then set `logo` in `config/site.php` | Text logo "FP Fitness Phyre" |
| 11 | Social media accounts (only if they exist) | `config/site.php` > `social` | Not shown |
| 12 | Genuine reviews the gym is happy to show (with reviewer permission) | `config/reviews.php` | Only the rating and a "View on Google" button |
| 13 | Current Google rating (it changes) | `config/site.php` > `rating` | 4.6 (as provided) |
| 14 | Confirmed facilities (e.g. changing rooms, parking, AC) | Edit text in `about.php` | **Not mentioned** (nothing is claimed) |
| 15 | Any special offers or joining fees | Edit text on `membership.php` | **Not mentioned** |
| 16 | The domain name the site will use | `config/site.php` > `site_url`, and `robots.txt` | Relative links only |
| 17 | Who receives enquiries and how fast they reply | Not in code. It is a business decision. | n/a |

## 2. Content written by me that the gym should read and approve

The wording is deliberately general and makes no specific claims, but the gym must confirm it is accurate:

- Home page: "A local gym for regular training", the six "What you can expect" points (training environment, variety, guidance, easy to reach, community, flexible options).
- About page: all paragraphs, the "Who it is for" and "Why people choose us" lists.
- Programs: the one-line description and "Suited for" text for each of the 9 programs.
- Membership: the four benefit cards and five FAQ answers.
- Form dropdown choices: goals, time slots, "Interested in" options.

## 3. Placeholders to replace

| Placeholder | Replace by |
|-------------|-----------|
| Home page "Gym floor photo" box | Save a 1200 x 900 photo as `assets/images/about/gym-floor.jpg` |
| About page "Gym photo" box | Save a 1200 x 900 photo as `assets/images/about/about-main.jpg` |
| Gallery "Placeholder" boxes | Upload photos in Admin > Gallery |
| Text logo and generic "FP" favicon | Real logo (and a favicon made from it) |
| WhatsApp/Facebook link preview image | `assets/images/og-default.jpg` (1200 x 630) and set `site_url` |
| `site_url` and `Sitemap:` line in `robots.txt` | The real domain |

## 4. Recommended before launch (not code)

- **Privacy notice.** The forms collect names and phone numbers. Add a short notice saying how the gym uses them (for example, only to reply to the request). Have the gym owner confirm the wording, and check what India's data-protection rules require for the business. I am not a lawyer.
- **Google Business Profile.** Make sure the name, address, phone and hours there match the website exactly.
- **Who checks the admin panel.** Enquiries only help if someone looks at them daily. Consider a daily routine for the gym staff.
- **Test with the owner.** Show them the site on their own phone and have them submit a test enquiry and trial request.

## 5. Final testing checklist (do this on the live site)

Tick each one after the site is on the real hosting with HTTPS.

**Pages and links**
- [ ] All 8 pages open (Home, About, Programs, Membership, Gallery, Reviews, Contact, Book a Trial)
- [ ] Every menu item and footer link works, including the mobile hamburger menu
- [ ] A made-up address such as `/nothing-here` shows the friendly "Page not found" page
- [ ] The padlock shows (HTTPS), and `http://` redirects to `https://`

**Buttons**
- [ ] "Call Now" / "Call Us" opens the phone dialler with +91 98031 58000 (test on a phone)
- [ ] WhatsApp buttons open a chat with the pre-filled message (floating button, Contact, Programs)
- [ ] "Get Directions" and "View on Google Maps" open the correct place
- [ ] "View on Google" opens the Fitness Phyre listing

**Map**
- [ ] The map shows streets (not a grey box) and the marker is on the gym
- [ ] Works on a phone: the page scrolls normally, pinch-zoom and the + / - buttons work

**Forms**
- [ ] Contact form: submit empty (errors show), bad phone, bad email, then a valid one (success message)
- [ ] Trial form: past date is refused; a valid request shows exactly "Thanks! Your trial request has been received. Our team will contact you to confirm the time."
- [ ] Refreshing after success does not send a second copy
- [ ] Both valid test submissions appear in the admin panel

**Admin**
- [ ] Wrong password is refused; right password logs in
- [ ] Mark contacted, mark new, and delete work
- [ ] Gallery: upload a photo, see it on the public Gallery page, open it in the lightbox, delete it
- [ ] Log out, then open `/admin/enquiries.php`: you are sent to the login page
- [ ] `/admin/setup.php` is **deleted** from the server

**Security**
- [ ] `https://your-domain/config/db.php` and `/config/site.php` show an error page (not a blank page and not code)
- [ ] `https://your-domain/database/schema.sql` shows an error page
- [ ] `https://your-domain/includes/` shows an error page, not a file list

**Browsers and screens**
- [ ] Chrome and Edge on a laptop; Chrome on an Android phone; Safari on an iPhone if possible
- [ ] Nothing overflows sideways on a small phone (about 360 px wide)

**Search**
- [ ] `https://your-domain/sitemap.xml` loads and lists 8 pages
- [ ] `robots.txt` shows the real sitemap address
- [ ] Add the site to Google Search Console and submit the sitemap

## 6. Testing already done during development

These ran against a local copy using a stand-in database (SQLite), **not** real MySQL, and in Chromium only.

- **Forms, spam checks, admin and security:** 48 automated checks, all passed (validation, CSRF, honeypot, rate limit, duplicates, SQL injection and script text, login lockout, session handling, logged-out access).
- **Gallery, headers, SEO:** 33 automated checks, all passed (upload rules, a PHP file disguised as an image, code hidden inside a JPEG, path tricks on delete, security headers, sitemap, 404 page, structured data).
- **Accessibility:** automated axe audit, 0 violations on 9 pages at desktop and phone sizes (WCAG 2.0/2.1 A and AA plus best practices). Keyboard use of the gallery viewer checked.
- **Browser:** no content-security-policy violations, no broken images or scripts, no horizontal overflow.

**Not tested here:** real MySQL/MariaDB, Apache (`.htaccess` rules), real HTTPS, Firefox/Safari/Edge, real phones, map tile images (no internet in the test environment). That is what section 5 is for.
