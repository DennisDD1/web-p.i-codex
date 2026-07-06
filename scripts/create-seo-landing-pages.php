<?php
/**
 * Create or update painter.ink SEO landing pages.
 *
 * Run from the WordPress root:
 * wp eval-file /tmp/create-seo-landing-pages.php --allow-root
 */

if ( ! defined( 'ABSPATH' ) ) {
	fwrite( STDERR, "This script must run through WP-CLI inside WordPress.\n" );
	exit( 1 );
}

$product_shortcode = '[products limit="8" columns="4" orderby="date" order="DESC"]';

function painter_seo_internal_links() {
	return '
		<nav class="painter-seo-links" aria-label="Related guides">
			<a href="/inkbox-alternative/">Inkbox Alternative</a>
			<a href="/semi-permanent-temporary-tattoos/">Semi-Permanent Temporary Tattoos</a>
			<a href="/museum-art-temporary-tattoos/">Museum Art Temporary Tattoos</a>
			<a href="/shop/">Shop Temporary Tattoos</a>
		</nav>';
}

function painter_seo_page_shell( $hero, $why, $how, $faq, $extra = '' ) {
	global $product_shortcode;

	return '
<article class="painter-seo-page">
	<section class="painter-seo-hero">
' . $hero . '
		<p><a class="button primary" href="/shop/">Shop Temporary Tattoos</a></p>
	</section>

	<section class="painter-seo-section">
' . $why . '
	</section>

	<section class="painter-seo-products">
		<h2>Shop Painter.ink temporary tattoos</h2>
		<p>Browse recent designs from our wearable art collection.</p>
		' . $product_shortcode . '
	</section>

	<section class="painter-seo-section">
' . $how . '
	</section>

' . $extra . '

	<section class="painter-seo-faq">
		<h2>FAQ</h2>
' . $faq . '
	</section>

	<section class="painter-seo-section painter-seo-next">
		<h2>Continue exploring</h2>
		<p>Compare styles, learn how the tattoos develop, or browse the full collection.</p>
		' . painter_seo_internal_links() . '
	</section>
</article>';
}

