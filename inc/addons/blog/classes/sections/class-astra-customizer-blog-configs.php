<?php
/**
 * Astra Blog  General Options for our theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2021, Brainstorm Force
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

/**
 * Customizer Sanitizes
 *
 * @since x.x.x
 */

/**
 * Register General Customizer Configurations.
 */
class Astra_Customizer_Blog_Configs extends Astra_Customizer_Config_Base {

	/**
	 * Register General Customizer Configurations.
	 *
	 * @param Array                $configurations Astra Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since x.x.x
	 * @return Array Astra Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$_configs = array(
			/**
			 * Option: Space Between Post
			 */
			array(
				'name'      => ASTRA_THEME_SETTINGS . '[blog-space-bet-posts]',
				'default'   => astra_get_option( 'blog-space-bet-posts' ),
				'type'      => 'control',
				'control'   => 'ast-toggle-control',
				'section'   => 'section-blog',
				'title'     => __( 'Add Space Between Posts', 'astra-addon' ),
				'transport' => 'postMessage',
				'priority'  => 15,
				'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			/**
			 * Option: Read more text
			 */
			array(
				'name'     => ASTRA_THEME_SETTINGS . '[blog-read-more-text]',
				'default'  => astra_get_option( 'blog-read-more-text' ),
				'type'     => 'control',
				'section'  => 'section-blog',
				'priority' => 85,
				'title'    => __( 'Read More Text', 'astra-addon' ),
				'control'  => 'text',
				'context'  => array(
					Astra_Builder_Helper::$general_tab_config,
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[blog-post-content]',
						'operator' => '===',
						'value'    => 'excerpt',
					),
				),
			),
			
			/**
			 * Option: Excerpt Count
			 */
			array(
				'name'        => ASTRA_THEME_SETTINGS . '[blog-excerpt-count]',
				'default'     => astra_get_option( 'blog-excerpt-count' ),
				'type'        => 'control',
				'control'     => 'number',
				'section'     => 'section-blog',
				'priority'    => 80,
				'title'       => __( 'Excerpt Count', 'astra-addon' ),
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 3000,
				),
				'context'     => array(
					Astra_Builder_Helper::$general_tab_config,
					array(
						'setting'  => ASTRA_THEME_SETTINGS . '[blog-post-content]',
						'operator' => '===',
						'value'    => 'excerpt',
					),
				),
			),

			/**
			 * Option: Post Inside Spacing
			 */
			array(
				'name'              => ASTRA_THEME_SETTINGS . '[blog-post-inside-spacing]',
				'default'           => astra_get_option( 'blog-post-inside-spacing' ),
				'type'              => 'control',
				'control'           => 'ast-responsive-spacing',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
				'transport'         => 'postMessage',
				'section'           => 'section-blog',
				'context'           => ( true === Astra_Builder_Helper::$is_header_footer_builder_active ) ?
					Astra_Builder_Helper::$design_tab : Astra_Builder_Helper::$general_tab,
				'priority'          => 170,
				'title'             => __( 'Inside Post Spacing', 'astra-addon' ),
				'linked_choices'    => true,
				'unit_choices'      => array( 'px', 'em', '%' ),
				'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
				'choices'           => array(
					'top'    => __( 'Top', 'astra-addon' ),
					'right'  => __( 'Right', 'astra-addon' ),
					'bottom' => __( 'Bottom', 'astra-addon' ),
					'left'   => __( 'Left', 'astra-addon' ),
				),
			),
		);
		if ( ! defined( 'ASTRA_EXT_VER' ) || ( defined( 'ASTRA_EXT_VER' ) && ! Astra_Ext_Extension::is_active( 'blog-pro' ) ) ) {
			$grid_config = array(
				/**
				 * Option: Grid Layout
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-grid]',
					'type'     => 'control',
					'control'  => 'ast-select',
					'section'  => 'section-blog',
					'default'  => astra_get_option( 'blog-grid' ),
					'priority' => 10,
					'title'    => __( 'Grid Layout', 'astra-addon' ),
					'choices'  => array(
						'1' => __( '1 Column', 'astra-addon' ),
						'2' => __( '2 Columns', 'astra-addon' ),
						'3' => __( '3 Columns', 'astra-addon' ),
						'4' => __( '4 Columns', 'astra-addon' ),
					),
					'divider'  => array( 'ast_class' => 'ast-bottom-divider' ),
				),
			);
			$_configs    = array_merge( $_configs, $grid_config );
		}

		return array_merge( $configurations, $_configs );
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Blog_Configs();
