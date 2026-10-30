# AGENTS.md: start here (for the next AI agent or developer)

Read this file, then `ARCHITECTURE-ESSENTIALS.md`, `PRD.md` and `CONTENT-GAPS.md` before changing anything. Append to the work log at the bottom when you finish.

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

## Current state (30 Oct 2026, evening)

- Theme **0.4.0** and Core **0.4.0** on `main`. PHP lint not run here (no PHP in this environment); lint on the server or CI before relying on it.

## How to install / update

1. Elementor (free) active. 2. Upload `light-of-future-core.zip`, activate. 3. Upload `light-of-future.zip` (Hello Elementor installs automatically), activate. 4. **Appearance > Light of Future setup > Run setup**: creates the pages with Elementor layouts, menus, home page, and the starter programmes, stories, leaders and impact figures. Re-running never changes or duplicates existing items. To rebuild one page's layout, delete that page and run setup again.

## Next steps (phase 3)

1. Paystack donations (one-off + monthly via Paystack plans), server-side verification and webhook signature check, receipts; separate from shop orders.
2. WooCommerce styling + Paystack for WooCommerce; KES, delivery/pickup.
3. Secure enquiry forms (contact, volunteer, partner, sponsor) with nonce, signed time, honeypot, rate limit, private storage.
4. Gallery widget (consent-aware lightbox), reports widget, WhatsApp button.
5. Elementor Pro header/footer templates (optional), SEO/schema, cookie banner, GA4, legal page drafts.
6. GitHub Actions PHP lint; EDITING-GUIDE, INSTALL-AND-DEPLOY, SECURITY-AUDIT, QA checklist.

## Work log

- **30 Oct 2026, 19:55 EAT:** 0.4.0: foundation's content document applied (see CHANGELOG, CONTENT-GAPS 'To confirm'). Seeder revisions: bump an item's revision in seed.php to push new client copy; items edited in WordPress are skipped.

- **30 Oct 2026, 19:40 EAT:** 0.3.1: header/footer logos were missing live because Hello Elementor 3.x loads dynamic-header/footer.php; overridden in the child theme. Lesson: when Hello Elementor is the parent, override both `header.php`/`footer.php` parts and the `dynamic-*` parts.

- **30 Oct 2026, 19:30 EAT:** 0.3.0 on `main` (see CHANGELOG): real photos, hero collage, giving band, gallery, ribbon, secure enquiry form, count-up, WhatsApp/mobile donate, Apply latest design. Site is live on lightoffuturefoundation.org with Elementor 4.3.5, Hello Elementor 3.5.1. After uploading zips click Appearance > Light of Future setup > Apply latest design.

- **30 Oct 2026, 19:06 EAT:** Agnes confirmed Frank Omosh (proposal contact) is founder Francis Ouma Sikuku. Docs updated; no code change needed.

- **30 Oct 2026 (evening):** 0.2.0 committed directly to `main` (PR #1 closed; Agnes wants direct commits). Read the Proposal & Quotation and Content Requirements PDFs. Decisions recorded: official name, figures confirmed, Precious consent held, Paystack, build on live domain. Added 11 Light of Future Elementor widgets (Hero with sunrise artwork, Section Heading, Story Feature, Cards/FAQ, Programmes, Impact Figures, Impact Stories, Leadership, Partners, Call to Action, Contact Details), all text/images/links/colours/spacing editable; dynamic ones read the Core plugin and hide when empty. Added Appearance > Light of Future setup (idempotent): 11 published pages + 3 draft pages (Projects, Gallery, Resources) with Elementor layouts and plain-HTML fallback, main and footer menus, home page. Core: starter content (5 programmes, 3 stories with consent, 4 leaders, 4 verified figures), Paystack note in settings, listing addresses now served by Elementor pages (archives off), permalink refresh on update. Theme: single template for programmes/stories/projects/reports, sections.css.

- **30 Oct 2026:** Project started. Studied the brief, the content docx (mostly WhatsApp correspondence plus the content request sent to the client on 24 Oct), the legacy site and the Smile Community Centre repo. Pulled the Logo Kit from Drive. Built 0.1.0 foundation: Hello Elementor child theme with brand tokens, self-hosted Cinzel + Montserrat, Logo Kit logos and favicons, sticky accessible header with mobile menu, Earth Brown footer, Elementor global colours/fonts sync (fill-only, never overwrites edits), baseline security headers. Core plugin with organisation settings (prefilled only with contact details from the legacy site), 7 content types, structured fields, story consent gate and verified-only impact figures. Docs: README, PRD, architecture essentials, content gaps, requirements matrix, legacy content, brand.
