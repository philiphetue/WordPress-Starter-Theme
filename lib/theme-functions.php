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
	wp_enqueue_style( '_mbbasetheme-style', get_stylesheet_uri() );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( !is_admin() ) {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'customplugins', get_template_directory_uri() . '/assets/js/plugins.min.js', array('jquery'), NULL, true );
		wp_enqueue_script( 'customscripts', get_template_directory_uri() . '/assets/js/main.min.js', array('jquery'), NULL, true );
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
function print_responsive_image( $id, $sizes = '100vw', $class = '' ) {
	$image = wp_get_attachment_image_src( $id, 'furrow_large' );

	$src = $image[ 0 ];
	$srcset = wp_get_attachment_image_srcset( $id );
	$title = get_the_title( $id );
	$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );

	printf( '<img src="%s" srcset="%s" alt="%s" title="%s" sizes="%s" class="%s">', $src, $srcset, $alt, $title, $sizes, $class );
}

function the_post_banner( $banner_sizes = '100vw', $banner_class = '', $post = null ) {
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

	print_responsive_image( $banner_id, $banner_sizes, $banner_class );
}

/**
 * Add ability to add a class to wp_nav_menu links
 */

function furrow_add_menu_link_class( $atts, $item, $args ) {
	if ( property_exists( $args, 'link_class' ) ) {
		$atts[ 'class' ] = $args->link_class;
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
