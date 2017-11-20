<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

?>

<div <?php astra_attr( 'sidebar' ); ?>>

	<div class="sidebar-main">

		<?php astra_sidebars_before(); ?>
		
		<?php astra_sidebars_content(); ?>
		
		<?php astra_sidebars_after(); ?>

	</div><!-- .sidebar-main -->
</div><!-- #secondary -->
