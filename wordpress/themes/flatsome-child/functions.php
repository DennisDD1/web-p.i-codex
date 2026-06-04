<?php
// Hide leftover Flatsome demo portfolio routes from the public site.
add_action( 'template_redirect', function () {
	$request_path = isset( $_SERVER['REQUEST_URI'] ) ? strtok( wp_unslash( $_SERVER['REQUEST_URI'] ), '?' ) : '';
	$request_path = trim( $request_path, '/' );

	if ( 0 === strpos( $request_path, 'featured_item' ) || is_post_type_archive( 'featured_item' ) || is_tax( array( 'featured_item_category', 'featured_item_tag' ) ) ) {
		global $wp_query;

		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
		include get_query_template( '404' );
		exit;
	}
} );

add_action( 'wp_footer', function () {
	if ( ! is_front_page() && ! is_home() ) {
		return;
	}

	$product_notes = array(
		array( 'Crying Woman', 'Mikulas Galanda inspired, 1938', '4.86 x 2.84 cm', 'Expressive linework for a visible wrist or shoulder placement.' ),
		array( 'Girl hugs a cat', 'Theo van Hoytema inspired, 1890-1910', '2.0 x 5.9 cm', 'Tall, tender silhouette that reads well on forearm or ankle.' ),
		array( 'Broken Heart', '19th century illustration', '3.34 x 3.17 cm', 'A compact symbol with a soft vintage mood.' ),
		array( 'Phases of Mercury', 'Astronomical illustration, 1898', '5.04 x 1.71 cm', 'Slim celestial strip for wrist, collarbone, or behind the arm.' ),
		array( 'Volcano', 'Mikulas Galanda inspired, 1930', '2.23 x 3.96 cm', 'Small dramatic mark with strong outline clarity.' ),
		array( 'Blad met banenpatroon', 'Decorative heart pattern, 1750-1900', '1.74 x 8.22 cm', 'Long strip format for bracelet-like styling.' ),
		array( 'Free Curve', 'Kandinsky inspired, 1925', '4.22 x 6.4 cm', 'Abstract movement for a more art-forward placement.' ),
		array( 'Woman and Flower', 'Mikulas Galanda inspired, 1937', '1.86 x 4.91 cm', 'Fine vertical figure for subtle styling.' ),
		array( 'Stars from Heaven', 'Historic celestial artwork, 1776', '5.37 x 5.98 cm', 'Statement archive motif with a poetic origin.' ),
		array( 'Peace.', 'Graphic peace motif, 1970', '3.24 x 4.5 cm', 'Friendly symbol for festivals, trips, and everyday outfits.' ),
		array( 'Angels Care', 'Paul Klee inspired, 1931', '4.08 x 3.78 cm', 'Playful archive detail with a light, protective mood.' ),
		array( 'Starry Night', 'Vincent van Gogh inspired, 1889', '3.10 x 3.11 cm', 'Recognizable classic art in a small wearable scale.' ),
		array( 'Traditional Chinese Calligraphy', 'Traditional Chinese calligraphy', '3.0 x 3.9 cm', 'Simple calligraphic mark with quiet meaning.' ),
		array( 'Peace Dove', 'Leo Gestel inspired, 1934-1936', '4.0 x 3.9 cm', 'Readable line icon with soft blue-black finish.' ),
		array( 'Composition with Red', 'Piet Mondrian inspired, 1921', '3.7 x 3.66 cm', 'Graphic color-block art for clean styling.' ),
		array( 'Sacred Heart', 'Devotional graphic artwork, 1935-1942', '3.34 x 4.13 cm', 'Classic symbol with stronger visual presence.' ),
		array( 'Crown', 'Rennell Rodd inspired, 1886', '3.81 x 2.44 cm', 'Tiny European detail for a refined accent.' ),
	);

	$product_images = array();
	if ( function_exists( 'wc_get_products' ) ) {
		$products = wc_get_products(
			array(
				'status' => 'publish',
				'limit'  => 60,
				'return' => 'objects',
			)
		);

		foreach ( $products as $product ) {
			$image_id = $product->get_image_id();
			if ( ! $image_id ) {
				continue;
			}

			$image = wp_get_attachment_image_src( $image_id, 'large' );
			if ( ! $image ) {
				$image = wp_get_attachment_image_src( $image_id, 'full' );
			}

			if ( $image ) {
				$product_images[ $product->get_slug() ] = esc_url_raw( $image[0] );
			}
		}
	}
	?>
	<script>
	(function () {
		var productNotes = <?php echo wp_json_encode( $product_notes ); ?>;
		var productImages = <?php echo wp_json_encode( $product_images ); ?>;

		function findNote(title) {
			var text = (title || '').toLowerCase();
			return productNotes.find(function (item) {
				return text.indexOf(item[0].toLowerCase()) !== -1;
			});
		}

		function findImage(url) {
			var match = Object.keys(productImages).find(function (slug) {
				return url.indexOf('/product/' + slug + '/') !== -1;
			});

			return match ? productImages[match] : '';
		}

		function flattenProductSliders() {
			document.querySelectorAll('.home .row').forEach(function (row) {
				if (row.dataset.painterGridReady || !row.querySelector('.product-small')) return;
				row.classList.add('painter-product-grid');

				var slider = row.querySelector('.flickity-slider');
				if (!slider) {
					if (!row.classList.contains('flickity-enabled')) {
						row.dataset.painterGridReady = 'true';
					}
					return;
				}

				Array.prototype.slice.call(slider.children).forEach(function (col) {
					col.removeAttribute('style');
					col.classList.remove('is-selected');
					row.appendChild(col);
				});

				row.querySelectorAll('.flickity-viewport, .flickity-button, .flickity-page-dots').forEach(function (node) {
					node.remove();
				});

				row.classList.remove('slider', 'row-slider', 'flickity-enabled', 'is-draggable');
				row.removeAttribute('tabindex');
				row.dataset.painterGridReady = 'true';
			});
		}

		function enhanceCards() {
			flattenProductSliders();

			document.querySelectorAll('.home .product-small').forEach(function (card, index) {
				var title = card.querySelector('.product-title a');
				var body = card.querySelector('.box-text') || card;
				var note = title ? findNote(title.textContent) : null;
				var row = card.closest('.row');
				var image = card.querySelector('.box-image img');
				var link = title || card.querySelector('.box-image a');
				var largeImage = link ? findImage(link.href) : '';

				if (row) row.classList.add('painter-product-grid');
				card.classList.add('painter-product-card');

				if (image && largeImage && image.src !== largeImage) {
					image.src = largeImage;
					image.removeAttribute('srcset');
					image.removeAttribute('sizes');
					if (index < 4) image.loading = 'eager';
				}

				if (!note || body.querySelector('.painter-product-note')) return;

				var meta = document.createElement('div');
				meta.className = 'painter-product-note';
				meta.innerHTML = '<span>' + note[1] + '</span><small>' + note[2] + ' · STUDIO NOTE</small><em>' + note[3] + '</em>';
				body.appendChild(meta);
			});
		}

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', enhanceCards);
		} else {
			enhanceCards();
		}
		window.setTimeout(enhanceCards, 700);
		window.setTimeout(enhanceCards, 1600);
	})();
	</script>
	<?php
}, 30 );
