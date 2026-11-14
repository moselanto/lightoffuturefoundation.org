# AGENTS.md

**Read `docs/HANDOVER.md` first (section 1 = live state, section 9 = pending work), then `docs/SEO-AEO-GEO.md` for SEO, analytics and AI-discovery work.** It is the single, current source of truth (status, rules, code map, release steps, traps, pending items). History lives in `CHANGELOG.md`.

## Key standing rules (full list in HANDOVER section 3)
- Never invent facts, figures, quotes, prices, partners or payment details.
- No new site wording without the foundation's approval.
- Safeguard children's identities: consent first; stories stay draft until consent is recorded.
- Theme = presentation; Core plugin = data and logic.
- Escape all output; wrap strings in `__()` with `light-of-future` (theme) or `light-of-future-core` (plugin).
- No secrets in git (Paystack keys only in `wp-config.php`).
- Commit directly to `main`; no branches or PRs.
- Never use "Apply latest design" (it times out). Ship page/content changes as one-off upgrades on `admin_init` inside the `is_admin()` block, guarded by an option.
- Never reinstall `light-of-future-media` from the repo (photos live only on the server).
- Build release zips in the same chat session that uploads them.
- Run `php -l` on changed PHP when PHP is available; otherwise check syntax by hand and say so.

## Every release
1. Bump versions (theme `LOF_THEME_VERSION` + `style.css`; Core header + `LOF_CORE_VERSION`).
2. **Append a CHANGELOG entry** (newest first) **and a HANDOVER status update** (sections 1 and 9).
3. Commit to `main`; install via wp-admin uploads, open the Dashboard once, purge LiteSpeed, check signed out.
