<?php
/**
 * Astra Theme Customizer
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
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
		 * Contexts.
		 *
		 * @access private
		 * @var object
		 */
		private static $contexts;

		/**
		 * Dynamic options.
		 *
		 * @since 3.1.0
		 * @access private
		 * @var object
		 */
		private static $dynamic_options = array();

		/**
		 * Tabful sections.
		 *
		 * @access private
		 * @var object
		 */
		private static $tabbed_sections = array();

		/**
		 * Choices.
		 *
		 * @access private
		 * @var object
		 */
		private static $choices;

		/**
		 * JS Configs.
		 *
		 * @access private
		 * @var object
		 */
		private static $js_configs;

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
				add_action( 'customize_register', array( $this, 'prepare_customizer_javascript_configs' ) );
				add_action( 'customize_register', array( $this, 'astra_pro_upgrade_configurations' ), 2 );
				add_action( 'customize_register', array( $this, 'prepare_group_configs' ), 9 );

				add_filter( 'customize_dynamic_setting_args', array( $this, 'filter_dynamic_setting_args' ), 10, 2 );
				add_filter( 'customize_dynamic_partial_args', array( $this, 'filter_dynamic_partial_args' ), 10, 2 );

			}

			add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_scripts' ) );
			add_filter( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_customizer_scripts' ), 999 );

			add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_footer_scripts' ) );


			add_action( 'customize_register', array( $this, 'customize_register_panel' ), 2 );
			add_action( 'customize_register', array( $this, 'customize_register' ) );
			add_action( 'customize_save_after', array( $this, 'customize_save' ) );
			add_action( 'customize_save_after', array( $this, 'delete_cached_partials' ) );
			add_action( 'wp_head', array( $this, 'preview_styles' ) );
		}

		/**
		 *  Delete the cached partial configs.
		 */
		public function delete_cached_partials() {
			delete_option( 'astra_partials_config_cache' );
		}

		/**
		 * Add dynamic control partial refresh.
		 *
		 * @since 3.1.0
		 * @param array  $partial_args partial configs.
		 * @param string $partial_id partial id.
		 * @return array|mixed
		 */
		public function filter_dynamic_partial_args( $partial_args, $partial_id ) {

			if ( isset( self::$dynamic_options['partials'][ $partial_id ] ) ) {
				if ( false === $partial_args ) {
					$partial_args = array();
				}
				$partial_args = array_merge( $partial_args, self::$dynamic_options['partials'][ $partial_id ] );
			}

			return $partial_args;

		}


		/**
		 * Add dynamic control settings.
		 *
		 * @since 3.1.0
		 * @param array  $setting_args setting configs.
		 * @param string $setting_id setting id.
		 * @return mixed
		 */
		public function filter_dynamic_setting_args( $setting_args, $setting_id ) {

			if ( isset( self::$dynamic_options['settings'][ $setting_id ] ) ) {
				return self::$dynamic_options['settings'][ $setting_id ];
			}

			return $setting_args;
		}

			/**
			 * Prepare Contexts and choices.
			 *
			 * @since 3.0.0
			 */
		public function prepare_customizer_javascript_configs() {

			global $wp_customize;

			$cached_data = get_option( 'astra_partials_config_cache', false );

			if ( $wp_customize->selective_refresh->is_render_partials_request() && $cached_data ) {
				self::$dynamic_options = $cached_data;
				return;
			}

			$configurations = $this->get_customizer_configurations();

			$defaults = $this->get_astra_customizer_configuration_defaults();

			foreach ( $configurations as $key => $configuration ) {

				$config = wp_parse_args( $configuration, $defaults );

				if ( isset( $configuration['context'] ) ) {
					self::$contexts[ $configuration['name'] ] = $configuration['context'];
				} else {
					if ( isset( $configuration['type'] ) && ( ( 'control' === $configuration['type'] ) || ( 'sub-control' === $configuration['type'] ) ) ) {
						if ( ( isset( $configuration['control'] ) && 'ast-builder-header-control' !== $configuration['control'] ) && ( isset( $configuration['name'] ) && strpos( $configuration['name'], 'ast-callback-notice' ) === false ) ) {
							self::$contexts[ $configuration['name'] ] = Astra_Builder_Helper::$general_tab;
						}
					}
				}

				if ( isset( $configuration['choices'] ) ) {
					self::$choices[ $configuration['name'] ] = $configuration['choices'];
				}

				switch ( $config['type'] ) {

					case 'panel':
						$this->prepare_javascript_panel_configs( $config );
						break;
					case 'section':
						$this->prepare_javascript_section_configs( $config );
						break;

					case 'sub-control':
						$this->prepare_javascript_sub_control_configs( $config );
						break;
					case 'control':
						$this->prepare_javascript_control_configs( $config );
						break;
				}
			}

			update_option( 'astra_partials_config_cache', self::$dynamic_options, false );

		}

		/**
		 * Get control default.
		 *
		 * @param string $setting_key setting key.
		 * @param array  $default_values default value array.
		 * @return mixed|string
		 */
		private function get_default_value( $setting_key, $default_values ) {
			$return = '';
			preg_match( '#astra-settings\[(.*?)\]#', $setting_key, $match );
			if ( ! empty( $match ) && isset( $match[1] ) ) {
				$return = isset( $default_values[ $match[1] ] ) ? $default_values[ $match[1] ] : '';
			}
			return $return;
		}

		/**
		 * Prepare tabbed sections for dynamic controls to optimize frontend JS calls.
		 */
		private static function prepare_tabbed_sections() {

			if ( ! isset( self::$js_configs['controls'] ) ) {
				return;
			}

			foreach ( self::$js_configs['controls'] as $section_id => $controls ) {
				$tab_id        = $section_id . '-ast-context-tabs';
				$control_names = wp_list_pluck( $controls, 'name' );
				if ( in_array( $tab_id, $control_names, true ) ) {
					array_push( self::$tabbed_sections, $section_id );
				}
			}

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

			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 *  Set default context for WP default controls.
		 */
		private static function set_default_context() {

			if ( false === Astra_Builder_Helper::$is_header_footer_builder_active ) {
				return;
			}

			self::$contexts['blogname'] = array(
				Astra_Builder_Helper::$general_tab_config,
				array(
					'setting'  => ASTRA_THEME_SETTINGS . '[display-site-title]',
					'operator' => '==',
					'value'    => true,
				),

			);

			self::$contexts['blogdescription'] = array(
				Astra_Builder_Helper::$general_tab_config,
				array(
					'setting'  => ASTRA_THEME_SETTINGS . '[display-site-tagline]',
					'operator' => '==',
					'value'    => true,
				),

			);

		}

		/**
		 * Bypass JS configs for Controls.
		 *
		 * @param array $configuration configuration.
		 */
		public static function bypass_control_configs( $configuration ) {

			$val = '';

			if ( isset( $configuration['name'] ) ) {

				$data = explode( '[', rtrim( $configuration['name'], ']' ) );

				if ( isset( $data[1] ) ) {
					$val = astra_get_option( $data[1] );
				}
			}

			if ( isset( $val ) && ! empty( $val ) ) {

				$configuration['value'] = $val;
			}

			switch ( $configuration['type'] ) {

				case 'ast-builder':
					if ( is_array( $configuration['default'] ) && ! isset( $configuration['default']['popup'] ) ) {
						$configuration['default']['popup'] = array( 'popup_content' => array() );
					}
					break;
				case 'ast-responsive-spacing':
					if ( ! is_array( $val ) || is_numeric( $val ) ) {

						$configuration['value'] = array(
							'desktop'      => array(
								'top'    => $val,
								'right'  => '',
								'bottom' => $val,
								'left'   => '',
							),
							'tablet'       => array(
								'top'    => $val,
								'right'  => '',
								'bottom' => $val,
								'left'   => '',
							),
							'mobile'       => array(
								'top'    => $val,
								'right'  => '',
								'bottom' => $val,
								'left'   => '',
							),
							'desktop-unit' => 'px',
							'tablet-unit'  => 'px',
							'mobile-unit'  => 'px',
						);
					}

					break;
				case 'ast-radio-image':
					$configuration['value'] = $val;

					if ( isset( $configuration['choices'] ) && is_array( $configuration['choices'] ) ) {

						foreach ( $configuration['choices'] as $key => $value ) {
							$configuration['choices'][ $key ]        = $value['path'];
							$configuration['choices_titles'][ $key ] = $value['label'];
						}
					}
					if ( isset( $configuration['input_attrs'] ) ) {

						$configuration['inputAttrs'] = '';
						$configuration['labelStyle'] = '';
						foreach ( $configuration['input_attrs'] as $attr => $value ) {
							if ( 'style' !== $attr ) {
								$configuration['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
							} else {
								$configuration['labelStyle'] = 'style="' . esc_attr( $value ) . '" ';
							}
						}
					}
					break;
				case 'ast-border':
					$configuration['value'] = $val;

					break;
				case 'ast-responsive-slider':
					if ( ! is_array( $val ) || is_numeric( $val ) ) {

						$configuration['value'] = array(
							'desktop' => $val,
							'tablet'  => '',
							'mobile'  => '',
						);
					}
					break;
				case 'ast-responsive-background':
					$configuration['value'] = $val;

					break;
				case 'ast-responsive':
					if ( ! is_array( $val ) || is_numeric( $val ) ) {

						$configuration['value'] = array(
							'desktop'      => $val,
							'tablet'       => '',
							'mobile'       => '',
							'desktop-unit' => '',
							'tablet-unit'  => '',
							'mobile-unit'  => '',
						);
					}
					break;
				case 'ast-link':
					$configuration['value'] = $val;

					break;
				case 'ast-hidden':
					$configuration['value'] = $val;

					break;
				case 'ast-settings-group':
					$config = array();

					if ( isset( self::$group_configs[ $configuration['name'] ]['tabs'] ) ) {
						$tab = array_keys( self::$group_configs[ $configuration['name'] ]['tabs'] );
						rsort( $tab );
						foreach ( $tab as $key => $value ) {

							$config['tabs'][ $value ] = wp_list_sort( self::$group_configs[ $configuration['name'] ]['tabs'][ $value ], 'priority' );
						}
					} else {
						if ( isset( self::$group_configs[ $configuration['name'] ] ) ) {
							$config = wp_list_sort( self::$group_configs[ $configuration['name'] ], 'priority' );
						}
					}
					$configuration['ast_fields'] = $config;
					break;
				case 'ast-font-weight':
					$configuration['ast_all_font_weight'] = array(
						'100'       => __( 'Thin 100', 'astra' ),
						'100italic' => __( '100 Italic', 'astra' ),
						'200'       => __( 'Extra-Light 200', 'astra' ),
						'200italic' => __( '200 Italic', 'astra' ),
						'300'       => __( 'Light 300', 'astra' ),
						'300italic' => __( '300 Italic', 'astra' ),
						'400'       => __( 'Normal 400', 'astra' ),
						'italic'    => __( '400 Italic', 'astra' ),
						'500'       => __( 'Medium 500', 'astra' ),
						'500italic' => __( '500 Italic', 'astra' ),
						'600'       => __( 'Semi-Bold 600', 'astra' ),
						'600italic' => __( '600 Italic', 'astra' ),
						'700'       => __( 'Bold 700', 'astra' ),
						'700italic' => __( '700 Italic', 'astra' ),
						'800'       => __( 'Extra-Bold 800', 'astra' ),
						'800italic' => __( '800 Italic', 'astra' ),
						'900'       => __( 'Ultra-Bold 900', 'astra' ),
						'900italic' => __( '900 Italic', 'astra' ),
					);
					break;
				case 'ast-sortable':
					$configuration['value'] = $val;

					break;

			} // Switch End.

			if ( isset( $configuration['id'] ) ) {

				$configuration['link'] = self::get_control_link( $configuration['id'] );
			}
			$exclude_controls = array( 'ast-builder', 'ast-radio-image' );

			if ( isset( $configuration['type'] ) && ! in_array( $configuration['type'], $exclude_controls ) && isset( $configuration['input_attrs'] ) && is_array( $configuration['input_attrs'] ) ) {

				$configuration['inputAttrs'] = '';

				foreach ( $configuration['input_attrs'] as $attr => $value ) {

					if ( ! is_array( $value ) ) {

						$configuration['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
					}
				}
			}

			return $configuration;
		}

		/**
		 * Prepare Panel Configs for Javascript.
		 *
		 * @since 3.0.0
		 * @param array $config configs.
		 */
		public function prepare_javascript_panel_configs( $config ) {

			$panel_name = astra_get_prop( $config, 'name' );

			unset( $config['type'] );
			$config['type']                            = 'ast_panel';
			$config['active']                          = true;
			$config['id']                              = $panel_name;
			self::$js_configs['panels'][ $panel_name ] = $config;
		}

		/**
		 * Prepare Section Configs for Javascript.
		 *
		 * @since 3.0.0
		 * @param array $config configs.
		 */
		public function prepare_javascript_section_configs( $config ) {

			$section_name = astra_get_prop( $config, 'name' );



			unset( $config['type'] );
			$config['type']            = isset( $config['ast_type'] ) ? $config['ast_type'] : 'ast_section';
			$config['active']          = true;
			$config['id']              = $section_name;
			$config['customizeAction'] = sprintf( 'Customizing ▸ %s', astra_get_prop( $config, 'title' ) );

			if ( isset( $config['clone_type'] ) && isset( $config['clone_index'] ) ) {

				if ( isset( Astra_Builder_Helper::$component_count_array[ $config['clone_type'] ] ) ) {
					if ( in_array( $section_name, Astra_Builder_Helper::$component_count_array['removed-items'], true ) || Astra_Builder_Helper::$component_count_array[ $config['clone_type'] ] < $config['clone_index'] ) {
						self::$js_configs['clone_sections'][ $section_name ] = $config;
					} else {
						self::$js_configs['sections'][ $section_name ] = $config;
					}
				}
			} else {
				self::$js_configs['sections'][ $section_name ] = $config;
			}


		}

		/**
		 * Prepare Sub Control Configs for Javascript.
		 *
		 * @since 3.0.0
		 * @param array $config configs.
		 */
		public function prepare_javascript_sub_control_configs( $config ) {

			global $wp_customize;
			unset( $config['type'] );

			$sub_control_name = ASTRA_THEME_SETTINGS . '[' . astra_get_prop( $config, 'name' ) . ']';
			$parent           = astra_get_prop( $config, 'parent' );

			$ignore_controls = array( 'ast-settings-group', 'ast-sortable', 'ast-radio-image', 'ast-slider', 'ast-responsive-slider' );

			$sanitize_callback = ( in_array( $config['control'], $ignore_controls, true ) ) ? false : astra_get_prop( $config, 'sanitize_callback', Astra_Customizer_Control_Base::get_sanitize_call( astra_get_prop( $config, 'control' ) ) );

			if ( ! $sanitize_callback ) {
				$config = $this->sanitize_control( $config );
			}

			$new_config = array(
				'name'              => $sub_control_name,
				'datastore_type'    => 'option',
				'transport'         => 'postMessage',
				'control'           => 'ast-hidden',
				'section'           => astra_get_prop( $config, 'section', 'title_tagline' ),
				'title'             => astra_get_prop( $config, 'title' ),
				'priority'          => astra_get_prop( $config, 'priority', '10' ),
				'default'           => astra_get_prop( $config, 'default' ),
				'sanitize_callback' => $sanitize_callback,
				'suffix'            => astra_get_prop( $config, 'suffix' ),
				'control_type'      => astra_get_prop( $config, 'control' ),
			);


			self::$dynamic_options['settings'][ astra_get_prop( $new_config, 'name' ) ] = array(
				'default'           => astra_get_prop( $new_config, 'default' ),
				'type'              => astra_get_prop( $new_config, 'datastore_type' ),
				'transport'         => astra_get_prop( $new_config, 'transport', 'refresh' ),
				'sanitize_callback' => astra_get_prop( $new_config, 'sanitize_callback', Astra_Customizer_Control_Base::get_sanitize_call( astra_get_prop( $new_config, 'control' ) ) ),
			);

			$new_config['type']                               = astra_get_prop( $new_config, 'control' );
			$new_config['id']                                 = astra_get_prop( $new_config, 'name' );
			$new_config['settings']                           = array( 'default' => astra_get_prop( $new_config, 'name' ) );
			$new_config                                       = self::bypass_control_configs( $new_config );
			self::$js_configs ['sub_controls'] [ $parent ] [] = $new_config;
		}

			/**
			 * Get the Link for Control.
			 *
			 * @since 3.0.0
			 * @param array $id Control ID.
			 */
		public static function get_control_link( $id ) {
			if ( isset( $id ) ) {
				return 'data-customize-setting-link="' . $id . '"';
			} else {
				return 'data-customize-setting-key-link="default"';
			}
		}

		/**
		 * Prepare Control Configs for Javascript.
		 *
		 * @since 3.0.0
		 * @param array $config configs.
		 */
		public function prepare_javascript_control_configs( $config ) {

			global $wp_customize;
			// Remove type from configuration.
			unset( $config['type'] );

			$ignore_controls = array( 'ast-settings-group', 'ast-sortable', 'ast-radio-image', 'ast-slider', 'ast-responsive-slider' );

			if ( ! isset( $config['control'] ) ) {
				return;
			}

			$sanitize_callback = ( in_array( $config['control'], $ignore_controls, true ) ) ? false : astra_get_prop( $config, 'sanitize_callback', Astra_Customizer_Control_Base::get_sanitize_call( astra_get_prop( $config, 'control' ) ) );


			if ( ! $sanitize_callback ) {
				$config = $this->sanitize_control( $config );
			}

			$config['label'] = astra_get_prop( $config, 'title' );
			$config['type']  = astra_get_prop( $config, 'control' );

			if ( false !== astra_get_prop( $config, 'font-type', false ) ) {
				$config['type'] = astra_get_prop( $config, 'font-type', false );
			}

			if ( 'image' === $config['type'] ) {
				$this->prepare_preload_controls( $config );
			}

			if ( isset( $config['active_callback'] ) ) {

				self::$js_configs ['skip_context'] [] = astra_get_prop( $config, 'name' );
				$this->prepare_preload_controls( $config );
				return;
			}

			self::$dynamic_options['settings'][ astra_get_prop( $config, 'name' ) ] = array(
				'default'           => astra_get_prop( $config, 'default' ),
				'type'              => astra_get_prop( $config, 'datastore_type' ),
				'transport'         => astra_get_prop( $config, 'transport', 'refresh' ),
				'sanitize_callback' => $sanitize_callback,
			);

			if ( astra_get_prop( $config, 'partial', false ) ) {
				self::$dynamic_options['partials'][ astra_get_prop( $config, 'name' ) ] = array(
					'selector'           => astra_get_prop( $config['partial'], 'selector' ),
					'render_callback'    => astra_get_prop( $config['partial'], 'render_callback' ),
					'containerInclusive' => astra_get_prop( $config['partial'], 'container_inclusive' ),
					'fallbackRefresh'    => astra_get_prop( $config['partial'], 'fallback_refresh', true ),
				);
			}

			$config['id']       = astra_get_prop( $config, 'name' );
			$config['settings'] = array( 'default' => astra_get_prop( $config, 'name' ) );
			$config             = self::bypass_control_configs( $config );

			if ( isset( $config['section'] ) ) {
				self::$js_configs ['controls'] [ $config['section'] ] [] = $config;
			}
		}

		/**
		 * Map and add sanitize callback to JS configs.
		 *
		 * @param array $config js config array.
		 * @return mixed
		 */
		public function sanitize_control( $config ) {

			$control_type = isset( $config['control'] ) ? $config['control'] : '';
			switch ( $control_type ) {
				case 'color':
					$config['sanitize_callback'] = array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' );
					break;
				case 'ast-border':
					$config['sanitize_callback'] = array( 'Astra_Customizer_Sanitizes', 'sanitize_border' );
					break;
				case 'ast-html-editor':
					$config['sanitize_callback'] = array( 'Astra_Customizer_Sanitizes', 'sanitize_html' );
					break;
				case 'ast-color':
					$config['sanitize_callback'] = array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' );
					break;
				case 'ast-sortable':
					$config ['sanitize_callback'] = array( 'Astra_Customizer_Sanitizes', 'sanitize_multi_choices' );
					break;
				case 'ast-radio-image':
					$config ['sanitize_callback'] = array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' );
					break;
				case 'ast-link':
					$config ['sanitize_callback'] = array( 'Astra_Customizer_Sanitizes', 'sanitize_link' );
					break;
				case 'ast-customizer-link':
					$config ['sanitize_callback'] = array( 'Astra_Customizer_Sanitizes', 'sanitize_customizer_links' );
					break;
				case 'ast-responsive-slider':
					$config ['sanitize_callback'] = array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' );
					break;
				case 'ast-toggle-control':
					$config ['sanitize_callback'] = array( 'Astra_Customizer_Sanitizes', 'sanitize_toggle_control' );
					break;
				default:
					break;
			}

			return $config;

		}

		/**
		 * Add controls for which active_callback is added.
		 *
		 * @since 3.0.0
		 * @param array $config config.
		 */
		public function prepare_preload_controls( $config ) {

			global $wp_customize;

			$instance = Astra_Customizer_Control_Base::get_control_instance( astra_get_prop( $config, 'control' ) );

			// Forwarding to the DOM as default control.
			if ( 'title_tagline' !== $config['section'] ) {
				self::$js_configs ['wp_defaults'][ astra_get_prop( $config, 'name' ) ] = $config['section'];
				$config['section'] = 'title_tagline';
			}

			$wp_customize->add_setting(
				astra_get_prop( $config, 'name' ),
				array(
					'default'           => astra_get_prop( $config, 'default' ),
					'type'              => astra_get_prop( $config, 'datastore_type' ),
					'transport'         => astra_get_prop( $config, 'transport', 'refresh' ),
					'sanitize_callback' => astra_get_prop( $config, 'sanitize_callback', Astra_Customizer_Control_Base::get_sanitize_call( astra_get_prop( $config, 'control' ) ) ),
				)
			);

			if ( false !== $instance ) {
				$wp_customize->add_control(
					new $instance( $wp_customize, astra_get_prop( $config, 'name' ), $config )
				);
			} else {
				$wp_customize->add_control( astra_get_prop( $config, 'name' ), $config );
			}
		}

		/**
		 * Prepare Group configs to visible sub-controls.
		 *
		 * @since 3.0.0
		 * @param object $wp_customize customizer object.
		 */
		public function prepare_group_configs( $wp_customize ) {

			if ( $wp_customize->selective_refresh->is_render_partials_request() ) {
				return;
			}

			$configurations = $this->get_customizer_configurations();
			$defaults       = $this->get_astra_customizer_configuration_defaults();

			foreach ( $configurations as $key => $configuration ) {
				$config = wp_parse_args( $configuration, $defaults );
				if ( 'sub-control' === $config['type'] ) {
					unset( $config['type'] );
					$parent = astra_get_prop( $config, 'parent' );
					$tab    = astra_get_prop( $config, 'tab' );

					if ( empty( self::$group_configs[ $parent ] ) ) {
						self::$group_configs[ $parent ] = array();
					}

					if ( array_key_exists( 'tab', $config ) ) {
						self::$group_configs[ $parent ]['tabs'][ $tab ][] = $config;
					} else {
						self::$group_configs[ $parent ][] = $config;
					}
				}
			}
		}

		/**
		 * Prepare context.
		 *
		 * @return mixed|void
		 */
		public static function get_contexts() {

			self::set_default_context();
			// Return contexts.
			return apply_filters( 'astra_customizer_context', self::$contexts );
		}

		/**
		 * Prepare choices.
		 *
		 * @return mixed|void
		 */
		public static function get_choices() {
			// Return contexts.
			return apply_filters( 'astra_customizer_choices', self::$choices );
		}

		/**
		 * Prepare javascript configs.
		 *
		 * @return mixed|void
		 */
		public static function get_js_configs() {

			// Return contexts.
			return apply_filters( 'astra_javascript_configurations', self::$js_configs );
		}

		/**
		 * Prepare tabbed sections.
		 *
		 * @return mixed|void
		 */
		public static function get_tabbed_sections() {

			self::prepare_tabbed_sections();
			// Return contexts.
			return apply_filters( 'astra_customizer_tabbed_sections', self::$tabbed_sections );
		}

		/**
		 * Prepare default values for the control.
		 *
		 * @return array
		 */
		private function get_control_defaults() {

			$defaults         = array();
			$default_values   = Astra_Theme_Options::defaults();
			$default_controls = array_merge( self::$js_configs['controls'], self::$js_configs['sub_controls'] );

			foreach ( $default_controls as $section_controls ) {
				foreach ( $section_controls as $control ) {
					$control_id = astra_get_prop( $control, 'name' );
					if ( 'ast-responsive-spacing' === $control['control'] ) {
							$defaults[ $control_id ] = array(
								'desktop'      => array(
									'top'    => '',
									'right'  => '',
									'bottom' => '',
									'left'   => '',
								),
								'tablet'       => array(
									'top'    => '',
									'right'  => '',
									'bottom' => '',
									'left'   => '',
								),
								'mobile'       => array(
									'top'    => '',
									'right'  => '',
									'bottom' => '',
									'left'   => '',
								),
								'desktop-unit' => 'px',
								'tablet-unit'  => 'px',
								'mobile-unit'  => 'px',
							);
					} else {
							$defaults[ $control_id ] = $this->get_default_value( $control_id, $default_values );
					}
				}
			}

			return $defaults;

		}

		/**
		 * Add customizer script.
		 *
		 * @since 3.0.0
		 */
		public function enqueue_customizer_scripts() {

			// Localize variables for Dev mode > Customizer JS.
			wp_localize_script(
				SCRIPT_DEBUG ? 'astra-custom-control-react-script' : 'astra-custom-control-script',
				'AstraBuilderCustomizerData',
				array(
					'contexts'                => self::get_contexts(),
					'dynamic_setting_options' => self::$dynamic_options['settings'],
					'choices'                 => self::get_choices(),
					'js_configs'              => self::get_js_configs(),
					'tabbed_sections'         => self::get_tabbed_sections(),
					'component_limit'         => Astra_Builder_Helper::$component_limit,
					'is_site_rtl'             => is_rtl(),
					'defaults'                => $this->get_control_defaults(),
				)
			);

			if ( is_rtl() ) {
				$builder_customizer_css_file = 'ast-builder-customizer-rtl';
			} else {
				$builder_customizer_css_file = 'ast-builder-customizer';
			}

			// Enqueue Builder CSS.
			wp_enqueue_style(
				'ahfb-customizer-style',
				ASTRA_THEME_URI . 'inc/assets/css/' . $builder_customizer_css_file . '.css',
				array( 'wp-components' ),
				ASTRA_THEME_VERSION
			);
		}

		/**
		 * Check if string is start with a string provided.
		 *
		 * @param string $string main string.
		 * @param string $start_string string to search.
		 * @since 2.0.0
		 * @return bool.
		 */
		public function starts_with( $string, $start_string ) {
			$len = strlen( $start_string );
			return ( substr( $string, 0, $len ) === $start_string );
		}

		/**
		 * Filter and return Customizer Configurations.
		 *
		 * @since 1.4.3
		 * @return Array Customizer Configurations for registering Sections/Panels/Controls.
		 */
		private function get_customizer_configurations() {

			global  $wp_customize;

			if ( ! is_null( self::$configuration ) ) {
				return self::$configuration;
			}

			self::$configuration = apply_filters( 'astra_customizer_configurations', array(), $wp_customize );
			return self::$configuration;
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
		 * Include Customizer Configuration files.
		 *
		 * @since 1.4.3
		 * @return void
		 */
		public function include_configurations() {
			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/class-astra-customizer-config-base.php';

			/**
			 * Register Sections & Panels
			 */
			require ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer-register-sections-panels.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/buttons/class-astra-customizer-button-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-site-layout-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-site-identity-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-blog-layout-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-blog-single-layout-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-related-posts-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-sidebar-layout-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-site-container-layout-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/colors-background/class-astra-body-colors-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/typography/class-astra-archive-typo-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/typography/class-astra-body-typo-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/typography/class-astra-content-typo-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/typography/class-astra-header-typo-configs.php';
			require ASTRA_THEME_DIR . 'inc/customizer/configurations/typography/class-astra-single-typo-configs.php';

			if ( astra_existing_header_footer_configs() ) {
				require ASTRA_THEME_DIR . 'inc/customizer/configurations/buttons/class-astra-existing-button-configs.php';
				require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-header-layout-configs.php';
				require ASTRA_THEME_DIR . 'inc/customizer/configurations/layout/class-astra-footer-layout-configs.php';
				require ASTRA_THEME_DIR . 'inc/customizer/configurations/colors-background/class-astra-advanced-footer-colors-configs.php';
				require ASTRA_THEME_DIR . 'inc/customizer/configurations/colors-background/class-astra-footer-colors-configs.php';
			}
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}

		/**
		 * Register custom section and panel.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_register_panel( $wp_customize ) {

			/**
			 * Register Extended Panel
			 */
			$wp_customize->register_panel_type( 'Astra_WP_Customize_Panel' );
			$wp_customize->register_section_type( 'Astra_WP_Customize_Section' );
			$wp_customize->register_section_type( 'Astra_WP_Customize_Separator' );

			if ( ! defined( 'ASTRA_EXT_VER' ) ) {
				$wp_customize->register_section_type( 'Astra_Pro_Customizer' );
			}

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require ASTRA_THEME_DIR . 'inc/customizer/extend-customizer/class-astra-wp-customize-panel.php';
			require ASTRA_THEME_DIR . 'inc/customizer/extend-customizer/class-astra-wp-customize-section.php';
			require ASTRA_THEME_DIR . 'inc/customizer/extend-customizer/class-astra-wp-customize-separator.php';
			require ASTRA_THEME_DIR . 'inc/customizer/customizer-controls.php';
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			/**
			 * Add Controls
			 */

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
				'ast-font-variant',
				array(
					'callback'          => 'Astra_Control_Font_Variant',
					'sanitize_callback' => 'sanitize_text_field',
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
				'ast-customizer-link',
				array(
					'callback'         => 'Astra_Control_Customizer_Link',
					'santize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_customizer_links' ),
				)
			);


			/**
			 * Helper files
			 */
			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			require ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer-partials.php';
			require ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer-callback.php';
			require ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer-sanitizes.php';
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function customize_register( $wp_customize ) {

			/**
			 * Override Defaults
			 */
			require ASTRA_THEME_DIR . 'inc/customizer/override-defaults.php';// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}

		/**
		 * Add upgrade link configurations controls.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function astra_pro_upgrade_configurations( $wp_customize ) {

			if ( ! defined( 'ASTRA_EXT_VER' ) ) {
				require ASTRA_THEME_DIR . 'inc/customizer/astra-pro/class-astra-pro-customizer.php';// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require ASTRA_THEME_DIR . 'inc/customizer/astra-pro/class-astra-pro-upgrade-link-configs.php';// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
		}

		/**
		 * Customizer Controls
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function controls_scripts() {

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

			wp_enqueue_style( 'wp-components' );
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style( 'thickbox' );

			// Customizer Core.
			wp_enqueue_script( 'astra-customizer-controls-toggle-js', ASTRA_THEME_URI . 'assets/js/' . $dir . '/customizer-controls-toggle' . $js_prefix, array(), ASTRA_THEME_VERSION, true );

			// Extended Customizer Assets - Panel extended.
			wp_enqueue_style( 'astra-extend-customizer-css', ASTRA_THEME_URI . 'assets/css/' . $dir . '/extend-customizer' . $css_prefix, null, ASTRA_THEME_VERSION );
			wp_enqueue_script( 'astra-extend-customizer-js', ASTRA_THEME_URI . 'assets/js/' . $dir . '/extend-customizer' . $js_prefix, array(), ASTRA_THEME_VERSION, true );

			// Customizer Controls.
			wp_enqueue_style( 'astra-customizer-controls-css', ASTRA_THEME_URI . 'assets/css/' . $dir . '/customizer-controls' . $css_prefix, null, ASTRA_THEME_VERSION );

			$string = $this->generate_font_dropdown();

			$template = '<div class="ast-field-settings-modal">
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
							'group_modal_tmpl' => $template,
							'is_pro'           => defined( 'ASTRA_EXT_VER' ),
							'upgrade_link'     => htmlspecialchars_decode( astra_get_pro_url( 'https://wpastra.com/pricing/', 'customizer', 'upgrade-link', 'upgrade-to-pro' ) ),
						),
						'theme'      => array(
							'option' => ASTRA_THEME_SETTINGS,
						),
					)
				)
			);
		}

		/**
		 * Generates HTML for font dropdown.
		 *
		 * @return string
		 */
		public function generate_font_dropdown() {

			ob_start();

			?>

			<option value="inherit"><?php esc_html_e( 'Default System Font', 'astra' ); ?></option>
			<optgroup label="Other System Fonts">

			<?php

			$system_fonts = Astra_Font_Families::get_system_fonts();
			$google_fonts = Astra_Font_Families::get_google_fonts();

			foreach ( $system_fonts as $name => $variants ) {
				?>

				<option value="<?php echo esc_attr( $name ); ?>" ><?php echo esc_html( $name ); ?></option>
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
				<option value="<?php echo "'" . esc_attr( $name ) . "', " . esc_attr( $category ); ?>"><?php echo esc_html( $name ); ?></option>

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
		public function preview_init() {

			// Update variables.
			Astra_Theme_Options::refresh();

			$js_prefix = '.min.js';
			$dir       = 'minified';
			if ( SCRIPT_DEBUG ) {
				$js_prefix = '.js';
				$dir       = 'unminified';
			}

			wp_enqueue_script( 'astra-customizer-preview-js', ASTRA_THEME_URI . 'assets/js/' . $dir . '/customizer-preview' . $js_prefix, array( 'customize-preview' ), ASTRA_THEME_VERSION, null );

			$localize_array = array(
				'headerBreakpoint'                     => astra_header_break_point(),
				'includeAnchorsInHeadindsCss'          => Astra_Dynamic_CSS::anchors_in_css_selectors_heading(),
				'googleFonts'                          => Astra_Font_Families::get_google_fonts(),
				'page_builder_button_style_css'        => Astra_Dynamic_CSS::page_builder_button_style_css(),
				'elementor_default_color_font_setting' => Astra_Dynamic_CSS::elementor_default_color_font_setting(),
				'dynamic_partial_options'              => self::$dynamic_options['partials'],
				'gb_outline_buttons_patterns_support'  => Astra_Dynamic_CSS::gutenberg_core_patterns_compat(),
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
		public function customize_save() {

			// Update variables.
			Astra_Theme_Options::refresh();

			if ( apply_filters( 'astra_resize_logo', true ) ) {

				/* Generate Header Logo */
				$custom_logo_id = get_theme_mod( 'custom_logo' );

				add_filter( 'intermediate_image_sizes_advanced', 'Astra_Customizer::logo_image_sizes', 10, 2 );
				self::generate_logo_by_width( $custom_logo_id );
				remove_filter( 'intermediate_image_sizes_advanced', 'Astra_Customizer::logo_image_sizes', 10 );

			} else {
				// Regenerate the logo without custom image sizes.
				$custom_logo_id = get_theme_mod( 'custom_logo' );
				self::generate_logo_by_width( $custom_logo_id );
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
							require_once ABSPATH . 'wp-admin/includes/image.php';// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
						}

						$metadata = wp_generate_attachment_metadata( $image->ID, $fullsizepath );

						if ( ! is_wp_error( $metadata ) && ! empty( $metadata ) ) {
							wp_update_attachment_metadata( $image->ID, $metadata );
						}
					}
				}
			}
		}

		/**
		 * Customizer Preview icon CSS
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function preview_styles() {
			if ( is_customize_preview() ) {
				echo '<style class="astra-custom-shortcut-edit-icons">
					.customize-partial-edit-shortcut-astra-settings-footer-adv {
						position: relative;
					    top: -1em;
					    left: -1.8em;
					}
					.customize-partial-edit-shortcut-astra-settings-breadcrumb-position .customize-partial-edit-shortcut-button{
						top: -0.5em;
					}
					.ast-small-footer-section-1 .ast-footer-widget-1-area .customize-partial-edit-shortcut,
					.ast-small-footer-section-2 .ast-footer-widget-2-area .customize-partial-edit-shortcut {
						position: absolute;
					    left: 47%;
					}
					.ast-small-footer-section-1.ast-small-footer-section-equally .ast-footer-widget-1-area .customize-partial-edit-shortcut,
					.ast-small-footer-section-2.ast-small-footer-section-equally .ast-footer-widget-2-area .customize-partial-edit-shortcut {
						position: absolute;
					    left: 42%;
					}
					.ast-small-footer-section-1.ast-small-footer-section-equally .ast-footer-widget-1-area .ast-no-widget-row .customize-partial-edit-shortcut-astra-settings-footer-sml-section-1 {
						position: absolute;
					    left: 1em;
					}
					.ast-small-footer-section-2.ast-small-footer-section-equally .ast-footer-widget-2-area .ast-no-widget-row .customize-partial-edit-shortcut-astra-settings-footer-sml-section-2 {
						left: 83.5%;
					}
					.ast-small-footer-section-1.ast-small-footer-section-equally .nav-menu .customize-partial-edit-shortcut-astra-settings-footer-sml-section-1 {
						position: absolute;
					    left: 1em;
					}
					.ast-small-footer-section-2.ast-small-footer-section-equally .nav-menu .customize-partial-edit-shortcut-astra-settings-footer-sml-section-2 {
						position: absolute;
					    left: 44.5%;
					}
					.ast-small-footer .ast-container .ast-small-footer-section-1 .footer-primary-navigation > .customize-partial-edit-shortcut,
					.ast-small-footer .ast-container .ast-small-footer-section-2 .footer-primary-navigation > .customize-partial-edit-shortcut{
						display: none;
					}
					.ast-small-footer .customize-partial-edit-shortcut-astra-settings-footer-sml-layout {
						    position: absolute;
						    top: 3%;
						    left: 10%;
					}
					.customize-partial-edit-shortcut button:hover {
						border-color: #fff;
					}
					.ast-main-header-bar-alignment .main-header-bar-navigation .customize-partial-edit-shortcut-button {
						display: none;
					}
				</style>';
				echo '<style class="astra-theme-custom-shortcut-edit-icons">
					.ast-replace-site-logo-transparent.ast-theme-transparent-header .customize-partial-edit-shortcut-astra-settings-transparent-header-logo,
					.ast-replace-site-logo-transparent.ast-theme-transparent-header .customize-partial-edit-shortcut-astra-settings-transparent-header-enable {
					    z-index: 6;
					}
				</style>';
			}
		}
	}
}

/**
 *  Kicking this off by calling 'get_instance()' method
 */
Astra_Customizer::get_instance();
