<?php
/**
 * Astra Theme Customizer
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
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
		 * Customizer Configurations.
		 *
		 * @access Private
		 * @since 1.4.3
		 * @var Array
		 */
		private static $configuration;

		/**
		 * Customizer Dependency Array.
		 *
		 * @access Private
		 * @since 1.4.3
		 * @var array
		 */
		private static $_dependency_arr = array();

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

			/**
			 * Customizer
			 */
			add_action( 'customize_preview_init', array( $this, 'preview_init' ) );

			if ( is_admin() || is_customize_preview() ) {
				add_action( 'customize_register', array( $this, 'include_configurations' ), 2 );
				add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );
				add_action( 'customize_register', array( $this, 'astra_pro_upgrade_configurations' ), 2 );
			}

			add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_scripts' ) );
			add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_footer_scripts' ) );
			add_action( 'customize_register', array( $this, 'customize_register_panel' ), 2 );
			add_action( 'customize_register', array( $this, 'customize_register' ) );
			add_action( 'customize_save_after', array( $this, 'customize_save' ) );
		}

		/**
		 * Process and Register Customizer Panels, Sections, Settings and Controls.
		 *
		 * @param WP_Customize_Manager $wp_customize Reference to WP_Customize_Manager.
		 * @since 1.4.3
		 * @return void
		 */
		public function register_customizer_settings( $wp_customize ) {

			$configurations = $this->get_customizer_configurations( $wp_customize );

			foreach ( $configurations as $key => $config ) {
				$config = wp_parse_args( $config, $this->get_astra_customizer_configuration_defaults() );

				switch ( $config['type'] ) {
					case 'panel':
						// Remove type from configuration.
						unset( $config['type'] );

						$this->register_panel( $config, $wp_customize );

						break;

					case 'section':
						// Remove type from configuration.
						unset( $config['type'] );

						$this->register_section( $config, $wp_customize );

						break;

					case 'control':
						// Remove type from configuration.
						unset( $config['type'] );

						$this->register_setting_control( $config, $wp_customize );

						break;
				}
			}

		}

		/**
		 * Filter and return Customizer Configurations.
		 *
		 * @param WP_Customize_Manager $wp_customize Reference to WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Customizer Configurations for registering Sections/Panels/Controls.
		 */
		private function get_customizer_configurations( $wp_customize ) {
			if ( ! is_null( self::$configuration ) ) {
				return self::$configuration;
			}

			return apply_filters( 'astra_customizer_configurations', array(), $wp_customize );
		}

		/**
		 * Return default values for the Customize Configurations.
		 *
		 * @since 1.4.3
		 * @return Array default values for the Customizer Configurations.
		 */
		private function get_astra_customizer_configuration_defaults() {
			return apply_filters(
				'astra_customizer_configuration_defaults',
				array(
					'priority'             => null,
					'title'                => null,
					'label'                => null,
					'name'                 => null,
					'type'                 => null,
					'description'          => null,
					'capability'           => null,
					'datastore_type'       => 'option', // theme_mod or option. Default option.
					'settings'             => null,
					'active_callback'      => null,
					'sanitize_callback'    => null,
					'sanitize_js_callback' => null,
					'theme_supports'       => null,
					'transport'            => null,
					'default'              => null,
					'selector'             => null,
				)
			);
		}

		/**
		 * Register Customizer Panel.
		 *
		 * @param Array                $config Panel Configuration settings.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return void
		 */
		private function register_panel( $config, $wp_customize ) {
			$wp_customize->add_panel( new Astra_WP_Customize_Panel( $wp_customize, astar( $config, 'name' ), $config ) );
		}

		/**
		 * Register Customizer Section.
		 *
		 * @param Array                $config Panel Configuration settings.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return void
		 */
		private function register_section( $config, $wp_customize ) {

			$callback = astar( $config, 'section_callback', 'Astra_WP_Customize_Section' );

			$wp_customize->add_section( new $callback( $wp_customize, astar( $config, 'name' ), $config ) );
		}

		/**
		 * Register Customizer Control and Setting.
		 *
		 * @param Array                $config Panel Configuration settings.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return void
		 */
		private function register_setting_control( $config, $wp_customize ) {

			$wp_customize->add_setting(
				astar( $config, 'name' ),
				array(
					'default'           => astar( $config, 'default' ),
					'type'              => astar( $config, 'datastore_type' ),
					'transport'         => astar( $config, 'transport', 'refresh' ),
					'sanitize_callback' => astar( $config, 'sanitize_callback', Astra_Customizer_Control_Base::get_sanitize_call( astar( $config, 'control' ) ) ),
				)
			);

			$instance = Astra_Customizer_Control_Base::get_control_instance( astar( $config, 'control' ) );

			$config['label'] = astar( $config, 'title' );
			$config['type']  = astar( $config, 'control' );

			// For ast-font control font-weight and font-family is passed as param `font-type` which needs to be converted to `type`.
			if ( false !== astar( $config, 'font-type', false ) ) {
				$config['type'] = astar( $config, 'font-type', false );
			}

			if ( false !== $instance ) {
				$wp_customize->add_control(
					new $instance( $wp_customize, astar( $config, 'name' ), $config )
				);
			} else {
				$wp_customize->add_control( astar( $config, 'name' ), $config );
			}

			if ( astar( $config, 'partial', false ) ) {

				if ( isset( $wp_customize->selective_refresh ) ) {
					$wp_customize->selective_refresh->add_partial(
						astar( $config, 'name' ),
						array(
							'selector'            => astar( $config['partial'], 'selector' ),
							'container_inclusive' => astar( $config['partial'], 'container_inclusive' ),
							'render_callback'     => astar( $config['partial'], 'render_callback' ),
						)
					);
				}
			}

			if ( false !== astar( $config, 'required', false ) ) {
				$this->update_dependency_arr( astar( $config, 'name' ), astar( $config, 'required' ) );
			}

		}

		/**
		 * Update dependency in the dependency array.
		 *
		 * @param String $key name of the Setting/Control for which the dependency is added.
		 * @param Array  $dependency dependency of the $name Setting/Control.
		 * @since 1.4.3
		 * @return void
		 */
		private function update_dependency_arr( $key, $dependency ) {
			self::$_dependency_arr[ $key ] = $dependency;
		}

		/**
		 * Get dependency Array.
		 *
		 * @since 1.4.3
		 * @return Array Dependencies discovered when registering controls and settings.
		 */
		private function get_dependency_arr() {
			return self::$_dependency_arr;
		}

		/**
		 * Include Customizer Configuration files.
		 *
		 * @since 1.4.3
		 * @return void
		 */
		public function include_configurations() {
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/class-astra-customizer-config-base.php';

			/**
			 * Register Sections & Panels
			 */
			require ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer-register-sections-panels.php';

			require ASTRA_THEME_DIR . 'inc/customizer/configurations/buttons/class-astra-customizer-button-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-site-layout-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-header-layout-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-site-identity-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-blog-layout-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-blog-single-layout-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-sidebar-layout-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-site-container-layout-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-footer-layout-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/colors-background/class-astra-body-colors-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/colors-background/class-astra-footer-colors-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/colors-background/class-astra-advanced-footer-colors-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/typography/class-astra-archive-typo-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/typography/class-astra-body-typo-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/typography/class-astra-content-typo-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/typography/class-astra-header-typo-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/typography/class-astra-single-typo-configs.php';

		}

		/**
		 * Print Footer Scripts
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function print_footer_scripts() {
			$output  = '<script type="text/javascript">';
			$output .= '
	        	wp.customize.bind(\'ready\', function() {
	            	wp.customize.control.each(function(ctrl, i) {
	                	var desc = ctrl.container.find(".customize-control-description");
	                	if( desc.length) {
	                    	var title 		= ctrl.container.find(".customize-control-title");
	                    	var li_wrapper 	= desc.closest("li");
	                    	var tooltip = desc.text().replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
	                    			return \'&#\'+i.charCodeAt(0)+\';\';
								});
	                    	desc.remove();
	                    	li_wrapper.append(" <i class=\'ast-control-tooltip dashicons dashicons-editor-help\'title=\'" + tooltip +"\'></i>");
	                	}
	            	});
	        	});';

			$output .= Astra_Fonts_Data::js();
			$output .= '</script>';

			echo $output;
		}

		/**
		 * Register custom section and panel.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function customize_register_panel( $wp_customize ) {

			/**
			 * Register Extended Panel
			 */
			$wp_customize->register_panel_type( 'Astra_WP_Customize_Panel' );
			$wp_customize->register_section_type( 'Astra_WP_Customize_Section' );

			if ( ! defined( 'ASTRA_EXT_VER' ) ) {
				$wp_customize->register_section_type( 'Astra_Pro_Customizer' );
			}

			require ASTRA_THEME_DIR . 'inc/customizer/extend-customizer/class-astra-wp-customize-panel.php';
			require ASTRA_THEME_DIR . 'inc/customizer/extend-customizer/class-astra-wp-customize-section.php';
			require ASTRA_THEME_DIR . 'inc/customizer/customizer-controls.php';

			/**
			 * Add Controls
			 */

			Astra_Customizer_Control_Base::add_control(
				'color',
				array(
					'callback'          => 'WP_Customize_Color_Control',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'ast-sortable',
				array(
					'callback'          => 'Astra_Control_Sortable',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_multi_choices' ),
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'ast-radio-image',
				array(
					'callback'          => 'Astra_Control_Radio_Image',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'ast-slider',
				array(
					'callback'          => 'Astra_Control_Slider',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'ast-responsive-slider',
				array(
					'callback'          => 'Astra_Control_Responsive_Slider',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'ast-responsive',
				array(
					'callback'          => 'Astra_Control_Responsive',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'ast-responsive-spacing',
				array(
					'callback'          => 'Astra_Control_Responsive_Spacing',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'ast-divider',
				array(
					'callback'          => 'Astra_Control_Divider',
					'sanitize_callback' => '',
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'ast-heading',
				array(
					'callback'          => 'Astra_Control_Heading',
					'sanitize_callback' => '',
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'ast-color',
				array(
					'callback'          => 'Astra_Control_Color',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'ast-description',
				array(
					'callback'          => 'Astra_Control_Description',
					'sanitize_callback' => '',
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'ast-background',
				array(
					'callback'          => 'Astra_Control_Background',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_background_obj' ),
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'image',
				array(
					'callback'          => 'WP_Customize_Image_Control',
					'sanitize_callback' => 'esc_url_raw',
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'ast-font',
				array(
					'callback'          => 'Astra_Control_Typography',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'number',
				array(
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
				)
			);
			Astra_Customizer_Control_Base::add_control(
				'ast-border',
				array(
					'callback'         => 'Astra_Control_Border',
					'santize_callback' => 'sanitize_border',
				)
			);

			/**
			 * Helper files
			 */
			require ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer-partials.php';
			require ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer-callback.php';
			require ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer-sanitizes.php';
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function customize_register( $wp_customize ) {

			/**
			 * Override Defaults
			 */
			require ASTRA_THEME_DIR . 'inc/customizer/override-defaults.php';

		}

		/**
		 * Add upgrade link configurations controls.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function astra_pro_upgrade_configurations( $wp_customize ) {

			if ( ! defined( 'ASTRA_EXT_VER' ) ) {
				require ASTRA_THEME_DIR . 'inc/customizer/astra-pro/class-astra-pro-customizer.php';
				require ASTRA_THEME_DIR . 'inc/customizer/astra-pro/class-astra-pro-upgrade-link-configs.php';
			}
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

			if ( is_rtl() ) {
				$css_prefix = '-rtl.min.css';
				if ( SCRIPT_DEBUG ) {
					$css_prefix = '-rtl.css';
				}
			}

			// Customizer Core.
			wp_enqueue_script( 'astra-customizer-controls-toggle-js', ASTRA_THEME_URI . 'assets/js/' . $dir . '/customizer-controls-toggle' . $js_prefix, array(), ASTRA_THEME_VERSION, true );

			// Extended Customizer Assets - Panel extended.
			wp_enqueue_style( 'astra-extend-customizer-css', ASTRA_THEME_URI . 'assets/css/' . $dir . '/extend-customizer' . $css_prefix, null, ASTRA_THEME_VERSION );
			wp_enqueue_script( 'astra-extend-customizer-js', ASTRA_THEME_URI . 'assets/js/' . $dir . '/extend-customizer' . $js_prefix, array(), ASTRA_THEME_VERSION, true );

			wp_enqueue_script( 'customizer-dependency', ASTRA_THEME_URI . 'assets/js/' . $dir . '/customizer-dependency' . $js_prefix, array( 'astra-customizer-controls-js' ), ASTRA_THEME_VERSION, true );

			// Customizer Controls.
			wp_enqueue_style( 'astra-customizer-controls-css', ASTRA_THEME_URI . 'assets/css/' . $dir . '/customizer-controls' . $css_prefix, null, ASTRA_THEME_VERSION );
			wp_enqueue_script( 'astra-customizer-controls-js', ASTRA_THEME_URI . 'assets/js/' . $dir . '/customizer-controls' . $js_prefix, array( 'astra-customizer-controls-toggle-js' ), ASTRA_THEME_VERSION, true );

			wp_localize_script(
				'astra-customizer-controls-toggle-js',
				'astra',
				apply_filters(
					'astra_theme_customizer_js_localize',
					array(
						'customizer' => array(
							'settings' => array(
								'sidebars'  => array(
									'single'  => array(
										'single-post-sidebar-layout',
										'single-page-sidebar-layout',
									),
									'archive' => array(
										'archive-post-sidebar-layout',
									),
								),
								'container' => array(
									'single'  => array(
										'single-post-content-layout',
										'single-page-content-layout',
									),
									'archive' => array(
										'archive-post-content-layout',
									),
								),
							),
						),
						'theme'      => array(
							'option' => ASTRA_THEME_SETTINGS,
						),
						'config'     => $this->get_dependency_arr(),
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

			wp_register_script( 'astra-customizer-preview-js', ASTRA_THEME_URI . 'assets/js/' . $dir . '/customizer-preview' . $js_prefix, array( 'customize-preview' ), null, ASTRA_THEME_VERSION );

			$localize_array = array(
				'headerBreakpoint'            => astra_header_break_point(),
				'includeAnchorsInHeadindsCss' => Astra_Dynamic_CSS::anchors_in_css_selectors_heading(),
			);

			wp_localize_script( 'astra-customizer-preview-js', 'astraCustomizer', $localize_array );
			wp_enqueue_script( 'astra-customizer-preview-js' );
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

			/* Generate Header Logo */
			$custom_logo_id = get_theme_mod( 'custom_logo' );

			add_filter( 'intermediate_image_sizes_advanced', 'Astra_Customizer::logo_image_sizes', 10, 2 );
			Astra_Customizer::generate_logo_by_width( $custom_logo_id );
			remove_filter( 'intermediate_image_sizes_advanced', 'Astra_Customizer::logo_image_sizes', 10 );

			do_action( 'astra_customizer_save' );
		}

		/**
		 * Add logo image sizes in filter.
		 *
		 * @since 1.0.0
		 * @param array $sizes Sizes.
		 * @param array $metadata attachment data.
		 *
		 * @return array
		 */
		public static function logo_image_sizes( $sizes, $metadata ) {

			$logo_width = astra_get_option( 'ast-header-responsive-logo-width' );

			if ( is_array( $sizes ) && '' != $logo_width['desktop'] ) {
				$max_value              = max( $logo_width );
				$sizes['ast-logo-size'] = array(
					'width'  => (int) $max_value,
					'height' => 0,
					'crop'   => false,
				);
			}

			return $sizes;
		}

		/**
		 * Generate logo image by its width.
		 *
		 * @since 1.0.0
		 * @param int $custom_logo_id Logo id.
		 */
		public static function generate_logo_by_width( $custom_logo_id ) {
			if ( $custom_logo_id ) {

				$image = get_post( $custom_logo_id );

				if ( $image ) {
					$fullsizepath = get_attached_file( $image->ID );

					if ( false !== $fullsizepath || file_exists( $fullsizepath ) ) {

						if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
							require_once ABSPATH . 'wp-admin/includes/image.php';
						}

						$metadata = wp_generate_attachment_metadata( $image->ID, $fullsizepath );

						if ( ! is_wp_error( $metadata ) && ! empty( $metadata ) ) {
							wp_update_attachment_metadata( $image->ID, $metadata );
						}
					}
				}
			}
		}
	}
}

/**
 *  Kicking this off by calling 'get_instance()' method
 */
Astra_Customizer::get_instance();
