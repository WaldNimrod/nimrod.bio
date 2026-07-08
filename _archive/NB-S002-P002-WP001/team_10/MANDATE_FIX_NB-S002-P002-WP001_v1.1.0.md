---
type: MANDATE_FIX
from: team_100 (nimrodbio_arch — Cursor's Claude)
to: team_10 (nimrodbio_build — Cursor)
wp_id: NB-S002-P002-WP001
project: nimrod-bio
milestone: V200
date: 2026-05-25
gate: L-GATE_VALIDATE FAIL → fix cycle iteration 1 → L-GATE_VALIDATE re-submission
track: A · STANDARD
priority: HIGH
cycle: 1
predecessor_artifact: MANDATE_NB-S002-P002-WP001_v1.0.0.md
verdict_ref: _COMMUNICATION/team_190/VERDICT_NB-S002-P002-WP001_VALIDATE_v1.0.0.md
methodology_ref: _aos/methodology/AOS_FIX_CYCLE_DISCIPLINE_v1.0.0.md
---

# MANDATE FIX — NB-S002-P002-WP001 — Cycle 1

**לצוות 10 (Builder — Cursor):**

team_190 (Codex) חזר עם VERDICT FAIL — 4 blockers ספציפיים. כל אחד יישומי, ללא דיון אסטרטגי. זה fix cycle, לא sprint חדש.

## Preconditions (AOS_FIX_CYCLE §2)

### Reproduction artifact

```bash
# B1 — T8 H1 font
curl -sk http://nimrod-bio-2026.s887.upress.link/ | grep -oE '<h1[^>]*>[^<]+</h1>'
# Then: Chrome DevTools → inspect h1 → Computed → font-family
# Observed: "Assistant, Heebo, system-ui, -apple-system, "Segoe UI", sans-serif"
# Expected: include "Frank Ruhl Libre"

# B2 — system.css drift
diff sources/team_35_design_package/_handoff/brand/system.css \
     nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/system.css
# Difference at line 174: "tiktrack" vs source "TikTrack"

# B3 — shell.css footer divergence
diff <(sed -n '468,501p' sources/team_35_design_package/_handoff/templates/T1-styles.css) \
     <(grep -A 35 'shell-foot {' nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/shell.css)
# Wrong opacities: theme has .72/.82/.74/.82; source has .5/.6/.45/.55/.85

# B4 — git untracked
git ls-files 'nimrod.bio/wp-content/themes/nimrod-bio-2026/**' | wc -l
# Observed: 0
# Expected: 16+ files
```

### Minimal failing case

Each blocker reproduces locally with the curl/diff/git commands above. No external dependencies. No timing/race conditions. All 4 are pure file-state defects.

### Impacted surfaces

- **Files only**: 2 theme CSS files + git index
- `nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/system.css`
- `nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/shell.css`
- git tracking under `nimrod.bio/wp-content/themes/nimrod-bio-2026/**`

**1 subsystem (theme assets) + git. Within fix-cycle scope per §3.**

---

## Fix 1 — T8 H1 font (FAIL)

### Root cause

`system.css` defines `.t-display` / `.t-h1` / `.t-h2` / `.t-h3` as classes using `Frank Ruhl Libre`. But raw HTML elements (`<h1>`, `<h2>`) inherit only from `body` → fall back to Assistant. The fallback panel in `index.php` and 404.php uses bare `<h1>` without the `.t-h1` class.

### Decision (locked by team_100)

**Add element-level rule in `shell.css` addendum block** (NOT in system.css — preserve verbatim).

Design intent: headings ARE serif by default; the `.t-*` classes were ergonomic shortcuts for explicit usage. Making elements serif by default is consistent with the design language.

### Exact change

In `assets/css/shell.css`, **in the theme-local addendum section** (after the `.unless-inline` rule), add:

```css
/* === Default heading serif inheritance (theme-local — h1/h2/h3 inherit Frank Ruhl Libre by default) === */
h1, h2, h3 {
    font-family: "Frank Ruhl Libre", "David Libre", Georgia, serif;
    font-weight: 700;
    line-height: 1.15;
}
h1 { font-size: clamp(36px, 5vw, 56px); }
h2 { font-size: 28px; line-height: 1.25; font-weight: 600; }
h3 { font-size: 20px; line-height: 1.30; font-weight: 600; }
```

⚠️ **לא בקובץ system.css.** הוסף רק ל-shell.css.

### Validation
```bash
# In Chrome DevTools on /:
getComputedStyle(document.querySelector('h1')).fontFamily
# Expected: includes "Frank Ruhl Libre"
```

---

## Fix 2 — system.css drift (FAIL)

### Root cause

In the theme's `assets/css/system.css`, line 174 reads `tiktrack` (lowercase). Source `sources/team_35_design_package/_handoff/brand/system.css` line 168 reads `TikTrack` (mixed case). Someone (intentionally or accidentally) lowercased it during copy — likely to make Check 12 forbidden_patterns scan pass.

### Decision

**Restore verbatim.** The case-sensitive grep in Check 12 (`forbidden_patterns: tiktrack`) does NOT match `TikTrack` — so restoring the original mixed-case is both correct (verbatim contract) and safe (Check 12 stays PASS).

### Exact change

```bash
# Re-copy from source verbatim
cp sources/team_35_design_package/_handoff/brand/system.css \
   nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/system.css

# Re-add the allowed header comment at the top:
```

```css
/*
 * system.css — locked tokens copy from team_35 design package v3.3
 * DO NOT EDIT. To change: GCR to team_35 → DESIGN_SYSTEM_EXTENSION_REQUEST.
 * Original: sources/team_35_design_package/_handoff/brand/system.css
 */
```

### Validation

```bash
# Body must be byte-identical after header
diff <(tail -n +6 sources/team_35_design_package/_handoff/brand/system.css) \
     <(tail -n +6 nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/system.css)
# Expected: no output (silent = identical)
# Then run validate_aos.sh — Check 12 should still PASS (case-sensitive grep doesn't match "TikTrack")
```

---

## Fix 3 — shell.css footer values (FAIL)

### Root cause

Footer CSS extraction diverged from source. Wrong opacity values (theme used `.72/.82/.74/.82`; source uses `.5/.6/.45/.55/.85`) and wrong `Unless em` color (theme used paper-tinted; source uses `var(--spark)`).

### Exact source values to restore (from `T1-styles.css` lines 468–501)

```css
.shell-foot {
  background: var(--ink); color: rgba(245,243,236,.8);
  padding: 64px var(--t1-gutter) 28px; margin-top: 0;
}
.shell-foot-inner { max-width: var(--t1-max); margin: 0 auto; }
.shell-foot .cols {
  display: grid; grid-template-columns: 1.6fr 1fr 1fr 1fr; gap: 36px;
  padding-bottom: 36px; border-bottom: 1px solid rgba(245,243,236,.15);
}
.shell-foot .cols h6 {
  font-family: "JetBrains Mono", monospace; font-size: 11px; letter-spacing: .12em;
  text-transform: uppercase; color: rgba(245,243,236,.5);   /* ← .5 (not .72) */
  margin: 0 0 16px; font-weight: 500;
}
.shell-foot .cols a {
  display: block; color: rgba(245,243,236,.85);             /* ← .85 (not .82) */
  text-decoration: none; padding: 5px 0; font-size: 14.5px;
}
.shell-foot .cols a:hover { color: #fff; }
.shell-foot .brand-block .name {
  font-family: "Frank Ruhl Libre", serif; font-size: 26px; font-weight: 900;
  color: var(--paper); margin-bottom: 8px;
}
.shell-foot .brand-block .tag {
  font-family: "Frank Ruhl Libre", serif; font-style: italic; font-size: 16px;
  color: rgba(245,243,236,.6);                              /* ← .6 (not .74) */
  max-width: 36ch; line-height: 1.55;
}
.shell-foot .bottom {
  display: flex; justify-content: space-between; gap: 18px; padding-top: 22px;
  font-family: "JetBrains Mono", monospace; font-size: 11px;
  color: rgba(245,243,236,.45);                             /* ← .45 (not .82) */
  letter-spacing: .04em;
}
.shell-foot .bottom .unless {
  font-family: "Frank Ruhl Libre", serif; font-style: italic; font-size: 14px;
  color: rgba(245,243,236,.55);                             /* ← .55 (not paper) */
  letter-spacing: 0;
}
.shell-foot .bottom .unless em {
  color: var(--spark);                                       /* ← spark (not paper!) */
  font-style: normal;
  font-weight: 700;
}
```

⚠️ **שים לב:** source מכיל `--t1-gutter` ו-`--t1-max` שלא קיימים ב-system.css (הם מוגדרים ב-T1-styles.css). הוסף לראש shell.css fallbacks:

```css
:root {
    --t1-gutter: clamp(20px, 4vw, 56px);
    --t1-max: 1240px;
}
```

(אלה אותם ערכים שמופיעים בראש T1-styles.css.)

### Validation

```bash
# Visual: open / on dev, check footer:
# - Background dark (ink)
# - "Unless" text — italic, with the word "אלא אם כן" in red (spark #d23a2e)
# - Column headers (h6) muted but legible
# Then DevTools: inspect .unless em → computed color must be rgb(210, 58, 46)
```

---

## Fix 4 — git tracking (FAIL)

### Root cause

`nimrod.bio/wp-content/themes/nimrod-bio-2026/` directory exists on disk but was never `git add`ed. Likely team_10 deployed via FTP and forgot the git step.

### Verify what's missing first

```bash
git ls-files 'nimrod.bio/wp-content/themes/nimrod-bio-2026/**' | wc -l   # currently 0
find nimrod.bio/wp-content/themes/nimrod-bio-2026 -type f | wc -l         # expected 16+
```

### Exact change

```bash
# Stage only the theme directory + the 2 fixed CSS files + this MANDATE response
git add nimrod.bio/wp-content/themes/nimrod-bio-2026/

# Verify .env.upress.dev and sources/team_35_design_package/ stay ignored
git check-ignore .env.upress.dev sources/team_35_design_package/_handoff/README.md
# Both should print their path = ignored ✓

# Verify no secrets in staged files
git diff --cached | grep -iE "password|secret|api_key|aos-publisher-dev|T4nT gKoe" || echo "OK: no secrets"

# Commit
git commit -m "fix(theme): nimrod-bio-2026 skeleton — restore verbatim system.css, correct footer values, add default heading serif, track theme files

Fixes from team_190 VERDICT_NB-S002-P002-WP001 cycle 1:
- system.css: restore verbatim (TikTrack case)
- shell.css: footer opacities and Unless spark color match T1-styles.css lines 468-501
- shell.css: add h1/h2/h3 default Frank Ruhl Libre serif (T8 fix)
- track full theme directory in git

Ref: _COMMUNICATION/team_190/VERDICT_NB-S002-P002-WP001_VALIDATE_v1.0.0.md"

# Push
git push origin main:master
```

### Validation

```bash
git ls-files 'nimrod.bio/wp-content/themes/nimrod-bio-2026/**' | wc -l
# Expected: ≥16

git check-ignore .env.upress.dev   # path = still ignored
```

---

## Re-deploy after fix

After all 4 fixes:

```bash
# Re-upload modified files to dev server
set -a; source .env.upress.dev; set +a
python3 scripts/upress_ftps_upload.py \
    nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/system.css \
    nimrod.bio/wp-content/themes/nimrod-bio-2026/assets/css/shell.css

# Cache bust — bump NB_THEME_VERSION in functions.php from 0.1.0 to 0.1.1
# (so browsers refetch CSS without manual ?ver= edits)
```

---

## Exit criteria — re-submission to L-GATE_VALIDATE

ה-COMPLETION עדכון (לא חדש) — `_COMMUNICATION/team_10/COMPLETION_NB-S002-P002-WP001.md`. הוסף סקציה בסוף:

```markdown
## Fix cycle 1 (2026-05-25)

| Blocker | Status | Evidence |
|---|---|---|
| B1 T8 h1 font | FIXED | `getComputedStyle(h1).fontFamily` includes "Frank Ruhl Libre" |
| B2 system.css drift | FIXED | `diff <(tail+5 source) <(tail+5 theme)` is silent |
| B3 shell.css footer | FIXED | `.unless em` computed color = rgb(210, 58, 46) |
| B4 git tracking | FIXED | `git ls-files nimrod.bio/wp-content/themes/nimrod-bio-2026/**` = 16+ files |
```

ה-checklist המקורי בסוף ה-COMPLETION:
- [x] (B1) T8 acceptance test now PASS  
- [x] (B2) system.css verbatim restored  
- [x] (B3) shell.css footer values match T1-styles.css lines 468–501  
- [x] (B4) theme directory tracked in git  
- [x] re-deployed to dev (FTP)  
- [x] cache-busted via version bump  
- [x] `validate_aos.sh` only known Check 32 drift remains

לאחר עדכון COMPLETION — team_100 יסקור (self-review מהיר) ויעביר חזרה ל-team_190 ל-VERDICT cycle 2.

## תזמון

- **Start:** מיד.
- **Target:** ≤4 שעות עבודה (כל 4 הfixes יישומיים, אין R&D).
- **Block:** WP002-2 + WP003+ עד PASS.

## Rollback path

אם פיקסים חדשים שוברים מצב נוכחי על dev:
1. `git revert` הקומיט החדש
2. re-deploy versioned files (NB_THEME_VERSION = 0.1.0)
3. דווח ל-team_100 לפני iteration 2

---

— team_100 (nimrod-bio) — 2026-05-25 — fix cycle 1
