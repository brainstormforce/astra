<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$sidebar = apply_filters( 'astra_get_sidebar', 'sidebar-1' );

?>

<div itemtype="https://schema.org/WPSideBar" itemscope="itemscope" id="secondary" <?php astra_secondary_class(); ?> role="complementary">

	<div class="sidebar-main" <?php echo apply_filters( 'astra_sidebar_data_attrs', '', $sidebar ); ?>>

		<?php astra_sidebars_before(); ?>

		<?php if ( is_active_sidebar( $sidebar ) ) : ?>

			<?php dynamic_sidebar( $sidebar ); ?>

		<?php endif; ?>

		<?php astra_sidebars_after(); ?>

	</div><!-- .sidebar-main -->
</div><!-- #secondary -->
