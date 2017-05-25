<?php
/**
 * Loader Functions
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme Enqueue Scripts
 */
if ( ! class_exists( 'AST_Enqueue_Scripts' ) ) {

	/**
	 * Theme Enqueue Scripts
	 */
	class AST_Enqueue_Scripts {

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

			add_action( 'ast_get_fonts',    array( $this, 'add_fonts' ), 1 );
			add_action( 'init',      		array( $this, 'add_theme_assets' )  );

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
			if( ! class_exists( 'Ast_Minify' ) ) {
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

			$js_uri  = AST_THEME_URI . 'assets/js/' . $dir_name . '/';
			$css_uri = AST_THEME_URI . 'assets/css/' . $dir_name . '/';

			// All assets.
			$all_assets = self::theme_assets();
			$styles     = $all_assets['css'];
			$scripts    = $all_assets['js'];

			// Register & Enqueue Styles.
			foreach ( $styles as $key => $style ) {

				// Generate CSS URL.
				$css_file = $css_uri . $style . $file_prefix . '.css';

				// Register.
				wp_register_style( $key, $css_file, array(), AST_THEME_VERSION, 'all' );

				// Enqueue.
				wp_enqueue_style( $key );

				// RTL support.
				wp_style_add_data( $key, 'rtl', 'replace' );

			}

			// Register & Enqueue Scripts.
			foreach ( $scripts as $key => $script ) {

				// Register.
				wp_register_script( $key, $js_uri . $script . $file_prefix . '.js', array(), AST_THEME_VERSION, true );

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

			$css_uri = AST_THEME_URI . 'assets/css/' . $dir_name . '/';

			// It always enqueued by default.
			// Register & Enqueue.
			wp_register_style( 'astra-fonts', $css_uri . 'astra-fonts' . $file_prefix . '.css', array(), AST_THEME_VERSION, 'all' );
			wp_enqueue_style( 'astra-fonts' );

			// Fonts - Render Fonts.
			Ast_Fonts::render_fonts();

			// Inline styles.
			wp_add_inline_style( 'astra-theme-css', apply_filters( 'ast_dynamic_css', AST_Dynamic_CSS::return_output() ) );
			wp_add_inline_style( 'astra-theme-css', AST_Dynamic_CSS::return_meta_output( true ) );

			// Add data attribute for script.
			wp_script_add_data( 'astra-flexibility', 'conditional', 'lt IE 9' );

			// Add localize variables for the script.
			wp_localize_script( 'astra-navigation', 'ast', self::theme_localize() );

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

			$ast_localize = array(
				'break_point' => ast_header_break_point(), 	// Header Break Point.
			);

			return apply_filters( 'ast_theme_js_localize', $ast_localize );

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

			$blog_layout = apply_filters( 'ast_theme_blog_layout', 'blog-layout-1' );
			if ( 'blog-layout-1' == $blog_layout ) {
				$default_assets['css']['astra-blog-layout'] = 'blog-layout-1';
			}

			return apply_filters( 'ast_theme_assets', $default_assets );
		}

		/**
		 * Add Fonts
		 */
		public function add_fonts() {

			$font_family = ast_get_option( 'body-font-family' );
			$font_weight = ast_get_option( 'body-font-weight' );

			Ast_Fonts::add_font( $font_family, $font_weight );
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

	new AST_Enqueue_Scripts();
}// End if().
