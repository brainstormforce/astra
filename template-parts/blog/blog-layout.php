<?php
/**
 * Template for Blog
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

?>
<div <?php astra_blog_layout_class( 'blog-layout-1' ); ?>>

	<div class="ast-blog-featured-section post-thumb ast-col-md-12">
		<?php astra_blog_post_featured_format(); ?>
	</div><!-- .post-thumb -->

	<div class="post-content ast-col-md-12">
		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title" itemprop="headline"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			
			<?php astra_blog_get_post_meta( array( 'date', 'link' ) ); ?>
		</header><!-- .entry-header -->

		<div class="entry-content clear" itemprop="text">

			<?php astra_entry_content_before(); ?>

			<?php astra_the_excerpt(); ?>

			<?php astra_entry_content_after(); ?>

			<?php
				wp_link_pages( array(
					'before'      => '<div class="page-links">' . esc_html( astra_default_strings( 'string-blog-page-links-before', false ) ),
					'after'       => '</div>',
					'link_before' => '<span class="page-link">',
					'link_after'  => '</span>',
				) );
			?>
		</div><!-- .entry-content .clear -->

		<footer class="entry-footer">
			
		</footer><!-- .entry-footer -->
	</div><!-- .post-content -->

</div> <!-- .blog-layout-1 -->
