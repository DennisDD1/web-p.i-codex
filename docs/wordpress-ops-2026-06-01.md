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

## Follow-up cleanup on 2026-06-02

Rollback backup created before changes:

- `/home/admin/painter-backups/20260602T020458Z`

Backup contents:

- `database.sql.gz`
- `wp-content-uploads-public-risk-files.tar.gz`
- `flatsome-child.tar.gz`
- `functions.php.before-featured-archive-hide`
- `comments.csv`
- `featured_items.csv`
- `pages.csv`

Changes:

- Added `Options -Indexes` to `wp-content/uploads/.htaccess` so visitors cannot browse the uploads folder.
- Removed public `wp-content/uploads/painter-child-theme-codex.tar.gz` after backing it up.
- Changed Flatsome demo portfolio items `228-235` from `publish` to `draft`.
- Moved FAQ page `90` to top level so its public URL is `/faq/` instead of the old `Elements > Pages` path.
- Set Yoast SEO to noindex `featured_item`, its archive, and related featured item taxonomies.
- Rebuilt Yoast indexables with `wp yoast index --reindex --skip-confirmation --allow-root`.
- Removed stale WP Fastest Cache files for old demo URLs and sitemaps.
- Added child theme logic to return 404 for leftover `/featured_item/` public routes.

Verification:

- `/wp-content/uploads/` returned HTTP 403.
- `/wp-content/uploads/painter-child-theme-codex.tar.gz` returned HTTP 404.
- `/featured_item/` returned HTTP 404.
- `/featured_item/flat-t-shirt-company/` returned HTTP 404.
- `/elements/`, `/test/`, `/sample-page/`, and `/demos/` returned HTTP 404.
- `/faq/` returned HTTP 200.
- `/elements/pages/faq/` returned HTTP 404.
- Homepage returned HTTP 200 and Cloudflare HIT.
- Shop returned HTTP 200.

Rollback examples:

```bash
cd /var/www/html
sudo cp /home/admin/painter-backups/20260602T020458Z/functions.php.before-featured-archive-hide wp-content/themes/flatsome-child/functions.php
sudo tar -xzf /home/admin/painter-backups/20260602T020458Z/wp-content-uploads-public-risk-files.tar.gz -C /var/www/html
wp db import /home/admin/painter-backups/20260602T020458Z/database.sql.gz --allow-root
```

## Homepage product grid update on 2026-06-04

Rollback backup created before changes:

- `/home/admin/painter-backups/20260604T050333Z`

Backup contents:

- `database.sql.gz`
- `flatsome-child.tar.gz`
- `functions.php.before-product-grid`
- `style.css.before-product-grid`
- `home-content.txt`
- `page_on_front.txt`
- `products.csv`

Changes:

- Changed the homepage product area from the Flatsome slider look into a 4-column product card grid on desktop.
- Kept a 2-column layout on normal mobile screens and 1 column on very narrow screens.
- Added product card notes on the homepage: source/context, size, and short styling note.
- Replaced small product thumbnails in the homepage cards with larger product images when available.
- Kept WooCommerce prices, sale prices, product links, and cart behavior unchanged.
- Bumped child theme CSS version to `3.0.3` so browsers request the new stylesheet.

Verification:

- Desktop screenshot check: 17 product cards, 17 notes, 4 columns, each card about 277px wide.
- Mobile screenshot check: 17 product cards, 17 notes, 2 columns, each card about 174px wide.
- Verified high-resolution image URLs such as `*-800x800.png` in the rendered homepage cards.
- Source cache cleared with `wp fastest-cache clear all --allow-root`.
- Cloudflare API connector could read the zone but could not purge cache; the normal homepage may keep old cached HTML until Cloudflare expires it.

Rollback examples:

```bash
cd /var/www/html
sudo cp /home/admin/painter-backups/20260604T050333Z/functions.php.before-product-grid wp-content/themes/flatsome-child/functions.php
sudo cp /home/admin/painter-backups/20260604T050333Z/style.css.before-product-grid wp-content/themes/flatsome-child/style.css
wp fastest-cache clear all --allow-root
```
