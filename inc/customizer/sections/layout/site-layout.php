<?php
/**
 * Site Layout Option for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	/**
	 * Option: Container Width
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-content-width]', array(
			'default'           => 1200,
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'validate_site_width' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[site-content-width]', array(
				'type'        => 'ast-slider',
				'section'     => 'section-site-layout',
				'priority'    => 10,
				'label'       => __( 'Container Width', 'astra' ),
				'suffix'      => '',
				'input_attrs' => array(
					'min'  => 768,
					'step' => 1,
					'max'  => 1920,
				),
			)
		)
	);
