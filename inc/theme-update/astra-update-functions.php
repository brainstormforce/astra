<?php
/**
 * Astra Updates
 *
 * Functions for updating data, used by the background updater.
 *
 * @package Astra
 * @version 2.1.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * Open Submenu just below menu for existing users.
 *
 * @since 2.1.3
 * @return void
 */
function astra_submenu_below_header() {
	$theme_options = get_option( 'astra-settings' );

	// Set flag to use flex align center css to open submenu just below menu.
	if ( ! isset( $theme_options['submenu-open-below-header'] ) ) {
		$theme_options['submenu-open-below-header'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Do not apply new default colors to the Elementor & Gutenberg Buttons for existing users.
 *
 * @since 2.2.0
 *
 * @return void
 */
function astra_page_builder_button_color_compatibility() {
	$theme_options = get_option( 'astra-settings', array() );

	// Set flag to not load button specific CSS.
	if ( ! isset( $theme_options['pb-button-color-compatibility'] ) ) {
		$theme_options['pb-button-color-compatibility'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Migrate option data from button vertical & horizontal padding to the new responsive padding param.
 *
 * @since 2.2.0
 *
 * @return void
 */
function astra_vertical_horizontal_padding_migration() {
	$theme_options = get_option( 'astra-settings', array() );

	$btn_vertical_padding   = isset( $theme_options['button-v-padding'] ) ? $theme_options['button-v-padding'] : 10;
	$btn_horizontal_padding = isset( $theme_options['button-h-padding'] ) ? $theme_options['button-h-padding'] : 40;

	if ( false === astra_get_db_option( 'theme-button-padding', false ) ) {

		// Migrate button vertical padding to the new padding param for button.
		$theme_options['theme-button-padding'] = array(
			'desktop'      => array(
				'top'    => $btn_vertical_padding,
				'right'  => $btn_horizontal_padding,
				'bottom' => $btn_vertical_padding,
				'left'   => $btn_horizontal_padding,
			),
			'tablet'       => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'mobile'       => array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
			),
			'desktop-unit' => 'px',
			'tablet-unit'  => 'px',
			'mobile-unit'  => 'px',
		);

		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Migrate option data from button url to the new link param.
 *
 * @since 2.3.0
 *
 * @return void
 */
function astra_header_button_new_options() {

	$theme_options = get_option( 'astra-settings', array() );

	$btn_url = isset( $theme_options['header-main-rt-section-button-link'] ) ? $theme_options['header-main-rt-section-button-link'] : 'https://www.wpastra.com';
	$theme_options['header-main-rt-section-button-link-option'] = array(
		'url'      => $btn_url,
		'new_tab'  => false,
		'link_rel' => '',
	);

	update_option( 'astra-settings', $theme_options );
}

/**
 * For existing users, do not provide Elementor Default Color Typo settings compatibility by default.
 *
 * @since 2.3.3
 *
 * @return void
 */
function astra_elementor_default_color_typo_comp() {

	$theme_options = get_option( 'astra-settings', array() );

	// Set flag to not load button specific CSS.
	if ( ! isset( $theme_options['ele-default-color-typo-setting-comp'] ) ) {
		$theme_options['ele-default-color-typo-setting-comp'] = false;
		update_option( 'astra-settings', $theme_options );
	}

}

/**
 * For existing users, change the separator from html entity to css entity.
 *
 * @since 2.3.4
 *
 * @return void
 */
function astra_breadcrumb_separator_fix() {

	$theme_options = get_option( 'astra-settings', array() );

	// Check if the saved database value for Breadcrumb Separator is "&#187;", then change it to '\00bb'.
	if ( isset( $theme_options['breadcrumb-separator'] ) && '&#187;' === $theme_options['breadcrumb-separator'] ) {
		$theme_options['breadcrumb-separator'] = '\00bb';
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Check if we need to change the default value for tablet breakpoint.
 *
 * @since 2.4.0
 * @return void
 */
function astra_update_theme_tablet_breakpoint() {

	$theme_options = get_option( 'astra-settings' );

	if ( ! isset( $theme_options['can-update-theme-tablet-breakpoint'] ) ) {
		// Set a flag to check if we need to change the theme tablet breakpoint value.
		$theme_options['can-update-theme-tablet-breakpoint'] = false;
	}

	update_option( 'astra-settings', $theme_options );
}

/**
 * Migrate option data from site layout background option to its desktop counterpart.
 *
 * @since 2.4.0
 *
 * @return void
 */
function astra_responsive_base_background_option() {

	$theme_options = get_option( 'astra-settings', array() );

	if ( false === get_option( 'site-layout-outside-bg-obj-responsive', false ) && isset( $theme_options['site-layout-outside-bg-obj'] ) ) {

		$theme_options['site-layout-outside-bg-obj-responsive']['desktop'] = $theme_options['site-layout-outside-bg-obj'];
		$theme_options['site-layout-outside-bg-obj-responsive']['tablet']  = array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		);
		$theme_options['site-layout-outside-bg-obj-responsive']['mobile']  = array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		);
	}

	update_option( 'astra-settings', $theme_options );
}

/**
 * Do not apply new wide/full image CSS for existing users.
 *
 * @since 2.4.4
 *
 * @return void
 */
function astra_gtn_full_wide_image_group_css() {

	$theme_options = get_option( 'astra-settings', array() );

	// Set flag to not load button specific CSS.
	if ( ! isset( $theme_options['gtn-full-wide-image-grp-css'] ) ) {
		$theme_options['gtn-full-wide-image-grp-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Do not apply new wide/full Group and Cover block CSS for existing users.
 *
 * @since 2.5.0
 *
 * @return void
 */
function astra_gtn_full_wide_group_cover_css() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['gtn-full-wide-grp-cover-css'] ) ) {
		$theme_options['gtn-full-wide-grp-cover-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}


/**
 * Do not apply the global border width and border color setting for the existng users.
 *
 * @since 2.5.0
 *
 * @return void
 */
function astra_global_button_woo_css() {
	$theme_options = get_option( 'astra-settings', array() );

	// Set flag to not load button specific CSS.
	if ( ! isset( $theme_options['global-btn-woo-css'] ) ) {
		$theme_options['global-btn-woo-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Migrate Footer Widget param to array.
 *
 * @since 2.5.2
 *
 * @return void
 */
function astra_footer_widget_bg() {
	$theme_options = get_option( 'astra-settings', array() );

	// Check if Footer Backgound array is already set or not. If not then set it as array.
	if ( isset( $theme_options['footer-adv-bg-obj'] ) && ! is_array( $theme_options['footer-adv-bg-obj'] ) ) {
		$theme_options['footer-adv-bg-obj'] = array(
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'auto',
			'background-attachment' => 'scroll',
		);
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Check if we need to load icons as font or SVG.
 *
 * @since 3.3.0
 * @return void
 */
function astra_icons_svg_compatibility() {

	$theme_options = get_option( 'astra-settings' );

	if ( ! isset( $theme_options['can-update-astra-icons-svg'] ) ) {
		// Set a flag to check if we need to add icons as SVG.
		$theme_options['can-update-astra-icons-svg'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Migrate Background control options to new array.
 *
 * @since 3.0.0
 *
 * @return void
 */
function astra_bg_control_migration() {

	$db_options = array(
		'footer-adv-bg-obj',
		'footer-bg-obj',
		'sidebar-bg-obj',
	);

	$theme_options = get_option( 'astra-settings', array() );

	foreach ( $db_options as $option_name ) {

		if ( ! ( isset( $theme_options[ $option_name ]['background-type'] ) && isset( $theme_options[ $option_name ]['background-media'] ) ) && isset( $theme_options[ $option_name ] ) ) {

			if ( ! empty( $theme_options[ $option_name ]['background-image'] ) ) {
				$theme_options[ $option_name ]['background-type']  = 'image';
				$theme_options[ $option_name ]['background-media'] = attachment_url_to_postid( $theme_options[ $option_name ]['background-image'] );
			} else {
				$theme_options[ $option_name ]['background-type']  = '';
				$theme_options[ $option_name ]['background-media'] = '';
			}

			error_log( sprintf( 'Astra: Migrating Background Option - %s', $option_name ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			update_option( 'astra-settings', $theme_options );
		}
	}
}

/**
 * Migrate Background Responsive options to new array.
 *
 * @since 3.0.0
 *
 * @return void
 */
function astra_bg_responsive_control_migration() {

	$db_options = array(
		'site-layout-outside-bg-obj-responsive',
		'content-bg-obj-responsive',
		'header-bg-obj-responsive',
		'primary-menu-bg-obj-responsive',
		'above-header-bg-obj-responsive',
		'above-header-menu-bg-obj-responsive',
		'below-header-bg-obj-responsive',
		'below-header-menu-bg-obj-responsive',
	);

	$theme_options = get_option( 'astra-settings', array() );

	foreach ( $db_options as $option_name ) {

		if ( ! ( isset( $theme_options[ $option_name ]['desktop']['background-type'] ) && isset( $theme_options[ $option_name ]['desktop']['background-media'] ) ) && isset( $theme_options[ $option_name ] ) ) {

			if ( ! empty( $theme_options[ $option_name ]['desktop']['background-image'] ) ) {
				$theme_options[ $option_name ]['desktop']['background-type']  = 'image';
				$theme_options[ $option_name ]['desktop']['background-media'] = attachment_url_to_postid( $theme_options[ $option_name ]['desktop']['background-image'] );
			} else {
				$theme_options[ $option_name ]['desktop']['background-type']  = '';
				$theme_options[ $option_name ]['desktop']['background-media'] = '';
			}

			if ( ! empty( $theme_options[ $option_name ]['tablet']['background-image'] ) ) {
				$theme_options[ $option_name ]['tablet']['background-type']  = 'image';
				$theme_options[ $option_name ]['tablet']['background-media'] = attachment_url_to_postid( $theme_options[ $option_name ]['tablet']['background-image'] );
			} else {
				$theme_options[ $option_name ]['tablet']['background-type']  = '';
				$theme_options[ $option_name ]['tablet']['background-media'] = '';
			}

			if ( ! empty( $theme_options[ $option_name ]['mobile']['background-image'] ) ) {
				$theme_options[ $option_name ]['mobile']['background-type']  = 'image';
				$theme_options[ $option_name ]['mobile']['background-media'] = attachment_url_to_postid( $theme_options[ $option_name ]['mobile']['background-image'] );
			} else {
				$theme_options[ $option_name ]['mobile']['background-type']  = '';
				$theme_options[ $option_name ]['mobile']['background-media'] = '';
			}

			error_log( sprintf( 'Astra: Migrating Background Response Option - %s', $option_name ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			update_option( 'astra-settings', $theme_options );
		}
	}
}

/**
 * Do not apply new Group, Column and Media & Text block CSS for existing users.
 *
 * @since 3.0.0
 *
 * @return void
 */
function astra_gutenberg_core_blocks_design_compatibility() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['guntenberg-core-blocks-comp-css'] ) ) {
		$theme_options['guntenberg-core-blocks-comp-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Header Footer builder - Migration compatibility.
 *
 * @since 3.0.0
 *
 * @return void
 */
function astra_header_builder_compatibility() {
	$theme_options = get_option( 'astra-settings', array() );

	// Set flag to not load button specific CSS.
	if ( ! isset( $theme_options['is-header-footer-builder'] ) ) {
		$theme_options['is-header-footer-builder'] = false;
		update_option( 'astra-settings', $theme_options );
	}
	if ( ! isset( $theme_options['header-footer-builder-notice'] ) ) {
		$theme_options['header-footer-builder-notice'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Clears assets cache and regenerates new assets files.
 *
 * @since 3.0.1
 *
 * @return void
 */
function astra_clear_assets_cache() {
	if ( is_callable( 'Astra_Minify::refresh_assets' ) ) {
		Astra_Minify::refresh_assets();
	}
}

/**
 * Header Footer builder - Migration of options.
 *
 * @since 3.0.0
 *
 * @return void
 */
function astra_header_builder_migration() {

	/**
	 * All theme options.
	 */
	$theme_options = get_option( 'astra-settings', array() );

	// WordPress sidebar_widgets option.
	$widget_options = get_option( 'sidebars_widgets', array() );

	$used_elements = array();

	$options = array(
		'theme_options'  => $theme_options,
		'used_elements'  => $used_elements,
		'widget_options' => $widget_options,
	);

	$options = astra_primary_header_builder_migration( $options['theme_options'], $options['used_elements'], $options['widget_options'] );

	$options = astra_below_header_builder_migration( $options['theme_options'], $options['used_elements'], $options['widget_options'] );

	$options = astra_above_header_builder_migration( $options['theme_options'], $options['used_elements'], $options['widget_options'] );

	$options = astra_footer_builder_migration( $options['theme_options'], $options['used_elements'], $options['widget_options'] );

	$options = astra_footer_widgets_migration( $options['theme_options'], $options['used_elements'], $options['widget_options'] );

	$options = astra_primary_menu_builder_migration( $options['theme_options'], $options['used_elements'], $options['widget_options'] );

	$options = astra_sticky_header_builder_migration( $options['theme_options'], $options['used_elements'], $options['widget_options'] );

	$theme_options  = $options['theme_options'];
	$widget_options = $options['widget_options'];

	$theme_options['v3-option-migration'] = true;

	update_option( 'astra-settings', $theme_options );
	update_option( 'sidebars_widgets', $widget_options );

}

/**
 * Header Footer builder - Migration of Sticky Header.
 *
 * @since 3.0.0
 * @param array $theme_options Theme options.
 * @param array $used_elements Used Elements array.
 * @param array $widget_options Widget options.
 * @return array
 */
function astra_sticky_header_builder_migration( $theme_options, $used_elements, $widget_options ) {

	// Menu.
	$is_menu_in_primary = false;
	$is_menu_in_above   = false;
	$is_menu_in_below   = false;

	if ( isset( $theme_options['header-desktop-items']['primary'] ) ) {
		foreach ( $theme_options['header-desktop-items']['primary'] as $zone ) {
			if ( false !== array_search( 'menu-1', $zone ) ) {
				$is_menu_in_primary = true;
			}
		}
	}

	if ( isset( $theme_options['header-desktop-items']['above'] ) ) {
		foreach ( $theme_options['header-desktop-items']['above'] as $zone ) {
			if ( false !== array_search( 'menu-1', $zone ) ) {
				$is_menu_in_above = true;
			}
		}
	}

	if ( isset( $theme_options['header-desktop-items']['below'] ) ) {
		foreach ( $theme_options['header-desktop-items']['below'] as $zone ) {
			if ( false !== array_search( 'menu-1', $zone ) ) {
				$is_menu_in_below = true;
			}
		}
	}

	if ( $is_menu_in_primary ) {

		// Menu.
		// Normal.
		if ( isset( $theme_options['sticky-header-menu-color-responsive'] ) ) {
			$theme_options['sticky-header-menu1-color-responsive'] = $theme_options['sticky-header-menu-color-responsive'];
		}

		if ( isset( $theme_options['sticky-header-menu-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu1-bg-obj-responsive'] = $theme_options['sticky-header-menu-bg-color-responsive'];
		}


		// Hover.
		if ( isset( $theme_options['sticky-header-menu-h-color-responsive'] ) ) {
			$theme_options['sticky-header-menu1-h-color-responsive'] = $theme_options['sticky-header-menu-h-color-responsive'];
		}

		if ( isset( $theme_options['sticky-header-menu-h-a-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu1-h-bg-color-responsive'] = $theme_options['sticky-header-menu-h-a-bg-color-responsive'];
		}


		// Active.
		if ( isset( $theme_options['sticky-header-menu-h-color-responsive'] ) ) {
			$theme_options['sticky-header-menu1-a-color-responsive'] = $theme_options['sticky-header-menu-h-color-responsive'];
		}

		if ( isset( $theme_options['sticky-header-menu-h-a-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu1-a-bg-color-responsive'] = $theme_options['sticky-header-menu-h-a-bg-color-responsive'];
		}


		// Submenu.

		// Normal.
		if ( isset( $theme_options['sticky-header-submenu-color-responsive'] ) ) {
			$theme_options['sticky-header-menu1-submenu-color-responsive'] = $theme_options['sticky-header-submenu-color-responsive'];
		}

		if ( isset( $theme_options['sticky-header-submenu-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu1-submenu-bg-color-responsive'] = $theme_options['sticky-header-submenu-bg-color-responsive'];
		}


		// Hover.
		if ( isset( $theme_options['sticky-header-submenu-h-color-responsive'] ) ) {
			$theme_options['sticky-header-menu1-submenu-h-color-responsive'] = $theme_options['sticky-header-submenu-h-color-responsive'];
		}

		if ( isset( $theme_options['sticky-header-submenu-h-a-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu1-submenu-h-bg-color-responsive'] = $theme_options['sticky-header-submenu-h-a-bg-color-responsive'];
		}


		// Active.
		if ( isset( $theme_options['sticky-header-submenu-h-color-responsive'] ) ) {
			$theme_options['sticky-header-menu1-submenu-a-color-responsive'] = $theme_options['sticky-header-submenu-h-color-responsive'];
		}

		if ( isset( $theme_options['sticky-header-submenu-h-a-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu1-submenu-a-bg-color-responsive'] = $theme_options['sticky-header-submenu-h-a-bg-color-responsive'];
		}


		// Mega menu.

		// Normal.
		if ( isset( $theme_options['sticky-primary-header-megamenu-heading-color'] ) ) {
			$theme_options['sticky-header-menu1-header-megamenu-heading-color'] = $theme_options['sticky-primary-header-megamenu-heading-color'];
		}


		// Hover.
		if ( isset( $theme_options['sticky-primary-header-megamenu-heading-h-color'] ) ) {
			$theme_options['sticky-header-menu1-header-megamenu-heading-h-color'] = $theme_options['sticky-primary-header-megamenu-heading-h-color'];
		}
	}

	if ( $is_menu_in_above ) {

		// Menu.

		// Normal.
		if ( isset( $theme_options['sticky-above-header-menu-color-responsive'] ) ) {
			$theme_options['sticky-header-menu3-color-responsive'] = $theme_options['sticky-above-header-menu-color-responsive'];
		}

		if ( isset( $theme_options['sticky-above-header-menu-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu3-bg-obj-responsive'] = $theme_options['sticky-above-header-menu-bg-color-responsive'];
		}


		// Hover.
		if ( isset( $theme_options['sticky-above-header-menu-h-color-responsive'] ) ) {
			$theme_options['sticky-header-menu3-h-color-responsive'] = $theme_options['sticky-above-header-menu-h-color-responsive'];
		}

		if ( isset( $theme_options['sticky-above-header-menu-h-a-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu3-h-bg-color-responsive'] = $theme_options['sticky-above-header-menu-h-a-bg-color-responsive'];
		}


		// Active.
		if ( isset( $theme_options['sticky-above-header-menu-h-color-responsive'] ) ) {
			$theme_options['sticky-header-menu3-a-color-responsive'] = $theme_options['sticky-above-header-menu-h-color-responsive'];
		}

		if ( isset( $theme_options['sticky-above-header-menu-h-a-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu3-a-bg-color-responsive'] = $theme_options['sticky-above-header-menu-h-a-bg-color-responsive'];
		}


		// Submenu.

		// Normal.
		if ( isset( $theme_options['sticky-above-header-submenu-color-responsive'] ) ) {
			$theme_options['sticky-header-menu3-submenu-color-responsive'] = $theme_options['sticky-above-header-submenu-color-responsive'];
		}

		if ( isset( $theme_options['sticky-above-header-submenu-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu3-submenu-bg-obj-responsive'] = $theme_options['sticky-above-header-submenu-bg-color-responsive'];
		}


		// Hover.
		if ( isset( $theme_options['sticky-above-header-submenu-h-color-responsive'] ) ) {
			$theme_options['sticky-header-menu3-submenu-h-color-responsive'] = $theme_options['sticky-above-header-submenu-h-color-responsive'];
		}

		if ( isset( $theme_options['sticky-above-header-submenu-h-a-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu3-submenu-h-bg-color-responsive'] = $theme_options['sticky-above-header-submenu-h-a-bg-color-responsive'];
		}


		// Active.
		if ( isset( $theme_options['sticky-above-header-submenu-h-color-responsive'] ) ) {
			$theme_options['sticky-header-menu3-submenu-a-color-responsive'] = $theme_options['sticky-above-header-submenu-h-color-responsive'];
		}

		if ( isset( $theme_options['sticky-above-header-submenu-h-a-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu3-submenu-a-bg-color-responsive'] = $theme_options['sticky-above-header-submenu-h-a-bg-color-responsive'];
		}


		// Mega menu.

		// Normal.
		if ( isset( $theme_options['sticky-above-header-megamenu-heading-color'] ) ) {
			$theme_options['sticky-header-menu3-header-megamenu-heading-color'] = $theme_options['sticky-above-header-megamenu-heading-color'];
		}


		// Hover.
		if ( isset( $theme_options['sticky-above-header-megamenu-heading-h-color'] ) ) {
			$theme_options['sticky-header-menu3-header-megamenu-heading-h-color'] = $theme_options['sticky-above-header-megamenu-heading-h-color'];
		}
	}

	if ( $is_menu_in_below ) {

		// Menu.

		// Normal.
		if ( isset( $theme_options['sticky-below-header-menu-color-responsive'] ) ) {
			$theme_options['sticky-header-menu2-color-responsive'] = $theme_options['sticky-below-header-menu-color-responsive'];
		}

		if ( isset( $theme_options['sticky-below-header-menu-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu2-bg-obj-responsive'] = $theme_options['sticky-below-header-menu-bg-color-responsive'];
		}


		// Hover.
		if ( isset( $theme_options['sticky-below-header-menu-h-color-responsive'] ) ) {
			$theme_options['sticky-header-menu2-h-color-responsive'] = $theme_options['sticky-below-header-menu-h-color-responsive'];
		}

		if ( isset( $theme_options['sticky-below-header-menu-h-a-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu2-h-bg-color-responsive'] = $theme_options['sticky-below-header-menu-h-a-bg-color-responsive'];
		}


		// Active.
		if ( isset( $theme_options['sticky-below-header-menu-h-color-responsive'] ) ) {
			$theme_options['sticky-header-menu2-a-color-responsive'] = $theme_options['sticky-below-header-menu-h-color-responsive'];
		}

		if ( isset( $theme_options['sticky-below-header-menu-h-a-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu2-a-bg-color-responsive'] = $theme_options['sticky-below-header-menu-h-a-bg-color-responsive'];
		}


		// Submenu.

		// Normal.
		if ( isset( $theme_options['sticky-below-header-submenu-color-responsive'] ) ) {
			$theme_options['sticky-header-menu2-submenu-color-responsive'] = $theme_options['sticky-below-header-submenu-color-responsive'];
		}

		if ( isset( $theme_options['sticky-below-header-submenu-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu2-submenu-bg-obj-responsive'] = $theme_options['sticky-below-header-submenu-bg-color-responsive'];
		}


		// Hover.
		if ( isset( $theme_options['sticky-below-header-submenu-h-color-responsive'] ) ) {
			$theme_options['sticky-header-menu2-submenu-h-color-responsive'] = $theme_options['sticky-below-header-submenu-h-color-responsive'];
		}

		if ( isset( $theme_options['sticky-below-header-submenu-h-a-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu2-submenu-h-bg-color-responsive'] = $theme_options['sticky-below-header-submenu-h-a-bg-color-responsive'];
		}


		// Active.
		if ( isset( $theme_options['sticky-below-header-submenu-h-color-responsive'] ) ) {
			$theme_options['sticky-header-menu2-submenu-a-color-responsive'] = $theme_options['sticky-below-header-submenu-h-color-responsive'];
		}

		if ( isset( $theme_options['sticky-below-header-submenu-h-a-bg-color-responsive'] ) ) {
			$theme_options['sticky-header-menu2-submenu-a-bg-color-responsive'] = $theme_options['sticky-below-header-submenu-h-a-bg-color-responsive'];
		}


		// Mega menu.

		// Normal.
		if ( isset( $theme_options['sticky-below-header-megamenu-heading-color'] ) ) {
			$theme_options['sticky-header-menu2-header-megamenu-heading-color'] = $theme_options['sticky-below-header-megamenu-heading-color'];
		}


		// Hover.
		if ( isset( $theme_options['sticky-below-header-megamenu-heading-h-color'] ) ) {
			$theme_options['sticky-header-menu2-header-megamenu-heading-h-color'] = $theme_options['sticky-below-header-megamenu-heading-h-color'];
		}
	}

	// Sticky Site Title.

	// Normal.
	if ( isset( $theme_options['sticky-header-color-site-title-responsive']['desktop'] ) ) {
		$theme_options['sticky-header-builder-site-title-color'] = $theme_options['sticky-header-color-site-title-responsive']['desktop'];
	}


	// Hover.
	if ( isset( $theme_options['sticky-header-color-h-site-title-responsive']['desktop'] ) ) {
		$theme_options['sticky-header-builder-site-title-h-color'] = $theme_options['sticky-header-color-h-site-title-responsive']['desktop'];
	}


	// Sticky Site Tagline.
	if ( isset( $theme_options['sticky-header-color-site-tagline-responsive']['desktop'] ) ) {
		$theme_options['sticky-header-builder-site-tagline-color'] = $theme_options['sticky-header-color-site-tagline-responsive']['desktop'];
	}


	// Sticky Above/Below Header HTML.
	$is_html_in_above = false;
	$is_html_in_below = false;

	foreach ( $theme_options['header-desktop-items']['above'] as $zone ) {
		if ( false !== array_search( 'html-3', $zone ) ) {
			$is_html_in_above = true;
		}
	}
	foreach ( $theme_options['header-desktop-items']['below'] as $zone ) {
		if ( false !== array_search( 'html-2', $zone ) ) {
			$is_html_in_below = true;
		}
	}

	if ( $is_html_in_above ) {

		if ( isset( $theme_options['sticky-above-header-content-section-text-color-responsive']['desktop'] ) ) {
			$theme_options['sticky-header-html-3color'] = $theme_options['sticky-above-header-content-section-text-color-responsive']['desktop'];
		}
	}
	if ( $is_html_in_below ) {

		if ( isset( $theme_options['sticky-below-header-content-section-text-color-responsive']['desktop'] ) ) {
			$theme_options['sticky-header-html-2color'] = $theme_options['sticky-below-header-content-section-text-color-responsive']['desktop'];
		}
	}

	// Sticky Above/Below Header Search.
	$is_search_in_above = false;
	$is_search_in_below = false;

	foreach ( $theme_options['header-desktop-items']['above'] as $zone ) {
		if ( false !== array_search( 'search', $zone ) ) {
			$is_search_in_above = true;
		}
	}
	foreach ( $theme_options['header-desktop-items']['below'] as $zone ) {
		if ( false !== array_search( 'search', $zone ) ) {
			$is_search_in_below = true;
		}
	}

	if ( $is_search_in_above ) {

		if ( isset( $theme_options['sticky-above-header-content-section-link-color-responsive']['desktop'] ) ) {
			$theme_options['sticky-header-search-icon-color'] = $theme_options['sticky-above-header-content-section-link-color-responsive']['desktop'];
		}
	}
	if ( $is_search_in_below ) {

		if ( isset( $theme_options['sticky-below-header-content-section-link-color-responsive']['desktop'] ) ) {
			$theme_options['sticky-header-search-icon-color'] = $theme_options['sticky-below-header-content-section-link-color-responsive']['desktop'];
		}
	}

	// Sticky Above/Below Header Widget.
	$is_widget_in_above = false;
	$is_widget_in_below = false;

	foreach ( $theme_options['header-desktop-items']['above'] as $zone ) {
		if ( false !== array_search( 'widget-3', $zone ) ) {
			$is_widget_in_above = true;
		}
	}
	foreach ( $theme_options['header-desktop-items']['below'] as $zone ) {
		if ( false !== array_search( 'widget-2', $zone ) ) {
			$is_widget_in_below = true;
		}
	}

	if ( $is_widget_in_above ) {

		if ( isset( $theme_options['sticky-above-header-content-section-text-color-responsive']['desktop'] ) ) {
			$theme_options['sticky-header-widget-3-title-color'] = $theme_options['sticky-above-header-content-section-text-color-responsive']['desktop'];
		}

		if ( isset( $theme_options['sticky-above-header-content-section-text-color-responsive']['desktop'] ) ) {
			$theme_options['sticky-header-widget-3-color'] = $theme_options['sticky-above-header-content-section-text-color-responsive']['desktop'];
		}

		if ( isset( $theme_options['sticky-above-header-content-section-link-color-responsive']['desktop'] ) ) {
			$theme_options['sticky-header-widget-3-link-color'] = $theme_options['sticky-above-header-content-section-link-color-responsive']['desktop'];
		}

		if ( isset( $theme_options['sticky-above-header-content-section-link-h-color-responsive']['desktop'] ) ) {
			$theme_options['sticky-header-widget-3-link-h-color'] = $theme_options['sticky-above-header-content-section-link-h-color-responsive']['desktop'];
		}
	}
	if ( $is_widget_in_below ) {

		if ( isset( $theme_options['sticky-below-header-content-section-text-color-responsive']['desktop'] ) ) {
			$theme_options['sticky-header-widget-2-title-color'] = $theme_options['sticky-below-header-content-section-text-color-responsive']['desktop'];
		}

		if ( isset( $theme_options['sticky-below-header-content-section-text-color-responsive']['desktop'] ) ) {
			$theme_options['sticky-header-widget-2-color'] = $theme_options['sticky-below-header-content-section-text-color-responsive']['desktop'];
		}

		if ( isset( $theme_options['sticky-below-header-content-section-link-color-responsive']['desktop'] ) ) {
			$theme_options['sticky-header-widget-2-link-color'] = $theme_options['sticky-below-header-content-section-link-color-responsive']['desktop'];
		}

		if ( isset( $theme_options['sticky-below-header-content-section-link-h-color-responsive']['desktop'] ) ) {
			$theme_options['sticky-header-widget-2-link-h-color'] = $theme_options['sticky-below-header-content-section-link-h-color-responsive']['desktop'];
		}
	}

	return array(
		'theme_options'  => $theme_options,
		'used_elements'  => $used_elements,
		'widget_options' => $widget_options,
	);
}

/**
 * Header Footer builder - Migration of Primary Menu.
 *
 * @since 3.0.0
 * @param array $theme_options Theme options.
 * @param array $used_elements Used Elements array.
 * @param array $widget_options Widget options.
 * @return array
 */
function astra_primary_menu_builder_migration( $theme_options, $used_elements, $widget_options ) {

	/**
	 * Primary Menu.
	 */
	if ( isset( $theme_options['header-main-submenu-container-animation'] ) ) {
		$theme_options['header-menu1-submenu-container-animation'] = $theme_options['header-main-submenu-container-animation'];
	}
	if ( isset( $theme_options['primary-submenu-border'] ) ) {
		$theme_options['header-menu1-submenu-border'] = $theme_options['primary-submenu-border'];
	}
	if ( isset( $theme_options['primary-submenu-b-color'] ) ) {
		$theme_options['header-menu1-submenu-b-color'] = $theme_options['primary-submenu-b-color'];
	}
	if ( isset( $theme_options['primary-submenu-item-border'] ) ) {
		$theme_options['header-menu1-submenu-item-border'] = $theme_options['primary-submenu-item-border'];
	}
	if ( isset( $theme_options['primary-submenu-item-b-color'] ) ) {
		$theme_options['header-menu1-submenu-item-b-color'] = $theme_options['primary-submenu-item-b-color'];
	}

	/**
	 * Primary Menu.
	 */

	if ( isset( $theme_options['primary-menu-color-responsive'] ) ) {
		$theme_options['header-menu1-color-responsive'] = $theme_options['primary-menu-color-responsive'];
	}

	if ( isset( $theme_options['primary-menu-bg-obj-responsive'] ) ) {
		$theme_options['header-menu1-bg-obj-responsive'] = $theme_options['primary-menu-bg-obj-responsive'];
	}


	if ( isset( $theme_options['primary-menu-text-h-color-responsive'] ) ) {
		$theme_options['header-menu1-h-color-responsive'] = $theme_options['primary-menu-text-h-color-responsive'];
	}

	if ( isset( $theme_options['primary-menu-h-bg-color-responsive'] ) ) {
		$theme_options['header-menu1-h-bg-color-responsive'] = $theme_options['primary-menu-h-bg-color-responsive'];
	}


	if ( isset( $theme_options['primary-menu-a-color-responsive'] ) ) {
		$theme_options['header-menu1-a-color-responsive'] = $theme_options['primary-menu-a-color-responsive'];
	}

	if ( isset( $theme_options['primary-menu-a-bg-color-responsive'] ) ) {
		$theme_options['header-menu1-a-bg-color-responsive'] = $theme_options['primary-menu-a-bg-color-responsive'];
	}


	if ( isset( $theme_options['font-size-primary-menu'] ) ) {
		$theme_options['header-menu1-font-size'] = $theme_options['font-size-primary-menu'];
	}

	if ( isset( $theme_options['font-weight-primary-menu'] ) ) {
		$theme_options['header-menu1-font-weight'] = $theme_options['font-weight-primary-menu'];
	}

	if ( isset( $theme_options['line-height-primary-menu'] ) ) {
		$theme_options['header-menu1-line-height'] = $theme_options['line-height-primary-menu'];
	}

	if ( isset( $theme_options['font-family-primary-menu'] ) ) {
		$theme_options['header-menu1-font-family'] = $theme_options['font-family-primary-menu'];
	}

	if ( isset( $theme_options['text-transform-primary-menu'] ) ) {
		$theme_options['header-menu1-text-transform'] = $theme_options['text-transform-primary-menu'];
	}

	if ( isset( $theme_options['primary-menu-spacing'] ) ) {
		$theme_options['header-menu1-menu-spacing'] = $theme_options['primary-menu-spacing'];
	}

	// Primary Menu - Submenu.
	if ( isset( $theme_options['primary-submenu-color-responsive'] ) ) {
		$theme_options['header-menu1-submenu-color-responsive'] = $theme_options['primary-submenu-color-responsive'];
	}

	if ( isset( $theme_options['primary-submenu-bg-color-responsive'] ) ) {
		$theme_options['header-menu1-submenu-bg-color-responsive'] = $theme_options['primary-submenu-bg-color-responsive'];
	}

	if ( isset( $theme_options['primary-submenu-h-color-responsive'] ) ) {
		$theme_options['header-menu1-submenu-h-color-responsive'] = $theme_options['primary-submenu-h-color-responsive'];
	}

	if ( isset( $theme_options['primary-submenu-h-bg-color-responsive'] ) ) {
		$theme_options['header-menu1-submenu-h-bg-color-responsive'] = $theme_options['primary-submenu-h-bg-color-responsive'];
	}

	if ( isset( $theme_options['primary-submenu-a-color-responsive'] ) ) {
		$theme_options['header-menu1-submenu-a-color-responsive'] = $theme_options['primary-submenu-a-color-responsive'];
	}

	if ( isset( $theme_options['primary-submenu-a-bg-color-responsive'] ) ) {
		$theme_options['header-menu1-submenu-a-bg-color-responsive'] = $theme_options['primary-submenu-a-bg-color-responsive'];
	}

	if ( isset( $theme_options['font-size-primary-dropdown-menu'] ) ) {
		$theme_options['header-font-size-menu1-sub-menu'] = $theme_options['font-size-primary-dropdown-menu'];
	}

	if ( isset( $theme_options['font-weight-primary-dropdown-menu'] ) ) {
		$theme_options['header-font-weight-menu1-sub-menu'] = $theme_options['font-weight-primary-dropdown-menu'];
	}

	if ( isset( $theme_options['line-height-primary-dropdown-menu'] ) ) {
		$theme_options['header-line-height-menu1-sub-menu'] = $theme_options['line-height-primary-dropdown-menu'];
	}

	if ( isset( $theme_options['font-family-primary-dropdown-menu'] ) ) {
		$theme_options['header-font-family-menu1-sub-menu'] = $theme_options['font-family-primary-dropdown-menu'];
	}

	if ( isset( $theme_options['text-transform-primary-dropdown-menu'] ) ) {
		$theme_options['header-text-transform-menu1-sub-menu'] = $theme_options['text-transform-primary-dropdown-menu'];
	}

	if ( isset( $theme_options['primary-submenu-spacing'] ) ) {
		$theme_options['header-menu1-submenu-spacing'] = $theme_options['primary-submenu-spacing'];
	}

	// Primary Menu - Mega Menu.
	if ( isset( $theme_options['primary-header-megamenu-heading-color'] ) ) {
		$theme_options['header-menu1-header-megamenu-heading-color'] = $theme_options['primary-header-megamenu-heading-color'];
	}

	if ( isset( $theme_options['primary-header-megamenu-heading-h-color'] ) ) {
		$theme_options['header-menu1-header-megamenu-heading-h-color'] = $theme_options['primary-header-megamenu-heading-h-color'];
	}

	if ( isset( $theme_options['primary-header-megamenu-heading-font-size'] ) ) {
		$theme_options['header-menu1-megamenu-heading-font-size'] = $theme_options['primary-header-megamenu-heading-font-size'];
	}

	if ( isset( $theme_options['primary-header-megamenu-heading-font-weight'] ) ) {
		$theme_options['header-menu1-megamenu-heading-font-weight'] = $theme_options['primary-header-megamenu-heading-font-weight'];
	}

	if ( isset( $theme_options['primary-header-megamenu-heading-line-height'] ) ) {
		$theme_options['header-menu1-megamenu-heading-line-height'] = $theme_options['primary-header-megamenu-heading-line-height'];
	}

	if ( isset( $theme_options['primary-header-megamenu-heading-font-family'] ) ) {
		$theme_options['header-menu1-megamenu-heading-font-family'] = $theme_options['primary-header-megamenu-heading-font-family'];
	}

	if ( isset( $theme_options['primary-header-megamenu-heading-text-transform'] ) ) {
		$theme_options['header-menu1-megamenu-heading-text-transform'] = $theme_options['primary-header-megamenu-heading-text-transform'];
	}

	if ( isset( $theme_options['primary-header-megamenu-heading-space'] ) ) {
		$theme_options['header-menu1-megamenu-heading-space'] = $theme_options['primary-header-megamenu-heading-space'];
	}


	/**
	 * Primary Menu - Mobile.
	 */
	if ( isset( $theme_options['header-main-menu-label'] ) ) {
		$theme_options['mobile-header-menu-label'] = $theme_options['header-main-menu-label'];
	}

	if ( isset( $theme_options['mobile-header-toggle-btn-style-color'] ) ) {
		$theme_options['mobile-header-toggle-btn-color']    = $theme_options['mobile-header-toggle-btn-style-color'];
		$theme_options['mobile-header-toggle-border-color'] = $theme_options['mobile-header-toggle-btn-style-color'];
	}

	if ( isset( $theme_options['mobile-header-toggle-btn-border-radius'] ) ) {
		$theme_options['mobile-header-toggle-border-radius'] = $theme_options['mobile-header-toggle-btn-border-radius'];
	}

	return array(
		'theme_options'  => $theme_options,
		'used_elements'  => $used_elements,
		'widget_options' => $widget_options,
	);
}

/**
 * Header Footer builder - Migration of Primary Header.
 *
 * @since 3.0.0
 * @param array $theme_options Theme options.
 * @param array $used_elements Used Elements array.
 * @param array $widget_options Widget options.
 * @return array
 */
function astra_primary_header_builder_migration( $theme_options, $used_elements, $widget_options ) {

	/**
	 * Primary Header.
	 */

	// Header : Primary Header - Layout.
	$primary_header_layout = ( isset( $theme_options['header-layouts'] ) ) ? $theme_options['header-layouts'] : '';

	// Header : Primary Header - Last Menu Item.
	$last_menu_item                = ( isset( $theme_options['header-main-rt-section'] ) ) ? $theme_options['header-main-rt-section'] : '';
	$last_menu_item_mobile_flag    = ( isset( $theme_options['hide-custom-menu-mobile'] ) ) ? $theme_options['hide-custom-menu-mobile'] : '';
	$last_menu_item_mobile_outside = ( isset( $theme_options['header-display-outside-menu'] ) ) ? $theme_options['header-display-outside-menu'] : '';
	$new_menu_item                 = '';

	$theme_options['mobile-header-type'] = 'dropdown';

	if ( isset( $theme_options['mobile-menu-style'] ) ) {
		switch ( $theme_options['mobile-menu-style'] ) {
			case 'flyout':
				$theme_options['mobile-header-type'] = 'off-canvas';
				if ( isset( $theme_options['flyout-mobile-menu-alignment'] ) ) {
					$theme_options['off-canvas-slide'] = $theme_options['flyout-mobile-menu-alignment'];
				}
				break;
			case 'fullscreen':
				$theme_options['mobile-header-type'] = 'full-width';
				break;

			case 'default':
			default:
				$theme_options['mobile-header-type'] = 'dropdown';
				break;
		}
	}

	switch ( $last_menu_item ) {
		case 'search':
			$new_menu_item = 'search';
			if ( isset( $theme_options['header-main-rt-section-search-box-type'] ) ) {
				$theme_options['header-search-box-type'] = $theme_options['header-main-rt-section-search-box-type'];
			}
			break;

		case 'button':
			$new_menu_item = 'button-1';
			if ( isset( $theme_options['header-main-rt-section-button-text'] ) ) {
				$theme_options['header-button1-text'] = $theme_options['header-main-rt-section-button-text'];
			}
			if ( isset( $theme_options['header-main-rt-section-button-link-option'] ) ) {
				$theme_options['header-button1-link-option'] = $theme_options['header-main-rt-section-button-link-option'];
			}
			if ( isset( $theme_options['header-main-rt-section-button-text-color'] ) ) {
				$theme_options['header-button1-text-color'] = array(
					'desktop' => $theme_options['header-main-rt-section-button-text-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			if ( isset( $theme_options['header-main-rt-section-button-back-color'] ) ) {
				$theme_options['header-button1-back-color'] = array(
					'desktop' => $theme_options['header-main-rt-section-button-back-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			if ( isset( $theme_options['header-main-rt-section-button-text-h-color'] ) ) {
				$theme_options['header-button1-text-h-color'] = array(
					'desktop' => $theme_options['header-main-rt-section-button-text-h-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			if ( isset( $theme_options['header-main-rt-section-button-back-h-color'] ) ) {
				$theme_options['header-button1-back-h-color'] = array(
					'desktop' => $theme_options['header-main-rt-section-button-back-h-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			if ( isset( $theme_options['header-main-rt-section-button-border-size'] ) ) {
				$theme_options['header-button1-border-size'] = $theme_options['header-main-rt-section-button-border-size'];
			}
			if ( isset( $theme_options['header-main-rt-section-button-border-color'] ) ) {
				$theme_options['header-button1-border-color'] = array(
					'desktop' => $theme_options['header-main-rt-section-button-border-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			if ( isset( $theme_options['header-main-rt-section-button-border-h-color'] ) ) {
				$theme_options['header-button1-border-h-color'] = array(
					'desktop' => $theme_options['header-main-rt-section-button-border-h-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			if ( isset( $theme_options['header-main-rt-section-button-border-radius'] ) ) {
				$theme_options['header-button1-border-radius'] = $theme_options['header-main-rt-section-button-border-radius'];
			}
			if ( isset( $theme_options['primary-header-button-font-family'] ) ) {
				$theme_options['header-button1-font-family'] = $theme_options['primary-header-button-font-family'];
			}
			if ( isset( $theme_options['primary-header-button-font-size'] ) ) {
				$theme_options['header-button1-font-size'] = $theme_options['primary-header-button-font-size'];
			}
			if ( isset( $theme_options['primary-header-button-font-weight'] ) ) {
				$theme_options['header-button1-font-weight'] = $theme_options['primary-header-button-font-weight'];
			}
			if ( isset( $theme_options['primary-header-button-text-transform'] ) ) {
				$theme_options['header-button1-text-transform'] = $theme_options['primary-header-button-text-transform'];
			}
			if ( isset( $theme_options['primary-header-button-line-height'] ) ) {
				$theme_options['header-button1-line-height'] = $theme_options['primary-header-button-line-height'];
			}
			if ( isset( $theme_options['primary-header-button-letter-spacing'] ) ) {
				$theme_options['header-button1-letter-spacing'] = $theme_options['primary-header-button-letter-spacing'];
			}
			if ( isset( $theme_options['header-main-rt-section-button-padding'] ) ) {
				$theme_options['section-hb-button-1-padding'] = $theme_options['header-main-rt-section-button-padding'];
			}
			// Sticky Header Button options.

			// Text Color.
			if ( isset( $theme_options['header-main-rt-sticky-section-button-text-color'] ) ) {

				$theme_options['sticky-header-button1-text-color'] = array(
					'desktop' => $theme_options['header-main-rt-sticky-section-button-text-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			// BG Color.
			if ( isset( $theme_options['header-main-rt-sticky-section-button-back-color'] ) ) {
				$theme_options['sticky-header-button1-back-color'] = array(
					'desktop' => $theme_options['header-main-rt-sticky-section-button-back-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			// Text Hover Color.
			if ( isset( $theme_options['header-main-rt-sticky-section-button-text-h-color'] ) ) {
				$theme_options['sticky-header-button1-text-h-color'] = array(
					'desktop' => $theme_options['header-main-rt-sticky-section-button-text-h-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			// BG Hover Color.
			if ( isset( $theme_options['header-main-rt-sticky-section-button-back-h-color'] ) ) {
				$theme_options['sticky-header-button1-back-h-color'] = array(
					'desktop' => $theme_options['header-main-rt-sticky-section-button-back-h-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			// Border Width.
			if ( isset( $theme_options['header-main-rt-sticky-section-button-border-size'] ) ) {
				$theme_options['sticky-header-button1-border-size'] = $theme_options['header-main-rt-sticky-section-button-border-size'];
			}
			// Border Color.
			if ( isset( $theme_options['header-main-rt-sticky-section-button-border-color'] ) ) {
				$theme_options['sticky-header-button1-border-color'] = array(
					'desktop' => $theme_options['header-main-rt-sticky-section-button-border-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			// Border Hover Color.
			if ( isset( $theme_options['header-main-rt-sticky-section-button-border-h-color'] ) ) {
				$theme_options['sticky-header-button1-border-h-color'] = array(
					'desktop' => $theme_options['header-main-rt-sticky-section-button-border-h-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			// Border Radius.
			if ( isset( $theme_options['header-main-rt-sticky-section-button-border-radius'] ) ) {
				$theme_options['sticky-header-button1-border-radius'] = $theme_options['header-main-rt-sticky-section-button-border-radius'];
			}
			// Padding.
			if ( isset( $theme_options['header-main-rt-sticky-section-button-padding'] ) ) {
				$theme_options['sticky-header-button1-padding'] = $theme_options['header-main-rt-sticky-section-button-padding'];
			}

			break;

		case 'text-html':
			$new_menu_item = 'html-1';
			if ( isset( $theme_options['header-main-rt-section-html'] ) ) {
				$theme_options['header-html-1'] = $theme_options['header-main-rt-section-html'];
			}
			break;

		case 'widget':
			$new_menu_item = 'widget-1';
			if ( isset( $widget_options['header-widget'] ) ) {
				$widget_options['header-widget-1'] = $widget_options['header-widget'];
			}
			break;

		case 'woocommerce':
			$new_menu_item = 'woo-cart';
			if ( ! empty( $theme_options['woo-header-cart-icon-color'] ) ) {
				$theme_options['header-woo-cart-icon-color'] = $theme_options['woo-header-cart-icon-color'];
			}
			break;

		case 'edd':
			$new_menu_item = 'edd-cart';
			break;
	}

	$used_elements[] = $new_menu_item;

	$new_menu_item_mobile = ( ! $last_menu_item_mobile_flag ) ? $new_menu_item : '';

	$new_menu_item_mobile_outside = '';
	if ( ! $last_menu_item_mobile_flag && $last_menu_item_mobile_outside ) {
		$new_menu_item_mobile_outside = $new_menu_item;
		$new_menu_item_mobile         = '';
	}

	$theme_options['header-desktop-items']['above'] = array(
		'above_left'         => array(),
		'above_left_center'  => array(),
		'above_center'       => array(),
		'above_right_center' => array(),
		'above_right'        => array(),
	);
	$theme_options['header-mobile-items']['above']  = array(
		'above_left'   => array(),
		'above_center' => array(),
		'above_right'  => array(),
	);


	$theme_options['header-desktop-items']['below'] = array(
		'below_left'         => array(),
		'below_left_center'  => array(),
		'below_center'       => array(),
		'below_right_center' => array(),
		'below_right'        => array(),
	);

	$theme_options['header-mobile-items']['below'] = array(
		'below_left'   => array(),
		'below_center' => array(),
		'below_right'  => array(),
	);

	/**
	 * Assign the new locations.
	 */
	switch ( $primary_header_layout ) {
		case 'header-main-layout-1':
			$theme_options['header-desktop-items']['primary'] = array(
				'primary_left'         => array( 'logo' ),
				'primary_left_center'  => array(),
				'primary_center'       => array(),
				'primary_right_center' => array(),
				'primary_right'        => ( '' !== $new_menu_item ) ? array( 'menu-1', $new_menu_item ) : array( 'menu-1' ),
			);
			break;

		case 'header-main-layout-2':
			$theme_options['header-desktop-items']['primary'] = array(
				'primary_left'         => array(),
				'primary_left_center'  => array(),
				'primary_center'       => array( 'logo' ),
				'primary_right_center' => array(),
				'primary_right'        => array(),
			);
			$theme_options['header-desktop-items']['below']   = array(
				'below_left'         => array(),
				'below_left_center'  => array(),
				'below_center'       => ( '' !== $new_menu_item ) ? array( 'menu-1', $new_menu_item ) : array( 'menu-1' ),
				'below_right_center' => array(),
				'below_right'        => array(),
			);
			break;

		case 'header-main-layout-3':
			$theme_options['header-desktop-items']['primary'] = array(
				'primary_left'         => ( '' !== $new_menu_item ) ? array( 'menu-1', $new_menu_item ) : array( 'menu-1' ),
				'primary_left_center'  => array(),
				'primary_center'       => array(),
				'primary_right_center' => array(),
				'primary_right'        => array( 'logo' ),
			);
			break;

		default:
			$theme_options['header-desktop-items']['primary'] = array(
				'primary_left'         => array( 'logo' ),
				'primary_left_center'  => array(),
				'primary_center'       => array(),
				'primary_right_center' => array(),
				'primary_right'        => array( 'menu-1' ),
			);
	}

	// Header : Primary Header - Mobile Layout.
	$mobile_layout = astra_get_option( 'header-main-menu-align' );

	if ( 'stack' === $mobile_layout ) {
		$theme_options['header-mobile-items']['popup'] = array( 'popup_content' => ( '' !== $new_menu_item_mobile && '' !== $new_menu_item_mobile_outside ) ? array( 'menu-1', $new_menu_item_mobile ) : array( 'menu-1' ) );

		$theme_options['header-mobile-items']['primary'] = array(
			'primary_left'   => array(),
			'primary_center' => array( 'logo' ),
			'primary_right'  => array(),
		);

		$theme_options['header-mobile-items']['below'] = array(
			'below_left'   => array(),
			'below_center' => ( '' !== $new_menu_item_mobile_outside ) ? array( $new_menu_item_mobile_outside, 'mobile-trigger' ) : array( 'mobile-trigger' ),
			'below_right'  => array(),
		);
	} else {

		$theme_options['header-mobile-items']['popup'] = array( 'popup_content' => ( '' !== $new_menu_item_mobile ) ? array( 'menu-1', $new_menu_item_mobile ) : array( 'menu-1' ) );

		if ( 'header-main-layout-3' === $primary_header_layout ) {
			$theme_options['header-mobile-items']['primary'] = array(
				'primary_left'   => ( '' !== $new_menu_item_mobile_outside ) ? array( $new_menu_item_mobile_outside, 'mobile-trigger' ) : array( 'mobile-trigger' ),
				'primary_center' => array(),
				'primary_right'  => array( 'logo' ),
			);
		} else {
			$theme_options['header-mobile-items']['primary'] = array(
				'primary_left'   => array( 'logo' ),
				'primary_center' => array(),
				'primary_right'  => ( '' !== $new_menu_item_mobile_outside ) ? array( $new_menu_item_mobile_outside, 'mobile-trigger' ) : array( 'mobile-trigger' ),
			);
		}
	}

	// Header - Primary Header - Content Width.
	if ( isset( $theme_options['header-main-layout-width'] ) ) {
		$theme_options['hb-header-main-layout-width'] = $theme_options['header-main-layout-width'];
	}

	// Header - Primary Header - Border Bottom.
	if ( isset( $theme_options['header-main-sep'] ) ) {
		$theme_options['hb-header-main-sep'] = $theme_options['header-main-sep'];
	}

	if ( isset( $theme_options['header-main-sep-color'] ) ) {
		$theme_options['hb-header-main-sep-color'] = $theme_options['header-main-sep-color'];
	}

	if ( isset( $theme_options['header-bg-obj-responsive'] ) ) {
		$theme_options['hb-header-bg-obj-responsive'] = $theme_options['header-bg-obj-responsive'];
	}

	if ( isset( $theme_options['header-spacing'] ) ) {
		$theme_options['section-primary-header-builder-padding'] = $theme_options['header-spacing'];
	}

	return array(
		'theme_options'  => $theme_options,
		'used_elements'  => $used_elements,
		'widget_options' => $widget_options,
	);
}

/**
 * Header Footer builder - Migration of Above Header.
 *
 * @since 3.0.0
 * @param array $theme_options Theme options.
 * @param array $used_elements Used Elements array.
 * @param array $widget_options Widget options.
 * @return array
 */
function astra_above_header_builder_migration( $theme_options, $used_elements, $widget_options ) {
	/**
	 * Above Header.
	 */

	$above_header_layout      = ( isset( $theme_options['above-header-layout'] ) ) ? $theme_options['above-header-layout'] : '';
	$above_header_on_mobile   = ( isset( $theme_options['above-header-on-mobile'] ) ) ? $theme_options['above-header-on-mobile'] : '';
	$above_header_merge_menu  = ( isset( $theme_options['above-header-merge-menu'] ) ) ? $theme_options['above-header-merge-menu'] : '';
	$above_header_swap_mobile = ( isset( $theme_options['above-header-swap-mobile'] ) ) ? $theme_options['above-header-swap-mobile'] : '';

	if ( isset( $theme_options['above-header-height'] ) ) {
		$theme_options['hba-header-height'] = array(
			'desktop' => $theme_options['above-header-height'],
			'tablet'  => '',
			'mobile'  => '',
		);
	}
	if ( isset( $theme_options['above-header-divider'] ) ) {
		$theme_options['hba-header-separator'] = $theme_options['above-header-divider'];
	}
	if ( isset( $theme_options['above-header-divider-color'] ) ) {
		$theme_options['hba-header-bottom-border-color'] = $theme_options['above-header-divider-color'];
	}
	if ( isset( $theme_options['above-header-bg-obj-responsive'] ) ) {
		$theme_options['hba-header-bg-obj-responsive'] = $theme_options['above-header-bg-obj-responsive'];
	}
	if ( isset( $theme_options['above-header-spacing'] ) ) {
		$theme_options['section-above-header-builder-padding'] = $theme_options['above-header-spacing'];
	}
	// Above Header Section 1.
	$above_header_section_1          = ( isset( $theme_options['above-header-section-1'] ) ) ? $theme_options['above-header-section-1'] : '';
	$new_above_header_section_1_item = '';

	switch ( $above_header_section_1 ) {
		case 'menu':
			$new_above_header_section_1_item = 'menu-3';
			break;

		case 'search':
			if ( ! in_array( 'search', $used_elements ) ) {
				$new_above_header_section_1_item = 'search';
				if ( isset( $theme_options['above-header-section-1-search-box-type'] ) ) {
					$theme_options['header-search-box-type'] = $theme_options['above-header-section-1-search-box-type'];
				}
			}
			break;

		case 'text-html':
			if ( ! in_array( 'html-3', $used_elements ) ) {
				$new_above_header_section_1_item = 'html-3';
				if ( isset( $theme_options['above-header-section-1-html'] ) ) {
					$theme_options['header-html-3'] = $theme_options['above-header-section-1-html'];
				}
			}

			break;

		case 'widget':
			if ( ! in_array( 'widget-3', $used_elements ) ) {
				$new_above_header_section_1_item = 'widget-3';
				if ( isset( $widget_options['above-header-widget-1'] ) ) {
					$widget_options['header-widget-3'] = $widget_options['above-header-widget-1'];
				}
			}
			break;

		case 'woocommerce':
			if ( ! in_array( 'woo-cart', $used_elements ) ) {
				$new_above_header_section_1_item = 'woo-cart';
			}
			break;

		case 'edd':
			if ( ! in_array( 'edd-cart', $used_elements ) ) {
				$new_above_header_section_1_item = 'edd-cart';
			}
			break;
	}

	// Above Header Section 2.
	$above_header_section_2          = ( isset( $theme_options['above-header-section-2'] ) ) ? $theme_options['above-header-section-2'] : '';
	$new_above_header_section_2_item = '';
	switch ( $above_header_section_2 ) {
		case 'menu':
			$new_above_header_section_2_item = 'menu-3';
			break;

		case 'search':
			if ( ! in_array( 'search', $used_elements ) ) {
				$new_above_header_section_2_item = 'search';
				if ( isset( $theme_options['above-header-section-2-search-box-type'] ) ) {
					$theme_options['header-search-box-type'] = $theme_options['above-header-section-2-search-box-type'];
				}
			}
			break;

		case 'text-html':
			if ( ! in_array( 'html-3', $used_elements ) ) {
				$new_above_header_section_2_item = 'html-3';
				if ( isset( $theme_options['above-header-section-2-html'] ) ) {
					$theme_options['header-html-3'] = $theme_options['above-header-section-2-html'];
				}
			}

			break;

		case 'widget':
			if ( ! in_array( 'widget-3', $used_elements ) ) {
				$new_above_header_section_2_item = 'widget-3';
				if ( isset( $widget_options['above-header-widget-2'] ) ) {
					$widget_options['header-widget-3'] = $widget_options['above-header-widget-2'];
				}
			}
			break;

		case 'woocommerce':
			if ( ! in_array( 'woo-cart', $used_elements ) ) {
				$new_above_header_section_2_item = 'woo-cart';
			}
			break;

		case 'edd':
			if ( ! in_array( 'edd-cart', $used_elements ) ) {
				$new_above_header_section_2_item = 'edd-cart';
			}
			break;
	}

	if ( 'menu' === $above_header_section_1 || 'menu' === $above_header_section_2 ) {
		$theme_options['header-menu3-menu-stack-on-mobile'] = false;
		/**
		 * Menu - 3
		 */
		if ( isset( $theme_options['above-header-submenu-container-animation'] ) ) {
			$theme_options['header-menu3-submenu-container-animation'] = $theme_options['above-header-submenu-container-animation'];
		}
		if ( isset( $theme_options['above-header-submenu-border'] ) ) {
			$theme_options['header-menu3-submenu-border'] = $theme_options['above-header-submenu-border'];
		}
		if ( isset( $theme_options['above-header-submenu-b-color'] ) ) {
			$theme_options['header-menu3-submenu-b-color'] = $theme_options['above-header-submenu-b-color'];
		}
		if ( isset( $theme_options['above-header-submenu-item-border'] ) ) {
			$theme_options['header-menu3-submenu-item-border'] = $theme_options['above-header-submenu-item-border'];
		}
		if ( isset( $theme_options['above-header-submenu-item-b-color'] ) ) {
			$theme_options['header-menu3-submenu-item-b-color'] = $theme_options['above-header-submenu-item-b-color'];
		}

		if ( isset( $theme_options['above-header-menu-text-color-responsive'] ) ) {
			$theme_options['header-menu3-color-responsive'] = $theme_options['above-header-menu-text-color-responsive'];
		}
		if ( isset( $theme_options['above-header-menu-bg-obj-responsive'] ) ) {
			$theme_options['header-menu3-bg-obj-responsive'] = $theme_options['above-header-menu-bg-obj-responsive'];
		}

		if ( isset( $theme_options['above-header-menu-text-hover-color-responsive'] ) ) {
			$theme_options['header-menu3-h-color-responsive'] = $theme_options['above-header-menu-text-hover-color-responsive'];
		}
		if ( isset( $theme_options['above-header-menu-bg-hover-color-responsive'] ) ) {
			$theme_options['header-menu3-h-bg-color-responsive'] = $theme_options['above-header-menu-bg-hover-color-responsive'];
		}

		if ( isset( $theme_options['above-header-current-menu-text-color-responsive'] ) ) {
			$theme_options['header-menu3-a-color-responsive'] = $theme_options['above-header-current-menu-text-color-responsive'];
		}
		if ( isset( $theme_options['above-header-current-menu-bg-color-responsive'] ) ) {
			$theme_options['header-menu3-a-bg-color-responsive'] = $theme_options['above-header-current-menu-bg-color-responsive'];
		}

		if ( isset( $theme_options['above-header-font-size'] ) ) {
			$theme_options['header-menu3-font-size'] = $theme_options['above-header-font-size'];
		}
		if ( isset( $theme_options['above-header-font-weight'] ) ) {
			$theme_options['header-menu3-font-weight'] = $theme_options['above-header-font-weight'];
		}
		if ( isset( $theme_options['above-header-line-height'] ) ) {
			$theme_options['header-menu3-line-height'] = $theme_options['above-header-line-height'];
		}
		if ( isset( $theme_options['above-header-font-family'] ) ) {
			$theme_options['header-menu3-font-family'] = $theme_options['above-header-font-family'];
		}
		if ( isset( $theme_options['above-header-text-transform'] ) ) {
			$theme_options['header-menu3-text-transform'] = $theme_options['above-header-text-transform'];
		}

		if ( isset( $theme_options['above-header-menu-spacing'] ) ) {
			$theme_options['header-menu3-menu-spacing'] = $theme_options['above-header-menu-spacing'];
		}

		// Menu 3 - Submenu.
		if ( isset( $theme_options['above-header-submenu-text-color-responsive'] ) ) {
			$theme_options['header-menu3-submenu-color-responsive'] = $theme_options['above-header-submenu-text-color-responsive'];
		}
		if ( isset( $theme_options['above-header-submenu-bg-color-responsive'] ) ) {
			$theme_options['header-menu3-submenu-bg-color-responsive'] = $theme_options['above-header-submenu-bg-color-responsive'];
		}

		if ( isset( $theme_options['above-header-submenu-hover-color-responsive'] ) ) {
			$theme_options['header-menu3-submenu-h-color-responsive'] = $theme_options['above-header-submenu-hover-color-responsive'];
		}
		if ( isset( $theme_options['above-header-submenu-bg-hover-color-responsive'] ) ) {
			$theme_options['header-menu3-submenu-h-bg-color-responsive'] = $theme_options['above-header-submenu-bg-hover-color-responsive'];
		}

		if ( isset( $theme_options['above-header-submenu-active-color-responsive'] ) ) {
			$theme_options['header-menu3-submenu-a-color-responsive'] = $theme_options['above-header-submenu-active-color-responsive'];
		}
		if ( isset( $theme_options['above-header-submenu-active-bg-color-responsive'] ) ) {
			$theme_options['header-menu3-submenu-a-bg-color-responsive'] = $theme_options['above-header-submenu-active-bg-color-responsive'];
		}

		if ( isset( $theme_options['font-size-above-header-dropdown-menu'] ) ) {
			$theme_options['header-font-size-menu3-sub-menu'] = $theme_options['font-size-above-header-dropdown-menu'];
		}
		if ( isset( $theme_options['font-weight-above-header-dropdown-menu'] ) ) {
			$theme_options['header-font-weight-menu3-sub-menu'] = $theme_options['font-weight-above-header-dropdown-menu'];
		}
		if ( isset( $theme_options['line-height-above-header-dropdown-menu'] ) ) {
			$theme_options['header-line-height-menu3-sub-menu'] = $theme_options['line-height-above-header-dropdown-menu'];
		}
		if ( isset( $theme_options['font-family-above-header-dropdown-menu'] ) ) {
			$theme_options['header-font-family-menu3-sub-menu'] = $theme_options['font-family-above-header-dropdown-menu'];
		}
		if ( isset( $theme_options['text-transform-above-header-dropdown-menu'] ) ) {
			$theme_options['header-text-transform-menu3-sub-menu'] = $theme_options['text-transform-above-header-dropdown-menu'];
		}

		if ( isset( $theme_options['above-header-submenu-spacing'] ) ) {
			$theme_options['header-menu3-submenu-spacing'] = $theme_options['above-header-submenu-spacing'];
		}
	}

	if ( 'search' === $above_header_section_1 || 'search' === $above_header_section_2 ) {
		if ( isset( $theme_options['above-header-text-color-responsive'] ) ) {
			$theme_options['header-search-icon-color'] = $theme_options['above-header-text-color-responsive'];
		}
	}

	if ( 'text-html' === $above_header_section_1 || 'text-html' === $above_header_section_2 ) {
		if ( isset( $theme_options['above-header-text-color-responsive'] ) ) {
			$theme_options['header-html-3color'] = $theme_options['above-header-text-color-responsive'];
		}
		if ( isset( $theme_options['above-header-link-color-responsive'] ) ) {
			$theme_options['header-html-3link-color'] = $theme_options['above-header-link-color-responsive'];
		}
		if ( isset( $theme_options['above-header-link-hover-color-responsive'] ) ) {
			$theme_options['header-html-3link-h-color'] = $theme_options['above-header-link-hover-color-responsive'];
		}
		if ( isset( $theme_options['font-size-above-header-content'] ) ) {
			$theme_options['font-size-section-hb-html-3'] = $theme_options['font-size-above-header-content'];
		}
		if ( isset( $theme_options['font-weight-above-header-content'] ) ) {
			$theme_options['font-weight-section-hb-html-3'] = $theme_options['font-weight-above-header-content'];
		}
		if ( isset( $theme_options['line-height-above-header-content'] ) ) {
			$theme_options['line-height-section-hb-html-3'] = $theme_options['line-height-above-header-content'];
		}
		if ( isset( $theme_options['font-family-above-header-content'] ) ) {
			$theme_options['font-family-section-hb-html-3'] = $theme_options['font-family-above-header-content'];
		}
		if ( isset( $theme_options['text-transform-above-header-content'] ) ) {
			$theme_options['text-transform-section-hb-html-3'] = $theme_options['text-transform-above-header-content'];
		}
	}

	if ( 'widget' === $above_header_section_1 || 'widget' === $above_header_section_2 ) {
		if ( isset( $theme_options['above-header-text-color-responsive'] ) ) {
			$theme_options['header-widget-3-color']       = $theme_options['above-header-text-color-responsive'];
			$theme_options['header-widget-3-title-color'] = $theme_options['above-header-text-color-responsive'];
		}
		if ( isset( $theme_options['above-header-link-color-responsive'] ) ) {
			$theme_options['header-widget-3-link-color'] = $theme_options['above-header-link-color-responsive'];
		}
		if ( isset( $theme_options['above-header-link-hover-color-responsive'] ) ) {
			$theme_options['header-widget-3-link-h-color'] = $theme_options['above-header-link-hover-color-responsive'];
		}
		if ( isset( $theme_options['font-size-above-header-content'] ) ) {
			$theme_options['header-widget-3-content-font-size'] = $theme_options['font-size-above-header-content'];
		}
		if ( isset( $theme_options['font-weight-above-header-content'] ) ) {
			$theme_options['header-widget-3-content-font-weight'] = $theme_options['font-weight-above-header-content'];
		}
		if ( isset( $theme_options['line-height-above-header-content'] ) ) {
			$theme_options['header-widget-3-content-line-height'] = $theme_options['line-height-above-header-content'];
		}
		if ( isset( $theme_options['font-family-above-header-content'] ) ) {
			$theme_options['header-widget-3-content-font-family'] = $theme_options['font-family-above-header-content'];
		}
		if ( isset( $theme_options['text-transform-above-header-content'] ) ) {
			$theme_options['header-widget-3-content-text-transform'] = $theme_options['text-transform-above-header-content'];
		}
	}

	switch ( $above_header_layout ) {

		case 'above-header-layout-1':
			$theme_options['header-desktop-items']['above'] = array(
				'above_left'         => ( '' !== $new_above_header_section_1_item ) ? array( $new_above_header_section_1_item ) : array(),
				'above_left_center'  => array(),
				'above_center'       => array(),
				'above_right_center' => array(),
				'above_right'        => ( '' !== $new_above_header_section_2_item ) ? array( $new_above_header_section_2_item ) : array(),
			);
			break;

		case 'above-header-layout-2':
			$theme_options['header-desktop-items']['above'] = array(
				'above_left'         => array(),
				'above_left_center'  => array(),
				'above_center'       => ( '' !== $new_above_header_section_1_item ) ? array( $new_above_header_section_1_item ) : array(),
				'above_right_center' => array(),
				'above_right'        => array(),
			);
			break;
	}

	if ( $above_header_on_mobile ) {

		if ( $above_header_swap_mobile && ( 'menu' === $above_header_section_1 || 'menu' === $above_header_section_2 ) ) {
			$temp                            = $new_above_header_section_1_item;
			$new_above_header_section_1_item = $new_above_header_section_2_item;
			$new_above_header_section_2_item = $temp;
		}

		if ( $above_header_merge_menu && ( 'menu' === $above_header_section_1 || 'menu' === $above_header_section_2 ) ) {
			if ( '' !== $new_above_header_section_1_item ) {
				$theme_options['header-mobile-items']['popup']['popup_content'][] = $new_above_header_section_1_item;
			}
			if ( '' !== $new_above_header_section_2_item ) {
				$theme_options['header-mobile-items']['popup']['popup_content'][] = $new_above_header_section_2_item;
			}
			$theme_options['header-menu3-menu-stack-on-mobile'] = true;
			$theme_options['header-mobile-items']['above']      = array(
				'above_left'   => array(),
				'above_center' => array(),
				'above_right'  => array(),
			);
		} else {
			switch ( $above_header_layout ) {

				case 'above-header-layout-1':
					$theme_options['header-mobile-items']['above'] = array(
						'above_left'   => ( '' !== $new_above_header_section_1_item ) ? array( $new_above_header_section_1_item ) : array(),
						'above_center' => array(),
						'above_right'  => ( '' !== $new_above_header_section_2_item ) ? array( $new_above_header_section_2_item ) : array(),
					);
					break;

				case 'above-header-layout-2':
					$theme_options['header-mobile-items']['above'] = array(
						'above_left'   => array(),
						'above_center' => ( '' !== $new_above_header_section_1_item ) ? array( $new_above_header_section_1_item ) : array(),
						'above_right'  => array(),
					);
					break;
			}
		}
	}

	return array(
		'theme_options'  => $theme_options,
		'used_elements'  => $used_elements,
		'widget_options' => $widget_options,
	);

}

/**
 * Header Footer builder - Migration of Below Header.
 *
 * @since 3.0.0
 * @param array $theme_options Theme options.
 * @param array $used_elements Used Elements array.
 * @param array $widget_options Widget options.
 * @return array
 */
function astra_below_header_builder_migration( $theme_options, $used_elements, $widget_options ) {
	/**
	 * Below Header
	 */

	$below_header_layout      = ( isset( $theme_options['below-header-layout'] ) ) ? $theme_options['below-header-layout'] : '';
	$below_header_on_mobile   = ( isset( $theme_options['below-header-on-mobile'] ) ) ? $theme_options['below-header-on-mobile'] : '';
	$below_header_merge_menu  = ( isset( $theme_options['below-header-merge-menu'] ) ) ? $theme_options['below-header-merge-menu'] : '';
	$below_header_swap_mobile = ( isset( $theme_options['below-header-swap-mobile'] ) ) ? $theme_options['below-header-swap-mobile'] : '';

	if ( isset( $theme_options['below-header-height'] ) ) {
		$theme_options['hbb-header-height'] = array(
			'desktop' => $theme_options['below-header-height'],
			'tablet'  => '',
			'mobile'  => '',
		);
	}

	if ( isset( $theme_options['below-header-divider'] ) ) {
		$theme_options['hbb-header-separator'] = $theme_options['below-header-divider'];
	}
	if ( isset( $theme_options['below-header-divider-color'] ) ) {
		$theme_options['hbb-header-bottom-border-color'] = $theme_options['below-header-divider-color'];
	}
	if ( isset( $theme_options['below-header-bg-obj-responsive'] ) ) {
		$theme_options['hbb-header-bg-obj-responsive'] = $theme_options['below-header-bg-obj-responsive'];
	}
	if ( isset( $theme_options['below-header-spacing'] ) ) {
		$theme_options['section-below-header-builder-padding'] = $theme_options['below-header-spacing'];
	}
	// Below Header Section 1.
	$below_header_section_1          = ( isset( $theme_options['below-header-section-1'] ) ) ? $theme_options['below-header-section-1'] : '';
	$new_below_header_section_1_item = '';
	switch ( $below_header_section_1 ) {
		case 'menu':
			$new_below_header_section_1_item = 'menu-2';
			break;

		case 'search':
			if ( ! in_array( 'search', $used_elements ) ) {
				$new_below_header_section_1_item = 'search';
				if ( isset( $theme_options['below-header-section-1-search-box-type'] ) ) {
					$theme_options['header-search-box-type'] = $theme_options['below-header-section-1-search-box-type'];
				}
			}
			break;

		case 'text-html':
			if ( ! in_array( 'html-2', $used_elements ) ) {
				$new_below_header_section_1_item = 'html-2';
				if ( isset( $theme_options['below-header-section-1-html'] ) ) {
					$theme_options['header-html-2'] = $theme_options['below-header-section-1-html'];
				}
			}

			break;

		case 'widget':
			if ( ! in_array( 'widget-2', $used_elements ) ) {
				$new_below_header_section_1_item = 'widget-2';
				if ( isset( $widget_options['below-header-widget-1'] ) ) {
					$widget_options['header-widget-2'] = $widget_options['below-header-widget-1'];
				}
			}
			break;

		case 'woocommerce':
			if ( ! in_array( 'woo-cart', $used_elements ) ) {
				$new_below_header_section_1_item = 'woo-cart';
			}
			break;

		case 'edd':
			if ( ! in_array( 'edd-cart', $used_elements ) ) {
				$new_below_header_section_1_item = 'edd-cart';
			}
			break;
	}

	// Below Header Section 2.
	$below_header_section_2          = ( isset( $theme_options['below-header-section-2'] ) ) ? $theme_options['below-header-section-2'] : '';
	$new_below_header_section_2_item = '';
	switch ( $below_header_section_2 ) {
		case 'menu':
			$new_below_header_section_2_item = 'menu-2';
			break;

		case 'search':
			if ( ! in_array( 'search', $used_elements ) ) {
				$new_below_header_section_2_item = 'search';
				if ( isset( $theme_options['below-header-section-2-search-box-type'] ) ) {
					$theme_options['header-search-box-type'] = $theme_options['below-header-section-2-search-box-type'];
				}
			}
			break;

		case 'text-html':
			if ( ! in_array( 'html-2', $used_elements ) ) {
				$new_below_header_section_2_item = 'html-2';
				if ( isset( $theme_options['below-header-section-2-html'] ) ) {
					$theme_options['header-html-2'] = $theme_options['below-header-section-2-html'];
				}
			}
			break;

		case 'widget':
			if ( ! in_array( 'widget-2', $used_elements ) ) {
				$new_below_header_section_2_item = 'widget-2';
				if ( isset( $widget_options['below-header-widget-2'] ) ) {
					$widget_options['header-widget-2'] = $widget_options['below-header-widget-2'];
				}
			}
			break;

		case 'woocommerce':
			if ( ! in_array( 'woo-cart', $used_elements ) ) {
				$new_below_header_section_2_item = 'woo-cart';
			}
			break;

		case 'edd':
			if ( ! in_array( 'edd-cart', $used_elements ) ) {
				$new_below_header_section_2_item = 'edd-cart';
			}
			break;
	}

	if ( 'menu' === $below_header_section_1 || 'menu' === $below_header_section_2 ) {
		$theme_options['header-menu2-menu-stack-on-mobile'] = false;
		/**
		 * Menu - 2
		 */
		if ( isset( $theme_options['below-header-submenu-container-animation'] ) ) {
			$theme_options['header-menu2-submenu-container-animation'] = $theme_options['below-header-submenu-container-animation'];
		}
		if ( isset( $theme_options['below-header-submenu-border'] ) ) {
			$theme_options['header-menu2-submenu-border'] = $theme_options['below-header-submenu-border'];
		}
		if ( isset( $theme_options['below-header-submenu-b-color'] ) ) {
			$theme_options['header-menu2-submenu-b-color'] = $theme_options['below-header-submenu-b-color'];
		}
		if ( isset( $theme_options['below-header-submenu-item-border'] ) ) {
			$theme_options['header-menu2-submenu-item-border'] = $theme_options['below-header-submenu-item-border'];
		}
		if ( isset( $theme_options['below-header-submenu-item-b-color'] ) ) {
			$theme_options['header-menu2-submenu-item-b-color'] = $theme_options['below-header-submenu-item-b-color'];
		}

		if ( isset( $theme_options['below-header-menu-text-color-responsive'] ) ) {
			$theme_options['header-menu2-color-responsive'] = $theme_options['below-header-menu-text-color-responsive'];
		}
		if ( isset( $theme_options['below-header-menu-bg-obj-responsive'] ) ) {
			$theme_options['header-menu2-bg-obj-responsive'] = $theme_options['below-header-menu-bg-obj-responsive'];
		}

		if ( isset( $theme_options['below-header-menu-text-hover-color-responsive'] ) ) {
			$theme_options['header-menu2-h-color-responsive'] = $theme_options['below-header-menu-text-hover-color-responsive'];
		}
		if ( isset( $theme_options['below-header-menu-bg-hover-color-responsive'] ) ) {
			$theme_options['header-menu2-h-bg-color-responsive'] = $theme_options['below-header-menu-bg-hover-color-responsive'];
		}

		if ( isset( $theme_options['below-header-current-menu-text-color-responsive'] ) ) {
			$theme_options['header-menu2-a-color-responsive'] = $theme_options['below-header-current-menu-text-color-responsive'];
		}
		if ( isset( $theme_options['below-header-current-menu-bg-color-responsive'] ) ) {
			$theme_options['header-menu2-a-bg-color-responsive'] = $theme_options['below-header-current-menu-bg-color-responsive'];
		}

		if ( isset( $theme_options['below-header-font-size'] ) ) {
			$theme_options['header-menu2-font-size'] = $theme_options['below-header-font-size'];
		}
		if ( isset( $theme_options['below-header-font-weight'] ) ) {
			$theme_options['header-menu2-font-weight'] = $theme_options['below-header-font-weight'];
		}
		if ( isset( $theme_options['below-header-line-height'] ) ) {
			$theme_options['header-menu2-line-height'] = $theme_options['below-header-line-height'];
		}
		if ( isset( $theme_options['below-header-font-family'] ) ) {
			$theme_options['header-menu2-font-family'] = $theme_options['below-header-font-family'];
		}
		if ( isset( $theme_options['below-header-text-transform'] ) ) {
			$theme_options['header-menu2-text-transform'] = $theme_options['below-header-text-transform'];
		}

		if ( isset( $theme_options['below-header-menu-spacing'] ) ) {
			$theme_options['header-menu2-menu-spacing'] = $theme_options['below-header-menu-spacing'];
		}

		// Menu 2 - Submenu.
		if ( isset( $theme_options['below-header-submenu-text-color-responsive'] ) ) {
			$theme_options['header-menu2-submenu-color-responsive'] = $theme_options['below-header-submenu-text-color-responsive'];
		}
		if ( isset( $theme_options['below-header-submenu-bg-color-responsive'] ) ) {
			$theme_options['header-menu2-submenu-bg-color-responsive'] = $theme_options['below-header-submenu-bg-color-responsive'];
		}

		if ( isset( $theme_options['below-header-submenu-hover-color-responsive'] ) ) {
			$theme_options['header-menu2-submenu-h-color-responsive'] = $theme_options['below-header-submenu-hover-color-responsive'];
		}
		if ( isset( $theme_options['below-header-submenu-bg-hover-color-responsive'] ) ) {
			$theme_options['header-menu2-submenu-h-bg-color-responsive'] = $theme_options['below-header-submenu-bg-hover-color-responsive'];
		}

		if ( isset( $theme_options['below-header-submenu-active-color-responsive'] ) ) {
			$theme_options['header-menu2-submenu-a-color-responsive'] = $theme_options['below-header-submenu-active-color-responsive'];
		}
		if ( isset( $theme_options['below-header-submenu-active-bg-color-responsive'] ) ) {
			$theme_options['header-menu2-submenu-a-bg-color-responsive'] = $theme_options['below-header-submenu-active-bg-color-responsive'];
		}

		if ( isset( $theme_options['font-size-below-header-dropdown-menu'] ) ) {
			$theme_options['header-font-size-menu2-sub-menu'] = $theme_options['font-size-below-header-dropdown-menu'];
		}
		if ( isset( $theme_options['font-weight-below-header-dropdown-menu'] ) ) {
			$theme_options['header-font-weight-menu2-sub-menu'] = $theme_options['font-weight-below-header-dropdown-menu'];
		}
		if ( isset( $theme_options['line-height-below-header-dropdown-menu'] ) ) {
			$theme_options['header-line-height-menu2-sub-menu'] = $theme_options['line-height-below-header-dropdown-menu'];
		}
		if ( isset( $theme_options['font-family-below-header-dropdown-menu'] ) ) {
			$theme_options['header-font-family-menu2-sub-menu'] = $theme_options['font-family-below-header-dropdown-menu'];
		}
		if ( isset( $theme_options['text-transform-below-header-dropdown-menu'] ) ) {
			$theme_options['header-text-transform-menu2-sub-menu'] = $theme_options['text-transform-below-header-dropdown-menu'];
		}

		if ( isset( $theme_options['below-header-submenu-spacing'] ) ) {
			$theme_options['header-menu2-submenu-spacing'] = $theme_options['below-header-submenu-spacing'];
		}
	}

	if ( 'search' === $below_header_section_1 || 'search' === $below_header_section_2 ) {
		if ( isset( $theme_options['below-header-text-color-responsive'] ) ) {
			$theme_options['header-search-icon-color'] = $theme_options['below-header-text-color-responsive'];
		}
	}

	if ( 'text-html' === $below_header_section_1 || 'text-html' === $below_header_section_2 ) {
		if ( isset( $theme_options['below-header-text-color-responsive'] ) ) {
			$theme_options['header-html-2color'] = $theme_options['below-header-text-color-responsive'];
		}
		if ( isset( $theme_options['below-header-link-color-responsive'] ) ) {
			$theme_options['header-html-2link-color'] = $theme_options['below-header-link-color-responsive'];
		}
		if ( isset( $theme_options['below-header-link-hover-color-responsive'] ) ) {
			$theme_options['header-html-2link-h-color'] = $theme_options['below-header-link-hover-color-responsive'];
		}
		if ( isset( $theme_options['font-size-below-header-content'] ) ) {
			$theme_options['font-size-section-hb-html-2'] = $theme_options['font-size-below-header-content'];
		}
		if ( isset( $theme_options['font-weight-below-header-content'] ) ) {
			$theme_options['font-weight-section-hb-html-2'] = $theme_options['font-weight-below-header-content'];
		}
		if ( isset( $theme_options['line-height-below-header-content'] ) ) {
			$theme_options['line-height-section-hb-html-2'] = $theme_options['line-height-below-header-content'];
		}
		if ( isset( $theme_options['font-family-below-header-content'] ) ) {
			$theme_options['font-family-section-hb-html-2'] = $theme_options['font-family-below-header-content'];
		}
		if ( isset( $theme_options['text-transform-below-header-content'] ) ) {
			$theme_options['text-transform-section-hb-html-2'] = $theme_options['text-transform-below-header-content'];
		}
	}

	if ( 'widget' === $below_header_section_1 || 'widget' === $below_header_section_2 ) {
		if ( isset( $theme_options['below-header-text-color-responsive'] ) ) {
			$theme_options['header-widget-2-color']       = $theme_options['below-header-text-color-responsive'];
			$theme_options['header-widget-2-title-color'] = $theme_options['below-header-text-color-responsive'];
		}
		if ( isset( $theme_options['below-header-link-color-responsive'] ) ) {
			$theme_options['header-widget-2-link-color'] = $theme_options['below-header-link-color-responsive'];
		}
		if ( isset( $theme_options['below-header-link-hover-color-responsive'] ) ) {
			$theme_options['header-widget-2-link-h-color'] = $theme_options['below-header-link-hover-color-responsive'];
		}
		if ( isset( $theme_options['font-size-below-header-content'] ) ) {
			$theme_options['header-widget-2-content-font-size'] = $theme_options['font-size-below-header-content'];
		}
		if ( isset( $theme_options['font-weight-below-header-content'] ) ) {
			$theme_options['header-widget-2-content-font-weight'] = $theme_options['font-weight-below-header-content'];
		}
		if ( isset( $theme_options['line-height-below-header-content'] ) ) {
			$theme_options['header-widget-2-content-line-height'] = $theme_options['line-height-below-header-content'];
		}
		if ( isset( $theme_options['font-family-below-header-content'] ) ) {
			$theme_options['header-widget-2-content-font-family'] = $theme_options['font-family-below-header-content'];
		}
		if ( isset( $theme_options['text-transform-below-header-content'] ) ) {
			$theme_options['header-widget-2-content-text-transform'] = $theme_options['text-transform-below-header-content'];
		}
	}

	switch ( $below_header_layout ) {

		case 'below-header-layout-1':
			$theme_options['header-desktop-items']['below'] = array(
				'below_left'         => ( '' !== $new_below_header_section_1_item ) ? array( $new_below_header_section_1_item ) : array(),
				'below_left_center'  => array(),
				'below_center'       => array(),
				'below_right_center' => array(),
				'below_right'        => ( '' !== $new_below_header_section_2_item ) ? array( $new_below_header_section_2_item ) : array(),
			);
			break;

		case 'below-header-layout-2':
			$theme_options['header-desktop-items']['below'] = array(
				'below_left'         => array(),
				'below_left_center'  => array(),
				'below_center'       => ( '' !== $new_below_header_section_1_item ) ? array( $new_below_header_section_1_item ) : array(),
				'below_right_center' => array(),
				'below_right'        => array(),
			);
			break;
	}

	if ( $below_header_on_mobile ) {

		if ( $below_header_swap_mobile && ( 'menu' === $below_header_section_1 || 'menu' === $below_header_section_2 ) ) {
			$temp                            = $new_below_header_section_1_item;
			$new_below_header_section_1_item = $new_below_header_section_2_item;
			$new_below_header_section_2_item = $temp;
		}

		if ( $below_header_merge_menu && ( 'menu' === $below_header_section_1 || 'menu' === $below_header_section_2 ) ) {
			if ( '' !== $new_below_header_section_1_item ) {
				$theme_options['header-mobile-items']['popup']['popup_content'][] = $new_below_header_section_1_item;
			}
			if ( '' !== $new_below_header_section_2_item ) {
				$theme_options['header-mobile-items']['popup']['popup_content'][] = $new_below_header_section_2_item;
			}
			$theme_options['header-menu2-menu-stack-on-mobile'] = true;
			$theme_options['header-mobile-items']['below']      = array(
				'below_left'   => array(),
				'below_center' => array(),
				'below_right'  => array(),
			);
		} else {
			switch ( $below_header_layout ) {

				case 'below-header-layout-1':
					$theme_options['header-mobile-items']['below'] = array(
						'below_left'   => ( '' !== $new_below_header_section_1_item ) ? array( $new_below_header_section_1_item ) : array(),
						'below_center' => array(),
						'below_right'  => ( '' !== $new_below_header_section_2_item ) ? array( $new_below_header_section_2_item ) : array(),
					);
					break;

				case 'below-header-layout-2':
					$theme_options['header-mobile-items']['below'] = array(
						'below_left'   => array(),
						'below_center' => ( '' !== $new_below_header_section_1_item ) ? array( $new_below_header_section_1_item ) : array(),
						'below_right'  => array(),
					);
					break;
			}
		}
	}

	return array(
		'theme_options'  => $theme_options,
		'used_elements'  => $used_elements,
		'widget_options' => $widget_options,
	);
}

/**
 * Header Footer builder - Migration of Footer.
 *
 * @since 3.0.0
 * @param array $theme_options Theme options.
 * @param array $used_elements Used Elements array.
 * @param array $widget_options Widget options.
 * @return array
 */
function astra_footer_builder_migration( $theme_options, $used_elements, $widget_options ) {
	/**
	 * Footer
	 */
	$footer_layout = ( isset( $theme_options['footer-sml-layout'] ) ) ? $theme_options['footer-sml-layout'] : '';

	if ( isset( $theme_options['footer-layout-width'] ) ) {
		$theme_options['hb-footer-layout-width'] = $theme_options['footer-layout-width'];
	}
	if ( isset( $theme_options['footer-sml-divider'] ) ) {
		$theme_options['hbb-footer-separator'] = $theme_options['footer-sml-divider'];
	}
	if ( isset( $theme_options['footer-sml-divider-color'] ) ) {
		$theme_options['hbb-footer-top-border-color'] = $theme_options['footer-sml-divider-color'];
	}
	if ( isset( $theme_options['footer-bg-obj'] ) ) {
		$theme_options['hbb-footer-bg-obj-responsive'] = array(
			'desktop' => $theme_options['footer-bg-obj'],
			'tablet'  => '',
			'mobile'  => '',
		);
	}
	if ( isset( $theme_options['footer-sml-spacing'] ) ) {
		$theme_options['section-below-footer-builder-padding'] = $theme_options['footer-sml-spacing'];
	}

	// Footer Section 1.
	$footer_section_1   = ( isset( $theme_options['footer-sml-section-1'] ) ) ? $theme_options['footer-sml-section-1'] : '';
	$new_section_1_item = '';
	$used_elements[]    = $new_section_1_item;

	$footer_section_2   = ( isset( $theme_options['footer-sml-section-2'] ) ) ? $theme_options['footer-sml-section-2'] : '';
	$new_section_2_item = '';
	$used_elements[]    = $new_section_2_item;

	switch ( $footer_section_1 ) {
		case 'custom':
			$new_section_1_item                          = 'copyright';
			$theme_options['footer-copyright-alignment'] = array(
				'desktop' => ( 'footer-sml-layout-1' === $footer_layout ) ? 'center' : 'left',
				'tablet'  => ( 'footer-sml-layout-1' === $footer_layout ) ? 'center' : 'left',
				'mobile'  => 'center',
			);
			break;

		case 'widget':
			$new_section_1_item                         = 'widget-1';
			$theme_options['footer-widget-alignment-1'] = array(
				'desktop' => ( 'footer-sml-layout-1' === $footer_layout ) ? 'center' : 'left',
				'tablet'  => ( 'footer-sml-layout-1' === $footer_layout ) ? 'center' : 'left',
				'mobile'  => 'center',
			);
			if ( isset( $theme_options['footer-color'] ) ) {
				$theme_options['footer-widget-1-color'] = array(
					'desktop' => $theme_options['footer-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			if ( isset( $theme_options['footer-link-color'] ) ) {
				$theme_options['footer-widget-1-link-color'] = array(
					'desktop' => $theme_options['footer-link-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			if ( isset( $theme_options['footer-link-h-color'] ) ) {
				$theme_options['footer-widget-1-link-h-color'] = array(
					'desktop' => $theme_options['footer-link-h-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}

			if ( isset( $theme_options['font-size-footer-content'] ) ) {
				$theme_options['footer-widget-1-content-font-size'] = $theme_options['font-size-footer-content'];
			}

			if ( isset( $theme_options['font-weight-footer-content'] ) ) {
				$theme_options['footer-widget-1-content-font-weight'] = $theme_options['font-weight-footer-content'];
			}

			if ( isset( $theme_options['line-height-footer-content'] ) ) {
				$theme_options['footer-widget-1-content-line-height'] = $theme_options['line-height-footer-content'];
			}

			if ( isset( $theme_options['font-family-footer-content'] ) ) {
				$theme_options['footer-widget-1-content-font-family'] = $theme_options['font-family-footer-content'];
			}

			if ( isset( $theme_options['text-transform-footer-content'] ) ) {
				$theme_options['footer-widget-1-content-text-transform'] = $theme_options['text-transform-footer-content'];
			}


			break;

		case 'menu':
			$theme_options['footer-menu-alignment'] = array(
				'desktop' => ( 'footer-sml-layout-1' === $footer_layout ) ? 'center' : 'flex-start',
				'tablet'  => ( 'footer-sml-layout-1' === $footer_layout ) ? 'center' : 'flex-start',
				'mobile'  => 'center',
			);
			$new_section_1_item                     = 'menu';
			break;
	}

	// Footer Section 2.
	switch ( $footer_section_2 ) {
		case 'custom':
			$new_section_2_item = ( 'copyright' !== $new_section_1_item ) ? 'copyright' : 'html-1';
			if ( 'copyright' !== $new_section_1_item ) {
				$theme_options['footer-copyright-alignment'] = array(
					'desktop' => ( 'footer-sml-layout-1' === $footer_layout ) ? 'center' : 'right',
					'tablet'  => ( 'footer-sml-layout-1' === $footer_layout ) ? 'center' : 'right',
					'mobile'  => 'center',
				);
				if ( isset( $theme_options['footer-sml-section-2-credit'] ) ) {
					$theme_options['footer-copyright-editor'] = $theme_options['footer-sml-section-2-credit'];
				}
			} else {
				$theme_options['footer-html-1-alignment'] = array(
					'desktop' => ( 'footer-sml-layout-1' === $footer_layout ) ? 'center' : 'right',
					'tablet'  => ( 'footer-sml-layout-1' === $footer_layout ) ? 'center' : 'right',
					'mobile'  => 'center',
				);
				if ( isset( $theme_options['footer-sml-section-2-credit'] ) ) {
					$theme_options['footer-html-1'] = $theme_options['footer-sml-section-2-credit'];
				}
			}

			break;

		case 'widget':
			$new_section_2_item                         = 'widget-2';
			$theme_options['footer-widget-alignment-2'] = array(
				'desktop' => ( 'footer-sml-layout-1' === $footer_layout ) ? 'center' : 'right',
				'tablet'  => ( 'footer-sml-layout-1' === $footer_layout ) ? 'center' : 'right',
				'mobile'  => 'center',
			);
			if ( isset( $theme_options['footer-color'] ) ) {
				$theme_options['footer-widget-2-color'] = array(
					'desktop' => $theme_options['footer-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			if ( isset( $theme_options['footer-link-color'] ) ) {
				$theme_options['footer-widget-2-link-color'] = array(
					'desktop' => $theme_options['footer-link-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			if ( isset( $theme_options['footer-link-h-color'] ) ) {
				$theme_options['footer-widget-2-link-h-color'] = array(
					'desktop' => $theme_options['footer-link-h-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}

			if ( isset( $theme_options['font-size-footer-content'] ) ) {
				$theme_options['footer-widget-2-content-font-size'] = $theme_options['font-size-footer-content'];
			}

			if ( isset( $theme_options['font-weight-footer-content'] ) ) {
				$theme_options['footer-widget-2-content-font-weight'] = $theme_options['font-weight-footer-content'];
			}

			if ( isset( $theme_options['line-height-footer-content'] ) ) {
				$theme_options['footer-widget-2-content-line-height'] = $theme_options['line-height-footer-content'];
			}

			if ( isset( $theme_options['font-family-footer-content'] ) ) {
				$theme_options['footer-widget-2-content-font-family'] = $theme_options['font-family-footer-content'];
			}

			if ( isset( $theme_options['text-transform-footer-content'] ) ) {
				$theme_options['footer-widget-2-content-text-transform'] = $theme_options['text-transform-footer-content'];
			}


			break;

		case 'menu':
			$new_section_2_item                     = 'menu';
			$theme_options['footer-menu-alignment'] = array(
				'desktop' => ( 'footer-sml-layout-1' === $footer_layout ) ? 'center' : 'flex-end',
				'tablet'  => ( 'footer-sml-layout-1' === $footer_layout ) ? 'center' : 'flex-end',
				'mobile'  => 'center',
			);
			break;
	}

	if ( 'custom' === $footer_section_1 || 'custom' === $footer_section_2 ) {

		// Footer Content Color migrated to Copyright.
		if ( isset( $theme_options['footer-sml-section-1-credit'] ) ) {
			$theme_options['footer-copyright-editor'] = $theme_options['footer-sml-section-1-credit'];
		}
		if ( isset( $theme_options['footer-color'] ) ) {
			$theme_options['footer-copyright-color'] = $theme_options['footer-color'];
		}
		if ( isset( $theme_options['footer-link-color'] ) ) {
			$theme_options['footer-copyright-link-color'] = $theme_options['footer-link-color'];
		}
		if ( isset( $theme_options['footer-link-h-color'] ) ) {
			$theme_options['footer-copyright-link-h-color'] = $theme_options['footer-link-h-color'];
		}

		if ( isset( $theme_options['font-size-footer-content'] ) ) {
			$theme_options['font-size-section-footer-copyright'] = $theme_options['font-size-footer-content'];
		}

		if ( isset( $theme_options['font-weight-footer-content'] ) ) {
			$theme_options['font-weight-section-footer-copyright'] = $theme_options['font-weight-footer-content'];
		}

		if ( isset( $theme_options['line-height-footer-content'] ) ) {
			$theme_options['line-height-section-footer-copyright'] = $theme_options['line-height-footer-content'];
		}

		if ( isset( $theme_options['font-family-footer-content'] ) ) {
			$theme_options['font-family-section-footer-copyright'] = $theme_options['font-family-footer-content'];
		}

		if ( isset( $theme_options['text-transform-footer-content'] ) ) {
			$theme_options['text-transform-section-footer-copyright'] = $theme_options['text-transform-footer-content'];
		}

		if ( 'html-1' === $new_section_2_item ) {
			// Footer Content Color migrated to HTML 1.
			if ( isset( $theme_options['footer-color'] ) ) {
				$theme_options['footer-html-1-color'] = array(
					'desktop' => $theme_options['footer-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			if ( isset( $theme_options['footer-link-color'] ) ) {
				$theme_options['footer-html-1-link-color'] = array(
					'desktop' => $theme_options['footer-link-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}
			if ( isset( $theme_options['footer-link-h-color'] ) ) {
				$theme_options['footer-html-1-link-h-color'] = array(
					'desktop' => $theme_options['footer-link-h-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}

			if ( isset( $theme_options['font-size-footer-content'] ) ) {
				$theme_options['font-size-section-fb-html-1'] = $theme_options['font-size-footer-content'];
			}

			if ( isset( $theme_options['font-weight-footer-content'] ) ) {
				$theme_options['font-weight-section-fb-html-1'] = $theme_options['font-weight-footer-content'];
			}

			if ( isset( $theme_options['line-height-footer-content'] ) ) {
				$theme_options['line-height-section-fb-html-1'] = $theme_options['line-height-footer-content'];
			}

			if ( isset( $theme_options['font-family-footer-content'] ) ) {
				$theme_options['font-family-section-fb-html-1'] = $theme_options['font-family-footer-content'];
			}

			if ( isset( $theme_options['text-transform-footer-content'] ) ) {
				$theme_options['text-transform-section-fb-html-1'] = $theme_options['text-transform-footer-content'];
			}
		}
	}

	if ( 'menu' === $footer_section_1 || 'menu' === $footer_section_2 ) {
		if ( isset( $theme_options['footer-link-color'] ) ) {
			$theme_options['footer-menu-color-responsive'] = array(
				'desktop' => $theme_options['footer-link-color'],
				'tablet'  => '',
				'mobile'  => '',
			);
		}
		if ( isset( $theme_options['footer-link-h-color'] ) ) {
			$theme_options['footer-menu-h-color-responsive'] = array(
				'desktop' => $theme_options['footer-link-h-color'],
				'tablet'  => '',
				'mobile'  => '',
			);
		}

		$theme_options['footer-menu-layout'] = array(
			'desktop' => 'horizontal',
			'tablet'  => 'horizontal',
			'mobile'  => 'horizontal',
		);

		if ( isset( $theme_options['font-size-footer-content'] ) ) {
			$theme_options['footer-menu-font-size'] = $theme_options['font-size-footer-content'];
		}

		if ( isset( $theme_options['font-weight-footer-content'] ) ) {
			$theme_options['footer-menu-font-weight'] = $theme_options['font-weight-footer-content'];
		}

		if ( isset( $theme_options['line-height-footer-content'] ) ) {
			$theme_options['footer-menu-line-height'] = $theme_options['line-height-footer-content'];
		}

		if ( isset( $theme_options['font-family-footer-content'] ) ) {
			$theme_options['footer-menu-font-family'] = $theme_options['font-family-footer-content'];
		}

		if ( isset( $theme_options['text-transform-footer-content'] ) ) {
			$theme_options['footer-menu-text-transform'] = $theme_options['text-transform-footer-content'];
		}

		if ( isset( $theme_options['footer-menu-spacing'] ) ) {
			$theme_options['footer-main-menu-spacing'] = $theme_options['footer-menu-spacing'];
		}
	}

	if ( '' !== $footer_layout ) {

		$theme_options['footer-desktop-items'] = array(
			'above'   =>
				array(
					'above_1' => array(),
					'above_2' => array(),
					'above_3' => array(),
					'above_4' => array(),
					'above_5' => array(),
				),
			'primary' =>
				array(
					'primary_1' => array(),
					'primary_2' => array(),
					'primary_3' => array(),
					'primary_4' => array(),
					'primary_5' => array(),
				),
			'below'   =>
				array(
					'below_1' => array(),
					'below_2' => array(),
					'below_3' => array(),
					'below_4' => array(),
					'below_5' => array(),
				),
		);

		switch ( $footer_layout ) {
			case 'footer-sml-layout-1':
				$theme_options['footer-desktop-items']['below'] = array(
					'below_1' => array( $new_section_1_item, $new_section_2_item ),
					'below_2' => array(),
					'below_3' => array(),
					'below_4' => array(),
					'below_5' => array(),
				);
				$theme_options['hbb-footer-column']             = 1;
				$theme_options['hbb-footer-layout']             = array(
					'desktop' => 'full',
					'tablet'  => 'full',
					'mobile'  => 'full',
				);
				break;

			case 'footer-sml-layout-2':
				$theme_options['footer-desktop-items']['below'] = array(
					'below_1' => array( $new_section_1_item ),
					'below_2' => array( $new_section_2_item ),
					'below_3' => array(),
					'below_4' => array(),
					'below_5' => array(),
				);
				$theme_options['hbb-footer-column']             = 2;
				$theme_options['hbb-footer-layout']             = array(
					'desktop' => '2-equal',
					'tablet'  => '2-equal',
					'mobile'  => 'full',
				);
				break;

			default:
				$theme_options['footer-desktop-items']['below'] = array(
					'below_1' => array( 'copyright' ),
					'below_2' => array(),
					'below_3' => array(),
					'below_4' => array(),
					'below_5' => array(),
				);
		}
	}

	return array(
		'theme_options'  => $theme_options,
		'used_elements'  => $used_elements,
		'widget_options' => $widget_options,
	);
}

/**
 * Header Footer builder - Migration of Footer Widgets.
 *
 * @since 3.0.0
 * @param array $theme_options Theme options.
 * @param array $used_elements Used Elements array.
 * @param array $widget_options Widget options.
 * @return array
 */
function astra_footer_widgets_migration( $theme_options, $used_elements, $widget_options ) {

	$footer_widget_layouts = ( isset( $theme_options['footer-adv'] ) ) ? $theme_options['footer-adv'] : '';

	if ( '' !== $footer_widget_layouts ) {

		$column = 2;
		$layout = array(
			'desktop' => '2-equal',
			'tablet'  => '2-equal',
			'mobile'  => 'full',
		);
		$items  = array(
			'above_1' => array(),
			'above_2' => array(),
			'above_3' => array(),
			'above_4' => array(),
			'above_5' => array(),
		);

		switch ( $footer_widget_layouts ) {
			case 'layout-1':
				$column = '1';
				$layout = array(
					'desktop' => 'full',
					'tablet'  => 'full',
					'mobile'  => 'full',
				);
				$items  = array(
					'above_1' => array( 'widget-1' ),
					'above_2' => array(),
					'above_3' => array(),
					'above_4' => array(),
					'above_5' => array(),
				);
				break;

			case 'layout-2':
				$column = '2';
				$layout = array(
					'desktop' => '2-equal',
					'tablet'  => '2-equal',
					'mobile'  => '2-equal',
				);
				$items  = array(
					'above_1' => array( 'widget-1' ),
					'above_2' => array( 'widget-2' ),
					'above_3' => array(),
					'above_4' => array(),
					'above_5' => array(),
				);
				break;

			case 'layout-3':
				$column = '3';
				$layout = array(
					'desktop' => '3-equal',
					'tablet'  => 'full',
					'mobile'  => 'full',
				);
				$items  = array(
					'above_1' => array( 'widget-1' ),
					'above_2' => array( 'widget-2' ),
					'above_3' => array( 'widget-3' ),
					'above_4' => array(),
					'above_5' => array(),
				);
				break;

			case 'layout-4':
				$column = '4';
				$layout = array(
					'desktop' => '4-equal',
					'tablet'  => 'full',
					'mobile'  => 'full',
				);
				$items  = array(
					'above_1' => array( 'widget-1' ),
					'above_2' => array( 'widget-2' ),
					'above_3' => array( 'widget-3' ),
					'above_4' => array( 'widget-4' ),
					'above_5' => array(),
				);
				break;

			case 'layout-5':
				$column = '5';
				$layout = array(
					'desktop' => '5-equal',
					'tablet'  => 'full',
					'mobile'  => 'full',
				);
				$items  = array(
					'above_1' => array( 'widget-1' ),
					'above_2' => array( 'widget-2' ),
					'above_3' => array( 'widget-3' ),
					'above_4' => array( 'widget-4' ),
					'above_5' => array( 'widget-5' ),
				);
				break;

			case 'layout-6':
			case 'layout-7':
				$column = '3';
				$layout = array(
					'desktop' => '3-lheavy',
					'tablet'  => 'full',
					'mobile'  => 'full',
				);
				$items  = array(
					'above_1' => array( 'widget-1' ),
					'above_2' => array( 'widget-2' ),
					'above_3' => array( 'widget-3' ),
					'above_4' => array(),
					'above_5' => array(),
				);
				break;
		}

		$theme_options['hba-footer-column'] = $column;
		$theme_options['hba-footer-layout'] = $layout;
		if ( isset( $theme_options['footer-desktop-items'] ) ) {
			$theme_options['footer-desktop-items']['above'] = $items;
		}

		for ( $i = 1; $i <= $column; $i++ ) {

			if ( isset( $theme_options['footer-adv-wgt-title-color'] ) ) {
				$theme_options[ 'footer-widget-' . $i . '-title-color' ] = array(
					'desktop' => $theme_options['footer-adv-wgt-title-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}

			if ( isset( $theme_options['footer-adv-text-color'] ) ) {
				$theme_options[ 'footer-widget-' . $i . '-color' ] = array(
					'desktop' => $theme_options['footer-adv-text-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}

			if ( isset( $theme_options['footer-adv-link-color'] ) ) {
				$theme_options[ 'footer-widget-' . $i . '-link-color' ] = array(
					'desktop' => $theme_options['footer-adv-link-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}

			if ( isset( $theme_options['footer-adv-link-h-color'] ) ) {
				$theme_options[ 'footer-widget-' . $i . '-link-h-color' ] = array(
					'desktop' => $theme_options['footer-adv-link-h-color'],
					'tablet'  => '',
					'mobile'  => '',
				);
			}


			if ( isset( $theme_options['footer-adv-wgt-title-font-size'] ) ) {
				$theme_options[ 'footer-widget-' . $i . '-font-size' ] = $theme_options['footer-adv-wgt-title-font-size'];
			}

			if ( isset( $theme_options['footer-adv-wgt-title-font-weight'] ) ) {
				$theme_options[ 'footer-widget-' . $i . '-font-weight' ] = $theme_options['footer-adv-wgt-title-font-weight'];
			}

			if ( isset( $theme_options['footer-adv-wgt-title-line-height'] ) ) {
				$theme_options[ 'footer-widget-' . $i . '-line-height' ] = $theme_options['footer-adv-wgt-title-line-height'];
			}

			if ( isset( $theme_options['footer-adv-wgt-title-font-family'] ) ) {
				$theme_options[ 'footer-widget-' . $i . '-font-family' ] = $theme_options['footer-adv-wgt-title-font-family'];
			}

			if ( isset( $theme_options['footer-adv-wgt-title-text-transform'] ) ) {
				$theme_options[ 'footer-widget-' . $i . '-text-transform' ] = $theme_options['footer-adv-wgt-title-text-transform'];
			}


			if ( isset( $theme_options['footer-adv-wgt-content-font-size'] ) ) {
				$theme_options[ 'footer-widget-' . $i . '-content-font-size' ] = $theme_options['footer-adv-wgt-content-font-size'];
			}

			if ( isset( $theme_options['footer-adv-wgt-content-font-weight'] ) ) {
				$theme_options[ 'footer-widget-' . $i . '-content-font-weight' ] = $theme_options['footer-adv-wgt-content-font-weight'];
			}

			if ( isset( $theme_options['footer-adv-wgt-content-line-height'] ) ) {
				$theme_options[ 'footer-widget-' . $i . '-content-line-height' ] = $theme_options['footer-adv-wgt-content-line-height'];
			}

			if ( isset( $theme_options['footer-adv-wgt-content-font-family'] ) ) {
				$theme_options[ 'footer-widget-' . $i . '-content-font-family' ] = $theme_options['footer-adv-wgt-content-font-family'];
			}

			if ( isset( $theme_options['footer-adv-wgt-content-text-transform'] ) ) {
				$theme_options[ 'footer-widget-' . $i . '-content-text-transform' ] = $theme_options['footer-adv-wgt-content-text-transform'];
			}

			if ( isset( $widget_options[ 'advanced-footer-widget-' . $i ] ) ) {
				$widget_options[ 'footer-widget-' . $i ] = $widget_options[ 'advanced-footer-widget-' . $i ];
			}
		}
	}

	if ( isset( $theme_options['footer-adv-border-width'] ) ) {
		$theme_options['hba-footer-separator'] = $theme_options['footer-adv-border-width'];
	}

	if ( isset( $theme_options['footer-adv-border-color'] ) ) {
		$theme_options['hba-footer-top-border-color'] = $theme_options['footer-adv-border-color'];
	}

	if ( isset( $theme_options['footer-adv-bg-obj'] ) ) {
		$theme_options['hba-footer-bg-obj-responsive'] = array(
			'desktop' => $theme_options['footer-adv-bg-obj'],
			'tablet'  => '',
			'mobile'  => '',
		);
	}

	if ( isset( $theme_options['footer-adv-area-padding'] ) ) {
		$theme_options['section-above-footer-builder-padding'] = $theme_options['footer-adv-area-padding'];
	}

	return array(
		'theme_options'  => $theme_options,
		'used_elements'  => $used_elements,
		'widget_options' => $widget_options,
	);
}

/**
 * Do not apply new Media & Text block padding CSS & not remove padding for #primary on mobile devices directly for existing users.
 *
 * @since 2.6.1
 *
 * @return void
 */
function astra_gutenberg_media_text_block_css_compatibility() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['guntenberg-media-text-block-padding-css'] ) ) {
		$theme_options['guntenberg-media-text-block-padding-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Gutenberg pattern compatibility changes.
 *
 * @since 3.3.0
 *
 * @return void
 */
function astra_gutenberg_pattern_compatibility() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['guntenberg-button-pattern-compat-css'] ) ) {
		$theme_options['guntenberg-button-pattern-compat-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Set flag to provide backward compatibility of float based CSS for existing users.
 *
 * @since 3.3.0
 * @return void.
 */
function astra_check_flex_based_css() {
	$theme_options = get_option( 'astra-settings', array() );

	if ( ! isset( $theme_options['is-flex-based-css'] ) ) {
		$theme_options['is-flex-based-css'] = false;
		update_option( 'astra-settings', $theme_options );
	}
}

/**
 * Update the Cart Style, Icon color & Border radius if None style is selected.
 *
 * @since 3.4.0
 * @return void.
 */
function astra_update_cart_style() {

	$theme_options = get_option( 'astra-settings', array() );

	if ( isset( $theme_options['woo-header-cart-icon-style'] ) && 'none' === $theme_options['woo-header-cart-icon-style'] ) {
		$theme_options['woo-header-cart-icon-style']  = 'outline';
		$theme_options['header-woo-cart-icon-color']  = '';
		$theme_options['woo-header-cart-icon-color']  = '';
		$theme_options['woo-header-cart-icon-radius'] = '';
	}

	if ( isset( $theme_options['edd-header-cart-icon-style'] ) && 'none' === $theme_options['edd-header-cart-icon-style'] ) {
		$theme_options['edd-header-cart-icon-style']  = 'outline';
		$theme_options['edd-header-cart-icon-color']  = '';
		$theme_options['edd-header-cart-icon-radius'] = '';
	}

	update_option( 'astra-settings', $theme_options );
}
