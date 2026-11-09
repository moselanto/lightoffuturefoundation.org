# HANDOVER - Light of Future Foundation website (read this first)

Last updated: **9 Nov 2026, 20:30 EAT**, at theme **0.63.13** + Core **0.46.4** (section 0). Sections 1-9 were written on 3 Nov at 0.43.1 and are kept for the code map and history. Where they disagree with section 0, section 0 wins.

## 0. STATUS AS OF 9 NOV 2026 (read this first; sections 1-9 below are the 3 Nov / 0.43.1 snapshot and partly outdated)

**Live versions:** theme `light-of-future` **0.63.15**, plugin `light-of-future-core` **0.46.4** (both installed on the live site and verified signed out on desktop and at 390px). Repo `moselanto/lightoffuturefoundation.org`, branch `main`. Every change is committed; `CHANGELOG.md` has one entry per release.

**The site is public and indexable.** The 3 Nov "keep noindex" rule is obsolete: theme 0.58 forces indexing on even if "Discourage search engines" gets ticked again. Do not turn noindex back on.

### 0.1 What changed since 0.43.1 (short version; details in CHANGELOG.md)
- **Shop** (0.44-0.57): crafts shop with WhatsApp ordering, shop page in menu and phone bottom bar, redesigned product pages.
- **Speed / security** (0.54-0.62): image compression, page cache, shop CSS only on shop pages; login lockout, HSTS, file editors off. Phone PageSpeed scores were 94-100.
- **Email:** the public address is info@lightoffuturefoundation.org everywhere.
- **Donations (Core 0.46.x):** Paystack form on /donate/. USD shown first ($10, $25, $50, $100, $250, $500, $1,000), KES second; once, monthly or yearly (yearly = Paystack `annually` plan); "Give anonymously" (hides the name, flagged in receipts, admin list, CSV and staff email). References start `LOF-` (old `SCC-` still accepted). Admin preview: `/donate/?lof_pay_preview=1`.
- **Other ways to give** box on /donate/ (theme 0.63.9 + Core 0.46.3): M-PESA Paybill **522522**, account **1354817087**; bank transfer **Kenya Commercial Bank (KCB)**, account **1354817087**. Editable in wp-admin under Light of Future > Settings (fields `mpesa_paybill`, `mpesa_account`, `bank_name`, `bank_account`; defaults live in `lof_core_setting_defaults()`).
- **Facebook** (Core 0.46.4): `social_facebook` = https://www.facebook.com/people/Light-of-future-foundation/100094073726098/ (shown as a footer icon on every page).
- **Impact figures** (0.63.8): now "People supported since 2024: 55" and "People we aim to support by 2040: 500,000". "Children supported 200+" and "Widows assisted 30+" are hidden (unpublished, not deleted). Still shown and **not yet re-confirmed** against the new 55 figure: People mentored 6,000+, Girls given sanitary pads 4,500+, Families served with food 3,500+, Children given school materials 2,500+, Children's school fees paid 200, Homes built for widows and widowers 30.
- **Impact figure layout** (0.63.13): one card grid on Home, About and Impact. 4 x 2 on desktop, 2 columns at 900px and below, one source note under the grid. The old 0.50 "one row" rule (shop.css block `0.50.0`) is overridden by selector `body .lof-section.lof-impact.lof-impact.lof-impact`. Do not go back to a single row: 8 figures overlap.
- **Partners** (0.63.8): Suivera and Grace International Foundation logos on Home ("Our partners"). No website links yet.
- **Stories:** 8 published stories, including Eunice, Margaret, Philister, Elijah, **Rosemary** (post 564) and **Hellen** (post 568), both added 8 Nov with topic "Business empowerment".
- **Story videos** (0.63.10-0.63.13): Rosemary's and Hellen's videos play on the page (no Google Drive links). The server downloaded the MP4s once into `wp-content/uploads/lof-videos/story-{slug}.mp4`, with posters `story-{slug}-poster.jpg` (posters also in `assets/video/`). The MP4s are **not in git** (Rosemary about 62 MB, Hellen about 28 MB). The player is `inc/story-0613.php` (front end): a `the_content` filter swaps a Drive button for a `<video>` using post meta `lof_story_video` / `lof_story_video_poster`, or finds the file by story slug. To add a video to another story, put `story-{slug}.mp4` (+ poster) in `uploads/lof-videos/`. Margaret's videos use the separate `light-of-future-video-8` plugin.
- **Story page layout** (0.63.12-0.63.13, CSS at the end of `assets/css/shop.css`): one centred column (820px), 16:9 top photo, 4:3 inline photos with centred captions, 2 x 2 gallery, full-width "Help us do more" band at the end. On the Impact page, "Real people, real change" is three equal cards.

