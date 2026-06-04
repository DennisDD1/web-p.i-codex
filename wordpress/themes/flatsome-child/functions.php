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
		array( 'Crying Woman', '4.86 x 2.84 cm', '1938年斯洛伐克现代主义先驱Galanda生命最后一年所作。时值二战阴云笼罩，他以扭曲线条描绘哭泣女性，空洞眼神与无声尖叫凝缩了时代焦虑与个体哀伤。' ),
		array( 'Girl hugs a cat', '2.0 x 5.9 cm', 'Van Hoytema（1863-1917）荷兰石版画家，幼年父母双亡。此作描绘女孩拥抱猫咪的温情瞬间，展现新艺术运动对日常亲密场景的诗意捕捉。' ),
		array( 'Broken Heart', '3.34 x 3.17 cm', '破碎之心符号源于中世纪宗教艺术，19世纪维多利亚时代流行于情人节卡片与哀悼首饰。从圣心传统世俗化而来，成为情感创伤的通用视觉隐喻。' ),
		array( 'Phases of Mercury', '5.04 x 1.71 cm', 'Agnes Giberne（1845-1939）英国科普作家，1898年在其天文学著作中以插图展示水星盈亏，以生动方式向大众传播科学。' ),
		array( 'Volcano', '2.23 x 3.96 cm', '1930年Galanda从巴黎游学归来，与Fulla合著私人信件激辩现代艺术方向。此钢笔速写以狂放线条描绘火山喷发，折射现代主义对原始力量的迷恋。' ),
		array( 'Blad met banenpatroon', '1.74 x 8.22 cm', '荷兰语意为心形条纹图案之页，横跨1750至1900年。心形被化为图案单元，条纹重复展现对秩序与节奏的追求。' ),
		array( 'Free Curve', '4.22 x 6.4 cm', '1925年康定斯基任教于德绍包豪斯，是其几何抽象高峰。自由曲线向点实践点线面理论，以曲线向点汇聚。' ),
		array( 'Woman and Flower', '1.86 x 4.91 cm', '1937年Galanda在巴黎和莫斯科世博会获银奖，画风融合民间元素与现代主义。此作将女性与花卉糅为诗意画面。' ),
		array( 'We Take the Stars', '5.37 x 5.98 cm', '据传出自华盛顿对星条旗的解释：从天堂取星辰，从母国取红色。1776年独立革命之际，此文传播新生国家理想。' ),
		array( 'Stars from Heaven', '5.37 x 5.98 cm', '据传出自华盛顿对星条旗的解释：从天堂取星辰，从母国取红色。1776年独立革命之际，此文传播新生国家理想。' ),
		array( 'Angels Care', '4.08 x 3.78 cm', '1931年克利从包豪斯转至杜塞尔多夫，创作守护天使系列。不久纳粹迫其离开德国，天使成为黑暗年代里最后的精神庇护。' ),
		array( 'Starry Night', '3.10 x 3.11 cm', '1889年梵高在圣雷米精神病院从病房东窗取景创作。旋动的星光与升腾的柏树是内心风暴的外化。' ),
		array( 'Dream', '3.0 x 3.9 cm', 'painter.ink特邀七十岁书法老师专为纹身题写的「夢」字。以苍劲行书挥毫而就，一横一竖凝聚毕生功力。' ),
		array( 'Traditional Chinese Calligraphy', '3.0 x 3.9 cm', 'painter.ink特邀七十岁书法老师专为纹身题写的「夢」字。以苍劲行书挥毫而就，一横一竖凝聚毕生功力。' ),
		array( 'Peace Dove', '4.0 x 3.9 cm', 'Leo Gestel（1881-1941）荷兰现代主义先驱，与蒙德里安齐名。1934-1936年欧洲战云密布，他以书籍插图创作和平鸽。' ),
		array( 'Peace', '3.24 x 4.5 cm', '1970年越战抗议达高峰，和平鸽被广泛用作反战符号。图像源自毕加索1949年石版画鸽子，后成为全球和平运动的视觉语言。' ),
		array( 'Composition with Red', '3.7 x 3.66 cm', '1921年蒙德里安在巴黎将绘画推向新造型主义。仅用黑网格与红黄蓝三原色，将万物还原为本质元素。' ),
		array( 'Sacred Heart', '3.34 x 4.13 cm', '出自1935-1942年美国WPA美国设计索引。圣心图案源自新墨西哥州西班牙殖民教堂，是信仰与民间手工艺交融的见证。' ),
		array( 'Crown', '3.81 x 2.44 cm', '英国外交官兼诗人Rennell Rodd诗集Feda中的王冠插图。精美雕刻展现维多利亚时代对古典权威与骑士精神的回望。' ),
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

