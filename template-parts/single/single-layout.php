<?php
/**
 * Template for Single post
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.0.0
 */

?>

<div <?php ast_blog_layout_class( 'single-layout-1' ); ?>>

	<?php ast_single_header_before(); ?>

	<header class="entry-header">

		<?php ast_single_header_top(); ?>

		<?php if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) : ?>
			<div class="post-thumb">
				<?php the_post_thumbnail(); ?>
			</div>
		<?php endif; ?>

		<?php ast_the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>

		<?php ast_single_header_bottom(); ?>

	</header><!-- .entry-header -->

	<?php ast_single_header_after(); ?>

	<div class="entry-content clear" itemprop="text">

		<?php ast_entry_content_before(); ?>

		<?php the_content(); ?>

		<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					esc_html__( 'Edit %s', 'astra' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>

		<?php ast_entry_content_after(); ?>

		<?php
			wp_link_pages( array(
				'before'      => '<div class="page-links">' . ast_default_strings( 'string-single-page-links-before', false ),
				'after'       => '</div>',
				'link_before' => '<span class="page-link">',
				'link_after'  => '</span>',
			) );
		?>
	</div><!-- .entry-content .clear -->
</div>
