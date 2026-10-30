# Changelog

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
