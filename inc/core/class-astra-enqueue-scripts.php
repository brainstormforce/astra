<?php
/**
 * Loader Functions
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme Enqueue Scripts
 */
if ( ! class_exists( 'Astra_Enqueue_Scripts' ) ) {

	/**
	 * Theme Enqueue Scripts
	 */
	class Astra_Enqueue_Scripts {

		/**
		 * Class styles.
		 *
		 * @access public
		 * @var $styles Enqueued styles.
		 */
		public static $styles;

		/**
		 * Class scripts.
		 *
		 * @access public
		 * @var $scripts Enqueued scripts.
		 */
		public static $scripts;

		/**
		 * Constructor
		 */
		public function __construct() {

			add_action( 'astra_get_fonts',		array( $this, 'add_fonts' ), 1 );
			add_action( 'init',      			array( $this, 'add_theme_assets' )  );

		}

		/**
		 * Add theme (css & js) assets.
		 *
		 * @since 1.0.5
		 * @return void
		 */
		function add_theme_assets() {

			// If not have 'Ast_Minify' class
			// then process to enqueue assets.
			if( ! class_exists( 'Astra_Minify' ) ) {
				add_action( 'wp_enqueue_scripts', 	array( $this, 'enqueue_scripts' ) );
			}

			// Add localize variables.
			add_action( 'wp_enqueue_scripts', 	array( $this, 'localize_script' ) );

		}

		/**
		 * Add theme (css & js) assets.
		 *
		 * @since 1.0.4
		 * @since 1.0.5 	Enqueue JS & CSS files if class 'Ast_Minify' not found.
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			/* Directory and Extension */
			$file_prefix = ( SCRIPT_DEBUG ) ? '' : '.min';
			$dir_name    = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';

			$js_uri  = ASTRA_THEME_URI . 'assets/js/' . $dir_name . '/';
			$css_uri = ASTRA_THEME_URI . 'assets/css/' . $dir_name . '/';

			// All assets.
			$all_assets = self::theme_assets();
			$styles     = $all_assets['css'];
			$scripts    = $all_assets['js'];

			// Register & Enqueue Styles.
			foreach ( $styles as $key => $style ) {

				// Generate CSS URL.
				$css_file = $css_uri . $style . $file_prefix . '.css';

				// Register.
				wp_register_style( $key, $css_file, array(), ASTRA_THEME_VERSION, 'all' );

				// Enqueue.
				wp_enqueue_style( $key );

				// RTL support.
				wp_style_add_data( $key, 'rtl', 'replace' );

			}

			// Register & Enqueue Scripts.
			foreach ( $scripts as $key => $script ) {

				// Register.
				wp_register_script( $key, $js_uri . $script . $file_prefix . '.js', array(), ASTRA_THEME_VERSION, true );

				// Enqueue.
				wp_enqueue_script( $key );

			}

		}

		/**
		 * Add required assets and enqueue localize scripts
		 *
		 * @since 1.0.5
		 * @return void
		 */
		public function localize_script() {

			/* Directory and Extension */
			$file_prefix = ( SCRIPT_DEBUG ) ? '' : '.min';
			$dir_name    = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';

			$css_uri = ASTRA_THEME_URI . 'assets/css/' . $dir_name . '/';

			// It always enqueued by default.
			// Register & Enqueue.
			wp_register_style( 'astra-fonts', $css_uri . 'astra-fonts' . $file_prefix . '.css', array(), ASTRA_THEME_VERSION, 'all' );
			wp_enqueue_style( 'astra-fonts' );

			// Fonts - Render Fonts.
			Astra_Fonts::render_fonts();

			/**
			 * Inline styles
			 */
			wp_add_inline_style( 'astra-theme-css', apply_filters( 'astra_dynamic_css', Astra_Dynamic_CSS::return_output() ) );
			wp_add_inline_style( 'astra-theme-css', Astra_Dynamic_CSS::return_meta_output( true ) );

			// Add data attribute for script.
			wp_script_add_data( 'astra-flexibility', 'conditional', 'lt IE 9' );

			// Add localize variables for the script.
			wp_localize_script( 'astra-navigation', 'astra', self::theme_localize() );

			// Comment assets.
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
		}

		/**
		 * List of all assets.
		 *
		 * @return array assets array.
		 */
		public static function theme_localize() {

			$astra_localize = array(
				'break_point' => astra_header_break_point(),    // Header Break Point.
			);

			return apply_filters( 'astra_theme_js_localize', $astra_localize );

		}

		/**
		 * List of all assets.
		 *
		 * @return array assets array.
		 */
		public static function theme_assets() {

			$default_assets = array(

				// handle => location ( in /assets/js/ ) ( without .js ext).
				'js' => array(
					'astra-flexibility'         => 'flexibility',
					'astra-navigation'          => 'navigation',
					'astra-skip-link-focus-fix' => 'skip-link-focus-fix',
				),

				// handle => location ( in /assets/css/ ) ( without .css ext).
				'css' => array(
					'astra-theme-css' => 'style',
				),
			);

			$blog_layout = apply_filters( 'astra_theme_blog_layout', 'blog-layout-1' );
			if ( 'blog-layout-1' == $blog_layout ) {
				$default_assets['css']['astra-blog-layout'] = 'blog-layout-1';
			}

			return apply_filters( 'astra_theme_assets', $default_assets );
		}

		/**
		 * Add Fonts
		 */
		public function add_fonts() {

			$font_family = astra_get_option( 'body-font-family' );
			$font_weight = astra_get_option( 'body-font-weight' );

			Astra_Fonts::add_font( $font_family, $font_weight );
		}

		/**
		 * Trim CSS
		 *
		 * @since 1.0.0
		 * @param string $css CSS content to trim.
		 * @return string
		 */
		static public function trim_css( $css = '' ) {

			// Trim white space for faster page loading.
			if ( ! empty( $css ) ) {
				$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
				$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
				$css = str_replace( ', ', ',', $css );
			}

			return $css;
		}

	}

	new Astra_Enqueue_Scripts();
}// End if().
