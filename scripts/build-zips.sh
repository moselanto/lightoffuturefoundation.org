#!/usr/bin/env bash
# Build clean installable zips for the theme and Core plugin into dist/.
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
DIST="$ROOT/dist"
mkdir -p "$DIST"
rm -f "$DIST/light-of-future.zip" "$DIST/light-of-future-core.zip"

if command -v php >/dev/null 2>&1; then
  echo "PHP lint..."
  find "$ROOT/wp-content" -name '*.php' -print0 | xargs -0 -n1 php -l >/dev/null
else
  echo "php not found: skipping lint (run on staging or CI)."
fi

EXCLUDES=(-x '*.DS_Store' -x '*/node_modules/*' -x '*.log' -x '*/.git/*')
(cd "$ROOT/wp-content/themes" && zip -rq "$DIST/light-of-future.zip" light-of-future "${EXCLUDES[@]}")
(cd "$ROOT/wp-content/plugins" && zip -rq "$DIST/light-of-future-core.zip" light-of-future-core "${EXCLUDES[@]}")
echo "Built:"; ls -lh "$DIST"
