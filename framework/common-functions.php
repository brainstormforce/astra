<?php
/**
 * Astra framework initialization.
 *
 * @package Astra
 * @since 1.0.0
 */

/**
 * Function to check if it is Internet Explorer
 */
if ( ! function_exists( 'astra_attr' ) ) :

	/**
	 * Replace header logo.
	 *
	 * @param string  $context name.
	 * @param string  $layout type of layout ( optional ).
	 * @param boolean $echo echo result or return.
	 *
	 * @return string attr output or return.
	 */
	function astra_attr( $context = '', $layout = '', $echo = true ) {

		$attrs_output = '';

		if ( ! empty( $context ) ) {

			$attrs = apply_filters( 'astra_attr_' . $context, array(), $layout );
			if ( is_array( $attrs ) && ! empty( $attrs ) ) {
				foreach ( $attrs as $key => $value ) {

					if ( ! $value ) {
						continue;
					}

					if ( true === $value ) {
						$attrs_output .= esc_html( $key ) . ' ';
					} else {
						/* translators: 1. %s: attrubute key, 2. %s: attribute value */
						$attrs_output .= sprintf( '%s="%s" ', esc_html( $key ), esc_attr( $value ) );
					}
				}
			}
		}

		if ( $echo ) {
			echo $attrs_output;
		} else {
			return $attrs_output;
		}
	}
endif; // End if().
