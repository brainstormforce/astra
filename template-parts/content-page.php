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

<article 
	<?php
		echo astra_attr(
			'article-page',
			array(
				'itemtype'  => 'https://schema.org/CreativeWork',
				'itemscope' => 'itemscope',
				'id'        => 'post-' . get_the_id(),
				'class'     => join( ' ', get_post_class() ),

			)
		);
		?>
>

	<?php astra_entry_top(); ?>

	<header class="entry-header <?php astra_entry_header_class(); ?>">

		<?php astra_get_post_thumbnail(); ?>

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
