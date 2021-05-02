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
						'comments',
						'category',
						'author',
					),
					// Related Posts.
					'enable-related-posts'                 => false,
					'related-posts-total-count'            => 2,
					'enable-related-posts-excerpt'         => false,
					'related-posts-excerpt-count'          => 25,
					'related-posts-based-on'               => 'categories',
					'related-posts-order-by'               => 'date',
					'related-posts-order'                  => 'asc',
					'related-posts-grid'                   => '2',
					'related-posts-structure'              => array(
						'featured-image',
						'title-meta',
					),
					'related-posts-meta-structure'         => array(
						'comments',
						'category',
						'author',
					),
					// Related Posts - Color styles.
					'related-posts-text-color'             => '',
					'related-posts-link-color'             => '',
					'related-posts-title-color'            => '',
					'related-posts-background-color'       => '',
					'related-posts-meta-color'             => '',
					'related-posts-link-hover-color'       => '',
					'related-posts-meta-link-hover-color'  => '',
					// Related Posts - Title typo.
					'related-posts-section-title-font-family' => 'inherit',
					'related-posts-section-title-font-weight' => 'inherit',
					'related-posts-section-title-text-transform' => '',
					'related-posts-section-title-line-height' => '',
					'related-posts-section-title-font-size' => array(
						'desktop'      => '30',
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),

					// Related Posts - Title typo.
					'related-posts-title-font-family'      => 'inherit',
					'related-posts-title-font-weight'      => 'inherit',
					'related-posts-title-text-transform'   => '',
					'related-posts-title-line-height'      => '1',
					'related-posts-title-font-size'        => array(
						'desktop'      => '20',
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),

					// Related Posts - Meta typo.
					'related-posts-meta-font-family'       => 'inherit',
					'related-posts-meta-font-weight'       => 'inherit',
					'related-posts-meta-text-transform'    => '',
					'related-posts-meta-line-height'       => '',
					'related-posts-meta-font-size'         => array(
						'desktop'      => '14',
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),

					// Related Posts - Content typo.
					'related-posts-content-font-family'    => 'inherit',
					'related-posts-content-font-weight'    => 'inherit',
					'related-posts-content-text-transform' => '',
					'related-posts-content-line-height'    => '',
					'related-posts-content-font-size'      => array(
						'desktop'      => '',
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
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
						'comments',
						'category',
						'author',
					),
					// Colors.
					'text-color'                           => '#3a3a3a',
					'link-color'                           => '#0274be',
					'theme-color'                          => '#0274be',
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
							'top'    => 10,
							'right'  => 40,
							'bottom' => 10,
							'left'   => 40,
						),
						'tablet'       => array(
							'top'    => '',
							'right'  => '',
							'bottom' => '',
							'left'   => '',
						),
						'mobile'       => array(
							'top'    => '',
							'right'  => '',
							'bottom' => '',
							'left'   => '',
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
					'display-site-title'                   => 1,
					'display-site-tagline'                 => 0,
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
						'desktop'      => 15,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),

					'body-line-height'                     => '',
					'para-margin-bottom'                   => '',
					'body-text-transform'                  => '',
					'headings-font-family'                 => 'inherit',
					'headings-font-weight'                 => 'inherit',
					'headings-text-transform'              => '',
					'headings-line-height'                 => '',
					'font-size-site-title'                 => array(
						'desktop'      => 35,
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
						'desktop'      => 30,
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
						'desktop'      => 25,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),
					'font-size-h4'                         => array(
						'desktop'      => 20,
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
						'desktop'      => 15,
						'tablet'       => '',
						'mobile'       => '',
						'desktop-unit' => 'px',
						'tablet-unit'  => 'px',
						'mobile-unit'  => 'px',
					),

					// Sidebar.
					'site-sidebar-layout'                  => 'right-sidebar',
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
