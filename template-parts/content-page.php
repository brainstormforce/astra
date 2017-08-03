<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 1.0.0
 */

?>

<?php astra_entry_before(); ?>

<article itemtype="http://schema.org/CreativeWork" itemscope="itemscope" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php astra_entry_top(); ?>

	<?php
	$title_enabled = '';
	if ( ! apply_filters( 'astra_the_title_enabled', true ) ) {
		$title_enabled = 'ast-no-title';
	}
	?>
	<header class="entry-header <?php echo esc_attr( $title_enabled ); ?>">
		<?php $featured_image = apply_filters( 'astra_featured_image_enabled', true ); ?>
		<?php if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() && $featured_image ) : ?>
			<div class="post-thumb">
				<?php the_post_thumbnail(); ?>
			</div>
		<?php endif; ?>

		<?php astra_the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content clear" itemprop="text">

		<?php astra_entry_content_before(); ?>

		<?php the_content(); ?>

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

	<?php
		astra_edit_post_link(

			sprintf(
				/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'astra' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<footer class="entry-footer"><span class="edit-link">',
			'</span></footer><!-- .entry-footer -->'
		);
	?>

	<?php astra_entry_bottom(); ?>

</article><!-- #post-## -->

<?php astra_entry_after(); ?>
