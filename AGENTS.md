# AGENTS.md: start here (for the next AI agent or developer)

**Start with `docs/HANDOVER.md` (full status, code map, release steps, pending work, as of 0.22.0).** Then read this file, `ARCHITECTURE-ESSENTIALS.md`, `PRD.md` and `CONTENT-GAPS.md` before changing anything. Append to the work log at the bottom when you finish.

## The project

- **Client:** Light of Future Foundation, Nairobi, Kenya. Founder: Francis Ouma Sikuku. Registered with the NGO Board in May 2025 (per the previous website).
- **Built by:** Pimofy Digital (Moses Munyi). Agreed budget KES 35,000, 2-3 week delivery (from the proposal).
- **New domain (ours):** https://lightoffuturefoundation.org/
- **Legacy site (read-only content source):** https://lightoffuture.org/ - no access to its hosting or developer. Content captured in `docs/legacy-content.md`.
- **Official name:** Light of Future Foundation. **Client contact:** Frank Omosh (+254 715 394 295), who is founder Francis Ouma Sikuku (same person; use the full name on the site).
- **Hosting plan:** the site is built directly on the live domain (no staging). Keep "Discourage search engines" on until launch.
- **Brief:** "Light of the Future WordPress Theme Design Prompt" (premium WordPress + Elementor theme, companion core plugin, WooCommerce crafts shop, donations). Summary in `PRD.md`.
- **Brand:** Logo Kit v1.0 (October 2026) from the client's Google Drive folder. Colours and fonts in `docs/brand.md`.
- **Reference repos:** moselanto/smilecommunitycentre.org (primary pattern for Elementor-first editing), dckabeten.org, cisternofhopefoundation, soundcreationsltd.com, pimofyoriginaltheme, nurabeauty.co.ke.

## Standing rules

- **Never invent facts.** No made-up statistics, names, quotes, prices, registration numbers, partners or payment details. Missing facts become clearly labelled editable placeholders and go in `CONTENT-GAPS.md`.
- **Safeguarding.** No identifying details of children or vulnerable people without written consent (guardian consent for minors). The Core plugin keeps impact stories in draft until consent is ticked.
- **Impact figures** render only when marked verified, with a source and a last-verified date. The four legacy figures were confirmed true by the foundation (30 Oct 2026).
- **Payments: Paystack** (M-PESA and card). Payment mode stays "Not set up" until the foundation's Paystack account is verified and tested in test mode. Keys only as `LOF_PAYSTACK_PUBLIC_KEY` / `LOF_PAYSTACK_SECRET_KEY` in wp-config.php. Never fake payment success. No secrets in Git (use `wp-config.php` constants).
- **Theme = presentation, plugin = data.** Don't put content models or business logic in the theme.
- **Commit directly to `main`** (Agnes, 30 Oct 2026: always). No feature branches or PRs.
- Release flow: edit, bump version in `style.css` + `functions.php` (and the plugin header + `LOF_CORE_VERSION` if it changed), `bash scripts/build-zips.sh`, add a `CHANGELOG.md` entry, commit to `main`, upload zips.
- Wrap every visitor-facing string in `__()` / `esc_html__()` with text domain `light-of-future` (theme) or `light-of-future-core` (plugin).

## Current state (3 Nov 2026, 18:15 EAT)

- Theme **0.43.1** and Core **0.43.1** on `main`. All pages, footer, phone menu and phone bottom navigation finished to the current design. Pending: Paystack, shop, foundation content, approved legal wording, launch (see `docs/HANDOVER.md` section 7).
- **Do not use "Apply latest design"** (times out on this host); ship page-structure changes as one-off `init` upgrades (`docs/HANDOVER.md` section 5). PHP lint not run here (no PHP in this environment); lint on the server or CI before relying on it.

## How to install / update

1. Elementor (free) active. 2. Upload `light-of-future-core.zip`, activate. 3. Upload `light-of-future.zip` (Hello Elementor installs automatically), activate. 4. **Appearance > Light of Future setup > Run setup**: creates the pages with Elementor layouts, menus, home page, and the starter programmes, stories, leaders and impact figures. Re-running never changes or duplicates existing items. To rebuild one page's layout, delete that page and run setup again.

## Next steps (phase 3) - superseded by docs/HANDOVER.md section 7