$pages = array(
	array(
		'slug'        => 'inkbox-alternative',
		'title'       => 'Inkbox Alternative: Plant-Based Temporary Tattoos That Last Up to 2 Weeks',
		'seo_title'   => 'Inkbox Alternative: Plant-Based Temporary Tattoos | Painter.ink',
		'meta_desc'   => 'Looking for an Inkbox alternative? Discover plant-based semi-permanent temporary tattoos inspired by classic art and original designs.',
		'focus'       => 'inkbox alternative',
		'description' => 'Inkbox alternative page',
		'content'     => painter_seo_page_shell(
			'
		<h1>Inkbox Alternative: Plant-Based Temporary Tattoos That Last Up to 2 Weeks</h1>
		<p>Looking for an Inkbox-style semi-permanent tattoo experience? Painter.ink offers a wearable art approach: plant-based temporary tattoos inspired by classic art, museum archives, and original illustration.</p>
		<p>Our designs develop gradually on skin, settle into a realistic ink-like look, and fade naturally over time.</p>
		<p><strong>Painter.ink is not affiliated with Inkbox.</strong></p>',
			'
		<h2>Why Painter.ink?</h2>
		<p>Painter.ink is made for people who want temporary tattoos to feel more considered than a party sticker. The collection focuses on classical art references, restored archive drawings, and quiet graphic motifs that work as small wearable pieces.</p>
		<ul>
			<li>Plant-based formula designed for a natural-looking finish.</li>
			<li>Develops gradually instead of appearing as a flat printed sticker.</li>
			<li>Waterproof for everyday wear once developed.</li>
			<li>Usually lasts around 7-14 days, depending on skin and placement.</li>
			<li>Designed around art, not loud novelty graphics.</li>
		</ul>',
			'
		<h2>How it works</h2>
		<ol>
			<li>Apply the sheet to clean, dry skin for 2-3 hours.</li>
			<li>Remove the sheet and avoid rubbing the area immediately.</li>
			<li>The design develops gradually within 24-48 hours.</li>
			<li>It usually lasts 7-14 days depending on skin type, placement, friction, sweat, washing frequency, and aftercare.</li>
		</ol>',
			'
		<h3>Are Painter.ink tattoos like Inkbox?</h3>
		<p>They offer a similar semi-permanent temporary tattoo idea, but Painter.ink is its own brand with an art-led collection. Painter.ink is not affiliated with Inkbox.</p>
		<h3>How long do Painter.ink temporary tattoos last?</h3>
		<p>Most designs last around 7-14 days. Wear time depends on skin type, placement, friction, sweat, washing frequency, and aftercare.</p>
		<h3>How long does it take to develop?</h3>
		<p>The design develops gradually within 24-48 hours after the sheet is removed.</p>
		<h3>Are they waterproof?</h3>
		<p>Once developed, Painter.ink temporary tattoos are made for everyday wear and are waterproof, though frequent rubbing or washing may shorten wear time.</p>
		<h3>Are they real tattoos?</h3>
		<p>No. They are temporary tattoos. They do not use needles and they fade naturally over time.</p>
		<h3>Can I remove them early?</h3>
		<p>They are designed to fade gradually. Gentle exfoliation and regular washing can help them fade faster, but results vary.</p>'
		),
	),
	array(
		'slug'        => 'semi-permanent-temporary-tattoos',
		'title'       => 'Semi-Permanent Temporary Tattoos Inspired by Classic Art',
		'seo_title'   => 'Semi-Permanent Temporary Tattoos Inspired by Classic Art | Painter.ink',
		'meta_desc'   => 'Explore semi-permanent temporary tattoos that develop naturally, look like real ink, and last around 7-14 days.',
		'focus'       => 'semi permanent temporary tattoo',
		'description' => 'Semi-permanent temporary tattoo page',
		'content'     => painter_seo_page_shell(
			'
		<h1>Semi-Permanent Temporary Tattoos Inspired by Classic Art</h1>
		<p>Painter.ink creates semi-permanent temporary tattoos for people who want the look of real ink without committing to a permanent tattoo.</p>
		<p>Each design develops naturally on skin, settles into a soft ink-like finish, and usually lasts around 7-14 days.</p>',
			'
		<h2>More than a regular temporary tattoo</h2>
		<p>Many temporary tattoos sit on top of the skin and look shiny or sticker-like. Painter.ink is built around a slower reveal: apply the sheet, remove it, and let the design develop over the next day or two.</p>
		<ul>
			<li>Realistic ink-like look after the design develops.</li>
			<li>Plant-based formula with a natural fade.</li>
			<li>Waterproof for daily routines once developed.</li>
			<li>Art-inspired designs from classic references and original illustrations.</li>
			<li>Good for testing placement, mood, and scale before choosing a permanent tattoo.</li>
		</ul>',
			'
		<h2>How it works</h2>
		<ol>
			<li>Apply for 2-3 hours on clean, dry skin.</li>
			<li>Remove the sheet carefully.</li>
			<li>The design develops gradually within 24-48 hours.</li>
			<li>It usually lasts 7-14 days depending on skin type, placement, friction, sweat, washing frequency, and aftercare.</li>
		</ol>',
			'
		<h3>What is a semi-permanent temporary tattoo?</h3>
		<p>It is a temporary tattoo that develops on skin and lasts longer than a typical one-day sticker tattoo, while still fading naturally over time.</p>
		<h3>How long do Painter.ink semi-permanent temporary tattoos last?</h3>
		<p>They usually last around 7-14 days. Placement, friction, sweat, washing frequency, and aftercare all affect wear time.</p>
		<h3>Do they look like real ink?</h3>
		<p>The goal is a soft, ink-like look after the design develops. The finish is designed to feel more natural than a glossy sticker.</p>
		<h3>When will the design become visible?</h3>
		<p>The design develops gradually within 24-48 hours after application.</p>
		<h3>Are they waterproof?</h3>
		<p>Yes, they are made for everyday wear once developed, but heavy rubbing and frequent washing can shorten the visible life of the design.</p>
		<h3>What makes them different from regular temporary tattoos?</h3>
		<p>The slower development, longer wear time, natural fade, and art-focused design language make them different from typical novelty temporary tattoos.</p>'
		),
	),
	array(
		'slug'        => 'museum-art-temporary-tattoos',
		'title'       => 'Museum-Inspired Temporary Tattoos You Can Wear',
		'seo_title'   => 'Museum-Inspired Temporary Tattoos You Can Wear | Painter.ink',
		'meta_desc'   => 'Wear classic art on your skin with museum-inspired temporary tattoos by Painter.ink. Plant-based, realistic, and easy to apply.',
		'focus'       => 'museum art temporary tattoo',
		'description' => 'Museum art temporary tattoo page',
		'content'     => painter_seo_page_shell(
			'
		<h1>Museum-Inspired Temporary Tattoos You Can Wear</h1>
		<p>Painter.ink turns classic art references, public-domain archive images, and original illustration into wearable temporary tattoos.</p>
		<p>The collection is made for people who want a small piece of art on skin: quiet, graphic, and more personal than a standard sticker tattoo.</p>',
			'
		<h2>Wearable art, not throwaway graphics</h2>
		<p>Our collection includes motifs inspired by artists and visual traditions such as Van Gogh, Mondrian, modernist linework, decorative archives, and symbolic illustration. The aim is not to copy a museum wall, but to reinterpret historic visual language at a scale that works on the body.</p>
		<ul>
			<li>Classic art and archive-inspired temporary tattoos.</li>
			<li>Plant-based formula with a realistic developed finish.</li>
			<li>Waterproof for everyday wear once developed.</li>
			<li>Usually lasts around 7-14 days.</li>
			<li>Designed for subtle placement on wrist, collarbone, arm, ankle, or shoulder.</li>
		</ul>',
			'
		<h2>How it works</h2>
		<ol>
			<li>Apply the tattoo sheet for 2-3 hours.</li>
			<li>Remove the sheet after application.</li>
			<li>The design develops gradually within 24-48 hours.</li>
			<li>It usually lasts 7-14 days depending on skin type, placement, friction, sweat, washing frequency, and aftercare.</li>
		</ol>',
			'
		<h3>What is a museum art temporary tattoo?</h3>
		<p>It is a temporary tattoo inspired by classic artwork, museum archives, public-domain imagery, or art-historical visual language.</p>
		<h3>Do you have Van Gogh temporary tattoos?</h3>
		<p>Painter.ink includes art-inspired motifs, including designs that reference classic works and visual details such as night skies, moons, linework, and modern art forms.</p>
		<h3>Do you have Mondrian temporary tattoos?</h3>
		<p>The collection includes geometric and modernist-inspired designs, including motifs influenced by primary color, grid structure, and early twentieth-century abstraction.</p>
		<h3>Are these regular sticker tattoos?</h3>
		<p>No. Painter.ink designs develop gradually and are made to fade naturally over several days, with a softer ink-like look than many sticker-style temporary tattoos.</p>
		<h3>How long do museum-inspired temporary tattoos last?</h3>
		<p>Most last around 7-14 days, depending on skin type, placement, friction, sweat, washing frequency, and aftercare.</p>
		<h3>Where should I place an art temporary tattoo?</h3>
		<p>Smaller designs often work well on the wrist, collarbone, inner arm, ankle, shoulder, or behind the arm. Choose a lower-friction area for longer wear.</p>'
		),
	),
);

