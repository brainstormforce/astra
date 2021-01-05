<?php
/**
 * Astra Builder Helper.
 *
 * @package astra-builder
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Astra_Builder_Helper.
 */
final class Astra_Builder_Helper {

	/**
	 * Config context general tab.
	 *
	 * @var string[][]
	 */
	public static $general_tab = array(
		array(
			'setting' => 'ast_selected_tab',
			'value'   => 'general',
		),
	);

	/**
	 * Config context general tab config.
	 *
	 * @var string[][]
	 */
	public static $general_tab_config = array(
		'setting' => 'ast_selected_tab',
		'value'   => 'general',
	);

	/**
	 * Config context design tab.
	 *
	 * @var string[][]
	 */
	public static $design_tab = array(
		array(
			'setting' => 'ast_selected_tab',
			'value'   => 'design',
		),
	);

	/**
	 * Config context design tab.
	 *
	 * @var string[][]
	 */
	public static $design_tab_config = array(
		'setting' => 'ast_selected_tab',
		'value'   => 'design',
	);

	/**
	 * Config Tablet device context.
	 *
	 * @var string[][]
	 */
	public static $tablet_device = array(
		array(
			'setting'  => 'ast_selected_device',
			'operator' => 'in',
			'value'    => array( 'tablet' ),
		),
	);

	/**
	 * Config Mobile device context.
	 *
	 * @var string[][]
	 */
	public static $mobile_device = array(
		array(
			'setting'  => 'ast_selected_device',
			'operator' => 'in',
			'value'    => array( 'mobile' ),
		),
	);

	/**
	 * Config Mobile device context.
	 *
	 * @var string[][]
	 */
	public static $responsive_devices = array(
		array(
			'setting'  => 'ast_selected_device',
			'operator' => 'in',
			'value'    => array( 'tablet', 'mobile' ),
		),
	);

	/**
	 * Config Mobile device context.
	 *
	 * @var string[][]
	 */
	public static $responsive_general_tab = array(
		array(
			'setting' => 'ast_selected_tab',
			'value'   => 'general',
		),
		array(
			'setting'  => 'ast_selected_device',
			'operator' => 'in',
			'value'    => array( 'tablet', 'mobile' ),
		),
	);

	/**
	 * Config Desktop device context.
	 *
	 * @var string[][]
	 */
	public static $desktop_general_tab = array(
		array(
			'setting' => 'ast_selected_tab',
			'value'   => 'general',
		),
		array(
			'setting'  => 'ast_selected_device',
			'operator' => '==',
			'value'    => 'desktop',
		),
	);

	/**
	 * Config Tablet device context.
	 *
	 * @var string[][]
	 */
	public static $tablet_general_tab = array(
		array(
			'setting' => 'ast_selected_tab',
			'value'   => 'general',
		),
		array(
			'setting'  => 'ast_selected_device',
			'operator' => '==',
			'value'    => 'tablet',
		),
	);

	/**
	 * Config Mobile device context.
	 *
	 * @var string[][]
	 */
	public static $mobile_general_tab = array(
		array(
			'setting' => 'ast_selected_tab',
			'value'   => 'general',
		),
		array(
			'setting'  => 'ast_selected_device',
			'operator' => '==',
			'value'    => 'mobile',
		),
	);

	/**
	 *  No. Of. Footer Widgets.
	 *
	 * @var int
	 */
	public static $num_of_footer_widgets;

	/**
	 *  No. Of. Footer HTML.
	 *
	 * @var int
	 */
	public static $num_of_footer_html;

	/**
	 *  No. Of. Header Widgets.
	 *
	 * @var int
	 */
	public static $num_of_header_widgets;

	/**
	 *  No. Of. Header Menu.
	 *
	 * @var int
	 */
	public static $num_of_header_menu;

	/**
	 *  No. Of. Header Buttons.
	 *
	 * @var int
	 */
	public static $num_of_header_button;

	/**
	 *  No. Of. Footer Buttons.
	 *
	 * @var int
	 */
	public static $num_of_footer_button;

	/**
	 *  No. Of. Header HTML.
	 *
	 * @var int
	 */
	public static $num_of_header_html;

	/**
	 *  No. Of. Footer Columns.
	 *
	 * @var int
	 */
	public static $num_of_footer_columns;

