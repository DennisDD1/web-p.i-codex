# WordPress operations on 2026-06-01

Production server:

- `admin@13.215.113.170`
- WordPress root: `/var/www/html`

Rollback backup created before WordPress changes:

- `/home/admin/painter-backups/20260601T150542Z`

Backup contents:

- `database.sql.gz`
- `wp-content-plugins.tar.gz`
- `plugin-list.tsv`
- `theme-list.tsv`
- `pages.csv`
- `active_plugins.json`

Plugin changes:

- Deactivated `tiktok-for-business`
- Deactivated `better-search-replace`
- Deactivated `loco-translate`
- Deactivated `nextend-facebook-connect`

Plugins intentionally left active:

- Google / listings / analytics related plugins
- WooCommerce
- Payment plugins
- Pricing and discount plugins
- WP Fastest Cache
- WordPress SEO
- Post SMTP
- UpdraftPlus

Page cleanup:

- Converted Flatsome demo and element pages from `publish` to `draft`.
- Kept published: shop, cart, checkout, account, policies, about painter.ink, track order, FAQ, wishlist, payment pages, and homepage.
- This was a reversible database status change, not permanent deletion.

Cache:

- Ran `wp fastest-cache clear all --allow-root` after page cleanup.
- Removed stale WP Fastest Cache files under `/var/www/html/wp-content/cache/all/elements/` after explicit user confirmation. This removed old public HTML for drafted Flatsome demo pages.

Verification:

- Homepage returned HTTP 200 and Cloudflare homepage cache remained active.
- Cart returned HTTP 200 and remained dynamic.
- `/elements/` returned HTTP 404 after stale cache removal.

Rollback examples:

```bash
cd /var/www/html
wp plugin activate tiktok-for-business better-search-replace loco-translate nextend-facebook-connect --allow-root
wp db import /home/admin/painter-backups/20260601T150542Z/database.sql.gz --allow-root
```

For page status rollback, use `/home/admin/painter-backups/20260601T150542Z/pages.csv` as the source list.
