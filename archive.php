<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package _mbbasetheme
 */

get_header(); ?>

<div class="page-banner slide">
	<div class="container">
		<div class="text">
			<?php
				if ( is_category() ) : ?>
					<p class="pre-heading">Category</p>
					<h1><?php single_cat_title(); ?></h1>
					<?php

				elseif ( is_tag() ) : ?>
					<p class="pre-heading">Tag</p>
					<h1><?php single_tag_title(); ?></h1>
					<?php

				elseif ( is_author() ) : ?>
					<p class="pre-heading">Author</p>
					<h1><?php echo get_the_author(); ?></h1>
					<?php

				elseif ( is_day() ) : ?>
					<p class="pre-heading">Day</p>
					<h1><?php echo get_the_date(); ?></h1>
					<?php

				elseif ( is_month() ) : ?>
					<p class="pre-heading">Month</p>
					<h1><?php echo get_the_date( 'F Y' ); ?></h1>
					<?php

				elseif ( is_year() ) : ?>
					<p class="pre-heading">Year</p>
					<h1><?php echo get_the_date( 'Y' ); ?></h1>
					<?php

				elseif ( is_tax( 'post_format', 'post-format-aside' ) ) : ?>
					<h1><?php _e( 'Asides', '_mbbasetheme' ); ?></h1>
					<?php

				elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) : ?>
					<h1><?php _e( 'Galleries', '_mbbasetheme' ); ?></h1>
					<?php

				elseif ( is_tax( 'post_format', 'post-format-image' ) ) : ?>
					<h1><?php _e( 'Images', '_mbbasetheme' ); ?></h1>
					<?php

				elseif ( is_tax( 'post_format', 'post-format-video' ) ) : ?>
					<h1><?php _e( 'Videos', '_mbbasetheme' ); ?></h1>
					<?php

				elseif ( is_tax( 'post_format', 'post-format-quote' ) ) : ?>
					<h1><?php _e( 'Quotes', '_mbbasetheme' ); ?></h1>
					<?php

				elseif ( is_tax( 'post_format', 'post-format-link' ) ) : ?>
					<h1><?php _e( 'Links', '_mbbasetheme' ); ?></h1>
					<?php

				elseif ( is_tax( 'post_format', 'post-format-status' ) ) : ?>
					<h1><?php _e( 'Statuses', '_mbbasetheme' ); ?></h1>
					<?php

				elseif ( is_tax( 'post_format', 'post-format-audio' ) ) : ?>
					<h1><?php _e( 'Audios', '_mbbasetheme' ); ?></h1>
					<?php

				elseif ( is_tax( 'post_format', 'post-format-chat' ) ) : ?>
					<h1><?php _e( 'Chats', '_mbbasetheme' ); ?></h1>
					<?php

				else : ?>
					<h1><?php _e( 'Archives', '_mbbasetheme' ); ?></h1>
					<?php

				endif;
			?>
			<?php
				// Show an optional term description.
				$term_description = term_description();
				if ( ! empty( $term_description ) ) {
					?>
					<p><?php echo $term_description; ?></p>
					<?php
				}
			?>
		</div>
	</div>
</div>
<div class="container">
	<div class="page-content">
		<?php
			if ( have_posts() ) {

				while ( have_posts() ) {
					the_post();
					get_template_part( 'templates/partials/loop', get_post_type() );
				}

				_mbbasetheme_paging_nav();
			} else {
				get_template_part( 'content', 'none' );
			}
		?>
	</div>
</div>

<?php get_footer(); ?>