<?php
/**
 * Customizer Control: responsive spacing
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
if ( ! class_exists( 'Astra_Control_Border' ) && class_exists( 'WP_Customize_Control' ) ) :


	/**
	 * Border control.
	 */
	class Astra_Control_Border extends WP_Customize_Control {

		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'ast-border';

		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $linked_choices = '';

		/**
		 * The unit type.
		 *
		 * @access public
		 * @var array
		 */
		public $unit_choices = array( 'px' => 'px' );

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

			$this->json['value']          = $val;
			$this->json['choices']        = $this->choices;
			$this->json['link']           = $this->get_link();
			$this->json['id']             = $this->id;
			$this->json['label']          = esc_html( $this->label );
			$this->json['linked_choices'] = $this->linked_choices;
			$this->json['unit_choices']   = $this->unit_choices;
			$this->json['inputAttrs']     = '';
			foreach ( $this->input_attrs as $attr => $value ) {
				$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
			}
			$this->json['inputAttrs'] = maybe_serialize( $this->input_attrs() );
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

			$item_link_desc = __( 'Link Values Together', 'astra' );
			?>
			<label class='ast-border' for="" >

			<# if ( data.label ) { #>
				<span class="customize-control-title">{{{ data.label }}}</span>
			<# } #>
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>

			<div class="ast-border-outer-wrapper">
				<div class="input-wrapper ast-border-wrapper">

					<ul class="ast-border-wrapper desktop active"><# 
						if ( data.linked_choices ) { #>
						<li class="ast-border-input-item-link">
								<span class="dashicons dashicons-admin-links ast-border-connected wp-ui-highlight" data-element-connect="{{ data.id }}" title="<?php echo esc_html( $item_link_desc ); ?>"></span>
								<span class="dashicons dashicons-editor-unlink ast-border-disconnected" data-element-connect="{{ data.id }}" title="<?php echo esc_html( $item_link_desc ); ?>"></span>
							</li><#
						}
						_.each( data.choices, function( choiceLabel, choiceID ) {
						#><li {{{ data.inputAttrs }}} class='ast-border-input-item'>
							<input type='number' class='ast-border-input ast-border-desktop' data-id= '{{ choiceID }}' data-name="{{ data.name }}" value='{{ data.value[ choiceID ] }}'>
							<span class="ast-border-title">{{{ data.choices[ choiceID ] }}}</span>
						</li><#
						}); #>
					</ul>
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

endif;
