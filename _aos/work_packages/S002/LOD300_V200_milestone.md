---
type: LOD300_MILESTONE
milestone: V200
project: nimrod-bio
author: team_100 (nimrodbio_arch)
date: 2026-05-25
version: v1.0.0-draft
status: PENDING_TEAM_00_APPROVAL
supersedes: none
related:
  - milestone: V100 (COMPLETE)
  - gcr: GOVERNANCE_CHANGE_REQUEST_NIMROD_BIO_TEAM_35_PROPAGATION_v1.0.0 (in flight)
  - design_package: sources/team_35_design_package/_handoff/ (Stage 3 LOCKED)
  - design_handoff: sources/team_35_design_package/_handoff/00-HANDOFF-claude-code-110.md
---

# V200 — Site Rebuild · Milestone Plan (LOD300)

## 1. Mission

Replace the existing WordPress site (Flatsome theme, heavy plugin stack, content from 2016–2023) with a fresh WordPress installation running a **custom theme that implements the team_35 Stage 3 LOCKED design system** (`brand/system.css` v3.3, 7 templates T1/T2/T3/T4/T5/T7/T8, CPTs for service + project, world + flow_style taxonomies).

The rebuild migrates ~50 hand-picked posts from the legacy site (out of 24 published + 7 drafts + 1 private = 32 total posts, plus 7 published pages), preserves SEO-load-bearing slugs, and issues 301 redirects for every changed URL.

## 2. Success criteria — Definition of V200 done

The milestone is complete when **all** of the following hold:

1. Production cutover from old site (Flatsome) → new site (custom theme) has occurred at `nimrod.bio` with zero unhandled 404s on URLs present in the old sitemap.
2. All 7 design templates render real content (no placeholders) for at least one canonical instance each.
3. `validate_aos.sh` reports 0 FAIL on the spoke.
4. Lighthouse on `T7 Home`: Performance ≥ 85, Accessibility ≥ 95, SEO = 100, Best Practices ≥ 90.
5. RTL: zero visual bugs in Chrome, Safari, Firefox on viewports 360px / 768px / 1280px / 1920px.
6. WCAG 2.1 AA contrast across every text/background combination in `brand/system.css`.
7. WhatsApp CTA → `wa.me/972547776770` works from at least: T7 hero, T2 final CTA, T8 contact, footer.
8. Yoast (or RankMath) configured; `sitemap.xml` regenerated and submitted to Search Console.
9. Team 00 sign-off on content migration list (the ~50 posts) — recorded in `_COMMUNICATION/team_00/`.
10. Cutover runbook executed and archived under `_aos/work_packages/S002/RUNBOOK_cutover.md`.

## 3. Stack decisions (locked at LOD300)

| Decision | Choice | Reason |
|---|---|---|
| Hosting | uPress (existing) | Team 00 directive 2026-05-24 — no migration risk, native staging |
| CMS | WordPress 6.7+ | Existing investment, content model fits, design package assumes it |
| Theme | Custom theme `nimrod-bio-2026` (classic, not block) | system.css is conventional CSS; design package supplies PHP-renderable markup; block editor would force premature decomposition |
| Frontend rendering | PHP/Twig native (no Next.js, no headless) | Reduces moving parts; design uses minimal JS; team_35 React prototypes are spec-only |
| ACF / CMB2 / native | **Native CPT + custom meta boxes** (no plugin) | Team 00 decision 2026-05-25: no paid plugin (no ACF Pro / Meta Box / Pods). Most content goes via REST agent; admin UI polish has low ROI. Spec translation in LOD400 of P002-WP002. |
| Cache | uPress SuperCache (native) | No third-party cache plugin |
| CDN | uPress built-in | No Cloudflare separate config (CF already in front of prod via uPress) |
| SEO | Yoast SEO (existing licence + sitemap workflow) | Active on prod; All-in-One SEO will be **removed** (duplicate) |
| Forms | uPress native / Contact Form 7 (TBD pending uPress audit) | Decision in WP-V200-001 |
| Security | uPress Web Firewall | No Wordfence |
| Backup | uPress 30-day auto + manual snapshot | No UpdraftPlus |
| Migration mechanism | Fresh install + WXR import + media tarball | Cleaner than clone-and-clean (sheds Flatsome + plugin debt) |
| Dev env | `https://nimrod-bio-2026.s887.upress.link` (expired cert — workaround documented in CLAUDE.md) | Team 00 provisioned 2026-05-24 |

