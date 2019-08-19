<?php
/**
 * Customizer Control: color.
 *
 * Creates a jQuery color control.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2019, Astra
 * @link        https://wpastra.com/
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Field overrides.
 */
if ( ! class_exists( 'Astra_Control_Responsive_Color' ) && class_exists( 'WP_Customize_Control' ) ) :

	/**
	 * Color control (alpha).
	 */
	class Astra_Control_Responsive_Color extends WP_Customize_Control {

		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'ast-responsive-color';

		/**
		 * The responsive type.
		 *
		 * @access public
		 * @var string
		 */
		public $responsive = false;

		/**
		 * The color with opacity rgba type.
		 *
		 * @access public
		 * @var string
		 */
		public $rgba = false;

		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $suffix = '';

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
					'desktop' => $val,
					'tablet'  => '',
					'mobile'  => '',
				);
			}

			$this->json['value']      = $val;
			$this->json['link']       = $this->get_link();
			$this->json['id']         = $this->id;
			$this->json['label']      = esc_html( $this->label );
			$this->json['suffix']     = $this->suffix;
			$this->json['responsive'] = $this->responsive;
			$this->json['rgba']       = $this->rgba;

			$this->json['inputAttrs'] = '';
			foreach ( $this->input_attrs as $attr => $value ) {
				$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
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

			<# var defaultValue = '#RRGGBB', defaultValueAttr = '';

			if ( data.defaultValue ) {
				if ( '#' !== data.defaultValue.substring( 0, 1 ) ) {
					defaultValue = '#' + data.defaultValue;
				} else {
					defaultValue = data.defaultValue;
				}
				defaultValueAttr = ' data-default-color=' + defaultValue; // Quotes added automatically.
			} #>
			<label>
				<# if ( data.label ) { #>
					<span class="customize-control-title">{{{ data.label }}}</span>
					<# } #>
				<# if ( data.description ) { #>
					<span class="description customize-control-description">{{{ data.description }}}</span>
				<# } #>
				</label>

				<# if ( data.responsive ) { #>
					<ul class="ast-responsive-btns">
						<li class="desktop">
							<button type="button" class="preview-desktop" data-device="desktop">
								<i class="dashicons dashicons-desktop"></i>
							</button>
						</li>
						<li class="tablet">
							<button type="button" class="preview-tablet" data-device="tablet">
								<i class="dashicons dashicons-tablet"></i>
							</button>
						</li>
						<li class="mobile">
							<button type="button" class="preview-mobile" data-device="mobile">
								<i class="dashicons dashicons-smartphone"></i>
							</button>
						</li>
					</ul>
				<# } #>

					<# value_desktop = '';
					value_tablet  = '';
					value_mobile  = '';

					if ( data.value['desktop'] ) { 
						value_desktop = data.value['desktop'];
					} 

					if ( data.value['tablet'] ) { 
						value_tablet = data.value['tablet'];
					} 

					if ( data.value['mobile'] ) { 
						value_mobile = data.value['mobile'];
					} #>

				<div class="customize-control-content">

					<# if ( data.responsive ) { #>

						<input class="ast-color-picker-alpha color-picker-hex ast-responsive-color desktop active" type="text" maxlength="7" data-name="{{data.name}}" data-alpha="{{ data.rgba }}" data-id='desktop' placeholder="{{ defaultValue }}" {{ defaultValueAttr }} value="{{ value_desktop }}" />

						<input class="ast-color-picker-alpha color-picker-hex ast-responsive-color tablet" type="text" maxlength="7" data-name="{{data.name}}" data-alpha="{{ data.rgba }}" data-id='tablet' placeholder="{{ defaultValue }}" {{ defaultValueAttr }} value="{{ value_tablet }}" />

						<input class="ast-color-picker-alpha color-picker-hex ast-responsive-color mobile" type="text" maxlength="7" data-name="{{data.name}}" data-alpha="{{ data.rgba }}" data-id='mobile' placeholder="{{ defaultValue }}" {{ defaultValueAttr }} value="{{ value_mobile }}" />

					<# } else { #>

					<# } #>
				</div>
			<?php
		}

		/**
		 * Render the control's content.
		 *
		 * @see WP_Customize_Control::render_content()
		 */
		protected function render_content() {}
	}

endif;
