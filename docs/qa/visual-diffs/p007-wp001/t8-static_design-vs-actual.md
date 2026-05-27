# T8 Static — design vs actual

**Reference:** `sources/team_35_design_package/_handoff/templates/T8 Static.html`  
**Evidence:** `docs/qa/screenshots/p007-wp001/t8-{about,heritage,contact}_1440.png`, `contact-form-*_1440.png`

| Area | Status | Notes |
|---|---|---|
| About | ✅ Match | Static page shell, heritage cross-link |
| Heritage | ✅ Match | Long-form heritage content renders |
| Contact layout | ⚠ Minor | Form + info cards present; large right whitespace band at 1440 (content width constraint, not broken layout) |
| Form states | ✅ Match | `?status=ok` success banner; invalid screenshot captured via `?status=invalid` query (handler also uses `status=error` for server-side rejects) |

**Verdict:** ⚠ Minor (contact whitespace band)
