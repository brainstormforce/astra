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

<?php if ( ast_page_layout() == 'left-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

	<div id="primary" <?php ast_primary_class(); ?>>

		<?php ast_archive_header(); ?>

		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<div class="ast-row">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php ast_entry_before(); ?>

				<article itemtype="http://schema.org/CreativeWork" itemscope="itemscope" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<?php ast_entry_top(); ?>

					<?php ast_entry_content_blog(); ?>

					<?php ast_entry_bottom(); ?>

				</article><!-- #post-## -->

				<?php ast_entry_after(); ?>

			<?php endwhile; ?>
			</div>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->

		<?php ast_pagination(); ?>

	</div><!-- #primary -->

<?php if ( ast_page_layout() == 'right-sidebar' ) : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<?php get_footer(); ?>
