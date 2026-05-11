---
type: MANDATE
from: team_100 (nimrodbio_arch)
to: team_10 (nimrodbio_build)
wp_id: NB-S001-P003-WP001
date: "2026-05-11"
gate: L-GATE_BUILD
priority: HIGH
---

# MANDATE — NB-S001-P003-WP001 — MU Plugin Deployment + Stack Activation

**לצוות 10 (Builder — Cursor):**

V100 הושלם. השרת נוקה. השלב הבא: השלמת תשתית ה-SFA integration על nimrod.bio והפעלת ה-stack מלא.

פרויקט: `/Users/nimrod/Documents/nimrod-bio`

---

## מה מצפים ממך

### משימה 1 — הוסף `sfagent-file-upload.php` לרפו

הקובץ קיים ב-SFA project אך חסר ב-nimrod-bio:

```bash
cp /Users/nimrod/Documents/SmallFarmsAgents/wp-content/mu-plugins/sfagent-file-upload.php \
   /Users/nimrod/Documents/nimrod-bio/nimrod.bio/wp-content/mu-plugins/
```

- commit לmain עם מסר: `feat(mu-plugins): add sfagent-file-upload REST endpoint`
- push ל-GitHub (`git push origin main:master`)

**נימוק:** הקובץ הוא תשתית קבועה של האתר (REST endpoint `/wp-json/sfagent/v1/upload`).
חייב להיות ב-git כדי שישרוד `restore-production-from-backup.sh` עתידי.

---

### משימה 2 — פרוס ל-production uPress

העלה את `sfagent-file-upload.php` לuPress דרך FTPS:

**credentials:** `/Users/nimrod/Documents/SmallFarmsAgents/.env.upress`
- `UPRESS_SFTP_HOST`, `UPRESS_SFTP_USER`, `UPRESS_SFTP_PASS`
- port: 21, TLS (prot_p, no cert verify)

**⚠️ דרישה לפני חיבור:** Team 00 חייב לאשר ה-IP הנוכחי בממשק uPress (FTP Accounts → Allowlist).
**IP הנוכחי:** `185.194.185.8` — בקש אישור מ-Team 00 לפני הרצה.

**נתיב יעד על השרת** (יחסי ל-FTP root = WordPress root):
```
wp-content/mu-plugins/sfagent-file-upload.php
```

script לפריסה:
```python
import os, ftplib, ssl
from dotenv import load_dotenv
load_dotenv('/Users/nimrod/Documents/SmallFarmsAgents/.env.upress')
host = os.getenv('UPRESS_SFTP_HOST')
user = os.getenv('UPRESS_SFTP_USER')
pw   = os.getenv('UPRESS_SFTP_PASS')
ctx  = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode    = ssl.CERT_NONE
ftp = ftplib.FTP_TLS(timeout=20)
ftp.connect(host, 21)
ftp.login(user, pw)
ftp.prot_p()
src = '/Users/nimrod/Documents/nimrod-bio/nimrod.bio/wp-content/mu-plugins/sfagent-file-upload.php'
with open(src, 'rb') as f:
    ftp.storbinary('STOR wp-content/mu-plugins/sfagent-file-upload.php', f)
print('Uploaded OK')
ftp.quit()
```

---

### משימה 3 — אמת על השרת

לאחר העלאה, בדוק:
```bash
curl -s -o /dev/null -w "%{http_code}" \
  https://nimrod.bio/wp-json/sfagent/v1/upload
# צפוי: 401 (endpoint קיים, לא 404)
```

---

### משימה 4 — הרץ validate_aos.sh

```bash
cd /Users/nimrod/Documents/nimrod-bio
bash _aos/lean-kit/modules/validation-quality/scripts/validate_aos.sh .
# חייב: 0 FAIL
```

---

### משימה 5 — COMPLETION report

כתוב `_COMMUNICATION/team_10/COMPLETION_NB-S001-P003-WP001.md` עם:
- ✓/✗ לכל 4 משימות
- תוצאת validate_aos.sh
- תוצאת curl לendpoint
- כל חריגה + הסבר

---

## מצב נוכחי לידיעתך

| רכיב | מצב |
|------|-----|
| `sfagent-allow-json.php` | ✅ כבר ב-mu-plugins (בbackup) |
| `sfagent-file-upload.php` | ❌ חסר — זו המשימה |
| `smallfarmsagents/` dir על uPress | ייווצר אוטומטית ב-upload הראשון של SFA |
| local stack `http://localhost:8085` | ✅ פעיל |
| production server | ✅ נוקה (Agents/, sfa-hub/ וכו' נמחקו) |
| GitHub `master` | ✅ מסונכרן |

---

## Iron Rules

- credentials לא ב-git
- push רק אחרי validate_aos.sh 0 FAIL
- שמור את ה-COMPLETION ב-`_COMMUNICATION/team_10/` בלבד

*nimrod-bio | Team 100 → Team 10 | 2026-05-11*
