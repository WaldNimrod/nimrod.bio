# PROJECT CONTEXT — nimrod.bio — Personal Site (WordPress)

## AOS environment (read first)

- **Repository role:** spoke (hub = agents-os methodology source; spoke = product snapshot consumer)
- **Profile:** L0 — see `_aos/metadata.yaml`
- **Roadmap / WP state:** `_aos/roadmap.yaml`
- **Boundaries (forbidden imports, write roots):** `_aos/project_identity.yaml`
- **Governance contracts (snapshots):** `_aos/governance/team_*.md` — read **on mandate** or deep work; not required cover-to-cover each session

## Team entry

- **Primary activation / entry file:** _COMMUNICATION/team_00/  
  (Replace with `_aos/context/ACTIVATION_ARCH.md`, `ACTIVATION_BUILDER.md`, or latest `HANDOFF_*` under `_COMMUNICATION/team_XXX/` per session.)

## Domain profile

### What this product is

nimrod.bio — אתר WordPress אישי של נמרוד, מאוחסן על uPress.
שפה: עברית. פרופיל L0 — אין FastAPI engine; WordPress הוא ה-stack.
הפרויקט מנהל: תבנית, עיצוב, ותוכן האתר.
Live: https://nimrod.bio
Site root (git): `nimrod.bio/`

### Current focus

V100: AOS init + bootstrap סביבת פיתוח מקומית מ-uPress production.
- WP1 (NB-S001-P001-WP001): AOS Init + Port Registration — IN_PROGRESS
- WP2 (NB-S001-P002-WP001): Environment Bootstrap (Docker + uPress backup restore) — PLANNED

### Standards / SSOT

- WordPress hosting: uPress
- Language: he (עברית, RTL)
- Local dev: Docker Compose (WordPress + MySQL), port 8085 / 3309
- Theme: TBD (pending uPress pull)
- AOS Iron Rules: `_aos/lean-kit/` (physical snapshot)
