---
type: VERDICT
from: team_190 (Codex)
to: team_100
wp_id: NB-S002-P002-WP001
date: 2026-05-25
gate: L-GATE_VALIDATE
verdict: FAIL
---

# VERDICT — NB-S002-P002-WP001

## Summary
FAIL: deployed theme runtime is mostly functional, but L-GATE_VALIDATE cannot pass because the locked design contract and git disposition are not satisfied.

## Test results — T1-T15

| Test | Result | Independent evidence |
|---|---:|---|
| T1 Theme activation via REST | PASS | Authenticated `GET /wp/v2/themes?status=active` returned HTTP 200 with active `stylesheet: nimrod-bio-2026`. |
| T2 Dev URL HTML structure | PASS | `/` returned HTTP 200; HTML contains `lang="he-IL"`, `dir="rtl"`, `data-active-world=""`, and `class="skip-link screen-reader-text"`. |
| T3 Asset loading | PASS | `system.css`, `shell.css`, `shell.js`, and `home.svg` return HTTP 200; source order is Google Fonts -> `system.css` -> `shell.css`. |
| T4 Shell nav renders | PASS | Required labels are present: `נימרוד ולד`, `nimrod.bio`, `אדמה`, `ייעוץ והוראה`, `דיגיטל`, `בלוג`, `על נמרוד`, `צור קשר`. |
| T5 World active state | PASS | `/world/soil/`, `/world/know/`, `/world/code/` return expected 404 shell pages and each renders matching `nav-world <slug> is-active` plus `data-active-world="<slug>"`. |
| T6 Footer renders | PASS | Footer shell renders 4 columns plus bottom Unless row; current-year dynamic footer is present. |
| T7 RTL flow | PASS | Chrome headless at mobile width: `bodyDirection=rtl`, no horizontal scroll (`scrollWidth <= innerWidth`), no non-RTL sample found in first 200 rendered nodes. |
| T8 Font loads | FAIL | Chrome computed style for homepage `<h1>` was `Assistant, Heebo, system-ui, -apple-system, "Segoe UI", sans-serif`; LOD400 requires computed `<h1>` font-family to include `Frank Ruhl Libre`. |
| T9 Color tokens | PASS | Chrome computed `document.body` background is `rgb(245, 243, 236)` (= `paper`). |
| T10 Lighthouse on `/` | PASS_WITH_DEFERRAL | HTTPS Lighthouse with cert errors ignored: Performance 88, Accessibility 98, Best Practices 100, SEO 63. SEO failure is `is-crawlable=0`; `curl -I` confirms `X-Robots-Tag: noindex, nofollow` at uPress edge, while theme HTML has no robots noindex meta. |
| T11 No console errors | PASS | Lighthouse `errors-in-console` audit score is 1 (`No browser errors logged to the console`). |
| T12 `validate_aos.sh` | PASS_WITH_DEFERRAL | Independent run: `31 PASS / 16 SKIP / 1 FAIL`; only FAIL is known Check 32 `_aos/roadmap.yaml` drift. Check 12 cross-project boundary is PASS. |
| T13 Theme.json palette | PASS | Local `theme.json` and authenticated global-styles REST endpoint both expose exactly 12 theme palette slugs: `paper` through `spark`; `custom=false`, `customDuotone=false`, `defaultPalette=false`. |
| T14 `wp_get_environment_type()` | PASS | Homepage fallback panel renders `<code>local</code>`. |
| T15 Application Password still works | PASS | Authenticated `GET /wp/v2/users/me` returned HTTP 200. |

## Constitutional checks (LOD400 §7)

### a) Shell HTML 1:1 from JSX

PASS. `template-parts/shell-nav.php` preserves the 8-link order, required Hebrew strings, `aria-label="בית"` on `.nav-home`, `nav-sep`, and contains no React/JSX residue (`className`, `onClick`, `strokeWidth`, `VariantBar` absent).

