const assert = require('assert');
const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const template = path.join(root, 'wordpress/themes/flatsome-child/page-templates/archive-player.php');
const products = path.join(root, 'wordpress/themes/flatsome-child/inc/archive-products.php');
const script = path.join(root, 'wordpress/themes/flatsome-child/assets/js/archive-player.js');
const styles = path.join(root, 'wordpress/themes/flatsome-child/assets/css/archive-player.css');

for (const file of [template, products, script, styles]) {
  assert.ok(fs.existsSync(file), `missing ${path.relative(root, file)}`);
}

const php = fs.readFileSync(template, 'utf8');
const data = fs.readFileSync(products, 'utf8');
const js = fs.readFileSync(script, 'utf8');
const css = fs.readFileSync(styles, 'utf8');

assert.match(php, /Template Name:\s*Archive Player Preview/);
assert.strictEqual((data.match(/'sku'\s*=>/g) || []).length, 17, 'expected seventeen products');
assert.match(php, /noindex, nofollow/);
assert.match(php, /painter-archive__coverflow/);
assert.match(php, /wc_get_cart_url/);
assert.strictEqual((php.match(/class="painter-archive__caption"/g) || []).length, 1, 'caption must be a single fixed panel');
assert.match(php, /data-scene-title=/);
assert.match(php, /data-view-mode="player"/);
assert.match(php, /data-view-mode="grid"/);
assert.match(php, /painter-archive__grid-view/);
assert.match(php, /painter-archive__hamburger/);
assert.match(php, /About Painter\.ink/);
assert.match(php, /Shipping Policy/);
assert.match(php, /Terms &amp; Conditions/);
assert.match(php, /Refund &amp; Resolution Policy/);
assert.doesNotMatch(php, /painter-archive__rail[^]*painter-archive__number/);
assert.match(js, /addEventListener\('wheel'/);
assert.match(js, /addEventListener\('touchstart'/);
assert.match(js, /prefers-reduced-motion/);
assert.match(js, /wheelGestureActive/);
assert.match(js, /finishVisualTransition/);
assert.match(js, /updateCaption/);
assert.match(js, /setViewMode/);
assert.match(js, /root\.addEventListener\('wheel'/);
assert.match(js, /root\.addEventListener\('touchstart'/);
assert.match(js, /flexDirection === 'row'/);
assert.match(js, /document\.documentElement\.classList\.toggle\('painter-archive-grid-mode'/);
assert.doesNotMatch(js, /scrollTop\s*=/);
assert.doesNotMatch(css, /scroll-snap-type/);
assert.match(css, /html\.painter-archive-grid-mode[^}]*overflow-y:auto/s);
assert.match(css, /\.painter-archive__caption\s*\{[^}]*position:fixed/s);
assert.match(css, /\.painter-archive__scene\.is-entering/);
assert.match(css, /\.painter-archive__caption-copy\.is-wiping/);
assert.match(css, /\.painter-archive__grid-view/);
assert.match(css, /column-count:\s*4/);
assert.match(css, /\.painter-archive__rail button:hover/);
assert.match(css, /\.painter-archive__product-image img\.is-hover[^}]*background:transparent/s);
assert.match(css, /perspective:/);
assert.match(css, /@media \(max-width:\s*767px\)/);

console.log('archive player static contract passed');
