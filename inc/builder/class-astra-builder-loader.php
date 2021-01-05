<?php
/**
 * Astra Builder Loader.
 *
 * @package astra-builder
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Builder_Loader' ) ) {

	/**
	 * Class Astra_Builder_Loader.
	 */
	final class Astra_Builder_Loader {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance = null;

		/**
		 *  Initiator
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
				do_action( 'astra_builder_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			/**
			 * Builder Core Files.
			 */
			require_once ASTRA_THEME_DIR . 'inc/core/builder/class-astra-builder-helper.php';
			require_once ASTRA_THEME_DIR . 'inc/core/builder/class-astra-builder-options.php';

			if( ! is_customize_preview( ) ){
				require_once ASTRA_THEME_DIR . 'inc/core/builder/class-astra-builder-admin.php';
			}
			/**
			 * Builder - Header & Footer Markup.
			 */
			require_once ASTRA_THEME_DIR . 'inc/builder/markup/class-astra-builder-header.php';

			if ( Astra_Builder_Helper::$is_header_footer_builder_active ) {

				require_once ASTRA_THEME_DIR . 'inc/builder/markup/class-astra-builder-footer.php';

			}
			/**
			 * Builder Controllers.
			 */
			require_once ASTRA_THEME_DIR . 'inc/builder/controllers/class-astra-builder-widget-controller.php';
			require_once ASTRA_THEME_DIR . 'inc/builder/controllers/class-astra-builder-ui-controller.php';
			/**
			 * Customizer - Configs.
			 */
			require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-builder-customizer.php';

			/**DONE */

			if ( Astra_Builder_Helper::$is_header_footer_builder_active ) {
				add_filter( 'astra_existing_header_footer_configs', '__return_false' );
				add_filter( 'astra_addon_existing_header_footer_configs', '__return_false' );
			}
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			add_action( 'wp', array( $this, 'load_markup' ), 100 );

			add_filter( 'astra_quick_settings', array( $this, 'quick_settings' ) );
		}

		/**
		 * Update Quick Settings links.
		 *
		 * @param array $quick_settings Links to the Quick Settings in Astra.
		 * @since 3.0.0
		 */
		public function quick_settings( $quick_settings ) {

			if ( ! Astra_Builder_Helper::$is_header_footer_builder_active ) {
				return $quick_settings;
			}

			$quick_settings['header']['title']     = __( 'Header Builder', 'astra' );
			$quick_settings['header']['quick_url'] = admin_url( 'customize.php?autofocus[section]=section-header-builder-layout' );

			$quick_settings['footer']['title']     = __( 'Footer Builder', 'astra' );
			$quick_settings['footer']['quick_url'] = admin_url( 'customize.php?autofocus[section]=section-footer-builder-layout' );

			return $quick_settings;
		}

		/**
		 * Advanced Hooks markup loader
		 *
		 * Loads appropriate template file based on the style option selected in options panel.
		 *
		 * @since 3.0.0
		 */
		public function load_markup() {

			if ( ! defined( 'ASTRA_ADVANCED_HOOKS_POST_TYPE' ) || ! Astra_Builder_Helper::$is_header_footer_builder_active ) {
				return;
			}

			$option = array(
				'location'  => 'ast-advanced-hook-location',
				'exclusion' => 'ast-advanced-hook-exclusion',
				'users'     => 'ast-advanced-hook-users',
			);

			$result             = Astra_Target_Rules_Fields::get_instance()->get_posts_by_conditions( ASTRA_ADVANCED_HOOKS_POST_TYPE, $option );
			$header_counter     = 0;
			$footer_counter     = 0;
			$layout_404_counter = 0;

			foreach ( $result as $post_id => $post_data ) {
				$post_type = get_post_type();

				if ( ASTRA_ADVANCED_HOOKS_POST_TYPE !== $post_type ) {

					$layout = get_post_meta( $post_id, 'ast-advanced-hook-layout', false );

					if ( isset( $layout[0] ) && '404-page' == $layout[0] && 0 == $layout_404_counter ) {

						$layout_404_settings = get_post_meta( $post_id, 'ast-404-page', true );
						if ( isset( $layout_404_settings['disable_header'] ) && 'enabled' == $layout_404_settings['disable_header'] ) {
							remove_action( 'astra_header', array( Astra_Builder_Header::get_instance(), 'header_builder_markup' ) );
						}

						if ( isset( $layout_404_settings['disable_footer'] ) && 'enabled' == $layout_404_settings['disable_footer'] ) {
							remove_action( 'astra_footer', array( Astra_Builder_Footer::get_instance(), 'footer_markup' ) );
						}

						$layout_404_counter ++;
					} elseif ( isset( $layout[0] ) && 'header' == $layout[0] && 0 == $header_counter ) {
						// Remove default site's header.
						remove_action( 'astra_header', array( Astra_Builder_Header::get_instance(), 'header_builder_markup' ) );
						$header_counter++;
					} elseif ( isset( $layout[0] ) && 'footer' == $layout[0] && 0 == $footer_counter ) {
						// Remove default site's footer.
						remove_action( 'astra_footer', array( Astra_Builder_Footer::get_instance(), 'footer_markup' ) );
						$footer_counter++;
					}
				}
			}
		}
	}

	/**
	 *  Prepare if class 'Astra_Builder_Loader' exist.
	 *  Kicking this off by calling 'get_instance()' method
	 */
	Astra_Builder_Loader::get_instance();
}


if ( ! function_exists( 'astra_builder' ) ) {
	/**
	 * Get global class.
	 *
	 * @return object
	 */
	function astra_builder() {
		return Astra_Builder_Loader::get_instance();
	}
}
