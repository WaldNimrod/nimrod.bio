---
type: LOD400_SPEC (draft pending team_100 registration in roadmap.yaml)
wp_id_proposed: NB-S002-P006-WP001
project: nimrod-bio
milestone: V200
program_proposed: P006 — Content Expansion (pre-cutover)
label: "Content Batch 001 — 3 string locks + 1 template prune + 11 placeholder posts"
revision_log:
  - 2026-05-26 v1.0.0 — initial draft, 13 posts
  - 2026-05-26 v1.0.1 — team_00 corrections: drop hobbithome + nimrod-book; enhance microgreens (hydroponic container) + smallfarmsagents (community + farm-mgmt + knowledge base); 11 posts net
track: A · CONTENT (data-only, no template changes)
author: team_110 (Domain Architect · cursor-composer-2)
date: 2026-05-26
predecessor: NB-S002-P005-WP001 (COMPLETE — CONDITIONAL GO)
successor: NB-S002-P005-WP002 (DEFERRED cutover — unfrozen after COMPLETION_CONTENT_PHASE)
estimated_effort: ~5 working hours (team_10)
authorization_chain:
  - team_00 directive 2026-05-26 (content phase mandate)
  - CONTENT_PHASE_INTAKE_2026-05-26_v1.0.0.md (Phase A LOCKED)
  - team_100 registration of P006-WP001 in roadmap.yaml (pending — see request alongside this draft)
---

# LOD400 — NB-S002-P006-WP001 — Content Batch 001

## 1. Mission

הוסף 13 פוסטים חדשים (12 מתוכננים + 1 placeholder לתיקון back-to-mud) ב-`/blog/`, נעל 3 מחרוזות מותגיות (טאגליין "Unless", SFA CTA, מיזו sub-brand verification), והסר את ההצמדה ל-broken link. כל זה data-only על תבניות קיימות — אין שינוי קוד / CSS / theme.json. הפלייסהולדרים יוחלפו ע"י team_00 לפני חתימת `COMPLETION_CONTENT_PHASE` (גייט סגירת השלב).

## 2. Pre-state assumed

- Dev `https://nimrod-bio-2026.s887.upress.link` — 12/13 V200 WPs COMPLETE, theme `nimrod-bio-2026` v0.4.1 active
- 22 פוסטים קיימים תחת `/blog/`, 6 seed CPT instances, 7 תבניות פעילות
- CPTs `service` + `project` פעילים; taxonomies `world` (soil/know/code) + `flow_style` (lead/wide/tall/typo/quote/feature/brief) פעילות
- WP REST API פעיל, App Password זמין ב-`.env.upress.dev` block 5
- MU plugin redirects פעיל; `/blog/back-to-mud/` כיום 404 (reference מהטמפלייט)

## 3. File scope (LOCKED — VC-3 ancestry binding)

### 3.1 Theme files (string edits)

| File | Touchpoints | Operation |
|---|---|---|
| `wp-content/themes/nimrod-bio-2026/templates/home.html` (T7) | טאגליין "Unless" — verify locked | NO-OP אם נכון; otherwise update |
| `wp-content/themes/nimrod-bio-2026/templates/about.html` (T8) | טאגליין "Unless" + מיזו mention | verify; NO-OP אם נכון |
| `wp-content/themes/nimrod-bio-2026/parts/footer.html` | "דיגיטל / מיזו" + טאגליין | verify |
| `wp-content/themes/nimrod-bio-2026/parts/services-sfa.html` (or T2 sfa block) | CTA label → "השתמש בכלי" (was "TBC") + copy | edit |
| `wp-content/themes/nimrod-bio-2026/templates/home.html` OR T1 lead block (TBD by team_10 during impl) | reference ל-`/blog/back-to-mud/` | **שמור — placeholder post יהפוך אותו לתקין** |
| Yoast meta defaults (DB option `wpseo_titles`) | meta_template containing "Unless" | verify |

**Net theme changes:** ~1 file edit (SFA CTA) + verifications. אם verification מגלה drift — diff מתועד וטיפול כ-string replace.

