<?php
/**
 * Schema markup.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra 2.1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Astra Schema Markup.
 *
 * @since 2.1.3
 */
class Astra_Schema {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->include_schemas();

		add_action( 'wp', array( $this, 'setup_schema' ) );
	}

	/**
	 * Setup schema
	 *
	 * @since 2.1.3
	 */
	public function setup_schema() { }

	/**
	 * Include schema files.
	 *
	 * @since 2.1.3
	 */
	private function include_schemas() {
		require_once ASTRA_THEME_DIR . 'inc/schema/class-astra-creativework-schema.php';
		require_once ASTRA_THEME_DIR . 'inc/schema/class-astra-wpheader-schema.php';
		require_once ASTRA_THEME_DIR . 'inc/schema/class-astra-wpfooter-schema.php';
		require_once ASTRA_THEME_DIR . 'inc/schema/class-astra-wpsidebar-schema.php';
		require_once ASTRA_THEME_DIR . 'inc/schema/class-astra-person-schema.php';
		require_once ASTRA_THEME_DIR . 'inc/schema/class-astra-organization-schema.php';
		require_once ASTRA_THEME_DIR . 'inc/schema/class-astra-site-navigation-schema.php';
		require_once ASTRA_THEME_DIR . 'inc/schema/class-astra-breadcrumb-schema.php';
	}

	/**
	 * Enabled schema
	 *
	 * @since 2.1.3
	 */
	protected function schema_enabled() {
		return apply_filters( 'astra_schema_enabled', true );
	}

}

new Astra_Schema();
