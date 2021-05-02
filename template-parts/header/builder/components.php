<?php
/**
 * Template part for header component.
 *
 * @package Astra
 */

if ( astra_wp_version_compare( '5.4.99', '>=' ) ) {
	$component_args = wp_parse_args(
		$args,
		array(
			'type'   => '',
			'device' => '',
		) 
	);
	$component_slug = $component_args['type'];
} else {
	$component_slug = get_query_var( 'type' );
}

$device = $component_args['device'];

switch ( $component_slug ) {

	case 'logo':
		?>
		<div class="ast-builder-layout-element ast-flex site-header-focus-item" data-section="title_tagline">
			<?php do_action( 'astra_site_identity' ); ?>
		</div>
		<?php
		break;

	case 'button-1':
		?>
		<div class="ast-builder-layout-element ast-flex site-header-focus-item ast-header-button-1" data-section="section-hb-button-1">
			<?php do_action( 'astra_header_button_1' ); ?>
		</div>
		<?php
		break;

	case 'menu-1':
		?>
		<div class="ast-builder-menu-1 ast-builder-menu ast-flex ast-builder-menu-1-focus-item ast-builder-layout-element site-header-focus-item" data-section="section-hb-menu-1">
			<?php do_action( 'astra_header_menu_1' ); ?>
		</div>
		<?php
		break;

	case 'menu-2':
		?>
		<div class="ast-builder-menu-2 ast-builder-menu ast-flex ast-builder-menu-2-focus-item ast-builder-layout-element site-header-focus-item" data-section="section-hb-menu-2">
			<?php do_action( 'astra_header_menu_2' ); ?>
		</div>
		<?php
		break;

	case 'mobile-menu':
		?>
		<div class="ast-builder-menu-mobile ast-builder-menu ast-builder-menu-mobile-focus-item ast-builder-layout-element site-header-focus-item" data-section="section-header-mobile-menu">
			<?php do_action( 'astra_header_menu_mobile' ); ?>
		</div>
		<?php
		break;

	case 'html-1':
		?>
		<div class="ast-builder-layout-element ast-flex site-header-focus-item ast-header-html-1" data-section="section-hb-html-1">
			<?php do_action( 'astra_header_html_1' ); ?>
		</div>
		<?php
		break;

	case 'html-2':
		?>
			<div class="ast-builder-layout-element ast-flex site-header-focus-item ast-header-html-2" data-section="section-hb-html-2">
				<?php do_action( 'astra_header_html_2' ); ?>
			</div>
			<?php
		break;

	case 'search':
		?>
		<div class="ast-builder-layout-element ast-flex site-header-focus-item ast-header-search" data-section="section-header-search">
			<?php do_action( 'astra_header_search', $args['device'] ); ?>
		</div>
		<?php
		break;

	case 'social-icons-1':
		?>
		<div class="ast-builder-layout-element ast-flex site-header-focus-item" data-section="section-hb-social-icons-1">
			<?php do_action( 'astra_header_social_1' ); ?>
		</div>
		<?php
		break;

	case 'mobile-trigger':
		if ( 'desktop' === $device && ! defined( 'ASTRA_EXT_VER' ) ) {
			break;
		}
		?>
		<div class="ast-builder-layout-element ast-flex site-header-focus-item" data-section="section-header-mobile-trigger">
			<?php do_action( 'astra_header_mobile_trigger' ); ?>
		</div>
		<?php
		break;

	case 'account':
		?>
		<div class="ast-builder-layout-element site-header-focus-item ast-header-account" data-section="section-header-account">
			<?php do_action( 'astra_header_account' ); ?>
		</div>
		<?php
		break;

	case 'woo-cart':
		if ( class_exists( 'Astra_Woocommerce' ) ) {
			?>
			<div class="ast-builder-layout-element site-header-focus-item ast-header-woo-cart" data-section="section-header-woo-cart">
				<?php do_action( 'astra_header_woo_cart' ); ?>
			</div>
			<?php
		}
		break;

	case 'edd-cart':
		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			?>
			<div class="ast-builder-layout-element site-header-focus-item ast-header-edd-cart" data-section="section-header-edd-cart">
				<?php do_action( 'astra_header_edd_cart' ); ?>
			</div>
			<?php
		}
		break;
	case 'widget-1':
		?>
		<aside
		<?php
		echo astra_attr(
			'header-widget-area-inner',
			array(
				'class'        => 'header-widget-area widget-area site-header-focus-item',
				'data-section' => 'sidebar-widgets-header-widget-1',
				'aria-label'   => 'Header Widget 1',
			) 
		); 
		?>
		>
			<?php
			if ( is_customize_preview() && class_exists( 'Astra_Builder_UI_Controller' ) ) {
				Astra_Builder_UI_Controller::render_customizer_edit_button();
			}
			?>
			<?php 
			astra_markup_open( 'header-widget-div' );
			astra_get_sidebar( 'header-widget-1' );
			astra_markup_close( 'header-widget-div' ); 
			?>
		</aside>
			<?php 
		break;
	case 'widget-2':
		?>
		<aside
		<?php
		echo astra_attr(
			'header-widget-area-inner',
			array(
				'class'        => 'header-widget-area widget-area site-header-focus-item',
				'data-section' => 'sidebar-widgets-header-widget-2',
				'aria-label'   => 'Header Widget 2',
			) 
		); 
		?>
		>
			<?php
			if ( is_customize_preview() && class_exists( 'Astra_Builder_UI_Controller' ) ) {
				Astra_Builder_UI_Controller::render_customizer_edit_button();
			}
			?>
			<?php
			astra_markup_open( 'header-widget-div' );
			astra_get_sidebar( 'header-widget-2' ); 
			astra_markup_close( 'header-widget-div' ); 
			?>
		</aside>
		<?php 
		break;
		
	default:
		do_action( 'astra_render_header_components', $component_slug );
		break;

}
?>
