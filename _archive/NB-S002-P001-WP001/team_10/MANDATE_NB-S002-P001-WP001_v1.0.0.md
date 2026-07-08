---
type: MANDATE
from: team_100 (nimrodbio_arch — Chief Architect)
to: team_10 (nimrodbio_build — Domain Builder, Cursor)
wp_id: NB-S002-P001-WP001
project: nimrod-bio
milestone: V200
date: 2026-05-25
gate: L-GATE_BUILD (to be exited at task completion)
track: A · STANDARD
priority: HIGH
predecessor: NB-S001-P003-WP001 (COMPLETE)
authorization: DECISION_V200_OPEN_QUESTIONS_2026-05-25_v1.0.0.md (team_00 sign-off)
---

# MANDATE — NB-S002-P001-WP001 — uPress dev environment preparation

**לצוות 10 (Builder — Cursor):**

V200 התחיל. החלטות סגורות. זה ה-WP הראשון מתוך 13 ב-milestone V200. ה-MANDATE הזה מכין את הקרקע — fresh WordPress install על uPress dev URL, אודיט תוספים, ותיעוד מצב התחלתי.

הפרויקט: `/Users/nimrod/Documents/nimrod-bio`
המסמך הראשי של V200: `_aos/work_packages/S002/LOD300_V200_milestone.md`

---

## הקשר

- אתר ישן (Flatsome) פעיל ב-`https://nimrod.bio` ולא נוגעים בו.
- אתר חדש ייבנה על dev URL: `https://nimrod-bio-2026.s887.upress.link` (תעודת SSL פגה — להשתמש ב-HTTP או לאשר אזהרה).
- ה-uPress provision כבר בוצע ע״י team_00 ב-2026-05-24.
- backup מלא של prod בוצע ע״י team_00 (לא באחריותך).

---

## מה מצפים ממך

### משימה 1 — Audit מצב dev env הנוכחי

היכנס לממשק uPress של `nimrod-bio-2026` והוצא אודיט של מה שהותקן אוטומטית:

1. **תוספים פעילים שמוצבים אוטומטית** ע״י uPress (אם יש).
2. **תוצאת התקנה**: גרסת WP, גרסת PHP, theme פעיל, locale (אמור להיות עברית).
3. **uPress SuperCache** — האם פעיל? איך מוגדר?
4. **uPress Web Firewall** — איך מוגדר? יש exceptions ל-IP שלך לפיתוח?
5. **Auto backups** — מתי הוגדר, retention.
6. **DB credentials** — סיסמת WP/DB ייעודיות ל-`nimrod-bio-2026`.
7. **SFTP/SSH credentials** — לאחסון ב-`.env.upress.dev` (אל **תוסיף** ל-git).

**Deliverable:** `docs/upress_control_panel_audit.md` עם 7 הסעיפים מלא + 3-5 צילומי מסך של הממשק.

---

### משימה 2 — Fresh WordPress install (התקנה נקייה)

⚠️ **לפני שמתחיל:** ודא ש-uPress provision לא הביא איתו אתר עם תוכן ישן. אם כן — דרוש מ-team_00 (Nimrod) לבצע "reset to clean install" דרך ממשק uPress לפני שאתה מתחיל.

לאחר אישור install נקי:

1. **WP version:** 6.7+ (latest stable מ-uPress).
2. **PHP:** 8.3 (כפי שזוהה).
3. **Locale:** עברית (`he_IL`).
4. **Site title:** "nimrod.bio · V200 dev" (זמני).
5. **Tagline:** "DEV — do not index" (תאמת `X-Robots-Tag: noindex` שכבר מוצב ע״י uPress).
6. **Permalinks:** `/%postname%/` (כן — אבל ראה משימה 4 לגבי `/blog/` prefix).
7. **Default theme:** השאר את שנת ברירת המחדל (`twentytwentyfive`) זמנית — נחליף ב-WP002.
8. **Admin user:** לא `admin`. הצע שם משתמש לאישור team_00.
9. **Discourage search engines:** ON (תיבת הסימון ב-WP settings).

