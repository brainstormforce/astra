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

			add_action( 'ast_get_fonts',      array( $this, 'add_fonts' ), 1 );

			add_action( 'init', array( $this, 'style_list' ) );
			add_action( 'init', array( $this, 'scripts_list' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1 );
			add_action( 'ast_get_css_files',  array( $this, 'add_styles' ), 1 );
		}

		/**
		 * Registered Styles List
		 */
		public static function style_list() {

			/*** Start Path Logic ***/

			/* Define Variables */
			$uri  = AST_THEME_URI . 'assets/css/';
			$path = AST_THEME_DIR . 'assets/css/';
			$rtl  = '';

			if ( is_rtl() ) {
			 	$rtl = '-rtl';
			}

			/* Directory and Extension */
			$file_prefix = $rtl . '.min';
			$dir_name    = 'minified';

			if ( SCRIPT_DEBUG ) {
				$file_prefix = $rtl;
				$dir_name    = 'unminified';
			}

			$css_uri = $uri . $dir_name . '/';
			$css_dir = $path . $dir_name . '/';

			/*** End Path Logic ***/

			// Styles.
			self::$styles[] = array(
				'handle' => 'ast-theme-css',
				'deps'   => array(),
				'ver'    => AST_THEME_VERSION,
				'media'  => 'all',

				'src'    => $css_dir . 'style' . $file_prefix . '.css',
				'path'   => $css_uri . 'style' . $file_prefix . '.css',
			);

			/* Blog Layouts */
			$blog_layout = apply_filters( 'ast_theme_blog_layout', 'blog-layout-1' );
			if ( 'blog-layout-1' == $blog_layout ) {

				// Styles.
				self::$styles[] = array(
					'handle' => 'ast-blog-layout',
					'deps'   => array(),
					'ver'    => AST_THEME_VERSION,
					'media'  => 'all',

					'src'    => $css_dir . 'blog-layout-1' . $file_prefix . '.css',
					'path'   => $css_uri . 'blog-layout-1' . $file_prefix . '.css',
				);
			}
		}

		/**
		 * Registered Scripts List
		 */
		public static function scripts_list() {

			/*** Start Path Logic ***/

			/* Define Variables */
			$uri  = AST_THEME_URI . 'assets/js/';
			$path = AST_THEME_DIR . 'assets/js/';

			/* Directory and Extension */
			$file_prefix = '.min';
			$dir_name    = 'minified';

			if ( SCRIPT_DEBUG ) {
				$file_prefix = '';
				$dir_name    = 'unminified';
			}

			$js_uri = $uri . $dir_name . '/';
			$js_dir = $path . $dir_name . '/';

			/*** End Path Logic ***/

			// Flexibility.
			self::$scripts[] = array(
				'handle'      => 'ast-flexibility',
				'deps'        => array(),
				'ver'         => AST_THEME_VERSION,
				'in_footer'   => true,

				'src'         => $js_dir . 'flexibility' . $file_prefix . '.js',
				'path'        => $js_uri . 'flexibility' . $file_prefix . '.js',
			);

			// Navigation.
			self::$scripts[] = array(
				'handle'      => 'ast-navigation',
				'deps'        => array(),
				'ver'         => AST_THEME_VERSION,
				'in_footer'   => true,

				'src'         => $js_dir . 'navigation' . $file_prefix . '.js',
				'path'        => $js_uri . 'navigation' . $file_prefix . '.js',
			);

			// Skip Link.
			self::$scripts[] = array(
				'handle'      => 'ast-skip-link',
				'deps'        => array(),
				'ver'         => AST_THEME_VERSION,
				'in_footer'   => true,

				'src'         => $js_dir . 'skip-link-focus-fix' . $file_prefix . '.js',
				'path'        => $js_uri . 'skip-link-focus-fix' . $file_prefix . '.js',
			);

		}

		/**
		 * Register and Enqueue styles
		 *
		 * @param  string  $handle Style handle.
		 * @param  string  $src    Style src.
		 * @param  mixed   $deps   Style deps.
		 * @param  string  $ver    Style ver.
		 * @param  string  $media  Style media.
		 * @param  boolean $rtl_support  Style media.
		 * @return void
		 */
		public static function register_style( $handle = '', $src = '', $deps = '', $ver = '', $media = '', $rtl_support = true ) {

			// Load minified assets.
			if ( ! SCRIPT_DEBUG ) {
				$src = str_replace( '.css', '.min.css', $src ); 		// Change extension.
				$src = str_replace( 'unminified', 'minified', $src ); 	// Change directory.
			}

			// If registered style has RTL support?
			// And is_rtl mode?
			if ( $rtl_support && is_rtl() ) {
				$src = str_replace( '.css', '-rtl.css', $src ); 		// Change extension for unminified.
				$src = str_replace( '.min.css', '-rtl.min.css', $src ); // Change extension for minified.
			}

			// Register.
			wp_register_style( $handle, $src, $deps, $ver, $media );

			// Enqueue.
			wp_enqueue_style( $handle );

		}

		/**
		 * Register and Enqueue Scripts
		 *
		 * @param  string $handle 		Script handle.
		 * @param  string $src    		Script src.
		 * @param  mixed  $deps   		Script deps.
		 * @param  string $ver    		Script ver.
		 * @param  string $in_footer  	Script in_footer.
		 * @return void
		 */
		public static function register_script( $handle = '', $src = '', $deps = '', $ver = '', $in_footer = '' ) {

			// Load minified assets.
			if ( ! SCRIPT_DEBUG ) {
				$src = str_replace( '.js', '.min.js', $src ); 			// Change extension.
				$src = str_replace( 'unminified', 'minified', $src ); 	// Change directory.
			}

			// Register.
			wp_register_script( $handle, $src, $deps, $ver, $in_footer );

			// Enqueue.
			wp_enqueue_script( $handle );

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
		 * Enqueue Scripts
		 */
		public function add_styles() {

			if ( class_exists( 'Ast_Minify' ) ) {

				if ( count( self::$styles ) > 0 ) {
					foreach ( self::$styles as $key => $style ) {
						Ast_Minify::add_css( $style['src'] );
					}
				}
			}

		}

		/**
		 * Enqueue Scripts
		 */
		public function enqueue_scripts() {

			if ( ! class_exists( 'Ast_Minify' ) ) {

				// Register & Enqueue Styles.
				foreach ( self::$styles as $key => $style ) {

					$handle = ( isset( $style['handle'] ) ) ? $style['handle'] : '';
					$path   = ( isset( $style['path'] ) ) ? $style['path'] : '';
					$deps   = ( isset( $style['deps'] ) ) ? $style['deps'] : '';
					$ver    = ( isset( $style['ver'] ) ) ? $style['ver'] : '';
					$media  = ( isset( $style['media'] ) ) ? $style['media'] : '';

					// Register.
					wp_register_style( $handle, $path, $deps, $ver, $media );

					// Enqueue.
					wp_enqueue_style( $handle );
				}

				// Register & Enqueue Scripts.
				foreach ( self::$scripts as $key => $script ) {

					$handle    = ( isset( $script['handle'] ) ) ? $script['handle'] : '';
					$path      = ( isset( $script['path'] ) ) ? $script['path'] : '';
					$deps      = ( isset( $script['deps'] ) ) ? $script['deps'] : '';
					$ver       = ( isset( $script['ver'] ) ) ? $script['ver'] : '';
					$in_footer = ( isset( $script['in_footer'] ) ) ? $script['in_footer'] : '';

					// Register.
					wp_register_script( $handle, $path, $deps, $ver, $in_footer );

					// Enqueue.
					wp_enqueue_script( $handle );
				}
			}// End if().

			// Register styles.
			if ( SCRIPT_DEBUG ) {
				wp_enqueue_style( 'ast-fonts', AST_THEME_URI . 'assets/css/unminified/astra-fonts.css', array(), AST_THEME_VERSION, 'all', false );
			} else {
				wp_enqueue_style( 'ast-fonts', AST_THEME_URI . 'assets/css/minified/astra-fonts.min.css', array(), AST_THEME_VERSION, 'all', false );
			}

			// Fonts - Render Fonts.
			Ast_Fonts::render_fonts();

			/**
			 * Inline styles & scripts.
			 */
			wp_add_inline_style( 'ast-theme-css', AST_Dynamic_CSS::return_output() );
			wp_add_inline_style( 'ast-theme-css', AST_Dynamic_CSS::return_meta_output( true ) );
			wp_add_inline_style( 'ast-addon-css', AST_Dynamic_CSS::return_output() );
			wp_add_inline_style( 'ast-addon-css', AST_Dynamic_CSS::return_meta_output( true ) );

			wp_script_add_data( 'ast-flexibility', 'conditional', 'lt IE 9' );

			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			// Registered localize framework options object 'astra'.
			global $wp_query;

			$ast_localize = array(
				'break_point' => ast_header_break_point(), 	// Header Break Point.
			);

			wp_localize_script( 'ast-navigation', 'ast', apply_filters( 'ast_theme_js_localize', $ast_localize ) );
			wp_localize_script( 'ast-addon-js', 'ast', apply_filters( 'ast_theme_js_localize', $ast_localize ) );

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
