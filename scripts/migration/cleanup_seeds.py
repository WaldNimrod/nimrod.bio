#!/usr/bin/env python3
"""Phase 6 — delete WP004 seed posts (_nb_seed=v200), keep migrated content."""
from __future__ import annotations

import sys
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))

from _lib import NB_SEED_LEGACY, NB_SEED_MIGRATED, dev_auth, dev_rest_base, load_envs, rest_request  # noqa: E402


def main() -> int:
    load_envs()
    base = dev_rest_base()
    user, password = dev_auth()

    posts = rest_request("GET", f"{base}/posts?per_page=100&status=any", user, password)
    seeds = []
    migrated = []
    for post in posts or []:
        meta = post.get("meta") or {}
        seed = meta.get("_nb_seed")
        if seed == NB_SEED_LEGACY:
            seeds.append(post)
        elif seed == NB_SEED_MIGRATED:
            migrated.append(post)

    print(f"[INFO] Before cleanup: {len(seeds)} seeds, {len(migrated)} migrated, {len(posts or [])} total listed")

    deleted = 0
    for post in seeds:
        rest_request("DELETE", f"{base}/posts/{post['id']}?force=true", user, password, sleep_ms=100)
        deleted += 1
        print(f"[DEL] seed id={post['id']} slug={post.get('slug')}")

    posts_after = rest_request("GET", f"{base}/posts?per_page=100&status=any", user, password)
    remaining_seeds = [
        p for p in (posts_after or []) if (p.get("meta") or {}).get("_nb_seed") == NB_SEED_LEGACY
    ]
    migrated_after = [
        p for p in (posts_after or []) if (p.get("meta") or {}).get("_nb_seed") == NB_SEED_MIGRATED
    ]
    print(f"[OK] Deleted {deleted} seed posts; remaining seeds={len(remaining_seeds)} migrated={len(migrated_after)}")
    return 1 if remaining_seeds else 0


if __name__ == "__main__":
    raise SystemExit(main())