**Deliverable:** סקריפט `scripts/wp_dev_baseline.sh` שמתעד את כל הגדרות wp-cli שביצעת (idempotent), + קובץ `_COMMUNICATION/team_10/WP_DEV_BASELINE_v1.md` עם המצב הסופי.

---

### משימה 3 — Basic auth ב-edge (uPress) או .htpasswd

dev URL חייב להיות לא-נגיש לציבור הרחב מעבר ל-`noindex` (שמונע אינדקס, לא גישה). הוסף שכבת auth:

**אופציה A (מועדפת):** uPress access restriction לפי IP (אם מאופשר בממשק).
**אופציה B:** `.htpasswd` ב-root של ה-dev. username:`dev`, password: יצירה אקראית, שמירה ב-`.env.upress.dev` שלא ב-git.

**Deliverable:** הוכחה ש-`curl https://nimrod-bio-2026.s887.upress.link/` מחזיר 401 לבלי auth, ו-200 עם auth.

---

### משימה 4 — Permalink validation לטובת `/blog/` prefix

על-פי החלטת team_00 (`DECISION_V200_OPEN_QUESTIONS_2026-05-25`), כל פוסטים באתר החדש יהיו תחת `/blog/<slug>/`. WordPress תומך בזה מובנה דרך:

```
Settings → Permalinks → Common Settings → Custom Structure: /blog/%postname%/
```

ודא שזה עובד:
1. הגדר את ה-permalink structure ל-`/blog/%postname%/`.
2. צור פוסט בדיקה עם slug `hello-world`.
3. וודא URL נוצר: `https://nimrod-bio-2026.s887.upress.link/blog/hello-world/`.
4. וודא שעמודים (pages) **לא** מקבלים את ה-prefix — `Sample Page` נשארת ב-`/sample-page/`.

**Deliverable:** screenshots או curl logs ב-COMPLETION report.

---

### משימה 6 — Enable Application Passwords over HTTP (dev only)

**הקשר:** WordPress חוסם יצירת Application Passwords כברירת מחדל כשאין HTTPS תקין (תעודת ה-dev URL פגה). team_00 לא יכול ליצור App Password מהממשק. צריך פתרון אוטומטי שייכנס כחלק מ-baseline ה-dev.

**מה לעשות:**

1. **הוסף קבוע ל-`wp-config.php`** ב-dev server (לפני `/* That's all, stop editing! */`):
   ```php
   define( 'WP_ENVIRONMENT_TYPE', 'local' );
   ```

2. **צור MU plugin** `nimrod.bio/wp-content/mu-plugins/nb-dev-app-passwords.php`:
   ```php
   <?php
   /**
    * Plugin Name: NB Dev — App Passwords over HTTP
    * Description: Allows WP Application Passwords on the uPress dev URL (no valid SSL).
    *              Active only when WP_ENVIRONMENT_TYPE is 'local' or 'development'.
    *              DO NOT ship to production.
    */
   if ( ! defined( 'ABSPATH' ) ) exit;

   add_filter( 'wp_is_application_passwords_available', function( $available ) {
       $env = function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production';
       return in_array( $env, [ 'local', 'development' ], true ) ? true : $available;
   } );

   // Visual marker in admin bar so we never confuse dev with prod
   add_action( 'admin_bar_menu', function( $bar ) {
       $env = function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production';
       if ( in_array( $env, [ 'local', 'development' ], true ) ) {
           $bar->add_node( [
               'id'    => 'nb-dev-marker',
               'title' => '⚠ DEV — ' . strtoupper( $env ),
               'meta'  => [ 'class' => 'nb-dev-marker' ],
           ] );
       }
   }, 999 );

   add_action( 'admin_head', function() {
       echo '<style>#wpadminbar #wp-admin-bar-nb-dev-marker > .ab-item { background:#d23a2e !important; color:#fff !important; font-weight:700; }</style>';
   } );
   ```

3. **דלוק לראות שמתבטל הצורך ב-HTTPS:** היכנס ל-`/wp-admin/profile.php` כ-admin, גלול ל-Application Passwords — הסקציה כעת זמינה.

4. **צור Application Password** עם השם `aos-publisher-dev`.

