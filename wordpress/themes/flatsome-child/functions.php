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
	if ( ! is_front_page() && ! is_home() && ! is_shop() && ! is_product_taxonomy() ) {
		return;
	}

	$product_notes = array(
		array( 'Crying Woman', '4.86 x 2.84 cm', 'Drawn in 1938, near the end of Mikulas Galanda\'s life. Its fractured lines and hollow gaze hold the anxiety of a Europe moving toward war.' ),
		array( 'Girl hugs a cat', '2.0 x 5.9 cm', 'Inspired by Theo van Hoytema, the Dutch lithographer known for tender animal scenes. A slim, affectionate motif for quiet placement.' ),
		array( 'Broken Heart', '3.34 x 3.17 cm', 'A Victorian-era symbol with roots in devotional heart imagery. It turns private hurt into a small, direct visual mark.' ),
		array( 'Phases of Mercury', '5.04 x 1.71 cm', 'Adapted from an 1898 astronomy illustration by Agnes Giberne, made to bring celestial movement into clear popular science.' ),
		array( 'Volcano', '2.23 x 3.96 cm', 'A 1930 Galanda-inspired sketch charged with restless energy, reflecting modernism\'s fascination with raw natural force.' ),
		array( 'Blad met banenpatroon', '1.74 x 8.22 cm', 'A long heart-pattern strip from Dutch decorative sources, turning the heart into rhythm, order, and bracelet-like movement.' ),
		array( 'Free Curve', '4.22 x 6.4 cm', 'Inspired by Kandinsky\'s 1925 Bauhaus period, where line, point, and curve became a language of pure motion.' ),
		array( 'Woman and Flower', '1.86 x 4.91 cm', 'A Galanda-inspired vertical figure from the late 1930s, blending folk feeling with modern linework and a quiet floral presence.' ),
		array( 'We Take the Stars', '5.37 x 5.98 cm', 'A historic celestial phrase tied to early American symbolism: stars from heaven, red from the mother country, and a new ideal taking shape.' ),
		array( 'Stars from Heaven', '5.37 x 5.98 cm', 'A historic celestial phrase tied to early American symbolism: stars from heaven, red from the mother country, and a new ideal taking shape.' ),
		array( 'Angels Care', '4.08 x 3.78 cm', 'Inspired by Paul Klee\'s guardian-angel works from 1931, made as a small sign of care before Europe entered darker years.' ),
		array( 'Starry Night', '3.10 x 3.11 cm', 'Drawn from Van Gogh\'s 1889 night sky at Saint-Remy, where swirling stars and rising cypress turn inner turbulence into light.' ),
		array( 'Dream', '3.0 x 3.9 cm', 'A custom Chinese calligraphy motif made for painter.ink, using the character for dream in a bold, hand-brushed form.' ),
		array( 'Traditional Chinese Calligraphy', '3.0 x 3.9 cm', 'A custom Chinese calligraphy motif made for painter.ink, using the character for dream in a bold, hand-brushed form.' ),
		array( 'Peace Dove', '4.0 x 3.9 cm', 'Inspired by Leo Gestel\'s 1930s modernist linework. A clear dove motif made for a calm, readable placement.' ),
		array( 'Peace', '3.24 x 4.5 cm', 'A 1970 peace motif shaped by anti-war visual culture, echoing the dove as a global sign of hope and refusal.' ),
		array( 'Composition with Red', '3.7 x 3.66 cm', 'Inspired by Mondrian\'s 1921 neoplastic work, reducing the world to black structure and primary color.' ),
		array( 'Sacred Heart', '3.34 x 4.13 cm', 'Based on a WPA Index of American Design devotional graphic, where Spanish colonial church imagery meets folk craft.' ),
		array( 'Crown', '3.81 x 2.44 cm', 'Adapted from a Victorian-era crown illustration linked to Rennell Rodd, with a small mark of classical authority and refinement.' ),
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

			$gallery_ids = $product->get_gallery_image_ids();
			$hover      = ! empty( $gallery_ids ) ? wp_get_attachment_image_src( $gallery_ids[0], 'large' ) : false;
			if ( ! $hover && ! empty( $gallery_ids ) ) {
				$hover = wp_get_attachment_image_src( $gallery_ids[0], 'full' );
			}

			if ( $image ) {
				$product_images[ $product->get_slug() ] = array(
					'primary' => esc_url_raw( $image[0] ),
					'hover'   => $hover ? esc_url_raw( $hover[0] ) : esc_url_raw( $image[0] ),
				);
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
				if (item[0].toLowerCase() === 'peace' && text.indexOf('peace dove') !== -1) return false;
				return text.indexOf(item[0].toLowerCase()) !== -1;
			});
		}

		function findImage(url) {
			var match = Object.keys(productImages).find(function (slug) {
				return url.indexOf('/product/' + slug + '/') !== -1;
			});

			return match ? productImages[match] : '';
		}

		function cleanStorefrontTitle(titleNode) {
			if (!titleNode || titleNode.dataset.painterTitleCleaned) return;

			titleNode.textContent = titleNode.textContent
				.replace(/\s*[\(\uff08][\u3400-\u9fff]+[\)\uff09]/g, '')
				.replace(/\uff08/g, '(')
				.replace(/\uff09/g, ')')
				.replace(/([A-Za-z])\(/g, '$1 (')
				.replace(/\s+/g, ' ')
				.trim();
			titleNode.dataset.painterTitleCleaned = 'true';
		}

		function isProductArea(node) {
			return node && (node.closest('.home') || node.closest('.woocommerce-shop') || node.closest('.archive.woocommerce'));
		}

		function rowHasDirectProducts(row) {
			return Array.prototype.some.call(row.children, function (child) {
				return child.classList.contains('product-small') || !!child.querySelector(':scope > .col-inner > .product-small');
			});
		}

		function flattenProductSliders() {
			document.querySelectorAll('.row').forEach(function (row) {
				if (!isProductArea(row)) return;
				if (!rowHasDirectProducts(row)) return;
				if (row.dataset.painterGridReady && !row.classList.contains('flickity-enabled') && !row.querySelector('.flickity-slider')) return;
				row.classList.add('painter-product-grid');
				row.classList.remove('row-masonry', 'has-packery');
				row.removeAttribute('style');
				Array.prototype.slice.call(row.children).forEach(function (col) {
					if (col.classList.contains('product-small') || col.querySelector('.product-small')) {
						col.removeAttribute('style');
					}
				});

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

			document.querySelectorAll('.product-small').forEach(function (card, index) {
				if (!isProductArea(card) || card.parentElement.closest('.product-small')) return;

				var title = card.querySelector('.product-title a');
				var body = card.querySelector('.box-text') || card;
				var note = title ? findNote(title.textContent) : null;
				var row = card.closest('.row');
				var image = card.querySelector('.box-image img');
				var link = title || card.querySelector('.box-image a');
				var imageSet = link ? findImage(link.href) : '';

				if (row) row.classList.add('painter-product-grid');
				card.classList.add('painter-product-card');
				cleanStorefrontTitle(title);

				if (image && imageSet && imageSet.primary && image.src !== imageSet.primary) {
					image.src = imageSet.primary;
					image.removeAttribute('srcset');
					image.removeAttribute('sizes');
					if (index < 4) image.loading = 'eager';
				}

				if (image && imageSet && imageSet.hover && !card.querySelector('.painter-hover-image')) {
					var hover = image.cloneNode(false);
					hover.className = 'painter-hover-image';
					hover.src = imageSet.hover;
					hover.removeAttribute('srcset');
					hover.removeAttribute('sizes');
					image.insertAdjacentElement('afterend', hover);
				}

				if (!note || body.querySelector('.painter-product-note')) return;

				var meta = document.createElement('div');
				meta.className = 'painter-product-note';
				meta.innerHTML = '<small>' + note[1] + '</small><em>' + note[2] + '</em>';
				body.appendChild(meta);
			});
		}

		function hideLegacyPromotionSections() {
			document.querySelectorAll('section').forEach(function (section) {
				var text = section.textContent || '';
				if (text.indexOf('30% OFF SITEWIDE') !== -1 && text.indexOf('Latest Promotions') !== -1) {
					section.classList.add('painter-legacy-promo-hidden');
				}
			});
		}

		document.addEventListener('touchstart', function (event) {
			var card = event.target.closest('.painter-product-card');
			if (card) card.classList.add('is-touching-image');
		}, { passive: true });

		document.addEventListener('touchend', function () {
			document.querySelectorAll('.painter-product-card.is-touching-image').forEach(function (card) {
				card.classList.remove('is-touching-image');
			});
		}, { passive: true });

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', function () {
				enhanceCards();
				hideLegacyPromotionSections();
			});
		} else {
			enhanceCards();
			hideLegacyPromotionSections();
		}
		window.setTimeout(enhanceCards, 700);
		window.setTimeout(enhanceCards, 1600);
		window.setTimeout(hideLegacyPromotionSections, 700);
	})();
	</script>
	<?php
}, 30 );

add_action( 'wp_body_open', function () {
	if ( is_cart() || is_checkout() || is_account_page() ) {
		return;
	}
	?>
	<div class="painter-offer-bar" role="note" aria-label="Current offer">
		<span>Sitewide 30% off</span>
		<strong>Free shipping over $11.98</strong>
		<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">Shop motifs</a>
	</div>
	<?php
} );