	/**
	 *  No. Of. Header Social Icons.
	 *
	 * @var int
	 */
	public static $num_of_header_social_icons;

	/**
	 *  No. Of. Footer Social Icons.
	 *
	 * @var int
	 */
	public static $num_of_footer_social_icons;

	/**
	 *  Check if migrated to new HFB.
	 *
	 * @var int
	 */
	public static $is_header_footer_builder_active;

	/**
	 * Footer Row layout
	 *
	 * @var array
	 */
	public static $footer_row_layouts;

	/**
	 * Header Desktop Items
	 *
	 * @var array
	 */
	public static $header_desktop_items = null;

	/**
	 * Footer Desktop Items
	 *
	 * @var array
	 */
	public static $footer_desktop_items = null;

	/**
	 * Header Mobile Items
	 *
	 * @var array
	 */
	public static $header_mobile_items = null;

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	public static $loaded_grid = null;

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance = null;

	/**
	 *  Initiator
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {

		$component_count_by_key = self::get_component_count_by_key();

		self::$num_of_header_button = defined( 'ASTRA_EXT_VER' ) ? $component_count_by_key['header-button'] : 1;
		self::$num_of_footer_button = defined( 'ASTRA_EXT_VER' ) ? $component_count_by_key['footer-button'] : 0;

		self::$num_of_header_html = defined( 'ASTRA_EXT_VER' ) ? $component_count_by_key['header-html'] : 2;
		self::$num_of_footer_html = defined( 'ASTRA_EXT_VER' ) ? $component_count_by_key['footer-html'] : 2;

		self::$num_of_header_menu = defined( 'ASTRA_EXT_VER' ) ? $component_count_by_key['header-menu'] : 2;

		self::$num_of_header_widgets = defined( 'ASTRA_EXT_VER' ) ? $component_count_by_key['header-widget'] : 2;
		self::$num_of_footer_widgets = defined( 'ASTRA_EXT_VER' ) ? $component_count_by_key['footer-widget'] : 4;

		self::$num_of_header_social_icons = defined( 'ASTRA_EXT_VER' ) ? $component_count_by_key['header-social-icons'] : 1;
		self::$num_of_footer_social_icons = defined( 'ASTRA_EXT_VER' ) ? $component_count_by_key['footer-social-icons'] : 1;

		self::$num_of_footer_columns = defined( 'ASTRA_EXT_VER' ) ? apply_filters( 'astra_footer_column_count', 6 ) : 6;

		self::$footer_row_layouts = apply_filters(
			'astra_footer_row_layout',
			array(
				'desktop'    => array(
					'6' => array(
						'6-equal' => array(
							'icon' => 'sixcol',
						),
					),
					'5' => array(
						'5-equal' => array(
							'icon' => 'fivecol',
						),
					),
					'4' => array(
						'4-equal'  => array(
							'icon' => 'fourcol',
						),
						'4-lheavy' => array(
							'icon' => 'lfourforty',
						),
						'4-rheavy' => array(
							'icon' => 'rfourforty',
						),
					),
					'3' => array(
						'3-equal'  => array(
							'icon' => 'threecol',
						),
						'3-lheavy' => array(
							'icon' => 'lefthalf',
						),
						'3-rheavy' => array(
							'icon' => 'righthalf',
						),
						'3-cheavy' => array(
							'icon' => 'centerhalf',
						),
						'3-cwide'  => array(
							'icon' => 'widecenter',
						),
					),
					'2' => array(
						'2-equal'  => array(
							'icon' => 'twocol',
						),
						'2-lheavy' => array(
							'icon' => 'twoleftgolden',
						),
						'2-rheavy' => array(
							'icon' => 'tworightgolden',
						),
					),
					'1' => array(
						'full' => array(
							'icon' => 'row',
						),
					),
				),
				'tablet'     => array(
					'6' => array(
						'6-equal' => array(
							'tooltip' => __( 'Equal Width Columns', 'astra' ),
							'icon'    => 'sixcol',
						),
						'full'    => array(
							'tooltip' => __( 'Collapse to Rows', 'astra' ),
							'icon'    => 'collapserowsix',
						),
					),
					'5' => array(
						'5-equal' => array(
							'tooltip' => __( 'Equal Width Columns', 'astra' ),
							'icon'    => 'fivecol',
						),
						'full'    => array(
							'tooltip' => __( 'Collapse to Rows', 'astra' ),
							'icon'    => 'collapserowfive',
						),
					),
					'4' => array(
						'4-equal' => array(
							'tooltip' => __( 'Equal Width Columns', 'astra' ),
							'icon'    => 'fourcol',
						),
						'2-equal' => array(
							'tooltip' => __( 'Two Column Grid', 'astra' ),
							'icon'    => 'grid',
						),
						'full'    => array(
							'tooltip' => __( 'Collapse to Rows', 'astra' ),
							'icon'    => 'collapserowfour',
						),
					),
					'3' => array(
						'3-equal'    => array(
							'tooltip' => __( 'Equal Width Columns', 'astra' ),
							'icon'    => 'threecol',
						),
						'3-lheavy'   => array(
							'tooltip' => __( 'Left Heavy 50/25/25', 'astra' ),
							'icon'    => 'lefthalf',
						),
						'3-rheavy'   => array(
							'tooltip' => __( 'Right Heavy 25/25/50', 'astra' ),
							'icon'    => 'righthalf',
						),
						'3-cheavy'   => array(
							'tooltip' => __( 'Center Heavy 25/50/25', 'astra' ),
							'icon'    => 'centerhalf',
						),
						'3-cwide'    => array(
							'tooltip' => __( 'Wide Center 20/60/20', 'astra' ),
							'icon'    => 'widecenter',
						),
						'3-firstrow' => array(
							'tooltip' => __( 'First Row, Next Columns 100 - 50/50', 'astra' ),
							'icon'    => 'firstrow',
						),
						'3-lastrow'  => array(
							'tooltip' => __( 'Last Row, Previous Columns 50/50 - 100', 'astra' ),
							'icon'    => 'lastrow',
						),
						'full'       => array(
							'tooltip' => __( 'Collapse to Rows', 'astra' ),
							'icon'    => 'collapserowthree',
						),
					),
					'2' => array(
						'2-equal'  => array(
							'tooltip' => __( 'Equal Width Columns', 'astra' ),
							'icon'    => 'twocol',
						),
						'2-lheavy' => array(
							'tooltip' => __( 'Left Heavy 66/33', 'astra' ),
							'icon'    => 'twoleftgolden',
						),
						'2-rheavy' => array(
							'tooltip' => __( 'Right Heavy 33/66', 'astra' ),
							'icon'    => 'tworightgolden',
						),
						'full'     => array(
							'tooltip' => __( 'Collapse to Rows', 'astra' ),
							'icon'    => 'collapserow',
						),
					),
					'1' => array(
						'full' => array(
							'tooltip' => __( 'Single Row', 'astra' ),
							'icon'    => 'row',
						),
					),
				),
				'mobile'     => array(
					'6' => array(
						'6-equal' => array(
							'tooltip' => __( 'Equal Width Columns', 'astra' ),
							'icon'    => 'sixcol',
						),
						'full'    => array(
							'tooltip' => __( 'Collapse to Rows', 'astra' ),
							'icon'    => 'collapserowsix',
						),
					),
					'5' => array(
						'5-equal' => array(
							'tooltip' => __( 'Equal Width Columns', 'astra' ),
							'icon'    => 'fivecol',
						),
						'full'    => array(
							'tooltip' => __( 'Collapse to Rows', 'astra' ),
							'icon'    => 'collapserowfive',
						),
					),
					'4' => array(
						'4-equal' => array(
							'icon' => 'fourcol',
						),
						'2-equal' => array(
							'icon' => 'grid',
						),
						'full'    => array(
							'icon' => 'collapserowfour',
						),
					),
					'3' => array(
						'3-equal'    => array(
							'icon' => 'threecol',
						),
						'3-lheavy'   => array(
							'icon' => 'lefthalf',
						),
						'3-rheavy'   => array(
							'icon' => 'righthalf',
						),
						'3-cheavy'   => array(
							'icon' => 'centerhalf',
						),
						'3-cwide'    => array(
							'icon' => 'widecenter',
						),
						'3-firstrow' => array(
							'icon' => 'firstrow',
						),
						'3-lastrow'  => array(
							'icon' => 'lastrow',
						),
						'full'       => array(
							'icon' => 'collapserowthree',
						),
					),
					'2' => array(
						'2-equal'  => array(
							'icon' => 'twocol',
						),
						'2-lheavy' => array(
							'icon' => 'twoleftgolden',
						),
						'2-rheavy' => array(
							'icon' => 'tworightgolden',
						),
						'full'     => array(
							'icon' => 'collapserow',
						),
					),
					'1' => array(
						'full' => array(
							'icon' => 'row',
						),
					),
				),
				'responsive' => true,
			)
		);

		self::$header_desktop_items = apply_filters(
			'astra_header_desktop_items',
			array(
				'logo'    => array(
					'name'    => __( 'Logo', 'astra' ),
					'icon'    => 'admin-appearance',
					'section' => 'title_tagline',
				),
				'search'  => array(
					'name'    => __( 'Search', 'astra' ),
					'icon'    => 'search',
					'section' => 'section-header-search',
				),
				'account' => array(
					'name'    => __( 'Account', 'astra' ),
					'icon'    => 'admin-users',
					'section' => 'section-header-account',
				),
			)
		);

		self::$footer_desktop_items = apply_filters(
			'astra_footer_desktop_items',
			array(
				'copyright' => array(
					'name'    => 'Copyright',
					'icon'    => 'nametag',
					'section' => 'section-footer-copyright',
				),
				'menu'      => array(
					'name'    => 'Footer Menu',
					'icon'    => 'menu',
					'section' => 'section-footer-menu',
				),
			)
		);
		
		if ( class_exists( 'Astra_Woocommerce' ) ) {

			$woo_cart_name = class_exists( 'Easy_Digital_Downloads' ) ? __( 'Woo Cart', 'astra' ) : __( 'Cart', 'astra' );

			self::$header_desktop_items['woo-cart'] = array(
				'name'    => $woo_cart_name,
				'icon'    => 'cart',
				'section' => 'section-header-woo-cart',
			);
		}
		if ( class_exists( 'Easy_Digital_Downloads' ) ) {

			$edd_cart_name = class_exists( 'Astra_Woocommerce' ) ? __( 'EDD Cart', 'astra' ) : __( 'Cart', 'astra' );

			self::$header_desktop_items['edd-cart'] = array(
				'name'    => $edd_cart_name,
				'icon'    => 'cart',
				'section' => 'section-header-edd-cart',
			);
		}

		self::$header_mobile_items = apply_filters(
			'astra_header_mobile_items',
			array(
				'logo'           => array(
					'name'    => __( 'Logo', 'astra' ),
					'icon'    => 'admin-appearance',
					'section' => 'title_tagline',
				),
				'search'         => array(
					'name'    => __( 'Search', 'astra' ),
					'icon'    => 'search',
					'section' => 'section-header-search',
				),
				'mobile-trigger' => array(
					'name'    => __( 'Toggle Button', 'astra' ),
					'icon'    => 'menu-alt',
					'section' => 'section-header-mobile-trigger',
				),
				'mobile-menu'    => array(
					'name'    => __( 'Mobile Menu', 'astra' ),
					'icon'    => 'menu-alt',
					'section' => 'section-header-mobile-menu',
				),
				'account'        => array(
					'name'    => __( 'Account', 'astra' ),
					'icon'    => 'admin-users',
					'section' => 'section-header-account',
				),
			)
		);

		if ( class_exists( 'Astra_Woocommerce' ) ) {
			self::$header_mobile_items['woo-cart'] = array(
				'name'    => $woo_cart_name,
				'icon'    => 'cart',
				'section' => 'section-header-woo-cart',
			);
		}
		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			self::$header_mobile_items['edd-cart'] = array(
				'name'    => $edd_cart_name,
				'icon'    => 'cart',
				'section' => 'section-header-edd-cart',
			);
		}

		self::$is_header_footer_builder_active = self::is_header_footer_builder_active();

		add_filter( 'astra_addon_list', array( $this, 'deprecate_old_header_and_footer' ) );
	}

	/**
	 * Get count of all components.
	 *
	 * @since 3.0.0
	 *
	 * @return int Number of all components.
	 */
	public static function get_component_count_by_key() {

		$component_keys_count = array(
			'header-button'       => 2,
			'footer-button'       => 2,
			'header-html'         => 2,
			'footer-html'         => 2,
			'header-menu'         => 2,
			'header-widget'       => 4,
			'footer-widget'       => 4,
			'header-social-icons' => 1,
			'footer-social-icons' => 1,
		);

		$component_keys_count = array_merge(
			$component_keys_count,
			apply_filters(
				'astra_builder_elements_count',
				$component_keys_count
			)
		);

		// Buttons.
		$component_keys_count['header-button'] = ( 10 >= $component_keys_count['header-button'] ) ? $component_keys_count['header-button'] : 10;
		$component_keys_count['footer-button'] = ( 10 >= $component_keys_count['footer-button'] ) ? $component_keys_count['footer-button'] : 10;

		// HTML.
		$component_keys_count['header-html'] = ( 10 >= $component_keys_count['header-html'] ) ? $component_keys_count['header-html'] : 10;
		$component_keys_count['footer-html'] = ( 10 >= $component_keys_count['footer-html'] ) ? $component_keys_count['footer-html'] : 10;

		// Header Menu.
		$component_keys_count['header-menu'] = ( 5 >= $component_keys_count['header-menu'] ) ? $component_keys_count['header-menu'] : 5;

		// Widgets.
		$component_keys_count['header-widget'] = ( 10 >= $component_keys_count['header-widget'] ) ? $component_keys_count['header-widget'] : 10;
		$component_keys_count['footer-widget'] = ( 10 >= $component_keys_count['footer-widget'] ) ? $component_keys_count['footer-widget'] : 10;

		// Social Icons.
		$component_keys_count['header-social-icons'] = ( 5 >= $component_keys_count['header-social-icons'] ) ? $component_keys_count['header-social-icons'] : 5;
		$component_keys_count['footer-social-icons'] = ( 5 >= $component_keys_count['footer-social-icons'] ) ? $component_keys_count['footer-social-icons'] : 5;

		return $component_keys_count;
	}

