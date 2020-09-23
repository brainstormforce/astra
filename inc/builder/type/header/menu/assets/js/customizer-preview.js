/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package Astra
 * @since x.x.x
 */

( function( $ ) {

	var tablet_break_point    = AstraBuilderMenuData.tablet_break_point || 768,
		mobile_break_point    = AstraBuilderMenuData.mobile_break_point || 544;

	for ( var index = 1; index <= AstraBuilderMenuData.header_menu_count; index++ ) {

		var prefix = 'menu' + index;
		var selector = '.astra-hfb-header .ast-builder-menu-' + index + '.ast-builder-menu .main-header-menu';

		/**
		 * Typography CSS.
		 */

			// Menu Typography.
			astra_generate_outside_font_family_css(
				'astra-settings[header-' + prefix + '-font-family]',
				selector + ' .menu-item > .menu-link'
			);
			astra_css(
				'astra-settings[header-' + prefix + '-font-weight]',
				'font-weight',
				selector + ' .menu-item > .menu-link'
			);
			astra_css(
				'astra-settings[header-' + prefix + '-text-transform]',
				'text-transform',
				selector + ' .menu-item > .menu-link'
			);
			astra_responsive_font_size(
				'astra-settings[header-' + prefix + '-font-size]',
				selector + ' .menu-item > .menu-link'
			);
			astra_css(
				'astra-settings[header-' + prefix + '-line-height]',
				'line-height',
				selector + ' .menu-item > .menu-link'
			);
			astra_css(
				'astra-settings[header-' + prefix + '-letter-spacing]',
				'letter-spacing',
				selector + ' .menu-item > .menu-link',
				'px'
			);

		/**
		 * Color CSS.
		 */

			/**
			 * Menu - Colors
			 */

			// Menu - Normal Color
			astra_color_responsive_css(
				'astra-builder',
				'astra-settings[header-' + prefix + '-color-responsive]',
				'color',
				selector + ' .menu-item > .menu-link'
			);
			
			// Menu - Hover Color
			astra_color_responsive_css(
				'astra-builder',
				'astra-settings[header-' + prefix + '-h-color-responsive]',
				'color',
				selector + ' .menu-item:hover > .menu-link'
			);

			// Menu Toggle -  Color
			astra_color_responsive_css(
				'astra-builder-toggle',
				'astra-settings[header-' + prefix + '-color-responsive]',
				'color',
				selector + ' .menu-item > .ast-menu-toggle'
			);
			
			// Menu Toggle - Hover Color
			astra_color_responsive_css(
				'astra-builder-toggle',
				'astra-settings[header-' + prefix + '-h-color-responsive]',
				'color',
				selector + ' .menu-item:hover > .ast-menu-toggle'
			);
			// Menu - Active Color
			astra_color_responsive_css(
				'astra-builder-active',
				'astra-settings[header-' + prefix + '-a-color-responsive]',
				'color',
				selector + ' .menu-item.current-menu-item > .menu-link'
			);

			// Menu - Normal Background
			astra_apply_responsive_background_css( 'astra-settings[header-' + prefix + '-bg-obj-responsive]', selector + '.ast-nav-menu', 'desktop' );
			astra_apply_responsive_background_css( 'astra-settings[header-' + prefix + '-bg-obj-responsive]', selector + '.ast-nav-menu', 'tablet' );
			astra_apply_responsive_background_css( 'astra-settings[header-' + prefix + '-bg-obj-responsive]', selector + '.ast-nav-menu', 'mobile' );
			
			// Menu - Hover Background
			astra_color_responsive_css(
				'astra-builder',
				'astra-settings[header-' + prefix + '-h-bg-color-responsive]',
				'background',
				selector + ' .menu-item:hover > .menu-link'
			);

			// Menu - Active Background
			astra_color_responsive_css(
				'astra-builder',
				'astra-settings[header-' + prefix + '-a-bg-color-responsive]',
				'background',
				selector + ' .menu-item.current-menu-item > .menu-link'
			);

		/**
		 * Border CSS.
		 */

			// Menu 1 > Sub Menu Border Size.
			wp.customize( 'astra-settings[header-menu1-submenu-border]', function( setting ) {
				setting.bind( function( border ) {

					var dynamicStyle = '.astra-hfb-header .ast-builder-menu-1 .main-header-menu .sub-menu, .astra-hfb-header .ast-builder-menu-1 .main-header-menu.submenu-with-border .astra-megamenu {';
						dynamicStyle += 'border-top-width:'  + border.top + 'px;';
						dynamicStyle += 'border-right-width:'  + border.right + 'px;';
						dynamicStyle += 'border-left-width:'   + border.left + 'px;';
						dynamicStyle += 'border-style: solid;';
						dynamicStyle += 'border-bottom-width:'   + border.bottom + 'px;';
						
					dynamicStyle += '}';
					astra_add_dynamic_css( 'header-menu1-submenu-border', dynamicStyle );

				} );
			} );

			// Menu 2 > Sub Menu Border Size.
			wp.customize( 'astra-settings[header-menu2-submenu-border]', function( setting ) {
				setting.bind( function( border ) {

					var dynamicStyle = '.astra-hfb-header .ast-builder-menu-2 .main-header-menu .sub-menu, .astra-hfb-header .ast-builder-menu-2 .main-header-menu.submenu-with-border .astra-megamenu {';
						dynamicStyle += 'border-top-width:'  + border.top + 'px;';
						dynamicStyle += 'border-right-width:'  + border.right + 'px;';
						dynamicStyle += 'border-left-width:'   + border.left + 'px;';
						dynamicStyle += 'border-style: solid;';
						dynamicStyle += 'border-bottom-width:'   + border.bottom + 'px;';
						
					dynamicStyle += '}';
					astra_add_dynamic_css( 'header-menu2-submenu-border', dynamicStyle );

				} );
			} );

			// Sub Menu - Border Color.
			astra_css(
				'astra-settings[header-' + prefix + '-submenu-b-color]',
				'border-color',
				selector + ' li.menu-item .sub-menu '
			);

	}

	/**
	 * Spacing CSS.
	 */

	// Menu Spacing - Menu 1.
	wp.customize( 'astra-settings[header-menu1-menu-spacing]', function( value ) {
		value.bind( function( padding ) {
			var dynamicStyle = '';
			dynamicStyle += '.ast-builder-menu-1.ast-builder-menu .main-header-menu .menu-item > .menu-link {';
			dynamicStyle += 'padding-left: ' + padding['desktop']['left'] + padding['desktop-unit'] + ';';
			dynamicStyle += 'padding-right: ' + padding['desktop']['right'] + padding['desktop-unit'] + ';';
			dynamicStyle += 'padding-top: ' + padding['desktop']['top'] + padding['desktop-unit'] + ';';
			dynamicStyle += 'padding-bottom: ' + padding['desktop']['bottom'] + padding['desktop-unit'] + ';';
			dynamicStyle += '} ';

			dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
			dynamicStyle += '.ast-builder-menu-1.ast-builder-menu .main-header-menu .menu-item > .menu-link {';
			dynamicStyle += 'padding-left: ' + padding['tablet']['left'] + padding['tablet-unit'] + ';';
			dynamicStyle += 'padding-right: ' + padding['tablet']['right'] + padding['tablet-unit'] + ';';
			dynamicStyle += 'padding-top: ' + padding['tablet']['top'] + padding['tablet-unit'] + ';';
			dynamicStyle += 'padding-bottom: ' + padding['tablet']['bottom'] + padding['tablet-unit'] + ';';
			dynamicStyle += '} ';
			dynamicStyle += '} ';

			dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
			dynamicStyle += '.ast-builder-menu-1.ast-builder-menu .main-header-menu .menu-item > .menu-link {';
			dynamicStyle += 'padding-left: ' + padding['mobile']['left'] + padding['mobile-unit'] + ';';
			dynamicStyle += 'padding-right: ' + padding['mobile']['right'] + padding['mobile-unit'] + ';';
			dynamicStyle += 'padding-top: ' + padding['mobile']['top'] + padding['mobile-unit'] + ';';
			dynamicStyle += 'padding-bottom: ' + padding['mobile']['bottom'] + padding['mobile-unit'] + ';';
			dynamicStyle += '} ';
			dynamicStyle += '} ';

			astra_add_dynamic_css( 'header-menu1-menu-spacing-toggle-button', dynamicStyle );
		} );
	} );

	// Margin - Menu 1.
	wp.customize( 'astra-settings[section-hb-menu-1-margin]', function( value ) {
			value.bind( function( margin ) {
				var selector = '.astra-hfb-header .ast-builder-menu-1 .main-header-bar-navigation .main-header-menu, .astra-hfb-header.ast-header-break-point .ast-builder-menu-1 .main-header-bar-navigation .main-header-menu';
				var dynamicStyle = '';
				dynamicStyle += selector + ' {';
				dynamicStyle += 'margin-left: ' + margin['desktop']['left'] + margin['desktop-unit'] + ';';
				dynamicStyle += 'margin-right: ' + margin['desktop']['right'] + margin['desktop-unit'] + ';';
				dynamicStyle += 'margin-top: ' + margin['desktop']['top'] + margin['desktop-unit'] + ';';
				dynamicStyle += 'margin-bottom: ' + margin['desktop']['bottom'] + margin['desktop-unit'] + ';';
				dynamicStyle += '} ';

				dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
				dynamicStyle += selector + ' {';
				dynamicStyle += 'margin-left: ' + margin['tablet']['left'] + margin['tablet-unit'] + ';';
				dynamicStyle += 'margin-right: ' + margin['tablet']['right'] + margin['tablet-unit'] + ';';
				dynamicStyle += 'margin-top: ' + margin['tablet']['top'] + margin['desktop-unit'] + ';';
				dynamicStyle += 'margin-bottom: ' + margin['tablet']['bottom'] + margin['desktop-unit'] + ';';
				dynamicStyle += '} ';
				dynamicStyle += '} ';

				dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
				dynamicStyle += selector + ' {';
				dynamicStyle += 'margin-left: ' + margin['mobile']['left'] + margin['mobile-unit'] + ';';
				dynamicStyle += 'margin-right: ' + margin['mobile']['right'] + margin['mobile-unit'] + ';';
				dynamicStyle += 'margin-top: ' + margin['mobile']['top'] + margin['desktop-unit'] + ';';
				dynamicStyle += 'margin-bottom: ' + margin['mobile']['bottom'] + margin['desktop-unit'] + ';';
				dynamicStyle += '} ';
				dynamicStyle += '} ';
				astra_add_dynamic_css( 'section-hb-menu-1-margin', dynamicStyle );
			} );
		} );

	// Menu Spacing - Menu 2.
	wp.customize( 'astra-settings[header-menu2-menu-spacing]', function( value ) {
		value.bind( function( padding ) {
			var dynamicStyle = '';
			dynamicStyle += '.ast-builder-menu-2.ast-builder-menu .main-header-menu .menu-item > .menu-link {';
			dynamicStyle += 'padding-left: ' + padding['desktop']['left'] + padding['desktop-unit'] + ';';
			dynamicStyle += 'padding-right: ' + padding['desktop']['right'] + padding['desktop-unit'] + ';';
			dynamicStyle += 'padding-top: ' + padding['desktop']['top'] + padding['desktop-unit'] + ';';
			dynamicStyle += 'padding-bottom: ' + padding['desktop']['bottom'] + padding['desktop-unit'] + ';';
			dynamicStyle += '} ';

			dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
			dynamicStyle += '.ast-builder-menu-2.ast-builder-menu .main-header-menu .menu-item > .menu-link {';
			dynamicStyle += 'padding-left: ' + padding['tablet']['left'] + padding['tablet-unit'] + ';';
			dynamicStyle += 'padding-right: ' + padding['tablet']['right'] + padding['tablet-unit'] + ';';
			dynamicStyle += 'padding-top: ' + padding['tablet']['top'] + padding['tablet-unit'] + ';';
			dynamicStyle += 'padding-bottom: ' + padding['tablet']['bottom'] + padding['tablet-unit'] + ';';
			dynamicStyle += '} ';
			dynamicStyle += '} ';

			dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
			dynamicStyle += '.ast-builder-menu-2.ast-builder-menu .main-header-menu .menu-item > .menu-link {';
			dynamicStyle += 'padding-left: ' + padding['mobile']['left'] + padding['mobile-unit'] + ';';
			dynamicStyle += 'padding-right: ' + padding['mobile']['right'] + padding['mobile-unit'] + ';';
			dynamicStyle += 'padding-top: ' + padding['mobile']['top'] + padding['mobile-unit'] + ';';
			dynamicStyle += 'padding-bottom: ' + padding['mobile']['bottom'] + padding['mobile-unit'] + ';';
			dynamicStyle += '} ';
			dynamicStyle += '} ';

			astra_add_dynamic_css( 'header-menu2-menu-spacing-toggle-button', dynamicStyle );
		} );
	} );

	// Margin - Menu 2.
	wp.customize( 'astra-settings[section-hb-menu-2-margin]', function( value ) {
		value.bind( function( margin ) {
			var selector = '.astra-hfb-header .ast-builder-menu-2 .main-header-bar-navigation .main-header-menu, .astra-hfb-header.ast-header-break-point .ast-builder-menu-2 .main-header-bar-navigation .main-header-menu';
			var dynamicStyle = '';
			dynamicStyle += selector + ' {';
			dynamicStyle += 'margin-left: ' + margin['desktop']['left'] + margin['desktop-unit'] + ';';
			dynamicStyle += 'margin-right: ' + margin['desktop']['right'] + margin['desktop-unit'] + ';';
			dynamicStyle += 'margin-top: ' + margin['desktop']['top'] + margin['desktop-unit'] + ';';
			dynamicStyle += 'margin-bottom: ' + margin['desktop']['bottom'] + margin['desktop-unit'] + ';';
			dynamicStyle += '} ';

			dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
			dynamicStyle += selector + ' {';
			dynamicStyle += 'margin-left: ' + margin['tablet']['left'] + margin['tablet-unit'] + ';';
			dynamicStyle += 'margin-right: ' + margin['tablet']['right'] + margin['tablet-unit'] + ';';
			dynamicStyle += 'margin-top: ' + margin['tablet']['top'] + margin['desktop-unit'] + ';';
			dynamicStyle += 'margin-bottom: ' + margin['tablet']['bottom'] + margin['desktop-unit'] + ';';
			dynamicStyle += '} ';
			dynamicStyle += '} ';

			dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
			dynamicStyle += selector + ' {';
			dynamicStyle += 'margin-left: ' + margin['mobile']['left'] + margin['mobile-unit'] + ';';
			dynamicStyle += 'margin-right: ' + margin['mobile']['right'] + margin['mobile-unit'] + ';';
			dynamicStyle += 'margin-top: ' + margin['mobile']['top'] + margin['desktop-unit'] + ';';
			dynamicStyle += 'margin-bottom: ' + margin['mobile']['bottom'] + margin['desktop-unit'] + ';';
			dynamicStyle += '} ';
			dynamicStyle += '} ';
			astra_add_dynamic_css( 'section-hb-menu-2-margin', dynamicStyle );
		} );
	} );

	
	// Sub Menu Divider Toggle.
	wp.customize( 'astra-settings[header-menu1-submenu-item-border]', function( setting ) {
		setting.bind( function( value ) {

			var item_divider_color = (typeof ( wp.customize._value['astra-settings[header-menu2-submenu-item-b-color]'] ) != 'undefined') ? wp.customize._value['astra-settings[header-menu2-submenu-item-b-color]']._value : '';

			var dynamicStyle = '';
			if ( value ) {

				dynamicStyle += '.astra-hfb-header.ast-header-break-point .ast-builder-menu-1.ast-builder-menu .main-header-menu .menu-item .menu-link {';
					dynamicStyle += 'border-bottom-width: 1px;';
					dynamicStyle += 'border-style: solid;';
					dynamicStyle += 'border-color:' + item_divider_color + ';';
				dynamicStyle += '}';

				dynamicStyle += '.astra-hfb-header.ast-header-break-point .ast-builder-menu-1.ast-builder-menu .main-header-menu .menu-item:last-child .menu-link {';
					dynamicStyle += 'border-bottom-width: 0px;';
					dynamicStyle += 'border-style: none;';
				dynamicStyle += '}';

				dynamicStyle += '.astra-hfb-header .ast-builder-menu-1.ast-builder-menu .main-header-menu .sub-menu .menu-link {';
					dynamicStyle += 'border-bottom-width: 1px;';
					dynamicStyle += 'border-style: solid;';
					dynamicStyle += 'border-color:' + item_divider_color + ';';
				dynamicStyle += '}';

				dynamicStyle += '.astra-hfb-header .ast-builder-menu-1.ast-builder-menu .main-header-menu li.menu-item .sub-menu .menu-item:last-child .menu-link {';
					dynamicStyle += 'border-bottom-width: 0px;';
					dynamicStyle += 'border-style: none;';
				dynamicStyle += '}';
	
			} else {
				dynamicStyle = '.astra-hfb-header .ast-builder-menu-1 .main-navigation .menu-item .menu-link {';
					dynamicStyle += 'border-style: none;';
				dynamicStyle += '}';

				dynamicStyle = '.astra-hfb-header .ast-builder-menu-1.ast-builder-menu .main-header-menu .menu-item .menu-link {';
					dynamicStyle += 'border-style: none;';
				dynamicStyle += '}';
			}

			astra_add_dynamic_css( 'header-menu2-submenu-item-border', dynamicStyle );

		} );
	} );

	// Sub Menu Divider Toggle.
	wp.customize( 'astra-settings[header-menu2-submenu-item-border]', function( setting ) {
		setting.bind( function( value ) {

			var dynamicStyle = '';
			if ( value ) {

				dynamicStyle += '.astra-hfb-header.ast-header-break-point .ast-builder-menu-2.ast-builder-menu .main-header-menu .menu-item .menu-link {';
					dynamicStyle += 'border-bottom-width: 1px;';
					dynamicStyle += 'border-style: solid;';
				dynamicStyle += '}';

				dynamicStyle += '.astra-hfb-header.ast-header-break-point .ast-builder-menu-2.ast-builder-menu .main-header-menu .menu-item:last-child .menu-link {';
					dynamicStyle += 'border-bottom-width: 0px;';
					dynamicStyle += 'border-style: none;';
				dynamicStyle += '}';

				dynamicStyle += '.astra-hfb-header .ast-builder-menu-2.ast-builder-menu .main-header-menu .sub-menu .menu-link {';
					dynamicStyle += 'border-bottom-width: 1px;';
					dynamicStyle += 'border-style: solid;';
				dynamicStyle += '}';

				dynamicStyle += '.astra-hfb-header .ast-builder-menu-2.ast-builder-menu .main-header-menu li.menu-item .sub-menu .menu-item:last-child .menu-link {';
					dynamicStyle += 'border-bottom-width: 0px;';
					dynamicStyle += 'border-style: none;';
				dynamicStyle += '}';
	
			} else {
				dynamicStyle = '.astra-hfb-header .ast-builder-menu-2 .main-navigation .menu-item .menu-link {';
					dynamicStyle += 'border-style: none;';
				dynamicStyle += '}';

				dynamicStyle = '.astra-hfb-header .ast-builder-menu-2.ast-builder-menu .main-header-menu .menu-item .menu-link {';
					dynamicStyle += 'border-style: none;';
				dynamicStyle += '}';
			}

			astra_add_dynamic_css( 'header-menu2-submenu-item-border', dynamicStyle );

		} );
	} );

	// Sub Menu - Divider Color.
	astra_css(
		'astra-settings[header-menu1-submenu-item-b-color]',
		'border-color',
		'.astra-hfb-header .ast-builder-menu-1.ast-builder-menu .main-header-menu .sub-menu .menu-link, .astra-hfb-header.ast-header-break-point .ast-builder-menu-1.ast-builder-menu .main-header-menu .menu-item .menu-link'
	);
	astra_css(
		'astra-settings[header-menu2-submenu-item-b-color]',
		'border-color',
		'.astra-hfb-header .ast-builder-menu-2.ast-builder-menu .main-header-menu .sub-menu .menu-link, .astra-hfb-header.ast-header-break-point .ast-builder-menu-2.ast-builder-menu .main-header-menu .menu-item .menu-link'
	);

	// Transparent header > Submenu link hover color.
	astra_color_responsive_css( 'astra-builder-transparent-submenu', 'astra-settings[transparent-submenu-h-color-responsive]', 'color', '.ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-item:hover > .menu-link' );

} )( jQuery );
		