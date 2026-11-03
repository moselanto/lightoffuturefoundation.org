# HANDOVER - Light of Future Foundation website (read this first)

Last updated: **3 Nov 2026, 18:15 EAT**, at theme + Core **0.34.0**. Written so the next AI agent or developer can pick up without the chat history.

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
- Inner-page heroes without their own photo show a breadcrumb and an arch-framed photo chosen per page slug in `lof_page_hero_side_photo()` (filter `lof_page_hero_side_photos`). Setting a Photo on the hero widget replaces it.
- FAQ widget (Cards widget, style FAQ) renders a two-column layout: numbered questions plus a dark "Still have a question?" card (Call button uses the phone setting).

## 7. Pending (in priority order)
1. **Paystack:** keys go in `wp-config.php` as `LOF_PAYSTACK_PUBLIC_KEY` / `LOF_PAYSTACK_SECRET_KEY`; build donations (one-off + monthly), server-side verification, webhook signature check, receipts. Then switch the giving panel from the contact-form hand-off to Paystack checkout and update the "coming soon" wording.
2. **WooCommerce crafts shop** (Paystack for WooCommerce, KES, delivery/pickup) - needs the catalogue.
3. **Content from the foundation** (see `CONTENT-GAPS.md`): partner logos (partners section hidden until added), photos for each of the 11 focus areas (5 cards still show numbered tiles), public email, WhatsApp number (enables the WhatsApp float and footer icon), social links, a sharper photo of Philister, Kennedy's approval of his portrait, leads for Community Empowerment / GBV Awareness / Prison Outreach, full text of specific objective (h).
4. **Approved Privacy Policy and Terms** wording.
5. **Launch:** turn off "Discourage search engines", submit sitemap, run Lighthouse at https://pagespeed.web.dev/ (the API quota failed from the build environment), set up GA4 and a cookie notice if analytics is added.
6. Optional: remove the phone preview page (`lof_mobile_preview` in `inc/setup.php`) after launch, or keep it (noindex) for QA.

## 8. Known quirks
- `inc/importer.php` loads **only in wp-admin**. Anything the front end needs (hero photo, at-a-glance figures, programme icons) lives in `inc/template-tags.php`. One-off upgrades in importer.php therefore run when someone opens wp-admin.
- "Apply latest design" times out on this host (no notice). Use one-off upgrades.
- Hello Elementor gives buttons a pink focus colour; our CSS overrides it for header, bottom-bar and accordion buttons - add the same override to any new `<button>`.
- Impact figures animate from 0 when scrolled into view; full-page PDF captures can freeze them mid-count (looks like "71+" instead of "200+"). Not a bug.
- `sections.css` is long and layered by version; search for the latest block of a component before editing.
- Elementor needs jQuery; Migrate is removed on the front end only (re-add if an old plugin breaks).

## 9. Reference repos
moselanto/smilecommunitycentre.org (Elementor/Redux pattern, fonts), moselanto/cisternofhopefoundation, dckabeten.org, Agnes's mock-up `light_of_future_foundation_website.html` (ideas only - its stock photos and unconfirmed claims were deliberately not used).
