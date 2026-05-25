# RTL / BiDi Audit — 2026-05-25

## Scope

- Browsers: Chromium, Firefox, WebKit
- URLs:
  - `/` (T7)
  - `/blog/` (T5)
  - `/services/produce/` (T2)
  - `/blog/יום-בגינה/` (T4)
  - `/contact/` (T8)

## Method

- Automated browser pass via Playwright with HTTPS ignore-cert handling for dev cert.
- Per page/browser captured:
  - `document.documentElement.lang`
  - `document.documentElement` computed direction
  - `body` computed direction
  - page title snapshot

Raw evidence: `docs/qa_rtl_bidi_audit_2026-05-25.json` (15 observations).

## Results

- 15/15 checks show computed direction `rtl` on `html` and `body`.
- No runtime LTR-direction escapes detected by structural checks.
- Hebrew page titles loaded correctly across all three engines.

## Notes / limits

- This pass validates directionality and cross-engine rendering parity at structure level.
- Fine-grained visual bidi typography review (punctuation placement edge-cases) still benefits from manual editorial review during cutover smoke test.

## Verdict

**PASS (structural RTL/bidi checks).**
