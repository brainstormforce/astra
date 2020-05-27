<?php
/**
 * AMP Compatibility.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Astra BB Ultimate Addon Compatibility
 */
if ( ! class_exists( 'Astra_AMP' ) ) :

	/**
	 * Class Astra_AMP
	 */
	class Astra_AMP {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'wp', array( $this, 'astra_amp_init' ) );
		}

		/**
		 * Init Astra Amp Compatibility.
		 * This adds required actions and filters only if AMP endpoinnt is detected.
		 *
		 * @since 1.7.0
		 * @return void
		 */
		public function astra_amp_init() {

			// bail if AMP endpoint is not detected.
			if ( ! astra_is_amp_endpoint() ) {
				return;
			}

			add_filter( 'astra_nav_toggle_data_attrs', array( $this, 'add_nav_toggle_attrs' ) );
			add_filter( 'astra_search_slide_toggle_data_attrs', array( $this, 'add_search_slide_toggle_attrs' ) );
			add_filter( 'astra_search_field_toggle_data_attrs', array( $this, 'add_search_field_toggle_attrs' ) );
			add_action( 'wp_footer', array( $this, 'render_amp_states' ) );
			add_filter( 'astra_attr_ast-main-header-bar-alignment', array( $this, 'nav_menu_wrapper' ) );
			add_filter( 'astra_attr_ast-menu-toggle', array( $this, 'menu_toggle_button' ), 20, 3 );
			add_filter( 'astra_theme_dynamic_css', array( $this, 'dynamic_css' ) );
			add_filter( 'astra_toggle_button_markup', array( $this, 'toggle_button_markup' ), 20, 2 );
			add_filter( 'astra_schema_body', array( $this, 'body_id' ) );
		}

		/**
		 * Add ID to body to toggleClasses on AMP actions.
		 *
		 * @since 1.7.0
		 * @param String $schema markup returned from theme.
		 * @return String
		 */
		public function body_id( $schema ) {
			return $schema . 'id="astra-body"';
		}

		/**
		 * Dynamic CSS used for AMP pages.
		 * This should be changed to main CSS in next versions, replacing JavaScript based interactions with pure CSS alternatives.
		 *
		 * @since 1.7.0
		 * @param String $compiled_css Dynamic CSS received to  be enqueued on page.
		 *
		 * @return String Updated dynamic CSS with AMP specific changes.
		 */
		public function dynamic_css( $compiled_css ) {
			$css = array(
				'.ast-mobile-menu-buttons'                 => array(
					'text-align'              => 'right',
					'-js-display'             => 'flex',
					'display'                 => '-webkit-box',
					'display'                 => '-webkit-flex',
					'display'                 => '-moz-box',
					'display'                 => '-ms-flexbox',
					'display'                 => 'flex',
					'-webkit-box-pack'        => 'end',
					'-webkit-justify-content' => 'flex-end',
					'-moz-box-pack'           => 'end',
					'-ms-flex-pack'           => 'end',
					'justify-content'         => 'flex-end',
					'-webkit-align-self'      => 'center',
					'-ms-flex-item-align'     => 'center',
					'align-self'              => 'center',
				),

				'.site-header .main-header-bar-wrap .site-branding' => array(
					'display'             => '-webkit-box',
					'display'             => '-webkit-flex',
					'display'             => '-moz-box',
					'display'             => '-ms-flexbox',
					'display'             => 'flex',
					'-webkit-box-flex'    => '1',
					'-webkit-flex'        => '1',
					'-moz-box-flex'       => '1',
					'-ms-flex'            => '1',
					'flex'                => '1',
					'-webkit-align-self'  => 'center',
					'-ms-flex-item-align' => 'center',
					'align-self'          => 'center',
				),

				'.ast-main-header-bar-alignment.toggle-on .main-header-bar-navigation' => array(
					'display' => 'block',
				),

				'.main-navigation'                         => array(
					'display' => 'block',
					'width'   => '100%',
				),

				'.main-header-menu > .menu-item > a'       => array(
					'padding'             => '0 20px',
					'display'             => 'inline-block',
					'width'               => '100%',
					'border-bottom-width' => '1px',
					'border-style'        => 'solid',
					'border-color'        => '#eaeaea',
				),

				'.ast-main-header-bar-alignment.toggle-on' => array(
					'display'                   => 'block',
					'width'                     => '100%',
					'-webkit-box-flex'          => '1',
					'-webkit-flex'              => 'auto',
					'-moz-box-flex'             => '1',
					'-ms-flex'                  => 'auto',
					'flex'                      => 'auto',
					'-webkit-box-ordinal-group' => '5',
					'-webkit-order'             => '4',
					'-moz-box-ordinal-group'    => '5',
					'-ms-flex-order'            => '4',
					'order'                     => '4',
				),

				'.main-header-menu .menu-item'             => array(
					'width'      => '100%',
					'text-align' => 'left',
					'border-top' => '0',
				),

				'.header-main-layout-1 .main-navigation'   => array(
					'padding' => '0',
				),

				'.main-header-bar-navigation'              => array(
					'width'  => '-webkit-calc( 100% + 40px)',
					'width'  => 'calc( 100% + 40px)',
					'margin' => '0 -20px',
				),

				'.main-header-bar .main-header-bar-navigation .main-header-menu' => array(
					'border-top-width' => '1px',
					'border-style'     => 'solid',
					'border-color'     => '#eaeaea',
				),

				'.main-header-bar .main-header-bar-navigation .page_item_has_children > .ast-menu-toggle, .main-header-bar .main-header-bar-navigation .menu-item-has-children > .ast-menu-toggle' => array(
					'display'                 => 'inline-block',
					'position'                => 'absolute',
					'font-size'               => 'inherit',
					'top'                     => '-1px',
					'right'                   => '20px',
					'cursor'                  => 'pointer',
					'-webkit-font-smoothing'  => 'antialiased',
					'-moz-osx-font-smoothing' => 'grayscale',
					'padding'                 => '0 0.907em',
					'font-weight'             => 'normal',
					'line-height'             => 'inherit',
					'-webkit-transition'      => 'all .2s',
					'transition'              => 'all .2s',
				),

				'.main-header-bar-navigation .menu-item-has-children > a:after' => array(
					'content' => 'none',
				),

				'.main-header-bar .main-header-bar-navigation .page_item_has_children > .ast-menu-toggle::before, .main-header-bar .main-header-bar-navigation .menu-item-has-children > .ast-menu-toggle::before' => array(
					'font-weight'     => 'bold',
					'content'         => '"\e900"',
					'font-family'     => 'Astra',
					'text-decoration' => 'inherit',
					'display'         => 'inline-block',
				),
				'.ast-button-wrap .menu-toggle.toggled .menu-toggle-icon:before' => array(
					'content' => "\e5cd",
				),

			);

			$parse_css = $compiled_css . astra_parse_css( $css, '', astra_header_break_point() );

			// Move all header-break-point css from class based css to media query based CSS.
			$astra_break_point_navigation = array(
				'.ast-amp .ast-mobile-menu-buttons'        => array(
					'text-align'              => 'right',
					'-js-display'             => 'flex',
					'display'                 => '-webkit-box',
					'display'                 => '-webkit-flex',
					'display'                 => '-moz-box',
					'display'                 => '-ms-flexbox',
					'display'                 => 'flex',
					'-webkit-box-pack'        => 'end',
					'-webkit-justify-content' => 'flex-end',
					'-moz-box-pack'           => 'end',
					'-ms-flex-pack'           => 'end',
					'justify-content'         => 'flex-end',
					'-webkit-align-self'      => 'center',
					'-ms-flex-item-align'     => 'center',
					'align-self'              => 'center',
				),
				'.ast-theme.ast-header-custom-item-outside .main-header-bar .ast-search-icon' => array(
					'margin-right' => '1em',
				),
				'.ast-theme.ast-header-custom-item-inside .main-header-bar .main-header-bar-navigation .ast-search-icon' => array(
					'display' => 'none',
				),
				'.ast-theme.ast-header-custom-item-inside .main-header-bar .ast-search-menu-icon .search-field, .ast-theme.ast-header-custom-item-inside .main-header-bar .ast-search-menu-icon.ast-inline-search .search-field' => array(
					'width'         => '100%',
					'padding-right' => '5.5em',
				),
				'.ast-theme.ast-header-custom-item-inside .main-header-bar .ast-search-menu-icon .search-submit' => array(
					'display'       => 'block',
					'position'      => 'absolute',
					'height'        => '100%',
					'top'           => '0',
					'right'         => '0',
					'padding'       => '0 1em',
					'border-radius' => '0',
				),
				'.ast-theme.ast-header-custom-item-inside .main-header-bar .ast-search-menu-icon .search-form' => array(
					'padding'  => '0',
					'display'  => 'block',
					'overflow' => 'hidden',
				),
				'.ast-amp .entry-content .alignwide'       => array(
					'margin-left'  => 'auto',
					'margin-right' => 'auto',
				),
				'.ast-amp .main-navigation'                => array(
					'padding-left' => '0',
				),
				'.ast-amp .main-navigation ul li a, .ast-amp .main-navigation ul .button-custom-menu-item a' => array(
					'padding'             => '0 20px',
					'display'             => 'inline-block',
					'width'               => '100%',
					'border-bottom-width' => '1px',
					'border-style'        => 'solid',
					'border-color'        => '#eaeaea',
				),
				'.ast-amp .main-navigation ul.children li a, .ast-amp .main-navigation ul.sub-menu li a' => array(
					'padding-left' => '30px',
				),
				'.ast-amp .main-navigation ul.children li a:before, .ast-amp .main-navigation ul.sub-menu li a:before' => array(
					'content'         => '"\e900"',
					'font-family'     => '"Astra"',
					'font-size'       => '0.65em',
					'text-decoration' => 'inherit',
					'display'         => 'inline-block',
					'transform'       => 'translate(0, -2px) rotateZ(270deg)',
					'margin-right'    => '5px',
				),
				'.ast-amp .main-navigation ul.children li li a, .ast-amp .main-navigation ul.sub-menu li li a' => array(
					'padding-left' => '40px',
				),
				'.ast-amp .main-navigation ul.children li li li a, .ast-amp .main-navigation ul.sub-menu li li li a' => array(),
				'.ast-amp .main-navigation ul.children li li li li a, .ast-amp .main-navigation ul.sub-menu li li li li a' => array(
					'padding-left' => '60px',
				),
				'.ast-amp .ast-header-custom-item'         => array(
					'background-color' => '#f9f9f9',
				),
				'.ast-amp .main-header-menu'               => array(
					'background-color' => '#f9f9f9',
				),
				'.ast-amp .main-header-menu ul'            => array(
					'background-color' => '#f9f9f9',
					'position'         => 'static',
					'opacity'          => '1',
					'visibility'       => 'visible',
					'border'           => '0',
					'width'            => 'auto',
				),
				'.ast-amp .main-header-menu ul li.ast-left-align-sub-menu:hover > ul, .ast-amp .main-header-menu ul li.ast-left-align-sub-menu.focus > ul' => array(
					'left' => '0',
				),
				'.ast-amp .main-header-menu li.ast-sub-menu-goes-outside:hover > ul, .ast-amp .main-header-menu li.ast-sub-menu-goes-outside.focus > ul' => array(
					'left' => '0',
				),
				'.ast-amp .submenu-with-border .sub-menu'  => array(
					'border' => '0',
				),
				'.ast-amp .user-select'                    => array(
					'clear' => 'both',
				),
				'.ast-amp .ast-mobile-menu-buttons'        => array(
					'display'             => 'block',
					'-webkit-align-self'  => 'center',
					'-ms-flex-item-align' => 'center',
					'align-self'          => 'center',
				),
				'.ast-amp .main-header-bar-navigation'     => array(
					'-webkit-box-flex' => '1',
					'-webkit-flex'     => 'auto',
					'-moz-box-flex'    => '1',
					'-ms-flex'         => 'auto',
					'flex'             => 'auto',
				),
				'.ast-amp .ast-main-header-bar-alignment'  => array(
					'display'                   => 'block',
					'width'                     => '100%',
					'-webkit-box-flex'          => '1',
					'-webkit-flex'              => 'auto',
					'-moz-box-flex'             => '1',
					'-ms-flex'                  => 'auto',
					'flex'                      => 'auto',
					'-webkit-box-ordinal-group' => '5',
					'-webkit-order'             => '4',
					'-moz-box-ordinal-group'    => '5',
					'-ms-flex-order'            => '4',
					'order'                     => '4',
				),
				'.ast-amp .ast-mobile-menu-buttons'        => array(
					'text-align'              => 'right',
					'display'                 => '-webkit-box',
					'display'                 => '-webkit-flex',
					'display'                 => '-moz-box',
					'display'                 => '-ms-flexbox',
					'display'                 => 'flex',
					'-webkit-box-pack'        => 'end',
					'-webkit-justify-content' => 'flex-end',
					'-moz-box-pack'           => 'end',
					'-ms-flex-pack'           => 'end',
					'justify-content'         => 'flex-end',
				),
				'.ast-amp .ast-mobile-header-stack .site-description' => array(
					'text-align' => 'center',
				),
				'.ast-amp .ast-mobile-header-stack.ast-logo-title-inline .site-description' => array(
					'text-align' => 'left',
				),
				'.ast-theme.ast-header-custom-item-outside .ast-primary-menu-disabled .ast-mobile-menu-buttons' => array(
					'display' => 'none',
				),
				'.ast-amp .ast-hide-custom-menu-mobile .ast-masthead-custom-menu-items' => array(
					'display' => 'none',
				),
				'.ast-amp .ast-mobile-header-inline .site-branding' => array(
					'text-align'     => 'left',
					'padding-bottom' => '0',
				),
				'.ast-amp .ast-mobile-header-inline.header-main-layout-3 .site-branding' => array(
					'text-align' => 'right',
				),
				'.ast-amp .site-header .main-header-bar-wrap .site-branding' => array(
					'-js-display'         => 'flex',
					'display'             => '-webkit-box',
					'display'             => '-webkit-flex',
					'display'             => '-moz-box',
					'display'             => '-ms-flexbox',
					'display'             => 'flex',
					'-webkit-box-flex'    => '1',
					'-webkit-flex'        => '1',
					'-moz-box-flex'       => '1',
					'-ms-flex'            => '1',
					'flex'                => '1',
					'-webkit-align-self'  => 'center',
					'-ms-flex-item-align' => 'center',
					'align-self'          => 'center',
				),
				'.ast-amp ul li.ast-masthead-custom-menu-items a' => array(
					'padding' => '0',
					'width'   => 'auto',
					'display' => 'initial',
				),
				'.ast-amp li.ast-masthead-custom-menu-items' => array(
					'padding-left'  => '20px',
					'padding-right' => '20px',
					'margin-bottom' => '1em',
					'margin-top'    => '1em',
				),
				'.ast-amp .ast-site-identity'              => array(
					'width' => '100%',
				),
				'.ast-amp .main-header-bar-navigation .page_item_has_children > a:after, .ast-amp .main-header-bar-navigation .menu-item-has-children > a:after' => array(
					'display' => 'none',
				),
				'.ast-amp .main-header-bar'                => array(
					'display'     => 'block',
					'line-height' => '3',
				),
				'.ast-main-header-bar-alignment .main-header-bar-navigation' => array(
					'line-height' => '3',
					'display'     => 'none',
				),
				'.ast-amp .main-header-bar .toggled-on .main-header-bar-navigation' => array(
					'line-height' => '3',
					'display'     => 'none',
				),
				'.ast-amp .main-header-bar .main-header-bar-navigation .children, .ast-amp .main-header-bar .main-header-bar-navigation .sub-menu' => array(
					'line-height' => '3',
				),
				'.ast-amp .main-header-bar .main-header-bar-navigation .page_item_has_children .sub-menu, .ast-amp .main-header-bar .main-header-bar-navigation .menu-item-has-children .sub-menu' => array(
					'display' => 'none',
				),
				'.ast-amp .main-header-bar .main-header-bar-navigation .menu-item-has-children .dropdown-open+ul.sub-menu' => array(
					'display' => 'block',
				),
				'.ast-amp .main-header-bar .main-header-bar-navigation .page_item_has_children > .ast-menu-toggle, .ast-amp .main-header-bar .main-header-bar-navigation .menu-item-has-children > .ast-menu-toggle' => array(
					'display'                => 'inline-block',
					'position'               => 'absolute',
					'font-size'              => 'inherit',
					'top'                    => '-1px',
					'right'                  => '20px',
					'cursor'                 => 'pointer',
					'webkit-font-smoothing'  => 'antialiased',
					'moz-osx-font-smoothing' => 'grayscale',
					'padding'                => '0 0.907em',
					'font-weight'            => 'normal',
					'line-height'            => 'inherit',
					'transition'             => 'all 0.2s',
				),
				'.ast-amp .main-header-bar .main-header-bar-navigation .page_item_has_children > .ast-menu-toggle::before, .ast-amp .main-header-bar .main-header-bar-navigation .menu-item-has-children > .ast-menu-toggle::before' => array(
					'font-weight'     => 'bold',
					'content'         => '"\e900"',
					'font-family'     => '"Astra"',
					'text-decoration' => 'inherit',
					'display'         => 'inline-block',
				),
				'.ast-amp .main-header-bar .main-header-bar-navigation .ast-submenu-expanded > .ast-menu-toggle::before' => array(
					'-webkit-transform' => 'rotateX(180deg)',
					'transform'         => 'rotateX(180deg)',
				),
				'.ast-amp .main-header-bar .main-header-bar-navigation .main-header-menu' => array(
					'border-top-width' => '1px',
					'border-style'     => 'solid',
					'border-color'     => '#eaeaea',
				),
				'.ast-theme.ast-header-custom-item-inside .ast-search-menu-icon' => array(
					'position'          => 'relative',
					'display'           => 'block',
					'right'             => 'auto',
					'visibility'        => 'visible',
					'opacity'           => '1',
					'-webkit-transform' => 'none',
					'-ms-transform'     => 'none',
					'transform'         => 'none',
				),
				'.ast-amp .main-navigation'                => array(
					'display' => 'block',
					'width'   => '100%',
				),
				'.ast-amp .main-navigation ul > li:first-child' => array(
					'border-top' => '0',
				),
				'.ast-amp .main-navigation ul ul'          => array(
					'left'  => 'auto',
					'right' => 'auto',
				),
				'.ast-amp .main-navigation li'             => array(
					'width' => '100%',
				),
				'.ast-amp .main-navigation .widget'        => array(
					'margin-bottom' => '1em',
				),
				'.ast-amp .main-navigation .widget li'     => array(
					'width' => 'auto',
				),
				'.ast-amp .main-navigation .widget:last-child' => array(
					'margin-bottom' => '0',
				),
				'.ast-amp .main-header-bar-navigation'     => array(
					'width'  => '-webkit-calc( 100% + 40px)',
					'width'  => 'calc(100% + 40px )',
					'margin' => '0 -20px',
				),
				'.ast-amp .main-header-menu ul ul'         => array(
					'top' => '0',
				),
				'.ast-amp .ast-has-mobile-header-logo .custom-logo-link, .ast-amp .ast-has-mobile-header-logo .astra-logo-svg' => array(
					'display' => 'none',
				),
				'.ast-amp .ast-has-mobile-header-logo .custom-mobile-logo-link' => array(
					'display' => 'inline-block',
				),
				'.ast-theme.ast-mobile-inherit-site-logo .ast-has-mobile-header-logo .custom-logo-link, .ast-theme.ast-mobile-inherit-site-logo .ast-has-mobile-header-logo .astra-logo-svg' => array(
					'display' => 'block',
				),
				'.ast-theme.ast-header-custom-item-outside .ast-mobile-menu-buttons' => array(
					'-webkit-box-ordinal-group' => '3',
					'-webkit-order'             => '2',
					'-moz-box-ordinal-group'    => '3',
					'-ms-flex-order'            => '2',
					'order'                     => '2',
				),
				'.ast-theme.ast-header-custom-item-outside .main-header-bar-navigation' => array(
					'-webkit-box-ordinal-group' => '4',
					'-webkit-order'             => '3',
					'-moz-box-ordinal-group'    => '4',
					'-ms-flex-order'            => '3',
					'order'                     => '3',
				),
				'.ast-theme.ast-header-custom-item-outside .ast-masthead-custom-menu-items' => array(
					'-webkit-box-ordinal-group' => '2',
					'-webkit-order'             => '1',
					'-moz-box-ordinal-group'    => '2',
					'-ms-flex-order'            => '1',
					'order'                     => '1',
				),
				'.ast-theme.ast-header-custom-item-outside .header-main-layout-2 .ast-masthead-custom-menu-items' => array(
					'text-align' => 'center',
				),
				'.ast-theme.ast-header-custom-item-outside .ast-mobile-header-inline .site-branding, .ast-theme.ast-header-custom-item-outside .ast-mobile-header-inline .ast-mobile-menu-buttons' => array(
					'-js-display' => 'flex',
					'display'     => '-webkit-box',
					'display'     => '-webkit-flex',
					'display'     => '-moz-box',
					'display'     => '-ms-flexbox',
					'display'     => 'flex',
				),
				'.ast-theme.ast-header-custom-item-outside.ast-header-custom-item-outside .header-main-layout-2 .ast-mobile-menu-buttons' => array(
					'padding-bottom' => '0',
				),
				'.ast-theme.ast-header-custom-item-outside .ast-mobile-header-inline .ast-site-identity' => array(
					'width' => '100%',
				),
				'.ast-theme.ast-header-custom-item-outside .ast-mobile-header-inline.header-main-layout-3 .ast-site-identity' => array(
					'width' => 'auto',
				),
				'.ast-theme.ast-header-custom-item-outside .ast-mobile-header-inline.header-main-layout-2 .site-branding' => array(
					'-webkit-box-flex' => '1',
					'-webkit-flex'     => '1 1 auto',
					'-moz-box-flex'    => '1',
					'-ms-flex'         => '1 1 auto',
					'flex'             => '1 1 auto',
				),
				'.ast-theme.ast-header-custom-item-outside .ast-mobile-header-inline .site-branding' => array(
					'text-align' => 'left',
				),
				'.ast-theme.ast-header-custom-item-outside .ast-mobile-header-inline .site-title' => array(
					'-webkit-box-pack'        => 'left',
					'-webkit-justify-content' => 'left',
					'-moz-box-pack'           => 'left',
					'-ms-flex-pack'           => 'left',
					'justify-content'         => 'left',
				),
				'.ast-theme.ast-header-custom-item-outside .header-main-layout-2 .ast-mobile-menu-buttons' => array(
					'padding-bottom' => '1em',
				),
				'.ast-amp .ast-mobile-header-stack .main-header-container, .ast-amp .ast-mobile-header-inline .main-header-container' => array(
					'-js-display' => 'flex',
					'display'     => '-webkit-box',
					'display'     => '-webkit-flex',
					'display'     => '-moz-box',
					'display'     => '-ms-flexbox',
					'display'     => 'flex',
				),
				'.ast-amp .header-main-layout-1 .site-branding' => array(
					'padding-right' => '1em',
				),
				'.ast-amp .header-main-layout-1 .main-header-bar-navigation' => array(
					'text-align' => 'left',
				),
				'.ast-amp .header-main-layout-1 .main-navigation' => array(
					'padding-left' => '0',
				),
				'.ast-amp .ast-mobile-header-stack .ast-masthead-custom-menu-items' => array(
					'-webkit-box-flex' => '1',
					'-webkit-flex'     => '1 1 100%',
					'-moz-box-flex'    => '1',
					'-ms-flex'         => '1 1 100%',
					'flex'             => '1 1 100%',
				),
				'.ast-amp .ast-mobile-header-stack .site-branding' => array(
					'padding-left'     => '0',
					'padding-right'    => '0',
					'padding-bottom'   => '1em',
					'-webkit-box-flex' => '1',
					'-webkit-flex'     => '1 1 100%',
					'-moz-box-flex'    => '1',
					'-ms-flex'         => '1 1 100%',
					'flex'             => '1 1 100%',
				),
				'.ast-amp .ast-mobile-header-stack .ast-masthead-custom-menu-items, .ast-amp .ast-mobile-header-stack .site-branding, .ast-amp .ast-mobile-header-stack .site-title, .ast-amp .ast-mobile-header-stack .ast-site-identity' => array(
					'-webkit-box-pack'        => 'center',
					'-webkit-justify-content' => 'center',
					'-moz-box-pack'           => 'center',
					'-ms-flex-pack'           => 'center',
					'justify-content'         => 'center',
					'text-align'              => 'center',
				),
				'.ast-amp .ast-mobile-header-stack.ast-logo-title-inline .site-title' => array(
					'text-align' => 'left',
				),
				'.ast-amp .ast-mobile-header-stack .ast-mobile-menu-buttons' => array(
					'-webkit-box-flex'        => '1',
					'-webkit-flex'            => '1 1 100%',
					'-moz-box-flex'           => '1',
					'-ms-flex'                => '1 1 100%',
					'flex'                    => '1 1 100%',
					'text-align'              => 'center',
					'-webkit-box-pack'        => 'center',
					'-webkit-justify-content' => 'center',
					'-moz-box-pack'           => 'center',
					'-ms-flex-pack'           => 'center',
					'justify-content'         => 'center',
				),
				'.ast-amp .ast-mobile-header-stack.header-main-layout-3 .main-header-container' => array(
					'flex-direction' => 'initial',
				),
				'.ast-amp .header-main-layout-2 .ast-mobile-menu-buttons' => array(
					'-js-display'             => 'flex',
					'display'                 => '-webkit-box',
					'display'                 => '-webkit-flex',
					'display'                 => '-moz-box',
					'display'                 => '-ms-flexbox',
					'display'                 => 'flex',
					'-webkit-box-pack'        => 'center',
					'-webkit-justify-content' => 'center',
					'-moz-box-pack'           => 'center',
					'-ms-flex-pack'           => 'center',
					'justify-content'         => 'center',
				),
				'.ast-amp .header-main-layout-2 .main-header-bar-navigation, .ast-amp .header-main-layout-2 .widget' => array(
					'text-align' => 'left',
				),
				'.ast-theme.ast-header-custom-item-outside .header-main-layout-3 .main-header-bar .ast-search-icon' => array(
					'margin-right' => 'auto',
					'margin-left'  => '1em',
				),
				'.ast-amp .header-main-layout-3 .main-header-bar .ast-search-menu-icon.slide-search .search-form' => array(
					'right' => 'auto',
					'left'  => '0',
				),
				'.ast-amp .header-main-layout-3.ast-mobile-header-inline .ast-mobile-menu-buttons' => array(
					'-webkit-box-pack'        => 'start',
					'-webkit-justify-content' => 'flex-start',
					'-moz-box-pack'           => 'start',
					'-ms-flex-pack'           => 'start',
					'justify-content'         => 'flex-start',
				),
				'.ast-amp .header-main-layout-3 li .ast-search-menu-icon' => array(
					'left' => '0',
				),
				'.ast-amp .header-main-layout-3 .site-branding' => array(
					'padding-left'            => '1em',
					'-webkit-box-pack'        => 'end',
					'-webkit-justify-content' => 'flex-end',
					'-moz-box-pack'           => 'end',
					'-ms-flex-pack'           => 'end',
					'justify-content'         => 'flex-end',
				),
				'.ast-amp .header-main-layout-3 .main-navigation' => array(
					'padding-right' => '0',
				),
				'.ast-amp .header-main-layout-1 .site-branding' => array(
					'padding-right' => '1em',
				),
				'.ast-amp .header-main-layout-1 .main-header-bar-navigation' => array(
					'text-align' => 'left',
				),
				'.ast-amp .header-main-layout-1 .main-navigation' => array(
					'padding-left' => '0',
				),
				'.ast-amp .ast-mobile-header-stack .ast-masthead-custom-menu-items' => array(
					'-webkit-box-flex' => '1',
					'-webkit-flex'     => '1 1 100%',
					'-moz-box-flex'    => '1',
					'-ms-flex'         => '1 1 100%',
					'flex'             => '1 1 100%',
				),
				'.ast-amp .ast-mobile-header-stack .site-branding' => array(
					'padding-left'     => '0',
					'padding-right'    => '0',
					'padding-bottom'   => '1em',
					'-webkit-box-flex' => '1',
					'-webkit-flex'     => '1 1 100%',
					'-moz-box-flex'    => '1',
					'-ms-flex'         => '1 1 100%',
					'flex'             => '1 1 100%',
				),
				'.ast-amp .ast-mobile-header-stack .ast-masthead-custom-menu-items, .ast-amp .ast-mobile-header-stack .site-branding, .ast-amp .ast-mobile-header-stack .site-title, .ast-amp .ast-mobile-header-stack .ast-site-identity' => array(
					'-webkit-box-pack'        => 'center',
					'-webkit-justify-content' => 'center',
					'-moz-box-pack'           => 'center',
					'-ms-flex-pack'           => 'center',
					'justify-content'         => 'center',
					'text-align'              => 'center',
				),
				'.ast-amp .ast-mobile-header-stack.ast-logo-title-inline .site-title' => array(
					'text-align' => 'left',
				),
				'.ast-amp .ast-mobile-header-stack .ast-mobile-menu-buttons' => array(
					'flex'                    => '1 1 100%',
					'text-align'              => 'center',
					'-webkit-box-pack'        => 'center',
					'-webkit-justify-content' => 'center',
					'-moz-box-pack'           => 'center',
					'-ms-flex-pack'           => 'center',
					'justify-content'         => 'center',
				),
				'.ast-amp .ast-mobile-header-stack.header-main-layout-3 .main-header-container' => array(
					'flex-direction' => 'initial',
				),
				'.ast-amp .header-main-layout-2 .ast-mobile-menu-buttons' => array(
					'display'                 => '-webkit-box',
					'display'                 => '-webkit-flex',
					'display'                 => '-moz-box',
					'display'                 => '-ms-flexbox',
					'display'                 => 'flex',
					'-webkit-box-pack'        => 'center',
					'-webkit-justify-content' => 'center',
					'-moz-box-pack'           => 'center',
					'-ms-flex-pack'           => 'center',
					'justify-content'         => 'center',
				),
				'.ast-amp .header-main-layout-2 .main-header-bar-navigation, .ast-amp .header-main-layout-2 .widget' => array(
					'text-align' => 'left',
				),
				'.ast-theme.ast-header-custom-item-outside .header-main-layout-3 .main-header-bar .ast-search-icon' => array(
					'margin-right' => 'auto',
					'margin-left'  => '1em',
				),
				'.ast-amp .header-main-layout-3 .main-header-bar .ast-search-menu-icon.slide-search .search-form' => array(
					'right' => 'auto',
					'left'  => '0',
				),
				'.ast-amp .header-main-layout-3.ast-mobile-header-inline .ast-mobile-menu-buttons' => array(
					'-webkit-box-pack'        => 'start',
					'-webkit-justify-content' => 'flex-start',
					'-moz-box-pack'           => 'start',
					'-ms-flex-pack'           => 'start',
					'justify-content'         => 'flex-start',
				),
				'.ast-amp .header-main-layout-3 li .ast-search-menu-icon' => array(
					'left' => '0',
				),
				'.ast-amp .header-main-layout-3 .site-branding' => array(
					'padding-left'            => '1em',
					'-webkit-box-pack'        => 'end',
					'-webkit-justify-content' => 'flex-end',
					'-moz-box-pack'           => 'end',
					'-ms-flex-pack'           => 'end',
					'justify-content'         => 'flex-end',
				),
				'.ast-amp .header-main-layout-3 .main-navigation' => array(
					'padding-right' => '0',
				),
				'.ast-amp .ast-header-widget-area .widget' => array(
					'margin'  => '0.5em 0',
					'display' => 'block',
				),
				'.ast-amp .main-header-bar'                => array(
					'border' => '0',
				),
				'.ast-amp .nav-fallback-text'              => array(
					'float' => 'none',
				),
				'.ast-amp .main-header-bar'                => array(
					'border-bottom-color' => '#eaeaea',
					'border-bottom-style' => 'solid',
				),
				'.ast-amp .ast-header-custom-item'         => array(
					'border-top' => '1px solid #eaeaea',
				),
				'.ast-amp .ast-header-custom-item .ast-masthead-custom-menu-items' => array(
					'padding-left'  => '20px',
					'padding-right' => '20px',
					'margin-bottom' => '1em',
					'margin-top'    => '1em',
				),
				'.ast-amp .ast-header-custom-item .widget:last-child' => array(
					'margin-bottom' => '1em',
				),
				'.ast-header-custom-item-inside.ast-amp .button-custom-menu-item .menu-link' => array(
					'display' => 'block',
				),
				'.ast-header-custom-item-inside.ast-amp .button-custom-menu-item' => array(
					'padding-left'  => '0',
					'padding-right' => '0',
					'margin-top'    => '0',
					'margin-bottom' => '0',
				),
				'.ast-header-custom-item-inside.ast-amp .button-custom-menu-item .ast-custom-button-link' => array(
					'display' => 'none',
				),
				'.ast-header-custom-item-inside.ast-amp .button-custom-menu-item .menu-link' => array(
					'display' => 'block',
				),
				'.ast-amp .woocommerce-custom-menu-item .ast-cart-menu-wrap' => array(
					'width'          => '2em',
					'height'         => '2em',
					'font-size'      => '1.4em',
					'line-height'    => '2',
					'vertical-align' => 'middle',
					'text-align'     => 'right',
				),
				'.ast-amp .main-header-menu .woocommerce-custom-menu-item .ast-cart-menu-wrap' => array(
					'height'      => '3em',
					'line-height' => '3',
					'text-align'  => 'left',
				),
				'.ast-amp #ast-site-header-cart .widget_shopping_cart' => array(
					'display' => 'none',
				),
				'.ast-theme.ast-woocommerce-cart-menu .ast-site-header-cart' => array(
					'order'       => 'initial',
					'line-height' => '3',
					'padding'     => '0 1em 1em 0',
				),
				'.ast-theme.ast-woocommerce-cart-menu .header-main-layout-3 .ast-site-header-cart' => array(
					'padding' => '0 0 1em 1em',
				),
				'.ast-theme.ast-woocommerce-cart-menu.ast-header-custom-item-outside .ast-site-header-cart' => array(
					'padding' => '0',
				),
				'.ast-amp .ast-masthead-custom-menu-items.woocommerce-custom-menu-item' => array(
					'margin-bottom' => '0',
					'margin-top'    => '0',
				),
				'.ast-amp .ast-masthead-custom-menu-items.woocommerce-custom-menu-item .ast-site-header-cart' => array(
					'padding' => '0',
				),
				'.ast-amp .ast-masthead-custom-menu-items.woocommerce-custom-menu-item .ast-site-header-cart a' => array(
					'border'  => 'none',
					'display' => 'inline-block',
				),
				'.ast-amp .ast-edd-site-header-cart .widget_edd_cart_widget, .ast-amp .ast-edd-site-header-cart .ast-edd-header-cart-info-wrap' => array(
					'display' => 'none',
				),
				'.ast-amp div.ast-masthead-custom-menu-items.edd-custom-menu-item' => array(
					'padding' => '0',
				),
				'.ast-theme.ast-header-custom-item-inside .main-header-bar .ast-search-menu-icon .search-form' => array(
					'visibility' => 'visible',
					'opacity'    => '1',
					'position'   => 'relative',
					'right'      => 'auto',
					'top'        => 'auto',
					'transform'  => 'none',
				),
				'.ast-theme.ast-header-custom-item-outside .ast-mobile-header-stack .main-header-bar .ast-search-icon' => array(
					'margin' => '0',
				),
				'.ast-amp .main-header-bar .ast-search-menu-icon.slide-search .search-form' => array(
					'right' => '0',
				),
				'.ast-amp .ast-mobile-header-stack .main-header-bar .ast-search-menu-icon.slide-search .search-form' => array(
					'right' => '-1em',
				),
				'.ast-safari-browser-less-than-11.ast-woocommerce-cart-menu.ast-header-break-point .header-main-layout-2 .main-header-container' => array(
					'display' => 'flex',
				),
				'.ast-amp .ast-mobile-header-stack .site-branding, .ast-amp .ast-mobile-header-stack .ast-mobile-menu-buttons' => array(
					'-webkit-box-pack'        => 'center',
					'-webkit-justify-content' => 'center',
					'-moz-box-pack'           => 'center',
					'-ms-flex-pack'           => 'center',
					'justify-content'         => 'center',
					'text-align'              => 'center',
					'padding-bottom'          => '0',
				),
				'.ast-amp .main-header-menu .sub-menu'     => array(
					'box-shadow' => 'none',
				),
				'.ast-amp .submenu-with-border .sub-menu a' => array(
					'border-width' => '1px',
				),
				'.ast-amp .submenu-with-border .sub-menu > li:last-child > a' => array(
					'border-width' => '1px',
				),
			);
			$parse_css                   .= astra_parse_css( $astra_break_point_navigation, '', astra_header_break_point() );

			// Tablet CSS.
			$astra_medium_break_point_navigation = array(
				'.ast-amp .footer-sml-layout-2 .ast-small-footer-section-2' => array(
					'margin-top' => '1em',
				),
			);

			$parse_css .= astra_parse_css( $astra_medium_break_point_navigation, astra_get_tablet_breakpoint() );

			// Mobile CSS.
			$astra_small_break_point_navigation = array(
				'.ast-theme.ast-woocommerce-cart-menu .header-main-layout-1.ast-mobile-header-stack.ast-no-menu-items .ast-site-header-cart, .ast-theme.ast-woocommerce-cart-menu .header-main-layout-3.ast-mobile-header-stack.ast-no-menu-items .ast-site-header-cart' => array(
					'padding-right' => '0',
					'padding-left'  => '0',
				),
				'.ast-theme.ast-woocommerce-cart-menu .header-main-layout-1.ast-mobile-header-stack .main-header-bar, .ast-theme.ast-woocommerce-cart-menu .header-main-layout-3.ast-mobile-header-stack .main-header-bar' => array(
					'text-align' => 'center',
				),
				'.ast-theme.ast-woocommerce-cart-menu .header-main-layout-1.ast-mobile-header-stack .ast-site-header-cart, .ast-theme.ast-woocommerce-cart-menu .header-main-layout-3.ast-mobile-header-stack .ast-site-header-cart' => array(
					'display' => 'inline-block',
				),
				'.ast-theme.ast-woocommerce-cart-menu .header-main-layout-1.ast-mobile-header-stack .ast-mobile-menu-buttons, .ast-theme.ast-woocommerce-cart-menu .header-main-layout-3.ast-mobile-header-stack .ast-mobile-menu-buttons' => array(
					'display' => 'inline-block',
				),
				'.ast-theme.ast-woocommerce-cart-menu .header-main-layout-2.ast-mobile-header-inline .site-branding' => array(
					'flex' => 'auto',
				),
				'.ast-theme.ast-woocommerce-cart-menu .header-main-layout-3.ast-mobile-header-stack .site-branding' => array(
					'flex' => '0 0 100%',
				),
				'.ast-theme.ast-woocommerce-cart-menu .header-main-layout-3.ast-mobile-header-stack .main-header-container' => array(
					'display'                 => '-webkit-box',
					'display'                 => '-webkit-flex',
					'display'                 => '-moz-box',
					'display'                 => '-ms-flexbox',
					'display'                 => 'flex',
					'-webkit-box-pack'        => 'center',
					'-webkit-justify-content' => 'center',
					'-moz-box-pack'           => 'center',
					'-ms-flex-pack'           => 'center',
					'justify-content'         => 'center',
				),
				'.ast-amp .ast-mobile-header-stack .ast-mobile-menu-buttons' => array(
					'width' => '100%',
				),
				'.ast-amp .ast-mobile-header-stack .site-branding, .ast-amp .ast-mobile-header-stack .ast-mobile-menu-buttons' => array(
					'-webkit-box-pack'        => 'center',
					'-webkit-justify-content' => 'center',
					'-moz-box-pack'           => 'center',
					'-ms-flex-pack'           => 'center',
					'justify-content'         => 'center',
				),
				'.ast-amp .ast-mobile-header-stack .main-header-bar-wrap .site-branding' => array(
					'-webkit-box-flex' => '1',
					'-webkit-flex'     => '1 1 auto',
					'-moz-box-flex'    => '1',
					'-ms-flex'         => '1 1 auto',
					'-webkit-box-flex' => '1',
					'-webkit-flex'     => '1 1 auto',
					'-moz-box-flex'    => '1',
					'-ms-flex'         => '1 1 auto',
					'flex'             => '1 1 auto',
				),
				'.ast-amp .ast-mobile-header-stack .ast-mobile-menu-buttons' => array(
					'padding-top' => '0.8em',
				),
				'.ast-amp .ast-mobile-header-stack.header-main-layout-2 .ast-mobile-menu-buttons' => array(
					'padding-top' => '0.8em',
				),
				'.ast-amp .ast-mobile-header-stack.header-main-layout-1 .site-branding' => array(
					'padding-bottom' => '0',
				),
				'.ast-header-custom-item-outside.ast-amp .ast-mobile-header-stack .ast-masthead-custom-menu-items' => array(
					'padding'    => '0.8em 1em 0 1em',
					'text-align' => 'center',
					'width'      => '100%',
				),
				'.ast-header-custom-item-outside.ast-amp .ast-mobile-header-stack.header-main-layout-3 .ast-mobile-menu-buttons, .ast-header-custom-item-outside.ast-amp .ast-mobile-header-stack.header-main-layout-3 .ast-masthead-custom-menu-items' => array(
					'padding-top' => '0.8em',
				),
				// Tablet CSS.
				'.ast-amp .footer-sml-layout-2 .ast-small-footer-section-2' => array(
					'margin-top' => '1em',
				),
			);

			$parse_css .= astra_parse_css( $astra_small_break_point_navigation, astra_get_mobile_breakpoint() );

			return $parse_css;
		}

		/**
		 * Add AMP attributes to the nav menu wrapper.
		 *
		 * @since 1.7.0
		 * @param Array $attr HTML attributes to be added to the nav menu wrapper.
		 *
		 * @return Array updated HTML attributes.
		 */
		public function nav_menu_wrapper( $attr ) {
			$attr['[class]']         = '( astraAmpMenuExpanded ? \'ast-main-header-bar-alignment toggle-on\' : \'ast-main-header-bar-alignment\' )';
			$attr['aria-expanded']   = 'false';
			$attr['[aria-expanded]'] = '(astraAmpMenuExpanded ? \'true\' : \'false\')';

			return $attr;
		}

		/**
		 * Set AMP State for eeach sub menu toggle.
		 *
		 * @since 1.7.0
		 * @param String  $item_output HTML markup for the menu item.
		 * @param  WP_Post $item Post object for the navigation menu.
		 *
		 * @return String HTML MArkup for the menu including the AML State.
		 */
		public function toggle_button_markup( $item_output, $item ) {
			$item_output .= '<amp-state id="astraNavMenuItemExpanded' . esc_attr( $item->ID ) . '"><script type="application/json">false</script></amp-state>';

			return $item_output;
		}

		/**
		 * Add AMP attribites to the toggle button to add `.ast-submenu-expanded` class to parent li.
		 *
		 * @since 1.7.0
		 * @param array  $attr Optional. Extra attributes to merge with defaults.
		 * @param string $context    The context, to build filter name.
		 * @param array  $args       Optional. Custom data to pass to filter.
		 *
		 * @return Array updated HTML attributes.
		 */
		public function menu_toggle_button( $attr, $context, $args ) {
			$attr['[class]'] = '( astraNavMenuItemExpanded' . $args->ID . ' ? \' ast-menu-toggle dropdown-open\' : \'ast-menu-toggle\')';
			$attr['on']      = 'tap:AMP.setState( { astraNavMenuItemExpanded' . $args->ID . ': ! astraNavMenuItemExpanded' . $args->ID . ' } )';

			return $attr;
		}

		/**
		 * Add amp states to the dom.
		 */
		public function render_amp_states() {
			echo '<amp-state id="astraAmpMenuExpanded">';
			echo '<script type="application/json">false</script>';
			echo '</amp-state>';
		}

		/**
		 * Add search slide data attributes.
		 *
		 * @param string $input the data attrs already existing in the nav.
		 *
		 * @return string
		 */
		public function add_search_slide_toggle_attrs( $input ) {
			$input .= ' on="tap:AMP.setState( { astraAmpSlideSearchMenuExpanded: ! astraAmpSlideSearchMenuExpanded } )" ';
			$input .= ' [class]="( astraAmpSlideSearchMenuExpanded ? \'ast-search-menu-icon slide-search ast-dropdown-active\' : \'ast-search-menu-icon slide-search\' )" ';
			$input .= ' aria-expanded="false" [aria-expanded]="astraAmpSlideSearchMenuExpanded ? \'true\' : \'false\'" ';

			return $input;
		}

		/**
		 * Add search slide data attributes.
		 *
		 * @param string $input the data attrs already existing in the nav.
		 *
		 * @return string
		 */
		public function add_search_field_toggle_attrs( $input ) {
			$input .= ' on="tap:AMP.setState( { astraAmpSlideSearchMenuExpanded: astraAmpSlideSearchMenuExpanded } )" ';

			return $input;
		}

		/**
		 * Add the nav toggle data attributes.
		 *
		 * @param string $input the data attrs already existing in nav toggle.
		 *
		 * @return string
		 */
		public function add_nav_toggle_attrs( $input ) {
			$input .= ' on="tap:AMP.setState( { astraAmpMenuExpanded: ! astraAmpMenuExpanded } ),astra-body.toggleClass(class=ast-main-header-nav-open)" ';
			$input .= ' [class]="\'menu-toggle main-header-menu-toggle  ast-mobile-menu-buttons-minimal\' + ( astraAmpMenuExpanded ? \' toggled\' : \'\' )" ';
			$input .= ' aria-expanded="false" ';
			$input .= ' [aria-expanded]="astraAmpMenuExpanded ? \'true\' : \'false\'" ';

			return $input;
		}

	}
endif;

/**
* Kicking this off by calling 'get_instance()' method
*/
Astra_AMP::get_instance();
