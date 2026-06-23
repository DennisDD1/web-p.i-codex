const assert = require('assert');
const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const template = path.join(root, 'wordpress/themes/flatsome-child/page-templates/archive-player.php');
const script = path.join(root, 'wordpress/themes/flatsome-child/assets/js/archive-player.js');
const styles = path.join(root, 'wordpress/themes/flatsome-child/assets/css/archive-player.css');

for (const file of [template, script, styles]) {
  assert.ok(fs.existsSync(file), `missing ${path.relative(root, file)}`);
}

const php = fs.readFileSync(template, 'utf8');
const js = fs.readFileSync(script, 'utf8');
const css = fs.readFileSync(styles, 'utf8');

assert.match(php, /Template Name:\s*Archive Player Preview/);
assert.strictEqual((php.match(/'title'\s*=>/g) || []).length, 8, 'expected eight scenes');
assert.match(php, /noindex, nofollow/);
assert.match(php, /painter-archive__coverflow/);
assert.match(php, /wc_get_cart_url/);
assert.match(js, /addEventListener\('wheel'/);
assert.match(js, /addEventListener\('touchstart'/);
assert.match(js, /prefers-reduced-motion/);
assert.match(js, /viewport\.scrollTop\s*=/);
assert.match(css, /scroll-snap-type:\s*y mandatory/);
assert.match(css, /perspective:/);
assert.match(css, /@media \(max-width:\s*767px\)/);

console.log('archive player static contract passed');
