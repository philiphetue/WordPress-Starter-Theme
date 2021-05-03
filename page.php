<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package _mbbasetheme
 */

get_header();
the_post();
?>

<div class="page-banner slide">
	<div class="container">
		<div class="text">
			<h1><?php the_title(); ?></h1>
		</div>
	</div>
	<?php the_post_banner( '100w', 'bg' ); ?>
</div>
<div class="container">
	<div class="page-content">
		<?php the_content(); ?>
	</div>
</div>