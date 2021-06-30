<?php
/**
 * Astra Theme Options
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Theme Options
 */
if ( ! class_exists( 'Astra_Theme_Options' ) ) {
	/**
	 * Theme Options
	 */
	class Astra_Theme_Options {

		/**
		 * Class instance.
		 *
		 * @access private
		 * @var $instance Class instance.
		 */
		private static $instance;

		/**
		 * Customizer defaults.
		 *
		 * @access Private
		 * @since 1.4.3
		 * @var Array
		 */
		private static $defaults;

		/**
		 * Post id.
		 *
		 * @var $instance Post id.
		 */
		public static $post_id = null;

		/**
		 * A static option variable.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var mixed $db_options
		 */
		private static $db_options;

		/**
		 * A static option variable.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var mixed $db_options
		 */
		private static $db_options_no_defaults;

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

			// Refresh options variables after customizer save.
			add_action( 'after_setup_theme', array( $this, 'refresh' ) );

		}

		/**
		 * Set default theme option values
		 *
		 * @since 1.0.0
		 * @return default values of the theme.
		 */
		public static function defaults() {

			if ( ! is_null( self::$defaults ) ) {
				return self::$defaults;
			}

			/**
			 * Update Astra customizer default values. To not update directly on existing users site, added backwards.
			 *
			 * @since x.x.x
			 */
			$apply_new_default_values = astra_apply_new_default_blog_values();

			// Defaults list of options.
			self::$defaults = apply_filters(
				'astra_theme_defaults',
				array(
					// Blog Single.
					'blog-single-post-structure'           => array(
						'single-image',
						'single-title-meta',
					),

					'blog-single-width'                    => 'default',
					'blog-single-max-width'                => 1200,
					'blog-single-meta'                     => array(
						'category',
						'author',
						'date',
					),
					// Blog.
					'blog-post-structure'                  => array(
						'image',
						'title-meta',
					),
					'blog-width'                           => 'default',
					'blog-max-width'                       => 1200,
					'blog-post-content'                    => 'excerpt',
					'blog-meta'                            => array(
						'category',
						'author',
						'date',
					),
					// Colors.
					'text-color'                           => '#3a3a3a',
					'link-color'                           => '#0170B9',
					'theme-color'                          => '#0170B9',
					'link-h-color'                         => '#3a3a3a',

					// Footer Bar Background.
					'footer-bg-obj'                        => array(
						'background-color'      => '',
						'background-image'      => '',
						'background-repeat'     => 'repeat',
						'background-position'   => 'center center',
						'background-size'       => 'auto',
						'background-attachment' => 'scroll',
						'background-type'       => '',
						'background-media'      => '',
					),
					'footer-color'                         => '',
					'footer-link-color'                    => '',
					'footer-link-h-color'                  => '',

					// Footer Widgets Background.
					'footer-adv-bg-obj'                    => array(
						'background-color'      => '',
						'background-image'      => '',
						'background-repeat'     => 'repeat',
						'background-position'   => 'center center',
						'background-size'       => 'auto',
						'background-attachment' => 'scroll',
						'background-type'       => '',
						'background-media'      => '',
					),
					'footer-adv-text-color'                => '',
					'footer-adv-link-color'                => '',
					'footer-adv-link-h-color'              => '',
					'footer-adv-wgt-title-color'           => '',

					// Buttons.
					'button-color'                         => '',
					'button-h-color'                       => '',
					'button-bg-color'                      => '',
					'button-bg-h-color'                    => '',
					'theme-button-padding'                 => array(
						'desktop'      => array(
							'top'    => $apply_new_default_values ? 15 : 10,
							'right'  => $apply_new_default_values ? 30 : 40,
							'bottom' => $apply_new_default_values ? 15 : 10,
							'left'   => $apply_new_default_values ? 30 : 40,
						),
						'tablet'       => array(
							'top'    => $apply_new_default_values ? 14 : '',
							'right'  => $apply_new_default_values ? 28 : '',
							'bottom' => $apply_new_default_values ? 14 : '',
							'left'   => $apply_new_default_values ? 28 : '',
						),
						'mobile'       => array(
							'top'    => $apply_new_default_values ? 12 : '',
							'right'  => $apply_new_default_values ? 24 : '',
							'bottom' => $apply_new_default_values ? 12 : '',
							'left'   => $apply_new_default_values ? 24 : '',
						),
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'button-radius'                        => 2,
					'theme-button-border-group-border-size' => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					// Footer - Small.
					'footer-sml-layout'                    => 'footer-sml-layout-1',
					'footer-sml-section-1'                 => 'custom',
					'footer-sml-section-1-credit'          => __( 'Copyright &copy; [current_year] [site_title] | Powered by [theme_author]', 'astra' ),
					'footer-sml-section-2'                 => '',
					'footer-sml-section-2-credit'          => __( 'Copyright &copy; [current_year] [site_title] | Powered by [theme_author]', 'astra' ),
					'footer-sml-dist-equal-align'          => true,
					'footer-sml-divider'                   => 1,
					'footer-sml-divider-color'             => '#7a7a7a',
					'footer-layout-width'                  => 'content',
					// General.
					'ast-header-retina-logo'               => '',
					'ast-header-logo-width'                => '',
					'ast-header-responsive-logo-width'     => array(
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					),
					'header-color-site-title'              => '',
					'header-color-h-site-title'            => '',
					'header-color-site-tagline'            => '',
					'display-site-title-responsive'        => array(
						'desktop' => 1,
						'tablet'  => 1,
						'mobile'  => 1,
					),
					'display-site-tagline-responsive'      => array(
						'desktop' => 0,
						'tablet'  => 0,
						'mobile'  => 0,
					),
					'logo-title-inline'                    => 1,
					// Header - Primary.
					'disable-primary-nav'                  => false,
					'header-layouts'                       => 'header-main-layout-1',
					'header-main-rt-section'               => 'none',
					'header-display-outside-menu'          => false,
					'header-main-rt-section-html'          => '<button>' . __( 'Contact Us', 'astra' ) . '</button>',
					'header-main-rt-section-button-text'   => __( 'Button', 'astra' ),
					'header-main-rt-section-button-link'   => apply_filters( 'astra_site_url', 'https://www.wpastra.com' ),
					'header-main-rt-section-button-link-option' => array(
						'url'      => apply_filters( 'astra_site_url', 'https://www.wpastra.com' ),
						'new_tab'  => false,
						'link_rel' => '',
					),
					'header-main-rt-section-button-style'  => 'theme-button',
					'header-main-rt-section-button-text-color' => '',
					'header-main-rt-section-button-back-color' => '',
					'header-main-rt-section-button-text-h-color' => '',
					'header-main-rt-section-button-back-h-color' => '',
					'header-main-rt-section-button-padding' => array(
						'desktop' => array(
							'top'    => '',
							'right'  => '',
							'bottom' => '',
							'left'   => '',
						),
						'tablet'  => array(
							'top'    => '',
							'right'  => '',
							'bottom' => '',
							'left'   => '',
						),
						'mobile'  => array(
							'top'    => '',
							'right'  => '',
							'bottom' => '',
							'left'   => '',
						),
					),
					'header-main-rt-section-button-border-size' => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'header-main-sep'                      => 1,
					'header-main-sep-color'                => '',
					'header-main-layout-width'             => 'content',
					// Header - Sub menu Border.
					'primary-submenu-border'               => array(
						'top'    => '2',
						'right'  => '0',
						'bottom' => '0',
						'left'   => '0',
					),
					'primary-submenu-item-border'          => false,
					'primary-submenu-b-color'              => '',
					'primary-submenu-item-b-color'         => '',

					// Primary header button typo options.
					'primary-header-button-font-family'    => 'inherit',
					'primary-header-button-font-weight'    => 'inherit',
					'primary-header-button-font-size'      => array(
						'desktop'      => '',
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'primary-header-button-text-transform' => '',
					'primary-header-button-line-height'    => 1,
					'primary-header-button-letter-spacing' => '',

					'header-main-menu-label'               => '',
					'header-main-menu-align'               => 'inline',
					'header-main-submenu-container-animation' => '',
					'mobile-header-breakpoint'             => '',
					'mobile-header-logo'                   => '',
					'mobile-header-logo-width'             => '',
					// Site Layout.
					'site-layout'                          => 'ast-full-width-layout',
					'site-content-width'                   => 1200,
					'site-layout-outside-bg-obj-responsive' => array(
						'desktop' => array(
							'background-color'      => '',
							'background-image'      => '',
							'background-repeat'     => 'repeat',
							'background-position'   => 'center center',
							'background-size'       => 'auto',
							'background-attachment' => 'scroll',
							'background-type'       => '',
							'background-media'      => '',
						),
						'tablet'  => array(
							'background-color'      => '',
							'background-image'      => '',
							'background-repeat'     => 'repeat',
							'background-position'   => 'center center',
							'background-size'       => 'auto',
							'background-attachment' => 'scroll',
							'background-type'       => '',
							'background-media'      => '',
						),
						'mobile'  => array(
							'background-color'      => '',
							'background-image'      => '',
							'background-repeat'     => 'repeat',
							'background-position'   => 'center center',
							'background-size'       => 'auto',
							'background-attachment' => 'scroll',
							'background-type'       => '',
							'background-media'      => '',
						),
					),
					// Container.
					'site-content-layout'                  => 'content-boxed-container',
					'single-page-content-layout'           => 'default',
					'single-post-content-layout'           => 'default',
					'archive-post-content-layout'          => 'default',
					// Typography.
					'body-font-family'                     => 'inherit',
					'body-font-variant'                    => '',
					'body-font-weight'                     => 'inherit',
					'font-size-body'                       => array(
						'desktop'      => $apply_new_default_values ? 16 : 15,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),

					'body-line-height'                     => $apply_new_default_values ? 1.8 : '',
					'para-margin-bottom'                   => '',
					'body-text-transform'                  => '',
					'headings-font-family'                 => 'inherit',
					'headings-font-weight'                 => $apply_new_default_values ? 600 : 'inherit',
					'headings-text-transform'              => '',
					'headings-line-height'                 => '',
					'font-size-site-title'                 => array(
						'desktop'      => $apply_new_default_values ? 26 : 35,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-site-tagline'               => array(
						'desktop'      => 15,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-entry-title'                => array(
						'desktop'      => 30,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-archive-summary-title'      => array(
						'desktop'      => 40,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-page-title'                 => array(
						'desktop'      => $apply_new_default_values ? 24 : 30,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-h1'                         => array(
						'desktop'      => 40,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-h2'                         => array(
						'desktop'      => 30,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-h3'                         => array(
						'desktop'      => $apply_new_default_values ? 26 : 25,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-h4'                         => array(
						'desktop'      => $apply_new_default_values ? 22 : 20,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-h5'                         => array(
						'desktop'      => 18,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-h6'                         => array(
						'desktop'      => $apply_new_default_values ? 16 : 15,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),

					// Sidebar.
					'site-sidebar-layout'                  => $apply_new_default_values ? 'no-sidebar' : 'right-sidebar',
					'site-sidebar-width'                   => 30,
					'single-page-sidebar-layout'           => 'default',
					'single-post-sidebar-layout'           => 'default',
					'archive-post-sidebar-layout'          => 'default',

					// Sidebar.
					'footer-adv'                           => 'disabled',
					'footer-adv-border-width'              => '',
					'footer-adv-border-color'              => '#7a7a7a',

					// toogle menu style.
					'mobile-header-toggle-btn-style'       => 'minimal',
					'hide-custom-menu-mobile'              => 1,

					// toogle menu target.
					'mobile-header-toggle-target'          => 'icon',

					// Performance.
					'load-google-fonts-locally'            => false,
					'preload-local-fonts'                  => false,
				)
			);

			return self::$defaults;
		}

		/**
		 * Get theme options from static array()
		 *
		 * @return array    Return array of theme options.
		 */
		public static function get_options() {
			return self::$db_options;
		}

		/**
		 * Update theme static option array.
		 */
		public static function refresh() {
			self::$db_options = wp_parse_args(
				self::get_db_options(),
				self::defaults()
			);
		}

		/**
		 * Get theme options from static array() from database
		 *
		 * @return array    Return array of theme options from database.
		 */
		public static function get_db_options() {
			self::$db_options_no_defaults = get_option( ASTRA_THEME_SETTINGS );
			return self::$db_options_no_defaults;
		}
	}
}
/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Theme_Options::get_instance();
