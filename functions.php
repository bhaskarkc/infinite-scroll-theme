<?php
add_action(
	'after_setup_theme', function() {
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'ang-medium', 1024, 700, true );

	}
);

add_action(
	'init', function() {
		// Remove WP default stuff which we do not need.
		remove_action( 'wp_head', '_wp_render_title_tag', 1 );
		remove_action( 'wp_head', 'wp_resource_hints', 2 );
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		remove_action( 'publish_future_post', 'check_and_publish_future_post', 10, 1 );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'rel_canonical' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
		remove_action( 'wp_head', 'wp_site_icon', 99 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );

		// Remove REST API
		remove_action( 'rest_api_init', 'rest_api_default_filters', 10, 1 );
		remove_action( 'init', 'rest_api_init' );
		remove_action( 'rest_api_init', 'register_initial_settings', 10 );
		remove_action( 'rest_api_init', 'create_initial_rest_routes', 99 );
		remove_action( 'parse_request', 'rest_api_loaded' );
	}
);

add_action(
	'wp_enqueue_scripts', function() {

		wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700|Playfair+Display:400,400italic,700,900' );
		wp_enqueue_style( 'normalize-css', 'https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css' );

		if ( is_home() ) {
			wp_enqueue_style( 'scroll-style', get_stylesheet_directory_uri() . '/css/scroll.css' );
			wp_enqueue_script( 'angular-js', 'http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular.min.js', [], '1.3.14', true );
			wp_enqueue_script( 'angular-animate-js', 'http://cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.14/angular-animate.min.js', [ 'angular-js' ], '1.3.14', true );
			wp_enqueue_script( 'scroll-js', get_stylesheet_directory_uri() . '/js/scroll.js', [ 'angular-js', 'angular-animate-js' ], null, true );
		}

		if ( is_post_type_archive( 'attachment' ) ) {
			wp_enqueue_style( 'pagination-style', get_stylesheet_directory_uri() . '/css/pagination.css' );
			wp_enqueue_script( 'pagination-js', get_stylesheet_directory_uri() . '/js/pagination.js', [], null, true );

		}

	}
);

// Remove admin bar on the frontend.
add_filter( 'show_admin_bar', '__return_false' );

if ( ! function_exists( 'register_location_metabox' ) ) {
	/**
	 * Register meta box(es).
	 */
	function register_location_metabox( $post_type ) {

		if ( 'post' != $post_type ) {
			return;
		}

		add_meta_box( 'location-id', __( 'Location', 'infinite-scroll' ), 'custom_location_field', 'post' );
	}

	add_action( 'add_meta_boxes', 'register_location_metabox' );
}

if ( ! function_exists( 'custom_location_field' ) ) {
	/**
	 * Custom meta post box for location.
	 *
	 * @return void
	 */
	function custom_location_field() {
		global $post;
		?>
		<input autocomplete="off" type="text" name="location" value="<?php echo get_post_meta( $post->ID, 'location', true ); ?>" class="">
		<?php
	}
}

// Save location meta value
add_action(
	'save_post', function( $post_id ) {
		if ( ! empty( $_POST['location'] ) ) {
			update_post_meta( $post_id, 'location', sanitize_text_field( $_POST['location'] ) );
		}
	}
);

/**
 * Generate article list
 *
 * @return void
 */
function generate_homepage_article_list() {

	$post_arg = [
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 10,
		'orderby'        => 'ID',
		'order'          => 'DESC',
	];

	$articles = new WP_Query( $post_arg );
	$list     = [];

	if ( empty( $articles->posts ) ) {
		return $list;
	}

	foreach ( $articles->posts as $key => $article ) {
		$list[ $key ]['name']     = $article->post_title;
		$list[ $key ]['summary']  = $article->post_excerpt;
		$list[ $key ]['location'] = get_post_meta( $article->ID, 'location', true );
		$list[ $key ]['image']    = wp_get_attachment_image_url( get_post_thumbnail_id(), $article->ID, 'ang-medium' );
	}
	return $list;
}

if ( ! function_exists( 'homepage_js_script_print_list' ) ) {

	add_action( 'wp_footer', 'homepage_js_script_print_list' );

	/**
	 * Prints list required for angular app
	 *
	 * @return void
	 */
	function homepage_js_script_print_list() {
		if ( ! is_home() ) {
			return;
		}
		wp_localize_script( 'scroll-js', 'list', generate_homepage_article_list() );
	}
}


if ( ! function_exists( 'alter_attachment_post_type_args' ) ) {

	add_filter( 'register_post_type_args', 'alter_attachment_post_type_args', 10, 2 );

	/**
	 * Alters attachment post type attribute
	 *
	 * @param array $args
	 * @param string $post_type
	 *
	 * @return array
	 */
	function alter_attachment_post_type_args( $args, $post_type ) {
		if ( 'attachment' == $post_type ) {
			$args['rewrite']     = [ 'slug' => 'media' ];
			$args['has_archive'] = true;
		}
		return $args;
	}
}

if ( ! function_exists( 'alter_attachment_archive_query' ) ) {

	add_action( 'pre_get_posts', 'alter_attachment_archive_query' );

	/**
	 * Alters attachment post query when in attachment
	 * archive page.
	 *
	 * @param WP_Query $query
	 *
	 * @return void
	 */
	function alter_attachment_archive_query( $query ) {
		if ( ! is_admin() && is_post_type_archive( 'attachment' ) && 'attachment' == $query->get( 'post_type' ) ) {
			$query->set( 'posts_per_page', -1 );
			$query->set( 'post_status', [ 'publish', 'inherit' ] );
			$query->set( 'fields', 'ids' );
		}
	}
}

if ( ! function_exists( 'prepare_media_image_source_list' ) ) {

	add_action( 'wp_footer', 'prepare_media_image_source_list' );
	/**
	 * Adds image src list JS var on attachment archive page.
	 *
	 * @return void
	 */
	function prepare_media_image_source_list() {

		// Bail early if not attachment archive page.
		if ( ! is_post_type_archive( 'attachment' ) ) {
			return;
		}

		global $wp_query;
		if ( ! empty( $wp_query->posts ) ) {
			$images = array_column(
				array_map( 'wp_get_attachment_image_src', $wp_query->posts ),
				0
			);
			wp_localize_script( 'pagination-js', 'wp_images', $images );
		}
	}
}
