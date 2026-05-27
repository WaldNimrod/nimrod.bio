# T4 Post — design vs actual

**Reference:** `sources/team_35_design_package/_handoff/templates/T4 Post.html`  
**Evidence:** `docs/qa/screenshots/p007-wp001/t4-post-*_{1440,375}.png`

| Area | Status | Notes |
|---|---|---|
| Single-post shell | ✅ Match | H1, meta row, content column, related posts band |
| Migrated ASCII (`harish2021`) | ✅ Match | Full migrated body + inline images |
| Migrated Hebrew (`יום-בגינה`) | ⚠ Minor | Layout correct; hero uses grey placeholder (missing featured image) |
| Placeholder posts | ⚠ Minor | `data-nb-placeholder` content + checklist pattern visible on agents-os |
| Mixed Hebrew+English | ✅ Match | `אלה-אם-unless` placeholder renders RTL title correctly (via blog index card) |
| Mobile 375 | ✅ Match | No horizontal scroll on harish2021 |

**Verdict:** ⚠ Minor (expected placeholder + missing featured images)
