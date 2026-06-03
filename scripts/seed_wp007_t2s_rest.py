#!/usr/bin/env python3
"""WP007 Phase 2 — seed v5 t2s anchor content to a WP target via REST.

Reads scripts/seed_wp007_t2s_data.json and PATCHes service meta. Resolves each
service by slug. JSON-typed values are stored as JSON strings (parsed by
nb_json_meta); array-typed as arrays; string-typed verbatim. Idempotent.

Usage: python3 scripts/seed_wp007_t2s_rest.py            # dev (.env.upress.dev)
"""
from __future__ import annotations

import base64
import json
import os
import urllib.error
import urllib.request
from pathlib import Path

REPO = Path(__file__).resolve().parent.parent


def load_env() -> None:
    env = REPO / ".env.upress.dev"
    for raw in env.read_text(encoding="utf-8").splitlines():
        line = raw.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        k, v = line.split("=", 1)
        os.environ.setdefault(k.strip(), v.strip().strip("'\""))


def auth_header() -> dict:
    user = os.environ["WP_REST_USER"]
    pw = os.environ["WP_REST_APP_PASSWORD"]
    token = base64.b64encode(f"{user}:{pw}".encode()).decode()
    return {"Authorization": f"Basic {token}", "Content-Type": "application/json"}


def base_url() -> str:
    b = os.environ.get("WP_REST_BASE_URL", "")
    return b.replace("/wp-json", "").rstrip("/")


def req(method: str, url: str, data: dict | None = None) -> dict:
    body = None if data is None else json.dumps(data, ensure_ascii=False).encode("utf-8")
    r = urllib.request.Request(url, data=body, headers=auth_header(), method=method)
    with urllib.request.urlopen(r, timeout=60) as resp:
        return json.loads(resp.read().decode("utf-8"))


def main() -> None:
    load_env()
    base = base_url()
    rest = f"{base}/wp-json/wp/v2/services"
    spec = json.loads((REPO / "scripts" / "seed_wp007_t2s_data.json").read_text(encoding="utf-8"))
    for slug, fields in spec.items():
        if slug == "_note" or not isinstance(fields, dict):
            continue
        found = req("GET", f"{rest}?slug={slug}&context=edit&_fields=id,slug")
        if not found:
            print(f"!! service not found: {slug}")
            continue
        sid = found[0]["id"]
        meta: dict = {}
        for k, v in fields.get("string", {}).items():
            meta[f"_nb_{k}"] = v
        for k, v in fields.get("json", {}).items():
            meta[f"_nb_{k}"] = json.dumps(v, ensure_ascii=False)
        for k, v in fields.get("array", {}).items():
            meta[f"_nb_{k}"] = v
        meta["_nb_seed"] = "wp007-t2s"
        out = req("POST", f"{rest}/{sid}", {"meta": meta})
        got = out.get("meta", {})
        print(f"seeded {slug} (id {sid}): feat_tiles={'set' if got.get('_nb_feat_tiles') else '??'} "
              f"svc_steps={'set' if got.get('_nb_svc_steps') else '??'} "
              f"bridge={'set' if got.get('_nb_bridge') else '??'}")


if __name__ == "__main__":
    main()
