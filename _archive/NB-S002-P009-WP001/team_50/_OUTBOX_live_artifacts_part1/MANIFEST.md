# MANIFEST — Live artifact pack Part 1 (NB-S002-P009-WP001)

**WP:** NB-S002-P009-WP001 · **Mandate:** team_35 → team_50 live-artifact request (2026-06-02)  
**Delivered by:** team_50 · **Capture window:** 2026-06-02T18:53–18:56Z  
**Live host:** https://nimrod-bio-2026.s887.upress.link · **Theme header:** `0.7.15` (`?ver=0.7.15` on enqueued assets)  
**Repo SHA at capture:** `a978d7b505409ade62fb1fad5871fd61f35286af` · **Mandate baseline:** `a35a67df` (theme constant still `0.7.15`; local repo advanced with aos-sync commits)

## Route confirmation

| Mockup screen | Live route used | HTTP |
|---------------|-----------------|------|
| t7 home | `/` | 200 |
| t1 world אדמה | `/world/soil/` (not `/world/adama/`) | 200 |
| contact | `/contact/` | 200 |
| about | `/about/` | 200 |
| sys 404 | `/nb-precision-404-probe-2026/` | 404 |
| sys search | `/?s=נימרוד` | 200 |
| states | n/a | design-spec only → `states_design_spec_only.md` |

## Artifact index

| filename | screen | viewport | proof type | timestamp (UTC) | SHA / note |
|----------|--------|----------|------------|-----------------|------------|
| `screenshots/live_t7_375.png` | t7 | 375 | full-page PNG | 2026-06-02T18:53:15Z | DPR 1 · ver 0.7.15 |
| `screenshots/live_t7_1440.png` | t7 | 1440 | full-page PNG | 2026-06-02T18:53:15Z | DPR 1 · ver 0.7.15 |
| `screenshots/live_t1_375.png` | t1 | 375 | full-page PNG | 2026-06-02T18:53:15Z | `/world/soil/` |
| `screenshots/live_t1_1440.png` | t1 | 1440 | full-page PNG | 2026-06-02T18:53:15Z | |
| `screenshots/live_contact_375.png` | contact | 375 | full-page PNG | 2026-06-02T18:53:15Z | |
| `screenshots/live_contact_1440.png` | contact | 1440 | full-page PNG | 2026-06-02T18:53:15Z | |
| `screenshots/live_about_375.png` | about | 375 | full-page PNG | 2026-06-02T18:53:15Z | |
| `screenshots/live_about_1440.png` | about | 1440 | full-page PNG | 2026-06-02T18:53:15Z | |
| `screenshots/live_sys404_375.png` | sys | 375 | 404 full-page | 2026-06-02T18:53:15Z | |
| `screenshots/live_sys404_1440.png` | sys | 1440 | 404 full-page | 2026-06-02T18:53:15Z | |
| `screenshots/live_syssearch_375.png` | sys | 375 | search full-page | 2026-06-02T18:53:15Z | |
| `screenshots/live_syssearch_1440.png` | sys | 1440 | search full-page | 2026-06-02T18:53:15Z | |
| `shot_metadata.json` | all | — | per-shot URL/DPR/ver/SHA | 2026-06-02T18:53:15Z | |
| `cdp_probe/qa_probe_stdout.json` | all | 375+1440 | **raw** qa_probe stdout | 2026-06-02T18:54:58Z | verdict PASS |
| `cdp_probe/qa_probe_result.json` | all | 375+1440 | qa_probe JSON mirror | 2026-06-02T18:54:58Z | 12/12 pass |
| `cdp_probe/screenshots/*.png` | all | 375+1440 | harness screenshots (12) | 2026-06-02T18:54:58Z | duplicate angle of A |
| `live_probe_details.json` | all | 375+1440 | overflow elems + computed + §06 | 2026-06-02T18:55:44Z | see overflow table |
| `computed_style_proofs_1440.json` | A/B/C | 1440 | getComputedStyle dump | 2026-06-02T18:55:44Z | |
| `BUCKET_ABC_BYTE_CHECK.md` | A/B/C | 1440 | human byte-check table | 2026-06-02 | |
| `wa_btn_hover_proof.json` | A | 1440 | WA hover proof | 2026-06-02 | |
| `section06_dom_proof.json` | D §06 | 1440 | DOM + placement | 2026-06-02T18:55:44Z | **§06 absent on live** |
| `lock_scan_aggregate.json` | locks | all | forbidden-term scan | 2026-06-02T18:55:44Z | **0 hits** |
| `environment_integrity.json` | E | — | version/cache/SHA/parity | 2026-06-02T18:55:44Z | |
| `states_design_spec_only.md` | states | — | no live route | 2026-06-02 | |
| `part1_capture_stdout.log` | — | — | capture runner log | 2026-06-02 | |
| `qa_probe_config.json` | — | — | harness config used | 2026-06-02 | |

## qa_probe overflow (document-level)

All 12 runs: `scrollWidth === clientWidth`, `overflow: false`, `forbiddenFound: []`.

## Element-level overflow (live_probe_details — sub-document)

| screen | viewport | element | overflow px | doc scroll |
|--------|----------|---------|-------------|------------|
| t7 | 375 | `img` | 26 | no (375=375) |
| t7 | 1440 | `img` | 101 | no (1440=1440) |

## Lock scan

**Aggregate: 0** across all pages/viewports (`lock_scan_aggregate.json`). Terms scanned: Micha, Micha OS, CDIP, Cross-Domain Isomorphism, cross-domain, אנטרופיה, נגנטרופיה, רקורסיה, פרמקלצר, 3×, אינסטנסים, קואופרטיב, קומון, TBD, TBC, recursion.

## Environment / integrity (E)

- **Deployed `NB_THEME_VERSION`:** `0.7.15` (matches repo `functions.php` constant).
- **Cache-bust sample:** `.../assets/css/system.css?ver=0.7.15`
- **Deployed commit SHA:** not exposed by host; mandate baseline `a35a67df` tagged v0.7.15 in mandate. Capture machine repo at `a978d7b` (aos-sync only delta vs baseline — theme tree version constant unchanged).
- **Byte parity (fetchable assets):**
  - `t8.css` live vs repo: **MATCH** (`b5dbf34f…`)
  - `system.css` live vs repo: **MATCH** (`5825aaef…`, 14253 bytes; re-verified via `cmp`)
  - `functions.php` / `front-page.php`: direct HTTP fetch **403** (not parity-checkable via curl; §06 absence on live aligns with repo `front-page.php` having no `.posts-grid` block)

## §06 status (blocking Part 1 fidelity for home)

Live home has **no** `.posts-grid` / `.rp-card` block. Section order: §05 `t7-projects` → `manifesto` → §07 `final-cta`. Team 00 §06 build **not present on dev** at capture time.

---

*team_50 | full Part 1 artifact pack | ready for team_35 per-screen PASS / drift-remaining scan*
