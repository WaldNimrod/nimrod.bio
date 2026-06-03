# HANDOFF — NB-S002-P009-WP007 implementation session — team_100 — v1

**Date:** 2026-06-03 · **From:** team_100 (orchestrator) · **To:** WP007 implementation session (team_100-orchestrated, **Claude Code**)
**Gate cleared:** L-GATE_SPEC **PASS_WITH_FINDINGS** (team_190 Cursor — `VERDICT_NB-S002-P009-WP007_LOD400_2026-06-03_v1.md`). Build authorized.

> Paste this as the activation brief for a fresh implementation session. It is self-contained.

## Identity & rules
- **WP:** NB-S002-P009-WP007 — full design implementation / precision walk vs **Precision Mockup v5**.
- **Engine:** Claude Code (team_100-orchestrated). **Iron Rule #1:** build = Claude Code → L-GATE_VALIDATE MUST be cross-engine (team_190 **Cursor** + team_50; Codex unavailable).
- **Spoke:** nimrod-bio (L0, ADR034 R9 file-based SSoT). Iron Rules + governance per `_aos/governance/team_100.md`.

## The contract
- **LOD400 (authority):** `_aos/work_packages/NB-S002-P009-WP007/LOD400_NB-S002-P009-WP007.md` — build to its §4 ATs (AT-1..AT-7) + §5 global acceptance (AC-A..AC-G).
- **Design SSoT (pixel authority):** `_COMMUNICATION/team_35/HANDOFF_v5_2026-06-03/Precision_Mockup_v5.html` (renderable full pkg, gitignored: `sources/team_35_design_package/HANDOFF_v5_2026-06-03/`).

## Carry-in findings (from L-GATE_SPEC — obey these)
- **F1 · v5 WINS on conflict.** AT numeric/selector values are indicative. Read exact values off the v5 mockup CSS before building. Known corrections already folded into the LOD400: AT-1 `.svc-grid` **2-col** (v5 L739) + h1 `clamp(40px,6.2vw,84px)` (L600); AT-2 hero **`1.2fr .85fr`** (L925) + feat-grid **2×2** (L933); AT-3 h1 `clamp(34px,5vw,64px)` (L822); AT-4 **no numbered h2** (numbering is heritage-only) + use `.post-hero-meta-top`.
- **F2 · G3a override source = `Precision_Mockup_v5.html` L896–913** (the old `04_build_layer/.../t1.css` path does NOT contain it). Lift the `.t1-world-know`/`.t1-world-code` block from there into the live `t1.css` in Phase 1. v5 uses selector vocabulary `.lat-anchor`/`.post-row` — map to the live theme's equivalents.
- **F3 · G1 projects archive is DONE** (WP001) — out of scope; non-regression check only.

## Scope (LOD400 §3)
- **G2:** T2 services-index (`archive-service.php`) · T2 service-single (`single-service.php`) · T3 (`single-project.php`) · T4 (`single.php`) · T5 (`home.php`).
- **G3:** world know/code accents (`page-know.php`/`page-code.php`) + land G3a · heritage (`page-heritage.php`).
- **OUT:** projects archive (done), content/copy (LOCKED), production cutover, `system.css` tokens.

## Phasing (LOD400 §6 — deploy + verify between phases)
1. **Phase 1** — land G3a override + know/code accents + heritage.
2. **Phase 2** — T2 service-single (t2s) + T3.
3. **Phase 3** — T2 index + T4 + T5.

## Hard gates every phase (LOD400 §5)
- **a11y NON-REGRESSION (AC-A):** `node scripts/qa/cdp/axe_probe.mjs --config docs/qa/cdp/v200b/team190/axe_config.json` → **0 serious/critical, 0 color-contrast**; **Lighthouse a11y ≥95** on affected routes. WP006 baseline = 100 — must hold. Re-check know/code `--w-*-deep` on `--know-wash`/`--code-wash` ≥ 4.5.
- **No overflow (AC-B):** `node scripts/qa/cdp/qa_probe.mjs` 0 @375/1440.
- **Locks (AC-C):** 0 forbidden terms (Micha; demonstrate-never-name) in HTML+CSS+alt/aria+comments.
- **Discipline (AC-D):** module CSS + template-parts ONLY; 0 inline; no overrides layer; `system.css` byte-identical (`git diff` empty); RTL logical properties.
- **Deploy (AC-E):** bump `NB_THEME_VERSION`; FTPS per `docs/UPRESS_FTPS_MANDATORY_PROCEDURE.md`; byte-parity served==repo.
- **Non-regression (AC-F):** T7/T1-soil/about/contact/404/search/projects-archive + WP002 mobile + WP006 a11y + wired galleries (project 49/31, service 24) still pass.
- **validate_aos (AC-G):** 0 FAIL.

## Environment (pinned, clean)
- Baseline: **v0.7.19 @ `8ac8fc47`** (or HEAD). Working tree clean, `main == origin/main`.
- **Local Docker = 100% dev mirror** (WP 7.0 + dev plugins + V200 DB + uploads): `http://localhost:8085`. Dev: `https://nimrod-bio-2026.s887.upress.link`.
- Harness: `docs/QA_HARNESS.md`. Lighthouse needs full Chrome via `CHROME_PATH` + explicit `PATH=/opt/homebrew/bin:$PATH` in compound shells. Dev TLS invalid BY DESIGN → `-k` / `--ignore-certificate-errors` (DEV-ONLY).

## Super-locks (every byte, incl. alt/aria/comments)
1. Micha / "Micha OS" — never. 2. Demonstrate, never name — אנטרופיה · נגנטרופיה · רקורסיה · CDIP · cross-domain · פרמקלצר · 3× · אינסטנסים · קואופרטיב · קומון. (Don't introduce new instances of the pre-existing comment terms.)

## Close-out
Per phase / on completion: deploy → qa_probe + axe non-regression → file **L-GATE_VALIDATE** to team_190 (Cursor) + team_50 → on PASS, team_100 closes per ADR042 (roadmap LOD500 + git audit).

## FIRST ACTION (implementation session)
Read the LOD400 + open `Precision_Mockup_v5.html`. Start **Phase 1**: lift the G3a block from v5 L896–913 → live `t1.css`; verify `/world/know/` accents=orange, `/world/code/`=teal, `/world/soil/` unchanged @375/1440; heritage vs v5 `heritage` screen; run axe + qa_probe (non-regression); deploy; byte-parity.

*team_100 · WP007 implementation handoff · 2026-06-03 · L-GATE_SPEC PASS_WITH_FINDINGS · build=Claude → validate=Cursor*
