"""Transform cached prod post/page records for dev import."""
from __future__ import annotations

import json
import re
from typing import Any

from _lib import (
    NB_SEED_MIGRATED,
    dev_site_url,
    extract_upload_urls,
    read_cached_raw,
    rewrite_upload_urls,
    slug_from_new_url,
)


def transform_record(
    row: dict,
    raw: dict | None = None,
    *,
    dev_url: str | None = None,
) -> dict[str, Any]:
    """Return REST payload fields + referenced upload URLs."""
    raw = raw or read_cached_raw(str(row["id"]))
    dev_url = (dev_url or dev_site_url()).rstrip("/")

    title = raw.get("title", {})
    if isinstance(title, dict):
        title = title.get("rendered", row.get("title", ""))

    content_obj = raw.get("content", {})
    content = content_obj.get("rendered", "") if isinstance(content_obj, dict) else str(content_obj or "")

    excerpt_obj = raw.get("excerpt", {})
    excerpt = excerpt_obj.get("rendered", "") if isinstance(excerpt_obj, dict) else str(excerpt_obj or "")
    excerpt = re.sub(r"\s+", " ", strip_tags(excerpt)).strip()

    slug = slug_from_new_url(row.get("new_url"), row.get("slug", raw.get("slug", "")))
    entity_type = row.get("type", "post")
    date = raw.get("date") or raw.get("date_gmt")
    status = raw.get("status") or "publish"

    referenced = extract_upload_urls(content)
    featured_media = raw.get("featured_media") or 0
    if featured_media:
        embedded = raw.get("_embedded", {})
        media_list = embedded.get("wp:featuredmedia") or []
        if media_list:
            src = media_list[0].get("source_url")
            if src:
                referenced.add(src.split("?")[0])

    content = rewrite_upload_urls(content, dev_url)

    payload: dict[str, Any] = {
        "title": strip_tags(title),
        "slug": slug,
        "status": status if status in ("publish", "draft", "private") else "publish",
        "content": content,
        "excerpt": excerpt,
        "date": date,
        "meta": {"_nb_seed": NB_SEED_MIGRATED},
    }

    if entity_type == "page":
        payload["type"] = "page"
    else:
        payload["type"] = "post"

    return {
        "entity_type": entity_type,
        "prod_id": str(row["id"]),
        "slug": slug,
        "payload": payload,
        "referenced_uploads": sorted(referenced),
        "date": date,
    }


def strip_tags(text: str) -> str:
    return re.sub(r"<[^>]+>", "", text or "").strip()


def collect_referenced_uploads(rows: list[dict]) -> set[str]:
    urls: set[str] = set()
    for row in rows:
        transformed = transform_record(row)
        urls.update(transformed["referenced_uploads"])
    return urls


if __name__ == "__main__":
    import sys
    from pathlib import Path

    sys.path.insert(0, str(Path(__file__).resolve().parent))
    from _lib import importable_posts, load_decisions  # noqa: E402

    posts = importable_posts(load_decisions())
    if not posts:
        print("No importable posts in decisions JSON")
        raise SystemExit(1)
    sample = transform_record(posts[0])
    print(json.dumps({k: sample[k] for k in ("prod_id", "slug", "date", "referenced_uploads")}, ensure_ascii=False, indent=2))
