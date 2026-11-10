# HANDOVER - Light of Future Foundation website (single source of truth)

**Status as of 10 Nov 2026, theme `light-of-future` 0.66.0 + plugin `light-of-future-core` 0.46.5 (both on `main`).** This is the one current document. History is in `CHANGELOG.md` (one entry per release), not here. When you release, update section 1 ("Status") and section 9 ("Pending") and prepend a CHANGELOG entry.

## 1. Status
- **On `main`, to be installed:** theme 0.66.0 (includes 0.64.0-0.65.3) and Core 0.46.5.
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
| `assets/css/shop.css` (+ `shop.min.css`) | Loads on every front-end page; new CSS is appended here in version blocks (later blocks win). `assets/css/critical/home-*.css` = Home first-paint CSS. `sections.css` = older layered blocks. |
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

**0.66.0 / 0.46.5 checks:** Home goes slider > Shop with purpose with no figures card and no gap; inner-page figure strips still show; /donate/ shows KES first and preselected with KES tiers; view source on Home, /donate/ and a story for `lof-schema`; Light of Future > Media backup loads.

## 9. Pending (priority order)
1. **Install 0.66.0 + Core 0.46.5** (if 0.64-0.65 were not installed yet, this covers them; the Dashboard visit runs `lof_upgrade_0640_home_order`).
2. **Owner to confirm KES as the default donation currency** (done in code, pending confirmation).
3. **Paystack USD:** a $10 test gave "We could not start the payment". USD must be enabled on the foundation's Paystack account with a USD settlement bank account (requirements PDF sent to Francis on 6 Nov). Payment mode was reported "Not set up" on 8 Nov: confirm live keys and mode before telling anyone card giving works.
4. **Rosemary web video:** upload `story-rosemary-web.mp4` via the Media Library, open the Dashboard, check her story plays.
5. **Media backup:** run it once after installing, download and store the zips off-server, revoke.
6. **Figures:** Francis to re-confirm People mentored 6,000+, Girls given sanitary pads 4,500+, Families served with food 3,500+, Children given school materials 2,500+, Children's school fees paid 200, Homes built for widows and widowers 30 next to "55 people supported since 2024" (shown with "500,000 by 2040"; 200+ children and 30+ widows are hidden, not deleted).
7. **Leadership top photo:** owner to choose option D, E or F.
8. **Partners:** website links for Suivera and Grace International Foundation.
9. **Talent-development photos** (the talent gallery has videos only). Publish remaining consented intake photos from the Content Library `projects/Light of Future Foundation/*` collections.
10. **Board-approved Privacy Policy and Terms of Use** (placeholders live, meta `_lof_legal_placeholder`); full text of specific objective (h).
11. Optional: shorter Facebook URL if the foundation sets a username; check the Margaret crop on /stories/; recheck the page loader and that the first slide stays still before load; GA4 and cookie notice if analytics is added.

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
- **The 0.64.0 block hides every `span` inside `.lof-market__trust`** (`display:none!important`); 0.65.3 re-shows the 0.65.2 icon/text spans. Any new span there needs a higher-specificity rule.
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