### 0.2 Next steps (priority order)
1. DONE 0.63.14/0.63.15: one figures note everywhere; Rosemary's main photo is now her portrait (the stall photo itself cuts off her head; it is inline in the story instead). Still check the Margaret crop on /stories/.
2. **CONSENT CONFIRMED (9 Nov 2026, by the owner Ngimu):** consent is held for the pending stories and photos in the foundation's content intake list (Precious's photos, Eunice's sons, sanitary-pad girls, school-materials children, food-donation and mentorship photos, the borehole boy, talent-development performers). They can now be published. Still do not publish private records (hospital cards, clinical notes, blood-donor forms), keep Elijah's graphic wound images blurred (media plugin `!` prefix), cut children's spoken full names from videos, and keep Precious's location as "Nairobi" only. Next: publish these from the Content Library collections `projects/Light of Future Foundation/*` (Education, Sanitary Pads, Food Donations, Mentorship, Clean Water, Talent Development, Education Materials), using the intake document's suggested photo picks.
3. **Paystack USD:** a $10 test gave "We could not start the payment". USD must be enabled on the foundation's Paystack account, and a USD settlement bank account is needed (requirements PDF was sent to Francis on 6 Nov). Payment mode was reported as "Not set up" in settings on 8 Nov: confirm live keys and mode before telling anyone card giving works. **Open question to the owner:** should KES be the default currency until USD is approved?
4. **Figures:** ask Francis whether the six older figures listed above are still accurate next to "55 people supported since 2024".
5. **Leadership page top photo:** the owner disliked the sanitary-pads group photo on mobile; options D, E and F are awaiting a choice.
6. **Partners:** website links for Suivera and Grace International Foundation.
7. Consent: confirmed 9 Nov (see item 2).
8. Optional: a shorter Facebook URL if the foundation sets a username. Recheck the page loader, and confirm the first slide stays still before load.

### 0.3 How releases are done now
1. Edit. Append new CSS to the end of `assets/css/shop.css` (later blocks win), then regenerate `shop.min.css`. Keep quoted strings intact when minifying: an earlier minifier removed the space after a comma inside a `content:` string.
2. Bump `LOF_THEME_VERSION` (functions.php) and `Version:` (style.css). For the plugin, bump the header and `LOF_CORE_VERSION`.
3. Prepend a `CHANGELOG.md` entry. Run `php -l` on changed files.
4. Build one zip per product (`scripts/build-zips.sh`, or `zip -rq light-of-future-X.Y.Z.zip light-of-future`). Keep zips well under about 40 MB: large uploads failed. Never put videos in the theme zip.
5. wp-admin: upload zip > "Replace current with uploaded" > open the Dashboard once (this runs the one-off upgrades) > LiteSpeed Cache > Toolbox > Purge All > sign out > check signed out. Phone check: `/?lof_mobile_preview=light&page=SLUG&w=390` (blank for single stories: use a narrow window or the Stories preview and follow links).

### 0.4 Traps learned (add yours)
- Everything inside `if ( is_admin() )` in functions.php (upgrade-*.php, importer.php, shop-setup.php) **never runs on the front end**. Front-end filters belong in an always-loaded file. This is why the video player failed in 0.63.10-0.63.11.
- One-off upgrades (`inc/upgrade-06xx.php`) run on `admin_init`, guarded by an option (`lof_stories_0637_rev`, `lof_videos_0640_rev`, `lof_core_up_0464_fb` ...). Delete the option to re-run.
- `lof_core_settings` passes through `lof_core_sanitize_settings()`, which **drops any key not in `lof_core_setting_fields()`**. A new setting must be added to the fields list (and to defaults). `lof_core_setting()` only falls back to a default when the key is *absent*: an empty saved value returns ''. Fill it with a one-off upgrade (see `lof_core_upgrade_0464_facebook`).
- Stories without consent are forced to draft by `lof_core_require_story_consent`. Set `lof_consent=1` and `lof_consent_date` before publishing (see upgrade-0637.php).
- The browser automation cannot type long numeric strings (for example the Facebook profile ID) into admin fields, because it treats them as card numbers. Set such values in code.
- Never invent facts, figures, payment details or partner names. No new wording on the site without the foundation's approval. Francis Ouma Sikuku's personal Gmail is never published.

