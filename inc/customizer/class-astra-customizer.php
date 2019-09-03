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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

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
		 * All groups parent-child relation array data.
		 *
		 * @access Public
		 * @since 2.0.0
		 * @var Array
		 */
		public static $group_configs = array();

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

					case 'sub-control':
						// Remove type from configuration.
						unset( $config['type'] );

						$this->register_sub_control_setting( $config, $wp_customize );

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
		 * @param Array                $control_config Panel Configuration settings.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 2.0.0
		 * @return void
		 */
		private function register_sub_control_setting( $control_config, $wp_customize ) {

			$sub_control_name = ASTRA_THEME_SETTINGS . '[' . astra_get_prop( $control_config, 'name' ) . ']';

			$parent = astra_get_prop( $control_config, 'parent' );
			$tab    = astra_get_prop( $control_config, 'tab' );

			if ( empty( self::$group_configs[ $parent ] ) ) {
				self::$group_configs[ $parent ] = array();
			}

			if ( array_key_exists( 'tab', $control_config ) ) {
				self::$group_configs[ $parent ]['tabs'][ $tab ][] = $control_config;
			} else {
				self::$group_configs[ $parent ][] = $control_config;
			}

			$config = array(
				'name'              => $sub_control_name,
				'datastore_type'    => 'option',
				'transport'         => 'postMessage',
				'control'           => 'ast-hidden',
				'section'           => astra_get_prop( $control_config, 'section', 'title_tagline' ),
				'default'           => astra_get_prop( $control_config, 'default' ),
				'sanitize_callback' => astra_get_prop( $control_config, 'sanitize_callback', Astra_Customizer_Control_Base::get_sanitize_call( astra_get_prop( $control_config, 'control' ) ) ),
			);

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

			if ( false !== $instance ) {
				$wp_customize->add_control(
					new $instance( $wp_customize, $sub_control_name, $config )
				);
			} else {
				$wp_customize->add_control( $sub_control_name, $config );
			}
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

			if ( 'ast-settings-group' === $config['control'] ) {
				$callback = false;
			} else {
				$callback = astra_get_prop( $config, 'sanitize_callback', Astra_Customizer_Control_Base::get_sanitize_call( astra_get_prop( $config, 'control' ) ) );
			}

			$wp_customize->add_setting(
				astra_get_prop( $config, 'name' ),
				array(
					'default'           => astra_get_prop( $config, 'default' ),
					'type'              => astra_get_prop( $config, 'datastore_type' ),
					'transport'         => astra_get_prop( $config, 'transport', 'refresh' ),
					'sanitize_callback' => $callback,
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
			$wp_customize->register_section_type( 'Astra_WP_Customize_Separator' );

			if ( ! defined( 'ASTRA_EXT_VER' ) ) {
				$wp_customize->register_section_type( 'Astra_Pro_Customizer' );
			}

			require ASTRA_THEME_DIR . 'inc/customizer/extend-customizer/class-astra-wp-customize-panel.php';
			require ASTRA_THEME_DIR . 'inc/customizer/extend-customizer/class-astra-wp-customize-section.php';
			require ASTRA_THEME_DIR . 'inc/customizer/extend-customizer/class-astra-wp-customize-separator.php';
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
				'ast-hidden',
				array(
					'callback'          => 'Astra_Control_Hidden',
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
					'callback' => 'Astra_Control_Settings_Group',
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
				$css_prefix = '.min-rtl.css';
				if ( SCRIPT_DEBUG ) {
					$css_prefix = '-rtl.css';
				}
			}

			wp_enqueue_script( 'astra-color-alpha' );

			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style( 'thickbox' );

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
		function generate_font_dropdown() {

			ob_start();

			?>

			<option value="inherit"><?php _e( 'Default System Font', 'astra' ); ?></option>
			<optgroup label="Other System Fonts">

			<?php

			$system_fonts = Astra_Font_Families::get_system_fonts();
			$google_fonts = Astra_Font_Families::get_google_fonts();

			foreach ( $system_fonts as $name => $variants ) {
				?>

				<option value="<?php echo esc_attr( $name ); ?>" ><?php echo esc_attr( $name ); ?></option>
				<?php
			}

			// Add Custom Font List Into Customizer.
			do_action( 'astra_customizer_font_list', '' );

			?>
			<optgroup label="Google">

			<?php
			foreach ( $google_fonts as $name => $single_font ) {
				$variants = astra_get_prop( $single_font, '0' );
				$category = astra_get_prop( $single_font, '1' );

				?>
				<option value="<?php echo "'" . esc_attr( $name ) . "', " . esc_attr( $category ); ?>"><?php echo esc_attr( $name ); ?></option>

				<?php
			}

			return ob_get_clean();
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