	/**
	 * Deprecate Header Sections, Mobile Headers, Footer Widgets for new users and migrated users.
	 *
	 * @since 3.0.0
	 * @param array $args The arguments as per the filter.
	 * @return array $args Updated arguments as per the filter.
	 */
	public function deprecate_old_header_and_footer( $args ) {

		if ( self::$is_header_footer_builder_active ) {
			unset( $args['mobile-header'] );
			unset( $args['header-sections'] );
			unset( $args['advanced-footer'] );
		}
		return $args;
	}

	/**
	 * For existing users, do not load the wide/full width image CSS by default.
	 *
	 * @since 3.0.0
	 * @return boolean false if it is an existing user , true if not.
	 */
	public static function is_header_footer_builder_active() {

		$astra_settings                             = get_option( ASTRA_THEME_SETTINGS );
		$astra_settings['is-header-footer-builder'] = isset( $astra_settings['is-header-footer-builder'] ) ? $astra_settings['is-header-footer-builder'] : true;
		return apply_filters( 'astra_is_header_footer_builder_active', $astra_settings['is-header-footer-builder'] );
	}

	/**
	 *  Check if Migrated to new Astra Builder.
	 */
	public static function is_new_user() {
		return astra_get_option( 'header-footer-builder-notice', true );
	}