### 3.2 Post creates (REST API or admin UI)

11 פוסטים חדשים תחת CPT `post` (לא `service` / `project`). כולם תחת `/blog/<slug>/`. Status: **`publish`** עם placeholder marker visible (כדי שטמפלייטים יבדקו, /blog/ index ירנדר, redirects/sitemap יתעדכנו).

#### Post metadata table (v1.0.1 — corrected by team_00)

| # | slug | title (Hebrew) | world terms | flow_style | featured | source for placeholder body |
|---|---|---|---|---|---|---|
| 1 | `agents-os` | Agents-OS — מסגרת ממשל לסוכנים | code, know | feature | V300 placeholder | projects.yaml entry `agents-os` |
| 2 | `eyal-amit-2026` | אייל עמית — אתר 2026 | code | feature | V300 | projects.yaml `eyalamit` |
| 3 | `israel-microgreens` | Israel Microgreens — מכולה הידרופונית (תכנון + חקלאות) | **soil, know, code** | **lead** | V300 | team_00: "פרויקט חשוב, מכולה הידרופונית — תכנון + חקלאות" |
| 4 | `shaked-wg-agent` | Shaked WG — סוכן חיפוש בזל | code | feature | V300 | projects.yaml `shaked-wg-agent` |
| 5 | `smallfarmsagents` | SmallFarmsAgents — מערכת קהילתית לחווה אורגנית | **soil, know, code** | **lead** | V300 | team_00: "קהילתית + חזון ניהול חווה מלא + בסיס ידע לחקלאים/גננים" |
| 6 | `tiktrack-phoenix` | TikTrack Phoenix | code | brief | V300 | projects.yaml `tiktrack` |
| 7 | `agros-insite` | Agros Insite | soil | feature | V300 | projects.yaml `agros-insite` |
| 8 | `capra-mio` | Capra Mio — סוכן הפלגה | code | feature | V300 | projects.yaml `capra-mio` |
| 9 | `אנטרופיה` (URL-encoded) | אנטרופיה | know | typo | V300 | meta-essay placeholder |
| 10 | `אלה-אם-unless` (URL-encoded) | אלה אם — Unless | code, know | typo | V300 | meta-essay placeholder (ties tagline to conditional logic) |
| 11 | `back-to-mud` | Back to Mud (placeholder title) | soil | brief | V300 | minimal stub — team_00 will rename + fill |

