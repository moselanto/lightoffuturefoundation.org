# HANDOVER - Light of Future Foundation website (single source of truth)

**Status as of 14 Nov 2026 (evening): LIVE = theme `light-of-future` 0.72.2 + plugin `light-of-future-core` 0.47.0, both on `main` and installed in wp-admin.** This is the one current document. History is in `CHANGELOG.md` (one entry per release). When you release, update section 1 ("Status") and section 9 ("Pending") and prepend a CHANGELOG entry.

**Next agent: read section 1, then section 9, then `docs/SEO-AEO-GEO.md`.**

## 1. Status
### Live and verified signed out (14 Nov 2026)
- **Theme 0.72.2 / Core 0.47.0** installed via wp-admin uploads, Dashboard opened, LiteSpeed purged. 48 sitemap URLs return 200, no PHP warnings.
- **Theme 0.72.1:** default share picture `assets/images/brand/og-default.jpg` (og:image/twitter:image on Home and pages without a featured image); Cart / Checkout / My account out of the page sitemap.
- **Core 0.46.7:** `includes/hardening.php` writes a marked `.htaccess` block denying `/readme.html` and `/license.txt` (both return 403). Akismet and Hello Dolly plugins deleted (18 plugins, all active).
- **Core 0.47.0 (SEO/AEO/GEO):** connected JSON-LD graph (`/#organization` NGO, `/#website`, `/#logo`, `/#founder`, `<url>#webpage` with WebPage/AboutPage/ContactPage/CollectionPage, BreadcrumbList, 11 approved leaders as Person on /leadership/, Article on stories, FAQPage from the visible FAQ: 10 on /faq/, 4 on /donate/); robots.txt AI crawler groups (owner chose: allow all, including training bots); `/llms.txt`; `max-snippet:-1, max-video-preview:-1`; Google tag with Consent Mode v2 + cookie notice; events `donate_start`, `generate_lead`, `contact_click`; verification meta tags. Theme 0.72.2 only adds the FAQ widget hook.

### Google / Bing accounts (owner decision: the foundation's own Google account, lightofthefutureorg@gmail.com)
- **GA4:** account 416324192, property 564372601, stream 16342148210, **Measurement ID G-NSPE2134S2**, Kenya time, KES. ID saved in Light of Future > Organisation details. Data arriving (Realtime). Key events `donate_start`, `generate_lead`, `contact_click` created by name (Admin > Key events) on 14 Nov; no stream data for them yet.
- **Search Console:** URL-prefix property https://lightoffuturefoundation.org/ verified (HTML tag in Organisation details + GA). `wp-sitemap.xml` submitted: Success.
- **Bing Webmaster Tools:** imported from Search Console, sitemap submitted (processing), Search Performance and AI Performance (Copilot citations) reports available.
- **Google Business Profile:** NOT created. Owner chose a public office address on Maps; needs the office street address and opening hours (P.O. Box not accepted by Google). Then save the Maps link in Organisation details > Google Business Profile link (feeds `sameAs`).

### SEO planning documents (all in `docs/`, 14 Nov)
`SEO-AEO-GEO.md` (audit, fact register, schema, crawler policy, measurement), `KEYWORD-AND-TOPICAL-MAP.md`, `AEO-PAGE-QA-PLAN.md`, `CONTENT-AUTHORITY-ROADMAP.md` (Dec 2026 - May 2027), `DIGITAL-PR-AND-BACKLINK-PLAN.md`, `AI-VISIBILITY-CHECKLIST.md` (monthly). No search volumes anywhere; estimates are marked.

### Access notes
- wp-admin sign-in works through the browser driver (owner supplies credentials in the browser; never store them in the repo).
- Release zips are in the Town Content Library collection Light of Future Foundation/releases (0.72.1, 0.72.2 theme; 0.46.7, 0.47.0 Core).

