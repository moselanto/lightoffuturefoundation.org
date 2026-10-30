# Architecture essentials

## The stack

**WordPress** + **Hello Elementor** (parent) + **light-of-future** (child theme) + **Light of Future Core** (plugin) + **Elementor** (layouts) + optional **WooCommerce** (crafts shop).

## The one rule

**Presentation in the theme, data in the plugin.** Programmes, stories, leaders, partners, reports, impact figures, organisation details, forms and payment logic live in the plugin so they survive a theme change.

## Where things are

| I want to change... | Go to |
|---|---|
| Colours, fonts, spacing | `themes/light-of-future/assets/css/main.css` (`:root` tokens), `inc/brand.php` |
| Elementor global colours/fonts | Elementor > Site Settings (seeded once by `inc/elementor.php`) |
| Header / footer (no Elementor Pro) | `template-parts/header.php`, `template-parts/footer.php` |
| Logo, favicons | `assets/images/brand/`, `assets/images/icons/`, or upload a Custom Logo |
| Front-end behaviour | `assets/js/main.js` |
| Security headers | `inc/security.php` (each feature has an off-switch filter) |
| Phone, email, social links, donate button | WordPress: Light of Future > Organisation details (`plugins/light-of-future-core/includes/settings.php`) |
| Content types | `plugins/light-of-future-core/includes/post-types.php` |
| Fields, consent gate, impact figures | `plugins/light-of-future-core/includes/meta.php` |

## Content types (Core plugin)

`lof_programme` (/programmes/), `lof_project` (/projects/), `lof_story` (/stories/), `lof_resource` (/resources/) are public with archives. `lof_leader`, `lof_partner`, `lof_metric` are admin-managed and rendered by widgets.

## Safeguards built in

- Impact stories cannot be published until consent is ticked; consent fields are private (not in REST).
- Impact figures only render when marked verified and have a value.
- Empty organisation details are hidden, never faked.
- Payment mode defaults to "Not set up".

## Never do

- Put keys or passwords in code (use `wp-config.php` constants).
- Echo request data without escaping, or build SQL by joining strings.
- Publish children's names, locations or identifying details.
- Present placeholder data as fact.
