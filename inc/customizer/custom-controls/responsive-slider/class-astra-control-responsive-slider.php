<?php
/**
 * Customizer Control: slider.
 *
 * Creates a jQuery slider control.
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
 * Responsive Slider control (range).
 */
class Astra_Control_Responsive_Slider extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'ast-responsive-slider';

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

		$this->json['value']  = $val;
		$this->json['link']   = $this->get_link();
		$this->json['id']     = $this->id;
		$this->json['label']  = esc_html( $this->label );
		$this->json['suffix'] = $this->suffix;

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
		$reset = __( 'Back to default', 'astra' );
		?>
		<label for="">
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{{ data.label }}}</span>
				<ul class="ast-responsive-slider-btns">
					<li class="desktop active">
						<button type="button" class="preview-desktop active" data-device="desktop">
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
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } 

			value_desktop = '';
			value_tablet  = '';
			value_mobile  = '';
			default_desktop = '';
			default_tablet  = '';
			default_mobile  = '';

			if ( data.value['desktop'] ) { 
				value_desktop = data.value['desktop'];
			} 

			if ( data.value['tablet'] ) { 
				value_tablet = data.value['tablet'];
			} 

			if ( data.value['mobile'] ) { 
				value_mobile = data.value['mobile'];
			}

			if ( data.default['desktop'] ) { 
				default_desktop = data.default['desktop'];
			} 

			if ( data.default['tablet'] ) { 
				default_tablet = data.default['tablet'];
			} 

			if ( data.default['mobile'] ) { 
				default_mobile = data.default['mobile'];
			} #>
			<div class="wrapper">
				<div class="input-field-wrapper desktop active">
					<input {{{ data.inputAttrs }}} type="range" value="{{ value_desktop }}" data-reset_value="{{ default_desktop }}" />
					<div class="astra_range_value">
						<input type="number" data-id='desktop' class="ast-responsive-range-value-input" value="{{ value_desktop }}" {{{ data.inputAttrs }}} ><#
						if ( data.suffix ) {
						#><span class="ast-range-unit">{{ data.suffix }}</span><#
						} #>
					</div>
				</div>
				<div class="input-field-wrapper tablet">
					<input {{{ data.inputAttrs }}} type="range" value="{{ value_tablet }}" data-reset_value="{{ default_tablet }}" />
					<div class="astra_range_value">
						<input type="number" data-id='tablet' class="ast-responsive-range-value-input" value="{{ value_tablet }}" {{{ data.inputAttrs }}} ><#
						if ( data.suffix ) {
						#><span class="ast-range-unit">{{ data.suffix }}</span><#
						} #>
					</div>
				</div>
				<div class="input-field-wrapper mobile">
					<input {{{ data.inputAttrs }}} type="range" value="{{ value_mobile }}" data-reset_value="{{ default_mobile }}" />
					<div class="astra_range_value">
						<input type="number" data-id='mobile' class="ast-responsive-range-value-input" value="{{ value_mobile }}" {{{ data.inputAttrs }}} ><#
						if ( data.suffix ) {
						#><span class="ast-range-unit">{{ data.suffix }}</span><#
						} #>
					</div>
				</div>
				<div class="ast-responsive-slider-reset">
					<span class="dashicons dashicons-image-rotate ast-control-tooltip" title="<?php echo esc_html( $reset ); ?>" ></span>

				</div>
			</div>
		</label>
		<?php
	}

	/**
	 * Render the control's content.
	 *
	 * @see WP_Customize_Control::render_content()
	 */
	protected function render_content() {}
}
