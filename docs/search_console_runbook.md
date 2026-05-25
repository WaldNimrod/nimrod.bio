# Search Console Runbook (P005-WP002)

This runbook is documentation-only for cutover. Do not execute these actions during P004-WP002.

## Preconditions

- Production cutover to the V200 site is complete.
- Redirect block already deployed on production.
- Yoast is active and `https://nimrod.bio/sitemap_index.xml` is reachable.

## Steps

1. In Google Search Console, open property `https://nimrod.bio/`.
2. Submit sitemap `https://nimrod.bio/sitemap_index.xml` in **Indexing > Sitemaps**.
3. Run URL Inspection and request indexing for top SEO pages:
   - `/shook/`
   - `/blog/יום-בגינה/`
   - `/blog/פטריות-יער-בגינה/`
   - `/about/heritage/`
   - `/blog/transplant-spread/`
4. In **Pages** report, monitor `Redirect (301)`, `Not found (404)`, and `Soft 404` daily for 30 days.
5. Validate that the 6 drop URLs are reported as `Excluded`/`Not found` and not indexed.
6. If any dropped URL remains indexed after 30 days, submit temporary removal requests for those URLs.
7. Export weekly coverage snapshots (CSV) for P005-WP002 evidence.

## Notes

- Change of Address is not required (domain remains `nimrod.bio`).
- Search Console execution ownership: P005-WP002.
