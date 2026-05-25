# uPress FTPS Mandatory Procedure (nimrod-bio)

Status: ACTIVE  
Applies to: כל פריסה ל-uPress דרך FTP/FTPS בדומיין `nimrod-bio`  
Source protocol: `_aos/lean-kit/modules/12-home-server-infrastructure/runbooks/UPRESS_FTPS_PROTOCOL_v1.0.0.md`  
Operational precedent: SFA deployment flow (resolved FTP instability via canonical protocol + allowlist discipline)

## Why this is mandatory

ניסיונות FTPS "רגילים" (במיוחד `lftp`/`prot_p`) הובילו לחסימות/כשלי data channel או timeout.
הפרוטוקול המחייב שנבדק ב-SFA ונכנס ל-runbook הארגוני הוא:

1. בדיקת IP ציבורי לפני חיבור.
2. אימות שה-IP נמצא ב-allowlist של uPress.
3. חיבור FTPS מפורש לפורט 21.
4. `FTP_TLS` עם `prot_c()` (לא `prot_p()`), ו-`PASV`.

אין לסטות מהזרימה הזו ללא אישור architecture (team_100).

## Mandatory preflight (before every upload)

1. טען משתנים:
   - `set -a; source .env.upress.dev; set +a`
2. בדוק IP ציבורי IPv4:
   - `curl -4 -s ifconfig.me`
3. אמת שה-IP מופיע ב-`UPRESS_FTP_ALLOWED_IPS` וגם מעודכן ב-uPress panel:
   - FTP Accounts -> Allowlist
4. בצע probe לפורט:
   - נדרש מעבר TCP לפורט 21 ל-`UPRESS_FTP_HOST`.

אם אחד מהסעיפים נכשל: **עוצרים** ומעדכנים allowlist/גישה לפני כל ניסיון deploy נוסף.

## Mandatory deploy command

להעלאת theme (WP001):

```bash
python3 scripts/upress_ftps_upload.py \
  --env-file .env.upress.dev \
  --local-dir nimrod.bio/wp-content/themes/nimrod-bio-2026 \
  --remote-dir wp-content/themes/nimrod-bio-2026
```

להעלאת MU plugins:

```bash
python3 scripts/upress_ftps_upload.py \
  --env-file .env.upress.dev \
  --local-dir nimrod.bio/wp-content/mu-plugins \
  --remote-dir wp-content/mu-plugins
```

## Implementation standard (binding)

כל כלי FTPS חדש בדומיין הזה חייב לעמוד בדרישות:

- שימוש ב-`ftplib.FTP_TLS` עם TLS context שמכבה cert verify (`CERT_NONE`) עבור uPress.
- סדר פעולות קבוע:
  - `connect()`
  - `login()`
  - `prot_c()`
  - `set_pasv(True)`
- איסור שימוש ב-`prot_p()` כ-default ב-uPress.
- בדיקת allowlist IP לפני חיבור (אוטומטית או ידנית עם חסימה קשיחה בכשל).

## Fallback policy

אם FTPS ממשיך להיכשל אחרי preflight תקין:

1. עוברים ל-uPress File Manager להעלאה ידנית.
2. מתעדים את התקלה ב-`_COMMUNICATION/team_10/` עם evidence (פקודות/פלט).
3. מעדכנים את הנוהל רק אחרי שחזור יציב ואישור team_100.

## Evidence minimum for completion

בדוח BUILD חייב להופיע:

- הוכחת preflight (IP + allowlist).
- הוכחת upload (file listing/HTTP 200 של `style.css` או קובץ יעד אחר).
- אם הייתה חריגה - root cause + remediation.
