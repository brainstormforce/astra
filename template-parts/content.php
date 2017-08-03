<?php
/**
 * Template part for displaying posts.
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

		<?php the_title( sprintf( '<h2 class="entry-title" itemprop="headline"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

	</header><!-- .entry-header -->

	<div class="entry-content clear" itemprop="text">

		<?php astra_entry_content_before(); ?>

		<?php
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. */
						 __( 'Continue reading %s', 'astra' ) . ' <span class="meta-nav">&rarr;</span>', array(
							 'span' => array(
								 'class' => array(),
							 ),
						 )
					),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				)
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

	<footer class="entry-footer">
		<?php astra_entry_footer(); ?>
	</footer><!-- .entry-footer -->

	<?php astra_entry_bottom(); ?>

</article><!-- #post-## -->

<?php astra_entry_after(); ?>