5. **החזר לקובץ הסביבה** (אני אעדכן את הקובץ; אתה רק תספק לי את הסיסמה):
   ```
   WP_REST_USER=sb0233051_admin
   WP_REST_APP_PASSWORD='abcd EFGH 1234 wxyz 5678 ijkl'
   ```

6. **בדוק REST API:**
   ```bash
   curl -u "sb0233051_admin:<app-password>" \
        https://nimrod-bio-2026.s887.upress.link/wp-json/wp/v2/users/me -k
   ```
   צריך להחזיר JSON של המשתמש (לא 401).

⚠️ **MU plugin זה לא יעבור ל-production** — שם `WP_ENVIRONMENT_TYPE='production'` (ברירת מחדל) חוסם אוטומטית.

---

### משימה 5 — validate_aos.sh + git hygiene

1. ודא ש-`bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .` מחזיר 0 FAIL (חוץ מ-drift קיים ב-`_aos/definition.yaml` שאינו באחריותך — זה תוצאה של GCR closure ע״י hub team_200).
2. commit אך **רק** קבצים שיצרת. אל תיגע ב-uncommitted drift הקיים.
3. push ל-main.

---

## What's out of scope (לא לעשות)

- **לא להתקין תוסף עיצוב/בנייה** — לא Elementor, לא Divi, לא Bricks.
- **לא להחליף theme** — נשארים על default עד WP002.
- **לא להתקין SEO plugin** עדיין — Yoast יותקן רק ב-P004-WP002.
- **לא לייבא תוכן** — content migration ב-P004-WP001.
- **לא להוסיף ACF / CPT-UI / Pods** — החלטה D של team_00 היא native code, יבוא ב-WP002-WP002.
- **לא לגעת בפרודקשן** `nimrod.bio` בשום צורה.

---

## Exit criteria — L-GATE_BUILD

WP מוחזר ל-team_100 כשכל הבאים מוצבים ב-COMPLETION report:

- [ ] `docs/upress_control_panel_audit.md` קיים, מלא, מקוטעי-screenshot.
- [ ] `scripts/wp_dev_baseline.sh` idempotent + מתועד.
- [ ] `_COMMUNICATION/team_10/WP_DEV_BASELINE_v1.md` מתאר מצב סופי.
- [ ] `curl https://nimrod-bio-2026.s887.upress.link/` מאחורי auth (401 בלי, 200 עם).
- [ ] Permalink `/blog/%postname%/` עובד עם פוסט בדיקה.
- [ ] `validate_aos.sh` 0 FAIL (drift קיים מותר).
- [ ] `wp-config.php` כולל `WP_ENVIRONMENT_TYPE = 'local'`.
- [ ] MU plugin `nb-dev-app-passwords.php` קיים, ה-admin bar מציג תווית אדומה "⚠ DEV".
- [ ] Application Password נוצר, נבדק עם curl, ערך הסיסמה הועבר ל-team_00 / team_100 ב-COMPLETION (ערך הסיסמה יכנס ל-`.env.upress.dev` שאינו ב-git).
- [ ] git: commit + push לכל deliverables שלך, ללא נגיעה ב-drift חיצוני.
- [ ] COMPLETION report ב-`_COMMUNICATION/team_10/COMPLETION_NB-S002-P001-WP001.md`.

---

## L-GATE_VALIDATE — לא נדרש ל-WP זה

הוחלט: OPS/Express track. ה-WP reversible (התקנה נקייה, אין content). team_100 self-review ב-COMPLETION + Express PASS לאחר ביקורת.

---

## תזמון

- **Start:** מיד עם קבלת MANDATE.
- **Target completion:** 2 ימי עבודה.
- **Status update:** ב-_COMMUNICATION/team_10/ אם חוסם משהו מעבר ל-4 שעות.

---

## Reference

- LOD300 milestone: `_aos/work_packages/S002/LOD300_V200_milestone.md`
- Team 00 decisions: `_COMMUNICATION/team_00/DECISION_V200_OPEN_QUESTIONS_2026-05-25_v1.0.0.md`
- Design package: `sources/team_35_design_package/_handoff/`
- Triage results: `docs/url_migration_decisions_2026-05-25.json`
- CLAUDE.md domain rules (uPress quirks + dev env)

— team_100 (nimrod-bio) — 2026-05-25
