# Product requirements (summary of the client brief)

Source: "Light of the Future WordPress Theme Design Prompt" (supplied 30 Oct 2026). Full brief in the project Drive folder (WEBSITE DESIGN).

## Goal

A creative, emotionally resonant, credible, donor-ready website that is **fully editable in Elementor**, rooted in real community work. Concept: **"Light that opens tomorrow"** - hope, dignity, people shaping their own futures. Not a generic NGO template or pity-driven fundraising page.

## Pages

Home, About Us, Leadership & Governance, Programmes (+ single), Projects, Talent Development, Impact, Impact Stories / News (+ single), Crafts / Shop (WooCommerce), Photo Gallery, Donate / Support Our Work, Get Involved, Resources / Reports, Contact, FAQ, legal pages (privacy, cookies, terms, donation policy, shipping, returns, safeguarding).

## Must-haves

- Elementor-editable layouts for all key pages; header/footer via Elementor Pro Theme Builder when licensed, theme fallback otherwise.
- First-party Elementor widgets for hero, programmes, impact figures, stories, projects, leadership, shop teaser, donation CTA, partners, reports, gallery, quote, FAQ, contact, closing CTA.
- Idempotent setup/importer that never overwrites edits.
- WooCommerce for the crafts shop (KES, Kenya-first checkout, delivery/pickup), optional without fatals.
- Donations separate from shop orders; M-PESA Daraja STK, card and PayPal via an approved provider; sandbox first; signature-verified callbacks; no secrets in Git.
- SEO/AEO foundations; structured data only from verified facts; defer to Rank Math/Yoast when active.
- Mobile-first, WCAG 2.2 AA intent, reduced motion, local fonts, strong Core Web Vitals.
- Docs: README, AGENTS, PRD, ARCHITECTURE(-ESSENTIALS), EDITING-GUIDE, INSTALL-AND-DEPLOY, SECURITY-AUDIT, CONTENT-GAPS, CHANGELOG, QA checklist.

## Brand

Logo Kit v1.0: Earth Brown #5C3D1E, Cream #FCF6E1, Sun Yellow #FBD62B, Sun Gold #F4AF16, Amber #DC8C13. Cinzel (display) + Montserrat (UI/body). Use about 50% Earth Brown, 25% Cream, 25% sun tones. This replaces the brief's suggested teal/coral palette, which was pending confirmation against the logo.

## Decisions (30 Oct 2026)

- Official name: Light of Future Foundation.
- Payments: Paystack (M-PESA and card) for donations and the shop.
- Built directly on https://lightoffuturefoundation.org/.
- Legacy impact figures confirmed; Precious's guardian consent held.
- Proposal extras to deliver: cookie banner, GA4 events (donations, sales, enquiries), WhatsApp button, branded email, Search Console, Google Business Profile, training and 3 months' support.

## Scope context

Agreed budget KES 35,000; delivery 2-3 weeks (proposal).
