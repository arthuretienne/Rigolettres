#!/usr/bin/env bash
# Push a Code Snippet to rigolettres.fr via REST API.
#
# Usage:
#   ./push.sh <file.php> "<snippet name>" "<description>" [scope] [priority]
#
# If a snippet with the same name exists, it's PATCHed. Otherwise created.
# Strips leading <?php line automatically.
#
# Requires: jq, curl.

set -euo pipefail
export LANG=en_US.UTF-8
export LC_ALL=en_US.UTF-8

FILE="${1:?missing file}"
NAME="${2:?missing name}"
DESC="${3:-}"
SCOPE="${4:-global}"
PRIORITY="${5:-10}"

BASE="https://rigolettres.fr/wp-json/code-snippets/v1/snippets"
AUTH="aetiennea@gmail.com:plKg G0zW cVAy tU2g VsdX Hp6X"

PHP_CODE=$(tail -n +2 "$FILE")

# Find existing snippet by name
existing_id=$(curl -sS -u "$AUTH" "$BASE?per_page=100" | jq -r --arg n "$NAME" '.[] | select(.name == $n) | .id' | head -1)

body=$(jq -n \
  --arg name "$NAME" \
  --arg desc "$DESC" \
  --arg code "$PHP_CODE" \
  --arg scope "$SCOPE" \
  --argjson priority "$PRIORITY" \
  '{name: $name, desc: $desc, code: $code, tags: ["rigolettres"], scope: $scope, active: true, priority: $priority}')

if [[ -n "$existing_id" && "$existing_id" != "null" ]]; then
  echo "↻ Updating snippet id=$existing_id ($NAME)"
  code=$(curl -sS -X PATCH -u "$AUTH" \
    -H "Content-Type: application/json" \
    -d "$body" \
    -o /tmp/snippet-resp.json \
    -w "%{http_code}" \
    "$BASE/$existing_id")
  id="$existing_id"
else
  echo "＋ Creating new snippet ($NAME)"
  code=$(curl -sS -X POST -u "$AUTH" \
    -H "Content-Type: application/json" \
    -d "$body" \
    -o /tmp/snippet-resp.json \
    -w "%{http_code}" \
    "$BASE")
  if [[ "$code" == "201" || "$code" == "200" ]]; then
    id=$(jq -r '.id' /tmp/snippet-resp.json)
  else
    id=""
  fi
fi

if [[ "$code" != "200" && "$code" != "201" ]]; then
  echo "✗ HTTP $code"
  cat /tmp/snippet-resp.json | head -c 500
  echo ""
  exit 1
fi

# Ensure active (PATCH sometimes auto-deactivates when code changes)
if [[ -n "$id" ]]; then
  curl -sS -X POST -u "$AUTH" "$BASE/$id/activate" -o /dev/null
fi

jq --arg id "$id" '{id: .id, name: .name, active: .active, code_error: .code_error, code_length: (.code | length), modified: .modified}' /tmp/snippet-resp.json
