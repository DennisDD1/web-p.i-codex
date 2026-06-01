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

## Proposed next rule: anonymous homepage HTML cache

Reason:

- Origin-local homepage TTFB is about `0.15s`.
- External homepage TTFB through Cloudflare is about `1.28s`.
- Homepage HTML is currently `cf-cache-status: DYNAMIC`, so anonymous visitors still cross-region back to origin.

Safe approach:

- Add a bypass rule for logged-in/cart/checkout/account/admin/API requests.
- Add a narrow cache rule for `https://painter.ink/` only.
- Keep checkout, cart, my-account, wp-admin, wp-login, wp-json, and WooCommerce AJAX/API dynamic.

Rollback:

- Disable or delete the homepage HTML cache rule and any matching bypass rule.
