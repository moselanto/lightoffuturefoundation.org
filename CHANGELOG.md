# Changelog

## Core 0.47.0 + Theme 0.72.2 - 14 Nov 2026
SEO / AEO / GEO and analytics (owner brief "light of the future aeo geo seo and schema", decisions of 14 Nov: foundation's Google account, cookie notice + Consent Mode v2, allow all AI crawlers, public office for Business Profile). Full write-up: `docs/SEO-AEO-GEO.md`.
- **Google tag + GA4 with Consent Mode v2** (`includes/analytics.php`): loads only when Organisation details > Google Analytics 4 Measurement ID is set. All consent types default to denied; analytics granted only after Accept on a new cookie notice (brand colours, sits above the phone bottom bar; `#cookie-settings` links reopen it); ad signals always denied; editors not tracked. Events: `donate_start`, `generate_lead`, `contact_click`. Google Search Console and Bing verification meta tags on Home from new settings.
- **Settings** (`includes/settings.php`): `ga4_id`, `gsc_verification`, `bing_verification`, `business_profile`.
- **Structured data** (`includes/schema.php`): connected graph with stable ids (`/#organization`, `/#website`, `/#logo`, `/#founder`, `<url>#webpage`, `#breadcrumb`, `#faq`); NGO gains description, legalName, areaServed (Nairobi, Kenya), knowsAbout (published programme titles), contactPoint, sameAs (all saved socials + Business Profile); page types WebPage / AboutPage / ContactPage / CollectionPage; BreadcrumbList (skipped on WooCommerce pages, which print their own); approved leaders as Person on /leadership/; programme pages `about` the programme; story Article linked to its page; FAQPage from the questions the FAQ widget actually renders (printed in the footer). No impact figures, ratings or registration number in schema.
- **AI discovery** (`includes/ai-discovery.php`): robots.txt groups allowing documented AI crawlers (OAI-SearchBot, ChatGPT-User, GPTBot, PerplexityBot, Perplexity-User, ClaudeBot, Claude-SearchBot, Claude-User, Google-Extended, Applebot, Applebot-Extended, bingbot, DuckAssistBot, Meta-ExternalAgent, MistralAI-User, CCBot) with the same exclusions as `*`; `/llms.txt` built from facts and pages already on the site; `max-snippet:-1, max-video-preview:-1` on indexable pages.
- **Theme 0.72.2**: FAQ widget (`inc/elementor/widgets-content.php`) passes each visible question/answer to Core for FAQPage. No visual change.
- PHP not available in the build sandbox: brace/paren/bracket balance checked by a tokenizer script on every Core PHP file; logic reviewed by hand.

## Core 0.46.7 - 14 Nov 2026
- **Hide WordPress version files** (owner chose option 1): new `includes/hardening.php` writes a marked block `# BEGIN Light of Future Core hardening` into the site root `.htaccess` that denies `readme.html` and `license.txt` (mod_rewrite `[F]` rule plus a `FilesMatch` deny). WordPress core updates restore these files but do not touch `.htaccess`, so they stay blocked. Runs on `admin_init` for administrators only when the block is missing (re-added if removed); outcome in option `lof_core_harden_0467` (`written` / `removed` / `not-writable`). Removed on plugin deactivation. Off switch: `add_filter( 'lof_core_harden_htaccess', '__return_false' )` (the block is then removed on the next wp-admin load).
- Housekeeping in wp-admin (not code): inactive plugins Akismet and Hello Dolly deleted.
- PHP not available in the build sandbox: syntax checked by hand and by a bracket-balance script.

## Theme 0.72.1 - 14 Nov 2026
Follow-up audit (crawled all 48 sitemap URLs: all 200, one H1 each, no missing alt text, no PHP notices, no mixed content; HTTP and www redirect to https, author enumeration and the users REST route closed, security headers present; Paystack webhook HMAC check and backup token download reviewed, no issues).
- **Share picture on Home:** Home (and any page without a featured image) printed no `og:image`, so links shared on WhatsApp/Facebook showed no picture. New fallback `assets/images/brand/og-default.jpg` (1200x630, cropped from the approved `brand-hero-dark.webp`, 31 KB) used after the featured image, front-page image, custom logo and site icon.
- **Sitemap:** Cart, Checkout and My account were listed in the page sitemap while marked noindex (Checkout also redirects to Cart). They are now left out (`wc_get_page_id` cart / checkout / myaccount) in `inc/seo-0720.php`.
- Docs: HANDOVER status brought up to 0.72.1 / Core 0.46.6 and the public phone/WhatsApp corrected to +254 716 385 485.
- PHP not available in the build sandbox: syntax of the changed lines in `inc/seo-0720.php` checked by hand.
- Server note (not code): `/readme.html` is publicly readable and reveals the WordPress version; delete it on the server (it returns after core updates).


## Core 0.46.6 - 11 Nov 2026
- Built-in default phone in `includes/settings.php` changed from +254 715 394295 to the official +254 716 385 485 (owner request). The same old number was also the fallback in `includes/schema.php` (organisation structured data); updated there too, so the old number no longer appears anywhere in the plugin. Only affects fresh installs or a reset setting; the live "phone" setting was already +254 716 385 485 (theme 0.70.0 / 0.72.0).

## Theme 0.72.0 - 11 Nov 2026
Whole-site audit (owner: "go through the entire website, clean it, improve it, check for errors and fix them"). Crawled all 51 sitemap URLs, 370 internal links/images and 15 external links: all return 200 except the item below.
- **Old phone number removed:** FAQ "How do I contact you?" still said +254 715 394295. Replaced on output (`inc/seo-0720.php`) and in stored page content / Elementor data by `lof_upgrade_0720_phone_text` (guard `lof_phone_text_0720`), which also clears the Elementor CSS cache and flushes rewrite rules.
- **Broken sitemap entry:** /stories/topic/business-empowerment/ returned 404 but was in the sitemap. Story topics are now out of the sitemap and /stories/topic/* redirects 301 to /stories/.
- **Unique meta descriptions:** 23 pages shared one description. Each page now uses its own existing intro line (no new wording); product categories and remaining pages get a page-specific fallback.
- **Social sharing tags:** Open Graph and Twitter card tags (title, description, URL, picture) on every page, so links shared on WhatsApp/Facebook/X show a preview.
- **Canonical links** added to the shop and product category pages.
- **Placeholder legal pages:** Privacy Policy and Terms (still awaiting approved wording) are kept out of search results and the sitemap until real wording is published.
- New always-loaded file `inc/seo-0720.php` (replaces the 0.6x `lof_meta_description` output).

## Theme 0.71.1 - 11 Nov 2026
- **Partner pages redesigned** (owner: "improve these two pages more professionally"). `template-parts/partner.php` rewritten: photo hero (the supported programme's featured image under a dark gradient) with a status pill, name, tagline, "See the programme" / "Become a partner" buttons and a large white logo card; "Our partnership" story with a highlighted closing line beside an "At a glance" card (Partner, Partnership, Supports, Programme, Donate); three impact cards with line icons and large faded numbers (no reveal animation, so they never render clipped); full-width photo card for the supported programme (uses the programme excerpt; the old lead showed a person's name); other-partner cards with "Read about our partnership"; refreshed Partner with us band. Phones: logo above the text, stacked buttons, single-column cards.
- CSS: the 0.71.0 partner-page block replaced by block 0.71.1 in shop.css and shop.min.css (tile link rules kept). No wording added beyond headings/labels; partner text unchanged.

## Theme 0.71.0 - 11 Nov 2026
- **Partner pages** (owner; modelled on the Cistern of Hope partner pages): each approved partner with text now has its own page at `/partners/<slug>/` (`/partners/suivera/`, `/partners/grace-international-foundation/`). `inc/partners-0710.php` (always loaded) makes the Core `lof_partner` type viewable via `register_post_type_args` (public, /partners/ slug, editor + excerpt; excluded from search), 404s unapproved partners or partners without text, and uses `single-lof_partner.php` + `template-parts/partner.php`. Layout: hero (status · focus, name, tagline), logo card + facts (Partnership, Supports, Programme) beside the story, "The difference this partnership makes" numbered impact cards, a card linking to the supported programme, other partners, and a Partner with us band (Become a partner, All our partners).
- Partner text from the owner's message of 11 Nov 2026 (grammar tidied only) set by one-off `lof_upgrade_0710_partners` (guard `lof_partners_0710`), which also refreshes permalinks. Meta: `lof_tagline`, `lof_status_label`, `lof_focus`, `lof_impacts` (one per line), `lof_programme` (slug: hygiene-kits / food-donations).
- Home partners: tiles link to the partner page (same tab) with "Read about our partnership"; anchor `#our-partners` added before the tiles. Partners without a page keep the old behaviour.
- Lightbox on programme and story pages: photos in media sections (e.g. Margaret, Rosemary on Community Empowerment) now open in the same viewer as the Gallery (close, arrows, keyboard, swipe) instead of opening the image file. New block at the end of `shop.js`; skipped on the Gallery, which keeps its own.
- CSS block 0.71.0 appended to shop.css and shop.min.css.

## Theme 0.70.6 - 11 Nov 2026
- Community Empowerment (owner): two new photos of Rosemary at her fruit and vegetable stall (business empowerment start-ups), shown after Margaret's media in the media plugin's photo grid style. Uploaded via Media as `programme-empowerment-rosemary-1.webp` / `-2.webp`; one-off `lof_upgrade_0706_empower_photos` stores the IDs in option `lof_empower_photo_ids`; filter `lof_0706_empower_photos` in `inc/agri-0702.php` inserts them. Captions reuse the approved Rosemary stall wording.

## Theme 0.70.5 - 10 Nov 2026
- Gallery (owner): the two agriculture videos also show on /gallery/, first in the "Community enterprise: the goat feedlot vision" section (`#lofm-goats .lofm__videos`), same card style as the goat videos. `inc/agri-0702.php` filter now runs on the Gallery page too; the regex allows the section heading before the video row; on the Gallery it never appends elsewhere if the section is missing. Media plugin untouched.

## Theme 0.70.4 - 10 Nov 2026
- WhatsApp on the official number (owner): one-off `lof_upgrade_0704_whatsapp` sets the Core setting `whatsapp` to +254 716 385 485 (guard `lof_whatsapp_0704`), and the fallback in `lof_wa_number()` (`inc/shop.php`) is now 254716385485. Covers the WhatsApp button and chat card, footer and Contact WhatsApp links, and every "Order on WhatsApp" link and order form (wa.me/254716385485).

## Theme 0.70.3 - 10 Nov 2026
- Fix: the 0.70.2 front-end filter lived in `inc/upgrade-0700.php`, which only loads in wp-admin, so the agriculture videos did not show. The filter and figure markup moved to `inc/agri-0702.php`, loaded on every request; filter priority raised to 999 so it runs after the media plugin builds the goat row.

## Theme 0.70.2 - 10 Nov 2026
- Community Development (owner): the two agriculture videos now sit in the same video row as the goat videos (first in `#lofm-goats .lofm__videos`, media-plugin markup and styling) instead of a separate block under the agriculture paragraph. One-off `lof_upgrade_0702_agri_move` removes the 0.70.0 block from the content and stores the URLs in option `lof_agri_video_urls` (guard `lof_agri_move_0702`); a `the_content` filter (priority 99) inserts them, falling back to the end of the content if the goat row is missing. Media plugin untouched.

## Theme 0.70.1 - 10 Nov 2026
- Agriculture video posters: 0.70.0 built the poster URLs from the parent theme (hello-elementor) so the players showed black until played. `inc/upgrade-0700.php` now uses the child theme URL, and a one-off fix (guard `lof_agri_posters_0701`) corrects the URLs already saved in the Community Development programme.

## Theme 0.70.0 - 10 Nov 2026
- **Official phone number** (owner): one-off admin upgrade `inc/upgrade-0700.php` sets the Core setting `phone` to +254 716 385 485 (header, footer, Contact, FAQ). WhatsApp number setting not changed (still +254 715 394 295). Guard option `lof_phone_0700`.
- **Partners: names under logos** (owner): the LOF Partners widget prints the partner name below each logo (Suivera first, Grace International Foundation second, by menu order).
- **Community Development: agriculture videos** (owner, from the foundation's Drive folder): two videos re-encoded to 360px wide (7.8 MB and 9.5 MB), uploaded via Media as `programme-agriculture-1-web.mp4` and `programme-agriculture-2-web.mp4`; the upgrade inserts them under the bananas/maize/groundnuts paragraph, two across on desktop, stacked on phones, posters in `assets/img/agri/`. Guard option `lof_agri_videos_0700`. Originals are in the foundation's Google Drive.
- CSS block 0.70.0 appended to shop.css and shop.min.css.

## Theme 0.69.2 - 10 Nov 2026
- Gallery "Levelling the floor": on tablet and desktop (4:3 tiles, caption below) the thumbnail is framed at 50% 58% so the worker's head is no longer cut off; phones keep the 0.69.1 framing.

## Theme 0.69.1 - 10 Nov 2026
- Phones (<=760px): WhatsApp button reduced from 54px to 46px and moved lower and closer to the edge (8px above the bottom bar, 10px from the right) so it stops covering Leadership board cards. Still always visible.
- Gallery: the "Levelling the floor" thumbnail (philister-floor2.webp) is framed toward the bottom (object-position 50% 92%) so the person low in the photo is not hidden behind the caption.
- CSS block 0.69.1 appended to shop.css, shop.min.css and critical/home-shop.css.

## Theme 0.69.0 - 10 Nov 2026
- **WhatsApp button always visible on phones and tablets** (owner: "on mobile it loads, then after loading it disappears"). Cause: `shop.js` added `is-tucked` on screens <= 991px while `scrollY < 160` and the panel/teaser were hidden, and CSS (0.46.5) hid the button with `translateY(160%)`, `opacity:0`, `visibility:hidden`; since 0.65.1 the teaser auto-hides after 6 s, so at the top of the page the button vanished. A second rule (0.49.0 / 0.58.3) slid it off with `is-scrolling` while scrolling. Removed: the `tuck()` function, the scroll/resize listeners and every `is-tucked` / `is-scrolling` use in `shop.js`; the 0.46.5, 0.49.0 and 0.58.3 CSS rules in `shop.css` (comments left in place) and the matching rules in `critical/home-shop.css`. New 0.69.0 WhatsApp block (after the 0.65.1 block): fixed on the right, 54px round button, no opacity, subtle shadow, gold focus ring; desktop 16px from the corner, tablets (761-991px) 20px + safe area, phones (<= 760px, where the bottom bar shows) `bottom: 64px + 14px + safe area` so it sits just above the bottom bar and never covers it; z-index 950 (above the bottom bar 945, below the order modal 10000 and lightbox 99999); hidden while the mobile menu drawer or the order modal is open. Teaser kept (shows once after 4 s on phones, auto-hides after 6 s, the red dot now hides with it); chat panel unchanged.
- **Home Give today re-balance** (after the 0.67.0 Give rules): photo + heading in a narrower left column, a wider form column (about 1 : 1.45); amount tiles in an `auto-fit` grid with a 128px minimum (desktop), 120px (tablet), 3 across on phones with "Other amount" on its own row (2 across below 360px), so "KES 5,000" and "Other amount" never overflow; the fund select is full width (16px text, 50px tall) so "Where it is needed most" shows in full; step labels on one line; full-width button. Same form, field names and action.
- **Story cards: one arrow.** "Read the story" had the markup arrow (`.lof-card__arrow`) plus the old `sections.css` `.lof-card__link::after " →"`. The `::after` is now switched off wherever the span exists (`.lof-card__link:has(> .lof-card__arrow)`, single-level :has), on every page.
- **Gallery framing:** photo tiles, sensitive tiles and the featured mosaic use `object-position: 50% 28%` (top-weighted, heads stay in frame at about 800px); phones use 4:5 tiles (still all the same size), tablets/desktop stay 4:3.
- **Mobile design system** (new last block in `shop.css`, phones <= 640px with 390px as reference, tablets 641-991px; desktop unchanged): section padding 3.25rem phones / 3.75rem tablets; 20px gutters on phones (16px below 360px), 24px on tablets; section titles `clamp(1.6rem,6.6vw,2.05rem)`, page-hero titles `clamp(1.85rem,8vw,2.3rem)`, card titles 1.12-1.2rem, body line-height 1.6, balanced wrapping; compact page heroes with a stronger bottom overlay and stacked full-width buttons; slider buttons full width, 44px controls; no horizontal overflow (long words wrap, tables in prose scroll inside their box, iframes/video capped, main clips sideways); sticky side columns (story aside, donate info, product gallery) become normal flow on touch screens; form fields 16px (no iOS zoom), 48px tall, full-width submit buttons; mobile menu drawer with 52px items, full-width Donate, own scrolling and bottom padding so the bottom bar never covers the last item; 44px tap targets for footer links, legal links, social icons, contact rows, crumbs, card links, shop buttons, FAQ rows; order modal scrolls inside a 92svh bottom sheet with safe-area padding; footer gets 56px extra bottom room so its last row clears the WhatsApp button; anchor `scroll-margin` accounts for the 72px header. Dark mode and `prefers-reduced-motion` respected. No wording, images, facts or form names changed.
- Files: `assets/js/shop.js`, `assets/css/shop.css`, `assets/css/shop.min.css` (regenerated from source, quoted strings intact), `assets/css/critical/home-shop.css`, `functions.php`, `style.css`, `docs/HANDOVER.md` (new "Mobile design system" section). `node --check assets/js/shop.js` passes. PHP not available in the sandbox: only the version constant changed in functions.php (checked by hand). No `:has()` nested inside `:has()`. Core not changed (stays 0.46.5); media plugin untouched.

## Theme 0.68.0 - 10 Nov 2026
- **Gallery page redesign** (`/gallery/`, `body.lof-page-gallery`; CSS block `0.68.0` in `shop.css` placed straight after the 0.61.1 gallery rules, same block inserted at the matching place in `shop.min.css`; component index updated). The media plugin is untouched: only its output is styled from the theme, existing group titles and captions are reused, no new copy.
  - Featured photos under the hero: four photos fill the mosaic exactly (large + wide + two small) instead of leaving an empty cell; rounded corners, gold focus ring, softer caption gradient.
  - Filter: the pills sit in one white panel and **wrap onto centred rows at every width** (no sideways scrolling, no fade mask); counts as small badges; selected pill Earth Brown with a gold count; gold focus rings. The filter no longer turns into a fixed bar while scrolling (`shop.js` 0.55.1 block skips the Gallery); choosing a filter still scrolls back to the top of the gallery and the filtered section loses its divider (`data-filter` on `.lofm-all`).
  - Sections: centred heading with the gold rules, count line ("10 photos · 2 videos") centred under it, hairline between programmes.
  - Photos: uniform 4:3 tiles, **4 / 3 / 2 per row** (desktop >= 992 / tablet 641-991 / phone <= 640), even gaps (16 / 14 / 10 px), 14px corners, short last rows centred, two-line caption overlay, subtle hover zoom plus an expand badge (hover devices and keyboard focus only), gold focus ring. Lazy loading unchanged (plugin `loading="lazy"`, theme thumbnails/srcset from speed-059).
  - Sensitive images (media plugin `!` prefix, e.g. Elijah's burn photos): blur kept; the "Sensitive image / Tap to view" button is centred above the caption and now really disappears once opened (the plugin's `display:flex!important` had kept it showing).
  - Videos: equal white cards with a 4:5 poster, same 4 / 3 / 2 columns as the photos, caption below in brown. New JS (`shop.js`, block 0.68.0) adds a gold play button over each poster; native controls appear once the video plays, and starting one video pauses any other. Without JS the native controls stay.
  - Lightbox (`.lofx`, existing keyboard/focus-trap/swipe logic unchanged): darker blurred backdrop, rounded photo, caption in a soft pill, counter pill, bordered round buttons with a 3px gold focus ring; fade-in. Phone keeps prev/next at the bottom.
  - `prefers-reduced-motion`: no zoom, fades or button transitions. Dark mode overrides for the panel, pills, count line and video captions.
- No PHP changed except the version constant (PHP not available in the sandbox; functions.php checked by hand). `node --check assets/js/shop.js` passes. No `:has()` nested inside `:has()`. Core not changed (stays 0.46.5).

## Theme 0.67.0 - 10 Nov 2026
- **Stylesheet cleanup (no visual change intended).** `shop.css`, `sections.css`, `main.css` and `critical/home-*.css`: every declaration overridden by a later rule with the identical selector list, the same @media/@supports context and equal or higher `!important` was removed, emptied rules dropped, adjacent identical rules merged. Values that may be browser fallbacks (vendor prefixes, dvh/svh, color-mix, etc.) were kept. Release order was NOT reshuffled (a full regroup by component could change the cascade); instead `shop.css` now opens with a component index listing where each component's blocks live. Sizes: shop.css 187,759 -> 197,637 bytes (includes about 11 KB of new 0.67.0 rules; cleanup alone 183,618), sections.css 262,987 -> 248,027, main.css 14,242 -> 13,798; min files regenerated from source (shop.min 174,141 -> 182,351, sections.min 231,914 -> 211,239, main.min 12,482 -> 11,682); critical home-shop 42,838 -> 40,358, home-sections 36,967 -> 35,356.
- **Verification (script, tinycss2):** for every (at-rule context, selector list, property) key the final winning value and importance before and after are identical (shop 4,510 keys, sections 5,227, main 403, critical 1,327 / 765 / 70), surviving declarations keep their original order, and each regenerated .min.css has the same winners as its source. 10,327 keys whose selectors use class names found in theme/Core PHP and JS were sampled; the only changes are the intended span fix below. Limits: this proves dead declarations were removed; it does not render pages, and a later value a browser cannot parse would previously have fallen back to the removed one (guarded by keeping risky values). The old `sections.min.css` had dropped a space inside a quoted `content` string ("Kenya ·23 May"); regenerating restored "Kenya · 23 May".
- **Trap removed:** the 0.64.0 rule that hid every span in `.lof-market__trust` is scoped to `.lof-market__trust:not(.lof-market__trust--v2) span`; the 0.65.3 workaround rules are gone (same result on the v2 trust cards).
- **Home Give today** (`lof-give--compact`, CSS only): a white card on a cream section instead of the dark band; readable step labels with gold number badges, no grey box behind the eyebrow, amount chips in one even row (5 across, 3 across on phones), fund select and gold button aligned. Same form, field names (amount, amount_other, fund), GET action and KES amounts.
- **Programmes page** (`body.lof-page-programmes`): focus-area links wrap onto centred rows at every width (no sideways scrolling); all 11 programme cards in a 3 / 2 / 1 per row grid with the last row centred, equal heights, 4:3 photos. Home focus areas unchanged.
- **Donate page** (`body.lof-page-donate`, CSS only): trust points as one white card, other ways to give (M-PESA Paybill, bank) as two tidy cards, the Paystack form as a polished card with numbered steps (How often, Currency, Choose an amount), brand-gold selected tiles and focus rings, two-column name/email. Form below 992px comes first, info after. No change to field names, nonces, validation, JS or Paystack calls.
- Core not changed (stays 0.46.5). No PHP changed except the version constant; PHP not available in the sandbox, functions.php edit checked by hand.

## Theme 0.66.0 - 10 Nov 2026
- **Home: duplicate figures removed.** The figures card under the home slider (slider figures bar `.lof-figbar` and any "strip" Impact figures widget) no longer renders on the front page; the full "Our impact" section further down keeps the figures. Inner-page strips are unchanged. Helper `lof_home_hide_impact_strip()` in `inc/template-tags.php`, filter `lof_home_hide_impact_strip` (default true) brings it back. The slider gets class `lof-cine--nofig` so it no longer reserves space for the floating card on desktop; an emptied strip wrapper takes no room; the slider runs straight into Shop with purpose. CSS block `0.66.0` appended to `shop.css`, `shop.min.css`, `critical/home-shop.css`, `critical/home-sections.css`. Importer home layout notes that no strip is added on Home (it already had none).
- **Story video player** (`inc/story-0613.php`): prefers a web-sized sibling `uploads/lof-videos/story-{slug}-web.mp4` over `story-{slug}.mp4`; poster falls back to `story-{slug}-poster.jpg` when the meta poster is empty.
- **Rosemary web video:** new one-off `inc/upgrade-0660.php` (`lof_upgrade_0660_rosemary_web`, admin_init, option `lof_rosemary_web_0660`). Finds the Media Library video whose file name starts with `story-rosemary-web` (video/mp4), sets `lof_story_video` on Rosemary's story (post 564) to its URL, keeps the existing poster, purges LiteSpeed. If the file is not uploaded yet nothing changes and it retries on the next admin load.
- Docs: `docs/HANDOVER.md` rewritten as the single current source of truth; `AGENTS.md` and `README.md` shortened; `CONTENT-GAPS.md` updated (KES default done, pending confirmation).
- Built without PHP in the sandbox: braces/parentheses/strings checked by script only. Run `php -l` on functions.php, inc/template-tags.php, inc/story-0613.php, inc/upgrade-0660.php, inc/importer.php, inc/elementor/widgets-dynamic.php, inc/elementor/widgets-extra.php.

## Core 0.46.5 - 10 Nov 2026
- **KES is the default donation currency** on the Paystack form: listed first and preselected (KES tiers shown by default), USD second. New setting Light of Future > Organisation details > **Default donation currency** (`default_currency`, KES or USD, default KES; added to the fields and defaults) and filter `lof_pay_default_currency`. Default currency list is now `KES,USD`. Payment logic, references, receipts and Paystack calls unchanged.
- **Structured data** (`includes/schema.php`): JSON-LD graph in wp_head from verified facts only: NGO + Organization (name, url, logo, email, telephone, postal address P.O. Box 53-00520 Ruai along Kangundo Road 00520 Nairobi KE, foundingDate 2025-05-23, founder Francis Ouma Sikuku, sameAs Facebook) and WebSite on every page; DonateAction on /donate/; Article on single stories. Skipped when Rank Math or Yoast is active. No Product node (WooCommerce outputs its own). Filters `lof_core_schema_enabled`, `lof_core_schema_facts`, `lof_core_schema_graph`.
- **Media backup** (`includes/backup.php`): Light of Future > Media backup (manage_options). Builds zips with ZipArchive in `uploads/lof-backups/<random 20 chars>/`: the light-of-future-media plugin (photos) in one zip; videos (light-of-future-video-* plugins and uploads/lof-videos) in zips of at most 45 MB (a larger file goes alone); `manifest.json` with every source path, size, sha1 and every zip name, size, sha1. Folders protected by index.html + .htaccess "Require all denied". Built in steps of about 200 s (set_time_limit(300), ignore_user_abort) with automatic continue. Downloads only with a 32-character token valid 48 hours: `/?lof_backup=TOKEN&manifest=1` and `/?lof_backup=TOKEN&file=NAME` (name checked against the manifest list, streamed in 1 MB chunks, no cache). "Revoke token / delete backups" removes files and options (`lof_core_backup`, `lof_core_backup_job`).
- Built without PHP in the sandbox: syntax checked by script only. Run `php -l` on light-of-future-core.php, includes/settings.php, includes/donations.php, includes/schema.php, includes/backup.php.

## 0.65.3 - 10 Nov 2026
- Fix: the shop trust cards from 0.65.2 rendered empty because the 0.64.0 rule `.lof-market__trust span{display:none!important}` also hid the new icon and text wrappers (both spans). Higher-specificity !important rules now show them.

## 0.65.2 - 10 Nov 2026
- Home "Shop with purpose" trust points (Handmade in Kenya, Shopping that gives back, Easy WhatsApp ordering) redesigned as three balanced white cards with icons: gold star-maker icon, gold heart icon, green WhatsApp icon (lof_wa_icon). The existing one-line descriptions are shown again. 3 across on desktop, stacked (max 560px) below 992px. Markup in inc/shop.php (class lof-market__trust--v2); CSS block 0.65.2 appended to shop.css, shop.min.css and critical/home-shop.css.
- Fixed the "Visit the shop" pill on Home rendering as an empty dark pill (label colour now forced to cream).

## 0.65.1 - 10 Nov 2026
- Phone polish after a desktop + 390px review of Home, About Us and Leadership.
- WhatsApp greeting teaser (phones only) now hides itself 6 seconds after it appears and is marked seen for the session, so it no longer sits over content such as Leadership portraits. The chat icon stays.
- Leadership at 640px and below: board and programme-lead portraits are square (max 300px) instead of 4:5, and the founder card stacks with a 4:3 photo (max 320px), so names and roles appear without a long scroll.
- Teaser width capped on phones. CSS appended as block `0.65.1` at the end of shop.css / shop.min.css; JS change in assets/js/shop.js.

## Theme 0.65.0 - 2026-11-10
- **About Us: no sticky chip bar.** The "On this page" row of pill chips (section titles such as "Restoring dignity, creating opportunity" ... "Registration and contacts") was built by `assets/js/shop.js` and styled `position: sticky` (`.lof-jump` in sections.css / shop.css), so it stuck under the header and covered the figures band. It is no longer built; any cached copy is hidden on About and Leadership. The objectives heading and founder photo can no longer stick either.
- **Leadership: no sticky "Jump to" bar.** `initLeaderTabs()` in `assets/js/main.js` inserted a sticky "Jump to: Our founder / Board members / Programme leads / How we are run" bar that appeared to jump while scrolling. Removed; leader cards are still shown at once.
- **About Us redesign (CSS only):** consistent section rhythm and heading hierarchy; Our story as a balanced two-column layout with a framed 4:5 photo and caption pill; What guides us with heading and statement side by side and Vision/Mission as two equal cards; Our values as equal numbered cards, 4 per row with the last row centred (3 / 2 / 1 on smaller screens); Our objectives as one centred list card; founder card balanced and not sticky; Registration and contacts as one clean card of four equal columns (2 x 2 on tablet, stacked on phone).
- **Leadership redesign (CSS only):** founder profile card 2:3 photo/text; Board and Programme leads use one card size with 4:5 portraits, name, board label chip and role with a gold rule, short bio and Read more aligned left; 4 / 3 / 2 / 1 cards per row (desktop >=1200 / 992-1199 / tablet / phone) with shorter rows centred; governance steps and accountability cards tidied.
- No content, names, roles or figures changed; no live Elementor data changed (no one-off upgrade). Core unchanged (0.46.4).
- CSS: new block `0.65.0 About + Leadership` at the end of `assets/css/shop.css` (and `shop.min.css`). shop.css loads on every front-end page; the Home critical CSS is not used on inner pages.

## Theme 0.64.0 - 2026-11-10
- **Home slider:** more cinematic and refined. Smoother 1.1s crossfade with a slow photo settle (off with prefers-reduced-motion), stronger type hierarchy (gold rule eyebrow, balanced headline, 44ch text), pill buttons, slimmer navigation dock with gold progress bars, round prev/pause/next controls with visible focus rings (Hello Elementor pink focus overridden). First slide image still eager / fetchpriority high; pause, keyboard and aria unchanged. Figures card under the slider tidied.
- **Impact figures (Home, About, Impact, inner-page strips):** one professional design everywhere: large gold numerals above uppercase labels, hairline dividers between equal-height cells, a short gold accent line per figure; 4 x 2 on desktop, 2 columns on tablet and phone. Verified-only logic, values and the single source note are unchanged.
- **Stories of change (Home, Impact "Real people, real change", /stories/):** balanced editorial cards: equal heights, 4:3 face-safe photos, topic tag (from the existing story topic) and location, title, three-line excerpt, "Read the story" link with arrow, gentle hover. The /stories/ featured story is a balanced split card. Location pill on the photo removed (it repeated the meta line).
- **Give today (Home only):** compact band: small photo, heading, one line, one trust line, amount chips, fund select and Give button. Same form (GET, same action, field names `amount`, `amount_other`, `fund`). /donate/ is unchanged. Filter `lof_give_compact` turns it off.
- **Our partners:** logos in full original colour (grayscale and opacity removed), larger, on centred white tiles with a subtle border and shadow.
- **Shop with purpose:** moved straight after the home slider, as Cistern of Hope places its Hope Market section directly after the hero. Restyled like Cistern's: cream band, heading left with "Visit the shop" pill right, one row of four equal product cards (2 per row on tablet and phone), one-line trust strip. Live page reordered by the one-off upgrade `inc/upgrade-0644.php` (`lof_upgrade_0640_home_order`, option `lof_home_0640_order`, backup meta `_lof_layout_backup_0640`); importer home layout updated for fresh installs.
- CSS: new block `0.64.0` at the end of `assets/css/shop.css` (and `shop.min.css`); slider and shop parts also appended to `assets/css/critical/home-shop.css` so the first paint matches.

## Theme 0.63.17 - 2026-11-09
- Education 'Handing out books and pens' photo: the school office sign is blurred (it could not be cropped without cutting people). Copied once by `lof_upgrade_0642_media_rev2` (option `lof_media_0642_rev2`).

## Theme 0.63.16 - 2026-11-09
- Consent for the intake-list photos confirmed by the owner (9 Nov). The programme galleries (sanitary pads, education and school materials, food donations, mentorship, clean water, talent development) were already live from the Light of Future Media plugin; this release cleans them up.
- Cropped photos (shipped in `assets/lofm-fix/`, copied once over the media plugin's `assets/photos/` and its Media Library copies by `inc/upgrade-0642.php`, option `lof_media_0642_rev`): food-children (school sign and phone number), edu-books-team (school office sign), and the TECNO phone watermark removed from pads-school, pads-kits, pads-community, pads-thumbs, water-boy, ment-listening, food-family and edu-books.
- Precious: uniform photo stays the main photo; gallery shows the uniform and shoes photos; the guardian/home photo is hidden by CSS. Location stays "Nairobi".
- Eunice's story: Meshack and Silvester's school photo added above her gallery (it was already on the Education page).
- Videos checked: the live books-kibera (32 s) and pads-thanks (72 s) clips no longer contain any child's full name.

## Theme 0.63.15 - 2026-11-09
- Rosemary: the fruit-stall photo itself cuts off the top of her head, so no crop can fix it. Her portrait becomes the main story photo (story top, Impact, Stories, Home cards) and the stall photo moves into the story text. One-off `inc/upgrade-0641.php` (option `lof_rosemary_0641_rev`).

## Theme 0.63.14 - 2026-11-09
- Rosemary's fruit-stall photo framed from the top so her whole head shows (story page, Impact, Stories and Home cards).
- The figures note reads "Figures supplied by the foundation (November 2026)" on every page, About included.

## Theme 0.63.13 - 2026-11-09
- Story video player moved to a front-end file (it was only loaded in wp-admin), so Rosemary's and Hellen's videos replace the Drive buttons.
- Impact figures on Home, About and Impact: one consistent card grid, 4 x 2 on desktop and 2 columns on tablet/phone (the old one-row layout squeezed and overlapped numbers); one source note.
- Story photos framed to keep faces in view; Margaret's videos and gallery stay inside the reading column.
- CSS minifier keeps spaces inside quoted text.

## Theme 0.63.12 - 2026-11-09
- Story pages: centred single reading column (820px), larger lead and body text, uniform 16:9 hero and 4:3 inline photos with centred captions, 2 x 2 photo gallery, balanced video row, Help us do more turned into a full-width call to action at the end.
- Impact figures: balanced 4 x 2 grid (2 columns on phone) with one source note instead of a note on every figure.
- Impact page stories: three equal cards instead of one large and two squeezed cards.
- Includes the 0.63.11 story video player fix.

## Theme 0.63.11 - 2026-11-08
- Story video player: no longer depends on the loop/main-query checks, finds the hosted video by story slug if the meta is missing, and runs late, so Rosemary's and Hellen's videos replace the Google Drive buttons.

## Theme 0.63.10 - 2026-11-08
- Rosemary's and Hellen's story videos now play on the story pages (on-page player with poster) instead of opening Google Drive. Videos are downloaded once by the server from the shared Drive files into uploads/lof-videos on first wp-admin load (retries until both succeed); posters ship with the theme.

## Core plugin 0.46.4 - 2026-11-08
- Facebook link set to the foundation's page (Light of future foundation) by default and filled once if empty, so the footer and social links show it.

## Core plugin 0.46.3 - 2026-11-08
- Settings: M-PESA Paybill, Paybill account, bank name and bank account fields (defaults 522522 / 1354817087 / KCB / 1354817087), so the Donate page's Other ways to give box appears and staff can edit the details.

## 0.63.9 - 2026-11-08
- Donate page: 'Other ways to give' box with M-PESA Paybill 522522 (account 1354817087) and KCB bank transfer (account 1354817087); copy updated for one-time, monthly and yearly and anonymous giving.

## 0.63.8 / Core 0.46.2 - 2026-11-08
- Impact figures: 55 people supported since 2024 and the 2040 vision of 500,000 people; older 200+ / 30+ figures hidden.
- Partners: Suivera and Grace International Foundation with logos.
- Giving details: M-Pesa Paybill 522522, account 1354817087; bank transfer KCB account 1354817087 shown on the Donate page.

## 0.63.7 - 2026-11-08
- Business empowerment stories now publish: Rosemary (fixed: was saved as a draft because consent was recorded after saving) and new story Hellen (fish business, Dandora) with photo (children cropped out) and video link.

## 0.63.6 - 2026-11-08
- New impact story: Rosemary, the first beneficiary of the business empowerment programme (Dandora), with two photos (child cropped out) and a link to her video. Created once in wp-admin.

## Core plugin 0.46.1 - 2026-11-07
- Donation form shows up to 7 preset amounts (was 6), so the $1,000 tile appears.

## Core plugin 0.46.0 - 2026-11-06
- Donation form: US dollars first with $10, $25, $50, $100, $250, $500 and $1,000 tiles plus Other amount (KES still available).
- Gift frequency: One-time, Monthly or Yearly (yearly uses Paystack annual plans).
- Give anonymously option: name not required and never shown; receipts, admin list, CSV and staff notices mark the gift as anonymous.
- Admin preview of the form before Paystack keys are added: /donate/?lof_pay_preview=1 (administrators only).
- References now start with LOF- (old SCC- references still verified); receipt wording no longer mentions Smile.

## 0.63.5 - 2026-11-06
- Footer: centred brand block on phones, Explore and Focus areas become tidy open/close sections with full-width rows (no more squeezed two-column text), smaller logo on desktop, link hover states.

## 0.63.4 - 2026-11-06
- Shop cards: clear gap between View details and Order on WhatsApp; green order button.
- Shop: redesigned How ordering works (icon steps with a connecting line) and a new gift / larger order panel with photo collage, WhatsApp and email buttons.

## 0.63.3 - 2026-11-06
- Phones: a floating WhatsApp greeting appears on the right above the WhatsApp button after 4 seconds, once per visit, and can be dismissed. Still hidden on larger screens and the Gallery.

## 0.63.2 - 2026-11-06
- Shop hero: truly full width on every screen (including large-screen scaling) and exactly fills the first screen below the header; no gap above it.

## 0.63.1 - 2026-11-06
- Shop: new full-width hero with a photo mosaic, gold headline, stats and a floating trust bar; breadcrumb removed on the shop page.
- Shop: Clear filters returns to all products.
- About: founder photo on phones shows Francis in full with his name below the photo.

## 0.63.0 - 2026-11-06
- Shop: search bar, category slider with photos, price filters, sorting and a uniform product grid (inc/shop-063.php).
- Page loader: light rings around the foundation symbol, once per visit, removes itself within about a second even without scripts.
- Home: the first slide stays still (no text animation or progress bar) until the page has finished loading.

## 0.62.2 - 2026-11-06
- Footer: contact tiles no longer inherit no-wrap, so the full email address shows on phones.

## 0.62.1 - 2026-11-06
- Footer: the email address wraps instead of clipping on phones.
- Leadership: page-top photo changed to the goat herd (community enterprise).

## 0.62.0 - 2026-11-06
- Home speed: first-screen styles printed in the head; the full Elementor, sections and shop stylesheets load without blocking; jQuery and the scripts after it are deferred (new inc/speed-062.php, assets/css/critical/).

## 0.61.2 - 2026-11-06
- Stories on desktop: the featured photo shows Margaret's face.

## 0.61.1 - 2026-11-06
- WhatsApp greeting bubble retired (the button stays); it covered content on tablets and desktops.
- Home on phones: all three featured stories show.
- Stories: the featured photo keeps the person's head in frame.
- Programmes on tablets: "Our approach" panels stack into full-width rows.
- Gallery on phones: a short last row of photos is centred.

## 0.61.0 - 2026-11-06
- Large screens (1600px and wider): the design keeps the proportions of a 13-inch laptop instead of shrinking into the middle.
- Small screens: nothing can push a page sideways; tighter side margins below 380px.
- Sitemap: no author pages, blog categories or the default "Hello world" and "Sample page"; those pages are kept out of search results.
- Phone preview tool takes any width (`&w=1920`) for checking layouts.

## 0.60.4 - 2026-11-06
- Home: slider, programme card and story photos served from small theme copies (480/720/800/1200px WebP) at the size shown; first slider photo preloaded.

## 0.60.3 - 2026-11-06
- Home "Who we are" on phones: square crop centred on Francis.
- Programmes on phones: long focus-area names wrap inside their pill.
- Gallery: small photo groups use the same tile size, centred.

## 0.60.2 - 2026-11-06
- Contact (desktop): heading column centred beside the form.
- Programmes: note under the giving form aligned with the form; focus-area links wrap on phones.
- "On this page" links centred when they wrap (About Us and others).
- Gallery: all photo tiles the same size (4 / 3 / 2 per row).
- Talent Development: tighter gap above the yellow banner.

## 0.60.1 - 2026-11-06
- Video rows end level (captions share the row height).
- Story pages: photos and videos span the full page width on desktop; one video per row on phones.
- Gallery on phones: video captions show in full.

## 0.60.0 - 2026-11-05
- Inner pages balanced: media blocks (Gallery, Talent and other programme pages, stories) have centred headings and centred rows of equal video and photo tiles; story pages use one centred reading layout with the side card alongside; section headings and card rows share the same centred width.

## 0.59.0 - 2026-11-05
- Search engines: the live site can no longer be hidden by the "Discourage search engines" setting (filter `lof_force_indexable`).
- Leadership: the top photo is the sanitary pads group photo.
- Speed: WooCommerce styles load only on shop, product, cart, checkout and account pages; block styles only on blog posts; small stylesheets are printed inline; main, sections and shop styles are minified; the page-hero photo is preloaded.
- Images: Gallery thumbnails (400px) and video posters (small WebP, loaded near the screen), Shop artwork (320px copies) and the founder photo (420px) are served at the size shown; product photos ask for the size shown.

## 0.58.3 - 2026-11-05
- Stories (phones): the featured story shows the photo on top and the label, title and button below it, so nothing sits on the photo.
- WhatsApp button (phones): smaller, tucked into the corner, and stays out of the way while scrolling and for 1.6 seconds after.

## 0.58.2 - 2026-11-05
- Donate: the selected "Give once" / "Monthly" option reads cream on dark brown.
- Gallery: video captions take a fixed two-line height so tiles end level; the WhatsApp teaser bubble is hidden on the Gallery.

## 0.58.1 - 2026-11-05
- Donate: all text in the light giving section is dark and readable (it had inherited pale text from the old dark section); shorter amount tiles on phones.
- Gallery: choosing a filter keeps the eight-photo start (Show all still expands); video captions clamp to two lines so tiles line up; the WhatsApp greeting no longer covers videos.
- Phones: homepage approach steps become compact full-width rows; email addresses no longer split mid-address; scrolling chip rows have no visible scrollbar and fade at the edge; the WhatsApp greeting bubble is hidden.
- Photos of people: story cards, the Leadership hero and the Stories/Impact heroes keep faces in frame.

## 0.58.0 - 2026-11-05
- Donate: the Paystack form sits in a light two-column section. Left: "Make your gift today", four trust points, accepted methods (M-PESA, Visa, Mastercard) and a folded "Manage your monthly gift". Right: the gift form on a white card. Phones: form first. No dark slab or empty band.
- Gallery: filter buttons with counts (All plus one per section) instead of the long link list; photo and video counts under each title; eight photos per section with "Show all N photos"; full-screen photo viewer with previous/next, keyboard and swipe; videos as even 4:5 tiles, three per row (two on tablets, one on phones).

## 0.57.0 - 2026-11-05 (Core 0.45.1)
- Gallery: editorial layout. Pill section links, gold-ruled section titles, 4/3/2-column square grid with a large lead photo per section, captions on a soft gradient over the photo, hover zoom, two-up video cards (one per row on phones).
- Phones: page-top photos (Stories, Leadership and every other page hero) fill the full width of the band instead of a narrow photo between dark bars, with a soft fade into the title panel.
- Homepage: the extra "See all focus areas" button is removed (the section keeps its own "See all 11 focus areas").
- Paystack on: "being set up" / "launching soon" giving lines now point to online giving on the Donate page.
- Core 0.45.1: online giving offers KES only until the Paystack account accepts USD (define LIGHT_PAYSTACK_CURRENCIES as 'KES,USD' to turn USD on); both test USD attempts were refused at initialisation.

## 0.56.0 - 2026-11-05 (Core 0.45.0)
- Homepage: "Why we exist" and "Our story" merged into one short "Who we are" block (founder photo, three facts, Read our story); the 4th slider slide (shop) and the closing "Be the light" banner removed (render-time, Elementor data untouched); "See all focus areas" button. Phones: approach steps 2x2, six focus areas, two stories, two shop products.
- Donate (Core 0.45.0): Paystack online giving, ported from the Smile Community Centre module. Once or monthly, KES (500/1,000/2,500/5,000/10,000) or USD (10/25/50/100), optional fee cover, Paystack checkout (card, M-Pesa), server-side verification, receipts, staff notice to info@, Donations list with re-check and CSV export, manage-monthly-gift link, webhook at /wp-json/lof/v1/paystack (HMAC-SHA512 checked). Key from LIGHT_PAYSTACK_SECRET_KEY (or LOF_PAYSTACK_SECRET_KEY) in wp-config.php; sk_test_ keys run in test mode. Result pages are never cached.

## 0.55.3 - 2026-11-05
- Contact: the reply promise reads "within 2 working days" (form intro, "What happens next" and any text saved in the page editor).

## 0.55.2 - 2026-11-05
- Leadership: the added "On this page" row is removed; the page keeps its own jump row (Our founder / Board members ...). About Us keeps the new jump links.

## 0.55.1 - 2026-11-05
- Donate: the "how to give" line is visible on the cream panel; the panel no longer crowds the right edge on phones.
- Gallery: section links stay pinned at the top while scrolling (fixed on scroll; sticky failed inside the page wrapper).
- Leadership: no second set of jump links where the page already has its own "Jump to" row.

## 0.55.0 - 2026-11-05 (Core 0.44.0)
- Donate: while online payment is not live, the button reads "Ask us how to give" with a one-line explanation; the trust line no longer says "coming soon".
- Get Involved: enquiry form adds "Fundraising for the foundation" (Core plugin 0.44.0).
- About Us and Leadership: sticky "On this page" jump links; leader bios show four lines with Read more.
- Phones: shorter page heroes (intro clamped to three lines) and tighter section spacing on every page.
- Programmes: focus-area index is one compact scrolling row; cards without a photo no longer say "Photo coming soon".
- Stories: stories come before the figures strip. Story and focus-area pages get an early Home / All stories (All programmes) link; stories show an "Updated" date.
- Impact: one source note under the figures instead of the same sentence on every card.
- Contact: Call / WhatsApp / Email choices right under the hero.
- Gallery: added to the main menu under Impact (once, option lof_menu_gallery_rev); sticky scrolling section links; Gallery and Shop added to the phone preview.
- Shop: "handmade products" wording; delivery card says cost and timing are confirmed before payment. Product pages: design chips are buttons (select the design, show its photo for bags and sandals, pre-fill the order form); Delivery, Care and Handmade notes.
- Admin: WordPress admin email and the administrator login email become info@lightoffuturefoundation.org, with a test email and a Dashboard notice (option lof_admin_email_rev).
- Speed: uploaded photos over 160 KB resized to 1600 px and re-saved at quality 74 in batches (originals kept as .orig, option lof_shrink_rev); theme photos over 110 KB recompressed (168 KB saved).

## 0.54.0 - 2026-11-05
- Email: the foundation's address becomes info@lightoffuturefoundation.org (header, footer, contact details, enquiry form notifications, shop emails sender and order recipients), applied once on the first wp-admin load (option lof_email_rev). Core plugin default updated too.
- Security: X-Powered-By and X-Pingback headers removed; HSTS (1 year) on HTTPS; pingbacks/trackbacks off; theme and plugin file editors disabled in wp-admin; one generic login error; login lock for 15 minutes after 5 failed attempts from one IP; reviews/comments get a hidden honeypot, a signed time stamp (min 4 s) and a 2-link limit.

## 0.53.0 - 2026-11-05
- Phone bottom bar: Home, Shop, Donate (raised, centre), Stories, Menu. Focus areas move to the Menu (Programmes).
- Beaded Leather Belt: its two designs from the product description (Kenya Flag, Red Wave) now show as design labels and in the order form.

## 0.52.1 - 2026-11-05
- Phones: product pages stack the gallery, thumbnails and summary as plain blocks with the gallery sized by its own content, so thumbnails no longer overlap the title and the blank space above the gallery is gone.
- Product tabs: the "Additional information" tab is removed (About this piece and Reviews remain).

## 0.52.0 - 2026-11-05
- Phone bottom bar: Home, Focus areas, Donate (raised, centre), Shop, Menu. Shop opens /shop/ and is highlighted on shop, product and category pages; Stories stays in the Menu under Impact.
- Product pages match the shop: two-column layout with a sticky photo gallery and thumbnail strip, summary card with category eyebrow, larger price without decimals (KSh 7,500), design chips, full-width Order on WhatsApp button, three trust points; "How ordering works" steps; pill tabs ("About this piece"); "You may also like" related products. SKU line and the hidden cart form removed while ordering is on WhatsApp.

## 0.51.1 - 2026-11-05
- Category pages: the chips heading reads "Shop by category" instead of repeating the category name under the page title.

## 0.51.0 - 2026-11-05
- Shop in the main menu: added once (before Contact) to the menu in the Main menu location on the first wp-admin load (option lof_menu_shop_rev), without rebuilding hand-made menus; also in the setup menu tree and the fallback menu.
- Shop page redesign: dark brand hero with product photo mosaic, page heading, two calls to action and key facts; four-card trust strip; "The collection" heading with category chips (All, Bags, Jewellery, ...); cleaner toolbar (no result count); product card hover; "How ordering works" in three steps; closing gift and bulk order call to action. Phone layout and dark mode included.

## 0.50.2 - 2026-11-05
- Leadership: descriptions start level in every row of board and programme-lead cards (role block heights matched per row on tablets and desktop; role text unchanged). 0.50.1's fixed two-line slot missed the board cards and three-line roles.

## 0.50.1 - 2026-11-05
- Leadership: role labels become a two-line slot (gold rule, no pill) so descriptions start level on every card; role text unchanged.
- Phones: page-top photos show in full in a band above the title (no cropped faces), with the title on a solid dark panel.

## 0.50.0 - 2026-11-05
- Impact figures sit in one row on desktop (3 per row on tablets, 2 on phones) as a single framed band with dividers, gold accents and even sizing, on every page that shows them.
- Page heroes: a different photo from the November content on every page (needs light-of-future-media 1.0.4 to attach them); story and focus-area pages use their featured photo as the hero background.
- Leadership: portrait cards with consistent 4:5 photos, role pills, hover lift and a stronger founder card.

## 0.49.0 - 2026-11-05
- WhatsApp chat button: compact round button (52px desktop, 46px phone, label as hover/focus tooltip), no pulse ring; it steps aside while the visitor scrolls and returns 0.7s after scrolling stops, so it never sits on text being read. Still above the phone bottom menu.

## 0.48.1 - 2026-11-05
- Phones: slide text shows in full instead of being cut off with an ellipsis.

## 0.48.0 - 2026-11-05
- Phones: shorter home slider (16:10 photo, tighter type, 3-line text) so its buttons sit clear of the bottom menu.
- Homepage polish: consistent section spacing and heading rhythm; gold eyebrows on dark sections (Our impact, Give today); approach cards hover; founder photo keeps Francis in frame on phones; focus-area cards with even 16:10 photos, hover lift and clearer titles.

## 0.47.0 - 2026-11-05
- Home slider: four slides (adds "Shop with purpose" with the bags photo, linking to the shop), applied once in wp-admin (option lof_hero_rev 4, layout backed up). New look: glass tab dock with photo thumbnails and progress bars, glass controls, gold accent rule beside the text, refined title size and shading; tab dock on tablets; slim progress bars on phones.

## 0.46.6 - 2026-11-05
- Screen readers: Details / View details links include the product name; order form announced as "Order on WhatsApp, <product>"; quantity buttons named Decrease/Increase quantity; empty required fields get aria-invalid and point to the error message; the rest of the page is inert while the order form is open.

## 0.46.5 - 2026-11-05
- Phones: the floating WhatsApp button slides in once the visitor scrolls 160px, so it no longer covers hero content such as the Stories "2025 / Officially registered" tile on first load. Keyboard check (homepage, shop, product, order form, chat, Donate, Contact) passed with no code changes needed.

## 0.46.4 - 2026-11-05
- Contrast: Stories spotlight eyebrow on gold now dark brown (the light-mode eyebrow rule had higher specificity than the 0.46.3 fix).

## 0.46.3 - 2026-11-05
- Contrast: leadership role labels, the Programmes give-section eyebrow (on dark brown) and the Stories spotlight eyebrow (on gold) now meet WCAG AA. Site-wide axe audit (33 pages, desktop and phone) otherwise clean once fade-in animations finish.

## 0.46.2 - 2026-11-05
- Contrast: section eyebrows, CTA earth button text, footer Donate text and the floating WhatsApp button (now deep WhatsApp green #075E54) meet WCAG AA.

## 0.46.1 - 2026-11-05
- Order buttons keep "Order" / "Order now" / "Order on WhatsApp" in their accessible name on phones (visible labels aria-hidden, full screen-reader label with product name).

## 0.46.0 - 2026-11-05
- Homepage flow: founder story shortened to two paragraphs (facts unchanged, quote removed from the homepage block); the second "Who we are" block, which repeated the About text, removed. Runs once in wp-admin (option lof_home_flow_rev), layout backed up in _lof_layout_backup.
- Focus-area cards use short display names (Decent Houses, GBV Awareness, Hygiene Kits for Girls); full titles stay on programme pages. Card titles reserve two lines on desktop.
- Order form: style picker for the Leather Bag (Tan satchel, Red tooled flap bag, Red barrel bag) and the Sandals (5 styles).
- Accessibility (axe-core, WCAG 2.1 AA): kicker, breadcrumb, eyebrow and price contrast fixed; Order buttons' accessible names now include their visible text; chat in an aside landmark with a name on phones; product tag links underlined; one skip link with a real main#content target on WooCommerce pages.

## 0.45.0 - 2026-11-05
- Focus areas: 8 new photos from the November content intake (food packs, Ugenya borehole handwashing point, sanitary pads bought in bulk, Elijah's finished house, Margaret's solar light, goats, mentorship hall, awareness session), set once in wp-admin (option lof_prog_photos_rev). Prison Outreach still has no photo.
- Section h2 headings and heads now run full width (no max-width).
- Shop with purpose redesigned: 4:5 product photos, refined deep-green Order now button, Details link, three value cards.
- WhatsApp order form popup: every Order button (home, shop, product page) opens a form (design, quantity, name, delivery town, note) that sends a ready-made order on WhatsApp. Other WhatsApp links open the chat card first.
- Sticky header: scroll-padding-top and larger scroll margin on product pages so the product name stays visible.

## 0.44.1 - 2026-11-05
- Phone fixes for the crafts shop: shop and homepage product cards are equal height with buttons pinned to the bottom; WhatsApp buttons no longer wrap (short "Order" label on phones, full label for screen readers); product-page Order on WhatsApp button is full width on phones.
- WhatsApp chat: smaller teaser on phones and no automatic "Hello there" pop-up on phones (red dot only on larger screens); button still sits above the bottom tab bar.
- Product title and order button keep clear of the sticky header (scroll-margin).
- WooCommerce "Coming soon" switched off in wp-admin (Settings > Site visibility > Live); shop is public.

## 0.44.0 - 5 Nov 2026

Crafts shop, modelled on the Cistern of Hope Hope Market.
- New `inc/shop.php`: WhatsApp chat card on the right (greeting, topic chips, page-aware "I would like to order" chip on product pages, message box, typing dot, one-per-visit teaser; replaces the plain `lof-wa` float). Number from the Core setting, else +254 715 394 295.
- Order on WhatsApp: big button on product pages (adds design, quantity, price and link), small button on every shop card. Online checkout stays off until Paystack is live (`lof_shop_cart_enabled` filter); cart buttons hidden, cards say "View details". Random order on the shop page; intro band.
- Home "Shop with purpose": shortcode `[lof_home_market]` (featured products first, topped up), added before the closing CTA by one-off `lof_upgrade_home_market` (option `lof_market_rev`).
- New admin-only `inc/shop-setup.php`: one-off `lof_upgrade_shop_products` (option `lof_shop_rev`) creates the 8 catalogue products with cleaned photos (`assets/images/shop/`), confirmed prices, categories, featured flags (bags, basket, necklaces, sandals) and Design variations for price ranges; skips existing SKUs; sets currency to KES.
- New `assets/css/shop.css`, `assets/js/shop.js`.

## 0.43.1 - 4 Nov 2026

- Home page: fixed sections repeating / the brown impact band drawing over the focus cards. Cause: 0.43.0 set overflow-x: clip on the html and body elements, which changes how Chrome scrolls the page; it now sits on the main content and footer only.
- Accessibility (axe-core audit of Home, Programmes, Stories, Donate in light and dark, desktop and phone): small gold labels, figure numbers and programme jump numbers darkened to pass WCAG AA contrast; the logo link has a name in dark mode; the top contact bar is a labelled region.

## 0.43.0 - 4 Nov 2026

- Content check against the foundation's content document: everything is on the site (history, vision, mission, 7 values, registration details, main and specific objectives, 11 focus areas, all 11 leaders with roles, bios and photos). Added the missing detail that the foundation is registered as a charitable society.
- Same look on every desktop: the desktop layout now starts at 10-inch tablets held sideways (992px); text size and content width are fixed, so a 13.3in laptop, MacBooks and large monitors show the same design (larger screens only get wider side margins). Header compacts to fit on one line on small laptops and tablets.
- Mobile pass: no sideways scrolling, long words and emails wrap, full-width buttons with larger tap areas, tighter spacing; portrait tablets show cards two per row.

## 0.42.0 - 4 Nov 2026

- Page heroes: branded dark that pops, in the Cistern of Hope style. Photo banners use a warm brand-brown shade with a soft gold glow (instead of grey-black); any page without a photo gets the same dark brown banner with gold glows, so every inner page header matches. White title, gold label and breadcrumb, gold underline and a gold line along the bottom.

## 0.41.0 - 4 Nov 2026

- Brand colours made calmer and consistent across the site. Dark brown is now used only for the footer, the figures band, the photo banners at the top of pages and the donation panel; the FAQ help card, "What happens next", Donate's "Transparent and accountable", About's "Registration and contacts" and Contact's "Other ways to reach us" are light cards with a gold top edge. Softer sand background, one consistent colour for small gold labels, and a slightly softer gold call-to-action box.

## 0.40.2 - 3 Nov 2026

- Cross-check of the "Our story" and "Our impact" changes: stylesheet validated (no errors), and the Our story photo panel keeps a minimum height on laptops so the portrait never shrinks.

## 0.40.1 - 3 Nov 2026

- Home, "Our impact": much shorter band. The heading sits on the left with its short intro on the right on one line, the four figure cards are slimmer with slightly smaller numbers, and the section padding is reduced. Two figures per row on phones.

## 0.40.0 - 3 Nov 2026

- Home, "Our story": redesigned as one clean card. Francis's portrait fills the left panel from top to bottom with his name and title on a soft dark band; the heading, letter, quote, a simple 20 / 2021 / 2025 timeline and the "Read our story" button sit on the right. The arch frame, offset gold outline, floating name tag and separate timeline box are gone. On tablets and phones the photo sits on top of the card.

## 0.39.1 - 3 Nov 2026

- Theme picture (Appearance > Themes): the preview image is now a real view of the live home page - top bar, menu with Donate button, the Philister slide and the 200+ / 30+ / 11 / 2025 figures card.

## 0.39.0 - 3 Nov 2026

- Home page balance pass: even spacing between all sections; "Why we exist" heading and statement aligned with equal-height Vision and Mission cards; "Stories of change" lead story and the two side stories now share the same height, with side stories readable (photo 42%, larger title); "Give today" heading fits on two lines; focus-area cards equal height with the link at the bottom; "See all 11 focus areas" and "More stories" as rounded pill buttons.

## 0.38.0 - 3 Nov 2026

- Home, "Our story": the founder's letter sits on the left and his portrait on the right at a matching height; underneath, the quote card (left) and a milestone timeline card with 20 / 2021 / 2025 joined by a gold line (right, under the photo) share one row, so the right side is no longer empty. Stacks as before on tablets and phones.

## 0.37.1 - 3 Nov 2026

- Footer: back to a single row of four columns on laptops (logo and description | Explore | Focus areas | Support our work). The support card is a neat vertical stack with the "Support the Future" button on one line and the volunteer link centred under it. Two columns on tablets, stacked on phones.

## 0.37.0 - 3 Nov 2026

- Page heroes (About, Leadership, Programmes, Talent Development, Stories, Impact, Get Involved, Donate, Contact, FAQ): the foundation's photo now fills the whole banner behind the title with a dark brown overlay, white title, gold label and breadcrumb, and a thin gold line along the bottom. Banners are shorter (about 280-400px on laptops, 240px on phones), in the style of the Cistern of Hope site. Leadership keeps its "At a glance" card on top of the photo.

## 0.36.0 - 3 Nov 2026

- Closing call to action ("Be the light in someone's future", on most pages): the small buttons on the right are now a dark donation panel with a "Make a difference today" label, a large gold Donate button with a heart icon, an outline Get involved button and a "Registered NGO, Kenya - 23 May 2025" trust line. Larger heading on the left. Stacks on phones. The footer's "Every gift lights a future" card uses the same button style.

## 0.35.1 - 3 Nov 2026

- Footer: "Decent Houses for Widows, Widowers and Vulnerable Families" no longer runs into the support card (long names wrap). New balanced layout: logo, description and badge top left with the "Support our work" card directly under it, then Explore and Focus areas as two full columns. Larger logo, compact support card with button and volunteer link side by side, wider address cell in the contact bar. Tablet: brand and support on top, the two link lists below.

## 0.35.0 - 3 Nov 2026

- Typography: larger, easier-to-read text across every page, in line with the Cistern of Hope site. Base size 17px on phones, 17.5px on laptops and 18px on large screens; paragraphs at least 16px; small labels at least 13px; bigger section headings and page titles; larger menu, buttons and footer links. Phone headings capped so they never overflow.

## 0.34.1 - 3 Nov 2026

- Fix: on Donate, "Transparent and accountable" showed pale text on a white background. The three cards (Registered, Accountable, Community-led) now sit on a dark brown panel with gold titles and cream text, readable in light and dark mode.

## 0.34.0 - 3 Nov 2026

- About, "What we set out to do": heading centred above; the seven objectives sit in one white panel with two even columns, gold number tiles and thin dividers, and a gold "Registered with the NGO Board of Kenya on 23 May 2025" cell completes the last row so the panel ends square. One column on phones.
- About, "Registration and contacts": the four labels (Registered, Charitable purpose, Leadership, Postal address) were invisible on the dark band and now show in gold.

## 0.33.0 - 3 Nov 2026

- Footer: Call / Email / Visit as one joined bar with dividers and larger gold icons; four balanced columns (brand with a larger logo, Explore in two even columns, Focus areas, and Support our work as a highlighted card with a full-width button); headings with a gold underline; bottom bar with copyright, Privacy Policy / Terms of Use and Back to top on one line. Tablet shows two columns; on phones the two link lists sit side by side and the support card spans the width.

## 0.32.0 - 3 Nov 2026

- Form sections ("Ready to give?" on Donate, "Take the first step" on Get Involved, "Write to us" on Contact): the heading and contact details now sit beside the form at the same height, and "What happens next" runs full width underneath as three steps side by side, so the left column no longer hangs below the form.

## 0.31.0 - 3 Nov 2026

- Leadership: no more Board / Programme leads switch. Everyone is shown on one page in order (founder, board members, programme leads, how the foundation is run). A simple "Jump to:" bar with plain buttons stays at the top while scrolling; on phones it scrolls sideways.
- About: values text slightly larger and easier to read.

## 0.30.0 - 3 Nov 2026

- Programmes: focus areas without photos now really show the brown and gold icon tile (an older style kept them pale beige); a row of "jump to" chips (01 Education ... 11 Prison Outreach) above the cards scrolls straight to each focus area and highlights it; even spacing between card rows; tidier card footer.
- "From relief to self-reliance": the third card gets its own icon instead of an empty gold square.
- Impact: larger figures on a warm gold-lit band; six focus-area tiles use the new design.
- Stories and Talent Development: featured story height and section widths aligned.

## 0.29.0 - 3 Nov 2026

- Forms (Contact, Get Involved, Donate): a dark "What happens next" card with a three-step timeline fills the left column; the form sits in one raised card with clearer labels and a gold pill submit button.
- Contact: details appear once, in a dark "Other ways to reach us" band with three columns and a "Get directions" button (opens Google Maps), instead of repeating beside the form.
- Donate: "Transparent and accountable" becomes a dark trust band to break up the run of card grids; the repeated figures strip near the form is hidden so the page flows from the FAQ straight to "Talk to us".

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
