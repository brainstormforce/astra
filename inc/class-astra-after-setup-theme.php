<?php
/**
 * Astra functions and definitions.
 * Text Domain: astra
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * For more information on hooks, actions, and filters,
 * see http://codex.wordpress.org/Plugin_API
 *
 * Astra is a very powerful theme and virtually anything can be customized
 * via a child theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

/**
 * Astra_After_Setup_Theme initial setup
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Astra_After_Setup_Theme' ) ) {

	/**
	 * Astra_After_Setup_Theme initial setup
	 */
	class Astra_After_Setup_Theme {

		/**
		 * Instance
		 *
		 * @var $instance
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 1.0.0
		 * @return object
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
			add_action( 'after_setup_theme', array( $this, 'setup_theme' ), 2 );
		}

		/**
		 * Setup theme
		 *
		 * @since 1.0.0
		 */
		function setup_theme() {

			do_action( 'astra_class_loaded' );

			/**
			 * Content Width
			 */
			if ( ! isset( $content_width ) ) {
				$content_width = apply_filters( 'astra_content_width', 700 );
			}

			/**
			 * Make theme available for translation.
			 * Translations can be filed in the /languages/ directory.
			 * If you're building a theme based on Next, use a find and replace
			 * to change 'astra' to the name of your theme in all the template files.
			 */
			load_theme_textdomain( 'astra', ASTRA_THEME_DIR . '/languages' );

			/**
			 * Theme Support
			 */

			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );

			// Let WordPress manage the document title.
			add_theme_support( 'title-tag' );

			// Enable support for Post Thumbnails on posts and pages.
			add_theme_support( 'post-thumbnails' );

			// Switch default core markup for search form, comment form, and comments.
			// to output valid HTML5.
			add_theme_support(
				'html5', array(
					'search-form',
					'gallery',
					'caption',
				)
			);

			// Post formats.
			add_theme_support(
				'post-formats', array(
					'gallery',
					'image',
					'link',
					'quote',
					'video',
					'audio',
					'status',
					'aside',
				)
			);

			// Add theme support for Custom Logo.
			add_theme_support(
				'custom-logo', array(
					'width'       => 180,
					'height'      => 60,
					'flex-width'  => true,
					'flex-height' => true,
				)
			);

			// Customize Selective Refresh Widgets.
			add_theme_support( 'customize-selective-refresh-widgets' );

			/**
			 * This theme styles the visual editor to resemble the theme style,
			 * specifically font, colors, icons, and column width.
			 */
			/* Directory and Extension */
			$dir_name    = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';
			$file_prefix = ( SCRIPT_DEBUG ) ? '' : '.min';
			add_editor_style( 'assets/css/' . $dir_name . '/editor-style' . $file_prefix . '.css' );

			if ( apply_filters( 'astra_fullwidth_oembed', true ) ) {
				// Filters the oEmbed process to run the responsive_oembed_wrapper() function.
				add_filter( 'embed_oembed_html', array( $this, 'responsive_oembed_wrapper' ), 10, 3 );
			}
		}

		/**
		 * Adds a responsive embed wrapper around oEmbed content
		 *
		 * @param  string $html The oEmbed markup.
		 * @param  string $url  The URL being embedded.
		 * @param  array  $attr An array of attributes.
		 * @return string       Updated embed markup.
		 */
		function responsive_oembed_wrapper( $html, $url, $attr ) {
			return '' !== $html ? '<div class="ast-oembed-container">' . $html . '</div>' : '';
		}
	}
}// End if().

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_After_Setup_Theme::get_instance();
