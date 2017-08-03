<?php
/**
 * Astra Theme Customizer
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

/**
 * Customizer Loader
 */
if ( ! class_exists( 'Astra_Customizer' ) ) {

	/**
	 * Customizer Loader
	 *
	 * @since 1.0.0
	 */
	class Astra_Customizer {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object
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

			/**
			 * Customizer
			 */
			add_action( 'customize_preview_init',                  array( $this, 'preview_init' ) );
			add_action( 'customize_controls_enqueue_scripts',      array( $this, 'controls_scripts' ) );
			add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_footer_scripts' ) );
			add_action( 'customize_register',                      array( $this, 'customize_register' ) );
			add_action( 'customize_save_after',                    array( $this, 'customize_save' ) );
		}

		/**
		 * Print Footer Scripts
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function print_footer_scripts() {
			$output = '<script type="text/javascript">';
				$output .= '
	        	wp.customize.bind(\'ready\', function() {
	            	wp.customize.control.each(function(ctrl, i) {
	                	var desc = ctrl.container.find(".customize-control-description");
	                	if( desc.length) {
	                    	var title = ctrl.container.find(".customize-control-title");
	                    	var tooltip = desc.text().replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
	                    			return \'&#\'+i.charCodeAt(0)+\';\';
								});
	                    	desc.remove();
	                    	title.append(" <i class=\'dashicons dashicons-editor-help\'title=\'" + tooltip +"\'></i>");
	                	}
	            	});
	        	});';

				$output .= Astra_Fonts_Data::js();
			$output .= '</script>';

			echo $output;
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function customize_register( $wp_customize ) {

			/**
			 * Astra Pro Upsell Link
			 */
			if ( ! defined( 'ASTRA_EXT_VER' ) ) {
				require ASTRA_THEME_DIR . 'inc/customizer/astra-pro/class-astra-pro-customizer.php';
				require ASTRA_THEME_DIR . 'inc/customizer/astra-pro/astra-pro-section-register.php';
			}

			/**
			 * Register controls
			 */
			$wp_customize->register_control_type( 'Astra_Control_Sortable' );
			$wp_customize->register_control_type( 'Astra_Control_Radio_Image' );
			$wp_customize->register_control_type( 'Astra_Control_Slider' );
			$wp_customize->register_control_type( 'Astra_Control_Responsive' );
			$wp_customize->register_control_type( 'Astra_Control_Spacing' );
			$wp_customize->register_control_type( 'Astra_Control_Divider' );

			/**
			 * Helper files
			 */
			require ASTRA_THEME_DIR . 'inc/customizer/customizer-controls.php';
			require ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer-partials.php';
			require ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer-callback.php';
			require ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer-sanitizes.php';

			/**
			 * Override Defaults
			 */
			require ASTRA_THEME_DIR . 'inc/customizer/override-defaults.php';

			/**
			 * Register Sections & Panels
			 */
			require ASTRA_THEME_DIR . 'inc/customizer/register-panels-and-sections.php';

			/**
			 * Sections
			 */
			require ASTRA_THEME_DIR . 'inc/customizer/sections/site-identity/site-identity.php';
			require ASTRA_THEME_DIR . 'inc/customizer/sections/layout/site-layout.php';
			require ASTRA_THEME_DIR . 'inc/customizer/sections/layout/container.php';
			require ASTRA_THEME_DIR . 'inc/customizer/sections/layout/header.php';
			require ASTRA_THEME_DIR . 'inc/customizer/sections/layout/footer.php';
			require ASTRA_THEME_DIR . 'inc/customizer/sections/layout/blog.php';
			require ASTRA_THEME_DIR . 'inc/customizer/sections/layout/blog-single.php';
			require ASTRA_THEME_DIR . 'inc/customizer/sections/layout/sidebar.php';
			require ASTRA_THEME_DIR . 'inc/customizer/sections/layout/advanced-footer.php';
			require ASTRA_THEME_DIR . 'inc/customizer/sections/colors-background/body.php';
			require ASTRA_THEME_DIR . 'inc/customizer/sections/typography/header.php';
			require ASTRA_THEME_DIR . 'inc/customizer/sections/typography/body.php';
			require ASTRA_THEME_DIR . 'inc/customizer/sections/typography/content.php';
			require ASTRA_THEME_DIR . 'inc/customizer/sections/typography/single.php';
			require ASTRA_THEME_DIR . 'inc/customizer/sections/typography/archive.php';
			require ASTRA_THEME_DIR . 'inc/customizer/sections/buttons/buttons.php';

		}

		/**
		 * Customizer Controls
		 *
		 * @since 1.0.0
		 * @return void
		 */
		function controls_scripts() {

			$js_prefix  = '.min.js';
			$css_prefix = '.min.css';
			$dir        = 'minified';
			if ( SCRIPT_DEBUG ) {
				$js_prefix  = '.js';
				$css_prefix = '.css';
				$dir        = 'unminified';
			}

			// Customizer Core.
			wp_enqueue_script( 'astra-customizer-controls-toggle-js', ASTRA_THEME_URI . 'assets/js/' . $dir . '/customizer-controls-toggle' . $js_prefix, array(), ASTRA_THEME_VERSION, true );

			// Customizer Controls.
			wp_enqueue_style( 'astra-customizer-controls-css', ASTRA_THEME_URI . 'assets/css/' . $dir . '/customizer-controls' . $css_prefix, null, ASTRA_THEME_VERSION );
			wp_enqueue_script( 'astra-customizer-controls-js', ASTRA_THEME_URI . 'assets/js/' . $dir . '/customizer-controls' . $js_prefix, array( 'astra-customizer-controls-toggle-js' ), ASTRA_THEME_VERSION, true );

			wp_localize_script(
				'astra-customizer-controls-toggle-js', 'astra', apply_filters(
					'astra_theme_customizer_js_localize', array(
						'customizer' => array(
							'settings' => array(
								'sidebars' => array(
									'single' => array(
										'single-post-sidebar-layout',
										'single-page-sidebar-layout',
									),
									'archive' => array(
										'archive-post-sidebar-layout',
									),
								),
								'container' => array(
									'single' => array(
										'single-post-content-layout',
										'single-page-content-layout',
									),
									'archive' => array(
										'archive-post-content-layout',
									),
								),
							),
						),
						'theme' => array(
							'option' => ASTRA_THEME_SETTINGS,
						),
					)
				)
			);

		}

		/**
		 * Customizer Preview Init
		 *
		 * @since 1.0.0
		 * @return void
		 */
		function preview_init() {

			// Update variables.
			Astra_Theme_Options::refresh();

			$js_prefix  = '.min.js';
			$css_prefix = '.min.css';
			$dir        = 'minified';
			if ( SCRIPT_DEBUG ) {
				$js_prefix  = '.js';
				$css_prefix = '.css';
				$dir        = 'unminified';
			}

			wp_enqueue_script( 'astra-customizer-preview-js', ASTRA_THEME_URI . 'assets/js/' . $dir . '/customizer-preview' . $js_prefix, array( 'customize-preview' ), null, ASTRA_THEME_VERSION );
		}

		/**
		 * Called by the customize_save_after action to refresh
		 * the cached CSS when Customizer settings are saved.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		function customize_save() {

			// Update variables.
			Astra_Theme_Options::refresh();
		}
	}
}// End if().

/**
 *  Kicking this off by calling 'get_instance()' method
 */
Astra_Customizer::get_instance();
