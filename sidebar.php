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

<div itemtype="http://schema.org/WPSideBar" itemscope="itemscope" id="secondary" <?php astra_secondary_class(); ?> role="complementary">

	<div class="sidebar-main">

		<?php astra_sidebars_before(); ?>
		
		<?php astra_sidebars_content(); ?>
		
		<?php astra_sidebars_after(); ?>

	</div><!-- .sidebar-main -->
</div><!-- #secondary -->
