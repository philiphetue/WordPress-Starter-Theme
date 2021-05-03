<?php
/**
 * The template for displaying search results pages.
 *
 * @package _mbbasetheme
 */

get_header();
?>

<div class="page-banner slide">
	<div class="container">
		<div class="text">
			<p class="pre-heading">Search Results</p>
			<h1><?php echo get_search_query(); ?></h1>
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