## 1. Where we are in one paragraph
The site is live (still `noindex`) at https://lightoffuturefoundation.org/ on WordPress + Elementor (free) + Hello Elementor parent theme, with our child theme `light-of-future` and plugin `light-of-future-core`. Design, content and mobile UX went through many rounds with Agnes (Pimofy Digital) between 30 Oct and 3 Nov 2026. Every page, the header, footer, phone bottom navigation and phone menu are finished to the current design standard. **Not done yet:** Paystack donations (no keys), WooCommerce crafts shop, real partner logos, focus-area photos, public email and WhatsApp number, approved legal wording, and launch (turning indexing on).

## 2. People and rules
- **Client:** Francis Ouma Sikuku (founder, also "Frank Omosh" on the proposal), +254 715 394 295. His personal email franksikuku3@gmail.com must **never** be published.
- **Developer / your user:** Agnes (Pimofy Digital). She reviews on a 13.3in laptop and a phone and sends full-page PDF captures.
- **Standing rules:** never invent facts, figures, quotes, prices, partners or payment details; consent-first stories; commit **directly to `main`** (no branches/PRs); bump versions every release; update `CHANGELOG.md` and the work log in `AGENTS.md`; no secrets in Git.
- **Brand:** Earth Brown #5C3D1E, Cream #FCF6E1, Sun Yellow #FBD62B, Sun Gold #F4AF16, Amber #DC8C13. **Website type (since 0.15.0): Nunito (headings, 800) + Figtree (text)**, same files as the Smile Community Centre theme. Cinzel lives only inside the logo artwork. See `docs/brand.md`.

