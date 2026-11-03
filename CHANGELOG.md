# Changelog

## 0.28.0 - 3 Nov 2026

- Get Involved: Volunteer, Partner, Sponsor and Fundraise as icon cards with a gold top edge and the link aligned at the bottom.
- Donate: photo beside the hero title; "Real needs, real change" and "Transparent and accountable" as icon cards; "Give in the way that suits you" as numbered cards.
- Contact, Donate and Get Involved forms: phone, email and address beside the form as icon rows (call and email are tappable), intro column stays in view while scrolling.
- Contact: "Other ways to reach us" as three icon tiles instead of a plain list.
- Fix: a portrait photo in a page hero (Contact) no longer stretches the hero tall.

## 0.27.0 - 3 Nov 2026

- Stories: a "More stories of change" divider under the featured story, and the remaining stories as equal photo cards side by side (no empty column, no mismatched card sizes); "With dignity and consent" as icon cards.
- Impact: hero intro line; new "From relief to self-reliance" three-step panel and "Focus areas behind the numbers" (six focus areas with a link to all 11), using wording already on the Programmes page; tidier figures and card rhythm.

## 0.26.0 - 3 Nov 2026

- Fix: the inner-page hero photo (0.24.0) and the Leadership "At a glance" card (0.25.0) now actually show on the live site (their helpers were only loaded in wp-admin).
- Programmes: focus areas without a photo show a designed brand tile with an icon and "Photo coming soon" instead of a large number; all cards equal height with the same photo crop; the 11 cards end with a centred last row.
- Talent Development: talent areas as icon cards (sports, music, creative arts) and a new "From discovery to the stage" section (talent searches, platforms to perform, led from the heart) using facts already on the site; hero photo beside the title.

## 0.25.0 - 3 Nov 2026

- Leadership & Governance: hero "At a glance" card (Board of Directors and Programme leads counted from the approved Leadership entries, 10 founding members, registered 2025); founder shown as one wide spotlight card; the three other directors as equal portrait cards in one row (no lone card); board roles as pills; programme leads two per row on phones. The founder photo no longer repeats in the hero.

## 0.24.0 - 3 Nov 2026

- Inner pages: hero now has a breadcrumb and an arch-framed photo of the foundation's own work beside the title (About, Leadership, FAQ, Programmes, Stories, Impact, Get Involved, Contact), replacing the empty right side.
- About: objectives as an editorial two-column list with the heading on the left; values and vision/mission text slightly larger.
- FAQ: section heading, hero intro line and five more answers (programmes, volunteering and partners, governance, consent and safeguarding, contact), all from facts already on the site.

## 0.23.0 - 3 Nov 2026

- About: values as numbered cards, objectives as numbered tiles, 7-card grids balanced 4 + 3 with the last row centred, "Registration and contacts" as a single fact sheet on a dark band, larger intro statement.
- Leadership: segmented Board / Programme leads switch, people as consistent profile cards (same photo crop, gold divider, role, bio), last row centred, governance shown as one connected panel, polished accountability cards.
- FAQ: two-column layout with numbered questions, gold open state and a "Still have a question?" help card (call and message buttons).
- Site-wide: only one closing call to action per page (the footer card hides when the page has its own); page slug body class for page-level styling.

## 0.22.0 - 3 Nov 2026

- Legal: Privacy Policy and Terms of Use pages with placeholder wording that makes no claims about the foundation (visible "Placeholder" note), created by a one-off upgrade (`lof_legal_rev`) with a "Footer legal links" menu shown in the footer base bar. WordPress's draft privacy page is reused and set as the site privacy page.
- Inner pages to the home standard: page hero with warm glow and gold rule; Stories page featured story as a cinematic photo card; single story/programme with framed image, quote card, sticky facts card and gold Donate card; enquiry forms as raised cards with larger fields; styled plain pages for legal text.
- Audit fixes: meta description and theme colour on every page (skipped if an SEO plugin is active), jQuery Migrate removed on the front end, global gold focus ring, underlined links in prose, 32px footer link targets. Audit report in `docs/ACCESSIBILITY-AND-SPEED-AUDIT.md`.
- Docs: new `docs/HANDOVER.md` (full status for the next agent); AGENTS.md and CONTENT-GAPS.md updated.

