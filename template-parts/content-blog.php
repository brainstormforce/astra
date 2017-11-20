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

<article <?php astra_attr( 'article', 'archive' ); ?>>

	<?php astra_entry_top(); ?>

	<?php astra_entry_content_blog(); ?>

	<?php astra_entry_bottom(); ?>

</article><!-- #post-## -->

<?php astra_entry_after(); ?>
