---
type: MANDATE
from: team_100 (nimrodbio_arch)
to: team_10 (nimrodbio_build)
wp_id: NB-S001-P002-WP001
date: "2026-05-09"
gate: L-GATE_BUILD
priority: HIGH
---

# MANDATE — NB-S001-P002-WP001 — Environment Bootstrap

**לצוות 10 (Builder — Cursor):**

יש לבצע את WP2 לפי LOD400 המצורף.
הפרויקט: `/Users/nimrod/Documents/nimrod-bio`

## מה מצפים ממך

1. Docker Compose stack (WordPress:8085 + MySQL:3309) — **ports אלו בלבד**
2. `scripts/restore-production-from-backup.sh` — שחזור מ-`sources/` (Team 00 יניח ZIP+SQL)
3. `scripts/verify-connections.sh` — smoke test
4. `.gitignore` מלא
5. `nimrod.bio/` staged תחת git (ללא uploads/admin/includes/wp-config)
6. `validate_aos.sh` — **0 FAIL חובה**
7. Completion report ב-`_COMMUNICATION/team_10/COMPLETION_NB-S001-P002-WP001.md`

## Reference

- **LOD400:** `_aos/work_packages/NB-S001-P002-WP001/LOD400_NB-S001-P002-WP001.md`
- **Precedent:** `/Users/nimrod/Documents/HobbitHome/` — אותו pattern בדיוק
  - `docker-compose.yml`
  - `scripts/restore-production-from-backup.sh`
  - `scripts/verify-connections.sh`

## Gate Submission

כשסיימת: כתוב `_COMMUNICATION/team_10/COMPLETION_NB-S001-P002-WP001.md`.
Team 100 מבצע L-GATE_BUILD review. Team 190 מאחר לכן L-GATE_VALIDATE.

*nimrod-bio | Team 100 → Team 10 | 2026-05-09*