## 0.21.0 - 3 Nov 2026

- Footer: removed the large outlined "Light of Future" wordmark (Agnes). Gold top edge, cleaner base bar (copyright and registration, legal links, Back to top pill), tidier column titles and logo size.
- Why we exist: gold rule above a bolder statement; Vision and Mission as icon cards (eye and compass) with gold top edge.
- Impact band: figures as glass cards with a soft sun glow.
- Closing call to action: rounded sun card with ring motif instead of a flat full-width strip.

## 0.20.0 - 3 Nov 2026

- Phone and tablet menu: sub-menus are now an accordion. Each parent (About, Programmes, Impact, Get Involved) has a round chevron button that opens its pages in a tinted card (one group open at a time; the group of the current page opens automatically). Larger brand-font top items with dividers and a gold marker for the current section; sub-items with dots, chevrons and a highlighted current page; full-width Donate button. Parent links still go to their pages. Without JavaScript all sub-pages stay visible.
- Includes 0.19.2 phone side-margin fix.

## 0.19.2 - 3 Nov 2026

- Phones: side margins reduced from about 38px to 16px a side (removed a double gutter: container width calc plus extra padding). Tighter section, card, panel, FAQ and footer padding on small screens so content uses the width.

## 0.19.1 - 3 Nov 2026

- Phone bottom bar: the Menu button no longer turns pink when tapped; open state uses the brand gold highlight.

## 0.19.0 - 3 Nov 2026

- Give today (giving widget with a photo): one premium card on a light section. Left half is the photo with the heading, intro and three confirmed trust points (registered 23 May 2025; you choose where your gift goes; M-PESA and card through Paystack coming soon) over a dark gradient. Right half is a cream "Make your gift" form with dark selected amounts and a full-width button. Stacks on phones.
- Who we are: editorial two-column layout replacing the full-bleed split. Framed photo with gold gradient backing, dot pattern, caption on the photo and, on the home page, a "2025 / Registered with the NGO Board of Kenya" badge; lead paragraph with gold rule and gold-ruled supporting paragraphs.
- Phones: fixed bottom navigation (Home, Focus areas, a raised gold Donate button, Stories, Menu) replacing the single Donate bar. Current page highlighted; Menu opens the main menu; light and dark styles; WhatsApp button sits above it.

## 0.18.2 - 3 Nov 2026

- Lead story: text block anchored to the bottom of the photo on its own dark gradient, so the white title and text are always readable.

## 0.18.1 - 3 Nov 2026

- Give today: back to one column on tablets and phones (panel was cut off sideways).
- Founder name card: light card with dark text for readable contrast in both modes.
- Lead story: darker gradient and text shadow so white text stays readable over the photo.
- Headings no longer break mid-word on phones.

## 0.18.0 - 3 Nov 2026

