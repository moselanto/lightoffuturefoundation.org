# SEO, AEO, GEO and structured data (Core 0.47.0 / theme 0.72.2, 14 Nov 2026)

Source brief: the owner's "Light of the future AEO GEO SEO and schema" document (14 Nov 2026). This file consolidates the deliverables it asks for (audit, plan, schema architecture, crawler policy, fact register, measurement). Status labels: COMPLETED AND VERIFIED, IMPLEMENTED - AWAITING LIVE DEPLOYMENT, AWAITING FOUNDATION APPROVAL, AWAITING EXTERNAL ACCESS, NOT IMPLEMENTED - WITH REASON.

## 1. Owner decisions (14 Nov 2026)
| Topic | Decision |
|---|---|
| Google account for GA4, Search Console, Google tag | The foundation's own Google account (developer added as admin, not owner). |
| Privacy | Cookie notice + Google Consent Mode v2 (analytics only after Accept; ads signals always denied). |
| AI crawlers | Allow all search engines and AI assistants, including training crawlers. llms.txt published. |
| Google Business Profile | Public office address shown on Maps (needs the real office address; P.O. Box not accepted by Google). |

## 2. Baseline audit (live site, 14 Nov 2026, before 0.47.0)
| Severity | Finding | Evidence | Status |
|---|---|---|---|
| HIGH | No analytics or Search Console: no measurement of search impressions, clicks, donations or enquiries. | No gtag / verification tag in rendered HTML. | IMPLEMENTED (code); Google accounts AWAITING EXTERNAL ACCESS |
| MEDIUM | Structured data limited to NGO + WebSite on every page (+ Article on stories). No WebPage, breadcrumbs, people, page types, FAQ, logo id, areaServed, contactPoint. | JSON-LD on Home, FAQ, Leadership, programme pages. | IMPLEMENTED - AWAITING LIVE DEPLOYMENT |
| MEDIUM | FAQ answers visible but not machine-readable as Q&A. | /faq/ uses the lof-faq widget only. | IMPLEMENTED (FAQPage from the visible items) |
| MEDIUM | robots.txt has no stated AI crawler policy; no llms.txt. | /robots.txt = WordPress/WooCommerce defaults. | IMPLEMENTED |
| LOW | Snippet length not declared (max-snippet). | robots meta `max-image-preview:large` only. | IMPLEMENTED (`max-snippet:-1, max-video-preview:-1` on indexable pages) |
| LOW | `og:locale` en_GB while `<html lang>` is en-US (copy is British English). | Rendered head. | NOT IMPLEMENTED - WordPress Site Language is an admin setting; recommend English (UK) after owner confirms. |
| OK | 48 sitemap URLs return 200, one H1 each, unique titles/descriptions, canonical, OG/Twitter, no missing alt text, HTTPS + non-www redirect, cart/checkout/account out of sitemap (0.72.1), readme/license blocked (0.46.7). | Crawl 14 Nov. | COMPLETED AND VERIFIED |
| OK | No SEO plugin active (no Yoast/Rank Math/AIOSEO), so the Core graph is the only organisation entity; WooCommerce adds Product + BreadcrumbList on product pages only (Core skips its breadcrumb there to avoid duplicates). | Plugin list + rendered JSON-LD. | COMPLETED AND VERIFIED |

## 3. What 0.47.0 implements (files)
- `includes/analytics.php`: Google tag (gtag.js) for the GA4 ID saved in Organisation details; Consent Mode v2 defaults (all denied, analytics granted only after Accept); cookie notice (brand colours, 44px buttons, above the phone bottom bar); `#cookie-settings` links reopen it; editors not tracked; events `donate_start`, `generate_lead`, `contact_click` (phone/email/WhatsApp); Google and Bing verification meta tags on the home page.
- `includes/settings.php`: new fields `ga4_id`, `gsc_verification`, `bing_verification`, `business_profile`.
- `includes/schema.php`: one connected graph with stable ids: `/#organization` (NGO; logo `/#logo`, description, areaServed Nairobi/Kenya, knowsAbout = published programme titles, contactPoint, sameAs = every saved social URL + Business Profile, founder `/#founder`), `/#website`, `/#founder` (Person, Founder), approved leaders on /leadership/ (Person, jobTitle from `lof_role`, only `lof_approved`), a page node per URL (`#webpage`: WebPage / AboutPage / ContactPage / CollectionPage, description, dates, primary image), BreadcrumbList (`#breadcrumb`), programme pages `about` the programme, stories `Article` linked to the page, FAQPage (`#faq`) printed in the footer from the questions actually rendered.
- `includes/ai-discovery.php`: robots.txt groups for OAI-SearchBot, ChatGPT-User, GPTBot, PerplexityBot, Perplexity-User, ClaudeBot, Claude-SearchBot, Claude-User, Google-Extended, Applebot(-Extended), bingbot, DuckAssistBot, Meta-ExternalAgent, MistralAI-User, CCBot (same exclusions as `*`); `/llms.txt` built from existing site facts and pages; robots meta snippet permissions.
- Theme 0.72.2 `inc/elementor/widgets-content.php`: the FAQ widget passes each visible question/answer to Core.

Never in structured data: impact figures (pending re-confirmation), reviews/ratings, awards, registration number, funding amounts, children's details.

## 4. Fact register (public use)
| Fact | Value | Source | Status |
|---|---|---|---|
| Name | Light of Future Foundation | Owner, 30 Oct 2026 | Confirmed |
| Registration | NGO Board of Kenya, 23 May 2025, charitable organisation | Site copy (About/FAQ) | Confirmed; number/certificate not published (board approval) |
| Founder | Francis Ouma Sikuku | Owner | Confirmed |
| Postal address | P.O. Box 53-00520, Ruai, along Kangundo Road, Nairobi | Site copy | Confirmed |
| Office (street) address | - | - | AWAITING FOUNDATION (needed for Business Profile) |
| Phone / WhatsApp | +254 716 385 485 | Owner, 10 Nov | Confirmed |
| Email | info@lightoffuturefoundation.org | Owner | Confirmed |
| Impact figures | 55 people since 2024; 6,000+ mentored etc.; older 200+/30+ | Mixed | Not in schema until Francis re-confirms (HANDOVER section 9) |
| Programme locations | Only "Nairobi" / Dandora in stories | Site copy | areaServed limited to Nairobi |

## 5. Measurement plan
- GA4 (foundation account): key events `donate_start`, `generate_lead`, `contact_click`; mark them as key events in GA4 Admin. Paystack success is server-side (not tracked to avoid personal data).
- Search Console: URL-prefix property https://lightoffuturefoundation.org/ verified by the HTML tag setting; submit `wp-sitemap.xml`. Add Bing Webmaster Tools by importing from Search Console.
- Monthly AI visibility check (same prompts each month, record appeared / cited / page cited / accuracy): "Which organisations in Kenya support vulnerable children through education?", "Credible community organisations in Kenya supporting widows", "Kenyan charitable organisations accepting donations for community development", "What is Light of Future Foundation?" across ChatGPT, Gemini/AI Overviews, Perplexity, Copilot.

## 6. Awaiting the foundation
- Office address and opening hours (Business Profile; then `business_profile` link in settings).
- Cookie notice wording and the Privacy Policy (must mention analytics cookies) - board approval.
- Re-confirmed impact figures; registration certificate publication decision.
- Content roadmap items (programme evidence pages, annual report) need field material and approval; nothing generated without it.
