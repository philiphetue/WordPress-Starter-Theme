<?php
/**
 * The template for displaying all single posts.
 *
 * @package _mbbasetheme
 */

get_header();
the_post();
?>
<div class="post-banner">
	<div class="container">
		<div class="text">
			<p class="pre-heading"><?php echo get_the_term_list( $post->ID, 'category', '', ', ' ); ?></p>
			<h1><?php the_title(); ?></h1>
		</div>
	</div>
	<?php the_post_banner( '100w', 'bg' ); ?>
</div>
<div class="container">
	<div class="post-content">
		<?php the_content(); ?>
	</div>
</div>
<?php get_footer(); ?>