	/**
	 * Adds a check to see if the side columns should run.
	 *
	 * @param string $row the name of the row.
	 */
	public static function has_mobile_side_columns( $row = 'primary' ) {

		$mobile_sides = false;
		$elements     = astra_get_option( 'header-mobile-items' );
		if ( isset( $elements ) && isset( $elements[ $row ] ) ) {
			if ( ( isset( $elements[ $row ][ $row . '_left' ] ) && is_array( $elements[ $row ][ $row . '_left' ] ) &&
					! empty( $elements[ $row ][ $row . '_left' ] ) ) || ( isset( $elements[ $row ][ $row . '_left_center' ] ) &&
					is_array( $elements[ $row ][ $row . '_left_center' ] ) &&
					! empty( $elements[ $row ][ $row . '_left_center' ] ) ) || ( isset( $elements[ $row ][ $row . '_right_center' ] ) &&
					is_array( $elements[ $row ][ $row . '_right_center' ] ) && ! empty( $elements[ $row ][ $row . '_right_center' ] ) ) ||
				( isset( $elements[ $row ][ $row . '_right' ] ) && is_array( $elements[ $row ][ $row . '_right' ] ) &&
					! empty( $elements[ $row ][ $row . '_right' ] ) ) ) {
				$mobile_sides = true;
			}
		}

		return $mobile_sides;
	}


