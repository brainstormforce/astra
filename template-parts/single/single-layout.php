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

	<header class="entry-header <?php astra_entry_header_class(); ?>">

		<?php astra_single_header_top(); ?>

		<?php astra_blog_post_thumbnail_and_title_order(); ?>

		<?php astra_single_header_bottom(); ?>

	</header><!-- .entry-header -->

	<?php astra_single_header_after(); ?>

	<div <?php astra_attr( 'entry-content', 'single' ); ?> class="entry-content clear" >

		<?php astra_entry_content_before(); ?>

		<?php astra_entry_content(); ?>

		<?php astra_entry_single_edit_post_link(); ?>

		<?php astra_entry_content_after(); ?>

		<?php astra_entry_single_content_link_pages(); ?>
	</div><!-- .entry-content .clear -->
</div>
