<?php
/**
 * Template for Search
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

/**
 * Content Blog Templates.
 *
 * => Action hooked 'astra_entry_content_blog'
 *
 * 'astra_entry_content_blog_template' function found in "framework/structure/common.php"
 *
 * @since 1.0.0
 */
add_action( 'astra_entry_content_blog', 'astra_entry_content_blog_template' );

?>

<?php astra_entry_before(); ?>

<article itemtype="http://schema.org/CreativeWork" itemscope="itemscope" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php astra_entry_top(); ?>

	<?php astra_entry_content_blog(); ?>

	<?php astra_entry_bottom(); ?>

</article><!-- #post-## -->

<?php astra_entry_after(); ?>
