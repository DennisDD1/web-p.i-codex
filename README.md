# painter.ink rollback workspace

This repository is used as the reversible working copy for painter.ink production changes.

Current baseline snapshot:

- Captured at: 2026-06-01T13:06:47Z
- Server: `admin@13.215.113.170`
- WordPress root observed: `/var/www/html`
- Captured path: `/var/www/html/wp-content/themes/flatsome-child/`
- Local path: `wordpress/themes/flatsome-child/`

Scope rules:

- Do not delete or move files without explicit confirmation.
- Allowed edit surface: Flatsome pages, CSS, product templates, checkout styling, and the child theme files in this snapshot.
- Do not edit nginx, `wp-config.php`, database content, or payment plugins unless the scope is explicitly changed.
