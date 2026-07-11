---
type: RESPONSE_INVENTORY
from: team_00 (Principal)
recorded_by: team_110 (orchestrator · Wave 2)
to: team_110 (gate sign) → team_10 (Wave 3 executor)
project: nimrod-bio
milestone: V200
wp_id: NB-S002-P007-WP002
wave: 2 of 4 (P007)
date: 2026-05-28
version: v1.0.0
mandate_ref: _COMMUNICATION/team_110/MANDATE_NB-S002-P007-WP002_COMPLETION_INVENTORY_v1.0.0.md
decisions_ref: _COMMUNICATION/team_00/INVENTORY_DECISIONS_NB-S002-P007-WP002_2026-05-28_v1.0.0.md
status: PARTIAL — D-04 URLs PENDING (see §2 open items)
---

# RESPONSE_INVENTORY_P007 — team_00 decisions recorded

## §1 Decision log (D-01..D-07)

### D-01 — Blog pagination → A (keep 10/page)
No change to WP Reading settings. Wave 3: no action needed.

---

### D-02 — Yoast title template → A (`%title% · נמרוד ולד`)
Full name as domain identifier.
Wave 3 team_10 action: set Yoast › Settings › Site title separator to `· נמרוד ולד`. Verify in Wave 4.

---

### D-03 — Placeholder strip timing → PARADIGM SHIFT (supersedes options A/B/C)

**team_00 ruling:** *"האתר יעלה כשיהיה מושלם. לא מסירים כלום — מחליפים בשלבים לתוכן מלא ובסיום מעלים לאוויר."*

**Architectural implication (team_110 interpretation):**
- Cutover is GATED on content completion — not a parallel track.
- COMPLETION_CONTENT_PHASE v1.0.0 §6 acceptance ("placeholders may ship") is SUPERSEDED.
- Wave 3 scope = fill ALL placeholder content → strip markers → THEN cutover.
- Wave 4 validates completed site (no placeholder markers visible).
- Cutover MANDATE v1.1.0 precondition updated: Wave 4 PASS on fully-filled site.

**Impact on Wave 3 MANDATE:** scope is now FULL content fill (not partial). timeline TBD based on content sourcing.

---

### D-04 — SFA + TikTrack double-link architecture → EXPANDED SCOPE

**team_00 ruling:** *"קישור כפול — לעמוד של המערכת באתר ולמערכת עצמה בסאב דומיין. שני מוצרי מדף: SFA + TikTrack — שניהם מוצר ולא שירות מותאם."*

**Architectural interpretation (team_110):**

Both SFA and TikTrack are repositioned as **shelf software products** (not custom services). Each gets:
1. An internal marketing page on nimrod.bio (CPT: service or dedicated page)
2. A link to the live system on its own subdomain

TikTrack service CPT (ID 29, slug: `tiktrack`) already exists — add double-link treatment.
SFA service CPT was deleted in P006 — needs recreation as marketing page OR new dedicated page.

**Open items — URLs required from team_00 before Wave 3 can execute D-04:**
- SFA subdomain URL: `sfa.nimrod.bio`? other?
- TikTrack subdomain URL: `tiktrack.io`? other?
- SFA marketing page: recreate as service CPT, or new standalone page (`/software/sfa/`)?

**Wave 3 scope addition:** create/configure double-link pattern for both products. Recorded in §3 below.

---

### D-05 — /about/ content → nimrod-book domain session

**team_00 ruling:**
- /about/ content will be produced by a **nimrod-book domain session** in a dedicated session.
- The book itself (as a book) is NOT displayed on the site.
- **New item D-05.1:** create placeholder blog post — *"יצירת ספר עלי כבסיס קונטקסט לעבודה נכונה מול LLM"* — added to content list (see §3).

Wave 3: team_10 creates placeholder post. /about/ content depends on nimrod-book session (parallel track; Wave 3 MANDATE includes placeholder for /about/ with nimrod-book session as source).

---

### D-06 — harish2021 broken asset → DELETE the entire post

**team_00 ruling:** *"אפשר להוריד את הפוסט הזה — לא מעניין."*

Wave 3 action: REST DELETE post ID 67 (`/blog/harish2021/`).
Post count: 33 → 32.
Console F-004 resolved by deletion.

---

### D-07 — seed-t7-* entries → KEEP + add to content list

**team_00 ruling:** *"שמירה והוספה לרשימת התכנים החסרים להשלמה."*

seed-t7-produce (ID 42) and seed-t7-consulting-hydro (ID 43) remain published.
Both added to content fill list (see §3 — M-09, M-10 in INVENTORY_MEDIA now active).
Wave 3: fill content + featured images for both.

---

## §2 Open items (blocking Wave 3 full scope)

| # | Item | Who | Urgency |
|---|---|---|---|
| OI-1 | SFA subdomain URL | team_00 | P0 — needed for D-04 double-link |
| OI-2 | TikTrack subdomain URL | team_00 | P0 — needed for D-04 double-link |
| OI-3 | SFA page type: service CPT recreate vs new `/software/` page | team_00 | P1 — affects Wave 3 MANDATE scope |
| OI-4 | Content method for T-01..T-09 (bullets / co-author / drafts) | team_00 | P1 — drives Wave 3 build approach |
| OI-5 | nimrod-book session timing (for /about/ content) | team_00 | P1 — can be parallel to Wave 3 |

Wave 3 MANDATE cannot be finalized until OI-1 and OI-2 are resolved.

---

## §3 Updated content fill list (Wave 3 scope additions from this session)

### New items added to content backlog:

| # | type | item | action | priority |
|---|---|---|---|---|
| NEW-1 | post | "יצירת ספר עלי כבסיס קונטקסט לעבודה נכונה מול LLM" | Create placeholder post (World: know+code; flow_style: feature) | P1 |
| NEW-2 | service | SFA marketing page | Create/recreate with double-link pattern (internal page + subdomain CTA) | P1 |
| NEW-3 | service | TikTrack double-link | Add subdomain CTA to existing service ID 29 | P1 |
| NEW-4 | service | seed-t7-produce (ID 42) | Fill content + featured image | P2 |
| NEW-5 | service | seed-t7-consulting-hydro (ID 43) | Fill content + featured image | P2 |
| NEW-6 | post | DELETE harish2021 (ID 67) | REST DELETE | P1 |

### Existing Wave 3 scope (unchanged):
T-01..T-11 placeholder posts, T-12 /about/ (nimrod-book session), D-02 Yoast title, all media slots per INVENTORY_MEDIA.

---

## §4 Wave 3 MANDATE status

**BLOCKED on OI-1..OI-4** (D-04 URLs + content method + SFA page type).

Once team_00 provides:
- SFA URL + TikTrack URL
- SFA page type decision
- content_method for posts

team_110 will finalize and dispatch Wave 3 MANDATE to team_10.

— team_110 (orchestrator · recording team_00 response) — 2026-05-28
