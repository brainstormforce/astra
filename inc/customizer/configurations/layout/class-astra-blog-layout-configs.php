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
					'name'     => ASTRA_THEME_SETTINGS . '[blog-width]',
					'default'  => astra_get_option( 'blog-width' ),
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-blog',
					'priority' => 60,
					'title'    => __( 'Content Width', 'astra' ),
					'choices'  => array(
						'default' => __( 'Default', 'astra' ),
						'custom'  => __( 'Custom', 'astra' ),
					),
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
					'default'     => 1200,
					'priority'    => 65,
					'required'    => array( ASTRA_THEME_SETTINGS . '[blog-width]', '===', 'custom' ),
					'title'       => __( 'Custom Width', 'astra' ),
					'suffix'      => '',
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
					'priority' => 70,
					'settings' => array(),
				),

				/**
				 * Option: Blog Post Content
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-post-content]',
					'section'  => 'section-blog',
					'title'    => __( 'Post Content', 'astra' ),
					'default'  => astra_get_option( 'blog-post-content' ),
					'type'     => 'control',
					'control'  => 'select',
					'priority' => 75,
					'choices'  => array(
						'full-content' => __( 'Full Content', 'astra' ),
						'excerpt'      => __( 'Excerpt', 'astra' ),
					),
				),

				/**
				 * Option: Display Post Structure
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-post-structure]',
					'default'  => astra_get_option( 'blog-post-structure' ),
					'type'     => 'control',
					'control'  => 'ast-sortable',
					'section'  => 'section-blog',
					'priority' => 50,
					'title'    => __( 'Post Structure', 'astra' ),
					'choices'  => array(
						'image'      => __( 'Featured Image', 'astra' ),
						'title-meta' => __( 'Title & Blog Meta', 'astra' ),
					),
				),

				/**
				 * Option: Display Post Meta
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-meta]',
					'type'     => 'control',
					'control'  => 'ast-sortable',
					'section'  => 'section-blog',
					'default'  => astra_get_option( 'blog-meta' ),
					'priority' => 50,
					'required' => array( ASTRA_THEME_SETTINGS . '[blog-post-structure]', 'contains', 'title-meta' ),
					'title'    => __( 'Meta', 'astra' ),
					'choices'  => array(
						'comments' => __( 'Comments', 'astra' ),
						'category' => __( 'Category', 'astra' ),
						'author'   => __( 'Author', 'astra' ),
						'date'     => __( 'Publish Date', 'astra' ),
						'tag'      => __( 'Tag', 'astra' ),
					),
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;

		}
	}
}


new Astra_Blog_Layout_Configs;





