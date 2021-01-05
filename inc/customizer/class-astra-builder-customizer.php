<?php
/**
 * Astra Builder Controller.
 *
 * @package astra-builder
 * @since 3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Astra_Builder_Customizer.
 *
 * Customizer Configuration for Header Footer Builder.
 *
 * @since 3.0.0
 */
final class Astra_Builder_Customizer {

	/**
	 * Constructor
	 *
	 * @since 3.0.0
	 */
	public function __construct() {

		add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_preview_scripts' ) );
		add_action( 'customize_register', array( $this, 'woo_header_configs' ), 2 );

		if ( ! Astra_Builder_Helper::$is_header_footer_builder_active ) {
			return;
		}

		$this->load_base_components();

		add_action( 'customize_register', array( $this, 'builder_configs' ), 2 );
		add_action( 'customize_register', array( $this, 'header_configs' ), 2 );
		add_action( 'customize_register', array( $this, 'footer_configs' ), 2 );
		add_action( 'customize_register', array( $this, 'update_default_wp_configs' ) );
		add_action( 'init', array( $this, 'deregister_menu_locations_widgets' ), 999 );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'builder_customizer_preview_styles' ) );

	}

	/**
	 * Update default WP configs.
	 *
	 * @param object $wp_customize customizer object.
	 */
	public function update_default_wp_configs( $wp_customize ) {

		$wp_customize->get_control( 'custom_logo' )->priority     = 2;
		$wp_customize->get_control( 'blogname' )->priority        = 7;
		$wp_customize->get_control( 'blogdescription' )->priority = 11;

		$wp_customize->get_setting( 'custom_logo' )->transport     = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';

		$wp_customize->get_section( 'title_tagline' )->panel = 'panel-header-builder-group';

		$wp_customize->selective_refresh->add_partial(
			'custom_logo',
			array(
				'selector'            => '.site-branding',
				'container_inclusive' => true,
				'render_callback'     => 'Astra_Builder_Header::site_identity',
			)
		);

		// @codingStandardsIgnoreStart PHPCompatibility.FunctionDeclarations.NewClosure.Found
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => function() {
					bloginfo( 'description' );
				},
			)
		);

		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title',
				'render_callback' => function() {
					bloginfo( 'name' );
				},
			)
		);

		// @codingStandardsIgnoreStart PHPCompatibility.FunctionDeclarations.NewClosure.Found

	}

	/**
	 * Function to remove old Header and Footer Menu location and widgets.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function deregister_menu_locations_widgets() {

		// Remove Header Menus locations.
		unregister_nav_menu( 'above_header_menu' );
		unregister_nav_menu( 'below_header_menu' );

		// Remove Header Widgets.
		unregister_sidebar( 'above-header-widget-1' );
		unregister_sidebar( 'above-header-widget-2' );
		unregister_sidebar( 'below-header-widget-1' );
		unregister_sidebar( 'below-header-widget-2' );

		// Remove Footer Widgets.
		unregister_sidebar( 'advanced-footer-widget-1' );
		unregister_sidebar( 'advanced-footer-widget-2' );
		unregister_sidebar( 'advanced-footer-widget-3' );
		unregister_sidebar( 'advanced-footer-widget-4' );
		unregister_sidebar( 'advanced-footer-widget-5' );
	}

	/**
	 * Function to remove old Header and Footer panels from Plugin.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function remove_old_header_footer() {

		self::$removed_panels[] = 'panel-header-group';

		self::$removed_sections[] = 'section-footer-small';
		self::$removed_sections[] = 'section-footer-group';
		self::$removed_sections[] = 'section-footer-adv';

	}

	/**
	 * Attach customize_controls_print_footer_scripts preview styles conditionally.
	 *
	 * @since 3.0.0
	 */
	public function builder_customizer_preview_styles() {
		/**
		 * Added Astra Pro dependent customizer style.
		 */
		if ( is_customize_preview() ) {
			echo '<style type="text/css">
				.ahfb-builder-mode-header[data-row="above"] .ahfb-row-actions, .ahfb-builder-mode-header[data-row="below"] .ahfb-row-actions, .ahfb-builder-mode-footer[data-row="above"] .ahfb-row-actions, .ahfb-builder-mode-footer[data-row="primary"] .ahfb-row-actions {
					cursor: pointer;
				}
			</style>';
		}
	}

	/**
	 * Add Customizer preview script.
	 *
	 * @since 3.0.0
	 */
	public function enqueue_customizer_preview_scripts() {

		// Enqueue Builder CSS.
		wp_enqueue_style(
			'ahfb-customizer-preview-style',
			ASTRA_THEME_URI . 'inc/assets/css/customizer-preview.css',
			null,
			ASTRA_THEME_VERSION
		);

		// Advanced Dynamic CSS.
		wp_enqueue_script(
			'ahfb-customizer-preview',
			ASTRA_THEME_URI . 'inc/assets/js/customizer-preview.js',
			array( 'customize-preview' ),
			ASTRA_THEME_VERSION,
			true
		);

		// Base Dynamic CSS.
		wp_enqueue_script(
			'ahfb-base-customizer-preview',
			ASTRA_THEME_URI . 'inc/builder/type/base/assets/js/customizer-preview.js',
			array( 'customize-preview' ),
			ASTRA_THEME_VERSION,
			true
		);

		// Localize variables for Astra Breakpoints JS.
		wp_localize_script(
			'ahfb-base-customizer-preview',
			'astraBuilderPreview',
			array(
				'tablet_break_point' => astra_get_tablet_breakpoint(),
				'mobile_break_point' => astra_get_mobile_breakpoint(),
			)
		);

		wp_localize_script(
			'ahfb-customizer-preview',
			'astraBuilderCustomizer',
			array(
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'astra-builder-customizer-nonce' ),
			)
		);
	}

	/**
	 * Register Base Components for Builder.
	 */
	public function load_base_components() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once ASTRA_THEME_DIR . 'inc/customizer/configurations/builder/class-astra-builder-base-configuration.php';

		require_once ASTRA_THEME_DIR . 'inc/builder/type/class-astra-builder-base-dynamic-css.php';

		// Base Config Files.
		require_once ASTRA_THEME_DIR . 'inc/customizer/configurations/builder/base/class-astra-social-icon-component-configs.php';
		require_once ASTRA_THEME_DIR . 'inc/customizer/configurations/builder/base/class-astra-html-component-configs.php';
		require_once ASTRA_THEME_DIR . 'inc/customizer/configurations/builder/base/class-astra-button-component-configs.php';

		// Base Dynamic CSS Files.
		require_once ASTRA_THEME_DIR . 'inc/builder/type/base/dynamic-css/html/class-astra-html-component-dynamic-css.php';
		require_once ASTRA_THEME_DIR . 'inc/builder/type/base/dynamic-css/social/class-astra-social-component-dynamic-css.php';
		require_once ASTRA_THEME_DIR . 'inc/builder/type/base/dynamic-css/button/class-astra-button-component-dynamic-css.php';
		require_once ASTRA_THEME_DIR . 'inc/builder/type/base/dynamic-css/widget/class-astra-widget-component-dynamic-css.php';

		$this->load_header_components();
		$this->load_footer_components();
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register Components for Header Builder.
	 *
	 * @since 3.0.0
	 */
	public function load_header_components() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$header_components_path = ASTRA_THEME_DIR . 'inc/builder/type/header';
		require_once $header_components_path . '/site-identity/class-astra-header-site-identity-component.php';
		require_once $header_components_path . '/off-canvas/class-astra-off-canvas.php';
		require_once $header_components_path . '/primary-header/class-astra-primary-header.php';
		require_once $header_components_path . '/button/class-astra-header-button-component.php';
		require_once $header_components_path . '/menu/class-astra-header-menu-component.php';
		require_once $header_components_path . '/html/class-astra-header-html-component.php';
		require_once $header_components_path . '/search/class-astra-header-search-component.php';
		require_once $header_components_path . '/account/class-astra-header-account-component.php';
		require_once $header_components_path . '/social-icon/class-astra-header-social-icon-component.php';
		require_once $header_components_path . '/widget/class-astra-header-widget-component.php';
		require_once $header_components_path . '/mobile-trigger/class-astra-mobile-trigger.php';
		require_once $header_components_path . '/mobile-menu/class-astra-mobile-menu-component.php';

		require_once $header_components_path . '/above-header/class-astra-above-header.php';
		require_once $header_components_path . '/below-header/class-astra-below-header.php';
		
		if ( class_exists( 'Astra_Woocommerce' ) ) {
			require_once $header_components_path . '/woo-cart/class-astra-header-woo-cart-component.php';
		}

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			require_once $header_components_path . '/edd-cart/class-astra-header-edd-cart-component.php';
		}

		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register Components for Footer Builder.
	 *
	 * @since 3.0.0
	 */
	public function load_footer_components() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$footer_components_path = ASTRA_THEME_DIR . 'inc/builder/type/footer';
		require_once $footer_components_path . '/below-footer/class-astra-below-footer.php';
		require_once $footer_components_path . '/menu/class-astra-footer-menu-component.php';
		require_once $footer_components_path . '/html/class-astra-footer-html-component.php';
		require_once $footer_components_path . '/button/class-astra-footer-button-component.php';
		require_once $footer_components_path . '/copyright/class-astra-footer-copyright-component.php';
		require_once $footer_components_path . '/social-icon/class-astra-footer-social-icons-component.php';
		require_once $footer_components_path . '/above-footer/class-astra-above-footer.php';
		require_once $footer_components_path . '/primary-footer/class-astra-primary-footer.php';
		require_once $footer_components_path . '/widget/class-astra-footer-widget-component.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register controls for Header/Footer Builder.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @since 3.0.0
	 */
	public function builder_configs( $wp_customize ) {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$builder_config_path = ASTRA_THEME_DIR . 'inc/customizer/configurations/builder/';
		// Header Builder.
		require_once $builder_config_path . '/header/class-astra-customizer-header-builder-configs.php';
		// Footer Builder.
		require_once $builder_config_path . '/footer/class-astra-customizer-footer-builder-configs.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register controls for Header Builder.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @since 3.0.0
	 */
	public function header_configs( $wp_customize ) {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$header_config_path = ASTRA_THEME_DIR . 'inc/customizer/configurations/builder/header';
		require_once $header_config_path . '/class-astra-customizer-above-header-configs.php';
		require_once $header_config_path . '/class-astra-customizer-below-header-configs.php';
		require_once $header_config_path . '/class-astra-customizer-header-builder-configs.php';
		require_once $header_config_path . '/class-astra-customizer-header-widget-configs.php';
		require_once $header_config_path . '/class-astra-customizer-mobile-trigger-configs.php';
		require_once $header_config_path . '/class-astra-customizer-off-canvas-configs.php';
		require_once $header_config_path . '/class-astra-customizer-primary-header-configs.php';
		require_once $header_config_path . '/class-astra-customizer-site-identity-configs.php';
		require_once $header_config_path . '/class-astra-header-button-component-configs.php';
		require_once $header_config_path . '/class-astra-header-html-component-configs.php';
		require_once $header_config_path . '/class-astra-header-menu-component-configs.php';
		require_once $header_config_path . '/class-astra-header-search-component-configs.php';
		require_once $header_config_path . '/class-astra-header-account-component-configs.php';
		require_once $header_config_path . '/class-astra-header-social-icon-component-configs.php';

		if ( class_exists( 'Astra_Woocommerce' ) ) {
			require_once $header_config_path . '/class-astra-customizer-woo-cart-configs.php';
		}

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			require_once $header_config_path . '/class-astra-customizer-edd-cart-configs.php';
		}

		require_once $header_config_path . '/class-astra-mobile-menu-component-configs.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register controls for Footer Builder.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @since 3.0.0
	 */
	public function footer_configs( $wp_customize ) {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$footer_config_path = ASTRA_THEME_DIR . 'inc/customizer/configurations/builder/footer';
		require_once $footer_config_path . '/class-astra-customizer-above-footer-configs.php';
		require_once $footer_config_path . '/class-astra-customizer-below-footer-configs.php';
		require_once $footer_config_path . '/class-astra-customizer-copyright-configs.php';
		require_once $footer_config_path . '/class-astra-customizer-footer-builder-configs.php';
		require_once $footer_config_path . '/class-astra-customizer-footer-menu-configs.php';
		require_once $footer_config_path . '/class-astra-customizer-footer-social-icons-configs.php';
		require_once $footer_config_path . '/class-astra-customizer-primary-footer-configs.php';
		require_once $footer_config_path . '/class-astra-footer-html-component-configs.php';
		require_once $footer_config_path . '/class-astra-footer-button-component-configs.php';
		require_once $footer_config_path . '/class-astra-footer-widget-component-configs.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register Woocommerce controls for new and old Header Builder.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @since 3.0.0
	 */
	public function woo_header_configs( $wp_customize ) {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$header_config_path = ASTRA_THEME_DIR . 'inc/customizer/configurations/builder/header';

		if ( class_exists( 'Astra_Woocommerce' ) ) {
			require_once $header_config_path . '/class-astra-customizer-woo-cart-configs.php';
		}

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			require_once $header_config_path . '/class-astra-customizer-edd-cart-configs.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Prepare if class 'Astra_Builder_Customizer' exist.
 *  Kicking this off by creating new object of the class.
 */
new Astra_Builder_Customizer();
