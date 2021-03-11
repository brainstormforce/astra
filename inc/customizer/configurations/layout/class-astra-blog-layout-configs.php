<?php
/**
 * Bottom Footer Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Blog_Layout_Configs' ) ) {

	/**
	 * Register Blog Layout Customizer Configurations.
	 */
	class Astra_Blog_Layout_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Blog Layout Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[ast-styling-section-blog-width]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-blog',
					'priority' => 60,
					'settings' => array(),
				),

				/**
				 * Option: Blog Content Width
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[blog-width]',
					'default'    => astra_get_option( 'blog-width' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-blog',
					'priority'   => 50,
					'transport'  => 'postMessage',
					'title'      => __( 'Content Width', 'astra' ),
					'choices'    => array(
						'default' => __( 'Default', 'astra' ),
						'custom'  => __( 'Custom', 'astra' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Enter Width
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[blog-max-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => 'section-blog',
					'transport'   => 'postMessage',
					'default'     => astra_get_option( 'blog-max-width' ),
					'priority'    => 50,
					'context'     => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[blog-width]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),
					'title'       => __( 'Custom Width', 'astra' ),
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 768,
						'step' => 1,
						'max'  => 1920,
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[ast-styling-section-blog-width-end]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-blog',
					'priority' => 50,
					'settings' => array(),
					'context'  => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[blog-width]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),
				),

				/**
				 * Option: Blog Post Content
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[blog-post-content]',
					'section'    => 'section-blog',
					'title'      => __( 'Post Content', 'astra' ),
					'default'    => astra_get_option( 'blog-post-content' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'priority'   => 75,
					'choices'    => array(
						'full-content' => __( 'Full Content', 'astra' ),
						'excerpt'      => __( 'Excerpt', 'astra' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Display Post Structure
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[blog-post-structure]',
					'default'           => astra_get_option( 'blog-post-structure' ),
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'section'           => 'section-blog',
					'priority'          => 50,
					'title'             => __( 'Post Structure', 'astra' ),
					'choices'           => array(
						'image'      => __( 'Featured Image', 'astra' ),
						'title-meta' => __( 'Title & Blog Meta', 'astra' ),
					),
				),

			);

			if ( ! defined( 'ASTRA_EXT_VER' ) ) {
				array_push(
					$_configs,
					/**
					 * Option: Display Post Meta
					 */
					array(
						'name'              => ASTRA_THEME_SETTINGS . '[blog-meta]',
						'type'              => 'control',
						'control'           => 'ast-sortable',
						'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_multi_choices' ),
						'section'           => 'section-blog',
						'default'           => astra_get_option( 'blog-meta' ),
						'priority'          => 50,
						'context'           => array(
							Astra_Builder_Helper::$general_tab_config,
							array(
								'setting'  => ASTRA_THEME_SETTINGS . '[blog-post-structure]',
								'operator' => 'contains',
								'value'    => 'title-meta',
							),
						),
						'title'             => __( 'Meta', 'astra' ),
						'choices'           => array(
							'comments' => __( 'Comments', 'astra' ),
							'category' => __( 'Category', 'astra' ),
							'author'   => __( 'Author', 'astra' ),
							'date'     => __( 'Publish Date', 'astra' ),
							'tag'      => __( 'Tag', 'astra' ),
						),
					)
				);
			}

			if ( Astra_Builder_Helper::$is_header_footer_builder_active ) {

				array_push(
					$_configs,
					/**
					 * Option: Blog / Archive Tabs.
					 */
					array(
						'name'        => 'section-blog-ast-context-tabs',
						'section'     => 'section-blog',
						'type'        => 'control',
						'control'     => 'ast-builder-header-control',
						'priority'    => 0,
						'description' => '',
					)
				);

			}

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;

		}
	}
}


new Astra_Blog_Layout_Configs();
