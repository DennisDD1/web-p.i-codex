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

$scenes    = require $theme_dir . '/inc/archive-products.php';
$asset_uri = $theme_uri . '/assets/archive-products/';
foreach ( $scenes as &$scene ) {
	$asset_name       = strtolower( $scene['sku'] );
	$scene['art']     = $asset_uri . $asset_name . '-1.' . ( 'PTI-013' === $scene['sku'] ? 'png' : 'jpg' );
	$scene['wear']    = $asset_uri . $asset_name . '-2.png';
	$scene['product'] = home_url( $scene['product'] );
	$scene['id']      = url_to_postid( $scene['product'] );
}
unset( $scene );
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
		<div class="painter-archive__left-controls">
			<button class="painter-archive__brand" type="button" data-menu-toggle aria-expanded="false" aria-controls="archive-menu" aria-label="Open menu"><span class="painter-archive__hamburger" aria-hidden="true"><i></i><i></i><i></i></span></button>
			<div class="painter-archive__view-switch" aria-label="Browse view">
				<button type="button" data-view-mode="player" class="is-active" aria-pressed="true" title="Single artwork view"><span class="painter-view-icon painter-view-icon--single" aria-hidden="true"></span></button>
				<button type="button" data-view-mode="grid" aria-pressed="false" title="View all products"><span class="painter-view-icon painter-view-icon--grid" aria-hidden="true"></span></button>
			</div>
		</div>
		<div class="painter-archive__toolbar">
			<nav class="painter-archive__actions" aria-label="Primary">
				<a href="mailto:hi@painter.ink">Connect</a>
				<a href="<?php echo esc_url( $cart_url ); ?>">Cart</a>
			</nav>
		</div>
	</header>
	<aside id="archive-menu" class="painter-archive__menu" aria-hidden="true">
		<button type="button" data-menu-close aria-label="Close menu">&times;</button>
		<p>PAINTER.INK / WEARABLE ARCHIVE</p>
		<a href="<?php echo esc_url( home_url( '/about-painter-ink/' ) ); ?>">About Painter.ink</a>
		<a href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>">My Account</a>
		<a href="<?php echo esc_url( home_url( '/shipping-policy/' ) ); ?>">Shipping Policy</a>
		<a href="<?php echo esc_url( home_url( '/terms-conditions/' ) ); ?>">Terms &amp; Conditions</a>
		<a href="<?php echo esc_url( home_url( '/refund-resolution-policy/' ) ); ?>">Refund &amp; Resolution Policy</a>
	</aside>
	<div class="painter-archive__viewport" data-archive-viewport>
		<?php foreach ( $scenes as $index => $scene ) : ?>
			<section
				class="painter-archive__scene<?php echo 0 === $index ? ' is-active' : ' is-after'; ?>"
				data-scene="<?php echo esc_attr( $index ); ?>"
				data-scene-title="<?php echo esc_attr( $scene['title'] ); ?>"
				data-scene-kicker="<?php echo esc_attr( $scene['artist'] ); ?>"
				data-scene-story="<?php echo esc_attr( $scene['description'] ); ?>"
				data-scene-year="<?php echo esc_attr( $scene['year'] ); ?>"
				data-scene-size="<?php echo esc_attr( $scene['size'] ); ?>"
				data-scene-sku="<?php echo esc_attr( $scene['sku'] ); ?>"
				data-scene-accent="<?php echo esc_attr( $scene['accent'] ); ?>"
				style="--scene-accent:<?php echo esc_attr( $scene['accent'] ); ?>"
			>
				<div class="painter-archive__visual">
					<a class="painter-archive__art-link" href="<?php echo esc_url( $scene['product'] ); ?>" aria-label="View <?php echo esc_attr( $scene['title'] ); ?>">
						<img class="painter-archive__art" src="<?php echo esc_url( $scene['art'] ); ?>" alt="<?php echo esc_attr( $scene['title'] ); ?> original artwork" <?php echo 0 === $index ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
					</a>
					<div class="painter-archive__wear<?php echo 'PTI-013' === $scene['sku'] ? ' is-single-image' : ''; ?>">
						<a class="painter-archive__wear-image" href="<?php echo esc_url( $scene['product'] ); ?>" aria-label="Shop <?php echo esc_attr( $scene['title'] ); ?>">
							<img src="<?php echo esc_url( $scene['wear'] ); ?>" alt="<?php echo esc_attr( $scene['title'] ); ?> temporary tattoo on skin" loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>">
						</a>
						<div class="painter-archive__wear-actions">
							<a
								data-add-cart
								class="painter-archive__cart-action add_to_cart_button ajax_add_to_cart"
								href="<?php echo esc_url( $scene['id'] ? add_query_arg( 'add-to-cart', absint( $scene['id'] ), home_url( '/' ) ) : $scene['product'] ); ?>"
								data-product_id="<?php echo esc_attr( $scene['id'] ); ?>"
								data-quantity="1"
								rel="nofollow"
							>Add to cart</a>
							<a class="painter-archive__cart-action is-secondary" href="<?php echo esc_url( $cart_url ); ?>">View cart</a>
						</div>
					</div>
				</div>
			</section>
		<?php endforeach; ?>
		<section
			class="painter-archive__scene painter-archive__shop is-after"
			data-scene="<?php echo esc_attr( count( $scenes ) ); ?>"
			data-scene-title="Most Worn Motifs"
			data-scene-kicker="Continue into the collection"
			data-scene-story="Leave the archive player and browse the complete wearable art collection."
			data-scene-year="Shop"
			data-scene-size="17 motifs"
			data-scene-sku="COLLECTION"
			data-scene-accent="#00bf63"
			style="--scene-accent:#00bf63"
		>
			<p>Continue into the collection</p><h2>Most Worn Motifs</h2>
			<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Shop all motifs <span aria-hidden="true">&rarr;</span></a>
		</section>
	</div>
	<footer class="painter-archive__caption" data-archive-caption style="--caption-accent:<?php echo esc_attr( $scenes[0]['accent'] ); ?>">
		<div class="painter-archive__caption-copy" data-caption-copy>
			<span class="painter-archive__sku" data-caption-sku><?php echo esc_html( $scenes[0]['sku'] ); ?></span>
			<div>
				<p data-caption-kicker><?php echo esc_html( $scenes[0]['artist'] ); ?></p>
				<h1 data-caption-title><?php echo esc_html( $scenes[0]['title'] ); ?></h1>
			</div>
			<p class="painter-archive__story" data-caption-story><?php echo esc_html( $scenes[0]['description'] ); ?></p>
			<span class="painter-archive__size" data-caption-size><?php echo esc_html( $scenes[0]['size'] ); ?></span>
			<div class="painter-archive__year"><small>Artwork year</small><strong data-caption-year><?php echo esc_html( $scenes[0]['year'] ); ?></strong></div>
		</div>
	</footer>
	<section class="painter-archive__grid-view" data-grid-view aria-label="All products">
		<div class="painter-archive__grid-head">
			<p>painter.ink archive</p>
			<h1>All 17 Motifs</h1>
		</div>
		<div class="painter-archive__masonry">
			<?php foreach ( $scenes as $scene ) : ?>
				<a class="painter-archive__product-card" href="<?php echo esc_url( $scene['product'] ); ?>">
					<div class="painter-archive__product-image">
						<img src="<?php echo esc_url( $scene['art'] ); ?>" alt="<?php echo esc_attr( $scene['title'] ); ?>" loading="lazy">
						<img class="is-hover" src="<?php echo esc_url( $scene['wear'] ); ?>" alt="" loading="lazy">
					</div>
					<span><?php echo esc_html( $scene['sku'] ); ?></span>
					<h2><?php echo esc_html( $scene['title'] ); ?></h2>
					<p><?php echo esc_html( $scene['size'] ); ?></p>
					<small>Artwork year: <?php echo esc_html( $scene['year'] ); ?></small>
				</a>
			<?php endforeach; ?>
		</div>
	</section>
	<aside class="painter-archive__coverflow" aria-label="Scene selector">
		<div class="painter-archive__rail" data-coverflow>
			<?php foreach ( $scenes as $index => $scene ) : ?>
				<button type="button" data-scene-target="<?php echo esc_attr( $index ); ?>" aria-label="Show <?php echo esc_attr( $scene['title'] ); ?>" class="<?php echo 0 === $index ? 'is-active' : ''; ?>">
					<img src="<?php echo esc_url( $scene['art'] ); ?>" alt="" loading="lazy">
				</button>
			<?php endforeach; ?>
		</div>
	</aside>
</main>
<?php wp_footer(); ?>
</body>
</html>