### Older release notes (reference only)
- **What 0.69.0 adds:** the WhatsApp button always stays visible on phones/tablets (tuck and slide-off removed in JS and CSS), Home Give today re-balanced (no overflowing amount tiles, full-width fund select), one arrow on story cards, top-weighted gallery crops (4:5 tiles on phones), and a site-wide mobile design pass (see section 3a). Details in `CHANGELOG.md`.
- **Live:** 0.68.0 was installed and checked by the parent session on 10 Nov (the 0.69.0 fixes come from that check).
- **What 0.68.0 adds:** Gallery page redesign (CSS block 0.68.0 in shop.css after the 0.61.1 gallery rules + a small shop.js block): featured mosaic without gaps, filter pills that wrap (no sideways scroll, no fixed bar), uniform 4:3 photo tiles 4 / 3 / 2 per row, video cards with a gold play button, sensitive-image button fixed, polished lightbox. Details in `CHANGELOG.md`.
- **What 0.67.0 adds:** stylesheet cleanup with no intended visual change (dead overridden declarations removed, verified by script); Home Give today as a cream card; Programmes shows all 11 focus areas with no sideways scrolling; Donate page form card, trust card and other-ways-to-give cards. CSS only. Details in `CHANGELOG.md`.
- **Live (last verified):** theme 0.63.17 and Core 0.46.4, verified signed out on desktop and at 390px (9 Nov). Theme 0.64.0-0.65.3 were built on 10 Nov; check whether the parent session installed them before assuming what is live.
- **What 0.66.0 / 0.46.5 add:** KES is the default donation currency (listed first, preselected); the duplicate figures card under the home slider is gone; JSON-LD structured data; Light of Future > Media backup (zip + manifest + 48-hour token links for the server-only media); a guarded one-off that points Rosemary's story at a web-sized video once it is uploaded, and the player prefers `story-{slug}-web.mp4`. Details in `CHANGELOG.md`.
- **The site is public and indexable.** Theme 0.58 forces indexing on even if "Discourage search engines" is ticked. Never turn noindex back on.
- **Donations are built and live on /donate/** (Paystack, Core 0.46.x). Card/USD payments fail until Paystack approves USD (see section 9).

## 2. Project and people
- **Client:** Light of Future Foundation, Nairobi, Kenya (official name; not "Light of the Future"). Registered with the NGO Board of Kenya on 23 May 2025 as a charitable society. Postal address P.O. Box 53-00520, Ruai, along Kangundo Road, Nairobi, Kenya.
- **Founder:** Francis Ouma Sikuku (also "Frank Omosh" on the proposal; same person), +254 715 394 295. His personal Gmail (franksikuku3@gmail.com) is **never** published. The owner contact who confirms consent and decisions is referred to as Ngimu in the 9 Nov notes.
- **Built by:** Pimofy Digital (Moses Munyi; Agnes reviews on a 13.3in laptop and a phone and sends full-page PDF captures). Budget KES 35,000 (proposal PMF-QT-2026-0105).
- **Domain:** https://lightoffuturefoundation.org/ (built directly on the live domain, no staging). The old site https://lightoffuture.org/ is a read-only content source (captured in `docs/legacy-content.md`).
- **Public contacts:** info@lightoffuturefoundation.org, phone/WhatsApp +254 715 394 295, Facebook https://www.facebook.com/people/Light-of-future-foundation/100094073726098/.

## 3. Standing rules (all agents)
- **Never invent facts:** no made-up figures, names, quotes, prices, partners, registration numbers or payment details. Missing facts become labelled placeholders and go in `CONTENT-GAPS.md`.
- **No new wording on the site without the foundation's approval.**
- **Safeguarding:** no identifying details of children or vulnerable people without written consent (guardian consent for minors). Stories stay draft until consent is recorded (`lof_core_require_story_consent`; set `lof_consent=1` and `lof_consent_date`). Never publish private records (hospital cards, clinical notes, blood-donor forms); keep Elijah's graphic wound images blurred (media plugin `!` prefix); cut children's spoken full names from videos; Precious's location is "Nairobi" only.
- **Impact figures** render only when marked verified, with a source. One source note: "Figures supplied by the foundation (November 2026)".
- **Theme = presentation, plugin = data/logic.** Content models, settings, forms, payments, schema and backups live in Core.
- **Escape all output**; wrap visitor-facing strings in `__()` / `esc_html__()` with text domain `light-of-future` (theme) or `light-of-future-core` (plugin).
- **No secrets in git.** Paystack keys only as `LOF_PAYSTACK_PUBLIC_KEY` / `LOF_PAYSTACK_SECRET_KEY` in `wp-config.php`. Never fake payment success.
- **Commit directly to `main`** (no branches/PRs). Bump versions every release; prepend a `CHANGELOG.md` entry and update this file's status.
- **Do not use "Apply latest design"** (Appearance > Light of Future setup): it times out silently on this host. Ship page/content changes as one-off upgrades on `admin_init` inside the `is_admin()` block, guarded by an option.
- **Never reinstall the `light-of-future-media` plugin from the repo:** the repo copy has no photos and would delete them (photos live only on the server).
- Do not bring back sticky in-page nav bars on About or Leadership (owner complaint, 10 Nov).
- Impact figures: never go back to a single row (8 figures overlap). Footer main row stays one row of four columns.
- Colours: sections cream or white (sand #FAF1DC sparingly); dark brown only for footer, figures band, photo banners, donation panel, slider; gold only for accents. Brand: Earth Brown #5C3D1E, Cream #FCF6E1, Sun Yellow #FBD62B, Sun Gold #F4AF16, Amber #DC8C13; type Nunito (headings 800) + Figtree (text); Cinzel only inside the logo (`docs/brand.md`).
- Breakpoints: phone <= 640px, portrait tablet 641-991px, desktop >= 992px; root font 17px below 900px, 17.5px from 900px; content width 1200px.

## 3a. Mobile design system (0.69.0, last block of `shop.css`)
- **Breakpoints:** phone <= 640px (reference 390px; also check 360 and 414), bottom bar <= 760px, portrait tablet 641-991px, desktop >= 992px (desktop styles are not touched by this block).
- **Type scale (phones):** root 17px, body 1rem, line-height 1.6; section titles `clamp(1.6rem,6.6vw,2.05rem)`; page-hero titles `clamp(1.85rem,8vw,2.3rem)`; slider titles `clamp(1.55rem,7vw,1.95rem)`; card/person titles 1.12-1.2rem; intros 1.04rem; `text-wrap: balance` on headings. Below 360px titles step down (1.5rem / 1.75rem).
- **Spacing:** `--lof-section` 3.25rem (~55px) on phones, 3.75rem on tablets; section heads 1.5rem below; grid gaps 1rem. **Gutters:** 20px phones, 16px below 360px, 24px tablets (`.lof-container`).
- **Touch:** tap targets >= 44px (menu items 52px, buttons 48px, form submits 52px); form fields 16px text, 48px tall; gold `:focus-visible` rings.
- **Z-index order:** page content 0-20 < header 900 (the mobile menu drawer is inside it) < phone bottom bar 945 < WhatsApp button 950 < WhatsApp order modal 10000 < photo lightbox 99999.
- **Fixed elements:** bottom bar 64px + safe area (body has `padding-bottom: 72px + safe area`); WhatsApp button 54px at `bottom: 64px + 14px + safe area` on phones, `20px + safe area` on tablets; teaser/panel open above it; the footer has 56px extra bottom room so its last row clears the button; anchors use `scroll-margin-top: 88px` (72px header + 16px). Never bring back hiding/sliding the WhatsApp button.

## 4. Stack and code map
WordPress + Elementor (free) + Hello Elementor (parent) + child theme `light-of-future` + plugin `light-of-future-core` + WooCommerce (crafts shop, WhatsApp ordering) + LiteSpeed Cache. Server-only plugins: `light-of-future-media` (programme galleries, `lofm_groups()`, shortcode `[lof_media group=...]`) and `light-of-future-video-1` .. `-12` (videos; `-8` holds Margaret's videos).

| Path | What |
|---|---|
| `themes/light-of-future/functions.php`, `style.css` | `LOF_THEME_VERSION`, `Version:`. Files inside `if ( is_admin() )` (importer, shop-setup, upgrade-*.php) never run on the front end. |
| `inc/setup.php` | Enqueues, menus (`lof-primary`, `lof-footer`, `lof-legal`), colour mode (`?lof_theme=light|dark`), phone preview `/?lof_mobile_preview=light&page=SLUG&w=390`. |
| `inc/template-tags.php` | Front-end helpers (settings, social links, hero photos/facts, programme leads/icons, `lof_home_hide_impact_strip()`). |
| `inc/importer.php` (admin only) | Setup screen, page layouts `lof_setup_page_defs()`, menus, `lof_photo()`, older one-off upgrades. |
| `inc/elementor/widgets-*.php` | All `lof-*` Elementor widgets (slider in widgets-extra, impact/stories in widgets-dynamic, ...). |
| `inc/shop.php`, `shop-page.php`, `shop-single.php`, `shop-063.php`, `shop-setup.php` | WooCommerce shop presentation, WhatsApp order form, home `[lof_home_market]`. Products are WooCommerce products. |
| `inc/story-0613.php` | Front-end story video player (see section 7). |
| `inc/floating.php` | WhatsApp float, phone bottom navigation. |
| `inc/performance.php`, `speed-059.php`, `speed-062.php`, `security.php` | Caching/compression, critical CSS, security headers, login lockout, HSTS. |
| `assets/css/shop.css` (+ `shop.min.css`) | Loads on every front-end page. Version blocks in release order (later blocks win); the comment at the top is a component index. Since 0.67.0 put new CSS inside or right after the latest block of its component, not as a new override layer. `sections.css` = older layered blocks. `main`, `sections`, `shop` are served as `.min.css` (speed-059.php) unless SCRIPT_DEBUG: always regenerate the .min file from the source (keep quoted strings intact). `assets/css/critical/home-*.css` = Home first-paint CSS, inlined as is (no .min copies). |
| `assets/js/main.js`, `shop.js` | Slider, menus, FAQ, giving panel; shop/WhatsApp UI. |
| `assets/lofm-fix/` | Cleaned gallery photos copied over the media plugin by `upgrade-0642.php`. |
| `plugins/light-of-future-core/includes/settings.php` | Light of Future > Organisation details (`lof_core_setting()`). |
| `.../post-types.php`, `meta.php`, `seed.php`, `forms.php` | Content types (`lof_programme`, `lof_project`, `lof_story`, `lof_resource`, `lof_leader`, `lof_partner`, `lof_metric`), consent gate, verified figures, seed content, enquiry forms. |
| `.../donations.php`, `assets/donate.js`, `donate.css` | Paystack donations (`[lof_donate_form]`, hero, webhook, receipts, admin list, CSV). |
| `.../schema.php` | JSON-LD structured data (0.46.5). |
| `.../backup.php` | Light of Future > Media backup (0.46.5). |
| `scripts/build-zips.sh` | Builds the theme and plugin zips. |

## 5. Donations (Core)
- Paystack form on /donate/: once, monthly or yearly (yearly = Paystack `annually` plan); "Give anonymously" (name hidden, flagged in receipts, admin list, CSV and staff email). References start `LOF-` (old `SCC-` still accepted). Admin preview `/donate/?lof_pay_preview=1`.
- Tiers: KES 500 / 1,000 / 2,500 / 5,000 / 10,000; USD 10 / 25 / 50 / 100 / 250 / 500 / 1,000.
- **Default currency (0.46.5): KES**, listed first and preselected. Switch in Light of Future > Organisation details > **Default donation currency** (setting `default_currency`, KES or USD) or with the filter `lof_pay_default_currency`. Only order/preselection changes; payment logic, references, receipts and Paystack calls are untouched. The constant `LIGHT_PAYSTACK_CURRENCIES` (default now `KES,USD`) still controls which currencies are offered.
- Other ways to give box: M-PESA Paybill 522522, account 1354817087; Kenya Commercial Bank (KCB), account 1354817087 (settings `mpesa_paybill`, `mpesa_account`, `bank_name`, `bank_account`).

## 6. Structured data (Core 0.46.5, `includes/schema.php`)
One `<script type="application/ld+json" id="lof-schema">` in `wp_head` (priority 30):
- every front-end page: `NGO` + `Organization` (`@id` home/#organization): name, url, logo (Custom Logo, else theme `logo-horizontal-light.svg`), email and telephone from settings, PostalAddress (P.O. Box 53-00520, Ruai, along Kangundo Road, 00520, Nairobi, KE), foundingDate 2025-05-23, founder Person Francis Ouma Sikuku, sameAs Facebook (`social_facebook`); plus a `WebSite` node;
- /donate/: `potentialAction` `DonateAction` targeting the Donate URL (`donate_url` setting or /donate/);
- single stories: `Article` (headline, image, datePublished, dateModified, publisher = the NGO).
- Skipped entirely when Rank Math or Yoast is active. No Product node: products are WooCommerce and WooCommerce prints its own Product schema. Filters: `lof_core_schema_enabled`, `lof_core_schema_facts`, `lof_core_schema_graph`.

## 7. Media that lives only on the server (and how to back it up)
- **Gallery photos:** `wp-content/plugins/light-of-future-media/assets/photos/` (not in git).
- **Videos:** `wp-content/plugins/light-of-future-video-1` .. `-12` (`-8` = Margaret) and `wp-content/uploads/lof-videos/` (`story-{slug}.mp4` + `story-{slug}-poster.jpg`; Rosemary about 62 MB, Hellen about 28 MB). Posters are also in theme `assets/video/`.
- **Story player** (`inc/story-0613.php`, front end): uses post meta `lof_story_video` / `lof_story_video_poster`; otherwise `uploads/lof-videos/story-{slug}-web.mp4` if present, then `story-{slug}.mp4`; poster falls back to `story-{slug}-poster.jpg`. To add a video to a story, put the file there (or set the meta).
- **Rosemary web video (0.66.0):** upload a small `story-rosemary-web.mp4` through Media > Add New, then open any wp-admin page: `inc/upgrade-0660.php` sets `lof_story_video` on Rosemary's story (post 564) to that URL, keeps the poster, purges LiteSpeed and sets `lof_rosemary_web_0660`. Until the file exists it retries on every admin load.
- **Backup procedure (Core 0.46.5):** wp-admin > **Light of Future > Media backup** > **Build backup**. It writes to `wp-content/uploads/lof-backups/<random 20 chars>/`: one zip of the media plugin (photos), video zips of at most 45 MB each (a file over 45 MB goes alone), and `manifest.json` (every source path, size, sha1 and its zip; every zip name, size, sha1). Folders are protected by `index.html` + `.htaccess` "Require all denied". Large sets build in steps of about 200 s; the page continues automatically (keep it open). Then copy the links shown:
  - manifest: `https://lightoffuturefoundation.org/?lof_backup=TOKEN&manifest=1`
  - each zip: `https://lightoffuturefoundation.org/?lof_backup=TOKEN&file=ZIPNAME`
  The 32-character token expires after 48 hours. Verify downloads against the manifest sha1 values, store them off-server, then click **Revoke token / delete backups** (deletes the files and the token). Options: `lof_core_backup`, `lof_core_backup_job`. Restore: unzip at the WordPress root (entries start with `wp-content/`).

## 8. How to release and install
1. Edit. Append CSS to the end of `assets/css/shop.css` and the same rules to `shop.min.css` (and `critical/home-*.css` if Home first paint is affected). Keep quoted strings intact when minifying.
2. Bump `LOF_THEME_VERSION` (functions.php) + `Version:` (style.css); for Core, the plugin header + `LOF_CORE_VERSION`.
3. Prepend a `CHANGELOG.md` entry; update sections 1 and 9 here. Run `php -l` on changed PHP files where PHP is available (the build sandbox often has no PHP: then check braces/parens/semicolons by hand or script and say so).
4. Commit to `main`.
5. Build one zip per product in the **same chat session that uploads them** (`bash scripts/build-zips.sh`, or `zip -rq light-of-future-X.Y.Z.zip light-of-future`). Keep zips well under 40 MB; never put videos in the theme zip. Releases are kept in the Content Library (Light of Future Foundation/releases).
6. wp-admin: Plugins > Add New > Upload `light-of-future-core-X.zip` > **Replace current with uploaded**; Appearance > Themes > Add New > Upload `light-of-future-X.zip` > **Replace current with uploaded**.
7. **Open the Dashboard once** (runs the one-off upgrades on `admin_init`).
8. LiteSpeed Cache > Toolbox > **Purge All**. Sign out and check signed out on desktop and at 390px (`/?lof_mobile_preview=light&page=SLUG&w=390`; blank for single stories, use a narrow window).

**0.69.0 checks (signed out, 390px + 360px + desktop 1440px; also ~800px):** WhatsApp button visible on load at the top of every page and stays put while scrolling and after the teaser hides (no fade, no slide), sits just above the bottom bar on the right and covers no bottom-bar item; on tablets 20px from the corner; opens the chat panel; hidden while the menu drawer or an order form is open; teaser shows once on phones and hides after 6 s. Home Give today at 1440px: amount tiles hold their labels ("KES 5,000", "Other amount"), fund select reads "Where it is needed most" in full, step labels on one line, button full width; at 800px and 390px likewise ("Other amount" on its own row on phones). Story cards (Home, /stories/, /impact/) show exactly one arrow after "Read the story". Gallery: the builder's head in the Philister grid stays in frame at about 800px; phones show 4:5 tiles. Mobile pass: no sideways scroll on any page (Home, About, Leadership, Programmes + one programme, Projects, Talent, Impact, Stories + one story, Shop + one product, Gallery, Donate, Get Involved, Resources, Contact, FAQ, legal, 404); hero titles fit without oversized lines; menu drawer items large and the Donate button full width, last item clear of the bottom bar; form fields do not zoom on iPhone; footer links/icons easy to tap and the last footer row clear of the WhatsApp button; order modal scrolls inside; dark mode (`?lof_theme=dark`) still readable. Desktop otherwise unchanged.

**0.68.0 checks (/gallery/, desktop and 390px):** featured photos fill the mosaic with no empty cell; filter pills wrap in the white panel with no sideways scroll at 390px and the bar does not stick while scrolling; clicking a pill shows only that programme, All brings everything back; photo tiles 4 per row on desktop, 3 at about 800px, 2 at 390px, all the same 4:3 size, short rows centred; Show all N photos works; Elijah's two sensitive photos stay blurred until "Tap to view", then the button disappears and the photo opens in the lightbox; lightbox: Esc, arrows, Tab stays inside, close button shows a gold ring with the keyboard; video cards show a gold play button, play starts with native controls, a second video pauses the first; no horizontal scroll anywhere on the page.

**0.67.0 checks:** Home Give today card (desktop, 800px tablet, 390px): labels readable, chips in one row, button aligned; Shop with purpose trust cards still show icons and text; /programmes/ all 11 cards, no sideways scroll, focus-area links wrap; /donate/ form card, steps 1-3, KES first, other ways to give cards, a test click on amounts and the anonymous option. Spot-check About, Leadership, Stories, Shop, Gallery and a story page look unchanged after the CSS cleanup.

**0.66.0 / 0.46.5 checks:** Home goes slider > Shop with purpose with no figures card and no gap; inner-page figure strips still show; /donate/ shows KES first and preselected with KES tiers; view source on Home, /donate/ and a story for `lof-schema`; Light of Future > Media backup loads.

## 9. Pending (priority order, as of 14 Nov 2026)
1. **Google Business Profile:** get the office street address and opening hours from the owner, create the profile on lightofthefutureorg@gmail.com (nonprofit category, address shown on Maps), start verification, save the Maps link in Organisation details > Google Business Profile link.
2. **GA4 watcher routine (Town):** owner wants an unscheduled Town routine that checks GA4 G-NSPE2134S2 for `donate_start`, `generate_lead`, `contact_click` and messages the owner's "Light Future Foundation Improvement" thread when all three appear. Blocked: the Town Google Analytics integration does not show as connected to the assistant (checked 5 times on 14 Nov, last at 21:35 EAT after Town support said it was fixed) although the owner says it shows on the integrations page. Next step: owner to send Town support a screenshot of the Connected card and ask why the assistant's integration list still shows Google Analytics as not connected; then create the routine (no trigger; tools: the Google Analytics report tools + send_message_to_thread to thread bb122bae-2f58-5ef5-9815-747fbcf25eba).
4b. **Questionnaire for Francis:** drafted 14 Nov (covers item 4 and item 5); owner sends it. Record his answers in CONTENT-GAPS.md, then update pages via one-off upgrades. Key events are already created, so nothing in GA4 needs changing; the routine only notifies.
3. **Board approvals:** cookie notice wording (`lof_core_consent_text`) and a Privacy Policy that mentions analytics cookies; Privacy Policy and Terms of Use wording (placeholders live, noindexed, meta `_lof_legal_placeholder`); full text of specific objective (h); whether to publish the registration certificate / PBORA reference number.
4. **Questionnaire for Francis:** the NEEDS WORDING rows in `docs/AEO-PAGE-QA-PLAN.md` (operating areas, cost per child per term, house build cost/time, governance statement, tax-deductibility, consent line, volunteer roles).
5. **Figures:** Francis to re-confirm People mentored 6,000+, Girls given sanitary pads 4,500+, Families served with food 3,500+, Children given school materials 2,500+, Children's school fees paid 200, Homes built 30, next to "55 people supported since 2024" ("500,000 by 2040"). Kept out of structured data and llms.txt until confirmed.
6. **Paystack USD:** USD must be enabled on the foundation's Paystack account with a USD settlement account; confirm live keys and mode before saying card giving works. Owner to confirm KES as default currency (done in code).
7. **Outreach:** work through `docs/DIGITAL-PR-AND-BACKLINK-PLAN.md` starting with PBORA register check and directory listings; record outcomes in that file.
8. **Monthly:** run `docs/AI-VISIBILITY-CHECKLIST.md` on the first working day of each month (first run Dec 2026); mark the GA4 events only if they ever disappear from Key events.
9. **Site language:** WordPress Site Language is English (US) while copy is British English and og:locale is en_GB. Owner to confirm before switching to English (UK) (affects WooCommerce strings).
10. **Rosemary web video:** upload `story-rosemary-web.mp4` via Media, open the Dashboard, check her story plays.
11. **Media backup:** run Light of Future > Media backup, store zips off-server, revoke the token.
12. **Owner choices / content:** Leadership top photo (D/E/F); website links for Suivera and Grace International Foundation; talent-development photos.
13. Optional: shorter Facebook URL; Margaret crop on /stories/; page loader check.

## 10. One-off upgrades and their options
All run on `admin_init` (or `init` in the oldest ones) from files loaded only in wp-admin, each guarded by an option. Delete the option (e.g. `wp option delete NAME`) to run one again.

| File / function | Option | What |
|---|---|---|
| `importer.php` | `lof_hero_rev`, `lof_faq_rev`, `lof_talent_rev`, `lof_impact_rev`, `lof_legal_rev`, `lof_charity_rev`, `lof_setup_done`, `lof_design_version`, `lof_photo_ids`, `lof_leader_photo_set` | Early page upgrades (slider, FAQ, Talent, Impact, legal pages, charitable society line), setup state. |
| `shop-setup.php` | `lof_shop_rev`, `lof_market_rev`, `lof_shop_image_ids` | Shop products and home market. |
| `upgrade-045.php` | `lof_prog_photos_rev`, `lof_home_flow_rev`, `lof_hero_rev` | Focus-area photos, home flow. |
| `upgrade-051.php` | `lof_menu_shop_rev` | Shop in the main menu. |
| `upgrade-054.php` | `lof_email_rev` | info@ email everywhere. |
| `upgrade-055.php` | `lof_menu_gallery_rev`, `lof_admin_email_rev`, `lof_admin_email_report`, `lof_shrink_rev`, `lof_shrink_done` | Gallery menu, admin email, image compression. |
| `upgrade-0636.php` / `0637.php` | `lof_story_rosemary_rev`, `lof_stories_0637_rev` | Rosemary and Hellen stories with consent. |
| `upgrade-0638.php` | `lof_upgrade_0638_rev` | New figures, partners, giving details. |
| `upgrade-0640.php` | `lof_videos_0640_rev` | Story videos downloaded to uploads/lof-videos. |
| `upgrade-0641.php` | `lof_rosemary_0641_rev` | Rosemary portrait as main photo. |
| `upgrade-0642.php` | `lof_media_0642_rev`, `lof_media_0642_rev2` | Cleaned gallery photos copied over the media plugin. |
| `upgrade-0644.php` `lof_upgrade_0640_home_order` | `lof_home_0640_order` | Moves `[lof_home_market]` right after the slider; stores the outcome (`moved`, `already`, `not-found`, `no-home`, `no-data`); backup meta `_lof_layout_backup_0640`. |
| `upgrade-0660.php` `lof_upgrade_0660_rosemary_web` | `lof_rosemary_web_0660` | Rosemary's story uses the uploaded web-sized video (retries until found). |
| Core `settings.php` `lof_core_upgrade_0464_facebook` | `lof_core_up_0464_fb` | Fills the Facebook setting if empty. |
| Core `backup.php` (not an upgrade) | `lof_core_backup`, `lof_core_backup_job` | Media backup state and token. |

## 11. Traps
- Everything inside `if ( is_admin() )` in functions.php never runs on the front end; front-end filters go in an always-loaded file (this broke the video player in 0.63.10-0.63.11). `importer.php` is admin-only.
- `lof_core_settings` passes through `lof_core_sanitize_settings()`, which drops any key not in `lof_core_setting_fields()`: add a new setting to the fields AND the defaults. `lof_core_setting()` falls back to a default only when the key is absent; a saved empty value returns ''. Fill such values with a one-off upgrade.
- The browser automation cannot type long numeric strings into admin fields (treated as card numbers): set such values in code.
- Never nest `:has()` inside `:has()` in CSS (browsers drop the whole rule).
- Specificity: 0.64.0 figure rules use `html body .lof-section.lof-impact.lof-impact.lof-impact.lof-impact`, stories `.lof-section.lof-stories.lof-stories`; 0.65.0 Leadership/About grids use `html body.lof-page-leadership ...` with `!important`. Override with the same or higher specificity.
- (0.69.0) The WhatsApp button must stay visible: there is no `is-tucked` / `is-scrolling` logic any more (`shop.js`, `shop.css`, `critical/home-shop.css`). Story `.lof-card__link` arrows come from the `.lof-card__arrow` span only. Home Give today uses grid areas `thumb` / `lead` / `body` on `.lof-give__band`.
- (0.68.0) Gallery rules use `html body.lof-page-gallery .lofm-all ...` with `!important`; the photo grid selector is `... section.lofm .lofm__grid.lofm__grid.lofm__grid` (specificity 0,6,3) to beat the 0.60.3 `:has()` grid rule. Columns/gaps come from `--lofg-cols` / `--lofg-gap` on the body. Video play buttons (`.lofm__play`) are added by shop.js, which removes the native `controls` until play.
- (0.67.0) The old 0.64.0 span-hiding rule now only applies to non-v2 trust lists (`.lof-market__trust:not(.lof-market__trust--v2) span`); the 0.65.3 workaround is gone.
- Donate steps are numbered with a CSS counter on `fieldset > .lofpay__label` (scoped to `body.lof-page-donate .lof-pay2__form`); a hidden amounts fieldset does not count. If Core adds a fieldset with a legend, the numbers shift.
- Programmes grid on /programmes/ is flex (`.lof-grid--3:has(> .lof-card--programme)`, single-level :has) so the last row centres.
- Home Give today: the section is cream even if the Elementor widget tone is earth (forced in CSS).
- Home Give today is a compact variant (`lof-give--compact`, filter `lof_give_compact`); /donate/ is unaffected. Home figures card under the slider is hidden by `lof_home_hide_impact_strip` (default true).
- "Apply latest design" times out silently. Use one-off upgrades.
- **Never reinstall `light-of-future-media` from the repo** (no photos in git).
- **Zips must be built in the same chat session that uploads them** (sandbox files do not survive between sessions). Keep zips under about 40 MB.
- Hello Elementor gives buttons a pink focus colour: add our override to any new `<button>`.
- Impact figures count up from 0 when scrolled into view; PDF captures may freeze them mid-count. Not a bug.
- `sections.css` is long and layered by version: search for the latest block of a component before editing.
- Elementor needs jQuery; Migrate is removed on the front end only.
- Do not put `overflow` on html/body (breaks sticky header); overflow-x clip lives on main + footer.
- With Hello Elementor as parent, override both `header.php`/`footer.php` parts and the `dynamic-*` parts.
- Never round SVG transform numbers in the logo files.

## 12. Reference repos
moselanto/smilecommunitycentre.org (Elementor pattern, fonts), moselanto/cisternofhopefoundation (Hope Market after the hero, Leadership benchmark), dckabeten.org, soundcreationsltd.com, pimofyoriginaltheme, nurabeauty.co.ke. Agnes's mock-up `light_of_future_foundation_website.html` is ideas only (its stock photos and unconfirmed claims were deliberately not used). Other docs: `ARCHITECTURE-ESSENTIALS.md`, `PRD.md`, `CONTENT-GAPS.md`, `docs/brand.md`, `docs/legacy-content.md`.
