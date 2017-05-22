<?php
/**
 * Astra Theme Options
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.0.0
 */

/**
 * Theme Options
 */
if ( ! class_exists( 'Ast_Theme_Options' ) ) {

	/**
	 * Theme Options
	 */
	class Ast_Theme_Options {

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
			self::refresh();
			add_action( 'init', array( $this, 'update_options' ) );
		}

		/**
		 * Update Options
		 */
		function update_options() {
			self::refresh();
		}

		/**
		 * Set default theme option values
		 *
		 * @since 1.0.0
		 * @return default values of the theme.
		 */
		public static function defaults() {

			// Defaults list of options.
			return apply_filters( 'ast_theme_defaults', array(

				// Blog Single.
				'blog-single-width'					=> 'default',
				'blog-single-max-width'				=> 1200,
				'blog-single-meta'					=> array(
														'comments',
														'category',
														'author',
														),
				// Blog.
				'blog-width'						=> 'default',
				'blog-max-width'					=> 1200,
				'blog-post-content'					=> 'excerpt',
				'blog-meta'							=> array(
														'comments',
														'category',
														'author',
														),
				// Colors.
				'text-color'						=> '#3a3a3a',
				'link-color'						=> '#0085ba',
				'link-h-color'						=> '#3a3a3a',

				// Buttons.
				'button-color'						=> '',
				'button-h-color'					=> '',
				'button-bg-color'					=> '',
				'button-bg-h-color'					=> '',
				'button-radius'						=> 2,
				'button-v-padding'					=> 10,
				'button-h-padding'					=> 40,

				// Footer - Small.
				'footer-sml-layout'					=> 'footer-sml-layout-1',
				'footer-sml-section-1'				=> 'custom',
				'footer-sml-section-1-credit'		=> __( 'Copyright &copy; [current_year] [site_title] | Powered by [theme_author]', 'astra' ),
				'footer-sml-section-2'				=> '',
				'footer-sml-section-2-credit'		=> __( 'Copyright &copy; [current_year] [site_title] | Powered by [theme_author]', 'astra' ),
				'footer-sml-dist-equal-align'		=> true,
				'footer-sml-divider'				=> 4,
				'footer-sml-divider-color'			=> '#fff',
				'footer-layout-width'				=> 'content',

				// General.
				'display-site-title'				=> 1,
				'display-site-tagline'				=> 0,

				// Header - Primary.
				'disable-primary-nav'				=> false,
				'header-layouts'					=> 'header-main-layout-1',
				'header-main-rt-section'			=> 'none',
				'header-main-rt-section-html'		=> '<button>' . __( 'Contact Us' , 'astra' ) . '</button>',
				'header-main-sep'					=> 1,
				'header-main-sep-color'				=> '',
				'header-main-layout-width'			=> 'content',

				// Site Layout.
				'site-layout'                      => 'ast-full-width-layout',
				'site-content-width'               => 1200,
				'site-layout-outside-bg-color'     => '',

				// Container.
				'site-content-layout'				=> 'plain-container',
				'single-page-content-layout'		=> 'plain-container',
				'single-post-content-layout'		=> 'content-boxed-container',
				'archive-post-content-layout'		=> 'content-boxed-container',

				// Typography.
				'body-font-family'					=> 'inherit',
				'body-font-weight'					=> 'inherit',
				'font-size-body'					=> array(
														'desktop' => 15,
														'tablet' => '',
														'mobile' => '',
													),
				'body-line-height'					=> array(
														'desktop' => '',
														'tablet' => '',
														'mobile' => '',
													),
				'body-text-transform'				=> '',
				'font-size-site-title'				=> array(
															'desktop' => 35,
															'tablet' => '',
															'mobile' => '',
														),
				'font-size-site-tagline'			=> array(
															'desktop' => 15,
															'tablet' => '',
															'mobile' => '',
														),
				'font-size-entry-title'				=> array(
															'desktop' => 30,
															'tablet' => '',
															'mobile' => '',
														),
				'font-size-page-title'				=> array(
															'desktop' => 30,
															'tablet' => '',
															'mobile' => '',
														),
				'font-size-h1'						=> array(
															'desktop' => 48,
															'tablet' => '',
															'mobile' => '',
														),
				'font-size-h2'						=> array(
															'desktop' => 42,
															'tablet' => '',
															'mobile' => '',
														),
				'font-size-h3'						=> array(
															'desktop' => 30,
															'tablet' => '',
															'mobile' => '',
														),
				'font-size-h4'						=> array(
															'desktop' => 20,
															'tablet' => '',
															'mobile' => '',
														),
				'font-size-h5'						=> array(
															'desktop' => 18,
															'tablet' => '',
															'mobile' => '',
														),
				'font-size-h6'						=> array(
															'desktop' => 15,
															'tablet' => '',
															'mobile' => '',
														),

				// Sidebar.
				'site-sidebar-layout'				=> 'right-sidebar',
				'site-sidebar-width'				=> 30,
				'single-page-sidebar-layout'		=> 'no-sidebar',
				'single-post-sidebar-layout'		=> 'right-sidebar',
				'archive-post-sidebar-layout'		=> 'right-sidebar',
			) );

		}

		/**
		 * Get theme options from static array()
		 *
		 * @return array 	Return array of theme options.
		 */
		public static function get_options() {
			return self::$db_options;
		}

		/**
		 * Update theme static option array.
		 */
		public static function refresh() {
			self::$db_options = wp_parse_args(
				get_option( AST_THEME_SETTINGS ),
				self::defaults()
			);
		}
	}
}// End if().

/**
 * Kicking this off by calling 'get_instance()' method
 */
Ast_Theme_Options::get_instance();
