<?php
/*
Template Name: Archive Player Preview
Template Post Type: page
*/

defined( 'ABSPATH' ) || exit;

$theme_uri = get_stylesheet_directory_uri();
$theme_dir = get_stylesheet_directory();
wp_enqueue_style( 'painter-archive-player', $theme_uri . '/assets/css/archive-player.css', array(), (string) filemtime( $theme_dir . '/assets/css/archive-player.css' ) );
wp_enqueue_script( 'painter-archive-player', $theme_uri . '/assets/js/archive-player.js', array(), (string) filemtime( $theme_dir . '/assets/js/archive-player.js' ), true );
add_filter( 'wpseo_robots', static function () { return 'noindex, nofollow'; } );
add_filter(
	'wp_robots',
	static function ( $robots ) {
		unset( $robots['index'], $robots['follow'] );
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
		return $robots;
	}
);
add_action( 'wp_head', static function () { echo '<meta name="robots" content="noindex, nofollow">' . "\n"; }, 1 );

$upload = trailingslashit( wp_upload_dir()['baseurl'] ) . '2026/03/';
$scenes = array(
	array( 'title' => 'Broken Heart', 'year' => '19th century', 'kicker' => 'Human concern / personal torment', 'story' => 'A direct emblem of fracture, restored from historic devotional imagery and made small enough to carry.', 'art' => $upload . 'ch1.png', 'wear' => $upload . rawurlencode( '14-14ku-拷贝11.webp' ), 'product' => home_url( '/product/injured-heart-heartbreak-emblem-emotional-fracture-graphic/' ), 'accent' => '#f04a34' ),
	array( 'title' => 'Volcano', 'year' => '1930', 'kicker' => 'Raw line / quiet force', 'story' => 'Mikulas Galanda reduced a landscape into pressure and movement. On skin, the same line becomes intimate.', 'art' => $upload . 'flower1.png', 'wear' => $upload . rawurlencode( '14-14ku-拷贝22.webp' ), 'product' => home_url( '/product/volcano-drawing-by-mikulas-galanda-1930-surreal-pen-and-ink-art/' ), 'accent' => '#00bf63' ),
	array( 'title' => 'Free Curve to the Point', 'year' => '1925', 'kicker' => 'Motion without a figure', 'story' => 'A Bauhaus-era line study becomes a restrained mark that changes as the body turns.', 'art' => $upload . 'kd1.png', 'wear' => $upload . rawurlencode( '14-14ku2-拷贝.webp' ), 'product' => home_url( '/product/geometric-curve-composition-kandinsky-abstract-1925-modernist-line-study/' ), 'accent' => '#2eb4e8' ),
	array( 'title' => 'Woman and Flower', 'year' => '1937', 'kicker' => 'Folk feeling / modern line', 'story' => 'A vertical Galanda figure, held between tenderness and modernist restraint.', 'art' => $upload . 'njs1.png', 'wear' => $upload . rawurlencode( '14-14ku-拷贝2-3.webp' ), 'product' => home_url( '/product/galanda-woman-and-flower-modernist-floral-portrait-slovak-modernist-painting/' ), 'accent' => '#ef4ca4' ),
	array( 'title' => 'Stars from Heaven', 'year' => '1776', 'kicker' => 'A new symbol takes shape', 'story' => 'Historic celestial language returns as a bold graphic about independence and invention.', 'art' => $upload . 'ss1.png', 'wear' => $upload . rawurlencode( '14-14ku-拷贝33232.webp' ), 'product' => home_url( '/product/vintage-star-temporary-tattoo/' ), 'accent' => '#7659e8' ),
	array( 'title' => 'Angels Care', 'year' => '1931', 'kicker' => 'A small guardian', 'story' => 'Paul Klee imagined angels as imperfect witnesses. This one stays light, personal and close.', 'art' => $upload . 'xr1.png', 'wear' => $upload . rawurlencode( '14-14ku-拷贝231-1.webp' ), 'product' => home_url( '/product/klee-angel-tattoo-design-modernist-angel-tattoo-1931-angel-art-tattoo/' ), 'accent' => '#efb72e' ),
	array( 'title' => 'The Moon of The Starry Night', 'year' => '1889', 'kicker' => 'Turbulence becomes light', 'story' => 'A familiar night sky is cropped into a private symbol: a moon, a current and a restless horizon.', 'art' => $upload . 'xk1.png', 'wear' => $upload . 'xk3.png', 'product' => home_url( '/product/van-gogh-moon-tattoo-starry-night-detail-tattoo-swirl-moon-tattoo-design/' ), 'accent' => '#306ee8' ),
	array( 'title' => 'Composition in Primary Color', 'year' => '1921', 'kicker' => 'Order / balance / interruption', 'story' => 'Mondrian reduced the world to structure and color. The tattoo keeps the tension without the frame.', 'art' => $upload . 'tx1.png', 'wear' => $upload . 'tx3.png', 'product' => home_url( '/product/piet-mondrian-composition-red-yellow-blue-black-1921/' ), 'accent' => '#e43d2f' ),
);
$cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'painter-archive-page' ); ?>>
<?php wp_body_open(); ?>
<main class="painter-archive" data-archive-player>
	<header class="painter-archive__chrome">
		<button class="painter-archive__brand" type="button" data-menu-toggle aria-expanded="false" aria-controls="archive-menu"><span>PAI<br>NTER</span><i aria-hidden="true"></i></button>
		<nav class="painter-archive__actions" aria-label="Primary">
			<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Shop</a>
			<a href="mailto:hi@painter.ink">Contact</a>
			<a href="<?php echo esc_url( $cart_url ); ?>">Cart</a>
		</nav>
	</header>
	<aside id="archive-menu" class="painter-archive__menu" aria-hidden="true">
		<button type="button" data-menu-close aria-label="Close menu">&times;</button>
		<p>PAINTER.INK / WEARABLE ARCHIVE</p>
		<h2>Historic images, restored for real skin.</h2>
		<a href="<?php echo esc_url( home_url( '/about-painter-ink/' ) ); ?>">About painter.ink</a>
		<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">All motifs</a>
		<a href="mailto:hi@painter.ink">hi@painter.ink</a>
	</aside>
	<div class="painter-archive__viewport" data-archive-viewport>
		<?php foreach ( $scenes as $index => $scene ) : ?>
			<section class="painter-archive__scene<?php echo 0 === $index ? ' is-active' : ''; ?>" data-scene="<?php echo esc_attr( $index ); ?>" style="--scene-accent:<?php echo esc_attr( $scene['accent'] ); ?>">
				<div class="painter-archive__visual">
					<img class="painter-archive__art" src="<?php echo esc_url( $scene['art'] ); ?>" alt="<?php echo esc_attr( $scene['title'] ); ?> original artwork" <?php echo 0 === $index ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
					<a class="painter-archive__wear" href="<?php echo esc_url( $scene['product'] ); ?>" aria-label="Shop <?php echo esc_attr( $scene['title'] ); ?>">
						<img src="<?php echo esc_url( $scene['wear'] ); ?>" alt="<?php echo esc_attr( $scene['title'] ); ?> temporary tattoo on skin" loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>">
						<span>View motif</span>
					</a>
				</div>
				<footer class="painter-archive__caption">
					<span class="painter-archive__number"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
					<div><p><?php echo esc_html( $scene['kicker'] ); ?></p><h1><?php echo esc_html( $scene['title'] ); ?></h1></div>
					<p class="painter-archive__story"><?php echo esc_html( $scene['story'] ); ?></p>
					<span class="painter-archive__year"><?php echo esc_html( $scene['year'] ); ?></span>
				</footer>
			</section>
		<?php endforeach; ?>
		<section class="painter-archive__shop" data-scene="<?php echo esc_attr( count( $scenes ) ); ?>">
			<p>Continue into the collection</p><h2>Most Worn Motifs</h2>
			<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Shop all motifs <span aria-hidden="true">&rarr;</span></a>
		</section>
	</div>
	<aside class="painter-archive__coverflow" aria-label="Scene selector">
		<div class="painter-archive__rail" data-coverflow>
			<?php foreach ( $scenes as $index => $scene ) : ?>
				<button type="button" data-scene-target="<?php echo esc_attr( $index ); ?>" aria-label="Show <?php echo esc_attr( $scene['title'] ); ?>" class="<?php echo 0 === $index ? 'is-active' : ''; ?>">
					<img src="<?php echo esc_url( $scene['art'] ); ?>" alt="" loading="lazy"><span><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
				</button>
			<?php endforeach; ?>
		</div>
	</aside>
</main>
<?php wp_footer(); ?>
</body>
</html>
