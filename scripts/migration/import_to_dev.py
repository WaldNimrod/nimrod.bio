#!/usr/bin/env python3
"""Phase 4 — import transformed posts + shook page to dev via REST."""
from __future__ import annotations

import argparse
import json
import sys
import time
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))

from _lib import (  # noqa: E402
    CACHE_DIR,
    ID_MAPPING_PATH,
    REFERENCED_UPLOADS_PATH,
    SKIP_IMPORT_PAGE_IDS,
    dev_auth,
    dev_rest_base,
    load_decisions,
    load_envs,
    positive_decisions,
    rest_request,
)
from transform_post import transform_record  # noqa: E402


def find_tagging_decisions() -> Path | None:
    matches = sorted(Path("docs").glob("content_tagging_decisions_*.json"))
    repo_docs = Path(__file__).resolve().parent.parent.parent / "docs"
    matches = sorted(repo_docs.glob("content_tagging_decisions_*.json"))
    return matches[-1] if matches else None


def load_tagging(path: Path) -> dict[str, dict]:
    data = json.loads(path.read_text(encoding="utf-8"))
    rows = data.get("posts") or data.get("decisions") or []
    return {str(row["id"]): row for row in rows}


def term_ids(base: str, user: str, password: str) -> tuple[dict[str, int], dict[str, int]]:
    worlds = {}
    flows = {}
    for slug in ("soil", "know", "code"):
        data = rest_request("GET", f"{base}/world?slug={slug}", user, password)
        if not data:
            raise RuntimeError(f"Missing world term: {slug}")
        worlds[slug] = int(data[0]["id"])
    for slug in ("lead", "wide", "tall", "typo", "quote", "feature", "brief"):
        data = rest_request("GET", f"{base}/flow_style?slug={slug}", user, password)
        if not data:
            raise RuntimeError(f"Missing flow_style term: {slug}")
        flows[slug] = int(data[0]["id"])
    return worlds, flows


def should_import(row: dict) -> bool:
    entity_id = str(row["id"])
    if row.get("type") == "page":
        if entity_id in SKIP_IMPORT_PAGE_IDS:
            return False
        return row.get("decision") == "keep" and row.get("slug") == "shook"
    return row.get("type") == "post" and row.get("decision") == "redirect"


def delete_existing_by_slug(base: str, user: str, password: str, collection: str, slug: str) -> None:
    existing = rest_request("GET", f"{base}/{collection}?slug={slug}&status=any", user, password)
    for item in existing or []:
        rest_request("DELETE", f"{base}/{collection}/{item['id']}?force=true", user, password, sleep_ms=100)


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--tagging", help="Path to content_tagging_decisions JSON")
    parser.add_argument("--dry-run", action="store_true")
    args = parser.parse_args()

    load_envs()
    base = dev_rest_base()
    user, password = dev_auth()

    tagging_path = Path(args.tagging) if args.tagging else find_tagging_decisions()
    if not tagging_path or not tagging_path.exists():
        print("[ERROR] Missing content_tagging_decisions_*.json — team_00 must complete tagging first.")
        return 2

    tagging = load_tagging(tagging_path)
    decisions = load_decisions()
    rows = [row for row in positive_decisions(decisions) if should_import(row)]
    post_rows = [row for row in rows if row.get("type") == "post"]
    if len(post_rows) != 22:
        print(f"[WARN] Expected 22 importable posts, got {len(post_rows)}")

    missing_tags = [str(r["id"]) for r in post_rows if not tagging.get(str(r["id"]), {}).get("worlds")]
    if missing_tags:
        print(f"[ERROR] Tagging incomplete for post IDs: {', '.join(missing_tags[:5])}...")
        return 3

    world_ids, flow_ids = term_ids(base, user, password)
    mapping: dict[str, int] = {}
    all_uploads: set[str] = set()

    print(f"[INFO] Importing {len(rows)} entities using tagging from {tagging_path.name}")
    for idx, row in enumerate(rows, start=1):
        entity_id = str(row["id"])
        transformed = transform_record(row)
        payload = dict(transformed["payload"])
        all_uploads.update(transformed["referenced_uploads"])

        tag = tagging.get(entity_id, {})
        if row.get("type") == "post":
            payload["world"] = [world_ids[w] for w in tag.get("worlds", []) if w in world_ids]
            flow_slug = tag.get("flow_style") or "feature"
            payload["flow_style"] = [flow_ids[flow_slug]]
            if tag.get("featured"):
                payload["meta"]["_nb_featured"] = "1"

        collection = "pages" if row.get("type") == "page" else "posts"
        slug = payload["slug"]
        if args.dry_run:
            print(f"[DRY] {collection} prod={entity_id} slug={slug} date={payload.get('date')}")
            continue

        delete_existing_by_slug(base, user, password, collection, slug)
        created = rest_request("POST", f"{base}/{collection}", user, password, payload, sleep_ms=150)
        mapping[entity_id] = int(created["id"])
        print(f"[OK] {collection} prod={entity_id} -> dev={created['id']} slug={created.get('slug')}")
        time.sleep(0.15)

    if args.dry_run:
        return 0

    CACHE_DIR.mkdir(parents=True, exist_ok=True)
    ID_MAPPING_PATH.write_text(json.dumps(mapping, ensure_ascii=False, indent=2), encoding="utf-8")
    REFERENCED_UPLOADS_PATH.write_text(
        json.dumps(sorted(all_uploads), ensure_ascii=False, indent=2),
        encoding="utf-8",
    )
    print(f"[OK] Wrote {ID_MAPPING_PATH} ({len(mapping)} entries)")
    print(f"[OK] Wrote {REFERENCED_UPLOADS_PATH} ({len(all_uploads)} upload URLs)")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
