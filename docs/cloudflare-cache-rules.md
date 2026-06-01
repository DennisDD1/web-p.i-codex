# Cloudflare cache rules change log

This file records Cloudflare changes made for painter.ink so they can be reviewed and reversed.

## 2026-06-01 static assets rule

Created in Cloudflare Dashboard, zone `painter.ink`.

Rule name:

- `Cache static assets 30 days`

Purpose:

- Cache static assets at Cloudflare edge and in visitor browsers for 30 days.
- Free-plan compatible expression using `wildcard`, not paid `matches`.

Scope:

- Static file extensions only: `css`, `js`, `mjs`, `json`, `jpg`, `jpeg`, `png`, `gif`, `webp`, `avif`, `svg`, `ico`, `woff`, `woff2`, `ttf`, `eot`.

Actions:

- Cache eligibility: eligible for cache.
- Edge TTL: 30 days.
- Browser TTL: 30 days.

Verification:

- `https://painter.ink/wp-content/themes/flatsome/assets/css/flatsome.css` returned `cf-cache-status: HIT` and `cache-control: max-age=2592000`.
- A product image under `/wp-content/uploads/` returned `cf-cache-status: HIT` and `cache-control: max-age=2592000`.
- `/cart/` remained `cf-cache-status: DYNAMIC`.

Rollback:

- Disable or delete Cloudflare rule `Cache static assets 30 days`.

## 2026-06-01 anonymous homepage HTML cache

Reason:

- Origin-local homepage TTFB is about `0.15s`.
- External homepage TTFB through Cloudflare is about `1.28s`.
- Homepage HTML is currently `cf-cache-status: DYNAMIC`, so anonymous visitors still cross-region back to origin.

Rule name:

- `Cache anonymous homepage 2 hours`

Expression:

```txt
(http.host eq "painter.ink" and http.request.uri.path eq "/" and not http.cookie contains "wordpress_logged_in" and not http.cookie contains "woocommerce_items_in_cart" and not http.cookie contains "woocommerce_cart_hash" and not http.cookie contains "wp_woocommerce_session")
```

Actions:

- Cache eligibility: eligible for cache.
- Edge TTL: 2 hours.
- Browser TTL: not set.

Safety scope:

- Only `https://painter.ink/` is cached.
- Logged-in users and WooCommerce cart/session cookies are excluded.
- Cart, checkout, account, admin, login, API, and product pages are not matched by this rule.

Verification:

- First anonymous homepage request returned `cf-cache-status: MISS`.
- Subsequent anonymous homepage requests returned `cf-cache-status: HIT` with `age` increasing.
- Homepage with WooCommerce cart/session cookies returned `cf-cache-status: DYNAMIC`.
- `/cart/` returned `cf-cache-status: DYNAMIC`.

Rollback:

- Disable or delete Cloudflare rule `Cache anonymous homepage 2 hours`.