	/**
	 * Adds a check to see if the center column should run.
	 *
	 * @param string $row the name of the row.
	 */
	public static function has_mobile_center_column( $row = 'primary' ) {

		$mobile_center = false;
		$elements      = astra_get_option( 'header-mobile-items' );
		if ( isset( $elements ) && isset( $elements[ $row ] ) && isset( $elements[ $row ][ $row . '_center' ] ) &&
			is_array( $elements[ $row ][ $row . '_center' ] ) && ! empty( $elements[ $row ][ $row . '_center' ] ) ) {
			$mobile_center = true;
		}

		return $mobile_center;
	}

	/**
	 * Adds support to render header columns.
	 *
	 * @param string $row the name of the row.
	 * @param string $column the name of the column.
	 * @param string $header the name of the header.
	 * @param string $builder the name of the builder.
	 */
	public static function render_builder_markup( $row = 'primary', $column = 'left', $header = 'desktop', $builder = 'header' ) {
		$elements = astra_get_option( $builder . '-' . $header . '-items' );
		if ( isset( $elements ) && isset( $elements[ $row ] ) && isset( $elements[ $row ][ $row . '_' . $column ] ) && is_array( $elements[ $row ][ $row . '_' . $column ] ) && ! empty( $elements[ $row ][ $row . '_' . $column ] ) ) {
			foreach ( $elements[ $row ][ $row . '_' . $column ] as $key => $item ) {


				if ( astra_wp_version_compare( '5.4.99', '>=' ) ) {

					get_template_part(
						'template-parts/' . $builder . '/builder/components',
						'',
						array(
							'type'   => $item,
							'device' => $header,
						)
					);
				} else {

					set_query_var( 'type', $item );
					get_template_part( 'template-parts/' . $builder . '/builder/components' );
				}
			}
		}
	}
	/**
	 * Adds support to render Mobile Popup Markup.
	 */
	public static function render_mobile_popup_markup() {

		$off_canvas_slide   = astra_get_option( 'off-canvas-slide' );
		$mobile_header_type = astra_get_option( 'mobile-header-type' );
		$content_alignment  = astra_get_option( 'header-offcanvas-content-alignment' );

		$side_class = 'content-align-' . $content_alignment . ' ';

		if ( $mobile_header_type ) {

			if ( 'off-canvas' === $mobile_header_type ) {

				if ( $off_canvas_slide ) {

					if ( 'left' === $off_canvas_slide ) {

						$side_class .= 'ast-mobile-popup-left';
					} else {

						$side_class .= 'ast-mobile-popup-right';
					}
				}
			} else {
				$side_class .= 'ast-mobile-popup-full-width';
			}
		}
		?>
		<div id="ast-mobile-popup-wrapper">
			<div id="ast-mobile-popup" class="ast-mobile-popup-drawer <?php echo esc_attr( $side_class ); ?>">
			<div class="ast-mobile-popup-overlay"></div>
			<div class="ast-mobile-popup-inner">
					<div class="ast-mobile-popup-header">
						<button id="menu-toggle-close" class="menu-toggle-close" aria-label="Close menu">
							<span class="ast-svg-iconset">
								<?php echo Astra_Builder_UI_Controller::fetch_svg_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
						</button>
					</div>
					<div class="ast-mobile-popup-content">
						<?php
							/**
							 * Astra Off-Canvas
							 */
							do_action( 'astra_render_mobile_popup', 'popup', 'content' );
						?>
					</div>
				</div>
		</div>
		</div>

		<?php
	}

