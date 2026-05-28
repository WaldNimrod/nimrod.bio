#!/usr/bin/env python3
"""Lighthouse on 4 Wave 4 URLs — compare to Wave 1 / P005 baselines."""
from __future__ import annotations

import json
import subprocess
from datetime import datetime, timezone
from pathlib import Path
from urllib.parse import urljoin

REPO = Path(__file__).resolve().parent.parent.parent


def run_lighthouse(url: str) -> dict:
    cmd = [
        "npx",
        "--yes",
        "lighthouse",
        url,
        "--quiet",
        "--chrome-flags=--headless=new --ignore-certificate-errors",
        "--output=json",
        "--output-path=stdout",
    ]
    proc = subprocess.run(cmd, capture_output=True, text=True, check=False)
    if proc.returncode != 0:
        return {"url": url, "status": "error", "error": (proc.stderr or proc.stdout)[:500]}
    report = json.loads(proc.stdout)
    categories = report.get("categories", {})
    return {
        "url": url,
        "status": "ok",
        "scores": {
            "performance": round((categories.get("performance", {}).get("score") or 0) * 100),
            "accessibility": round((categories.get("accessibility", {}).get("score") or 0) * 100),
            "best_practices": round((categories.get("best-practices", {}).get("score") or 0) * 100),
            "seo": round((categories.get("seo", {}).get("score") or 0) * 100),
        },
    }


def load_baseline() -> dict:
    p25 = REPO / "docs/qa_lighthouse_results_2026-05-25.json"
    p27 = REPO / "docs/qa_lighthouse_results_2026-05-27.json"
    baseline: dict[str, dict] = {}
    for path in (p25, p27):
        if not path.exists():
            continue
        data = json.loads(path.read_text(encoding="utf-8"))
        for row in data.get("results", []):
            if row.get("status") == "ok":
                baseline[row["url"].rstrip("/")] = row["scores"]
    return baseline


def main() -> int:
    import os
    import sys

    sys.path.insert(0, str(REPO / "scripts" / "migration"))
    from _lib import dev_site_url, load_envs  # noqa: E402

    load_envs()
    base = dev_site_url().rstrip("/")
    paths = [
        "/",
        "/services/produce/",
        "/project/sfa/",
        "/blog/nimrod-context-book/",
    ]
    urls = [urljoin(base + "/", p.lstrip("/")) for p in paths]

    baseline = load_baseline()
    results = [run_lighthouse(u) for u in urls]
    comparisons = []
    for row in results:
        if row.get("status") != "ok":
            comparisons.append(row)
            continue
        key = row["url"].rstrip("/")
        alt = key.replace("http://", "https://")
        prior = baseline.get(key) or baseline.get(alt)
        delta = {}
        if prior:
            for metric in ("performance", "accessibility", "best_practices", "seo"):
                cur = row["scores"][metric]
                old = prior.get(metric)
                if old is not None:
                    delta[metric] = cur - old
        regressions = [
            m for m, d in delta.items() if d is not None and d < -5
        ]
        comparisons.append(
            {
                "url": row["url"],
                "scores": row["scores"],
                "baseline_scores": prior,
                "delta": delta,
                "regression_gt_5": regressions,
                "pass_non_regression": len(regressions) == 0,
            }
        )

    today = datetime.now(timezone.utc).date().isoformat()
    out = REPO / f"docs/qa_lighthouse_wp004_four_{today}.json"
    payload = {
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "base_url": base,
        "results": results,
        "comparisons": comparisons,
        "aggregate_pass": all(c.get("pass_non_regression", False) for c in comparisons if "scores" in c),
    }
    out.write_text(json.dumps(payload, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"[OK] wrote {out}")
    print(json.dumps({"aggregate_pass": payload["aggregate_pass"]}, indent=2))
    return 0 if payload["aggregate_pass"] else 1


if __name__ == "__main__":
    raise SystemExit(main())
