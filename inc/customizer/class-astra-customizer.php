<?php
/**
 * Astra Theme Customizer
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2019, Astra
 * @link        https://wpastra.com/
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
		 * Customizer controls data.
		 *
		 * @access Public
		 * @since 2.0.0
		 * @var Array
		 */
		public $control_types = array();

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

						if ( 'ast-settings-group' == $config['control'] ) {

							// Get Child controls for group control.
							$config_obj = wp_filter_object_list(
								$configurations,
								array(
									'parent' => astra_get_prop( $config, 'name' ),
								)
							);

							$control_defaults = array();

							foreach ( $config_obj as $sub_control ) {
								if ( isset( $sub_control['default'] ) ) {
									$control_defaults[ $sub_control['name'] ] = $sub_control['default'];

									$control_type = $sub_control['control'];

									if ( ! in_array( $control_type, $this->control_types ) && $this->starts_with( $control_type, 'ast-' ) ) {
										$this->control_types[] = $control_type;
									}
								}
							}

							// Sort them according to priority.
							$config_sorted = wp_list_sort( $config_obj, 'priority' );

							$tabs_data = array();

							foreach ( $config_sorted as $config_value ) {

								if ( isset( $config_value['tab'] ) ) {
									$tabs_data[ $config_value['tab'] ][] = $config_value;
								}
							}

							if ( ! empty( $tabs_data ) ) {
								$config_sorted         = array();
								$config_sorted['tabs'] = $tabs_data;
							}

							$config['ast_fields'] = $config_sorted;
							$config['default']    = $control_defaults;

						}

						$this->register_setting_control( $config, $wp_customize );

						break;
				}
			}
		}

		/**
		 * Check if string is start with a string provided.
		 *
		 * @param string $string main string.
		 * @param string $start_string string to search.
		 * @since 2.0.0
		 * @return bool.
		 */
		function starts_with( $string, $start_string ) {
			$len = strlen( $start_string );
			return ( substr( $string, 0, $len ) === $start_string );
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
					'ast_fields'           => array(),
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
			$wp_customize->add_panel( new Astra_WP_Customize_Panel( $wp_customize, astra_get_prop( $config, 'name' ), $config ) );
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

			$callback = astra_get_prop( $config, 'section_callback', 'Astra_WP_Customize_Section' );

			$wp_customize->add_section( new $callback( $wp_customize, astra_get_prop( $config, 'name' ), $config ) );
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
				astra_get_prop( $config, 'name' ),
				array(
					'default'           => astra_get_prop( $config, 'default' ),
					'type'              => astra_get_prop( $config, 'datastore_type' ),
					'transport'         => astra_get_prop( $config, 'transport', 'refresh' ),
					'sanitize_callback' => astra_get_prop( $config, 'sanitize_callback', Astra_Customizer_Control_Base::get_sanitize_call( astra_get_prop( $config, 'control' ) ) ),
				)
			);

			$instance = Astra_Customizer_Control_Base::get_control_instance( astra_get_prop( $config, 'control' ) );

			$config['label'] = astra_get_prop( $config, 'title' );
			$config['type']  = astra_get_prop( $config, 'control' );

			// For ast-font control font-weight and font-family is passed as param `font-type` which needs to be converted to `type`.
			if ( false !== astra_get_prop( $config, 'font-type', false ) ) {
				$config['type'] = astra_get_prop( $config, 'font-type', false );
			}

			if ( false !== $instance ) {
				$wp_customize->add_control(
					new $instance( $wp_customize, astra_get_prop( $config, 'name' ), $config )
				);
			} else {
				$wp_customize->add_control( astra_get_prop( $config, 'name' ), $config );
			}

			if ( astra_get_prop( $config, 'partial', false ) ) {

				if ( isset( $wp_customize->selective_refresh ) ) {
					$wp_customize->selective_refresh->add_partial(
						astra_get_prop( $config, 'name' ),
						array(
							'selector'            => astra_get_prop( $config['partial'], 'selector' ),
							'container_inclusive' => astra_get_prop( $config['partial'], 'container_inclusive' ),
							'render_callback'     => astra_get_prop( $config['partial'], 'render_callback' ),
						)
					);
				}
			}

			if ( false !== astra_get_prop( $config, 'required', false ) ) {
				$this->update_dependency_arr( astra_get_prop( $config, 'name' ), astra_get_prop( $config, 'required' ) );
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
			require ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer-register-notices-configs.php';

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

			Astra_Customizer_Control_Base::add_control(
				'ast-responsive-color',
				array(
					'callback'         => 'Astra_Control_Responsive_Color',
					'santize_callback' => 'sanitize_responsive_color',
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'ast-customizer-link',
				array(
					'callback'         => 'Astra_Control_Customizer_Link',
					'santize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_customizer_links' ),
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'ast-settings-group',
				array(
					'callback'          => 'Astra_Control_Settings_Group',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_customizer_settings_group' ),
				)
			);

			Astra_Customizer_Control_Base::add_control(
				'ast-select',
				array(
					'callback'          => 'Astra_Control_Select',
					'sanitize_callback' => '',
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

			wp_enqueue_script( 'astra-customizer-dependency', ASTRA_THEME_URI . 'assets/js/' . $dir . '/customizer-dependency' . $js_prefix, array( 'astra-customizer-controls-js' ), ASTRA_THEME_VERSION, true );

			// Customizer Controls.
			wp_enqueue_style( 'astra-customizer-controls-css', ASTRA_THEME_URI . 'assets/css/' . $dir . '/customizer-controls' . $css_prefix, null, ASTRA_THEME_VERSION );
			wp_enqueue_script( 'astra-customizer-controls-js', ASTRA_THEME_URI . 'assets/js/' . $dir . '/customizer-controls' . $js_prefix, array( 'astra-customizer-controls-toggle-js' ), ASTRA_THEME_VERSION, true );

			$google_fonts = Astra_Font_Families::get_google_fonts();
			$string       = $this->generate_font_dropdown();

			$control_types_data = self::get_controls_data();

			if ( ! empty( $this->control_types ) ) {

				foreach ( $this->control_types as $control ) {

					$uri                     = '';
					$control_data            = $control_types_data[ $control ];
					$control_data_css        = $control_data['css'];
					$control_data_js         = $control_data['js'];
					$control_data_dependency = $control_data['dependency'];

					if ( ! is_array( $control_data_css ) ) {
						if ( isset( $control_data['type'] ) && 'addon' == $control_data['type'] && defined( 'ASTRA_EXT_URI' ) ) {
							$uri = ASTRA_EXT_URI . 'classes/customizer/controls/' . $control_data_css . '/';
						} else {
							$uri = ASTRA_THEME_URI . 'inc/customizer/custom-controls/' . $control_data_css . '/';
						}
					}

					if ( is_array( $control_data_css ) ) {
						foreach ( $control_data_css as $control_slug ) {

							$slug  = ( isset( $control_data['slug']['css'][ $control_slug ] ) ) ? $control_data['slug']['css'][ $control_slug ] : $control_slug;
							$title = ( isset( $control_data['title'] ) ) ? $control_data['title'] : $control_data_css;
							$uri   = ASTRA_THEME_URI . 'inc/customizer/custom-controls/' . $title . '/';

							wp_enqueue_style( $slug, $uri . $slug . '.css', null, ASTRA_THEME_VERSION );
						}
					} elseif ( '' !== $control_data_css ) {
						wp_enqueue_style( $control, $uri . $control_data_css . '.css', null, ASTRA_THEME_VERSION );
					}

					if ( is_array( $control_data_js ) ) {
						foreach ( $control_data_js as $control_slug ) {

							$slug  = ( isset( $control_data['slug']['js'][ $control_slug ] ) ) ? $control_data['slug']['js'][ $control_slug ] : $control_slug;
							$title = ( isset( $control_data['title'] ) ) ? $control_data['title'] : $control_data_js;
							$uri   = ASTRA_THEME_URI . 'inc/customizer/custom-controls/' . $title . '/';

							wp_enqueue_script( $slug, $uri . $slug . '.js', $control_data_dependency, ASTRA_THEME_VERSION, true );
						}
					} elseif ( '' !== $control_data_js ) {
						wp_enqueue_script( $control_data_js, $uri . $control_data_js . '.js', $control_data_dependency, ASTRA_THEME_VERSION, true );
					}
				}
			}

			$tmpl = '<div class="ast-field-settings-modal">
					<ul class="ast-fields-wrap">
					</ul>
			</div>';

			wp_localize_script(
				'astra-customizer-controls-toggle-js',
				'astra',
				apply_filters(
					'astra_theme_customizer_js_localize',
					array(
						'customizer' => array(
							'settings'         => array(
								'sidebars'     => array(
									'single'  => array(
										'single-post-sidebar-layout',
										'single-page-sidebar-layout',
									),
									'archive' => array(
										'archive-post-sidebar-layout',
									),
								),
								'container'    => array(
									'single'  => array(
										'single-post-content-layout',
										'single-page-content-layout',
									),
									'archive' => array(
										'archive-post-content-layout',
									),
								),
								'google_fonts' => $string,
							),
							'group_modal_tmpl' => $tmpl,
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
		 * Generates HTML for font dropdown.
		 *
		 * @return string
		 */
		public static function get_controls_data() {

			$control = array(
				'ast-background'            => array(
					'css'        => 'background',
					'js'         => 'background',
					'dependency' => array(),
				),
				'ast-border'                => array(
					'css'        => 'border',
					'js'         => 'border',
					'dependency' => array( 'jquery', 'customize-base' ),
				),
				'ast-color'                 => array(
					'css'        => 'color',
					'js'         => 'color',
					'dependency' => array( 'astra-color-alpha' ),
				),
				'ast-customizer-link'       => array(
					'css'        => 'customizer-link',
					'js'         => 'customizer-link',
					'dependency' => array( 'jquery', 'customize-base' ),
				),
				'ast-description'           => array(
					'css'        => 'description',
					'js'         => '',
					'dependency' => array(),
				),
				'ast-divider'               => array(
					'css'        => 'divider',
					'js'         => '',
					'dependency' => array(),
				),
				'ast-heading'               => array(
					'css'        => 'heading',
					'js'         => '',
					'dependency' => array(),
				),
				'ast-radio-image'           => array(
					'css'        => 'radio-image',
					'js'         => 'radio-image',
					'dependency' => array( 'jquery', 'customize-base' ),
				),
				'ast-responsive'            => array(
					'css'        => 'responsive',
					'js'         => 'responsive',
					'dependency' => array( 'jquery', 'customize-base' ),
				),
				'ast-responsive-color'      => array(
					'css'        => 'responsive-color',
					'js'         => 'responsive-color',
					'dependency' => array( 'astra-color-alpha' ),
				),
				'ast-responsive-slider'     => array(
					'css'        => 'responsive-slider',
					'js'         => 'responsive-slider',
					'dependency' => array( 'jquery', 'customize-base' ),
				),
				'ast-responsive-spacing'    => array(
					'css'        => 'responsive-spacing',
					'js'         => 'responsive-spacing',
					'dependency' => array( 'jquery', 'customize-base' ),
				),
				'ast-select'                => array(
					'css'        => '',
					'js'         => '',
					'dependency' => array(),
				),
				'ast-settings-group'        => array(
					'css'        => 'settings-group',
					'js'         => 'settings-group',
					'dependency' => array( 'jquery', 'jquery-ui-tabs', 'customize-base' ),
				),
				'ast-slider'                => array(
					'css'        => 'slider',
					'js'         => 'slider',
					'dependency' => array( 'jquery', 'customize-base' ),
				),
				'ast-sortable'              => array(
					'css'        => 'sortable',
					'js'         => 'sortable',
					'dependency' => array( 'jquery', 'customize-base', 'jquery-ui-core', 'jquery-ui-sortable' ),
				),
				'ast-spacing'               => array(
					'css'        => 'spacing',
					'js'         => 'spacing',
					'dependency' => array( 'jquery', 'customize-base' ),
				),
				'ast-font'                  => array(
					'css'        => array( 'selectWoo', 'typography' ),
					'js'         => array( 'selectWoo', 'typography' ),
					'dependency' => array( 'jquery', 'customize-base' ),
					'title'      => 'typography',
					'slug'       => array(
						'css' => array(
							'selectWoo'  => 'astra-select-woo-style',
							'typography' => 'astra-typography-style',
						),
						'js'  => array(
							'selectWoo'  => 'astra-select-woo-script',
							'typography' => 'astra-typography',
						),
					),
				),
				'ast-responsive-background' => array(
					'css'        => 'responsive-background',
					'js'         => 'responsive-background',
					'dependency' => array( 'astra-color-alpha' ),
					'type'       => 'addon',
				),
			);

			return $control;
		}

		/**
		 * Generates HTML for font dropdown.
		 *
		 * @return string
		 */
		function generate_font_dropdown() {

			$string = '';

			$string .= '<option value="inherit">' . __( 'Inherit', 'astra' ) . '</option>';
			$string .= '<optgroup label="Other System Fonts">';

			$system_fonts = Astra_Font_Families::get_system_fonts();
			$google_fonts = Astra_Font_Families::get_google_fonts();

			foreach ( $system_fonts as $name => $variants ) {
				$string .= '<option value="' . esc_attr( $name ) . '" >' . esc_attr( $name ) . '</option>';
			}

			$string .= '<optgroup label="Google">';

			foreach ( $google_fonts as $name => $single_font ) {
				$variants = astra_get_prop( $single_font, '0' );
				$category = astra_get_prop( $single_font, '1' );
				$string  .= '<option value="\'' . esc_attr( $name ) . '\', ' . esc_attr( $category ) . '" ' . '>' . esc_attr( $name ) . '</option>';
			}

			return $string;
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

			wp_enqueue_script( 'astra-customizer-preview-js', ASTRA_THEME_URI . 'assets/js/' . $dir . '/customizer-preview' . $js_prefix, array( 'customize-preview' ), ASTRA_THEME_VERSION, null );

			$localize_array = array(
				'headerBreakpoint'            => astra_header_break_point(),
				'includeAnchorsInHeadindsCss' => Astra_Dynamic_CSS::anchors_in_css_selectors_heading(),
				'googleFonts'                 => Astra_Font_Families::get_google_fonts(),
			);

			wp_localize_script( 'astra-customizer-preview-js', 'astraCustomizer', $localize_array );
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

			if ( apply_filters( 'astra_resize_logo', true ) ) {

				/* Generate Header Logo */
				$custom_logo_id = get_theme_mod( 'custom_logo' );

				add_filter( 'intermediate_image_sizes_advanced', 'Astra_Customizer::logo_image_sizes', 10, 2 );
				Astra_Customizer::generate_logo_by_width( $custom_logo_id );
				remove_filter( 'intermediate_image_sizes_advanced', 'Astra_Customizer::logo_image_sizes', 10 );

			} else {
				// Regenerate the logo without custom image sizes.
				$custom_logo_id = get_theme_mod( 'custom_logo' );
				Astra_Customizer::generate_logo_by_width( $custom_logo_id );
			}

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
