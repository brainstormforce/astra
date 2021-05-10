<?php
/**
 * Customizer Control: Variant.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2020, Astra
 * @link        https://wpastra.com/
 * @since       3.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Variant control.
 */
final class Astra_Control_Font_Variant extends WP_Customize_Control {

	/**
	 * Used to connect controls to each other.
	 *
	 * @since 3.0.0
	 * @var bool $connect
	 */
	public $connect = false;

	/**
	 * Option name.
	 *
	 * @since 3.0.0
	 * @var string $name
	 */
	public $name = '';

	/**
	 * Option label.
	 *
	 * @since 3.0.0
	 * @var string $label
	 */
	public $label = '';

	/**
	 * Option description.
	 *
	 * @since 3.0.0
	 * @var string $description
	 */
	public $description = '';

	/**
	 * Control type.
	 *
	 * @since 3.0.0
	 * @var string $type
	 */
	public $type = 'ast-font-variant';

	/**
	 * Used to connect variant controls to each other.
	 *
	 * @since 1.5.2
	 * @var bool $variant
	 */
	public $variant = false;

	/**
	 * Used to set the default font options.
	 *
	 * @since 1.0.8
	 * @var string $ast_inherit
	 */
	public $ast_inherit = '';

	/**
	 * Set the default font options.
	 *
	 * @since 3.0.0
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    Default parent's arguments.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		$this->ast_inherit = __( 'Inherit', 'astra' );
		parent::__construct( $manager, $id, $args );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 3.0.0
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {

		parent::to_json();

		$this->json['label']       = esc_html( $this->label );
		$this->json['description'] = $this->description;
		$this->json['name']        = $this->name;
		$this->json['value']       = $this->value();
		$this->json['connect']     = $this->connect;
		$this->json['variant']     = $this->variant;
		$this->json['link']        = $this->get_link();
	}

	/**
	 * COntent Template for the Control rendering.
	 *
	 * @see WP_Customize_Control::print_template()
	 * @since 3.0.0
	 * @access protected
	 */
	protected function content_template() {

		?>

		<label>
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{{data.label}}}</span>
		<# } #>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{data.description}}}</span>
		<# } #>

		</label>
		<select data-inherit="<?php echo esc_attr( $this->ast_inherit ); ?>" <?php $this->link(); ?>  multiple data-name={{ data.name }}
		data-value="{{data.value}}"

		<# if ( data.connect ) { #>
			data-connected-control={{ data.connect }}
		<# } #>
		<# if ( data.variant ) { #>
			data-connected-variant="{{data.variant}}";
		<# } #>

		>
		<?php
			$values = explode( ',', $this->value() );
		foreach ( $values as $key => $value ) {
			echo '<option value="' . esc_attr( $value ) . '" selected="selected" >' . esc_html( $value ) . '</option>';
		}
		?>
		<input class="ast-font-variant-hidden-value" type="hidden" value="{{data.value}}">
		</select>
		<span class="ast-control-tooltip dashicons dashicons-editor-help ast-variant-description" title="<?php esc_attr_e( 'Only selected Font Variants will be loaded from Google Fonts.', 'astra' ); ?>"></span>
		<?php

	}
}