**DROPPED מהגרסה הקודמת:**
- `hobbithome` — team_00: "לא צריך כרגע עמוד"
- `nimrod-book` — team_00: "זה הבסיס לעמוד אודות נימרוד" → יוזן לתוך עמוד `/about/` (ראה §8 FOLLOW-UP, לא חלק מהבאצ' הזה)

### 3.3 Placeholder body template (instantiated per post)

```html
<!-- nb-content-phase-001 placeholder · v1.0 · 2026-05-26 -->
<!-- replace_before: COMPLETION_CONTENT_PHASE_*.md signing -->
<div class="placeholder-notice" data-nb-placeholder="true" style="border-right:4px solid #c33; padding:.5rem 1rem; background:#fff8f0; margin-bottom:1.5rem;">
  <strong>פלייסהולדר —</strong> פוסט זה ימולא בתוכן מלא לפני cutover. תוכן זמני נגזר מ-AOS metadata.
</div>

<!-- AUTO-GENERATED SUMMARY FROM projects.yaml (or placeholder essay for entropy / אלה אם / back-to-mud) -->
<p>{{ one_sentence_summary }}</p>

<p>{{ second_sentence_about_scope_or_lifecycle }}</p>

<!-- TODO checklist for team_00 -->
<ul class="nb-placeholder-todo">
  <li>☐ פסקה ראשונה — הקשר אישי</li>
  <li>☐ פסקה שנייה — מה הפרויקט עושה</li>
  <li>☐ פסקה שלישית — איפה זה היום</li>
  <li>☐ תמונה ראשית</li>
  <li>☐ cross-links לפוסטים אחרים</li>
</ul>
```

team_10 ירכיב את `{{ one_sentence_summary }}` ו-`{{ second_sentence_about_scope_or_lifecycle }}` מתוך השדות `name` + `type` + `lifecycle_archetype` + `profile` של כל פרויקט ב-`projects.yaml`. עבור פוסטים 11/12/13 — text נפרד (ראה §3.4).

### 3.4 Special placeholder texts (posts 11, 12, 13)

**#11 אנטרופיה:**
> פלייסהולדר — אנטרופיה כעקרון מארגן בעבודה ובחיי. ימולא בפסקאות הגותיות לפני cutover.

**#12 אלה אם — Unless:**
> פלייסהולדר — "Unless" כתאי המוצא של הסיפור. ב-`else if` של חיים. ימולא לפני cutover.

**#13 back-to-mud:**
> פלייסהולדר — חזרה לבוץ. הקישור הזה היה מקושר מהדף הראשי. הפוסט נוצר עכשיו כפלייסהולדר כדי לסגור את ה-404; team_00 ימלא את התוכן ויתכן ישנה את הכותרת.

## 4. Implementation tasks (לסדר עבודה אצל team_10)

### Task 4.1 — Tagline verification & lock
- File: `templates/home.html`, `templates/about.html`, `parts/footer.html`, Yoast meta_template
- Action: grep "Unless" across theme + DB option `wpseo_titles`; report current state; אם נדרש update — replace with literal `Unless`
- Test: post-edit grep returns expected count, T7 + T8 + footer render "Unless" identically

### Task 4.2 — SFA CTA edit (Q9=A declared free)
- File: SFA service template / block (team_10 locates exact path)
- Action: CTA label → `השתמש בכלי`; copy → declared-free positioning (~30-50 מילים, lifted מ-T1 know אם רלוונטי)
- Test: visit `/services/sfa/` (assuming slug); CTA label correct; no pricing copy visible

### Task 4.3 — Mezoo sub-brand verification (Q8=A)
- File: `parts/footer.html`, `templates/about.html`
- Action: verify "דיגיטל / מיזו" appears in footer; verify single about mention; NO additions
- Test: grep returns expected count; no new Mezoo references

### Task 4.4 — 11 post creates
- Mechanism: WP REST API `POST /wp-json/wp/v2/posts` × 11
- Auth: App Password from `.env.upress.dev` (never echo to logs or chat — `feedback_secret_redaction`)
- Per post:
  - `status: publish`
  - `slug: <see table §3.2>`
  - `title.rendered: <see table>`
  - `content.raw: <instantiate template §3.3 with projects.yaml fields OR §3.4 for posts 11/12/13>`
  - `meta._nb_placeholder: true` (custom field — for verification sweep)
  - `world: [<term IDs from table>]`
  - `flow_style: <term ID from table>`
- Test: 13 × `GET /wp-json/wp/v2/posts?slug=<slug>` returns 200 with expected world/flow_style; `/blog/` index renders 22+13=35 posts; sitemap regenerated

### Task 4.5 — back-to-mud cross-reference now valid
- After Task 4.4 publishes #13 — the existing reference (T7 hero or T1 lead) now resolves 200 instead of 404
- Test: `curl -I /blog/back-to-mud/` → HTTP 200; no 404 in Lighthouse crawl

## 5. Acceptance tests

| # | Test | Expected | Tool |
|---|---|---|---|
| AT-1 | טאגליין "Unless" rendered on T7 + T8 + footer + meta | 4+ matches, all literal "Unless" | `curl + grep` |
| AT-2 | SFA service CTA text | "השתמש בכלי" rendered; no "TBC" remains | `curl /services/sfa/` |
| AT-3 | Mezoo: 1× footer + 1× about; no other mentions | grep count = 2 | `curl + grep` |
| AT-4 | 11 חדשים תחת `/blog/` | 11 × HTTP 200 | `scripts/qa/post_smoke.sh` (קיים) |
| AT-5 | /blog/ index shows 33 posts | post count = 33 (22 קיימים + 11 חדשים) | render check |
| AT-6 | Sitemap regenerated | 33+ posts ב-sitemap_index.xml | Yoast force regen |
| AT-7 | back-to-mud HTTP 200 | not 404 | curl |
| AT-8 | פלייסהולדר notice visible on each new post | 11 × `data-nb-placeholder="true"` | DOM check |
| AT-9 | אין שינוי ב-system.css / shell.css / theme.json | git diff empty for those files | git |
| AT-10 | חתימת VC-3 על file scope | scope per §3.1 — no overflow | team_190 lightweight validate |

## 6. Risks + mitigations

| Risk | Likelihood | Mitigation |
|---|---|---|
| Hebrew slug encoding issues b/c URL-encoded paths | LOW | WP handles natively; test on existing 22 posts |
| placeholder marker בעצב מכוער ב-/blog/ index | MEDIUM | inline CSS bound to `[data-nb-placeholder]`; team_10 to add light style במידת הצורך |
| team_00 מאחר עם תוכן ממלא לפני cutover | MEDIUM | team_110 verification sweep לפני COMPLETION בודק שאף `_nb_placeholder=true` לא נשאר |
| Yoast meta drift (טאגליין שונה ב-meta vs visible) | LOW | task 4.1 כולל בדיקה של option `wpseo_titles` |
| World/flow_style term ID lookup error | LOW | task 4.4 ידרוש term resolve מקדים מ-`GET /wp-json/wp/v2/world` |

## 7. Validation hook (post-build, pre-COMPLETION)

team_110 sweep before signing `COMPLETION_CONTENT_PHASE`:
```bash
# pseudo-script — to be added under scripts/qa/
for slug in agents-os eyal-amit-2026 ... אלה-אם-unless back-to-mud; do
  meta=$(curl -s "https://nimrod-bio-2026.s887.upress.link/wp-json/wp/v2/posts?slug=$slug" | jq '.[0].meta._nb_placeholder')
  if [ "$meta" = "true" ]; then echo "STILL PLACEHOLDER: $slug"; fi
done
```

`COMPLETION_CONTENT_PHASE` יכול להיחתם רק כאשר sweep מחזיר אפס "STILL PLACEHOLDER" (או team_00 מאשר במפורש שכל פלייסהולדר שנותר הוא מקובל לעלייה).

## 8. Follow-ups noted (לא בבאצ' הזה — לתיעוד)

- **About page enhancement from `nimrod-book`:** team_00 ציין ש-`nimrod-book` הוא הבסיס לעמוד `/about/`. ההזנה הזו לא חלק מהבאצ' הזה — נוצרת WP נפרדת או batch_002 לאחר שינוי scope. כרגע `/about/` נשאר עם התוכן הקיים (CONDITIONAL GO מ-P005-WP001).
- **microgreens + smallfarmsagents בעלי flow_style `lead`:** שני פוסטים כ-`lead` חורג מההמלצה הראשונית של "1 lead per category". team_110 מאשר ארכיטקטונית — אין מגבלת מספר ב-flow_style; ה-template T4 יטפל בשני lead-style posts. Yoast meta נשאר זהה לכל פוסט.

## 9. Out-of-scope (V300 territory, אסור לגעת)

- Lighthouse A11y / BP uplift
- Mobile-specific tweaks
- T-03 watercolor backgrounds
- T-04 logo family
- מסעדות עוגן (Q-05 → V300)
- מקומות הוראה (Q-03 → V300)
- רענון 22 הפוסטים המהוגרים
- services / projects חדשים
- DNS / cutover (P005-WP002 — מוקפא)
- שינויי design system (system.css / shell.css / theme.json)

## 10. Dependencies before MANDATE issues

- [ ] team_100 רושם `NB-S002-P006-WP001` ב-`_aos/roadmap.yaml` + מעביר את ה-LOD400 הזה (או copy) ל-`_aos/work_packages/NB-S002-P006-WP001/`
- [ ] team_00 מאשר את ה-titles + slugs בטבלה §3.2 (או מספק תיקונים)
- [ ] term IDs של `world` + `flow_style` חיים ב-DB (sanity API call)

— team_110 (cursor-composer-2) — 2026-05-26
