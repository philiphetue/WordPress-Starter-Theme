<?php
/**
 * _mbbasetheme theme functions definted in /lib/init.php
 *
 * @package _mbbasetheme
 */


/**
 * Register Widget Areas
 */
function mb_widgets_init() {
	// Main Sidebar
	register_sidebar( array(
		'name'          => __( 'Sidebar', '_mbbasetheme' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}

/**
 * Remove Dashboard Meta Boxes
 */
function mb_remove_dashboard_widgets() {
	global $wp_meta_boxes;
	// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}

/**
 * Change Admin Menu Order
 */
function mb_custom_menu_order( $menu_ord ) {
	if ( !$menu_ord ) return true;
	return array(
		// 'index.php', // Dashboard
		// 'separator1', // First separator
		// 'edit.php?post_type=page', // Pages
		// 'edit.php', // Posts
		// 'upload.php', // Media
		// 'gf_edit_forms', // Gravity Forms
		// 'genesis', // Genesis
		// 'edit-comments.php', // Comments
		// 'separator2', // Second separator
		// 'themes.php', // Appearance
		// 'plugins.php', // Plugins
		// 'users.php', // Users
		// 'tools.php', // Tools
		// 'options-general.php', // Settings
		// 'separator-last', // Last separator
	);
}

/**
 * Hide Admin Areas that are not used
 */
function mb_remove_menu_pages() {
	// remove_menu_page( 'link-manager.php' );
}

/**
 * Remove default link for images
 */
function mb_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );
	if ( $image_set !== 'none' ) {
		update_option( 'image_default_link_type', 'none' );
	}
}

/**
 * Enqueue scripts
 */
function mb_scripts() {
	global $google_maps_api_key;
	$cache_buster = '0';
	wp_enqueue_style( '_mbbasetheme-style', get_stylesheet_uri(), array(), $cache_buster );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( !is_admin() ) {
		wp_enqueue_script( 'jquery' );
		# Note: add 'googlemaps' to customplugins and customscripts dependency arrays
		// wp_enqueue_script( 'googlemaps', "//maps.googleapis.com/maps/api/js?key={$google_maps_api_key}", array(), NULL, true );
		wp_enqueue_script( 'customplugins', get_template_directory_uri() . '/assets/js/plugins.min.js', array( 'jquery' ), $cache_buster, true );
		wp_enqueue_script( 'customscripts', get_template_directory_uri() . '/assets/js/main.min.js', array( 'jquery' ), $cache_buster, true );
	}
}

function furrow_admin_styles() {
	wp_enqueue_style( 'admin-styles', get_template_directory_uri() . '/admin-style.css' );
}

/**
 * Remove Query Strings From Static Resources
 */
function mb_remove_script_version( $src ){
	$parts = explode( '?ver', $src );
	return $parts[0];
}

/**
 * Remove Read More Jump
 */
function mb_remove_more_jump_link( $link ) {
	$offset = strpos( $link, '#more-' );
	if ($offset) {
		$end = strpos( $link, '"',$offset );
	}
	if ($end) {
		$link = substr_replace( $link, '', $offset, $end-$offset );
	}
	return $link;
}

/**
 * Output a banner image
 */
function print_responsive_image( $id, $sizes = '100vw', $class = '', $attr = [] ) {
	$image = wp_get_attachment_image_src( $id, 'furrow_large' );

	$src = $image[ 0 ];
	$srcset = wp_get_attachment_image_srcset( $id );
	$title = get_the_title( $id );
	$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
	$attr_str = '';

	foreach ( $attr as $att => $val ) {
		$attr_str .= " {$att}=\"{$val}\"";
	}

	printf( '<img src="%s" srcset="%s" alt="%s" title="%s" sizes="%s" class="%s" %s>', $src, $srcset, $alt, $title, $sizes, $class, $attr_str );
}

function the_post_banner( $banner_sizes = '100vw', $banner_class = '', $post = null, $attr = [] ) {
	$post = get_post( $post );
    if ( ! $post ) {
        return '';
    }

	if ( has_post_thumbnail() ) {
	    $banner_id = get_post_thumbnail_id( $post );
	} else {
		$def_image_gallery = get_field( 'default_images', 'option' );
		$banner_id = $def_image_gallery[ array_rand( $def_image_gallery ) ][ 'ID' ];
	}

	print_responsive_image( $banner_id, $banner_sizes, $banner_class, $attr );
}

function print_default_image( $sizes = '100vw', $class = '' ) {
	$def_image_gallery = get_field( 'default_images', 'option' );
	$id = $def_image_gallery[ array_rand( $def_image_gallery ) ][ 'ID' ];

	print_responsive_image( $id, $sizes, $class );
}


/**
 * Add ability to add a class to wp_nav_menu links
 */

function furrow_add_menu_link_class( $atts, $item, $args ) {
	if ( property_exists( $args, 'link_class' ) ) {
		$atts[ 'class' ] = $args->link_class;
	}
	if ( property_exists( $args, 'top_link_class' ) && $item->menu_item_parent == 0 ) {
		$atts[ 'class' ] = $args->top_link_class;
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'furrow_add_menu_link_class', 1, 3 );


/**
 * Increase the maximum size returned by WP srcset
 */

function furrow_max_srcset_image_width( $max_srcset_image_width, $size_array ) {
    return 2560;
}
add_filter( 'max_srcset_image_width', 'furrow_max_srcset_image_width', 10, 2 );

/**
 * Set API Key for ACF Google Maps usage
 */

function furrow_acf_google_map_api( $api ){
	global $google_maps_api_key;
    $api[ 'key' ] = $google_maps_api_key;
    return $api;
}
// add_filter( 'acf/fields/google_map/api', 'furrow_acf_google_map_api' );


/**
 * Shortcodes
 */

function furrow_button( $atts, $content = 'Read More' ) {
    $a = shortcode_atts( array(
        'url'    => '/',
        'color'  => 'blue',
        'size'   => 'large',
        'newtab' => 'false',
    ), $atts );

    $target = $a[ 'newtab' ] == 'true' || $a[ 'newtab' ] == '1' || $a[ 'newtab' ] == 1 ? '_blank' : '_self';
    $url = esc_url( $a[ 'url' ] );

    return "<a href=\"{$url}\" class=\"furrow-button {$a['color']} {$a['size']}\" target=\"{$target}\">{$content}</a>";
}
// add_shortcode( 'button', 'furrow_button' );

function furrow_blockquote( $atts, $content = '' ) {
    $a = shortcode_atts( array(
        'name'  => null,
        'title' => null,
    ), $atts );

    return "
		<figure class=\"furrow-quote\">
			<blockquote>{$content}</blockquote>
			<figcaption>
				<p class=\"author\">{$a['name']}</p>
				<p class=\"title\">{$a['title']}</p>
			</figcaption>
		</figure>	
	";
}
// add_shortcode( 'quote', 'furrow_blockquote' );


/**
 * Use {{ }} to wrap text in <span>
 */
function furrow_remove_braces( $string ) {
    return preg_replace( '/\{\{|\}\}/', '', $string );
}
function furrow_replace_braces( $string, $post_id = null, $field = null ) {
	$string = wptexturize( $string ); # replace straight quotes with smart quotes
	$string = str_replace( '{{', '<span class="highlight">', $string );
	$string = str_replace( '}}', '</span>', $string );

    if ( is_admin() ) {
        return strip_tags( $string );
    }
	return $string;
}

// add_filter( 'wp_title', 'furrow_remove_braces' );
// add_filter( 'admin_title', 'furrow_remove_braces' );
// add_filter( 'wpseo_title', 'furrow_remove_braces' );

// add_filter( 'the_title', 'furrow_replace_braces' );

// add_filter( 'acf/format_value/type=text', 'furrow_replace_braces', 10, 3 );
// add_filter( 'acf/format_value/type=textarea', 'furrow_replace_braces', 10, 3 );


/**
 * Extend WordPress search to include custom fields
 * http://adambalee.com
 */

# Join posts and postmeta tables
# http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join

function cf_search_join( $join ) {
    global $wpdb;
    if ( is_search() ) {    
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    return $join;
}

# Modify the search query with posts_where
# http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where

function cf_search_where( $where ) {
    global $pagenow, $wpdb;
    if ( is_search() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }
    return $where;
}

# Prevent duplicates
# http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct

function cf_search_distinct( $where ) {
    global $wpdb;
    if ( is_search() ) {
        return "DISTINCT";
    }
    return $where;
}

// add_filter( 'posts_join', 'cf_search_join' );
// add_filter( 'posts_where', 'cf_search_where' );
// add_filter( 'posts_distinct', 'cf_search_distinct' );


/**
 * Customize excerpts
 */

# Use ACF content when appropriate
function furrow_acf_excerpt( $excerpt ) {

	# If the excerpt already exists, leave it. If this is the admin, don't change anything
	if ( !empty( $excerpt ) || is_admin() ) return $excerpt;

	# try to get body content first
	// $content = get_field( 'page_content' );
	// foreach ( $content as $block ) {
		// if ( $block[ 'acf_fc_layout' ] == 'body_content' ) {
		// 	return wp_trim_words( strip_tags( $block[ 'content' ] ), 30 );
		// } elseif ( $block[ 'acf_fc_layout' ] == 'two_columns' ) {
		// 	return wp_trim_words( strip_tags( $block[ 'body' ] ), 30 );
		// }
	// }

	# then, try the banner text field
	// $banner = get_field( 'banner' );
	// if ( !empty( $banner[ 'sub_heading' ] ) ) return wp_trim_words( strip_tags( $banner[ 'sub_heading' ] ), 30 );
	// if ( !empty( $banner[ 'text' ][ 'body' ] ) ) return wp_trim_words( strip_tags( $banner[ 'text' ][ 'body' ] ), 30 );

	# if there's no content to be pulled from anywhere, return an empty string
	return '';
}
// add_filter( 'get_the_excerpt', 'furrow_acf_excerpt', 10, 1 );

# Change the excerpt length to be 30 words
function furrow_excerpt_length( $length ) {
	if ( is_admin() ) return $length;

	return 30;
}
add_filter( 'excerpt_length', 'furrow_excerpt_length', 999 );

# Use ellipsis only when content is truncated.
function furrow_excerpt_more( $more ) {
	if ( is_admin() ) return $more;

	return '&hellip;';
}
add_filter( 'excerpt_more', 'furrow_excerpt_more', 999 );