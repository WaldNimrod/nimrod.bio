#!/usr/bin/env python3
"""Phase 2 — READ-ONLY fetch of prod posts/pages per triage decisions."""
from __future__ import annotations

import json
import sys
import time
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))

from _lib import (  # noqa: E402
    RAW_DIR,
    ensure_cache_dirs,
    load_envs,
    positive_decisions,
    prod_auth,
    prod_rest_base,
    rest_request,
)


def endpoint_for(row: dict) -> str:
    collection = "pages" if row.get("type") == "page" else "posts"
    return f"{prod_rest_base()}/{collection}/{row['id']}?_embed&context=edit"


def main() -> int:
    load_envs()
    ensure_cache_dirs()
    user, password = prod_auth()
    rows = positive_decisions()
    ok = 0
    failed: list[str] = []

    print(f"[INFO] Fetching {len(rows)} entities from prod (READ ONLY)...")
    for idx, row in enumerate(rows, start=1):
        entity_id = str(row["id"])
        out_path = RAW_DIR / f"{entity_id}.json"
        if out_path.exists():
            print(f"[CACHE] {entity_id} ({row.get('type')} · {row.get('title', '')[:40]})")
            ok += 1
            continue
        url = endpoint_for(row)
        try:
            data = rest_request("GET", url, user, password, sleep_ms=200 if idx > 1 else 0)
            out_path.write_text(json.dumps(data, ensure_ascii=False, indent=2), encoding="utf-8")
            ok += 1
            print(f"[OK] {entity_id} {row.get('type')} · {row.get('title', '')[:50]}")
        except Exception as exc:  # noqa: BLE001
            failed.append(f"{entity_id}: {exc}")
            print(f"[FAIL] {entity_id}: {exc}")
        time.sleep(0.2)

    posts = sum(1 for r in rows if r.get("type") == "post")
    pages = sum(1 for r in rows if r.get("type") == "page")
    print(f"\nSummary: fetched/cached {ok}/{len(rows)} ({posts} posts + {pages} pages); failed {len(failed)}")
    if failed:
        for line in failed:
            print(f"  - {line}")
        return 1
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
