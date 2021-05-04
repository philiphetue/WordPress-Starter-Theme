<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="site-content">
 *
 * @package _mbbasetheme
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/favicon.ico">
	<link rel="apple-touch-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/apple-touch-icon.png">
	<?php wp_head(); ?>
</head>

<?php
	global $posts_page_id;
	$posts_page_id = intval( get_option( 'page_for_posts' ) );

	global $body_class;
	$body_class = empty( $body_class ) ? '' : $body_class;
?>

<body <?php body_class( $body_class ); ?>>

<?php include_once( 'assets/images/svg-defs.svg' ); ?>

<header id="site-header">
	<?php printf( '<%1$s id="site-logo"><a href="%2$s"><span class="visuallyhidden">%3$s</span><svg><use xlink:href-"#shape-%4$s"></svg></a></%1$s>', is_front_page() ? 'h1' : 'div', esc_url( home_url( '/' ) ), get_bloginfo( 'name' ), 'logo' ); ?>
	<a id="menu-toggle" href="#"><svg><use xlink:href="#shape-menu"></svg><span>&times;</span></a>
	<nav id="menu-full" class="modal">
		<h2 class="visuallyhidden">Main Navigation</h2>
		<div class="container scroll-container">
			<?php wp_nav_menu( array( 'theme_location' => 'header', 'container' => false ) ); ?>
			<?php get_search_form(); ?>
		</div>
	</nav>
</header>

<div class="site-content">