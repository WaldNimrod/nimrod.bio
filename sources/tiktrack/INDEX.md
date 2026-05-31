# TikTrack — source index (site)

Owner-provided engineering specs (2026-05-31), copied verbatim for site-text extraction.
TikTrack is marketed SEPARATELY from nimrod.bio; on the site it gets a teaser linking to the live app tt.nimrod.bio. These docs are TECHNICAL — site text must be high-level, never engineering jargon.

| file | content |
|------|---------|
| `TT_SPEC_01_engineering_v1.2.0.md` | Architecture: Active QA philosophy, DB migrations, D25 AI cost governance, 6-stage rollout. Certified LOD500_LOCKED 2026-05-02. |
| `TT_SPEC_02_trade_replay_dashboard_v1.2.0.md` | Trade Replay architecture + dashboard integration (6 cubes), Micha OS overlays, security/governance. |
| `TT_SPEC_03_trade_replay_active_qa.md` | Trade Replay engine + Active QA architecture, Plan Snapshot, financial precision, API design. |

## Marketing-relevant concepts extracted (NOT technical)
- **Active Quality Assurance** — redefines the category: not a retrospective journal, a proactive QA system.
- **Behavioral Mirror** — non-punitive transparency; intervenes in the decision loop to counter "hope & ego" biases.
- **Coach over Compliance** — "מאמן, לא משגיח." A clinical self-correction tool, not a gatekeeper.
- **Plan Snapshot** — freezes entry/stop/target at first execution; prevents the "hope trap" (widening stops) — your thesis can't be silently rewritten mid-trade.
- **Discipline Tax** — the manual review step is a deliberate forced-review moment = methodology hardening.
- **Audience:** Swing Trader (3–15 trades/week).
- **D25 "Skeptical Coach"** — AI bias-detection; BYOK (bring your own LLM key); cost preview before each run.
- **Micha OS methodology** — ATR Traffic Light (volatility-based stops), Death Kiss / 150 Law (strict risk).
- **Status:** pilot stage (10 users, Tailscale-gated). Live: tt.nimrod.bio ("TikTrack Phoenix").

## CDIP bridge (book, NOT site surface)
TikTrack embodies the same substrate as the garden: observe → specify → accept consequences → execute, and self-skepticism by design (the Skeptical Coach = `profiles/01` self-skepticism). This is conceptual ONLY — NOT a farm↔TikTrack product/data link (owner-direct Q-01: no direct link). Do not surface on the site.
