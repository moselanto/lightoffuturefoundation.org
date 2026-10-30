# Changelog

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