### b) `shell.css` extraction

FAIL. Critical selectors are present and prototype-only classes are absent, but the footer extraction is not value-faithful to `T1-styles.css` lines 468-501. Examples:

- Source expects footer muted colors such as `rgba(245,243,236,.5)`, `.6`, `.45`, `.55`; theme CSS uses stronger `.72`, `.82`, `.74`, `.82` values.
- Source expects `.shell-foot .bottom .unless em { color: var(--spark); font-style: normal; font-weight: 700; }`; theme CSS uses a paper-colored value instead of `var(--spark)`.

### c) `system.css` verbatim

FAIL. Header comment is present, but content after the allowed header is not byte-for-byte identical to `sources/team_35_design_package/_handoff/brand/system.css`. First observed body drift: source line says `TikTrack`; theme copy lowercases it to `tiktrack`. LOD400 permits only the added header comment.

### d) `theme.json` — 12 locked colors

PASS. Exactly 12 entries exist with required slugs and hex values; `customDuotone=false` and `defaultPalette=false`.

### e) Gitignore disposition

FAIL. `.env.upress.dev` and `sources/team_35_design_package/**` are correctly gitignored, but the theme directory is not tracked. `git ls-files 'nimrod.bio/wp-content/themes/nimrod-bio-2026/**'` returned no files, and `git status --short -- nimrod.bio/wp-content/themes/nimrod-bio-2026` shows the directory as untracked.

## SFA-FTPS procedure advisory

PASS advisory. `scripts/upress_ftps_upload.py` compiles, loads credentials from `.env.upress.dev`/environment variables, does not hard-code credentials, uses explicit FTPS on port 21 via `ftplib.FTP_TLS`, calls `prot_c()`, enables PASV, and performs an IP allowlist preflight. Dry-run after sourcing `.env.upress.dev` listed the 17 theme upload targets. Documentation records the observed uPress behavior: explicit FTPS on port 21, `prot_c`, PASV, and allowlist discipline.

No secrets were found in the procedure docs or uploader; only environment variable names are referenced.

## validate_aos.sh

Command:

```bash
bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .
```

Observed result:

```text
RESULT: 31 PASS / 16 SKIP / 1 FAIL
L-GATE_BUILD EXIT CRITERION: NOT MET (1 failures)
[FAIL] Check 32: uncommitted _aos/ drift — 1 file(s). Run aos_sync_all.sh via team_00/team_100. First:  M _aos/roadmap.yaml
```

Disposition: PASS_WITH_DEFERRAL for this WP only, because Check 32 is the documented pre-existing drift class and Check 12 is now PASS.

## Deferrals

- T10 SEO numeric threshold is deferred to an indexable production/staging URL because uPress adds `X-Robots-Tag: noindex, nofollow` on `*.upress.link`; theme code does not emit a robots noindex meta tag.
- `validate_aos.sh` Check 32 `_aos/roadmap.yaml` drift remains a team_00/team_100 governance cleanup item and is not attributed to this theme WP.

## Blocking remediations

1. Fix T8 so the computed homepage `<h1>` font-family includes `Frank Ruhl Libre`, or update the LOD400 contract through team_100 if the fallback panel is intentionally sans-serif.
2. Restore `assets/css/system.css` to a verbatim body copy of the locked design source, with only the allowed header comment added.
3. Restore `assets/css/shell.css` footer extraction values to match `T1-styles.css` lines 468-501, especially the muted footer opacity values and `Unless` spark color.
4. Stage/track the full `nimrod.bio/wp-content/themes/nimrod-bio-2026/**` deliverable set in git while keeping `.env.upress.dev` and `sources/team_35_design_package/**` ignored.

## Recommended action

Return to team_10 for remediation and resubmit to team_190 for revalidation; WP002-2 and WP003+ remain blocked until a PASS or PASS_WITH_DEFERRALS verdict is issued.
