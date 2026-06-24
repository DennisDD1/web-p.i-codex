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
assert.match(php, /painter-archive__left-controls/);
assert.match(css, /\.painter-archive__left-controls/);
assert.match(php, /painter-archive__grid-view/);
assert.match(php, /painter-archive__hamburger/);
assert.match(php, /About Painter\.ink/);
assert.match(php, /Shipping Policy/);
assert.match(php, /Terms &amp; Conditions/);
assert.match(php, /Refund &amp; Resolution Policy/);
assert.doesNotMatch(php, /Historic images, restored for real skin\./);
assert.match(php, /painter-archive__art-link/);
assert.match(php, /data-add-cart/);
assert.match(php, /Add to cart/);
assert.match(php, /View cart/);
assert.doesNotMatch(php, /painter-archive__rail[^]*painter-archive__number/);
assert.match(js, /prefers-reduced-motion/);
assert.match(js, /updateCaption/);
assert.match(js, /setViewMode/);
assert.match(js, /scheduleActiveFromScroll/);
assert.match(js, /window\.addEventListener\('scroll'/);
assert.match(js, /scrollIntoView/);
assert.match(js, /flexDirection === 'row'/);
assert.match(js, /document\.documentElement\.classList\.toggle\('painter-archive-grid-mode'/);
assert.doesNotMatch(js, /scrollTop\s*=/);
assert.doesNotMatch(js, /preventDefault\(\)/);
assert.match(css, /fonts\.googleapis\.com\/css2\?family=Montserrat/);
assert.match(css, /font-family:'Montserrat'/);
assert.match(css, /scroll-snap-type:y mandatory/);
assert.match(css, /html\.painter-archive-grid-mode[^}]*overflow-y:auto/s);
assert.match(css, /html,body\.painter-archive-page[^}]*overflow-y:auto/s);
assert.match(css, /\.painter-archive__caption\s*\{[^}]*position:fixed/s);
assert.match(css, /\.painter-archive__caption-copy\.is-wiping/);
assert.match(css, /\.painter-archive__grid-view/);
assert.match(css, /column-count:\s*4/);
assert.match(css, /\.painter-archive__rail button:hover/);
assert.match(css, /\.painter-archive__rail img[^}]*object-fit:contain/s);
assert.match(css, /\.painter-archive__wear-actions/);
assert.match(css, /\.painter-archive__wear\s*\{[^}]*bottom:max/s);
assert.doesNotMatch(css, /rotate\(2deg\)/);
assert.match(css, /\.painter-archive__left-controls\s*\{[^}]*height:58px/s);
assert.match(css, /\.painter-archive__toolbar\s*\{[^}]*height:58px/s);
assert.match(css, /\.painter-archive__product-image img\.is-hover[^}]*background:transparent/s);
assert.doesNotMatch(css, /perspective:/);
assert.match(css, /@media \(max-width:\s*767px\)/);

console.log('archive player static contract passed');
