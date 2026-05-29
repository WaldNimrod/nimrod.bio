---
type: CONFIRMATION + ASSET_REQUEST
from: team_100 (on behalf of team_00)
to: team_35 (Design Studio)
project: nimrod-bio
milestone: V200
wp_id: NB-S002-P009-WP001 (design) → NB-S002-P009-WP003 (impl)
date: 2026-05-29
version: v1.0.0
---

# team_35 — §6 Sequence CONFIRMED + Open-Asset Request

## §1 Confirmation (closes WP001 design gate)
team_00 **confirms the DESIGN_GAP_ANALYSIS §6 build sequence** (1 root `:root` fix → 2 bridges/Unless → 3 assets → 4 cards/precision → 5 hygiene). Your §7 gate is satisfied — the **WP001 design deliverable is accepted**. Package v5 received + extracted (gap analysis, README, Precision Mockup, assets). G-01 root bug independently confirmed by team_100.

Implementation proceeds as **NB-S002-P009-WP003** (team_100-orchestrated build). Scope (team_00): T7 home precision + t1 `:root` fix + shared components; broader T2–T8 propagation deferred.

## §2 Asset audit — what we ALREADY have (do NOT reproduce)
| Asset | Status in repo |
|---|---|
| Photography (hero/worlds/manifesto) | ✅ provided in package `raw/` (farm, greenhouse, field, why-morning, products, landscape, candids) |
| Heritage basket motif | ✅ `basket-*.png` (546×525) + **high-res source** `uploads/2017/11/Nimrod-garden-logo.jpg` (2569×2400) |
| IconPark icon family | ✅ already in repo `uploads/market/icons/iconpark/` |
| "Why we get up" portrait | ✅ `raw/why-morning.jpg` |
| World/bridge/spark 2-stroke icons | ⚙️ will build **in-house** as geometric SVG (simple) — request only if you want to own them |

**None of the below blocks the WP003 build** — we build now with the assets above + graceful placeholders, then swap your finals on delivery.

## §3 GENUINELY MISSING — please deliver (priority order)
| # | Asset | Spec (per your package §5 / README) | Build placeholder until delivered |
|---|---|---|---|
| 1 | **Logo SVG master** (T-04) | Vector wordmark "נמרוד ולד" (FRL 700) + lockup with 3 world dots; from the heritage basket line-art | High-res raster (2569×2400) + basket PNG — usable now; SVG = crispness/favicon source |
| 2 | **Favicon** 32×32 | Dedicated "נ" or abstract connection mark; replaces flatsome fallback | Current `home.svg` generic icon |
| 3 | **OG / social image** 1200×630 | Branded template (basket + wordmark + tagline) | Yoast default / none |
| 4 | **Watercolor washes ×5** (T-03) | soil · know · code + 2 bridges; section bg @ opacity 0.07–0.12; **3 resolutions each** (per updated `01-PROMPT-watercolor`) | CSS `--*-wash` gradient tokens (baseline already in spec) |
| 5 | **Real screenshots** — SFA app + tiktrack | For the systems-section media panels | duotone photo "screenshot pending" / dark sparkline panel |

## §4 Notes
- Logo SVG (#1): we have a 2569×2400 raster, so this is **polish**, not a blocker — needed mainly for favicon/OG crispness and large-scale use.
- Watercolors (#4): the spec's baseline is CSS gradients on `--*-wash`; the watercolor images are an enhancement layer.
- Please deliver as you finish each — we'll hot-swap into the live build (each has a documented placeholder swap point).

*team_100 · 2026-05-29 · §6 confirmed (team_00) · open-asset request for P009-WP003*
