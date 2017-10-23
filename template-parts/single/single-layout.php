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

<?php

/**
 * Single Post content.
 *
 * => Action hooked 'astra_entry_content'
 *
 * 'astra_single_content' function found in "framework/structure/single.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_content', 'astra_single_content', 10 );

/**
 * Single Edit Post Link.
 *
 * => Action hooked 'astra_entry_single_edit_post_link'
 *
 * 'astra_entry_single_the_edit_post_link' function found in "framework/structure/single.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_single_edit_post_link', 'astra_entry_single_the_edit_post_link', 10 );

/**
 * Single Post Content Pagination.
 *
 * => Action hooked 'astra_entry_single_content_link_pages'
 *
 * 'astra_entry_single_content_the_link_pages' function found in "framework/structure/single.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_single_content_link_pages', 'astra_entry_single_content_the_link_pages', 10 );

?>

<div <?php astra_blog_layout_class( 'single-layout-1' ); ?>>

	<?php astra_single_header_before(); ?>

	<header class="entry-header <?php astra_entry_header_class(); ?>">

		<?php astra_single_header_top(); ?>

		<?php astra_blog_post_thumbnai_and_title_order(); ?>

		<?php astra_single_header_bottom(); ?>

	</header><!-- .entry-header -->

	<?php astra_single_header_after(); ?>

	<div class="entry-content clear" itemprop="text">

		<?php astra_entry_content_before(); ?>

		<?php astra_entry_content(); ?>

		<?php astra_entry_single_edit_post_link(); ?>

		<?php astra_entry_content_after(); ?>

		<?php astra_entry_single_content_link_pages(); ?>
			
	</div><!-- .entry-content .clear -->
</div>
