<?php
/**
 * Bottom Footer Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2019, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Footer_Layout_Configs' ) ) {

	/**
	 * Register Footer Layout Configurations.
	 */
	class Astra_Footer_Layout_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Footer Layout Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Footer Bar Layout
				 */

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-sml-layout]',
					'type'     => 'control',
					'control'  => 'ast-radio-image',
					'default'  => astra_get_option( 'footer-sml-layout' ),
					'section'  => 'section-footer-small',
					'priority' => 5,
					'title'    => __( 'Layout', 'astra' ),
					'choices'  => array(
						'disabled'            => array(
							'label' => __( 'Disabled', 'astra' ),
							'path'  => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" role="img" id="Layer_1" x="0px" y="0px" width="120.5px" height="81px" viewBox="4.75 9.5 120.5 81" style="enable-background:new 4.75 9.5 120.5 81;" xml:space="preserve"><g><g><path style="fill:#0085BA;" d="M34.752,57.3h-5.484V42.546h5.484c2.287,0,4.144,0.685,5.574,2.058    c1.432,1.371,2.146,3.147,2.146,5.33c0,2.168-0.711,3.937-2.135,5.31C38.916,56.614,37.055,57.3,34.752,57.3z M34.752,55.02    c1.55,0,2.78-0.483,3.695-1.457c0.913-0.974,1.37-2.184,1.37-3.629c0-1.489-0.445-2.712-1.337-3.672    c-0.893-0.957-2.134-1.438-3.728-1.438h-2.897v10.195L34.752,55.02L34.752,55.02z"/><path style="fill:#0085BA;" d="M46.057,45.311c-0.385,0-0.718-0.14-1.007-0.42c-0.288-0.28-0.43-0.62-0.43-1.018    c0-0.398,0.143-0.737,0.43-1.017c0.29-0.282,0.623-0.421,1.007-0.421c0.399,0,0.737,0.139,1.018,0.421    c0.28,0.28,0.421,0.619,0.421,1.017c0,0.398-0.141,0.738-0.421,1.018C46.793,45.17,46.455,45.311,46.057,45.311z M47.228,57.3    h-2.322V46.615h2.322V57.3z"/><path style="fill:#0085BA;" d="M53.886,57.565c-1.917,0-3.443-0.555-4.579-1.659l1.062-1.681c0.398,0.413,0.937,0.767,1.615,1.061    c0.677,0.295,1.349,0.443,2.013,0.443c0.678,0,1.198-0.135,1.558-0.398c0.362-0.266,0.542-0.612,0.542-1.041    c0-0.382-0.22-0.68-0.664-0.896c-0.442-0.213-0.981-0.382-1.616-0.511c-0.633-0.124-1.267-0.28-1.902-0.463    c-0.634-0.187-1.172-0.514-1.614-0.984c-0.442-0.472-0.665-1.084-0.665-1.835c0-0.915,0.377-1.683,1.129-2.312    c0.752-0.627,1.777-0.94,3.076-0.94c1.637,0,3.021,0.501,4.158,1.504l-0.974,1.637c-0.339-0.383-0.788-0.694-1.35-0.929    c-0.559-0.235-1.165-0.354-1.813-0.354c-0.606,0-1.088,0.122-1.449,0.365c-0.362,0.244-0.541,0.558-0.541,0.941    c0,0.294,0.157,0.535,0.475,0.719c0.316,0.185,0.714,0.319,1.195,0.409c0.479,0.088,1,0.209,1.558,0.363    c0.561,0.156,1.082,0.338,1.559,0.543c0.48,0.208,0.877,0.543,1.194,1.008c0.317,0.464,0.476,1.027,0.476,1.691    c0,0.974-0.391,1.77-1.172,2.389C56.378,57.255,55.288,57.565,53.886,57.565z"/><path style="fill:#0085BA;" d="M69.522,57.3h-2.323v-1.15c-0.826,0.944-1.977,1.416-3.45,1.416c-0.974,0-1.836-0.313-2.588-0.94    c-0.752-0.627-1.129-1.494-1.129-2.598c0-1.136,0.373-2,1.117-2.59c0.745-0.59,1.612-0.885,2.6-0.885    c1.519,0,2.669,0.457,3.45,1.372v-1.593c0-0.62-0.229-1.106-0.683-1.46c-0.458-0.354-1.063-0.531-1.816-0.531    c-1.195,0-2.249,0.451-3.163,1.35l-0.952-1.614c1.21-1.149,2.706-1.726,4.491-1.726c1.311,0,2.38,0.309,3.208,0.93    c0.825,0.62,1.238,1.599,1.238,2.943V57.3L69.522,57.3z M64.658,55.972c1.166,0,2.013-0.369,2.543-1.105v-1.613    c-0.53-0.737-1.378-1.106-2.543-1.106c-0.665,0-1.209,0.177-1.637,0.529c-0.428,0.354-0.641,0.819-0.641,1.395    s0.212,1.037,0.641,1.383C63.449,55.802,63.993,55.972,64.658,55.972z"/><path style="fill:#0085BA;" d="M74.854,57.3h-2.323V42.546h2.323v5.552c0.87-1.164,2.013-1.748,3.43-1.748    c1.385,0,2.52,0.513,3.405,1.537c0.884,1.024,1.326,2.385,1.326,4.082c0,1.725-0.442,3.09-1.326,4.093    c-0.886,1.002-2.021,1.504-3.405,1.504c-1.402,0-2.546-0.575-3.43-1.727V57.3z M74.854,54.091c0.25,0.399,0.638,0.736,1.162,1.008    c0.521,0.271,1.052,0.409,1.579,0.409c0.916,0,1.648-0.327,2.205-0.983c0.553-0.657,0.827-1.508,0.827-2.556    c0-1.047-0.276-1.903-0.827-2.566c-0.557-0.665-1.289-0.995-2.205-0.995c-0.527,0-1.054,0.141-1.57,0.42    c-0.516,0.281-0.903,0.627-1.171,1.04V54.091L74.854,54.091z"/><path style="fill:#0085BA;" d="M87.705,57.3h-2.322V42.546h2.322V57.3z"/><path style="fill:#0085BA;" d="M95.646,57.565c-1.622,0-2.959-0.521-4.014-1.559c-1.057-1.04-1.582-2.393-1.582-4.06    c0-1.562,0.511-2.885,1.535-3.969c1.025-1.084,2.32-1.626,3.883-1.626c1.577,0,2.85,0.546,3.815,1.636    c0.966,1.093,1.448,2.486,1.448,4.18v0.554H92.48c0.09,0.842,0.438,1.542,1.04,2.102c0.604,0.56,1.396,0.84,2.366,0.84    c0.545,0,1.096-0.103,1.646-0.308c0.555-0.209,1.016-0.495,1.384-0.866l1.06,1.526C98.904,57.049,97.46,57.565,95.646,57.565z     M98.477,51.082c-0.028-0.735-0.297-1.392-0.806-1.967s-1.242-0.863-2.201-0.863c-0.914,0-1.63,0.285-2.146,0.851    c-0.517,0.568-0.804,1.229-0.862,1.979H98.477z"/></g></g><g><g><path style="fill:#0085BA;" d="M121.451,90.296H8.549C6.592,90.296,5,88.704,5,86.747V13.253c0-1.957,1.592-3.549,3.549-3.549    h112.902c1.956,0,3.549,1.592,3.549,3.549v73.494C125,88.704,123.407,90.296,121.451,90.296z M8.549,11.479    c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.979,0.795,1.773,1.773,1.773h112.902c0.979,0,1.773-0.797,1.773-1.773V13.253    c0-0.979-0.795-1.774-1.773-1.774H8.549z"/></g></g></svg>',
						),
						'footer-sml-layout-1' => array(
							'label' => __( 'Footer Bar Layout 1', 'astra' ),
							'path'  => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" role="img" id="Layer_1" x="0px" y="0px" width="120.5px" height="81px" viewBox="0 0 120.5 81" enable-background="new 0 0 120.5 81" xml:space="preserve"><g><path fill="#0085BA" d="M3.799,0.204h112.902c1.958,0,3.549,1.593,3.549,3.55v73.494c0,1.957-1.592,3.549-3.549,3.549H3.799 c-1.957,0-3.549-1.592-3.549-3.549V3.754C0.25,1.797,1.842,0.204,3.799,0.204z M116.701,79.021c0.979,0,1.774-0.795,1.774-1.773 l0.001-73.494c0-0.979-0.797-1.774-1.775-1.774H3.799c-0.979,0-1.773,0.795-1.773,1.774v73.494c0,0.979,0.795,1.773,1.773,1.773 H116.701z"/></g><line fill="none" stroke="#0085BA" stroke-miterlimit="10" x1="120.25" y1="58.659" x2="0.965" y2="58.659"/><g><g><path fill="#0085BA" d="M26.805,64.475h66.89c0.98,0,1.774,0.628,1.774,1.4s-0.794,1.4-1.774,1.4h-66.89 c-0.98,0-1.773-0.628-1.773-1.4C25.031,65.102,25.826,64.475,26.805,64.475z"/></g></g><g><ellipse fill="#0085BA" cx="72.604" cy="72.174" rx="2.146" ry="2.108"/><ellipse fill="#0085BA" cx="64.37" cy="72.174" rx="2.147" ry="2.108"/><ellipse fill="#0085BA" cx="56.132" cy="72.174" rx="2.145" ry="2.108"/><ellipse fill="#0085BA" cx="47.896" cy="72.174" rx="2.146" ry="2.108"/></g></svg>',
						),
						'footer-sml-layout-2' => array(
							'label' => __( 'Footer Bar Layout 2', 'astra' ),
							'path'  => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" role="img" id="Layer_1" x="0px" y="0px" width="120.5px" height="81px" viewBox="0 0 120.5 81" enable-background="new 0 0 120.5 81" xml:space="preserve"><g><path fill="#0085BA" d="M120.25,3.754v73.494c0,1.957-1.592,3.549-3.549,3.549H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.754 c0-1.957,1.591-3.55,3.549-3.55h112.902C118.658,0.204,120.25,1.797,120.25,3.754z M116.701,79.021 c0.979,0,1.773-0.795,1.773-1.773V3.754c0-0.979-0.795-1.774-1.773-1.774H3.799c-0.979,0-1.775,0.795-1.775,1.774l0.001,73.494 c0,0.979,0.796,1.773,1.774,1.773H116.701z"/></g><g><g><path fill="#0085BA" d="M120.25,3.754v73.494c0,1.957-1.592,3.549-3.549,3.549H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.754 c0-1.957,1.591-3.55,3.549-3.55h112.902C118.658,0.204,120.25,1.797,120.25,3.754z M116.701,79.021 c0.979,0,1.773-0.795,1.773-1.773V3.754c0-0.979-0.795-1.774-1.773-1.774H3.799c-0.979,0-1.775,0.795-1.775,1.774l0.001,73.494 c0,0.979,0.796,1.773,1.774,1.773H116.701z"/></g></g><g><g><g><path fill="#0085BA" d="M63.184,69.175c0,0.979-0.793,1.774-1.773,1.774h-46.89c-0.98,0-1.774-0.795-1.774-1.774 S13.54,67.4,14.521,67.4h46.89C62.389,67.4,63.184,68.194,63.184,69.175z"/></g></g><g><ellipse fill="#0085BA" cx="79.872" cy="69.175" rx="2.228" ry="2.188"/><ellipse fill="#0085BA" cx="88.422" cy="69.175" rx="2.229" ry="2.188"/><ellipse fill="#0085BA" cx="96.974" cy="69.175" rx="2.227" ry="2.188"/><ellipse fill="#0085BA" cx="105.525" cy="69.175" rx="2.229" ry="2.188"/></g></g><line fill="none" stroke="#0085BA" stroke-miterlimit="10" x1="120.25" y1="58.659" x2="0.965" y2="58.659"/></svg>',
						),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[section-ast-small-footer-layout-info]',
					'control'  => 'ast-divider',
					'type'     => 'control',
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
					'section'  => 'section-footer-small',
					'priority' => 10,
					'settings' => array(),
				),

				/**
				 *  Section: Section 1
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-sml-section-1]',
					'control'  => 'select',
					'default'  => astra_get_option( 'footer-sml-section-1' ),
					'type'     => 'control',
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
					'section'  => 'section-footer-small',
					'priority' => 15,
					'title'    => __( 'Section 1', 'astra' ),
					'choices'  => array(
						''       => __( 'None', 'astra' ),
						'custom' => __( 'Text', 'astra' ),
						'widget' => __( 'Widget', 'astra' ),
						'menu'   => __( 'Footer Menu', 'astra' ),
					),
				),
				/**
				 * Option: Section 1 Custom Text
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[footer-sml-section-1-credit]',
					'default'   => astra_get_option( 'footer-sml-section-1-credit' ),
					'type'      => 'control',
					'control'   => 'textarea',
					'transport' => 'postMessage',
					'section'   => 'section-footer-small',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[footer-sml-section-1]', '==', array( 'custom' ) ),
						),
					),
					'priority'  => 20,
					'title'     => __( 'Section 1 Custom Text', 'astra' ),
					'choices'   => array(
						''       => __( 'None', 'astra' ),
						'custom' => __( 'Custom Text', 'astra' ),
						'widget' => __( 'Widget', 'astra' ),
						'menu'   => __( 'Footer Menu', 'astra' ),
					),
					'partial'   => array(
						'selector'            => '.ast-small-footer-section-1',
						'container_inclusive' => false,
						'render_callback'     => array( 'Astra_Customizer_Partials', '_render_footer_sml_section_1_credit' ),
					),
				),

				/**
				 * Option: Section 2
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-sml-section-2]',
					'type'     => 'control',
					'control'  => 'select',
					'default'  => astra_get_option( 'footer-sml-section-2' ),
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
					'section'  => 'section-footer-small',
					'priority' => 25,
					'title'    => __( 'Section 2', 'astra' ),
					'choices'  => array(
						''       => __( 'None', 'astra' ),
						'custom' => __( 'Text', 'astra' ),
						'widget' => __( 'Widget', 'astra' ),
						'menu'   => __( 'Footer Menu', 'astra' ),
					),
				),

				/**
				 * Option: Section 2 Custom Text
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-sml-section-2-credit]',
					'type'     => 'control',
					'control'  => 'textarea',
					'default'  => astra_get_option( 'footer-sml-section-2-credit' ),
					'section'  => 'section-footer-small',
					'priority' => 30,
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-sml-section-2]', '==', 'custom' ),
					'title'    => __( 'Section 2 Custom Text', 'astra' ),
					'partials' => array(
						'selector'            => '.ast-small-footer-section-2',
						'container_inclusive' => false,
						'render_callback'     => array( 'Astra_Customizer_Partials', '_render_footer_sml_section_2_credit' ),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[section-ast-small-footer-typography]',
					'control'  => 'ast-divider',
					'type'     => 'control',
					'section'  => 'section-footer-small',
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
					'priority' => 35,
					'settings' => array(),
				),

				/**
				 * Option: Footer Top Border
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[footer-sml-divider]',
					'type'        => 'control',
					'control'     => 'number',
					'default'     => astra_get_option( 'footer-sml-divider' ),
					'section'     => 'section-footer-small',
					'priority'    => 40,
					'required'    => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
					'title'       => __( 'Border Size', 'astra' ),
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
				),

				/**
				 * Option: Footer Top Border Color
				 */

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[footer-sml-divider-color]',
					'section'   => 'section-footer-small',
					'default'   => '#7a7a7a',
					'type'      => 'control',
					'control'   => 'ast-color',
					'required'  => array( ASTRA_THEME_SETTINGS . '[footer-sml-divider]', '>=', 1 ),
					'priority'  => 45,
					'title'     => __( 'Border Color', 'astra' ),
					'transport' => 'postMessage',
				),

				/**
				 * Option: Footer Bar Color & Background Section heading
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-bar-color-background-heading-divider]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-footer-small',
					'title'    => __( 'Colors & Background', 'astra' ),
					'priority' => 46,
					'settings' => array(),
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
				),

				/**
				 * Option: Footer Bar Content Group
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[footer-bar-background-group]',
					'default'   => astra_get_option( 'footer-bar-background-group' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Background', 'astra' ),
					'section'   => 'section-footer-small',
					'transport' => 'postMessage',
					'priority'  => 47,
					'required'  => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
				),

				/**
				 * Option: Footer Bar Content Group
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[footer-bar-content-group]',
					'default'   => astra_get_option( 'footer-bar-content-group' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Content', 'astra' ),
					'section'   => 'section-footer-small',
					'transport' => 'postMessage',
					'priority'  => 47,
					'required'  => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
				),

				/**
				 * Option: Header Width
				 */

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-layout-width]',
					'type'     => 'control',
					'control'  => 'select',
					'default'  => astra_get_option( 'footer-layout-width' ),
					'section'  => 'section-footer-small',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[site-layout]', '!=', 'ast-box-layout' ),
							array( ASTRA_THEME_SETTINGS . '[site-layout]', '!=', 'ast-fluid-width-layout' ),
							array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
						),
					),
					'priority' => 35,
					'title'    => __( 'Width', 'astra' ),
					'choices'  => array(
						'full'    => __( 'Full Width', 'astra' ),
						'content' => __( 'Content Width', 'astra' ),
					),
				),

				/**
				 * Option: Footer Widgets Layout Layout
				 */
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[footer-adv]',
					'type'    => 'control',
					'control' => 'ast-radio-image',
					'default' => astra_get_option( 'footer-adv' ),
					'title'   => __( 'Layout', 'astra' ),
					'section' => 'section-footer-adv',
					'choices' => array(
						'disabled' => array(
							'label' => __( 'Disable', 'astra' ),
							'path'  => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" role="img" id="Layer_1" x="0px" y="0px" width="120.5px" height="81px" viewBox="4.75 9.5 120.5 81" style="enable-background:new 4.75 9.5 120.5 81;" xml:space="preserve"><g><g><path style="fill:#0085BA;" d="M34.752,57.3h-5.484V42.546h5.484c2.287,0,4.144,0.685,5.574,2.058    c1.432,1.371,2.146,3.147,2.146,5.33c0,2.168-0.711,3.937-2.135,5.31C38.916,56.614,37.055,57.3,34.752,57.3z M34.752,55.02    c1.55,0,2.78-0.483,3.695-1.457c0.913-0.974,1.37-2.184,1.37-3.629c0-1.489-0.445-2.712-1.337-3.672    c-0.893-0.957-2.134-1.438-3.728-1.438h-2.897v10.195L34.752,55.02L34.752,55.02z"/><path style="fill:#0085BA;" d="M46.057,45.311c-0.385,0-0.718-0.14-1.007-0.42c-0.288-0.28-0.43-0.62-0.43-1.018    c0-0.398,0.143-0.737,0.43-1.017c0.29-0.282,0.623-0.421,1.007-0.421c0.399,0,0.737,0.139,1.018,0.421    c0.28,0.28,0.421,0.619,0.421,1.017c0,0.398-0.141,0.738-0.421,1.018C46.793,45.17,46.455,45.311,46.057,45.311z M47.228,57.3    h-2.322V46.615h2.322V57.3z"/><path style="fill:#0085BA;" d="M53.886,57.565c-1.917,0-3.443-0.555-4.579-1.659l1.062-1.681c0.398,0.413,0.937,0.767,1.615,1.061    c0.677,0.295,1.349,0.443,2.013,0.443c0.678,0,1.198-0.135,1.558-0.398c0.362-0.266,0.542-0.612,0.542-1.041    c0-0.382-0.22-0.68-0.664-0.896c-0.442-0.213-0.981-0.382-1.616-0.511c-0.633-0.124-1.267-0.28-1.902-0.463    c-0.634-0.187-1.172-0.514-1.614-0.984c-0.442-0.472-0.665-1.084-0.665-1.835c0-0.915,0.377-1.683,1.129-2.312    c0.752-0.627,1.777-0.94,3.076-0.94c1.637,0,3.021,0.501,4.158,1.504l-0.974,1.637c-0.339-0.383-0.788-0.694-1.35-0.929    c-0.559-0.235-1.165-0.354-1.813-0.354c-0.606,0-1.088,0.122-1.449,0.365c-0.362,0.244-0.541,0.558-0.541,0.941    c0,0.294,0.157,0.535,0.475,0.719c0.316,0.185,0.714,0.319,1.195,0.409c0.479,0.088,1,0.209,1.558,0.363    c0.561,0.156,1.082,0.338,1.559,0.543c0.48,0.208,0.877,0.543,1.194,1.008c0.317,0.464,0.476,1.027,0.476,1.691    c0,0.974-0.391,1.77-1.172,2.389C56.378,57.255,55.288,57.565,53.886,57.565z"/><path style="fill:#0085BA;" d="M69.522,57.3h-2.323v-1.15c-0.826,0.944-1.977,1.416-3.45,1.416c-0.974,0-1.836-0.313-2.588-0.94    c-0.752-0.627-1.129-1.494-1.129-2.598c0-1.136,0.373-2,1.117-2.59c0.745-0.59,1.612-0.885,2.6-0.885    c1.519,0,2.669,0.457,3.45,1.372v-1.593c0-0.62-0.229-1.106-0.683-1.46c-0.458-0.354-1.063-0.531-1.816-0.531    c-1.195,0-2.249,0.451-3.163,1.35l-0.952-1.614c1.21-1.149,2.706-1.726,4.491-1.726c1.311,0,2.38,0.309,3.208,0.93    c0.825,0.62,1.238,1.599,1.238,2.943V57.3L69.522,57.3z M64.658,55.972c1.166,0,2.013-0.369,2.543-1.105v-1.613    c-0.53-0.737-1.378-1.106-2.543-1.106c-0.665,0-1.209,0.177-1.637,0.529c-0.428,0.354-0.641,0.819-0.641,1.395    s0.212,1.037,0.641,1.383C63.449,55.802,63.993,55.972,64.658,55.972z"/><path style="fill:#0085BA;" d="M74.854,57.3h-2.323V42.546h2.323v5.552c0.87-1.164,2.013-1.748,3.43-1.748    c1.385,0,2.52,0.513,3.405,1.537c0.884,1.024,1.326,2.385,1.326,4.082c0,1.725-0.442,3.09-1.326,4.093    c-0.886,1.002-2.021,1.504-3.405,1.504c-1.402,0-2.546-0.575-3.43-1.727V57.3z M74.854,54.091c0.25,0.399,0.638,0.736,1.162,1.008    c0.521,0.271,1.052,0.409,1.579,0.409c0.916,0,1.648-0.327,2.205-0.983c0.553-0.657,0.827-1.508,0.827-2.556    c0-1.047-0.276-1.903-0.827-2.566c-0.557-0.665-1.289-0.995-2.205-0.995c-0.527,0-1.054,0.141-1.57,0.42    c-0.516,0.281-0.903,0.627-1.171,1.04V54.091L74.854,54.091z"/><path style="fill:#0085BA;" d="M87.705,57.3h-2.322V42.546h2.322V57.3z"/><path style="fill:#0085BA;" d="M95.646,57.565c-1.622,0-2.959-0.521-4.014-1.559c-1.057-1.04-1.582-2.393-1.582-4.06    c0-1.562,0.511-2.885,1.535-3.969c1.025-1.084,2.32-1.626,3.883-1.626c1.577,0,2.85,0.546,3.815,1.636    c0.966,1.093,1.448,2.486,1.448,4.18v0.554H92.48c0.09,0.842,0.438,1.542,1.04,2.102c0.604,0.56,1.396,0.84,2.366,0.84    c0.545,0,1.096-0.103,1.646-0.308c0.555-0.209,1.016-0.495,1.384-0.866l1.06,1.526C98.904,57.049,97.46,57.565,95.646,57.565z     M98.477,51.082c-0.028-0.735-0.297-1.392-0.806-1.967s-1.242-0.863-2.201-0.863c-0.914,0-1.63,0.285-2.146,0.851    c-0.517,0.568-0.804,1.229-0.862,1.979H98.477z"/></g></g><g><g><path style="fill:#0085BA;" d="M121.451,90.296H8.549C6.592,90.296,5,88.704,5,86.747V13.253c0-1.957,1.592-3.549,3.549-3.549    h112.902c1.956,0,3.549,1.592,3.549,3.549v73.494C125,88.704,123.407,90.296,121.451,90.296z M8.549,11.479    c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.979,0.795,1.773,1.773,1.773h112.902c0.979,0,1.773-0.797,1.773-1.773V13.253    c0-0.979-0.795-1.774-1.773-1.774H8.549z"/></g></g></svg>',
						),
						'layout-4' => array(
							'label' => __( 'Layout 4', 'astra' ),
							'path'  => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" role="img" id="Layer_1" x="0px" y="0px" width="120.5px" height="81px" viewBox="0 0 120.5 81" enable-background="new 0 0 120.5 81" xml:space="preserve"><g><g><g><g><path fill="#0085BA" d="M116.701,80.796H3.799c-1.957,0-3.549-1.592-3.549-3.549V3.753c0-1.957,1.592-3.549,3.549-3.549h112.902 c1.956,0,3.549,1.592,3.549,3.549v73.494C120.25,79.204,118.657,80.796,116.701,80.796z M3.799,1.979 c-0.979,0-1.773,0.797-1.773,1.774v73.494c0,0.979,0.795,1.772,1.773,1.772h112.902c0.979,0,1.773-0.797,1.773-1.772V3.753 c0-0.979-0.795-1.774-1.773-1.774H3.799z"/></g></g></g></g><g><path fill="#0085BA" d="M28.064,70c0,1.657-1.354,3-3.023,3H12.458c-1.669,0-3.023-1.343-3.023-3V58.25c0-1.656,1.354-3,3.023-3 h12.583c1.67,0,3.023,1.344,3.023,3V70z"/></g><g><path fill="#0085BA" d="M55.731,70c0,1.657-1.354,3-3.023,3H40.125c-1.669,0-3.023-1.343-3.023-3V58.25c0-1.656,1.354-3,3.023-3 h12.583c1.67,0,3.023,1.344,3.023,3V70z"/></g><g><path fill="#0085BA" d="M83.397,70c0,1.657-1.354,3-3.023,3H67.791c-1.669,0-3.022-1.343-3.022-3V58.25c0-1.656,1.354-3,3.022-3 h12.583c1.67,0,3.023,1.344,3.023,3V70z"/></g><g><path fill="#0085BA" d="M111.064,70c0,1.657-1.354,3-3.023,3H95.458c-1.669,0-3.022-1.343-3.022-3V58.25c0-1.656,1.354-3,3.022-3 h12.583c1.67,0,3.023,1.344,3.023,3V70z"/></g><g><rect x="0.607" y="48" fill="#0085BA" width="119.285" height="1"/></g></svg>',
						),
					),
				),

				/**
				 * Option: Footer Top Border
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[footer-adv-border-width]',
					'type'        => 'control',
					'control'     => 'number',
					'transport'   => 'postMessage',
					'section'     => 'section-footer-adv',
					'default'     => astra_get_option( 'footer-adv-border-width' ),
					'priority'    => 40,
					'required'    => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
					'title'       => __( 'Top Border Size', 'astra' ),
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
				),

				/**
				 * Option: Footer Top Border Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[footer-adv-border-color]',
					'section'   => 'section-footer-adv',
					'title'     => __( 'Top Border Color', 'astra' ),
					'type'      => 'control',
					'transport' => 'postMessage',
					'control'   => 'ast-color',
					'default'   => astra_get_option( 'footer-adv-border-color' ),
					'required'  => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
					'priority'  => 45,
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			// Learn More link if Astra Pro is not activated.
			if ( ! defined( 'ASTRA_EXT_VER' ) ) {

				$config = array(
					/**
					 * Option: Divider
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[ast-footer-widget-more-feature-divider]',
						'type'     => 'control',
						'control'  => 'ast-divider',
						'section'  => 'section-footer-adv',
						'priority' => 999,
						'settings' => array(),
					),

					/**
					 * Option: Learn More about Footer Widget
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[ast-footer-widget-more-feature-description]',
						'type'     => 'control',
						'control'  => 'ast-description',
						'section'  => 'section-footer-adv',
						'priority' => 999,
						'label'    => '',
						'help'     => '<p>' . __( 'More Options Available in Astra Pro!', 'astra' ) . '</p><a href="' . astra_get_pro_url( 'https://wpastra.com/pro/', 'customizer', 'learn-more', 'upgrade-to-pro' ) . '" class="button button-primary"  target="_blank" rel="noopener">' . __( 'Learn More', 'astra' ) . '</a>',
						'settings' => array(),
					),

				);

				$configurations = array_merge( $configurations, $config );
			}

			return $configurations;

		}
	}
}


new Astra_Footer_Layout_Configs;