	/**
	 * Check if Center column element exists.
	 *
	 * @param string $row the name of the row.
	 * @param string $builder_type the type of the builder.
	 * @param string $device Device.
	 */
	public static function has_center_column( $row = 'main', $builder_type = 'header', $device = 'desktop' ) {

		$center   = false;
		$elements = astra_get_option( $builder_type . '-' . $device . '-items' );
		if ( isset( $elements ) && isset( $elements[ $row ] ) &&
			isset( $elements[ $row ][ $row . '_center' ] ) && is_array( $elements[ $row ][ $row . '_center' ] ) &&
			! empty( $elements[ $row ][ $row . '_center' ] ) ) {
			$center = true;
		}

		return $center;
	}

	/**
	 * Check if Side column element exists.
	 *
	 * @param string $row row.
	 * @param string $builder_type the type of the builder.
	 * @param string $device Device.
	 * @return bool
	 */
	public static function has_side_columns( $row = 'primary', $builder_type = 'header', $device = 'desktop' ) {

		$sides    = false;
		$elements = astra_get_option( $builder_type . '-' . $device . '-items' );
		if ( isset( $elements ) && isset( $elements[ $row ] ) ) {
			if ( (
				isset( $elements[ $row ][ $row . '_left' ] ) &&
				is_array( $elements[ $row ][ $row . '_left' ] ) && ! empty( $elements[ $row ][ $row . '_left' ] ) ) ||
				( isset( $elements[ $row ][ $row . '_left_center' ] ) &&
					is_array( $elements[ $row ][ $row . '_left_center' ] ) && ! empty( $elements[ $row ][ $row . '_left_center' ] ) ) ||
				( isset( $elements[ $row ][ $row . '_right_center' ] ) &&
					is_array( $elements[ $row ][ $row . '_right_center' ] ) && ! empty( $elements[ $row ][ $row . '_right_center' ] ) ) ||
				( isset( $elements[ $row ][ $row . '_right' ] ) &&
					is_array( $elements[ $row ][ $row . '_right' ] ) && ! empty( $elements[ $row ][ $row . '_right' ] ) ) ) {
				$sides = true;
			}
		}
		return $sides;
	}

