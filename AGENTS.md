# AGENTS.md: start here (for the next AI agent or developer)

Read this file, then `ARCHITECTURE-ESSENTIALS.md`, `PRD.md` and `CONTENT-GAPS.md` before changing anything. Append to the work log at the bottom when you finish.

## The project

- **Client:** Light of Future Foundation, Nairobi, Kenya. Founder: Francis Ouma Sikuku. Registered with the NGO Board in May 2025 (per the previous website).
- **Built by:** Pimofy Digital (Moses Munyi). Agreed budget KES 35,000, 2-3 week delivery (from the proposal).
- **New domain (ours):** https://lightoffuturefoundation.org/
- **Legacy site (read-only content source):** https://lightoffuture.org/ - no access to its hosting or developer. Content captured in `docs/legacy-content.md`.
- **Brief:** "Light of the Future WordPress Theme Design Prompt" (premium WordPress + Elementor theme, companion core plugin, WooCommerce crafts shop, donations). Summary in `PRD.md`.
- **Brand:** Logo Kit v1.0 (October 2026) from the client's Google Drive folder. Colours and fonts in `docs/brand.md`.
- **Reference repos:** moselanto/smilecommunitycentre.org (primary pattern for Elementor-first editing), dckabeten.org, cisternofhopefoundation, soundcreationsltd.com, pimofyoriginaltheme, nurabeauty.co.ke.

## Standing rules

- **Never invent facts.** No made-up statistics, names, quotes, prices, registration numbers, partners or payment details. Missing facts become clearly labelled editable placeholders and go in `CONTENT-GAPS.md`.
- **Safeguarding.** No identifying details of children or vulnerable people without written consent (guardian consent for minors). The Core plugin keeps impact stories in draft until consent is ticked.
- **Impact figures** render only when marked verified, with a source and a last-verified date. The legacy site's figures (200+ children, 30+ widows, 5 programmes) are not yet verified.
- **Payments** stay in "Not set up" until the organisation's own merchant accounts are verified and tested in sandbox. Never fake payment success. No secrets in Git (use `wp-config.php` constants).
- **Theme = presentation, plugin = data.** Don't put content models or business logic in the theme.
- Release flow: edit, bump version in `style.css` + `functions.php` (and the plugin header + `LOF_CORE_VERSION` if it changed), `bash scripts/build-zips.sh`, add a `CHANGELOG.md` entry, commit, PR.
- Wrap every visitor-facing string in `__()` / `esc_html__()` with text domain `light-of-future` (theme) or `light-of-future-core` (plugin).

## Current state (30 Oct 2026)

- Theme **0.1.0** and Core **0.1.0** on branch `feature/foundation-0-1` (PR). Not yet on a server. PHP lint not run here (no PHP in this environment); lint on staging or CI.

## Next steps (phase 2)

1. Elementor widgets: hero, programme grid, impact figures, story cards, leadership, partners, donation band, shop teaser, gallery, FAQ, contact, closing CTA.
2. Setup wizard / idempotent importer: pages (Home, About, Leadership, Programmes, Projects, Talent Development, Impact, Stories, Shop, Gallery, Donate, Get Involved, Resources, Contact, FAQ, legal), menus and Elementor layouts, using legacy content marked "pending approval".
3. Secure enquiry forms (nonce, signed time, honeypot, rate limit, private storage).
4. WooCommerce styling (optional plugin, no fatals when inactive).
5. Donation flow: decide gateway (M-PESA Daraja STK, card, PayPal) once merchant details arrive.
6. SEO/schema (defer to Rank Math/Yoast when active), GitHub Actions PHP lint.
7. Staging location to be confirmed (Smile and Sylvia's Dream use topnotchmall.co.ke sub-folders).

## Work log

- **30 Oct 2026:** Project started. Studied the brief, the content docx (mostly WhatsApp correspondence plus the content request sent to the client on 24 Oct), the legacy site and the Smile Community Centre repo. Pulled the Logo Kit from Drive. Built 0.1.0 foundation: Hello Elementor child theme with brand tokens, self-hosted Cinzel + Montserrat, Logo Kit logos and favicons, sticky accessible header with mobile menu, Earth Brown footer, Elementor global colours/fonts sync (fill-only, never overwrites edits), baseline security headers. Core plugin with organisation settings (prefilled only with contact details from the legacy site), 7 content types, structured fields, story consent gate and verified-only impact figures. Docs: README, PRD, architecture essentials, content gaps, requirements matrix, legacy content, brand.