## 4. Work package roster

| WP ID | Label | Track | Stage | Est. duration | Blocks |
|---|---|---|---|---|---|
| NB-S002-P001-WP001 | uPress dev env preparation (fresh WP install + basic auth + noindex audit) | A | Setup | 1 day | All |
| NB-S002-P001-WP002 | Design package intake + clarifications closure (4 questions to team_35) | A | Setup | depends on team_35 | All theme work |
| NB-S002-P002-WP001 | Custom theme skeleton (style.css, functions.php, system.css integration, Google Fonts, Shell + Footer) | A | Theme foundation | 3 days | WP002+, WP003+ |
| NB-S002-P002-WP002 | CPTs + taxonomies registration (service, project, world, flow_style) | A | Theme foundation | 2 days | WP003-3+ |
| NB-S002-P003-WP001 | T7 Home template (hero variants, ER diagram, 3 worlds cards, projects grid, Unless ribbon, recent posts, final CTA) | A | Templates | 5 days | — |
| NB-S002-P003-WP002 | T1 World pages × 3 (variant C: strata + recursion, seam bridge signal, anchor cards) | A | Templates | 4 days | — |
| NB-S002-P003-WP003 | T2 Services + T3 Projects templates + 3 instances each (heritage strip on produce, ribbons on seeking/legacy) | A | Templates | 6 days | — |
| NB-S002-P003-WP004 | T4 Post + T5 Blog index (3-col layout, ToC, share, filter chips, flow + grid views, flow_style routing) | A | Templates | 5 days | — |
| NB-S002-P003-WP005 | T8 Static pages (about, heritage, contact form, journey timeline, value tiles, media grid) | A | Templates | 4 days | — |
| NB-S002-P004-WP001 | Content migration (WXR export from prod, triage with Team 00, ~50 posts imported with world + flow_style tagging, uploads transferred) | A | Migration | 3 days | P004-WP002 |
| NB-S002-P004-WP002 | 301 redirect matrix implementation + Yoast configuration + sitemap regeneration + Search Console verification | A | Migration | 2 days | P005-WP001 |
| NB-S002-P005-WP001 | QA pass: Lighthouse, RTL, A11y AA, mobile 360px+, broken-link audit, redirect audit | A | QA | 3 days | P005-WP002 |
| NB-S002-P005-WP002 | Production cutover (final backup of old, swap document root on uPress, smoke test, monitoring 48h) | A | Cutover | 1 day + 2 days monitor | — |

**Tracks:** all A (standard L-gate flow). Design-track artifacts already delivered by team_35 — no track B or CONTENT track WPs in V200.

**Estimated total:** ~40 working days = ~8 calendar weeks if single-builder, ~5 weeks with parallelism between Templates WPs (P003-WP001 through WP005 can run in parallel after Foundations).

## 5. Gate flow

Each WP follows the standard L-gate cycle:
- **L-GATE_ELIGIBILITY** — team_100 confirms scope is clear, blockers absent
- **L-GATE_SPEC** — team_100 authors LOD400 spec
- **L-GATE_BUILD** — team_110 builds, team_100 reviews
- **L-GATE_VALIDATE** — team_190 cross-engine validation (must use different engine than builder)

**Express track exception:** WP NB-S002-P001-WP001 (dev env prep) may run OPS/Express — no LOD400, no L-GATE_VALIDATE — if Team 00 approves. It is reversible and risk-bounded.

## 6. Dependencies & blockers