## 3. Code map
| Path | What |
|---|---|
| `wp-content/themes/light-of-future/style.css`, `functions.php` | Version (`LOF_THEME_VERSION`). |
| `inc/setup.php` | Enqueues, menus (`lof-primary`, `lof-footer`, `lof-legal`), colour-mode script (`?lof_theme=light|dark` forces mode), font preload, **phone preview page** `/?lof_mobile_preview=light|dark` (390px iframes of every main page; `&page=slug` for one). |
| `inc/importer.php` | Setup screen (Appearance > Light of Future setup), page layout definitions (`lof_setup_page_defs()`), menus (`lof_primary_menu_tree()`), photo import (`lof_photo()`), and **one-off upgrades on `init`** (see section 5). |
| `inc/elementor/widgets-*.php` | All `lof-*` Elementor widgets (hero, slider, intro, cards, programmes, impact, stories, leaders, feature, donate/giving panel, form, CTA, gallery, partners...). |
| `inc/floating.php` | WhatsApp float (only when number set) and the **phone bottom navigation** (`lof_mobile_tabbar()`). |
| `inc/performance.php` | .htaccess compression/caching rules, meta description + theme colour, jQuery Migrate removed on the front end. |
| `inc/template-tags.php` | Settings helpers, social links, `lof_programme_lead()` (lead names per focus area from the foundation's document; override with custom field `lof_lead`). |
| `template-parts/header.php`, `footer.php`, `single.php` | Header, footer (CTA card, contact tiles, 4 columns, base bar with legal menu), single story/programme layout. |
| `assets/css/main.css` | Tokens, base, header. `assets/css/sections.css` | All section styles, appended in dated version blocks (`/* ===== 0.xx.x ... */`) - later blocks override earlier ones. |
| `assets/js/main.js` | Theme switch, header height var, home slider (`initCine`), leadership tabs, FAQ accordion, giving panel, phone menu accordion, bottom-bar Menu. |
| `wp-content/plugins/light-of-future-core/` | Post types (programmes, stories, leaders, partners, metrics), seed content (`includes/seed.php`, rev + fingerprint so edited items are never overwritten), settings, enquiry forms (`includes/forms.php`, giving pre-fill `lof_core_give_prefill()`). |
| `scripts/build-zips.sh` | Builds `dist/light-of-future.zip` and `dist/light-of-future-core.zip`. |

## 4. How to release (what we do every time)
1. Edit; bump `Version:` in theme `style.css`, `'0.xx.x'` in `functions.php`, plugin header `Version:` and `LOF_CORE_VERSION`.
2. `bash scripts/build-zips.sh` (no PHP in the build sandbox: check brackets by hand; lint on the server/CI when possible).
3. Add a `CHANGELOG.md` entry and a line to the `AGENTS.md` work log. Commit straight to `main`.
4. WordPress admin: Plugins > Add New > Upload `light-of-future-core.zip` > **Replace current with uploaded**; Appearance > Themes > Add New > Upload `light-of-future.zip` > **Replace current with uploaded**.
5. **Do not rely on "Apply latest design"** (Appearance > Light of Future setup): since 31 Oct it times out silently on this host. Page-structure changes are shipped as **one-off upgrades on `init`** instead (section 5). Style/markup changes need only the uploads.
6. Check signed out on desktop light/dark and with the phone preview page. The browser automation used so far cannot resize the window, hence the preview page.

## 5. One-off upgrades (run once on `init`, guarded by options)
| Function | Option | What it does |
|---|---|---|
| `lof_upgrade_home_hero()` | `lof_hero_rev` = 2 | Old hero: registration pill + figures row (superseded). |
| `lof_upgrade_home_slider()` | `lof_hero_rev` = 3 | Replaces the home hero with the full-width slider (`lof-slider`); backs up the old layout in `_lof_layout_backup`. |
| `lof_upgrade_faq_more()` | `lof_faq_rev` = 1 | FAQ: hero intro line, section heading and questions 6-10 (only if the page still has the original 5). Backs up the layout in `_lof_layout_backup`. |
| `lof_upgrade_talent_page()` | `lof_talent_rev` = 1 | Talent Development: inserts the "From discovery to the stage" steps section before the call to action. |
| `lof_upgrade_impact_page()` | `lof_impact_rev` = 1 | Impact: hero intro line, "From relief to self-reliance" steps and six focus areas before the call to action. |
| `lof_upgrade_legal_pages()` | `lof_legal_rev` = 1 | Creates **Privacy Policy** and **Terms of Use** with placeholder wording (meta `_lof_legal_placeholder`) and the "Footer legal links" menu in the `lof-legal` location. Never overwrites a published page. |
To re-run one, delete its option (e.g. with WP-CLI `wp option delete lof_legal_rev`).

## 6. What each page looks like now (0.22.0)
- **Header:** logo, menu Home / About (About us, Leadership & Governance, FAQ) / Programmes (Programmes, Talent development) / Impact (Our impact, Stories) / Get Involved (Get involved, Donate) / Contact, Donate button, dark-mode switch. Leadership has NO tabs (0.31.0): every group is visible, with a sticky "Jump to" bar of plain buttons - keep it simple for local visitors. Phone: full-height menu with **accordion sub-menus** (round chevrons, one group open), plus a **fixed bottom navigation** (Home, Focus areas, raised gold Donate, Stories, Menu).
- **Home:** full-width 3-slide slider (Elijah / Philister / Precious, own photos, confirmed copy, progress bars, pause, swipe; fits laptop screens) -> figures card (200+, 30+, 11, 2025) -> Why we exist + Vision/Mission icon cards -> Our approach (4 numbered steps) -> Our story (founder letter: arch portrait, name card, drop cap, quote card, timeline) -> Focus areas (6 cards with leads) -> Impact figures (glass cards) -> Stories of change (cinematic lead + 2 side cards) -> Give today (single card: photo with trust points + "Make your gift" form) -> Who we are (photo with registration badge) -> closing gold CTA card -> footer.
- **Inner pages:** cream page hero with gold rule (all pages), Programmes (11 focus areas), Stories (cinematic featured story + cards), Donate (giving card + form; giving panel pre-fills the contact form while Paystack is pending), Leadership (Board / Programme leads tabs, 11 portraits), FAQ (accordion), Contact, Get Involved, Impact, About.
- **Single story / programme:** hero, framed image, quote card, sticky aside with facts and a gold Donate card.
- **Footer:** gold top edge; gold CTA card (not on Home/Donate); Call / Email / Visit tiles; brand + badge, Explore (2 columns), Focus areas, Support our work; base bar with copyright + registration line, Privacy Policy / Terms of Use, Back to top. The big outlined wordmark was removed (3 Nov).
- **Legal pages:** plain WordPress pages with placeholder text and a visible "Placeholder" note. Replace with board-approved wording, then delete the note paragraph.

## 6b. Page-level styling (0.23.0)
- Body class `lof-page-{slug}` (e.g. `lof-page-about-us`, `lof-page-leadership`, `lof-page-faq`) scopes page-specific design in `sections.css` (block `0.23.0`).
- Any `lof-grid` with exactly 7 cards lays out 4 + 3 with the last row centred.
- The footer CTA card hides automatically when the page already has its own `lof-cta` section (CSS `:has`).
- Inner-page heroes without their own photo show a breadcrumb and a full-width background photo (0.37.0) chosen per page slug in `lof_page_hero_side_photo()` (filter `lof_page_hero_side_photos`). Setting a Photo on the hero widget replaces it.
- FAQ widget (Cards widget, style FAQ) renders a two-column layout: numbered questions plus a dark "Still have a question?" card (Call button uses the phone setting).

## 7. Pending (in priority order)
1. **Paystack:** keys go in `wp-config.php` as `LOF_PAYSTACK_PUBLIC_KEY` / `LOF_PAYSTACK_SECRET_KEY`; build donations (one-off + monthly), server-side verification, webhook signature check, receipts. Then switch the giving panel from the contact-form hand-off to Paystack checkout and update the "coming soon" wording.
2. **WooCommerce crafts shop** (Paystack for WooCommerce, KES, delivery/pickup) - needs the catalogue.
3. **Content from the foundation** (see `CONTENT-GAPS.md`): partner logos (partners section hidden until added), photos for each of the 11 focus areas (5 cards still show numbered tiles), public email, WhatsApp number (enables the WhatsApp float and footer icon), social links, a sharper photo of Philister, Kennedy's approval of his portrait, leads for Community Empowerment / GBV Awareness / Prison Outreach, full text of specific objective (h).
4. **Approved Privacy Policy and Terms** wording.
5. **Launch:** turn off "Discourage search engines", submit sitemap, run Lighthouse at https://pagespeed.web.dev/ (the API quota failed from the build environment), set up GA4 and a cookie notice if analytics is added.
6. Optional: remove the phone preview page (`lof_mobile_preview` in `inc/setup.php`) after launch, or keep it (noindex) for QA.

## 7b. Colour rules (0.41.0)
- Sections: cream or white (sand #FAF1DC sparingly).
- Dark brown only for: footer, figures band, photo page banners, donation panel in the gold CTA, home slider.
- Gold only for accents: labels (#B9740F on light), lines, icons, main buttons. Do not add new dark blocks.

## 7c. Breakpoints (0.43.0)
- Phone <= 640px, portrait tablet 641-991px, desktop >= 992px (10in tablets landscape and up). Root font 17px below 900px, 17.5px from 900px on every larger screen; content width 1200px. Keep new rules on these breakpoints.

## 8. Known quirks
- Never nest `:has()` inside another `:has()` in CSS: browsers drop the whole rule (this hid the Donate trust text in 0.29-0.34).
- `inc/importer.php` loads **only in wp-admin**. Anything the front end needs (hero photo, at-a-glance figures, programme icons) lives in `inc/template-tags.php`. One-off upgrades in importer.php therefore run when someone opens wp-admin.
- "Apply latest design" times out on this host (no notice). Use one-off upgrades.
- Hello Elementor gives buttons a pink focus colour; our CSS overrides it for header, bottom-bar and accordion buttons - add the same override to any new `<button>`.
- Impact figures animate from 0 when scrolled into view; full-page PDF captures can freeze them mid-count (looks like "71+" instead of "200+"). Not a bug.
- `sections.css` is long and layered by version; search for the latest block of a component before editing.
- Elementor needs jQuery; Migrate is removed on the front end only (re-add if an old plugin breaks).

## 9. Reference repos
moselanto/smilecommunitycentre.org (Elementor/Redux pattern, fonts), moselanto/cisternofhopefoundation, dckabeten.org, Agnes's mock-up `light_of_future_foundation_website.html` (ideas only - its stock photos and unconfirmed claims were deliberately not used).
