---
type: REQUEST
from: team_100 (nimrodbio_arch)
to: team_00 (Nimrod · Principal)
project: nimrod-bio
milestone: V200
wp_id: NB-S002-P005-WP001
date: 2026-05-25
priority: HIGH (blocks team_10 fix cycle)
estimated_user_time: 10 min
supersedes: GMAIL_CREATION_REQUEST_2026-05-25_v1.0.0.md (removed)
related_decision: DECISION_V200_SMTP_2026-05-25_v1.0.0.md (amended Q2=C)
---

# REQUEST — Create `contact@nimrod.bio` mailbox in uPress

לפי DECISION amended (Q2=C, Q3=B), אנו משתמשים ב-uPress native email במקום Gmail. דרושה פעולה ידנית של ~10 דקות בקונטרול פאנל של uPress.

## פעולה נדרשת ממך (~10 דקות)

### 1. כנס לcontrol panel של uPress

ה-URL לפאנל של `nimrod-bio-2026.s887.upress.link` (אם אינך זוכר — מה-uPress dashboard הראשי).

### 2. נווט ל-Email Accounts (או "ניהול דואר" / "Mail")

זה במסך הראשי או תחת "שירותים" / "Services". המינוח המדויק תלוי בגרסת ה-UI הנוכחית.

### 3. צור mailbox חדש

- **Local part:** `contact`
- **Domain:** `nimrod.bio`
- **Password:** ייצור סיסמה חזקה דרך כפתור "Generate" אם זמין; שמור ב-password manager (לא בקובץ פלאט!)
- **Quota:** דיפולט מהפלאן (אין צורך לשנות)
- **Forwarding (optional):** אם תרצה ש-emails שיגיעו ל-`contact@nimrod.bio` יועברו גם ל-`nimrod@mezoo.co` שלך — הוסף forwarding rule

### 4. רשום את פרטי ה-SMTP

uPress יציג את הפרטים אחרי יצירת הmailbox (לרוב במסך "הגדרות חיבור" / "Mail Configuration" / "SMTP/IMAP"). חפש:

- **SMTP Host** (לדוגמה: `smtp.upress.co.il`, `mail.nimrod.bio`, או דומה)
- **SMTP Port** (587 STARTTLS או 465 SSL)
- **Authentication:** username = `contact@nimrod.bio` (full address)
- **Encryption:** TLS (port 587) או SSL (port 465)

### 5. עדכן את `.env.upress.dev` עם ה-host/port (לא הסיסמה!)

בקובץ `/Users/nimrod/Documents/nimrod-bio/.env.upress.dev` בלוק 10 SMTP — תעדכן:

```bash
SMTP_HOST=<exact uPress SMTP host from step 4>     # ⬅ fill
SMTP_PORT=<587 or 465>                              # ⬅ confirm
SMTP_ENCRYPTION=<TLS or SSL>                        # ⬅ match port
SMTP_USERNAME=contact@nimrod.bio                    # ⬅ already known
SMTP_FROM_EMAIL=contact@nimrod.bio                  # ⬅ matches Q3=B
SMTP_FROM_NAME=nimrod.bio
# SMTP_PASSWORD — נשמרת ב-WP DB דרך הplugin בשלב הבא, לא ב-.env
```

### 6. תגיד "mailbox מוכן" ב-chat

עם אישור זה team_10 יוצא לפעולה:
- מתקין wp-mail-smtp plugin via REST
- שולח לך קישור ל-`https://nimrod-bio-2026.s887.upress.link/wp-admin/admin.php?page=wp-mail-smtp`
- אתה נכנס, מזין את ה-host/port/username/password (ה-password = של ה-mailbox שיצרת), שומר
- team_10 מבצע A12 retest

## אם נתקלת בבעיה

- **uPress plan לא כולל email** → דווח מיד, נחזור לאופציית Gmail (B המקורי) או נחקור uPress add-on
- **לא מוצא Email Accounts בפאנל** → צילום מסך של ה-dashboard ואשלח לך תיעוד uPress
- **רוצה שם אחר מ-contact@** → תציין בעת ה"מוכן" (לדוגמה `info@`, `hello@`, `nimrod@`)

## תזרים מלא (post-Gmail-cancellation)

```
[1] team_00: צור mailbox ב-uPress       ← NEXT (you)
[2] team_00: רשום SMTP host/port ב-.env.upress.dev
[3] team_00: confirm "mailbox מוכן"
[4] team_10: install wp-mail-smtp via REST
[5] team_00: enter creds in WP admin UI (host/port/user/password)
[6] team_00: confirm "config saved"
[7] team_10: A12 retest — form submit
[8] team_00: confirm "email arrived"
[9] team_10: update REPORT + COMPLETION addendum
[10] team_100: re-issue VALIDATE_REQUEST v1.0.1 to team_190
[11] team_190: VERDICT → P005-WP002 unblocked
```

ETA לסיום SMTP: ~1.5 שעות מ-step 1.

— team_100 (nimrod-bio) — 2026-05-25
