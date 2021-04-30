<?php
/**
 * Related Posts Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2021, Astra
 * @link        https://wpastra.com/
 * @since       Astra 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Related_Posts_Configs' ) ) {

	/**
	 * Register Related Posts Configurations.
	 */
	class Astra_Related_Posts_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Related Posts Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.4.0
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Related Posts setting.
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
					'default'  => astra_get_option( 'enable-related-posts' ),
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'title'    => __( 'Enable Related Posts', 'astra' ),
					'section'  => 'section-blog-single',
					'priority' => 10,
				),

				/**
				 * Option: Related Posts Query
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[related-posts-section-heading]',
					'section'  => 'section-blog-single',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'title'    => __( 'Related Posts', 'astra' ),
					'context'  => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'priority' => 11,
				),

				/**
				 * Option: Related Posts Structure
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[related-posts-structure]',
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'section'           => 'section-blog-single',
					'default'           => astra_get_option( 'related-posts-structure' ),
					'priority'          => 12,
					'context'           => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'title'             => __( 'Posts Structure', 'astra' ),
					'choices'           => array(
						'featured-image' => __( 'Featured Image', 'astra' ),
						'title-meta'     => __( 'Title & Post Meta', 'astra' ),
					),
					'divider'           => array( 'ast_class' => 'ast-top-divider' ),
				),

				array(
					'name'              => ASTRA_THEME_SETTINGS . '[related-posts-meta-structure]',
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'default'           => astra_get_option( 'related-posts-meta-structure' ),
					'context'           => array(
						Astra_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[related-posts-structure]',
							'operator' => 'contains',
							'value'    => 'title-meta',
						),
					),
					'section'           => 'section-blog-single',
					'priority'          => 12,
					'title'             => __( 'Meta', 'astra' ),
					'choices'           => array(
						'comments' => __( 'Comments', 'astra' ),
						'category' => __( 'Category', 'astra' ),
						'author'   => __( 'Author', 'astra' ),
						'date'     => __( 'Publish Date', 'astra' ),
						'tag'      => __( 'Tag', 'astra' ),
					),
				),

				/**
				 * Option: Enable excerpt for Related Posts.
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[enable-related-posts-excerpt]',
					'default'  => astra_get_option( 'enable-related-posts-excerpt' ),
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'title'    => __( 'Enable Post Excerpt', 'astra' ),
					'section'  => 'section-blog-single',
					'priority' => 12,
					'context'  => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
					),
				),

				/**
				 * Option: Excerpt word count for Related Posts
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[related-posts-excerpt-count]',
					'default'     => astra_get_option( 'related-posts-excerpt-count' ),
					'type'        => 'control',
					'control'     => 'ast-slider',
					'context'     => array(
						Astra_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts-excerpt]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'section'     => 'section-blog-single',
					'title'       => __( 'Excerpt Word Count', 'astra' ),
					'priority'    => 12,
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 60,
					),
				),

				/**
				 * Option: No. of Related Posts
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[related-posts-total-count]',
					'default'     => astra_get_option( 'related-posts-total-count' ),
					'type'        => 'control',
					'control'     => 'ast-slider',
					'context'     => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'section'     => 'section-blog-single',
					'title'       => __( 'Total Number of Related Posts', 'astra' ),
					'priority'    => 11,
					'input_attrs' => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 20,
					),
					'divider'     => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				/**
				 * Option: Related Posts Columns
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[related-posts-grid]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-blog-single',
					'default'    => astra_get_option( 'related-posts-grid' ),
					'priority'   => 11,
					'context'    => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'title'      => __( 'Grid Column Layout', 'astra' ),
					'choices'    => array(
						'1' => __( '1', 'astra' ),
						'2' => __( '2', 'astra' ),
						'3' => __( '3', 'astra' ),
						'4' => __( '4', 'astra' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
					'divider'    => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				/**
				 * Option: Related Posts Query group setting
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[related-posts-query-group]',
					'default'   => astra_get_option( 'related-posts-query-group' ),
					'type'      => 'control',
					'transport' => 'postMessage',
					'control'   => 'ast-settings-group',
					'context'   => array(
						Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'title'     => __( 'Posts Query', 'astra' ),
					'section'   => 'section-blog-single',
					'priority'  => 11,
				),

				/**
				 * Option: Related Posts based on.
				 */
				array(
					'name'       => 'related-posts-based-on',
					'default'    => astra_get_option( 'related-posts-based-on' ),
					'type'       => 'sub-control',
					'transport'  => 'postMessage',
					'parent'     => ASTRA_THEME_SETTINGS . '[related-posts-query-group]',
					'section'    => 'section-blog-single',
					'priority'   => 1,
					'control'    => 'ast-selector',
					'title'      => __( 'Related Posts by', 'astra' ),
					'choices'    => array(
						'categories' => __( 'Categories', 'astra' ),
						'tags'       => __( 'Tags', 'astra' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Display Post Structure
				 */
				array(
					'name'      => 'related-posts-order-by',
					'default'   => astra_get_option( 'related-posts-order-by' ),
					'parent'    => ASTRA_THEME_SETTINGS . '[related-posts-query-group]',
					'section'   => 'section-blog-single',
					'type'      => 'sub-control',
					'priority'  => 2,
					'transport' => 'postMessage',
					'title'     => __( 'Order by', 'astra' ),
					'control'   => 'ast-select',
					'choices'   => array(
						'date'          => __( 'Date', 'astra' ),
						'title'         => __( 'Title', 'astra' ),
						'post-order'    => __( 'Post Order', 'astra' ),
						'random'        => __( 'Random', 'astra' ),
						'comment-count' => __( 'Comment Counts', 'astra' ),
					),
				),

				/**
				 * Option: Display Post Structure
				 */
				array(
					'name'       => 'related-posts-order',
					'parent'     => ASTRA_THEME_SETTINGS . '[related-posts-query-group]',
					'section'    => 'section-blog-single',
					'type'       => 'sub-control',
					'transport'  => 'postMessage',
					'title'      => __( 'Order', 'astra' ),
					'default'    => astra_get_option( 'related-posts-order' ),
					'control'    => 'ast-selector',
					'priority'   => 3,
					'choices'    => array(
						'asc'  => __( 'Ascending', 'astra' ),
						'desc' => __( 'Descending', 'astra' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Related Posts colors setting group
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[related-posts-colors-group]',
					'default'   => astra_get_option( 'related-posts-colors-group' ),
					'type'      => 'control',
					'transport' => 'postMessage',
					'control'   => 'ast-settings-group',
					'context'   => array(
						true === Astra_Builder_Helper::$is_header_footer_builder_active ?
						Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[related-posts-structure]',
							'operator' => 'contains',
							'value'    => 'title-meta',
						),
					),
					'title'     => __( 'Content Colors', 'astra' ),
					'section'   => 'section-blog-single',
					'priority'  => 15,
				),

				/**
				 * Option: Related Posts title typography setting group
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
					'type'      => 'control',
					'priority'  => 16,
					'control'   => 'ast-settings-group',
					'context'   => array(
						true === Astra_Builder_Helper::$is_header_footer_builder_active ?
						Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'title'     => __( 'Section Title Font', 'astra' ),
					'section'   => 'section-blog-single',
					'transport' => 'postMessage',
				),

				/**
				 * Option: Related Posts title typography setting group
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[related-posts-title-typography-group]',
					'type'      => 'control',
					'priority'  => 17,
					'control'   => 'ast-settings-group',
					'context'   => array(
						true === Astra_Builder_Helper::$is_header_footer_builder_active ?
						Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[related-posts-structure]',
							'operator' => 'contains',
							'value'    => 'title-meta',
						),
					),
					'title'     => __( 'Post Title Font', 'astra' ),
					'section'   => 'section-blog-single',
					'transport' => 'postMessage',
				),

				/**
				 * Option: Related Posts meta typography setting group
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[related-posts-meta-typography-group]',
					'type'      => 'control',
					'priority'  => 18,
					'control'   => 'ast-settings-group',
					'context'   => array(
						true === Astra_Builder_Helper::$is_header_footer_builder_active ?
						Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[related-posts-structure]',
							'operator' => 'contains',
							'value'    => 'title-meta',
						),
					),
					'title'     => __( 'Meta Font', 'astra' ),
					'section'   => 'section-blog-single',
					'transport' => 'postMessage',
				),

				/**
				 * Option: Related Posts content typography setting group
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[related-posts-content-typography-group]',
					'type'      => 'control',
					'priority'  => 21,
					'control'   => 'ast-settings-group',
					'context'   => array(
						true === Astra_Builder_Helper::$is_header_footer_builder_active ?
						Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts-excerpt]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'title'     => __( 'Content Font', 'astra' ),
					'section'   => 'section-blog-single',
					'transport' => 'postMessage',
				),

				/**
				 * Option: Related post block text color
				 */
				array(
					'name'      => 'related-posts-text-color',
					'tab'       => __( 'Normal', 'astra' ),
					'type'      => 'sub-control',
					'parent'    => ASTRA_THEME_SETTINGS . '[related-posts-colors-group]',
					'section'   => 'section-blog-single',
					'default'   => astra_get_option( 'related-posts-text-color' ),
					'transport' => 'postMessage',
					'control'   => 'ast-color',
					'title'     => __( 'Text Color', 'astra' ),
				),

				/**
				 * Option: Related post block CTA link color
				 */
				array(
					'name'      => 'related-posts-link-color',
					'tab'       => __( 'Normal', 'astra' ),
					'type'      => 'sub-control',
					'parent'    => ASTRA_THEME_SETTINGS . '[related-posts-colors-group]',
					'section'   => 'section-blog-single',
					'default'   => astra_get_option( 'related-posts-link-color' ),
					'transport' => 'postMessage',
					'control'   => 'ast-color',
					'title'     => __( 'Link Color', 'astra' ),
				),

				/**
				 * Option: Related Posts Query
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[related-posts-design-section-heading]',
					'section'  => 'section-blog-single',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'title'    => ( true === Astra_Builder_Helper::$is_header_footer_builder_active ) ? __( 'Related Posts', 'astra' ) : __( 'Related Posts Design', 'astra' ),
					'context'  => array(
						true === Astra_Builder_Helper::$is_header_footer_builder_active ?
						Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'priority' => 14,
				),

				/**
				 * Option: Related post block BG color
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[related-posts-title-color]',
					'default'           => astra_get_option( 'related-posts-title-color' ),
					'type'              => 'control',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'section'           => 'section-blog-single',
					'transport'         => 'postMessage',
					'priority'          => 14,
					'context'           => array(
						true === Astra_Builder_Helper::$is_header_footer_builder_active ?
						Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'title'             => __( 'Section Title', 'astra' ),
				),

				/**
				 * Option: Related post block BG color
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[related-posts-background-color]',
					'default'           => astra_get_option( 'related-posts-background-color' ),
					'type'              => 'control',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'section'           => 'section-blog-single',
					'transport'         => 'postMessage',
					'priority'          => 14,
					'context'           => array(
						true === Astra_Builder_Helper::$is_header_footer_builder_active ?
						Astra_Builder_Helper::$design_tab_config : Astra_Builder_Helper::$general_tab_config,
						array(
							'setting'  => ASTRA_THEME_SETTINGS . '[enable-related-posts]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'title'             => __( 'Section Background', 'astra' ),
				),

				/**
				 * Option: Related post meta color
				 */
				array(
					'name'      => 'related-posts-meta-color',
					'default'   => astra_get_option( 'related-posts-meta-color' ),
					'tab'       => __( 'Normal', 'astra' ),
					'type'      => 'sub-control',
					'parent'    => ASTRA_THEME_SETTINGS . '[related-posts-colors-group]',
					'section'   => 'section-blog-single',
					'transport' => 'postMessage',
					'control'   => 'ast-color',
					'title'     => __( 'Meta Color', 'astra' ),
				),

				/**
				 * Option: Related hover CTA link color
				 */
				array(
					'name'      => 'related-posts-link-hover-color',
					'type'      => 'sub-control',
					'tab'       => __( 'Hover', 'astra' ),
					'parent'    => ASTRA_THEME_SETTINGS . '[related-posts-colors-group]',
					'section'   => 'section-blog-single',
					'control'   => 'ast-color',
					'default'   => astra_get_option( 'related-posts-link-hover-color' ),
					'transport' => 'postMessage',
					'title'     => __( 'Link Color', 'astra' ),
				),

				/**
				 * Option: Related hover meta link color
				 */
				array(
					'name'      => 'related-posts-meta-link-hover-color',
					'type'      => 'sub-control',
					'tab'       => __( 'Hover', 'astra' ),
					'parent'    => ASTRA_THEME_SETTINGS . '[related-posts-colors-group]',
					'section'   => 'section-blog-single',
					'control'   => 'ast-color',
					'default'   => astra_get_option( 'related-posts-meta-link-hover-color' ),
					'transport' => 'postMessage',
					'title'     => __( 'Meta Link Color', 'astra' ),
				),

				/**
				 * Option: Related Posts Title Font Family
				 */
				array(
					'name'      => 'related-posts-title-font-family',
					'parent'    => ASTRA_THEME_SETTINGS . '[related-posts-title-typography-group]',
					'section'   => 'section-blog-single',
					'type'      => 'sub-control',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'default'   => astra_get_option( 'related-posts-title-font-family' ),
					'title'     => __( 'Family', 'astra' ),
					'connect'   => ASTRA_THEME_SETTINGS . '[related-posts-title-font-weight]',
				),

				/**
				 * Option: Related Posts Title Font Size
				 */
				array(
					'name'        => 'related-posts-title-font-size',
					'parent'      => ASTRA_THEME_SETTINGS . '[related-posts-title-typography-group]',
					'section'     => 'section-blog-single',
					'type'        => 'sub-control',
					'control'     => 'ast-responsive',
					'default'     => astra_get_option( 'related-posts-title-font-size' ),
					'transport'   => 'postMessage',
					'title'       => __( 'Size', 'astra' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Related Posts Title Font Weight
				 */
				array(
					'name'              => 'related-posts-title-font-weight',
					'parent'            => ASTRA_THEME_SETTINGS . '[related-posts-title-typography-group]',
					'section'           => 'section-blog-single',
					'type'              => 'sub-control',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'related-posts-title-font-weight' ),
					'title'             => __( 'Weight', 'astra' ),
					'connect'           => 'related-posts-title-font-family',
				),

				/**
				 * Option: Related Posts Title Text Transform
				 */
				array(
					'name'      => 'related-posts-title-text-transform',
					'parent'    => ASTRA_THEME_SETTINGS . '[related-posts-title-typography-group]',
					'section'   => 'section-blog-single',
					'type'      => 'sub-control',
					'title'     => __( 'Text Transform', 'astra' ),
					'default'   => astra_get_option( 'related-posts-title-text-transform' ),
					'transport' => 'postMessage',
					'control'   => 'ast-select',
					'choices'   => array(
						''           => __( 'Inherit', 'astra' ),
						'none'       => __( 'None', 'astra' ),
						'capitalize' => __( 'Capitalize', 'astra' ),
						'uppercase'  => __( 'Uppercase', 'astra' ),
						'lowercase'  => __( 'Lowercase', 'astra' ),
					),
				),

				/**
				 * Option: Related Posts Title Line Height
				 */
				array(
					'name'              => 'related-posts-title-line-height',
					'parent'            => ASTRA_THEME_SETTINGS . '[related-posts-title-typography-group]',
					'section'           => 'section-blog-single',
					'type'              => 'sub-control',
					'transport'         => 'postMessage',
					'default'           => astra_get_option( 'related-posts-title-line-height' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'title'             => __( 'Line Height', 'astra' ),
					'control'           => 'ast-slider',
					'suffix'            => '',
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 5,
					),
				),

				/**
				 * Option: Related Posts Title Font Family
				 */
				array(
					'name'      => 'related-posts-section-title-font-family',
					'parent'    => ASTRA_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
					'section'   => 'section-blog-single',
					'type'      => 'sub-control',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'default'   => astra_get_option( 'related-posts-section-title-font-family' ),
					'title'     => __( 'Family', 'astra' ),
					'connect'   => ASTRA_THEME_SETTINGS . '[related-posts-section-title-font-weight]',
				),

				/**
				 * Option: Related Posts Title Font Size
				 */
				array(
					'name'        => 'related-posts-section-title-font-size',
					'parent'      => ASTRA_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
					'section'     => 'section-blog-single',
					'type'        => 'sub-control',
					'control'     => 'ast-responsive',
					'default'     => astra_get_option( 'related-posts-section-title-font-size' ),
					'transport'   => 'postMessage',
					'title'       => __( 'Size', 'astra' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Related Posts Title Font Weight
				 */
				array(
					'name'              => 'related-posts-section-title-font-weight',
					'parent'            => ASTRA_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
					'section'           => 'section-blog-single',
					'type'              => 'sub-control',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'related-posts-section-title-font-weight' ),
					'title'             => __( 'Weight', 'astra' ),
					'connect'           => 'related-posts-section-title-font-family',
				),

				/**
				 * Option: Related Posts Title Text Transform
				 */
				array(
					'name'      => 'related-posts-section-title-text-transform',
					'parent'    => ASTRA_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
					'section'   => 'section-blog-single',
					'type'      => 'sub-control',
					'title'     => __( 'Text Transform', 'astra' ),
					'default'   => astra_get_option( 'related-posts-section-title-text-transform' ),
					'transport' => 'postMessage',
					'control'   => 'ast-select',
					'choices'   => array(
						''           => __( 'Inherit', 'astra' ),
						'none'       => __( 'None', 'astra' ),
						'capitalize' => __( 'Capitalize', 'astra' ),
						'uppercase'  => __( 'Uppercase', 'astra' ),
						'lowercase'  => __( 'Lowercase', 'astra' ),
					),
				),

				/**
				 * Option: Related Posts Title Line Height
				 */
				array(
					'name'              => 'related-posts-section-title-line-height',
					'parent'            => ASTRA_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
					'section'           => 'section-blog-single',
					'type'              => 'sub-control',
					'transport'         => 'postMessage',
					'default'           => astra_get_option( 'related-posts-section-title-line-height' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'title'             => __( 'Line Height', 'astra' ),
					'control'           => 'ast-slider',
					'suffix'            => '',
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 5,
					),
				),

				/**
				 * Option: Related Posts Meta Font Family
				 */
				array(
					'name'      => 'related-posts-meta-font-family',
					'parent'    => ASTRA_THEME_SETTINGS . '[related-posts-meta-typography-group]',
					'section'   => 'section-blog-single',
					'type'      => 'sub-control',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'default'   => astra_get_option( 'related-posts-meta-font-family' ),
					'title'     => __( 'Family', 'astra' ),
					'connect'   => ASTRA_THEME_SETTINGS . '[related-posts-meta-font-weight]',
				),

				/**
				 * Option: Related Posts Meta Font Size
				 */
				array(
					'name'        => 'related-posts-meta-font-size',
					'parent'      => ASTRA_THEME_SETTINGS . '[related-posts-meta-typography-group]',
					'section'     => 'section-blog-single',
					'type'        => 'sub-control',
					'control'     => 'ast-responsive',
					'default'     => astra_get_option( 'related-posts-meta-font-size' ),
					'transport'   => 'postMessage',
					'title'       => __( 'Size', 'astra' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Related Posts Meta Font Weight
				 */
				array(
					'name'              => 'related-posts-meta-font-weight',
					'parent'            => ASTRA_THEME_SETTINGS . '[related-posts-meta-typography-group]',
					'section'           => 'section-blog-single',
					'type'              => 'sub-control',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'related-posts-meta-font-weight' ),
					'title'             => __( 'Weight', 'astra' ),
					'connect'           => 'related-posts-meta-font-family',
				),

				/**
				 * Option: Related Posts Meta Text Transform
				 */
				array(
					'name'      => 'related-posts-meta-text-transform',
					'parent'    => ASTRA_THEME_SETTINGS . '[related-posts-meta-typography-group]',
					'section'   => 'section-blog-single',
					'type'      => 'sub-control',
					'title'     => __( 'Text Transform', 'astra' ),
					'default'   => astra_get_option( 'related-posts-meta-text-transform' ),
					'transport' => 'postMessage',
					'control'   => 'ast-select',
					'choices'   => array(
						''           => __( 'Inherit', 'astra' ),
						'none'       => __( 'None', 'astra' ),
						'capitalize' => __( 'Capitalize', 'astra' ),
						'uppercase'  => __( 'Uppercase', 'astra' ),
						'lowercase'  => __( 'Lowercase', 'astra' ),
					),
				),

				/**
				 * Option: Related Posts Meta Line Height
				 */
				array(
					'name'              => 'related-posts-meta-line-height',
					'parent'            => ASTRA_THEME_SETTINGS . '[related-posts-meta-typography-group]',
					'section'           => 'section-blog-single',
					'type'              => 'sub-control',
					'transport'         => 'postMessage',
					'default'           => astra_get_option( 'related-posts-meta-line-height' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'title'             => __( 'Line Height', 'astra' ),
					'control'           => 'ast-slider',
					'suffix'            => '',
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 5,
					),
				),

				/**
				 * Option: Related Posts Content Font Family
				 */
				array(
					'name'      => 'related-posts-content-font-family',
					'parent'    => ASTRA_THEME_SETTINGS . '[related-posts-content-typography-group]',
					'section'   => 'section-blog-single',
					'type'      => 'sub-control',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'default'   => astra_get_option( 'related-posts-content-font-family' ),
					'title'     => __( 'Family', 'astra' ),
					'connect'   => ASTRA_THEME_SETTINGS . '[related-posts-content-font-weight]',
				),

				/**
				 * Option: Related Posts Content Font Size
				 */
				array(
					'name'        => 'related-posts-content-font-size',
					'parent'      => ASTRA_THEME_SETTINGS . '[related-posts-content-typography-group]',
					'section'     => 'section-blog-single',
					'type'        => 'sub-control',
					'control'     => 'ast-responsive',
					'default'     => astra_get_option( 'related-posts-content-font-size' ),
					'transport'   => 'postMessage',
					'title'       => __( 'Size', 'astra' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Related Posts Content Font Weight
				 */
				array(
					'name'              => 'related-posts-content-font-weight',
					'parent'            => ASTRA_THEME_SETTINGS . '[related-posts-content-typography-group]',
					'section'           => 'section-blog-single',
					'type'              => 'sub-control',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'related-posts-content-font-weight' ),
					'title'             => __( 'Weight', 'astra' ),
					'connect'           => 'related-posts-content-font-family',
				),

				/**
				 * Option: Related Posts Content Text Transform
				 */
				array(
					'name'      => 'related-posts-content-text-transform',
					'parent'    => ASTRA_THEME_SETTINGS . '[related-posts-content-typography-group]',
					'section'   => 'section-blog-single',
					'type'      => 'sub-control',
					'title'     => __( 'Text Transform', 'astra' ),
					'default'   => astra_get_option( 'related-posts-content-text-transform' ),
					'transport' => 'postMessage',
					'control'   => 'ast-select',
					'choices'   => array(
						''           => __( 'Inherit', 'astra' ),
						'none'       => __( 'None', 'astra' ),
						'capitalize' => __( 'Capitalize', 'astra' ),
						'uppercase'  => __( 'Uppercase', 'astra' ),
						'lowercase'  => __( 'Lowercase', 'astra' ),
					),
				),

				/**
				 * Option: Related Posts Content Line Height
				 */
				array(
					'name'              => 'related-posts-content-line-height',
					'parent'            => ASTRA_THEME_SETTINGS . '[related-posts-content-typography-group]',
					'section'           => 'section-blog-single',
					'type'              => 'sub-control',
					'transport'         => 'postMessage',
					'default'           => astra_get_option( 'related-posts-content-line-height' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'title'             => __( 'Line Height', 'astra' ),
					'control'           => 'ast-slider',
					'suffix'            => '',
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 5,
					),
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;
		}
	}
}


new Astra_Related_Posts_Configs();
