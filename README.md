# Light of Future Foundation website

WordPress + Elementor site for **Light of Future Foundation** (Nairobi, Kenya) at https://lightoffuturefoundation.org/.

| Path | What |
|---|---|
| `wp-content/themes/light-of-future/` | Child theme of Hello Elementor: presentation only. |
| `wp-content/plugins/light-of-future-core/` | Core plugin: content types, settings, forms, Paystack donations, structured data, media backup. |
| `scripts/build-zips.sh` | Builds the theme and plugin zips. |
| `docs/HANDOVER.md` | **Start here**: current status, rules, code map, release steps, pending items. |

Not in git (server only): the `light-of-future-media` and `light-of-future-video-*` plugins and `wp-content/uploads/lof-videos/`. Back them up with Light of Future > Media backup.

## Install order (fresh site)
1. Elementor (free), then WooCommerce.
2. Upload and activate `light-of-future-core.zip`.
3. Upload and activate `light-of-future.zip` (Hello Elementor installs automatically).
4. Appearance > Light of Future setup > Run setup (once, on a fresh site only).
5. Light of Future > Organisation details: confirm contact details. Paystack keys go in `wp-config.php`.

Updates: upload the zips with "Replace current with uploaded", open the Dashboard once, purge LiteSpeed (details in `docs/HANDOVER.md` section 8).

## Build
```bash
bash scripts/build-zips.sh
```

Commits go directly to `main`. Also read `ARCHITECTURE-ESSENTIALS.md`, `PRD.md`, `CONTENT-GAPS.md`.
