<?php
/**
 * Astra Generate Markup Class.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       Astra x.x.x
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Astra_Attr
 */
class Astra_Markup {

	/**
	 * Initialuze the Class.
	 *
	 * @since x.x.x
	 */
	public function __construct() {

		if ( ! Astra_Builder_Helper::apply_flex_based_css() ) {
			// Add filters here.
			$demo = 1; // to avoid phpcs empty if error, we be remove this in other PR.
			
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Markup();