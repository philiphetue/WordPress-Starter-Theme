<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package _mbbasetheme
 */

get_header();

global $posts_page_id;
?>

<div class="page-banner slide">
	<div class="container">
		<div class="text">
			<h1><?php echo get_the_title( $posts_page_id ); ?></h1>
		</div>
	</div>
	<?php the_post_banner( '100vw', 'bg', $posts_page_id ); ?>
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