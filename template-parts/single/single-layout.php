<?php
/**
 * Template for Single post
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

?>

<div <?php astra_blog_layout_class( 'single-layout-1' ); ?>>

	<?php astra_single_header_before(); ?>

	<?php
	$title_enabled = '';
	if ( ! apply_filters( 'astra_the_title_enabled', true ) ) {
		$title_enabled = 'ast-no-title';
	}
	?>
	<header class="entry-header <?php echo esc_attr( $title_enabled ); ?>">

		<?php astra_single_header_top(); ?>
		
		<?php $featured_image = apply_filters( 'astra_featured_image_enabled', true ); ?>
		<?php if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() && $featured_image ) : ?>
			<div class="post-thumb">
				<?php the_post_thumbnail(); ?>
			</div>
		<?php endif; ?>

		<?php astra_the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>

		<?php astra_single_header_bottom(); ?>

	</header><!-- .entry-header -->

	<?php astra_single_header_after(); ?>

	<div class="entry-content clear" itemprop="text">

		<?php astra_entry_content_before(); ?>

		<?php the_content(); ?>

		<?php
			astra_edit_post_link(

				sprintf(
					/* translators: %s: Name of current post */
					esc_html__( 'Edit %s', 'astra' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>

		<?php astra_entry_content_after(); ?>

		<?php
			wp_link_pages(
				array(
					'before'      => '<div class="page-links">' . esc_html( astra_default_strings( 'string-single-page-links-before', false ) ),
					'after'       => '</div>',
					'link_before' => '<span class="page-link">',
					'link_after'  => '</span>',
				)
			);
		?>
	</div><!-- .entry-content .clear -->
</div>
