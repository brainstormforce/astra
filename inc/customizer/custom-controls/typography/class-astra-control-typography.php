<?php
/**
 * Customizer Control: typography.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2017, Astra
 * @link        http://wpastra.com/
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
		$this->ast_inherit = __( 'Inherit', 'astra' );
		parent::__construct( $manager, $id, $args );
	}

	/**
	 * Renders the content for a control based on the type
	 * of control specified when this class is initialized.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	protected function render_content() {

		switch ( $this->type ) {

			case 'ast-font-family':
				$this->render_font( $this->ast_inherit );
				break;

			case 'ast-font-weight':
				$this->render_font_weight( $this->ast_inherit );
				break;
		}
	}

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {

		$js_uri = ASTRA_THEME_URI . 'inc/customizer/custom-controls/typography/';

		wp_enqueue_script( 'astra-typography', $js_uri . 'typography.js', array( 'jquery', 'customize-base' ), ASTRA_THEME_VERSION, true );
		$astra_typo_localize = array(
			'inherit' => __( 'Inherit', 'astra' ),
			'100'     => __( 'Thin 100', 'astra' ),
			'200'     => __( 'Extra-Light 200', 'astra' ),
			'300'     => __( 'Light 300', 'astra' ),
			'400'     => __( 'Normal 400', 'astra' ),
			'500'     => __( 'Medium 500', 'astra' ),
			'600'     => __( 'Semi-Bold 600', 'astra' ),
			'700'     => __( 'Bold 700', 'astra' ),
			'800'     => __( 'Extra-Bold 800', 'astra' ),
			'900'     => __( 'Ultra-Bold 900', 'astra' ),
		);

		wp_localize_script( 'astra-typography', 'astraTypo', $astra_typo_localize );
	}
	/**
	 * Renders the title and description for a control.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	protected function render_content_title() {
		if ( ! empty( $this->label ) ) {
			echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
		}
		if ( ! empty( $this->description ) ) {
			echo '<span class="description customize-control-description">' . esc_html( $this->description ) . '</span>';
		}
	}

	/**
	 * Renders the connect attribute for a connected control.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	protected function render_connect_attribute() {
		if ( $this->connect ) {
			echo ' data-connected-control="' . esc_attr( $this->connect ) . '"';
			echo ' data-inherit="' . esc_attr( $this->ast_inherit ) . '"';
		}
	}

	/**
	 * Renders a font control.
	 *
	 * @since 1.0.16 Added the action 'astra_customizer_font_list' to support custom fonts.
	 * @since 1.0.0
	 * @param  string $default Inherit/Default.
	 * @access protected
	 * @return void
	 */
	protected function render_font( $default ) {
		echo '<label>';
		$this->render_content_title();
		echo '<select ';
		$this->link();
		$this->render_connect_attribute();
		echo '>';
		echo '<option value="inherit" ' . selected( 'inherit', $this->value(), false ) . '>' . esc_attr( $default ) . '</option>';
		echo '<optgroup label="System">';

		foreach ( Astra_Font_Families::get_system_fonts() as $name => $variants ) {
			echo '<option value="' . esc_attr( $name ) . '" ' . selected( $name, $this->value(), false ) . '>' . esc_attr( $name ) . '</option>';
		}

		// Add Custom Font List Into Customizer.
		do_action( 'astra_customizer_font_list', $this->value() );

		echo '<optgroup label="Google">';

		foreach ( Astra_Font_Families::get_google_fonts() as $name => $variants ) {
			echo '<option value="' . esc_attr( $name ) . '" ' . selected( $name, $this->value(), false ) . '>' . esc_attr( $name ) . '</option>';
		}

		echo '</select>';
		echo '</label>';
	}

	/**
	 * Renders a font weight control.
	 *
	 * @since 1.0.0
	 * @param  string $default Inherit/Default.
	 * @access protected
	 * @return void
	 */
	protected function render_font_weight( $default ) {
		echo '<label>';
		$this->render_content_title();
		echo '<select ';
		$this->link();
		$this->render_connect_attribute();
		echo '>';
		echo '<option value="inherit" ' . selected( 'inherit', $this->value(), false ) . '>' . esc_attr( $default ) . '</option>';
		echo '<option value="' . esc_attr( $this->value() ) . '" selected="selected">' . esc_attr( $this->value() ) . '</option>';
		echo '</select>';
		echo '</label>';
	}
}
