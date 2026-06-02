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
