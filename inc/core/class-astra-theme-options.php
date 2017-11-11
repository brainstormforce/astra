<?php
/**
 * Astra Theme Options
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

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
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
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
			// Defaults list of options.
			return apply_filters(
				'astra_theme_defaults', array(
					// Blog Single.
					'blog-single-post-structure'      => array(
						'single-image',
						'single-title-meta',
					),

					'blog-single-width'               => 'default',
					'blog-single-max-width'           => 1200,
					'blog-single-meta'                => array(
						'comments',
						'category',
						'author',
					),
					// Blog.
					'blog-post-structure'             => array(
						'image',
						'title-meta',
					),
					'blog-width'                      => 'default',
					'blog-max-width'                  => 1200,
					'blog-post-content'               => 'excerpt',
					'blog-meta'                       => array(
						'comments',
						'category',
						'author',
					),
					// Colors.
					'text-color'                      => '#3a3a3a',
					'link-color'                      => '#0274be',
					'link-h-color'                    => '#3a3a3a',

					// Footer Colors.
					'footer-bg-color'                 => '',
					'footer-color'                    => '',
					'footer-link-color'               => '',
					'footer-link-h-color'             => '',
					// Footer Widgets.
					'footer-adv-bg-color'             => '',
					'footer-adv-text-color'           => '',
					'footer-adv-link-color'           => '',
					'footer-adv-link-h-color'         => '',
					'footer-adv-wgt-title-color'      => '',
					// Buttons.
					'button-color'                    => '',
					'button-h-color'                  => '',
					'button-bg-color'                 => '',
					'button-bg-h-color'               => '',
					'button-radius'                   => 2,
					'button-v-padding'                => 10,
					'button-h-padding'                => 40,
					// Footer - Small.
					'footer-sml-layout'               => 'footer-sml-layout-1',
					'footer-sml-section-1'            => 'custom',
					'footer-sml-section-1-credit'     => __( 'Copyright &copy; [current_year] [site_title] | Powered by [theme_author]', 'astra' ),
					'footer-sml-section-2'            => '',
					'footer-sml-section-2-credit'     => __( 'Copyright &copy; [current_year] [site_title] | Powered by [theme_author]', 'astra' ),
					'footer-sml-dist-equal-align'     => true,
					'footer-sml-divider'              => 1,
					'footer-sml-divider-color'        => '#7a7a7a',
					'footer-layout-width'             => 'content',
					// General.
					'ast-header-retina-logo'          => '',
					'ast-header-logo-width'           => '',
					'display-site-title'              => 1,
					'display-site-tagline'            => 0,
					// Header - Primary.
					'disable-primary-nav'             => false,
					'header-layouts'                  => 'header-main-layout-1',
					'header-main-rt-section'          => 'none',
					'header-main-rt-section-html'     => '<button>' . __( 'Contact Us', 'astra' ) . '</button>',
					'header-main-sep'                 => 1,
					'header-main-sep-color'           => '',
					'header-main-layout-width'        => 'content',
					'header-main-menu-label'          => '',
					'header-main-menu-align'          => 'inline',
					// Site Layout.
					'site-layout'                     => 'ast-full-width-layout',
					'site-content-width'              => 1200,
					'site-layout-outside-bg-color'    => '',
					// Container.
					'site-content-layout'             => 'content-boxed-container',
					'single-page-content-layout'      => 'default',
					'single-post-content-layout'      => 'default',
					'archive-post-content-layout'     => 'default',
					// Typography.
					'body-font-family'                => 'inherit',
					'body-font-weight'                => 'inherit',
					'font-size-body'                  => array(
						'desktop'      => 15,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),

					'body-line-height'                => '',
					'para-margin-bottom'              => '',
					'body-text-transform'             => '',
					'headings-font-family'            => 'inherit',
					'headings-font-weight'            => 'inherit',
					'headings-text-transform'         => '',
					'font-size-site-title'            => array(
						'desktop'      => 35,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-site-tagline'          => array(
						'desktop'      => 15,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-entry-title'           => array(
						'desktop'      => 30,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-archive-summary-title' => array(
						'desktop'      => 40,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-page-title'            => array(
						'desktop'      => 30,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-h1'                    => array(
						'desktop'      => 48,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-h2'                    => array(
						'desktop'      => 42,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-h3'                    => array(
						'desktop'      => 30,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-h4'                    => array(
						'desktop'      => 20,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-h5'                    => array(
						'desktop'      => 18,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-h6'                    => array(
						'desktop'      => 15,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),

					// Sidebar.
					'site-sidebar-layout'             => 'right-sidebar',
					'site-sidebar-width'              => 30,
					'single-page-sidebar-layout'      => 'default',
					'single-post-sidebar-layout'      => 'default',
					'archive-post-sidebar-layout'     => 'default',

					// Sidebar.
					'footer-adv'                      => 'disabled',
				)
			);
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
				get_option( ASTRA_THEME_SETTINGS ),
				self::defaults()
			);
		}
	}
}// End if().
/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Theme_Options::get_instance();