add_action( 'wp_footer', function () {
	if ( is_cart() || is_checkout() || is_account_page() ) {
		return;
	}

	$reviews = get_comments(
		array(
			'status'    => 'approve',
			'post_type' => 'product',
			'number'    => 3,
			'orderby'   => 'comment_date_gmt',
			'order'     => 'DESC',
		)
	);

	$ratings = array();
	foreach ( $reviews as $review ) {
		$rating = (int) get_comment_meta( $review->comment_ID, 'rating', true );
		if ( $rating > 0 ) {
			$ratings[] = $rating;
		}
	}
	$has_reviews    = ! empty( $reviews );
	$average_rating = ! empty( $ratings ) ? round( array_sum( $ratings ) / count( $ratings ), 1 ) : '';
	?>
	<section class="painter-review-footer" aria-label="Customer reviews and store information">
		<div class="painter-review-inner">
			<div class="painter-review-head">
				<span><?php echo esc_html( $has_reviews ? 'Customer notes' : 'Studio notes' ); ?></span>
				<h2><?php echo esc_html( $has_reviews ? 'What customers are saying.' : 'Small artworks, made for real skin.' ); ?></h2>
				<p><?php echo esc_html( $has_reviews ? 'Recent approved product feedback from painter.ink customers.' : 'Plant-based color, soft matte finish, and archive-inspired motifs for travel, styling, photos, and first tries.' ); ?></p>
			</div>
			<div class="painter-review-score">
				<strong><?php echo esc_html( $has_reviews && $average_rating ? $average_rating : 'Matte' ); ?></strong>
				<span><?php echo esc_html( $has_reviews ? '★★★★★' : 'Plant color' ); ?></span>
				<small><?php echo esc_html( $has_reviews ? 'Latest approved reviews' : 'Temporary tattoo finish' ); ?></small>
			</div>
			<div class="painter-review-grid">
				<?php if ( $has_reviews ) : ?>
					<?php foreach ( $reviews as $review ) : ?>
						<?php $rating = (int) get_comment_meta( $review->comment_ID, 'rating', true ); ?>
						<article>
							<span><?php echo esc_html( $rating > 0 ? str_repeat( '★', min( 5, $rating ) ) : 'Customer note' ); ?></span>
							<p>"<?php echo esc_html( wp_trim_words( wp_strip_all_tags( $review->comment_content ), 24, '...' ) ); ?>"</p>
							<small><?php echo esc_html( $review->comment_author ? $review->comment_author : 'painter.ink customer' ); ?></small>
						</article>
					<?php endforeach; ?>
				<?php else : ?>
				<article>
					<span>Finish</span>
					<p>Designed to look soft and matte on skin, without a shiny sticker edge.</p>
					<small>Temporary tattoo detail</small>
				</article>
				<article>
					<span>Motif</span>
					<p>Each card keeps the artwork size and background story close to the product image.</p>
					<small>Archive-inspired design</small>
				</article>
				<article>
					<span>Placement</span>
					<p>Small formats are easy to test on wrist, arm, shoulder, ankle, or collarbone.</p>
					<small>First placement guide</small>
				</article>
				<?php endif; ?>
			</div>
			<div class="painter-footer-links">
				<div>
					<strong>Painter.ink</strong>
					<p>Wearable art temporary tattoos with historical motifs and natural fading.</p>
				</div>
				<a href="/shipping-policy/">Shipping</a>
				<a href="/refund-resolution-policy/">Refunds</a>
				<a href="/faq/">FAQ</a>
				<a href="/track-order/">Track order</a>
			</div>
		</div>
	</section>
	<?php
}, 15 );
