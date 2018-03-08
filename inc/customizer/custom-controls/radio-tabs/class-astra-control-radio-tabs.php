<?php
/**
 * Customizer Control: radio-tabs.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Radio Image control (modified radio).
 */
class Astra_Control_Radio_Tabs extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'ast-radio-tabs';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		$css_uri = ASTRA_THEME_URI . 'inc/customizer/custom-controls/radio-tabs/';
		$js_uri  = ASTRA_THEME_URI . 'inc/customizer/custom-controls/radio-tabs/';

		wp_enqueue_script( 'astra-radio-tabs', $js_uri . 'radio-tabs.js', array( 'jquery', 'customize-base' ), ASTRA_THEME_VERSION, true );
		wp_enqueue_style( 'astra-radio-tabs', $css_uri . 'radio-tabs.css', null, ASTRA_THEME_VERSION );
	}

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
		$this->json['value'] = $this->value();

		foreach ( $this->choices as $key => $value ) {
			$this->json['choices'][ $key ] = $value;
		}

		$this->json['link'] = $this->get_link();
		$this->json['id']   = $this->id;

		$this->json['inputAttrs'] = '';
		$this->json['labelStyle'] = '';
		foreach ( $this->input_attrs as $attr => $value ) {
			if ( 'style' !== $attr ) {
				$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
			} else {
				$this->json['labelStyle'] = 'style="' . esc_attr( $value ) . '" ';
			}
		}

	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<div id="input_{{ data.id }}" class="radio-tabs-wrapper">
			<# for ( key in data.choices ) { #>
				<# if ( data.choices[ key ].length > 0 ) { #>
					<label for="{{ data.id }}{{ key }}" {{{ data.labelStyle }}} <# if ( 'layout' == key ) { #> class="activated" <# } #> data-dependent-control="{{ data.choices[key] }}" >
						<span class="image-clickable">{{ key }}</span>
					</label>
				<# } #>
			<# } #>
		</div>
		<?php
	}
}
