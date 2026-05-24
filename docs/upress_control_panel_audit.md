# uPress Control Panel Audit — nimrod-bio-2026

**WP:** NB-S002-P001-WP001  
**Date:** 2026-05-25  
**Auditor:** team_10 (nimrodbio_build)  
**Dev URL:** http://nimrod-bio-2026.s887.upress.link  
**Note:** HTTPS certificate on the dev URL is expired/invalid — all CLI probing done over HTTP. Screenshots from the uPress control panel are pending manual capture by team_00.

---

## 1. Auto-installed plugins (uPress provision)

**Result via REST API (authenticated):** No active plugins found on fresh install.

```json
GET /wp-json/wp/v2/plugins?status=active → []
```

uPress provisions a clean WP install with no pre-activated third-party plugins. The following uPress-native files are present in `wp-content/` (not standard plugins — part of hosting infrastructure):

| File | Purpose |
|---|---|
| `wp-content/advanced-cache.php` | uPress SuperCache drop-in |
| `wp-content/object-cache.php` | uPress object cache drop-in |
| `wp-content/ezcache-config.json` | SuperCache configuration |
| `wp-content/mu-plugins/booter-crawlers-manager-mu.php` | uPress crawler/bot management MU plugin |
| `wp-content/mu-plugins/sfagent-allow-json.php` | nimrod-bio custom MU plugin (from NB-S001) |
| `wp-content/mu-plugins/sfagent-file-upload.php` | nimrod-bio custom MU plugin (from NB-S001) |

---

## 2. Installation state

| Parameter | Value | Source |
|---|---|---|
| WordPress version | **7.0** | `<meta name="generator" content="WordPress 7.0">` |
| PHP version | **8.3** | Per team_00 provision report (2026-05-24) |
| Active theme | **Twenty Twenty-Five 1.5** | REST API `/wp/v2/themes?status=active` |
| Locale | English (default) — `he_IL` set via form but requires language pack download to confirm | Admin form |
| Site title | nimrod.bio · V200 dev | REST API settings |
| Tagline | DEV — do not index | REST API settings |
| Admin user | `sb0233051_admin` | Created during provision |
| Admin email | `admin@mezoo.co` | REST API settings |
| Search engine discouragement | `blog_public=0` set via form | Admin form |
| X-Robots-Tag at edge | `noindex, nofollow` | `curl -I` response header |

**✓ WP 7.0 > required 6.7+. Clean install confirmed (no Flatsome, no old content).**

---

## 3. uPress SuperCache

**Status:** Active via drop-in.

Evidence:
- `wp-content/advanced-cache.php` present (SuperCache page-level drop-in)
- `wp-content/object-cache.php` present (object cache drop-in)
- `wp-content/ezcache-config.json` present (SuperCache config file)

**⚠ Screenshots of control panel configuration pending — team_00 to capture from uPress panel → Performance / SuperCache section.**

---

## 4. uPress Web Firewall

**Status:** Presumed active (uPress default for all sites).

Evidence: The uPress platform activates its Web Firewall by default. Previous NB-S001 experience confirmed that POST requests without Content-Type header are intercepted by Cloudflare WAF before reaching WordPress (returned 400 instead of WP's 401). This is consistent behavior with the firewall active.

**⚠ Screenshots pending — team_00 to capture firewall configuration and any dev IP exceptions from uPress panel.**

---

## 5. Auto backups

**Status:** Presumed active (uPress 30-day retention default).

**⚠ Screenshots pending — team_00 to capture backup schedule from uPress panel → Backups section.**

---

## 6. DB credentials (dev environment)

Source: `.env.upress.dev` (not in git).

| Parameter | Value |
|---|---|
| DB host | localhost |
| DB name | sb0233051_up1 |
| DB user | sb0233051_up1 |
| DB pass | (in .env.upress.dev) |
| Table prefix | wp_ (default fresh install) |
| Charset | utf8mb4 |
| Collation | utf8mb4_unicode_ci |
| Remote access | false (MySQL bound to localhost) |

---

## 7. SFTP/SSH credentials

Source: `.env.upress.dev`.

| Parameter | Value |
|---|---|
| FTP host | ftp.s887.upress.link |
| FTP port | 21 (FTPS explicit) |
| FTP user | dev@nimrod-bio-2026.s887.upress.link |
| FTP pass | (in .env.upress.dev) |
| IP allowlist | 147.235.203.51 (confirmed current IP) |
| SSH | Not available (uPress plan does not provide SSH) |

**⚠ BLOCKER: FTP login failed with `530 Login incorrect` despite correct IP. Username format `dev@nimrod-bio-2026.s887.upress.link` may be incorrect. Team_00 must verify FTP account credentials from uPress control panel → FTP Accounts section.**

---

## Screenshots pending (team_00 manual capture required)

The following 5 screenshots are required for full audit completion:

1. `upress_panel_overview.png` — Main site overview page (WP version, PHP, disk usage)
2. `upress_supercache_config.png` — SuperCache settings page
3. `upress_firewall_config.png` — Web Firewall settings / exceptions
4. `upress_backups_config.png` — Auto backup schedule and retention
5. `upress_ftp_accounts.png` — FTP accounts list (to verify correct username format)

---

*Audit by team_10 (nimrodbio_build) — 2026-05-25*
