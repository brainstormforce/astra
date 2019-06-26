<?php
/**
 * Customizer Notices Class.
 * Display Relavant notices in the customizer panels and sections to improve UX.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2019, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       x.x.x
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

if ( ! class_exists( 'Astra_Customizer_Register_Notices_Configs' ) ) :

	/**
	 * The Customizer class.
	 */
	class Astra_Customizer_Register_Notices_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register General Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since x.x.x
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			if ( defined( 'ASTRA_THEME_TRANSPARENT_HEADER_DIR' ) ) {

				$_configs = array(

					/**
					 * Notice for Colors - Transparent header enabled on page.
					 */
					array(
						'name'            => ASTRA_THEME_SETTINGS . '[header-color-transparent-header-notice]',
						'type'            => 'control',
						'control'         => 'ast-description',
						'section'         => 'section-header',
						'priority'        => 1,
						'active_callback' => array( $this, 'is_transparent_header_enabled' ),
						'help'            => $this->get_help_text_notice( 'transparent-header' ),
					),
				);

				$configurations = array_merge( $configurations, $_configs );
			}

			return $configurations;

		}

		/**
		 * Check if transparent header is enabled on the page being previewed.
		 *
		 * @since  x.x.x
		 * @return boolean True - If Transparent Header is enabled, False if not.
		 */
		public function is_transparent_header_enabled() {
			return (bool) Astra_Ext_Transparent_Header_Markup::get_instance()->is_transparent_header();
		}

		/**
		 * Help notice message to be displayed when the page that is being previewed has header built using Custom Layout.
		 *
		 * @since  x.x.x
		 * @param String $context Type of notice message to be returned.
		 * @return String HTML Markup for the help notice.
		 */
		private function get_help_text_notice( $context ) {

			switch ( $context ) {

				case 'transparent-header':
					$notice = '<div class="ast-customizer-notice wp-ui-highlight"><p>This page has Transparent Header enabled, so the settings in this section may not apply</p><p><a href="#" class="ast-customizer-internal-link" data-ast-customizer-section="section-colors-transparent-header">Click here</a> to modify the transparent header settings.<p></div>';
					break;

				default:
					$notice = '';
					break;
			}

			return $notice;
		}

	}

endif;


new Astra_Customizer_Register_Notices_Configs();
