<?php
/**
 * Customizer Control: Settings Group
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2019, Astra
 * @link        https://wpastra.com/
 * @since       2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Control_Settings_Group' ) && class_exists( 'WP_Customize_Control' ) ) :

	/**
	 * A Settings group control.
	 */
	class Astra_Control_Settings_Group extends WP_Customize_Control {


		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'ast-settings-group';

		/**
		 * The text to display.
		 *
		 * @access public
		 * @var string
		 */
		public $text = '';

		/**
		 * The control name.
		 *
		 * @access public
		 * @var string
		 */
		public $name = '';

		/**
		 * The control value.
		 *
		 * @access public
		 * @var string
		 */
		public $value = '';

		/**
		 * The fields for group.
		 *
		 * @access public
		 * @var string
		 */
		public $ast_fields = '';

		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $help = '';

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @access public
		 */
		public function enqueue() {
			$assets_uri = ASTRA_THEME_URI . 'inc/customizer/custom-controls/settings-group/';
			wp_enqueue_style( 'astra-settings-group', $assets_uri . 'settings-group.css', null, ASTRA_THEME_VERSION );

			wp_enqueue_script( 'astra-settings-group-script', $assets_uri . 'settings-group.js', array( 'jquery', 'jquery-ui-tabs', 'customize-base' ), ASTRA_THEME_VERSION, true );
		}

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json() {
			parent::to_json();

			$this->json['label'] = esc_html( $this->label );
			$this->json['text']  = $this->text;
			$this->json['help']  = $this->help;
			$this->json['name']  = $this->name;

			$this->json['value']      = is_array( $this->value() ) ? json_encode( $this->value() ) : $this->value();
			$this->json['ast_fields'] = $this->ast_fields;
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
		protected function content_template() {             ?>

		<div class="ast-toggle-desc-wrap">
			<label class="customizer-text">
				<# if ( data.label ) { #>
					<span class="customize-control-title">{{{ data.label }}}</span>
					<# } #>
						<# if ( data.help ) { #>
							<span class="ast-description">{{{ data.help }}}</span>
							<# } #>
								<span class="ast-adv-toggle-icon dashicons" data-control="{{ data.name }}"></span>
			</label>
		</div>
		<div class="customize-control-content">
			<input type="hidden" data-name="{{ data.name }}" class="ast-hidden-input" value="{{ data.value }}">
		</div>
		<div class="ast-field-settings-wrap">
		</div>
			<?php
		}

		/**
		 * Render the control's content.
		 *
		 * @see WP_Customize_Control::render_content()
		 */
		protected function render_content() {       }
	}

endif;