```
P001-WP001 (dev env) ──┐
                       ├──> P002-WP001 (theme skel) ──┐
P001-WP002 (design Q) ─┘                              │
                                                      ├──> P003-WP001..005 (templates, parallel)
                       P002-WP002 (CPTs) ─────────────┘                │
                                                                       │
                                                  P004-WP001 (content) ┘
                                                  P004-WP002 (301s) <────┘
                                                  P005-WP001 (QA) <────────┘
                                                  P005-WP002 (cutover) <───┘
```

**External blockers:**
- GCR_NIMROD_BIO_TEAM_35_PROPAGATION (in flight at AOS hub) — needed before any formal handoff to/from team_35 inside spoke `_COMMUNICATION/`. Does **not** block dev work since the design package is already received and on disk.
- T-04 (logo family) and T-03 (watercolor backgrounds) — produced by image engines, blockers for visual polish (P005), not for early templates.

## 7. Risk register

| Risk | Likelihood | Impact | Mitigation |
|---|---|---|---|
| URL-encoded Hebrew slugs (e.g. `%d7%9e%d7%93%d7%a8%d7%99%d7%9a-...`) break in fresh WP if permalink structure differs | M | H | P002-WP002 to match `/%postname%/` exactly; P004-WP002 matrix audits every existing slug |
| Cloudflare in front of uPress caches stale during cutover | M | M | P005-WP002 includes CF purge step; document in cutover runbook |
| Team 35 clarifications (stack choice, tweaks-panel, Figma SSOT, TBC list) delay theme decisions | L | M | Build on documented assumptions in §3; deviate only with team_35 sign-off |
| Triage of ~50 posts blocked by Nimrod availability | M | H | Pre-stage matrix from P004-WP001 input so triage is point-and-click |
| uPress staging dev URL cert prevents some browser tests | L | L | Use HTTP for routine work, run Lighthouse from CLI with `--ignore-certificate-errors` |
| Yoast vs RankMath choice forced late | L | M | Lock to Yoast in §3; revisit only on hard blocker |
| Cutover discovers prod plugins were doing hidden work (e.g. Types/Views shortcodes embedded in post bodies) | M | H | P004-WP001 includes a content audit: grep WXR for `[types …]` and `[wpv-view …]` shortcodes pre-import |

## 8. Out of scope for V200

- Image generation (T-03 watercolor backgrounds, T-04 logo family) — Team 00 explicit exclusion
- Mobile-specific designs (Stage 5 of team_35) — responsive will be inferred from desktop tokens
- Multi-language (site stays Hebrew-only)
- E-commerce / WooCommerce
- Membership / gated content
- Email newsletter integration (deferred to V300)

## 9. Closed decisions (Team 00 — 2026-05-25)

All 5 open questions closed. Full record: `_COMMUNICATION/team_00/DECISION_V200_OPEN_QUESTIONS_2026-05-25_v1.0.0.md`.

| Q | Decision | Effect |
|---|---|---|
| Q1 team_35 actor | **Withdrawn** — meta-governance not in product scope | No change to spoke files |
| Q2 cutover timing | **A — event-driven** | Gated on team_00 sign-off; no calendar deadline |
| Q3 old site | **C — static `/archive/`** | wget mirror into new site, no `legacy.` subdomain |
| Q4 triage | **B — HTML UI (used)** | 2 keep / 23 redirect / 6 drop; results in `docs/url_migration_decisions_2026-05-25.json` |
| Q5 CPT plugin | **D — native + custom meta boxes** | No paid plugin; +1.5 days to P002-WP002 |

**Side-effect decision — URL prefix:** All posts on new site live under `/blog/<slug>/`. Old root-level post URLs become 301 redirects. Pages remain at root.

## 10. Roadmap registration (next action after Team 00 approval)

Upon approval, team_100 will:
1. Register all 13 WPs in `_aos/roadmap.yaml` under milestone `V200`
2. Update `MILESTONE_MAP.md` with V200 row
3. Create per-WP folders under `_aos/work_packages/NB-S002-*/`
4. Issue MANDATE_NB-S002-P001-WP001 to team_10 (builder)

---

*LOD300 milestone plan | nimrod-bio · V200 · Site Rebuild | Pending Team 00 approval*
