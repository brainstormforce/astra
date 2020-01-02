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
 * Astra Breadcrumb Schema Markup.
 *
 * @since 2.1.3
 */
class Astra_Breadcrumb_Schema extends Astra_Schema {

	/**
	 * Setup schema
	 *
	 * @since 2.1.3
	 */
	public function setup_schema() {
		add_action( 'wp', array( $this, 'disable_schema_before_title' ), 20 );
	}

	/**
	 * Disable Schema for Before Title option of Breadcrumb Position.
	 *
	 * @since 2.1.3
	 *
	 * @return void
	 */
	public function disable_schema_before_title() {
		$breadcrumb_position = astra_get_option( 'breadcrumb-position' );
		$breadcrumb_source   = astra_get_option( 'select-breadcrumb-source' );

		if ( ( 'astra_entry_top' === $breadcrumb_position && ( 'default' === $breadcrumb_source || empty( $breadcrumb_source ) ) ) || ( true !== $this->schema_enabled() ) ) {
			add_filter( 'astra_breadcrumb_trail_args', array( $this, 'breadcrumb_schema' ) );
		}
	}

	/**
	 * Disable schema by passing false to the 'schema' param to the filter.
	 *
	 * @since 2.1.3
	 *
	 * @param  array $args An array of default values.
	 *
	 * @return array       Updated schema param.
	 */
	public function breadcrumb_schema( $args ) {
		$args['schema'] = false;

		return $args;
	}

	/**
	 * Enabled schema
	 *
	 * @since 2.1.3
	 */
	protected function schema_enabled() {
		return apply_filters( 'astra_breadcrumb_schema_enabled', parent::schema_enabled() );
	}

}

new Astra_Breadcrumb_Schema();
