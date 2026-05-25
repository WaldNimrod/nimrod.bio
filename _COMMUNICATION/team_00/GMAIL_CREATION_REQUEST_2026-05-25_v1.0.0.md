---
type: REQUEST
from: team_100 (nimrodbio_arch)
to: team_00 (Nimrod · Principal)
project: nimrod-bio
milestone: V200
wp_id: NB-S002-P005-WP001
date: 2026-05-25
priority: HIGH (blocks team_10 fix cycle)
estimated_user_time: 10-15 min
related_decision: DECISION_V200_SMTP_2026-05-25_v1.0.0.md
---

# REQUEST — Create dedicated Gmail for nimrod.bio SMTP

לפי DECISION Q2 = B, צריך Gmail ייעודי + App Password.

## פעולה נדרשת ממך (~10-15 דקות)

### 1. צור חשבון Gmail חדש

URL: `https://accounts.google.com/signup`

- שם משתמש מוצע: `nimrod.bio.ops@gmail.com` (אם תפוס — `nimrodbio.ops@gmail.com` או `nimrod.bio.mail@gmail.com`)
- שם פרטי: `nimrod.bio` · שם משפחה: `ops`
- ⚠️ זהו account שירות (לא אישי) — שמור את הסיסמה ב-password manager
- מספר טלפון אימות: נדרש פעם אחת

### 2. הפעל 2FA (חובה לפני App Password)

URL: `https://myaccount.google.com/security`

- "2-Step Verification" → Get Started → אמת את הטלפון/אפליקציית authenticator

### 3. צור Gmail App Password

URL: `https://myaccount.google.com/apppasswords` (זמין רק אחרי שה-2FA הופעל)

- App name: `nimrod.bio WP Mail SMTP`
- Google ייצור סיסמה של 16 תווים בלי רווחים (לדוגמה `abcdefghijklmnop`)
- ⚠️ **תוצג פעם אחת בלבד** — העתק מיד

### 4. תעד את הפרטים

צור או עדכן את `/Users/nimrod/Documents/nimrod-bio/.env.upress.dev` והוסף בלוק חדש (אם עוד לא קיים):

```bash
# ─────────────────────────────────────────────────────────────
# 10. SMTP (added 2026-05-25 — V200 P005-WP001 scope expansion)
# ─────────────────────────────────────────────────────────────
# Reference only. The active credentials live in WP DB via wp-mail-smtp
# plugin (configured manually by team_00 via admin UI).
# This block exists so team_10 / team_190 know which Gmail was set up.

SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_ENCRYPTION=TLS
SMTP_USERNAME=                  # ⬅ fill: e.g. nimrod.bio.ops@gmail.com
SMTP_FROM_EMAIL=nimrod@mezoo.co
SMTP_FROM_NAME=nimrod.bio
# SMTP_PASSWORD — NOT stored in env. Lives in wp-mail-smtp plugin (DB).
```

⚠️ **אל תכתוב את ה-App Password ל-`.env.upress.dev`** — הוא יוכנס ישירות ב-WP admin UI ב-Phase B (אחרי שה-plugin יותקן).

### 5. תגיד "Gmail מוכן" ב-chat

עם זה team_10 יוצא לפעולה (installs wp-mail-smtp plugin via REST). תוך 5 דקות תקבל קישור ל-WP admin שבו תיכנס ותגדיר את הplugin עם ה-credentials.

## תזרים מלא

```
[1] You: create Gmail (now)
  ↓
[2] You: confirm "Gmail מוכן" + SMTP_USERNAME ב-env
  ↓
[3] team_10: install wp-mail-smtp via REST
  ↓
[4] You: log into wp-admin → WP Mail SMTP settings → enter creds
  ↓
[5] You: confirm "config saved"
  ↓
[6] team_10: A12 retest — submit contact form
  ↓
[7] You: confirm "email arrived at nimrod@mezoo.co"
  ↓
[8] team_10: update CUTOVER_READINESS_REPORT
  ↓
[9] team_100: re-issue VALIDATE_REQUEST to team_190
  ↓
[10] team_190: ratify CONDITIONAL GO → P005-WP002 unblocked
```

## אם משהו לא עובד

- Gmail signup requires browser + phone — אם בעיה: דווח ל-team_100
- App Password לא זמין אם 2FA לא פעיל — ודא הפעלת step 2 לפני step 3
- אם רוצים שם משתמש שונה — לציין בעת ה"מוכן" ל-team_100

— team_100 (nimrod-bio) — 2026-05-25
