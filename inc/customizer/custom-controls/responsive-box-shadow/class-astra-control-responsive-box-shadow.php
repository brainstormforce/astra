<?php
/**
 * Customizer Control: responsive box-shadow
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sortable control (uses checkboxes).
 */
class Astra_Control_Responsive_Box_Shadow extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'ast-responsive-box-shadow';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$this->json['default'] = $this->setting->default;
		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}

		$val = maybe_unserialize( $this->value() );

		if ( ! is_array( $val ) || is_numeric( $val ) ) {

			$val = array(
				'desktop'      => array(
					'x'    => $val,
					'y'  => $val,
					'blur' => $val,
					'spread'   => $val,
				),
				'tablet'       => array(
					'x'    => $val,
					'y'  => $val,
					'blur' => $val,
					'spread'   => $val,
				),
				'mobile'       => array(
					'x'    => $val,
					'y'  => $val,
					'blur' => $val,
					'spread'   => $val,
				),
			);
		}

		$this->json['value']          = $val;
		$this->json['choices']        = $this->choices;
		$this->json['id']             = $this->id;
		$this->json['label']          = esc_html( $this->label );
		// $this->json['linked_choices'] = $this->linked_choices;
		// $this->json['unit_choices']   = $this->unit_choices;
		$this->json['inputAttrs']     = '';
		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}
		$this->json['inputAttrs'] = maybe_serialize( $this->input_attrs() );

	}

	/**
	 * Render the control's content.
	 *
	 * @see WP_Customize_Control::render_content()
	 */
	protected function render_content() {}
}
