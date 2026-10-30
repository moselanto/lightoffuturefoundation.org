# Accessibility and speed audit - 30 Oct 2026 (v0.5.0 -> v0.6.0)

## Method and limits
- Google PageSpeed Insights (Lighthouse) was tried for 6 pages x mobile/desktop: the free API quota was exhausted (HTTP 429), so no Lighthouse scores are reported.
- A headless Chrome run with axe-core could not start in the build environment (missing system libraries).
- Instead: a static WCAG 2.2 AA check of the live HTML of 12 pages (landmarks, headings, alt text, link/button names, form labels, duplicate IDs, zoom, skip link), WCAG contrast maths for every text/background colour pair in light and dark themes, a review of asset weight, compression and caching headers, and a code review of keyboard and motion behaviour. Live click-throughs (menus, theme switch, mobile menu) were done in a real browser.
- Re-run Lighthouse from https://pagespeed.web.dev/ once launched (needs search indexing on for some checks) for official scores.

## Findings and fixes
| # | Finding | Severity | Fix in 0.6.0 |
|---|---|---|---|
| 1 | Elementor full-width pages had no `<main>` landmark, so the "Skip to content" link had no target | High | `<main id="content">` wrapper (`inc/a11y.php`) |
| 2 | Programmes, Stories, Leadership jumped from h1 to h3 | Medium | Visually hidden h2 when a section has no visible heading |
| 3 | Link colour #A5620A failed on cream (4.46:1) and sand (4.05:1) | Medium | #8A5208 (5.9:1 / 5.4:1); gold in dark mode (9.9:1) |
| 4 | Amber #DC8C13 used as text on white/cream (2.5-2.7:1): card numbers, roles, labels, impact numbers on light | Medium | Switched to the link token |
| 5 | Gold "sunrise" band ended in amber; dark text 3.6:1 (light) / 3.2:1 (dark) at the end | Medium | Lighter gradient ends; all text >= 4.97:1 |
| 6 | Moving word ribbon had no pause for keyboard/touch users (WCAG 2.2.2) | Medium | Pause/play button; static with reduced motion |
| 7 | Hero badge floated forever | Low | Two cycles only |
| 8 | Theme switch changed its own name and state together (confusing for screen readers) | Low | Fixed name "Dark mode" + aria-pressed |
| 9 | Gold focus ring invisible on gold sections | Low | Dark focus ring on gold, yellow on brown |
| 10 | Footer/top-bar links below 24px target size (WCAG 2.2 2.5.8) | Low | Min 28px height |
| 11 | Mobile menu let focus leave the open panel | Low | Focus cycles within the panel; Escape closes |
| 12 | No compression on HTML/CSS/JS, no browser caching headers (Apache) | High (speed) | gzip + 1-year caching rules written to .htaccess between "Light of Future performance" markers on Apply latest design |
| 13 | Header loaded both light and dark logos (2 x 62 KB SVG) | Medium (speed) | Dark logo lazy-loaded, footer logo lazy (SVG minifying reverted in 0.6.1 - it distorted the artwork) |
| 14 | Emoji script, shortlink, RSD, WLW links in head | Low (speed) | Removed |
| 15 | jQuery + migrate render-blocking in head (needed by Elementor) | Known | Left as is; Elementor depends on it |

## Passed
Single h1 per page, all images have alt text, all buttons/links named, all form fields labelled, no duplicate IDs, zoom allowed, lang set, reduced-motion respected, body text 15:1 (light) and 14.8:1 (dark), buttons 8.5-9:1, HTML pages 33-66 KB, server response 0.3-0.5 s.
