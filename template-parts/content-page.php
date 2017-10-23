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

<?php

/**
 * Page Content Template Part
 *
 * => Action hooked 'astra_entry_content_page'
 *
 * 'astra_entry_content_page_template' function found in "framework/structure/page.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_content_page', 'astra_entry_content_page_template', 10 );

/**
 * Page Content Comments
 *
 * => Action hooked 'astra_entry_after'
 *
 * 'astra_page_comments' function found in "framework/structure/page.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_after', 'astra_page_comments' );

?>

<?php astra_entry_before(); ?>

<article itemtype="http://schema.org/CreativeWork" itemscope="itemscope" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php astra_entry_top(); ?>

	<?php astra_entry_content_page(); ?>
	
	<?php astra_entry_bottom(); ?>

</article><!-- #post-## -->

<?php astra_entry_after(); ?>