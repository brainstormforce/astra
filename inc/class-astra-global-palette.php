<?php
/**
 * Astra Global color palette
 *
 * @package     Astra
 * @subpackage  Class
 * @author      Astra
 * @link        https://wpastra.com/
 * @since       x.x.x
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Global palette class
 */
class Astra_Global_Palette {

	/**
	 * Constructor
	 *
	 * @since x.x.x
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'support_editor_color_palette' ) );
		add_filter( 'astra_theme_customizer_js_localize', array( $this, 'localize_variables' ) );
		add_filter( 'astra_before_foreground_color_generation', array( $this, 'get_color_by_palette_variable' ) );
		$this->includes();
	}

	/**
	 * Get CSS variable prefix used for styling.
	 *
	 * @since x.x.x
	 * @return string variable prefix
	 */
	public static function get_css_variable_prefix() {
		return '--ast-global-color-';
	}

	/**
	 * Localize variables used in the customizer.
	 *
	 * @since x.x.x
	 * @param object $object localize object.
	 * @return array $object localize object.
	 */
	public function localize_variables( $object ) {

		if ( isset( $object['customizer'] ) ) {
			$object['customizer']['globalPaletteStylePrefix'] = self::get_css_variable_prefix();
			$object['customizer']['isElementorActive']        = astra_is_elemetor_active();
			$object['customizer']['globalPaletteSlugs']       = self::get_palette_slugs();
			$object['customizer']['globalPaletteLabels']      = self::get_palette_labels();
		}
		return $object;
	}

	/**
	 * Default global palette options.
	 *
	 * @since x.x.x
	 * @return array Palette options.
	 */
	public static function get_default_color_palette() {
		return array(
			'currentPalette' => 'palette_1',
			'palettes'       => array(
				'palette_1' => array(
					'#0274be',
					'#3a3a3a',
					'#3a3a3a',
					'#4B4F58',
					'#F6F7F8',
					'#00123A',
					'#243673',
					'#FBFCFF',
					'#BFD1FF',
				),
				'palette_2' => array(
					'#FF6333',
					'#FA430B',
					'#19150F',
					'#413E3A',
					'#F7F3ED',
					'#AE7867',
					'#462903',
					'#FFE1B4',
					'#FFFFFF',
				),
				'palette_3' => array(
					'#FD4973',
					'#F81B4F',
					'#19150F',
					'#483D40',
					'#F7F2F3',
					'#A46E7B',
					'#C8002F',
					'#FFD8E0',
					'#FFFFFF',
				),
			),
		);
	}

	/**
	 * Get labels for palette colors.
	 *
	 * @since x.x.x
	 * @return array Palette labels.
	 */
	public static function get_palette_labels() {
		return array(
			__( 'Theme Color', 'astra' ),
			__( 'Link Hover Color', 'astra' ),
			__( 'Heading Color', 'astra' ),
			__( 'Text Color', 'astra' ),
			__( 'Background Color', 'astra' ),
			__( 'Extra Color 1', 'astra' ),
			__( 'Extra Color 2', 'astra' ),
			__( 'Extra Color 3', 'astra' ),
			__( 'Extra Color 4', 'astra' ),
		);
	}

	/**
	 * Get slugs for palette colors.
	 *
	 * @since x.x.x
	 * @return array Palette slugs.
	 */
	public static function get_palette_slugs() {
		return array(
			'text-color',
			'theme-color',
			'link-hover',
			'link-hover-color',
			'heading-color',
			'extra-color-1',
			'extra-color-2',
			'extra-color-3',
			'extra-color-4',
		);
	}

	/**
	 * Include required files.
	 *
	 * @since x.x.x
	 */
	public function includes() {
		require_once ASTRA_THEME_DIR . 'inc/dynamic-css/global-color-palette.php';// PHPCS:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Generate palette CSS required to display on front end.
	 *
	 * @since x.x.x
	 * @return array palette style array.
	 */
	public static function generate_global_palette_style() {
		$palette_data = astra_get_option( 'global-color-palette' );

		$palette_style = array();

		if ( isset( $palette_data['palette'] ) ) {
			foreach ( $palette_data['palette'] as $key => $color ) {
				$palette_key                   = self::get_css_variable_prefix() . $key;
				$palette_style[ $palette_key ] = $color;
			}
		}

		return $palette_style;
	}

	/**
	 * Modify color palette from Gutenberg.
	 *
	 * @since x.x.x
	 * @return void
	 */
	public function support_editor_color_palette() {
		$global_palette = astra_get_option( 'global-color-palette' );
		$editor_palette = $this->format_global_palette( $global_palette );

		// Editor Color Palette.
		add_theme_support( 'editor-color-palette', $editor_palette );
	}

	/**
	 * Format color palette data required to pass for Gutenberg palette.
	 *
	 * @since x.x.x
	 * @param array $global_palette global palette data.
	 * @return bool
	 */
	public function format_global_palette( $global_palette ) {
		$editor_palette    = array();
		$extra_color_index = 1;
		$color_index       = 0;
		foreach ( $global_palette['palette'] as $key => $color ) {

			if ( isset( $global_palette['labels'][ $color_index ] ) ) {
				$label = $global_palette['labels'][ $color_index ];
			} else {
				$label = __( 'Extra Color', 'astra' ) . $extra_color_index;
				$extra_color_index++;
			}

			$editor_palette[] = array(
				'name'  => $label,
				'slug'  => self::get_css_variable_prefix() . $key,
				'color' => 'var(' . self::get_css_variable_prefix() . $key . ')',
			);
			$color_index++;
		}

		return $editor_palette;
	}

	/**
	 * Pass hex value for global palette to process forground color.
	 *
	 * @since x.x.x
	 * @param string $color hex color / css variable.
	 * @return string
	 */
	public function get_color_by_palette_variable( $color ) {
		// Check if color is CSS variable.
		if ( 0 === strpos( $color, 'var(--' ) ) {

			$global_palette = astra_get_option( 'global-color-palette' );

			foreach ( $global_palette['palette'] as $palette_index => $value ) {

				if ( 'var(' . self::get_css_variable_prefix() . $palette_index . ')' === $color ) {
					return $value;
				}
			}
		}

		return $color;
	}
}

new Astra_Global_Palette();