1. Paystack donations (one-off + monthly via Paystack plans), server-side verification and webhook signature check, receipts; separate from shop orders.
2. WooCommerce styling + Paystack for WooCommerce; KES, delivery/pickup.
3. Secure enquiry forms (contact, volunteer, partner, sponsor) with nonce, signed time, honeypot, rate limit, private storage.
4. Gallery widget (consent-aware lightbox), reports widget, WhatsApp button.
5. Elementor Pro header/footer templates (optional), SEO/schema, cookie banner, GA4, legal page drafts.
6. GitHub Actions PHP lint; EDITING-GUIDE, INSTALL-AND-DEPLOY, SECURITY-AUDIT, QA checklist.

## Work log

- **5 Nov 2026, 03:00 EAT:** 0.44.0: crafts shop (see CHANGELOG): WhatsApp chat card, Order on WhatsApp, home Shop with purpose, 8 products seeded by one-off upgrades in `inc/shop-setup.php` (run on first wp-admin load). WhatsApp number +254 715 394 295.

- **4 Nov 2026, 08:30 EAT:** 0.43.1: home stacking fix (overflow-x clip moved off html/body to main + footer). Audit (axe-core + Chromium, Home/Programmes/Stories/Donate, light+dark, 1366 desktop + 390 phone): fixed light-mode eyebrow/figure/jump-number contrast, dark-mode logo link name (aria-label), top bar now a labelled region. Perf: LCP 0.3-0.7s, CLS ~0, no sideways scroll. Do not put overflow on html/body again.

- **4 Nov 2026, 00:10 EAT:** 0.43.0: (1) content audit against the foundation's content document - all text, 11 leaders + photos, values, objectives, focus areas present; added "registered as a charitable society" (one-off `lof_upgrade_charitable_society`, option `lof_charity_rev`). (2) Responsive: desktop breakpoint moved 1025px -> 992px (all CSS + main.js), no font step at 1440px, fixed 17.5px root from 900px and 1200px content width so 13.3in = MacBook = large monitors; compact header 992-1180px; mobile safety (no sideways scroll, wrapping, full-width buttons); portrait tablets two-column grids.

- **4 Nov 2026, 00:00 EAT:** 0.42.0: page heroes branded dark everywhere - photo heroes get a warm brand-brown overlay with gold glow; heroes without a photo get the same dark brown band with gold glows and gold bottom line, so every inner page header matches (Cistern style).