Creative pass on four home sections (Agnes), CSS and widget markup only:
- Our story: founder letter layout. Arch-shaped portrait with gold outline and a name card (from the quote's attribution), drop cap on the first paragraph, quote as a dark pull-quote card, milestones as a gold timeline. Story features get class `lof-feature--story`.
- Stories of change: lead story becomes a full-photo card with text over a dark gradient and a gold "Read the story" button; other two stay side by side; hover lift on all.
- Who we are: full-bleed split panel (photo edge to edge on one half, text on a sand panel on the other) with gold ring and caption over the photo, gold rule under the lead paragraph; no hyphen breaks in the phone heading. Plain features get class `lof-feature--about`.
- Give today: sun-lit brown band with soft rays, arch-shaped photo matching the sun in the logo, giving panel with dashed step divider, brand-font amounts and an arrow on the button.

## 0.17.0 - 3 Nov 2026

Home sections redesigned (Agnes): CSS and widget markup only, no "Apply latest design" needed.
- Focus areas: cleaner cards (no stray symbol badge), 4:3 photos with soft shade, title underline on hover, three-line excerpt, "Lead" chip with person icon, link row with round arrow button.
- Stories of change: magazine layout on desktop (first story large, the other two as side-by-side cards), place shown as a chip on the photo, gold quote mark.
- Who we are: photo with gold outline frame and dot pattern, caption as an overlay on the photo, larger lead paragraph.
- Give today: photo as a framed portrait card with a gold "KES" badge (no more cropped circle), giving panel as a light card on the brown band with dark selected chips and a full-width button, shield icon on the note.
- Approach steps: numbered gold circles. Section headings slightly larger and tighter.

## 0.16.3 - 3 Nov 2026

- Desktop slide 3 (portrait photo): photo starts at 52% width and the text column is narrower, leaving a clear gap.

## 0.16.2 - 3 Nov 2026

- Phone slider rebuilt as a photo card with the text on a dark panel underneath, so the pill and headline never sit over a face; pill stays on one line.
- Desktop slide 3: text column narrowed so it keeps a clear gap from the photo.

## 0.16.1 - 3 Nov 2026

- Slider: progress bars and buttons now sit clear of the overlapping figures card on desktop.
- Portrait photos (slide focus "Top", e.g. Precious) show on the right half on desktop so the headline never crosses a face.
- Phone: photo on top, text on a dark panel below it, and the counter and buttons moved to the top of the slider so the fixed Donate bar no longer hides them.

## 0.16.0 - 3 Nov 2026

- Home: new full-width slider (widget `lof-slider`) replacing the split hero. Three slides using the foundation's own photos and confirmed copy: mission (Elijah's new home), Philister's safe home at 85, Precious staying in school. Each slide has its own headline, text and two buttons; slow zoom, text rise-in, gold progress bars with labels, slide counter, previous/pause/next buttons, keyboard arrows, swipe on phones, pauses on hover/focus and when the tab is hidden, no motion for reduced-motion users. Fits the screen on laptops and up.
- Figures bar (200+, 30+, 11, 2025) as a white card overlapping the slider edge.
- Balance: focus-area cards without a photo now get a light branded tile instead of a heavy brown block; cards gain border, shadow and hover lift.
- Applied by a one-off upgrade (option `lof_hero_rev` = 3) that swaps only the home hero for the slider and backs up the old layout; no "Apply latest design" needed. The importer's home definition uses the slider too.

## 0.15.0 - 3 Nov 2026

- Website typography switched to the Smile Community Centre pairing (Agnes): Nunito ExtraBold for headings and figures, Figtree for text, buttons and menus. Fonts are self-hosted from the Smile theme (SIL OFL) and preloaded. Cinzel stays in the logo artwork only and is no longer loaded.
- Giving note made consistent everywhere: online giving through Paystack is being set up.
- Phone: fund dropdown no longer cramped; page bottom padded so the fixed Donate bar does not cover content; WhatsApp button sits above the bar.

## 0.14.0 - 3 Nov 2026

From Agnes's mock-up, using only confirmed facts and the foundation's own photos. No "Apply latest design" needed: all changes are in widget output, CSS and JS.

- Leadership: tabs for "Board of Directors" (executive + board sections, 4 people) and "Programme leads" (7), with counts, keyboard arrows and #board / #programme-leads links. Without JS all sections still show.
- FAQ: polished accordion (cards, round plus/close icon, only one answer open at a time).
- Giving panel (lof-donate widget): step 1 amount chips plus "Other amount" field, step 2 fund choice (Where it is needed most + every focus area), "Continue to give" button. Until Paystack is live it goes to the Donate/Contact form, which pre-fills the message (e.g. "I would like to give KES 1,000 to Education...") and selects "Giving / donations".

