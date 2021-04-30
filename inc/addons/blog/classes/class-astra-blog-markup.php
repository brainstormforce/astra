<?php
/**
 * Blog Pro Markup
 *
 * @since x.x.x
 * @package Astra
 */

if ( ! class_exists( 'Astra_Blog_Markup' ) ) {

	/**
	 * Blog Pro Markup Initial Setup
	 *
	 * @since x.x.x
	 */
	class Astra_Blog_Markup {

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
		 *  Constructor
		 */
		public function __construct() {

			add_filter( 'post_class', array( $this, 'astra_post_class_blog_grid' ) );
			add_filter( 'astra_primary_class', array( $this, 'astra_primary_class_blog_grid' ) );
			add_action( 'init', array( $this, 'init_action' ) );
			add_filter( 'astra_dynamic_theme_css', array( $this, 'astra_blog_grid_css' ) );
		}

		/**
		 * Add Blog Grid Class
		 *
		 * @since x.x.x
		 * @param array $classes Body Class Array.
		 * @return array
		 */
		public function astra_primary_class_blog_grid( $classes ) {

			// Apply grid class to archive page.
			if ( ( is_home() ) || is_archive() || is_search() ) {

				$blog_grid = astra_get_option( 'blog-grid' );

				if ( self::is_blog_layout_1() ) {
					$classes[] = 'ast-grid-' . esc_attr( $blog_grid );
				}
				$classes = apply_filters( 'astra_primary_class_blog_grid', $classes );
			}
			return $classes;
		}

		/**
		 * Update grid columns base on selected grid layout columns.
		 *
		 * @since x.x.x
		 * @param string $dynamic_css inline css.
		 * @return string.
		 */
		public function astra_blog_grid_css( $dynamic_css ) {
			if ( self::is_blog_layout_1() ) {
				$blog_grid           = astra_get_option( 'blog-grid' );
				$blog_grid_variables = array(
					':root' => array(
						'--gridColumns' => $blog_grid,
					),
				);
				$dynamic_css        .= astra_parse_css( $blog_grid_variables );
			}
			return $dynamic_css;
		}

		/**
		 * Add Post Class Blog Grid
		 *
		 * @since x.x.x
		 * @param array $classes Body Class Array.
		 * @return array
		 */
		public function astra_post_class_blog_grid( $classes ) {

			if ( is_archive() || is_home() || is_search() ) {

				global $wp_query;

				$blog_grid            = astra_get_option( 'blog-grid' );
				$blog_space_bet_posts = astra_get_option( 'blog-space-bet-posts' );
				
				if ( self::is_blog_layout_1() && ! Astra_Builder_Helper::apply_flex_based_css() ) {
					$classes[] = Astra_Builder_Helper::apply_flex_based_css() ? 'ast-width-md-' . ( 12 / $blog_grid ) : 'ast-col-md-' . ( 12 / $blog_grid );
				}

				if ( true === Astra_Builder_Helper::$is_header_footer_builder_active ) {
					$classes[] = 'ast-archive-post';
				}
				
				if ( $blog_space_bet_posts ) {
					$classes[] = 'ast-separate-posts';
				}
			} 

			return $classes;
		}

		/**
		 * Init action.
		 *
		 * @since x.x.x
		 * @return void
		 */
		public function init_action() {

			if ( 'excerpt' === astra_get_option( 'blog-post-content' ) ) {
				// Excerpt Filter.
				add_filter( 'excerpt_length', array( $this, 'custom_excerpt_length' ) );
				add_filter( 'astra_post_read_more', array( $this, 'read_more_text' ) );
			}
		}

		/**
		 * Excerpt count.
		 *
		 * @since x.x.x
		 * @param int $length default count of words.
		 * @return int count of words
		 */
		public function custom_excerpt_length( $length ) {

			$excerpt_length = astra_get_option( 'blog-excerpt-count' );

			if ( '' != $excerpt_length ) {
				$length = $excerpt_length;
			}

			return $length;
		}

		/**
		 * Read more text.
		 *
		 * @since x.x.x
		 * @param string $text default read more text.
		 * @return string read more text
		 */
		public function read_more_text( $text ) {

			$read_more = astra_get_option( 'blog-read-more-text' );

			if ( '' != $read_more ) {
				$text = $read_more;
			}

			return $text;
		}

		/**
		 * Check is blog layout 1 is selected or add-on is deactivated.
		 * 
		 * @since x.x.x
		 * @return boolean
		 */
		public static function is_blog_layout_1() {
			$blog_layout = astra_get_option( 'blog-layout' );
			return ( 'blog-layout-1' == $blog_layout || ! defined( 'ASTRA_EXT_VER' ) || ( defined( 'ASTRA_EXT_VER' ) && ! Astra_Ext_Extension::is_active( 'blog-pro' ) ) ) ? true : false;
		}

	}

}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Blog_Markup::get_instance();