$results = array();

foreach ( $pages as $page ) {
	$existing = get_page_by_path( $page['slug'], OBJECT, 'page' );

	$postarr = array(
		'post_type'    => 'page',
		'post_title'   => $page['title'],
		'post_name'    => $page['slug'],
		'post_content' => $page['content'],
		'post_status'  => 'publish',
	);

	if ( $existing ) {
		$postarr['ID'] = $existing->ID;
		$post_id       = wp_update_post( wp_slash( $postarr ), true );
		$action        = 'updated';
	} else {
		$post_id = wp_insert_post( wp_slash( $postarr ), true );
		$action  = 'created';
	}

	if ( is_wp_error( $post_id ) ) {
		$results[] = array(
			'slug'  => $page['slug'],
			'error' => $post_id->get_error_message(),
		);
		continue;
	}

	update_post_meta( $post_id, '_yoast_wpseo_title', $page['seo_title'] );
	update_post_meta( $post_id, '_yoast_wpseo_metadesc', $page['meta_desc'] );
	update_post_meta( $post_id, '_yoast_wpseo_focuskw', $page['focus'] );

	$results[] = array(
		'id'          => (int) $post_id,
		'slug'        => $page['slug'],
		'action'      => $action,
		'status'      => get_post_status( $post_id ),
		'url'         => get_permalink( $post_id ),
		'seo_title'   => $page['seo_title'],
		'focus'       => $page['focus'],
		'description' => $page['description'],
	);
}

if ( class_exists( 'WP_CLI' ) ) {
	WP_CLI::log( wp_json_encode( $results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
}

