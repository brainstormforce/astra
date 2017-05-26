<?php
/**
 * Beaver Themer Compatibility File.
 *
 * @package Astra
 */

// If plugin - 'Beaver Themer' not exist then return.
if ( ! class_exists( 'FLThemeBuilderLoader' ) ) {
	return;
}

/**
 * Astra Beaver Themer Compatibility
 */
if ( ! class_exists( 'Astra_Beaver_Themer' ) ) :

	/**
	 * Astra Beaver Themer Compatibility
	 *
	 * @since 1.0.0
	 */
	class Astra_Beaver_Themer {

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
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'after_setup_theme', 			array( $this, 'header_footer_support' ) );
			add_action( 'wp', 							array( $this, 'theme_header_footer_render' ) );
			add_filter( 'fl_theme_builder_part_hooks', 	array( $this, 'register_part_hooks' ) );
			add_filter( 'post_class', 					array( $this, 'render_post_class' ), 99 );
			add_filter( 'astra_get_content_layout', 		array( $this, 'builder_template_content_layout' ), 20 );
		}

		/**
		 * Builder Template Content layout set as Page Builder
		 *
		 * @param  string $layout Content Layout.
		 * @return string
		 * @since  1.0.2
		 */
		function builder_template_content_layout( $layout ) {

			$ids = FLThemeBuilderLayoutData::get_current_page_content_ids();
			$post_type = get_post_type();

			if ( 'fl-theme-layout' == $post_type ) {
				remove_action( 'astra_entry_after', 'astra_single_post_navigation_markup' );
			}

			if ( empty( $ids ) && 'fl-theme-layout' != $post_type ) {
				return $layout;
			}

			return 'page-builder';
		}

		/**
		 * Remove theme post's default classes
		 *
		 * @param  array $classes Post Classes.
		 * @return array
		 * @since  1.0.2
		 */
		function render_post_class( $classes ) {

			$post_class = array( 'fl-post-grid-post', 'fl-post-gallery-post', 'fl-post-feed-post' );
			$result = array_intersect( $classes, $post_class );

			if ( count( $result ) > 0 ) {
				$classes = array_diff( $classes, array( 'ast-col-sm-12', 'ast-article-post' ) );
				remove_filter( 'excerpt_more', 'astra_post_link', 1 );
			}
			return $classes;
		}

		/**
		 * Function to add Theme Support
		 *
		 * @since 1.0.0
		 */
		function header_footer_support() {

			add_theme_support( 'fl-theme-builder-headers' );
			add_theme_support( 'fl-theme-builder-footers' );
			add_theme_support( 'fl-theme-builder-parts' );
		}

		/**
		 * Function to update Atra header/footer with Beaver template
		 *
		 * @since 1.0.0
		 */
		function theme_header_footer_render() {

			// Get the header ID.
			$header_ids = FLThemeBuilderLayoutData::get_current_page_header_ids();

			// If we have a header, remove the theme header and hook in Theme Builder's.
			if ( ! empty( $header_ids ) ) {
				remove_action( 'astra_header', 'astra_header_markup' );
				add_action( 'astra_header', 'FLThemeBuilderLayoutRenderer::render_header' );
			}

			// Get the footer ID.
			$footer_ids = FLThemeBuilderLayoutData::get_current_page_footer_ids();

			// If we have a footer, remove the theme footer and hook in Theme Builder's.
			if ( ! empty( $footer_ids ) ) {
				remove_action( 'astra_footer', 'astra_footer_markup' );
				add_action( 'astra_footer', 'FLThemeBuilderLayoutRenderer::render_footer' );
			}
		}

		/**
		 * Function to Astra theme parts
		 *
		 * @since 1.0.0
		 */
		function register_part_hooks() {

			return array(
				array(
					'label' => 'Page',
					'hooks' => array(
						'astra_body_top'    => __( 'Before Page', 'astra' ),
						'astra_body_bottom' => __( 'After Page', 'astra' ),
					),
				),
				array(
					'label' => 'Header',
					'hooks' => array(
						'astra_header_before' => __( 'Before Header', 'astra' ),
						'astra_header_after'  => __( 'After Header', 'astra' ),
					),
				),
				array(
					'label' => 'Content',
					'hooks' => array(
						'astra_primary_content_top'    => __( 'Before Content', 'astra' ),
						'astra_primary_content_bottom' => __( 'After Content', 'astra' ),
					),
				),
				array(
					'label' => 'Footer',
					'hooks' => array(
						'astra_footer_before' => __( 'Before Footer', 'astra' ),
						'astra_footer_after'  => __( 'After Footer', 'astra' ),
					),
				),
				array(
					'label' => 'Sidebar',
					'hooks' => array(
						'astra_sidebars_before' => __( 'Before Sidebar', 'astra' ),
						'astra_sidebars_after'  => __( 'After Sidebar', 'astra' ),
					),
				),
				array(
					'label' => 'Posts',
					'hooks' => array(
						'loop_start'               => __( 'Loop Start', 'astra' ),
						'astra_entry_top'            => __( 'Before Post', 'astra' ),
						'astra_entry_content_before' => __( 'Before Post Content', 'astra' ),
						'astra_entry_content_after'  => __( 'After Post Content', 'astra' ),
						'astra_entry_bottom'         => __( 'After Post', 'astra' ),
						'astra_comments_before'      => __( 'Before Comments', 'astra' ),
						'astra_comments_after'       => __( 'After Comments', 'astra' ),
						'loop_end'                 => __( 'Loop End', 'astra' ),
					),
				),
			);
		}
	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Beaver_Themer::get_instance();
