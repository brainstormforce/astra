<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 1.0.0
 */

?>
<?php

/**
 * Single Content Template Part
 *
 * => Action hooked 'astra_entry_content_single'
 *
 * 'astra_entry_content_single_template' function found in "framework/structure/single.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_content_single', 'astra_entry_content_single_template', 10 );

/**
 * Single Content Comments
 *
 * => Action hooked 'astra_entry_after'
 *
 * 'astra_single_comments' function found in "framework/structure/single.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_after', 'astra_single_comments' );
?>

<?php astra_entry_before(); ?>

<article itemtype="http://schema.org/CreativeWork" itemscope="itemscope" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php astra_entry_top(); ?>

	<?php astra_entry_content_single(); ?>
	
	<?php astra_entry_bottom(); ?>

</article><!-- #post-## -->

<?php astra_entry_after(); ?>