	/**
	 * Check if Footer Zone is empty.
	 *
	 * @param string $row row.
	 * @return bool
	 */
	public static function is_footer_row_empty( $row = 'primary' ) {
		$sides    = false;
		$elements = astra_get_option( 'footer-desktop-items' );

		if ( isset( $elements ) && isset( $elements[ $row ] ) ) {
			for ( $i = 1; $i <= 5; $i++ ) {
				if (
					isset( $elements[ $row ][ $row . '_' . $i ] ) &&
					is_array( $elements[ $row ][ $row . '_' . $i ] ) &&
					! empty( $elements[ $row ][ $row . '_' . $i ] )
				) {
					$sides = true;
					break;
				}
			}
		}
		return $sides;
	}

	/**
	 * Check if row is empty.
	 *
	 * @param string $row row.
	 * @param string $builder_type the type of the builder.
	 * @param string $device Device.
	 * @return bool
	 */
	public static function is_row_empty( $row = 'primary', $builder_type = 'header', $device = 'desktop' ) {
		if ( false === self::has_center_column( $row, $builder_type, $device ) && false === self::has_side_columns( $row, $builder_type, $device ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Check if component placed on the builder.
	 *
	 * @param integer $component_id component id.
	 * @param string  $builder_type builder type.
	 * @return bool
	 */
	public static function is_component_loaded( $component_id, $builder_type = 'header' ) {

		$loaded_components = array();

		if ( is_null( self::$loaded_grid ) ) {

			$grids[] = astra_get_option( 'header-desktop-items', array() );
			$grids[] = astra_get_option( 'header-mobile-items', array() );
			$grids[] = astra_get_option( 'footer-desktop-items', array() );

			if ( ! empty( $grids ) ) {

				foreach ( $grids as $row_gird => $row_grids ) {

					if ( ! empty( $row_grids ) ) {

						foreach ( $row_grids as $row => $grid ) {

							if ( ! in_array( $row, array( 'below', 'above', 'primary', 'popup' ) ) ) {
								continue;
							}

							if ( ! is_array( $grid ) ) {
								continue;
							}
							
							$result = array_values( $grid );
							if ( is_array( $result ) ) {
								$loaded_component    = call_user_func_array( 'array_merge', $result );
								$loaded_components[] = is_array( $loaded_component ) ? $loaded_component : array();
							}
						}
					}
				}
			}

			if ( ! empty( $loaded_components ) ) {
				$loaded_components = array_values( $loaded_components );
				$loaded_components = call_user_func_array( 'array_merge', $loaded_components );
			}

			self::$loaded_grid = $loaded_components;
		}

		$loaded_components = self::$loaded_grid;

		return in_array( $component_id, $loaded_components, true ) || is_customize_preview();
	}
}

/**
 *  Prepare if class 'Astra_Builder_Helper' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
Astra_Builder_Helper::get_instance();
