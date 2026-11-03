# Accessibility and speed audit - 3 Nov 2026 (0.21.0 -> 0.22.0)

## Method and limits
Static audit of the live HTML of 10 pages (home, programmes, stories, donate, about-us, leadership, contact, faq, get-involved, impact) fetched signed out: images (alt, width/height, lazy loading), heading order, one h1, link and button names, duplicate IDs, lang, meta description, CSS/JS count, asset size, compression and caching headers. Lighthouse/PageSpeed and axe could not run from the build environment (API quota, no headless browser); run https://pagespeed.web.dev/ after launch.

## Results
| Check | Result |
|---|---|
| Server response (HTML) | 0.39-0.61 s, 46-83 KB per page |
| Compression / caching | Brotli on CSS/JS, `Cache-Control: public, max-age=31536000` on static files (LiteSpeed) |
| Theme CSS / JS (compressed) | sections.css 22.7 KB, main.css 3.7 KB, main.js 4.2 KB |
| Images | All have alt text and width/height; all below the fold lazy-load; the slider's first photo loads eagerly with fetchpriority=high (LCP) |
| Headings | One h1 per page, no skipped levels |
| Links / buttons | All named (logo links named by the logo's alt text) |
| Duplicate IDs | None |
| lang | Set |
| Meta description | **Missing on all pages** - fixed in 0.22.0 |
| jQuery Migrate | Loaded but unused - removed on the front end in 0.22.0 |
| Focus visibility | Hello Elementor pink focus on buttons - global gold focus ring added in 0.22.0 |
| Links in body text | Underlined in prose and legal pages (not colour alone) |
| Footer link target size | Raised to 32px minimum |
| Known | jQuery + Elementor scripts are still render-blocking (Elementor requirement); 10 stylesheets (Hello + Elementor + ours) |

Earlier audit (30 Oct, 0.6.0) findings remain fixed: main landmark, skip link, contrast tokens, motion controls, mobile menu focus trap.
