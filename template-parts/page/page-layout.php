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
 * Page content.
 *
 * => Action hooked 'astra_entry_content'
 *
 * 'astra_page_content' function found in "framework/structure/page.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_content', 'astra_page_content', 10 );

/**
 * Page featured image.
 *
 * => Action hooked 'astra_entry_page_header_content'
 *
 * 'astra_entry_page_header_the_image' function found in "framework/structure/page.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_page_header_content', 'astra_entry_page_header_the_image', 5 );

/**
 * Page title.
 *
 * => Action hooked 'astra_entry_page_header_content'
 *
 * 'astra_entry_page_header_the_title' function found in "framework/structure/page.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_page_header_content', 'astra_entry_page_header_the_title', 10 );

/**
 * Page Content Pagination.
 *
 * => Action hooked 'astra_entry_page_content_link_pages'
 *
 * 'astra_entry_page_content_the_link_pages' function found in "framework/structure/page.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_page_content_link_pages', 'astra_entry_page_content_the_link_pages', 10 );

/**
 * Page Edit Link.
 *
 * => Action hooked 'astra_entry_page_edit_post_link'
 *
 * 'astra_entry_page_the_edit_post_link' function found in "framework/structure/page.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_page_edit_post_link', 'astra_entry_page_the_edit_post_link', 10 );

?>

<header class="entry-header <?php astra_entry_header_class(); ?>">

	<?php astra_entry_page_header_content(); ?>
</header><!-- .entry-header -->

<div class="entry-content clear" itemprop="text">

	<?php astra_entry_content_before(); ?>

	<?php astra_entry_content(); ?>

	<?php astra_entry_content_after(); ?>

	<?php astra_entry_page_content_link_pages(); ?>

</div><!-- .entry-content .clear -->

<?php astra_entry_page_edit_post_link(); ?>