## 0.13.1 - 31 Oct 2026

- "Apply latest design" returned no result twice on the live site (likely a server time-out). The handler now raises the time and memory limits and keeps running if the browser disconnects.
- The 0.13.0 home hero (registration pill and figures row) is now applied by a one-off, targeted upgrade that runs automatically once (option `lof_hero_rev` = 2). It only replaces the old default values, backs up the previous layout and leaves editor changes alone.

## 0.13.0 - 31 Oct 2026

Ideas taken from Agnes's reference mock-up (light_of_future_foundation_website.html), using only confirmed facts and the foundation's own photos (the mock-up's stock photos and unconfirmed claims were not used).

- Home hero: registration pill ("Registered NGO, Kenya · 23 May 2025") and a row of four figures (200+ children supported, 30+ widows assisted, 11 focus areas, 2025 officially registered) in place of the small badges. Hero widget gains Badge 4; "figure|label" badges render as the figures row.
- Focus-area cards: number tile on the photo and "Lead: name" where the foundation's document names one (Education: Edith Atieno; Food Security and Children and Families: Scholastica Baraza; Healthcare: Stephen Oduor; Water and Sanitation: Kennedy Mumbo; Hygiene Kits: Lucy Habiba; Decent Houses and Community Development: Victor Onyango). Override with custom field `lof_lead`.
- Menu and theme buttons no longer turn pink when tapped (Hello Elementor focus colour overridden).

## 0.12.2 - 31 Oct 2026

- Phone menu now opens as a full panel under the header (the header's blur effect had trapped it in a thin strip).
- Dark mode: menu button is gold with dark bars for contrast.
- Programmes giving block fits the phone width (two amount columns, stacked buttons, grid items allowed to shrink).
- Contact: long email address wraps inside its card.
- Footer: wider email tile on desktop; wordmark sized to fit on phones.

## 0.12.1 - 31 Oct 2026

- Fix: home hero text was pushed to the left edge (hero row lost its side margins in 0.12.0). The "Since 2021" card and stamp now sit on the photo's corners.
- Footer refined: row of three contact tiles (Call, Email, Visit), brand column with "Registered NGO, Kenya" badge, Explore links in two columns, Focus areas, Support our work column with Donate button, outlined wordmark now fits the width.

## 0.12.0 - 31 Oct 2026

- Home hero now fits the screen on 13.3in laptops and larger without scrolling: height follows the visible window minus the header; title, text, buttons and photo scale with screen height; header slims on short screens.
- Hero photo is now a slider (crossfade every 6 seconds, dots, pauses on hover/focus, still for reduced motion).
- New footer: gold call-to-action card (hidden on Home and Donate, which already end with one), four columns (brand with social icons, Explore, Focus areas, Get in touch with icons and Donate button), large outlined wordmark, base bar with registration line and Back to top. WhatsApp icon appears once the number is set.
- Phone polish: no sideways scrolling, tighter header and logo, centred topbar contacts, stacked hero buttons, mobile menu always opens directly under the header.

## 0.11.1 - 31 Oct 2026

- Menu: the Impact dropdown's first link now reads "Our impact" (Agnes).
- Phone-width preview for checks from a desktop browser: `/?lof_mobile_preview=light` or `=dark` (noindex). A `?lof_theme=light|dark` URL parameter forces the colour mode for that view.

## 0.11.0 - 30 Oct 2026

- Programmes replaced by the foundation's 11 focus areas (Agnes, 30 Oct 2026): Education, Food Security, Healthcare, Water and Sanitation, Hygiene Kits for the Girl Child, Decent Houses, Community Empowerment, Community Development, Children and Families, Domestic and GBV Awareness, Prison Outreach. Talent Development and Hand Skills programme items moved to draft (untouched copies only); the Talent development page stays.
- Main menu modelled on Cistern of Hope: Home, About (About us, Leadership & Governance, FAQ), Programmes (Programmes, Talent development), Impact (Our impact, Stories), Get Involved (Get involved, Donate), Contact, plus the Donate button.
- Fix: the five newly added programme leads now get their studio portraits (portrait switch retries until all 11 leaders exist).
- People grids centre a short last row; square portraits on the same cream backdrop.
- Impact figure "Community programmes" renamed "Focus areas".

## 0.10.0 - 30 Oct 2026

Benchmarked again against the Cistern of Hope home page; content from the foundation's content document (leadership structure, 30 Oct 2026).

- Uniform studio portraits for all 11 leaders (same warm cream backdrop, lighting and framing; faces unchanged). Bundled as leader-*-pro.webp and switched on once by Apply latest design (option lof_leader_photo_set = 2).
- Six programme leads added: Marion Atieno (communication, media and digital engagement), Lucy Habiba (community outreach and social development), Stephen Oduor (assistant operations manager and health care), Kennedy Mumbo (environment, water and sanitation), Victor Onyango (community development), Scholastica Baraza (food security, children and families). Leadership section 03 is now a grid of seven programme leads.
- Home rebalanced: no leaders strip or word ribbon. Order: hero, why we exist + vision/mission, four-step approach, founder story with milestones, six programmes, impact band, stories, giving, partners, dignity feature, closing call to action.

## 0.9.0 - 30 Oct 2026

Prototype refinement carried to the inner pages, brand colours kept.

- Page heroes (About, Leadership, Programmes, Impact, Stories, Donate, Get Involved, Contact, FAQ): cream band (warm dark surface in dark mode), large Cinzel title with a gold rule, faint brand symbol, no sun bands.
- Impact widget: new "Compact trust strip" option. Strip added under the hero on About, Programmes, Stories and Get Involved, and before the gift form on Donate. The duplicate figures at the foot of About were removed.
- Intro statement with band: About (main objective + vision / mission), Programmes (approach + three steps). Band now fits two or three panels.

## 0.8.1 - 30 Oct 2026

- Image captions (e.g. Philister's safe home) now readable in dark mode.

## 0.8.0 - 30 Oct 2026

Design refinement from Agnes's prototype (index.html), keeping the Logo Kit colours (Earth Brown, Cream, Sand, Gold).

- Hero: arched main photo, rotated gold "Hope in action" stamp, floating "Since 2021" card, fine gold circle outlines (new hero controls: stamp, card title, card text).
- New LOF Intro Statement widget: "Why we exist" heading beside a large statement with a left rule, plus a vision / mission band. Replaces the home purpose cards.
- Impact on earth tone becomes a divided trust strip.
- Crisper system: 6/10px radii, uppercase letter-spaced buttons with arrows, eyebrows with a leading rule, bordered cards that lift on hover.
- Programme cards: wider image, brand-symbol medallion, Cinzel titles, uppercase arrow links.
- Closing call to action becomes a split band (text left, buttons right) on desktop.

## 0.7.0 - 30 Oct 2026

Benchmarked against Cistern of Hope (home, about, leadership) and Smile Community Centre staging.

- Leadership page rebuilt: 01 Executive leadership (Francis, full profile card), 02 Board of Directors (Nixon, Vincent, Frederick as profile cards with board role badges), 03 Programmes team (Edith Atieno, new), "How the foundation is governed" joined steps panel, accountability cards (safeguarding, financial accountability, responsible communication).
- New: leader groups (executive / board / team) and profile-card layout in the Leadership widget; numbered section eyebrows (01 ——— LABEL); milestones on the story feature; joined "steps" card style.
- Home: purpose cards (vision, mission, objective) and a "Meet the people behind the work" team strip; founder story with milestones (age 20, 2021, 2025) on Home and About.
- Edith Atieno - Education & Skills Development Coordinator (children and youth): school support, educational materials, scholarships, child protection and safeguarding, vocational training. Portrait added.

## 0.6.1 - 30 Oct 2026

- Fix: theme switch printed raw attribute text (broken markup in 0.6.0).
- Fix: logos distorted / dark logo vanished - the 0.6.0 SVG optimisation rounded transform values (scale 0.2647 -> 0.2). Original Logo Kit SVGs restored.
- Ribbon pause button now announces "Play moving text" when paused.
- Leadership: Board of Directors - Francis Ouma Sikuku (Chairman; Founder & Executive Director), Nixon Omondo Owino (Vice Chairperson; General Operations Manager & Chief Adviser, new portrait), Vincent Okoth Okallo (Secretary; Administration Manager), Frederick Otieno (Treasurer; Finance Manager). Kennedy Oyamo Mumbo (old site) hidden.

## 0.6.0 - 30 Oct 2026 (theme 0.6.0, Core 0.6.0)

- Inner pages: Programmes (approach band, 11 programmes, giving band, CTA), Stories (featured story spotlight, consent and dignity band), Donate (amounts with photo, where your gift goes, ways to give, why give with confidence, giving FAQ, enquiry form pre-set to giving). Programme cards without photos get a branded panel.
- Accessibility and speed fixes from the audit in `docs/ACCESSIBILITY-AND-SPEED-AUDIT.md` (main landmark, heading order, contrast in both themes, ribbon pause, focus rings, target sizes, theme switch semantics, mobile menu focus, compression and caching rules, lighter logos, trimmed head).

## 0.5.0 - 30 Oct 2026 (theme 0.5.0, Core 0.5.0)

Goal (Agnes): unique, classic, professional, international standard; light and dark mode for every visitor; professional, well-aligned menus.

- Light and dark mode: follows the visitor's device setting by default; a sun/moon switch in the header lets anyone choose, remembered on that device; applied before the page paints (no flash); colours moved to design tokens with contrast-checked dark values; logo swaps to the dark-background artwork.
- Header: one-line navigation (no wrapping), About and Our Work dropdowns (hover, keyboard focus, aria-expanded), short "Donate" button, theme switch, slimmer bar; mobile drawer with nested items.
- "Apply latest design" now also rebuilds the setup-created Main menu into the new structure (a hand-renamed menu is left alone).
- Hero: refined title scale, slimmer horizon bands that no longer run through the badges, founder portrait in the collage.
- Leadership: Frederick Otieno, Finance Manager (from the updated content document) with portrait.

## 0.4.0 - 30 Oct 2026 (theme 0.4.0, Core 0.4.0)

Uses the foundation's own content document (history, vision, mission, values, registration, objectives, 11 programmes, leadership) and three new portraits.

- About page: updated founder history (Kisumu Institute of Management College, team of 10, registered 23 May 2025), vision, mission, main objective, 7 values, 7 objectives, registration/address at a glance.
- Programmes: 11 (decent houses, hygiene kits, education, clean water and sanitation, talent, hand skills, business start-ups, health and medical support, GBV awareness, prison outreach, food donations). Impact figure "Community programmes" now 11.
- Leadership: Francis Ouma Sikuku (Founder & Executive Director) and Vincent Okoth Okallo (Administration Manager & General Secretary) with new portraits and role descriptions.
- Organisation details: postal address P.O. Box 53-00520, Ruai, along Kangundo Road, Nairobi; footer registration line. Only filled where empty or still the old default.
- Seeder: starter items now carry a revision; untouched items are refreshed when the foundation sends new content, edited items are never overwritten.
- Home/FAQ copy updated (team quote, 11 programmes, office location).

## 0.3.1 - 30 Oct 2026 (theme only)

- Fix: header and footer logos did not show on the live site. Hello Elementor 3.x uses `template-parts/dynamic-header.php` / `dynamic-footer.php` (site title only) when Elementor is active; the child theme now overrides both to use the Light of Future header and footer with the Logo Kit logos.

## 0.3.0 - 30 Oct 2026 (theme 0.3.0, Core 0.3.0)

Goal (Agnes): clearly better than Smile Community Centre and Cistern of Hope.

- The foundation's own photos (from lightoffuture.org, phone watermarks and caption text cropped, EXIF removed, WebP): Elijah's new home, Philister's new home, Precious's school shoes (guardian consent held), founder Francis Ouma Sikuku.
- Home hero: layered photo collage with floating brand seal over the sunrise artwork, plus trust badges (NGO Board 2025, 200+ children, where we work).
- New widgets: Giving Band (suggested KES amounts, ?amount= passed on), Photo Gallery (mosaic + Elementor lightbox, consent note), Word Ribbon (moving strip, static with reduced motion), Enquiry Form.
- Impact figures count up when scrolled into view (respects reduced motion).
- Floating WhatsApp button (only when a WhatsApp number is set) and a mobile Donate bar.
- Core: secure enquiry form - nonce, signed time, honeypot, rate limit (5/hour per hashed IP), validation, consent, private Enquiries list (not public, not in REST), email to the foundation with Reply-To. Shortcode [lof_enquiry_form].
- Setup: photos imported into the Media Library once; starter stories, founder and two programmes get photos (only where none set). New "Apply latest design" button rebuilds pages created by setup with the newest layouts and keeps a backup of each previous layout (`_lof_layout_backup`).
- Layouts: Home adds ribbon, photos, giving band; About adds photo; Donate adds amount band; Get Involved and Contact get the enquiry form; Gallery draft filled with the four photos.

## 0.2.0 - 30 Oct 2026 (theme 0.2.0, Core 0.2.0)

- 11 Light of Future Elementor widgets: Hero (brand sunrise artwork or photo), Section Heading, Story Feature, Cards / FAQ, Programmes, Impact Figures, Impact Stories, Leadership, Partners, Call to Action, Contact Details. Every text, image, link, colour and spacing value is editable; dynamic widgets read the Core plugin and stay hidden when there is nothing approved.
- Appearance > Light of Future setup: one click creates Home, About Us, Leadership & Governance, Programmes, Talent Development, Impact, Stories, Donate, Get Involved, Contact, FAQ (plus draft Projects, Gallery, Resources) with Elementor layouts and plain fallback text, the main and footer menus and the home page. Never changes or duplicates existing items.
- Core: starter content from the previous website as confirmed by the foundation (5 programmes, 3 stories with consent, 4 leaders, 4 verified impact figures); Paystack noted as the gateway (keys in wp-config.php); listing addresses served by Elementor pages; permalinks refresh after updates.
- Theme: single template for programmes, projects, stories and reports with facts panel and donate box; new `assets/css/sections.css`.
- Docs: decisions recorded (official name, figures, consent, Paystack, live domain, direct commits to main); CONTENT-GAPS rewritten.

## 0.1.0 - 30 Oct 2026 (theme 0.1.0, Core 0.1.0)

- New project foundation.
- Theme `light-of-future` (child of Hello Elementor): Logo Kit v1.0 brand tokens, self-hosted Cinzel + Montserrat (SIL OFL), logo variants and favicons, sticky accessible header with mobile menu and Donate button, Earth Brown footer with contact/social/legal menus, reveal motion that respects reduced-motion, Elementor global colours and fonts seeded once (never overwrites edits), baseline security headers, user-enumeration block.
- Plugin `light-of-future-core`: Organisation details screen (contact prefilled from the legacy site only), payment mode defaulting to "Not set up", content types for programmes, projects, impact stories, leaders, partners, reports and impact figures, structured fields, story consent gate, verified-only impact figures helper.
- Docs: README, AGENTS, ARCHITECTURE-ESSENTIALS, PRD, CONTENT-GAPS, docs/requirements-matrix.md, docs/legacy-content.md, docs/brand.md. Build script `scripts/build-zips.sh`.
