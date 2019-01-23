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

if ( ! class_exists( 'Astra_Blog_Single_Layout_Configs' ) ) {

	/**
	 * Register Blog Single Layout Configurations.
	 */
	class Astra_Blog_Single_Layout_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Blog Single Layout Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Display Post Structure
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-single-post-structure]',
					'type'     => 'control',
					'control'  => 'ast-sortable',
					'section'  => 'section-blog-single',
					'default'  => astra_get_option( 'blog-single-post-structure' ),
					'priority' => 5,
					'title'    => __( 'Single Post Structure', 'astra' ),
					'choices'  => array(
						'single-image'      => __( 'Featured Image', 'astra' ),
						'single-title-meta' => __( 'Title & Blog Meta', 'astra' ),
					),
				),

				/**
				 * Option: Single Post Meta
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-single-meta]',
					'type'     => 'control',
					'control'  => 'ast-sortable',
					'default'  => astra_get_option( 'blog-single-meta' ),
					'required' => array( ASTRA_THEME_SETTINGS . '[blog-single-post-structure]', 'contains', 'single-title-meta' ),
					'section'  => 'section-blog-single',
					'priority' => 5,
					'title'    => __( 'Single Post Meta', 'astra' ),
					'choices'  => array(
						'comments' => __( 'Comments', 'astra' ),
						'category' => __( 'Category', 'astra' ),
						'author'   => __( 'Author', 'astra' ),
						'date'     => __( 'Publish Date', 'astra' ),
						'tag'      => __( 'Tag', 'astra' ),
						'read-time' => __( 'Read Time', 'astra' ),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[ast-styling-section-single-blog-layouts]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-blog-single',
					'priority' => 10,
					'settings' => array(),
				),

				/**
				 * Option: Single Post Content Width
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-single-width]',
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-blog-single',
					'default'  => astra_get_option( 'blog-single-width' ),
					'priority' => 15,
					'title'    => __( 'Single Post Content Width', 'astra' ),
					'choices'  => array(
						'default' => __( 'Default', 'astra' ),
						'custom'  => __( 'Custom', 'astra' ),
					),
				),

				/**
				 * Option: Enter Width
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[blog-single-max-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => 'section-blog-single',
					'default'     => 1200,
					'required'    => array( ASTRA_THEME_SETTINGS . '[blog-single-width]', '===', 'custom' ),
					'priority'    => 20,
					'title'       => __( 'Enter Width', 'astra' ),
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 768,
						'step' => 1,
						'max'  => 1920,
					),
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
						'name'     => ASTRA_THEME_SETTINGS . '[ast-blog-single-more-feature-divider]',
						'type'     => 'control',
						'control'  => 'ast-divider',
						'section'  => 'section-blog-single',
						'priority' => 999,
						'settings' => array(),
					),

					/**
					 * Option: Learn More about Single Blog Pro
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[ast-blog-single-more-feature-description]',
						'type'     => 'control',
						'control'  => 'ast-description',
						'section'  => 'section-blog-single',
						'priority' => 999,
						'title'    => '',
						'help'     => '<p>' . __( 'More Options Available for Single Post in Astra Pro!', 'astra' ) . '</p><a href="' . astra_get_pro_url( 'https://wpastra.com/docs/single-post-blog-pro/', 'customizer', 'learn-more', 'upgrade-to-pro' ) . '" class="button button-primary"  target="_blank" rel="noopener">' . __( 'Learn More', 'astra' ) . '</a>',
						'settings' => array(),
					),
				);

				$configurations = array_merge( $configurations, $config );
			}

			return $configurations;

		}
	}
}


new Astra_Blog_Single_Layout_Configs;





