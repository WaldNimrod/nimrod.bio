#!/usr/bin/env python3
"""Generate MU plugin runtime redirects for nginx-served dev hosts."""
from __future__ import annotations

from pathlib import Path

from _lib import DECISIONS_PATH, REPO_ROOT, load_decisions, quote_slug

OUTPUT_PATH = REPO_ROOT / "nimrod.bio" / "wp-content" / "mu-plugins" / "nb-v200-runtime-redirects.php"


def norm_encoded_path(slug: str) -> str:
    encoded = quote_slug(slug)
    return f"/{encoded}/"


def main() -> int:
    rows = load_decisions().get("decisions", [])

    redirect_rows = [row for row in rows if row.get("decision") == "redirect"]
    drop_rows = [row for row in rows if row.get("decision") == "drop"]

    # Keep deterministic ordering and avoid edge collisions.
    redirect_rows.sort(key=lambda row: len(row.get("slug", "")), reverse=True)
    drop_rows.sort(key=lambda row: len(row.get("slug", "")), reverse=True)

    redirect_pairs: list[tuple[str, str]] = []
    for row in redirect_rows:
        slug = (row.get("slug") or "").strip()
        target = (row.get("new_url") or "").strip()
        if not slug or not target:
            raise RuntimeError(f"Invalid redirect row: {row.get('id')}")
        if not target.startswith("/"):
            target = "/" + target
        if not target.endswith("/"):
            target += "/"
        redirect_pairs.append((norm_encoded_path(slug), target))

    drop_paths: list[str] = []
    for row in drop_rows:
        slug = (row.get("slug") or "").strip()
        if not slug:
            raise RuntimeError(f"Invalid drop row: {row.get('id')}")
        drop_paths.append(norm_encoded_path(slug))

    redirect_lines = "\n".join(
        f"        '{src}' => '{dst}'," for src, dst in redirect_pairs
    )
    drop_lines = "\n".join(f"        '{path}'," for path in drop_paths)

    output = f"""<?php
/**
 * Plugin Name: NB V200 Runtime Redirects
 * Description: Runtime 301/410 enforcement for V200 migration on nginx-served hosts.
 * Version: 1.0.0
 * Author: team_10
 */

if (!defined('ABSPATH')) {{
    exit;
}}

if (!function_exists('nb_v200_normalize_request_path')) {{
    function nb_v200_normalize_request_path(string $request_uri): string {{
        $path = (string) parse_url($request_uri, PHP_URL_PATH);
        if ($path === '') {{
            $path = '/';
        }}
        $decoded = rawurldecode($path);
        $trimmed = trim($decoded, '/');
        if ($trimmed === '') {{
            return '/';
        }}
        $segments = explode('/', $trimmed);
        $encoded = array_map(static function (string $segment): string {{
            return strtolower(rawurlencode($segment));
        }}, $segments);
        return '/' . implode('/', $encoded) . '/';
    }}
}}

add_action('parse_request', static function (): void {{
    if (is_admin()) {{
        return;
    }}

    // Legacy page_id alias that used to point to heritage source content.
    if (isset($_GET['page_id']) && (string) $_GET['page_id'] === '2516') {{
        wp_safe_redirect('/about/heritage/', 301, 'NB-V200-runtime');
        exit;
    }}

    $redirects = [
{redirect_lines}
    ];

    $drops = [
{drop_lines}
    ];

    $path = nb_v200_normalize_request_path($_SERVER['REQUEST_URI'] ?? '/');
    if (isset($redirects[$path])) {{
        wp_safe_redirect($redirects[$path], 301, 'NB-V200-runtime');
        exit;
    }}

    if (in_array($path, $drops, true)) {{
        status_header(410);
        nocache_headers();
        header('X-Redirect-By: NB-V200-runtime');
        echo '410 Gone';
        exit;
    }}
}}, 0);
"""

    OUTPUT_PATH.parent.mkdir(parents=True, exist_ok=True)
    OUTPUT_PATH.write_text(output, encoding="utf-8")

    print(f"[OK] Wrote: {OUTPUT_PATH}")
    print(f"[INFO] Source: {DECISIONS_PATH}")
    print(f"[INFO] Redirect rows: {len(redirect_pairs)}")
    print(f"[INFO] Drop rows: {len(drop_paths)}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
