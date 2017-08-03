<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Astra
 * @since 1.0.0
 */

get_header(); ?>

<?php if ( astra_page_layout() == 'left-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

	<div id="primary" <?php astra_primary_class(); ?>>

		<?php astra_primary_content_top(); ?>

		<?php astra_archive_header(); ?>

		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php ;/* Start the Loop */ ?>
			<div class="ast-row">
			<?php
			while ( have_posts() ) :
				the_post();
?>

				<?php astra_entry_before(); ?>

				<article itemtype="http://schema.org/CreativeWork" itemscope="itemscope" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<?php astra_entry_top(); ?>

					<?php astra_entry_content_blog(); ?>

					<?php astra_entry_bottom(); ?>

				</article><!-- #post-## -->

				<?php astra_entry_after(); ?>

			<?php endwhile; ?>
			</div>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->

		<?php astra_pagination(); ?>

		<?php astra_primary_content_bottom(); ?>

	</div><!-- #primary -->

<?php if ( astra_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>
