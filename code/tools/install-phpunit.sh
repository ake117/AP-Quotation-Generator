#!/usr/bin/env bash
# Download the PHPUnit phar used by the gate. Deliberately not committed: a 6 MB
# binary in git ages badly, and the version is pinned here instead.
set -euo pipefail

VERSION="${1:-12.5.40}"
DEST="$(cd "$(dirname "$0")" && pwd)/phpunit.phar"

if [ -x "$DEST" ] || [ -f "$DEST" ]; then
    php "$DEST" --version
    exit 0
fi

curl -fsSL -o "$DEST" "https://phar.phpunit.de/phpunit-${VERSION}.phar"
php "$DEST" --version
