# Light of Future Foundation website

WordPress + Elementor website for **Light of Future Foundation** (Nairobi, Kenya), to be developed on the new domain **https://lightoffuturefoundation.org/**.

The previous site, https://lightoffuture.org/, is a **read-only content source**. The organisation has no access to it or its developer, so nothing here depends on it for hosting, login or domain management.

## What is in this repo

| Path | What it is |
|---|---|
| `wp-content/themes/light-of-future/` | The theme (child of Hello Elementor). Presentation only: design tokens, header, footer, styles, Elementor integration. |
| `wp-content/plugins/light-of-future-core/` | Companion plugin. Programmes, projects, impact stories, leaders, partners, reports, verified impact figures and organisation details. Survives a theme change. |
| `docs/` | Requirements matrix, legacy content capture, brand notes. |
| `scripts/build-zips.sh` | Builds clean theme and plugin zips in `dist/`. |

## Install order (staging)

1. Install and activate **Elementor** (free). Elementor Pro is optional; without it the theme's own header and footer are used.
2. Upload `light-of-future-core.zip` (Plugins > Add New > Upload) and activate.
3. Upload `light-of-future.zip` (Appearance > Themes > Add New > Upload). WordPress installs **Hello Elementor** (the parent) automatically. Activate **Light of Future Foundation**.
4. Go to **Light of Future > Organisation details** and confirm the contact details.
5. Optional later: WooCommerce (crafts shop), chosen payment gateway.

## Build

```bash
bash scripts/build-zips.sh
```

## Read before changing anything

`AGENTS.md`, `ARCHITECTURE-ESSENTIALS.md`, `PRD.md`, `CONTENT-GAPS.md`.
