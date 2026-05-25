# Form + SMTP QA — 2026-05-25

## Environment

- Base URL: dev uPress environment (HTTPS with ignore-cert handling)
- Form endpoint: `/wp-admin/admin-post.php`
- Contact page: `/contact/`

## Tests executed

1. **Valid submission path**
   - POST with valid name/email/message + nonce/referer from live page.
   - Result: `302` redirect to `/contact/?status=ok`.

2. **Invalid submission path**
   - POST with missing required email.
   - Result: `302` redirect to `/contact/?status=invalid`.

3. **Honeypot path**
   - POST with `website=spam`.
   - Result: `302` redirect to `/contact/?status=ok`.

## Interpretation

- Functional submit flow is working (valid + invalid branches behave correctly by URL status marker).
- Honeypot behavior is not fully provable from redirect status alone; mailbox-side evidence is required to confirm that spam submissions are silently dropped.
- Inbox-level SMTP delivery confirmation was not captured in this run (manual mailbox verification needed).

## Policy mapping

- Per mandate, SMTP delivery uncertainty can be deferred to V300 and is non-blocking for this QA WP.

## Verdict

**PARTIAL PASS**  
Submit path and validation path pass; SMTP mailbox confirmation is deferred.
