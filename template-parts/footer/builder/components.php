<?php
/**
 * Template part for displaying the footer component.
 *
 * @package Astra
 */

if ( astra_wp_version_compare( '5.4.99', '>=' ) ) {

	$component_slug = wp_parse_args( $args, array( 'type' => '' ) );
	$component_slug = $component_slug['type'];
} else {

	$component_slug = get_query_var( 'type' );
}

switch ( $component_slug ) {

	case 'copyright':
		?>
			<div class="ast-builder-layout-element ast-flex site-footer-focus-item ast-footer-copyright" data-section="section-footer-builder">
				<?php do_action( 'astra_footer_copyright' ); ?>
			</div>
		<?php
		break;

	case 'social-icons-1':
		?>
			<div class="ast-builder-layout-element ast-flex site-footer-focus-item" data-section="section-fb-social-icons-1">
				<?php do_action( 'astra_footer_social_1' ); ?>
			</div>
		<?php
		break;

	case 'widget-1':
		?>
		<aside
		<?php
		echo astra_attr(
			'footer-widget-area-inner',
			array(
				'class'        => 'footer-widget-area widget-area site-footer-focus-item',
				'data-section' => 'sidebar-widgets-footer-widget-1',
				'aria-label'   => 'Footer Widget 1',
			) 
		); 
		?>
				>
			<?php
			astra_markup_open( 'footer-widget-div' );
			astra_get_sidebar( 'footer-widget-1' );
			astra_markup_close( 'footer-widget-div' );
			?>
		</aside>
		<?php 
		break;

	case 'widget-2':
		?>
		<aside
		<?php
		echo astra_attr(
			'footer-widget-area-inner',
			array(
				'class'        => 'footer-widget-area widget-area site-footer-focus-item',
				'data-section' => 'sidebar-widgets-footer-widget-2',
				'aria-label'   => 'Footer Widget 2',
			) 
		); 
		?>
		>
			<?php
			astra_markup_open( 'footer-widget-div' );
			astra_get_sidebar( 'footer-widget-2' );
			astra_markup_close( 'footer-widget-div' );
			?>
		</aside>
		<?php 
		break;

	case 'widget-3':
		?>
		<aside
		<?php
		echo astra_attr(
			'footer-widget-area-inner',
			array(
				'class'        => 'footer-widget-area widget-area site-footer-focus-item',
				'data-section' => 'sidebar-widgets-footer-widget-3',
				'aria-label'   => 'Footer Widget 3',
			) 
		); 
		?>
		>
			<?php
			astra_markup_open( 'footer-widget-div' );
			astra_get_sidebar( 'footer-widget-3' );
			astra_markup_close( 'footer-widget-div' );
			?>
		</aside>
		<?php 
		break;
		
	case 'widget-4': 
		?>
		<aside 
		<?php 
		echo astra_attr(
			'footer-widget-area-inner',
			array(
				'class'        => 'footer-widget-area widget-area site-footer-focus-item',
				'data-section' => 'sidebar-widgets-footer-widget-4',
				'aria-label'   => 'Footer Widget 4',
			) 
		); 
		?>
		>
			<?php
			astra_markup_open( 'footer-widget-div' );
			astra_get_sidebar( 'footer-widget-4' );
			astra_markup_close( 'footer-widget-div' );
			?>
		</aside>
		<?php 
		break;

	case 'html-1':
		?>
		<div class="footer-widget-area widget-area site-footer-focus-item ast-footer-html-1" data-section="section-fb-html-1">
			<?php do_action( 'astra_footer_html_1' ); ?>
		</div>
		<?php
		break;

	case 'html-2':
		?>
			<div class="footer-widget-area widget-area site-footer-focus-item ast-footer-html-2" data-section="section-fb-html-2">
				<?php do_action( 'astra_footer_html_2' ); ?>
			</div>
			<?php
		break;

	case 'menu':
		?>
			<div class="footer-widget-area widget-area site-footer-focus-item" data-section="section-footer-menu">
				<?php do_action( 'astra_footer_menu' ); ?>
			</div>
			<?php
		break;

	case 'divider-1':
		$layout_class = astra_get_option( 'footer-divider-1-layout' );
		?>
		<div class="footer-widget-area widget-area ast-flex site-footer-focus-item ast-footer-divider-element ast-footer-divider-1 ast-fb-divider-layout-<?php echo esc_attr( $layout_class ); ?>" data-section="section-fb-divider-1">
			<?php do_action( 'astra_footer_divider_1' ); ?>
		</div>
		<?php
		break;


	default:
		do_action( 'astra_render_footer_components', $component_slug );
		break;

}
?>