- **4 Nov 2026, 00:00 EAT:** 0.41.0: calmer brand colours. Dark brown kept only for footer, figures band, photo banners and donation panel; FAQ help card, "What happens next", Donate trust panel, About registration band and Contact band turned into light cards with a gold top edge; softer sand; one eyebrow colour (#B9740F); flatter gold CTA.

- **3 Nov 2026, 23:55 EAT:** 0.40.2: cross-check of 0.40.0/0.40.1 - whole sections.css parses with 0 errors (tinycss2), no nested :has(), braces balanced; Our story photo panel given a 520px minimum height on laptops. Note: the "Our story" card style also applies to the founder section on About (same widget, `.lof-feature--story`).

- **3 Nov 2026, 23:55 EAT:** 0.40.1: home "Our impact" band compacted (heading and intro on one line, smaller figure cards, less padding) - about half the previous height.

- **3 Nov 2026, 23:55 EAT:** 0.40.0: home "Our story" redone as one editorial card (CSS block `0.40.0`, selector `section.lof-feature--story`): full-height portrait panel left with name overlay, letter/quote/timeline/button right; arch frame, offset gold outline and separate timeline box removed.

- **3 Nov 2026, 23:50 EAT:** 0.39.1: theme `screenshot.png` (1200x900) replaced with a real capture of the live home page (top bar, menu, slider, figures card) so Appearance > Themes shows the actual site.

- **3 Nov 2026, 23:55 EAT:** 0.39.0: home page balance pass (body.home scope): consistent section spacing, Why we exist aligned with equal vision/mission cards, Stories lead + two side cards at equal height with readable side cards, Give today heading sized, focus-area cards equal height, rounded centre buttons.

- **3 Nov 2026, 23:50 EAT:** 0.38.0: home "Our story" (`.lof-feature--story`) rebalanced with grid areas (`.lof-feature__body` display: contents): letter left, portrait right, quote and milestone timeline card side by side below.

- **3 Nov 2026, 23:45 EAT:** 0.37.1: footer main row back to ONE row of four columns (brand | Explore | Focus areas | Support card) at Agnes's request; support card stacked vertically with a one-line button. Do not stack the support card under the brand again.

- **3 Nov 2026, 23:40 EAT:** 0.37.0: inner page heroes use the page photo as a full-width background (`.lof-hero--page.has-bg`, `.lof-hero__bg`) with a dark left-to-right overlay, white title, gold bottom line and shorter height (280-400px desktop, 240px phone). Leadership gets a background photo with the At a glance card on top.

- **3 Nov 2026, 23:45 EAT:** 0.36.0: closing CTA ("Be the light in someone's future") right side becomes a dark donation panel (label, large gold Donate pill with heart, outline Get involved, registration trust line); footer CTA buttons match. Includes 0.35.1 footer balance.

- **3 Nov 2026, 23:40 EAT:** 0.35.1: footer balance - grid areas (brand with support card under it | Explore | Focus areas), long focus-area names wrap (were overflowing into the support card), larger logo, compact support card, wider address cell.

- **3 Nov 2026, 23:30 EAT:** 0.35.0: site-wide larger type (benchmark: cisternofhopefoundation.org). Root size 17px phones / 17.5px laptops / 18px large screens; body copy min 16px; labels min 13px; bigger section and hero titles; larger menu, buttons and footer links.

- **3 Nov 2026, 23:25 EAT:** 0.34.1: FIX Donate "Transparent and accountable" text invisible (nested `:has()` is not supported, so the dark section background never applied). Dark panel now set on the grid itself. Lesson: never nest `:has()` inside `:has()`.

- **3 Nov 2026, 23:30 EAT:** 0.34.0: About objectives redesigned as one white two-column panel (centred heading, gold number tiles, 8th cell = gold NGO registration note via `::after`), registration band labels forced visible (gold) on the dark band.

- **3 Nov 2026, 23:25 EAT:** 0.33.0: footer redesign (CSS block `0.33.0`): contact strip as one joined bar, four balanced columns (brand, Explore in two columns, Focus areas, Support card), gold-underlined headings, base bar on one line; tablet two columns, phone link lists side by side.

- **3 Nov 2026, 23:20 EAT:** 0.32.0: form sections balanced - heading and contact rows beside the form at equal height (grid areas, intro `display: contents`), "What happens next" moved to a full-width horizontal three-step band below.

- **3 Nov 2026, 23:15 EAT:** 0.31.0: Leadership tabs removed at Agnes's request (locals found switching confusing). All groups show on one page; `initLeaderTabs()` in main.js now builds a sticky "Jump to:" bar (Our founder, Board members, Programme leads, How we are run). About values text larger.

- **3 Nov 2026, 23:15 EAT:** 0.30.0: programme photo placeholders forced to the brown/gold icon tile (an old 0.15 rule kept them pale), "jump to a focus area" chips on Programmes (anchors `#lof-prog-{slug}`), even grid gaps, third approach icon, Impact figures band glow, Stories featured height, Talent widths.

- **3 Nov 2026, 23:05 EAT:** 0.29.0: forms get a "What happens next" 3-step timeline under the contact details and a gold pill submit button; Contact details shown once (dark band with Get directions link, Google Maps search); Donate trust section as a dark band and the repeated figures strip hidden.

- **3 Nov 2026, 23:10 EAT:** 0.28.0: Get Involved, Donate, Contact - icon cards for ways to help / where your gift goes / transparency, numbered ways-to-give cards, contact details as icon rows beside every form, Contact "Other ways to reach us" as icon tiles, Donate hero photo, fix for portrait hero photos stretching (Elementor `img{height:auto}`).

- **3 Nov 2026, 23:00 EAT:** 0.27.0: Stories - "More stories of change" divider and equal cards after the featured story (`.lof-stories-more`), consent cards with icons. Impact - hero intro, "From relief to self-reliance" steps and six focus areas added by one-off `lof_upgrade_impact_page` (option `lof_impact_rev`).

- **3 Nov 2026, 22:55 EAT:** 0.26.0: FIX - hero side photo and Leadership "At a glance" helpers moved from admin-only `inc/importer.php` to `inc/template-tags.php` (they never rendered on the front end in 0.24/0.25). Programmes: icon tiles for focus areas without photos (`lof_programme_icon()`), 11 cards balanced with last row centred, equal-height cards. Talent Development: icon cards and a new "From discovery to the stage" section (one-off `lof_upgrade_talent_page`, option `lof_talent_rev`).

- **3 Nov 2026, 22:40 EAT:** 0.25.0: Leadership & Governance redesign (benchmark: Cistern of Hope's page). Hero "At a glance" card counted from approved leaders (`lof_page_hero_facts()`), executive spotlight card, board as three equal portrait cards, board role pills, phone two-column programme leads.

- **3 Nov 2026, 22:10 EAT:** 0.24.0: inner-page heroes get a breadcrumb and an arch-framed photo (`lof_page_hero_side_photo()`, foundation's own consented photos); About objectives as an editorial two-column list; FAQ gets a heading, hero intro and 5 more answers from published facts (one-off `lof_upgrade_faq_more`, option `lof_faq_rev`).

- **3 Nov 2026, 21:30 EAT:** 0.23.0: About, Leadership and FAQ professional pass (balanced 7-card grids, numbered values, objective tiles, fact-sheet band, profile cards, governance panel, segmented tabs, two-column FAQ with help card, one closing CTA per page). Page slug body class `lof-page-{slug}`.

- **3 Nov 2026, 18:15 EAT:** 0.22.0: Privacy Policy + Terms of Use placeholder pages and footer legal menu (one-off upgrade), inner pages (page hero, Stories featured card, single story/programme, forms, legal pages) to the home standard, audit fixes (meta description, theme colour, jQuery Migrate off, focus rings, link underline, target sizes), full `docs/HANDOVER.md`.

- **3 Nov 2026, 17:50 EAT:** 0.21.0: footer wordmark removed and base bar refined; landing intro, impact and closing CTA polish.

- **3 Nov 2026, 17:40 EAT:** 0.20.0: accordion mobile sub-menus (with 0.19.2 margins).

- **3 Nov 2026, 17:30 EAT:** 0.19.2: phone side margins 16px, tighter phone paddings.

- **3 Nov 2026, 17:20 EAT:** 0.19.1: bottom-bar Menu focus colour.

- **3 Nov 2026, 17:05 EAT:** 0.19.0: Give today card, Who we are editorial layout, phone bottom navigation.

- **3 Nov 2026, 16:45 EAT:** 0.18.2: lead-story text anchored on dark gradient.

- **3 Nov 2026, 16:35 EAT:** 0.18.1: phone giving layout, name-card contrast, lead-story contrast, heading breaks.

- **3 Nov 2026, 16:20 EAT:** 0.18.0: Our story, Stories of change, Who we are, Give today creative pass.

- **3 Nov 2026, 15:50 EAT:** 0.17.0: focus areas, stories, who we are, give today, approach steps redesigned.

- **3 Nov 2026, 15:30 EAT:** 0.16.3: slide 3 text/photo gap.

- **3 Nov 2026, 15:35 EAT:** 0.16.2: phone slider photo-above/text-below, slide 3 spacing.

- **3 Nov 2026, 15:20 EAT:** 0.16.1: slider control clearance, portrait-photo layout, phone slider layout.

- **3 Nov 2026, 15:00 EAT:** 0.16.0: full-width home slider with figures bar, lighter focus-area tiles.

- **3 Nov 2026, 14:30 EAT:** 0.15.0: Nunito + Figtree website fonts (as Smile Community Centre), giving note consistency, phone dropdown and Donate-bar spacing.

- **3 Nov 2026, 14:20 EAT:** 0.14.0: Leadership tabs, FAQ accordion, giving panel with amount and fund choice (contact form pre-fill).

- **31 Oct 2026, 23:45 EAT:** 0.13.1: one-off home hero upgrade (no Apply needed), sturdier Apply latest design handler.

- **31 Oct 2026, 23:30 EAT:** 0.13.0: hero figures row and registration pill, programme lead lines and number tiles, button focus colour.

- **31 Oct 2026, 08:30 EAT:** 0.12.2: phone menu panel, giving block, contact email, footer tiles and wordmark.

- **31 Oct 2026, 01:15 EAT:** 0.12.1: hero margin fix, refined footer with contact tiles.

- **31 Oct 2026, 01:00 EAT:** 0.12.0: viewport-fit hero slider, new footer, phone polish.

- **31 Oct 2026, 00:35 EAT:** 0.11.1: "Our impact" menu label; phone-width preview page for mobile checks.

- **30 Oct 2026, 23:20 EAT:** 0.11.0: 11 focus areas, Cistern-style menu, portrait fix for new leads, centred people grid.

- **30 Oct 2026, 23:00 EAT:** 0.10.0: 11 uniform portraits, 6 new programme leads, Cistern-balanced home without leaders.

- **30 Oct 2026, 22:40 EAT:** 0.9.0: inner pages get cream page heroes, compact trust strips and intro statements (About, Programmes).

- **30 Oct 2026, 22:15 EAT:** 0.8.0: prototype-inspired refinement (arched hero, stamp, intro statement widget, trust strip, crisper buttons/cards, split CTA). Colours unchanged.

- **30 Oct 2026, 20:45 EAT:** 0.7.0: leadership page in Cistern-style groups (set lof_group = executive/board/team on each leader), governance steps, accountability, home purpose + team strip, milestones, Edith Atieno.

- **30 Oct 2026, 20:35 EAT:** 0.6.1 hotfix (theme switch markup; logo SVGs restored - never round SVG transform numbers) + Board of Directors from the foundation.

- **30 Oct 2026, 20:30 EAT:** 0.6.0: inner pages polished; accessibility/speed audit + fixes (docs/ACCESSIBILITY-AND-SPEED-AUDIT.md). PageSpeed API quota and headless Chrome were unavailable, so the audit was static + contrast maths; re-run Lighthouse at pagespeed.web.dev for scores. Apply latest design now also writes compression/caching rules to .htaccess.

- **30 Oct 2026, 20:05 EAT:** 0.5.0: light/dark mode (tokens in main.css :root and html[data-theme=dark]; no-flash script in inc/setup.php), header with dropdowns + theme switch, menu rebuild on Apply latest design, hero polish, Frederick Otieno added. When adding colours, use the --lof-bg/surface/text/heading tokens, never raw cream/white for surfaces, so dark mode keeps working.

- **30 Oct 2026, 19:55 EAT:** 0.4.0: foundation's content document applied (see CHANGELOG, CONTENT-GAPS 'To confirm'). Seeder revisions: bump an item's revision in seed.php to push new client copy; items edited in WordPress are skipped.

- **30 Oct 2026, 19:40 EAT:** 0.3.1: header/footer logos were missing live because Hello Elementor 3.x loads dynamic-header/footer.php; overridden in the child theme. Lesson: when Hello Elementor is the parent, override both `header.php`/`footer.php` parts and the `dynamic-*` parts.

- **30 Oct 2026, 19:30 EAT:** 0.3.0 on `main` (see CHANGELOG): real photos, hero collage, giving band, gallery, ribbon, secure enquiry form, count-up, WhatsApp/mobile donate, Apply latest design. Site is live on lightoffuturefoundation.org with Elementor 4.3.5, Hello Elementor 3.5.1. After uploading zips click Appearance > Light of Future setup > Apply latest design.

- **30 Oct 2026, 19:06 EAT:** Agnes confirmed Frank Omosh (proposal contact) is founder Francis Ouma Sikuku. Docs updated; no code change needed.

- **30 Oct 2026 (evening):** 0.2.0 committed directly to `main` (PR #1 closed; Agnes wants direct commits). Read the Proposal & Quotation and Content Requirements PDFs. Decisions recorded: official name, figures confirmed, Precious consent held, Paystack, build on live domain. Added 11 Light of Future Elementor widgets (Hero with sunrise artwork, Section Heading, Story Feature, Cards/FAQ, Programmes, Impact Figures, Impact Stories, Leadership, Partners, Call to Action, Contact Details), all text/images/links/colours/spacing editable; dynamic ones read the Core plugin and hide when empty. Added Appearance > Light of Future setup (idempotent): 11 published pages + 3 draft pages (Projects, Gallery, Resources) with Elementor layouts and plain-HTML fallback, main and footer menus, home page. Core: starter content (5 programmes, 3 stories with consent, 4 leaders, 4 verified figures), Paystack note in settings, listing addresses now served by Elementor pages (archives off), permalink refresh on update. Theme: single template for programmes/stories/projects/reports, sections.css.

- **30 Oct 2026:** Project started. Studied the brief, the content docx (mostly WhatsApp correspondence plus the content request sent to the client on 24 Oct), the legacy site and the Smile Community Centre repo. Pulled the Logo Kit from Drive. Built 0.1.0 foundation: Hello Elementor child theme with brand tokens, self-hosted Cinzel + Montserrat, Logo Kit logos and favicons, sticky accessible header with mobile menu, Earth Brown footer, Elementor global colours/fonts sync (fill-only, never overwrites edits), baseline security headers. Core plugin with organisation settings (prefilled only with contact details from the legacy site), 7 content types, structured fields, story consent gate and verified-only impact figures. Docs: README, PRD, architecture essentials, content gaps, requirements matrix, legacy content, brand.
