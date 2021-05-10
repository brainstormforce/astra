<?php
/**
 * Customizer Control: typography.
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
 * Typography control.
 */
final class Astra_Control_Typography extends WP_Customize_Control {

	/**
	 * Used to connect controls to each other.
	 *
	 * @since 1.0.0
	 * @var bool $connect
	 */
	public $connect = false;

	/**
	 * Option name.
	 *
	 * @since 1.0.0
	 * @var string $name
	 */
	public $name = '';

	/**
	 * Option label.
	 *
	 * @since 1.0.0
	 * @var string $label
	 */
	public $label = '';

	/**
	 * Option description.
	 *
	 * @since 1.0.0
	 * @var string $description
	 */
	public $description = '';

	/**
	 * Control type.
	 *
	 * @since 1.0.0
	 * @var string $type
	 */
	public $type = 'ast-font';

	/**
	 * Used to connect variant controls to each other.
	 *
	 * @since 1.5.2
	 * @var bool $variant
	 */
	public $variant = false;

	/**
	 * Used to set the mode for code controls.
	 *
	 * @since 1.0.0
	 * @var bool $mode
	 */
	public $mode = 'html';

	/**
	 * Used to set the default font options.
	 *
	 * @since 1.0.8
	 * @var string $ast_inherit
	 */
	public $ast_inherit = '';

	/**
	 * All font weights
	 *
	 * @since 1.0.8
	 * @var string $ast_inherit
	 */
	public $ast_all_font_weight = array();

	/**
	 * If true, the preview button for a control will be rendered.
	 *
	 * @since 1.0.0
	 * @var bool $preview_button
	 */
	public $preview_button = false;

	/**
	 * Set the default font options.
	 *
	 * @since 1.0.8
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    Default parent's arguments.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		$this->ast_inherit         = __( 'Inherit', 'astra' );
		$this->ast_all_font_weight = array(
			'100'       => __( 'Thin 100', 'astra' ),
			'100italic' => __( '100 Italic', 'astra' ),
			'200'       => __( 'Extra-Light 200', 'astra' ),
			'200italic' => __( '200 Italic', 'astra' ),
			'300'       => __( 'Light 300', 'astra' ),
			'300italic' => __( '300 Italic', 'astra' ),
			'400'       => __( 'Normal 400', 'astra' ),
			'italic'    => __( '400 Italic', 'astra' ),
			'500'       => __( 'Medium 500', 'astra' ),
			'500italic' => __( '500 Italic', 'astra' ),
			'600'       => __( 'Semi-Bold 600', 'astra' ),
			'600italic' => __( '600 Italic', 'astra' ),
			'700'       => __( 'Bold 700', 'astra' ),
			'700italic' => __( '700 Italic', 'astra' ),
			'800'       => __( 'Extra-Bold 800', 'astra' ),
			'800italic' => __( '800 Italic', 'astra' ),
			'900'       => __( 'Ultra-Bold 900', 'astra' ),
			'900italic' => __( '900 Italic', 'astra' ),
		);
		parent::__construct( $manager, $id, $args );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {

		parent::to_json();

		$this->json['label']               = esc_html( $this->label );
		$this->json['description']         = $this->description;
		$this->json['name']                = $this->name;
		$this->json['value']               = $this->value();
		$this->json['connect']             = $this->connect;
		$this->json['variant']             = $this->variant;
		$this->json['link']                = $this->get_link();
		$this->json['ast_all_font_weight'] = $this->ast_all_font_weight;
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

		<label>
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{{data.label}}}</span>
		<# } #>

		</label>
		<select data-inherit="<?php echo esc_attr( $this->ast_inherit ); ?>" <?php $this->link(); ?> class={{ data.font_type }} data-name={{ data.name }}
		data-value="{{data.value}}"

		<# if ( data.connect ) { #>
			data-connected-control={{ data.connect }}
		<# } #>
		<# if ( data.variant ) { #>
			data-connected-variant="{{data.variant}}";
		<# } #>

		>
		</select>

		<?php
	}
}
