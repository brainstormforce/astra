<?php
/**
 * Rest routes used for e2e tests.
 *
 * @package Astra.
 */

namespace Astra\REST;

use WP_Rest_Server;
use WP_Rest_Request;

const REST_NAMESPACE = 'astra/v1';
const REST_BASE      = 'e2e-utils';

/**
 * Bootstrap the plugin.
 *
 * @return void
 */
function bootstrap() : void {
	add_action( 'rest_api_init', __NAMESPACE__ . '\\rest_route' );
}

/**
 * Register rest routes.
 *
 * @return void
 */
function rest_route() : void {
	register_rest_route(
		REST_NAMESPACE,
		REST_BASE . '/reset-theme',
		array(
			array(
				'methods'             => WP_Rest_Server::DELETABLE,
				'callback'            => function () {
					delete_option( 'astra-settings' );
					remove_theme_mod( 'custom_logo' );
					delete_option( 'site_title' );
					delete_option( 'site_icon' );
					update_option( 'blogdescription', 'Astra Test Enviornment' );

					return rest_ensure_response(
						array(
							'success' => true,
						)
					);
				},
				'permission_callback' => '__return_true',
			),
		)
	);

	register_rest_route(
		REST_NAMESPACE,
		REST_BASE . '/set-astra-settings',
		array(
			array(
				'methods'             => WP_Rest_Server::CREATABLE,
				'callback'            => function ( WP_Rest_Request $response ) {
					$current_options = get_option( 'astra-settings', array() );
					update_option( 'astra-settings', array_merge( $current_options, $response['settings'] ) );

					return rest_ensure_response(
						array(
							'success' => true,
						)
					);
				},
				'permission_callback' => '__return_true',
				'args'                => array(
					'settings' => array(
						'default'  => array(),
						'required' => true,
					),
				),
			),
		)
	);
}
