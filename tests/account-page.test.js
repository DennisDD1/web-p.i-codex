const assert = require('assert');
const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const styles = path.join(root, 'wordpress/themes/flatsome-child/style.css');

assert.ok(fs.existsSync(styles), 'missing child theme style.css');

const css = fs.readFileSync(styles, 'utf8');

assert.match(css, /\.woocommerce-account\s*\{[^}]*--account-paper:#f4f1ea/s);
assert.match(css, /\.woocommerce-account \.page-title-inner\s*\{/);
assert.match(css, /\.woocommerce-account:not\(\.logged-in\) \.woocommerce\s*\{[^}]*max-width:\s*760px/s);
assert.match(css, /\.woocommerce-account \.woocommerce-form-login\s*\{[^}]*border:\s*1px solid rgba\(17,17,17,\.18\)/s);
assert.match(css, /\.woocommerce-account \.woocommerce-form-login::before\s*\{[^}]*PAINTER\.INK ACCOUNT/s);
assert.match(css, /\.woocommerce-account \.woocommerce:has\(\.woocommerce-MyAccount-navigation\)\s*\{[^}]*grid-template-columns:\s*minmax\(220px,280px\) minmax\(0,1fr\)/s);
assert.match(css, /\.woocommerce-account \.woocommerce-MyAccount-navigation a\s*\{[^}]*text-transform:\s*uppercase/s);
assert.match(css, /\.woocommerce-account \.woocommerce-MyAccount-content\s*\{[^}]*border:\s*1px solid rgba\(17,17,17,\.14\)/s);
assert.match(css, /\.woocommerce-account form \.input-text[^}]*background:\s*#fbfaf6/s);
assert.match(css, /\.woocommerce-account \.button,[^}]*background:\s*#111/s);
assert.match(css, /@media only screen and \(max-width: 48em\)[^]*\.woocommerce-account \.woocommerce:has\(\.woocommerce-MyAccount-navigation\)\s*\{[^}]*grid-template-columns:\s*1fr/s);
assert.match(css, /@media only screen and \(max-width: 48em\)[^]*\.woocommerce-account \.woocommerce-form-login\s*\{[^}]*padding:\s*26px 18px/s);

console.log('account page style contract passed');
