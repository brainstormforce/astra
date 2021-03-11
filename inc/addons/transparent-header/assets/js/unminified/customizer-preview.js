/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package Astra Addon
 * @since  1.0.0
 */

 ( function( $ ) {

	var isAstraHFBuilderActive    = AstraBuilderTransparemtData.is_astra_hf_builder_active || false;

	/**
	 * Transparent Logo Width
	 */
	wp.customize( 'astra-settings[transparent-header-logo-width]', function( setting ) {
		setting.bind( function( logo_width ) {
			if ( logo_width['desktop'] != '' || logo_width['tablet'] != '' || logo_width['mobile'] != '' ) {
				var dynamicStyle = '.ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo img {max-width: ' + logo_width['desktop'] + 'px;} .ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo .astra-logo-svg { width: ' + logo_width['desktop'] + 'px;} @media( max-width: 768px ) { .ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo img {max-width: ' + logo_width['tablet'] + 'px;} .ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo .astra-logo-svg { width: ' + logo_width['tablet'] + 'px;} } @media( max-width: 544px ) { .ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo img {max-width: ' + logo_width['mobile'] + 'px;} .ast-theme-transparent-header #masthead .site-logo-img .transparent-custom-logo .astra-logo-svg { width: ' + logo_width['mobile'] + 'px;} }';
				astra_add_dynamic_css( 'transparent-header-logo-width', dynamicStyle );
			}
			else{
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );

	/**
	 * Transparent Header Bottom Border width
	 */
	wp.customize( 'astra-settings[transparent-header-main-sep]', function( value ) {
		value.bind( function( border ) {

			if( isAstraHFBuilderActive ) {

				var selector = '';
				var displayOn = (typeof ( wp.customize._value['astra-settings[transparent-header-on-devices]'] ) != 'undefined') ? wp.customize._value['astra-settings[transparent-header-on-devices]']._value : 'both';

				switch( displayOn ) {
					case 'both':
						selector = '.ast-theme-transparent-header #ast-desktop-header > [CLASS*="-header-wrap"]:last-child > [CLASS*="-header-bar"], .ast-theme-transparent-header.ast-header-break-point #ast-mobile-header > [CLASS*="-header-wrap"]:nth-last-child(2) > [CLASS*="-header-bar"]';
					break;
					case 'desktop':
						selector = '.ast-theme-transparent-header #ast-desktop-header > [CLASS*="-header-wrap"]:last-child > [CLASS*="-header-bar"]';
					break;
					case 'mobile':
						selector = '.ast-theme-transparent-header.ast-header-break-point #ast-mobile-header > [CLASS*="-header-wrap"]:nth-last-child(2) > [CLASS*="-header-bar"]';
					break;
				}

				var dynamicStyle = selector + '{';
				dynamicStyle += 'border-bottom-width: ' + border + 'px';
				dynamicStyle += '}';

				astra_add_dynamic_css( 'transparent-header-main-sep', dynamicStyle );

			} else {

				var dynamicStyle = ' body.ast-theme-transparent-header.ast-header-break-point .main-header-bar { border-bottom-width: ' + border + 'px } ';

				dynamicStyle += 'body.ast-theme-transparent-header.ast-desktop .main-header-bar {';
				dynamicStyle += 'border-bottom-width: ' + border + 'px';
				dynamicStyle += '}';

				astra_add_dynamic_css( 'transparent-header-main-sep', dynamicStyle );
			}
		} );
	} );

	/**
	 * Transparent Header Bottom Border color
	 */
	wp.customize( 'astra-settings[transparent-header-main-sep-color]', function( value ) {
		value.bind( function( color ) {
			if (color == '') {
				wp.customize.preview.send( 'refresh' );
			}

			if( isAstraHFBuilderActive ) {

				var selector = '';
				var displayOn = (typeof ( wp.customize._value['astra-settings[transparent-header-on-devices]'] ) != 'undefined') ? wp.customize._value['astra-settings[transparent-header-on-devices]']._value : 'both';

				switch( displayOn ) {
					case 'both':
						selector = '.ast-theme-transparent-header #ast-desktop-header > [CLASS*="-header-wrap"]:last-child > [CLASS*="-header-bar"], .ast-theme-transparent-header.ast-header-break-point #ast-mobile-header > [CLASS*="-header-wrap"]:nth-last-child(2) > [CLASS*="-header-bar"]';
					break;
					case 'desktop':
						selector = '.ast-theme-transparent-header #ast-desktop-header > [CLASS*="-header-wrap"]:last-child > [CLASS*="-header-bar"]';
					break;
					case 'mobile':
						selector = '.ast-theme-transparent-header.ast-header-break-point #ast-mobile-header > [CLASS*="-header-wrap"]:nth-last-child(2) > [CLASS*="-header-bar"]';
					break;
				}

				var dynamicStyle = selector + '{';
				dynamicStyle += 'border-bottom-color: ' + color;
				dynamicStyle += '}';

				astra_add_dynamic_css( 'transparent-header-main-sep-color', dynamicStyle );
			} else {

				var dynamicStyle = ' body.ast-theme-transparent-header.ast-desktop .main-header-bar { border-bottom-color: ' + color + '; } ';
					dynamicStyle += ' body.ast-theme-transparent-header.ast-header-break-point .main-header-bar { border-bottom-color: ' + color + '; } ';

				astra_add_dynamic_css( 'transparent-header-main-sep-color', dynamicStyle );
			}

		} );
	} );


	/* Transparent Header Colors */
	astra_color_responsive_css( 'colors-background', 'astra-settings[primary-menu-a-bg-color-responsive]', 	'background-color', 	'.main-header-menu .current-menu-item > .menu-link, .main-header-menu .current-menu-ancestor > .menu-link,.ast-header-sections-navigation .menu-item.current-menu-item > .menu-link,.ast-header-sections-navigation .menu-item.current-menu-ancestor > .menu-link' );

	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-header-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header .main-header-bar, .ast-theme-transparent-header.ast-header-break-point .main-header-bar-wrap .main-header-menu, .ast-theme-transparent-header.ast-header-break-point .main-header-bar-wrap .main-header-bar, .ast-theme-transparent-header.ast-header-break-point .ast-mobile-header-wrap .main-header-bar' );
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-header-color-site-title-responsive]', 'color', '.ast-theme-transparent-header .site-title a, .ast-theme-transparent-header .site-title a:focus, .ast-theme-transparent-header .site-title a:hover, .ast-theme-transparent-header .site-title a:visited, .ast-theme-transparent-header .site-header .site-description' );
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-header-color-h-site-title-responsive]', 'color', '.ast-theme-transparent-header .site-header .site-title a:hover' );

	// Primary Menu
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-menu-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header .ast-builder-menu .main-header-menu, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu, .ast-theme-transparent-header .ast-builder-menu .main-header-menu, .ast-theme-transparent-header.ast-header-break-point .ast-builder-menu .main-header-bar-wrap .main-header-menu, .ast-flyout-menu-enable.ast-header-break-point.ast-theme-transparent-header .main-header-bar-navigation #site-navigation, .ast-fullscreen-menu-enable.ast-header-break-point.ast-theme-transparent-header .main-header-bar-navigation #site-navigation, .ast-flyout-above-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation-wrap .ast-above-header-navigation, .ast-flyout-below-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-below-header-navigation-wrap .ast-below-header-actual-nav, .ast-fullscreen-above-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation-wrap, .ast-fullscreen-below-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-below-header-navigation-wrap, .ast-theme-transparent-header .main-header-menu .menu-link' );

	astra_color_responsive_css( 'transparent-primary-header-menu-colors', 'astra-settings[transparent-menu-color-responsive]', 'color', '.ast-theme-transparent-header .ast-builder-menu .main-header-menu, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-link, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .menu-item > .menu-link, .ast-theme-transparent-header .ast-masthead-custom-menu-items, .ast-theme-transparent-header .ast-masthead-custom-menu-items a, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .menu-link' );

	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-menu-h-color-responsive]', 'color', '.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item:hover > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .focus > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-item > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-ancestor > .menu-link, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-item > .menu-link, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-ancestor > .menu-link, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header [CLASS*="ast-builder-menu-"] .main-header-menu .current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .menu-item:hover > .menu-link, .ast-theme-transparent-header .main-header-menu .current-menu-item > .menu-link, .ast-theme-transparent-header .main-header-menu .current-menu-ancestor > .menu-link' );

	// Primary SubMenu
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-submenu-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu, .ast-header-break-point.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu, .ast-theme-transparent-header .ast-builder-menu [CLASS*="ast-builder-menu-"] .main-header-menu .sub-menu, .ast-header-break-point.ast-theme-transparent-header .ast-builder-menu [CLASS*="ast-builder-menu-"] .main-header-menu .sub-menu, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-link, .ast-header-break-point.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-link, .ast-theme-transparent-header .ast-builder-menu [CLASS*="ast-builder-menu-"] .main-header-menu .sub-menu .menu-link, .ast-header-break-point.ast-theme-transparent-header .ast-builder-menu [CLASS*="ast-builder-menu-"] .main-header-menu .sub-menu .menu-link, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-link, .ast-header-break-point.ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-link' );

	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-submenu-color-responsive]', 'color', '.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item .menu-link,.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item > .ast-menu-toggle, .astra-hfb-header.ast-theme-transparent-header [CLASS*="ast-builder-menu-"]  .main-header-menu .sub-menu .menu-item .menu-link, .astra-hfb-header.ast-theme-transparent-header [CLASS*="ast-builder-menu-"]  .main-header-menu .sub-menu .menu-item > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-link, .ast-header-break-point.ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-link' );

	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-submenu-h-color-responsive]', 'color', '.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu a:hover,.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item:hover > .menu-item, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.focus > .menu-item, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.current-menu-item > .menu-link,	.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.current-menu-item > .ast-menu-toggle,.ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item.focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-builder-menu .main-header-menu .menu-item .sub-menu .menu-item:hover > .menu-link, .ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-item:hover .menu-link' );

	// Primary Content Section text color
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-content-section-text-color-responsive]', 'color', '.ast-theme-transparent-header div.ast-masthead-custom-menu-items, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget-title, .ast-theme-transparent-header .site-header-section [CLASS*="ast-header-html-"] .ast-builder-html-element' );
	// Primary Content Section link color
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-content-section-link-color-responsive]', 'color', '.ast-theme-transparent-header div.ast-masthead-custom-menu-items a, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a, .ast-theme-transparent-header .site-header-section [CLASS*="ast-header-html-"] .ast-builder-html-element a' );
	// Primary Content Section link hover color
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-content-section-link-h-color-responsive]', 'color', '.ast-theme-transparent-header div.ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a:hover, .ast-theme-transparent-header .site-header-section [CLASS*="ast-header-html-"] .ast-builder-html-element a:hover' );



	// Above Header Menu
	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-header-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header .ast-above-header-wrap .ast-above-header' );

	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-menu-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header .ast-above-header-menu, .ast-theme-transparent-header.ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul, .ast-flyout-above-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation-wrap .ast-above-header-navigation, .ast-fullscreen-above-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-above-header-section-separated .ast-above-header-navigation-wrap' );
	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-menu-color-responsive]', 'color', '.ast-theme-transparent-header .ast-above-header-navigation a, .ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation a, .ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation > ul.ast-above-header-menu > .menu-item-has-children:not(.current-menu-item) > .ast-menu-toggle' );
	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-menu-h-color-responsive]', 'color', '.ast-theme-transparent-header .ast-above-header-navigation .menu-item.current-menu-item > .menu-link,.ast-theme-transparent-header .ast-above-header-navigation .menu-item.current-menu-ancestor > .menu-link, .ast-theme-transparent-header .ast-above-header-navigation .menu-item:hover > .menu-link' )
	// Above Header SubMenu
	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-submenu-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header .ast-above-header-menu .sub-menu' );
	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-submenu-color-responsive]', 'color', '.ast-theme-transparent-header .ast-above-header-menu .sub-menu, .ast-theme-transparent-header .ast-above-header-navigation .ast-above-header-menu .sub-menu a' );
	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-submenu-h-color-responsive]', 'color', '.ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item:hover > .menu-item, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item:focus > .menu-item, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.focus > .menu-item,.ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.focus > .ast-menu-toggle,.ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-ancestor:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-ancestor:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-ancestor.focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-item:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-item.focus > .ast-menu-toggle,.ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-ancestor > .menu-link, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-item > .menu-link, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-ancestor:hover > .menu-link, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-ancestor:focus > .menu-link, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-ancestor.focus > .menu-link, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-item:hover > .menu-link, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-item:focus > .menu-link, .ast-theme-transparent-header .ast-above-header-menu .sub-menu .menu-item.current-menu-item.focus > .menu-link' );

	// Above Header Content Section text color
	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-content-section-text-color-responsive]', 'color', '.ast-theme-transparent-header .ast-above-header-section .user-select, .ast-theme-transparent-header .ast-above-header-section .widget, .ast-theme-transparent-header .ast-above-header-section .widget-title' );
	// Above Header Content Section link color
	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-content-section-link-color-responsive]', 'color', '.ast-theme-transparent-header .ast-above-header-section .user-select a, .ast-theme-transparent-header .ast-above-header-section .widget a' );
	// Above Header Content Section link hover color
	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-content-section-link-h-color-responsive]', 'color', '.ast-theme-transparent-header .ast-above-header-section .user-select a:hover, .ast-theme-transparent-header .ast-above-header-section .widget a:hover' );

	// below Header Menu
	astra_color_responsive_css( 'transparent-below-header', 'astra-settings[transparent-header-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header .ast-below-header-wrap .ast-below-header' );

	astra_color_responsive_css( 'transparent-below-header', 'astra-settings[transparent-menu-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header.ast-no-toggle-below-menu-enable.ast-header-break-point .ast-below-header-navigation-wrap, .ast-theme-transparent-header .ast-below-header-actual-nav, .ast-theme-transparent-header.ast-header-break-point .ast-below-header-actual-nav, .ast-flyout-below-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-below-header-navigation-wrap .ast-below-header-actual-nav, .ast-fullscreen-below-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-below-header-section-separated .ast-below-header-navigation-wrap' );
	astra_color_responsive_css( 'transparent-below-header', 'astra-settings[transparent-menu-color-responsive]', 'color', '.ast-theme-transparent-header .ast-below-header-menu, .ast-theme-transparent-header .ast-below-header-menu a, .ast-header-break-point.ast-theme-transparent-header .ast-below-header-menu a, .ast-header-break-point.ast-theme-transparent-header .ast-below-header-menu' );
	astra_color_responsive_css( 'transparent-below-header', 'astra-settings[transparent-menu-h-color-responsive]', 'color', '.ast-theme-transparent-header .ast-below-header-menu .menu-item:hover > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .menu-item:focus > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .menu-item.focus > .menu-link,.ast-theme-transparent-header .ast-below-header-menu .menu-item.current-menu-ancestor > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .menu-item.current-menu-item > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .menu-item.current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .menu-item.current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-ancestor:hover > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-ancestor:focus > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-ancestor.focus > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-item:hover > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-item:focus > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-item.focus > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-ancestor:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-ancestor:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-ancestor.focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-item:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-item.focus > .ast-menu-toggle' );
	// below Header SubMenu
	astra_color_responsive_css( 'transparent-below-header', 'astra-settings[transparent-submenu-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header .ast-below-header-menu .sub-menu' );
	astra_color_responsive_css( 'transparent-below-header', 'astra-settings[transparent-submenu-color-responsive]', 'color', '.ast-theme-transparent-header .ast-below-header-menu .sub-menu, .ast-theme-transparent-header .ast-below-header-menu .sub-menu a' );
	astra_color_responsive_css( 'transparent-below-header', 'astra-settings[transparent-submenu-h-color-responsive]', 'color', '.ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item:hover > .menu-item, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item:focus > .menu-item, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.focus > .menu-item,.ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-ancestor > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-item > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-ancestor:hover > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-ancestor:focus > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-ancestor.focus > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-item:hover > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-item:focus > .menu-link, .ast-theme-transparent-header .ast-below-header-menu .sub-menu .menu-item.current-menu-item.focus > .menu-link' );

	// below Header Content Section text color
	astra_color_responsive_css( 'transparent-below-header', 'astra-settings[transparent-content-section-text-color-responsive]', 'color', '', '.ast-theme-transparent-header .below-header-user-select, .ast-theme-transparent-header .below-header-user-select .widget,.ast-theme-transparent-header .below-header-user-select .widget-title' );
	// below Header Content Section link color
	astra_color_responsive_css( 'transparent-below-header', 'astra-settings[transparent-content-section-link-color-responsive]', 'color', '', '.ast-theme-transparent-header .below-header-user-select a, .ast-theme-transparent-header .below-header-user-select .widget a' );
	// below Header Content Section link hover color
	astra_color_responsive_css( 'below-transparent-header', 'astra-settings[transparent-content-section-link-h-color-responsive]', 'color', '.ast-theme-transparent-header .below-header-user-select a:hover, .ast-theme-transparent-header .below-header-user-select .widget a:hover' );

	/**
	 * Button border
	 */
	wp.customize( 'astra-settings[primary-header-button-border-group]', function( value ) {
		value.bind( function( value ) {

			var optionValue = JSON.parse(value);
			var border =  optionValue['header-main-rt-section-button-border-size'];

			if( '' != border.top || '' != border.right || '' != border.bottom || '' != border.left ) {
				var dynamicStyle = '.main-header-bar .ast-container .button-custom-menu-item .ast-custom-button-link .ast-custom-button';
					dynamicStyle += '{';
					dynamicStyle += 'border-top-width:'  + border.top + 'px;';
					dynamicStyle += 'border-right-width:'  + border.right + 'px;';
					dynamicStyle += 'border-left-width:'   + border.left + 'px;';
					dynamicStyle += 'border-bottom-width:'   + border.bottom + 'px;';
					dynamicStyle += 'border-style: solid;';
					dynamicStyle += '}';

				astra_add_dynamic_css( 'header-main-rt-section-button-border-size', dynamicStyle );
			}

		} );
	} );

	astra_css( 'astra-settings[header-main-rt-trans-section-button-text-color]', 'color', '.ast-theme-transparent-header .main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button' );
	astra_css( 'astra-settings[header-main-rt-trans-section-button-back-color]', 'background-color', '.ast-theme-transparent-header .main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button' );
	astra_css( 'astra-settings[header-main-rt-trans-section-button-text-h-color]', 'color', '.ast-theme-transparent-header .main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button:hover' );
	astra_css( 'astra-settings[header-main-rt-trans-section-button-back-h-color]', 'background-color', '.ast-theme-transparent-header .main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button:hover' );
	astra_css( 'astra-settings[header-main-rt-trans-section-button-border-radius]', 'border-radius', '.ast-theme-transparent-header .main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button', 'px' );
	astra_css( 'astra-settings[header-main-rt-trans-section-button-border-color]', 'border-color', '.ast-theme-transparent-header .main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button' );
	astra_css( 'astra-settings[header-main-rt-trans-section-button-border-h-color]', 'border-color', '.ast-theme-transparent-header .main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button:hover' );
	astra_responsive_spacing( 'astra-settings[header-main-rt-trans-section-button-padding]','.ast-theme-transparent-header .main-header-bar .button-custom-menu-item .ast-custom-button-link .ast-custom-button', 'padding', ['top', 'right', 'bottom', 'left' ] );

	/**
	 * Transparent Header > Elements preview styles.
	 */
	astra_css( 'astra-settings[transparent-header-divider-color]', 'border-color', '.ast-theme-transparent-header .ast-header-divider-element .ast-divider-wrapper' );
	astra_css( 'astra-settings[transparent-header-html-text-color]', 'color', '.ast-theme-transparent-header [CLASS*="ast-header-html-"] .ast-builder-html-element' );
	astra_css( 'astra-settings[transparent-header-html-link-color]', 'color', '.ast-theme-transparent-header [CLASS*="ast-header-html-"] .ast-builder-html-element a' );
	astra_css( 'astra-settings[transparent-header-html-link-h-color]', 'color', '.ast-theme-transparent-header [CLASS*="ast-header-html-"] .ast-builder-html-element a:hover' );
	astra_css( 'astra-settings[transparent-header-search-icon-color]', 'color', '.ast-theme-transparent-header .ast-header-search .astra-search-icon, .ast-theme-transparent-header .ast-header-search .search-field::placeholder' );
	astra_css( 'astra-settings[transparent-header-search-box-placeholder-color]', 'color', '.ast-theme-transparent-header .ast-header-search .ast-search-menu-icon .search-field, .ast-theme-transparent-header .ast-header-search .ast-search-menu-icon .search-field::placeholder' );
	astra_css( 'astra-settings[transparent-header-search-box-background-color]', 'background-color', '.ast-theme-transparent-header .ast-header-search .ast-search-menu-icon .search-field, .ast-theme-transparent-header .ast-header-search .ast-search-menu-icon .search-form, .ast-theme-transparent-header .ast-header-search .ast-search-menu-icon .search-submit' );
	astra_color_responsive_css( 'transparent-header-social-color', 'astra-settings[transparent-header-social-icons-bg-color]', 'background', '.ast-theme-transparent-header .ast-header-social-wrap .ast-social-color-type-custom .ast-builder-social-element' );
	astra_color_responsive_css( 'transparent-header-social-color', 'astra-settings[transparent-header-social-icons-color]', 'fill', '.ast-theme-transparent-header .ast-header-social-wrap .ast-social-color-type-custom .ast-builder-social-element svg' );
	astra_color_responsive_css( 'transparent-header-social-color-label', 'astra-settings[transparent-header-social-icons-color]', 'color', '.ast-theme-transparent-header .ast-header-social-wrap .ast-social-color-type-custom .ast-builder-social-element .social-item-label' );
	astra_color_responsive_css( 'transparent-header-social-color', 'astra-settings[transparent-header-social-icons-bg-h-color]', 'background', '.ast-theme-transparent-header .ast-header-social-wrap .ast-social-color-type-custom .ast-builder-social-element:hover' );
	astra_color_responsive_css( 'transparent-header-social-color', 'astra-settings[transparent-header-social-icons-h-color]', 'fill', '.ast-theme-transparent-header .ast-header-social-wrap .ast-social-color-type-custom .ast-builder-social-element:hover svg' );
	astra_color_responsive_css( 'transparent-header-social-color-label-h', 'astra-settings[transparent-header-social-icons-h-color]', 'color', '.ast-theme-transparent-header .ast-header-social-wrap .ast-social-color-type-custom .ast-builder-social-element:hover .social-item-label' );
	astra_css( 'astra-settings[transparent-header-widget-title-color]', 'color', '.ast-theme-transparent-header .widget-area.header-widget-area .widget-title' );
	astra_css( 'astra-settings[transparent-header-widget-content-color]', 'color', '.ast-theme-transparent-header .widget-area.header-widget-area .header-widget-area-inner' );
	astra_css( 'astra-settings[transparent-header-widget-link-color]', 'color', '.ast-theme-transparent-header .widget-area.header-widget-area .header-widget-area-inner a' );
	astra_css( 'astra-settings[transparent-header-widget-link-h-color]', 'color', '.ast-theme-transparent-header .widget-area.header-widget-area .header-widget-area-inner a:hover' );
	astra_css( 'astra-settings[transparent-header-button-text-color]', 'color', '.ast-theme-transparent-header [CLASS*="ast-header-button-"] .ast-custom-button' );
	astra_css( 'astra-settings[transparent-header-button-bg-color]', 'background', '.ast-theme-transparent-header [CLASS*="ast-header-button-"] .ast-custom-button' );
	astra_css( 'astra-settings[transparent-header-button-text-h-color]', 'color', '.ast-theme-transparent-header [CLASS*="ast-header-button-"] ..ast-custom-button:hover' );
	astra_css( 'astra-settings[transparent-header-button-bg-h-color]', 'background', '.ast-theme-transparent-header [CLASS*="ast-header-button-"] .ast-custom-button:hover' );

	/**
	 * Transparent Header menu-toggle Dynamic CSS.
	 */
	var toggle_selector = '.ast-theme-transparent-header [data-section="section-header-mobile-trigger"]';

	// Trigger Icon Color.
	astra_css(
		'astra-settings[transparent-header-toggle-btn-color]',
		'fill',
		toggle_selector + ' .ast-button-wrap .mobile-menu-toggle-icon .ast-mobile-svg'
	);

	// Trigger Label Color.
	astra_css(
		'astra-settings[transparent-header-toggle-btn-color]',
		'color',
		toggle_selector + ' .ast-button-wrap .mobile-menu-wrap .mobile-menu'
	);

	// Trigger Button Background Color.
	astra_css(
		'astra-settings[transparent-header-toggle-btn-bg-color]',
		'background',
		toggle_selector + ' .ast-button-wrap .menu-toggle.ast-mobile-menu-trigger-fill'
	);

	// Border Color.
	astra_css(
		'astra-settings[transparent-header-toggle-border-color]',
		'border-color',
		toggle_selector + ' .ast-button-wrap .menu-toggle.ast-mobile-menu-trigger-outline'
	);

	// Icon Color.
	astra_css(
		'astra-settings[transparent-account-icon-color]',
		'fill',
		'.ast-theme-transparent-header .ast-header-account-wrap .ast-header-account-type-icon .ahfb-svg-iconset svg path:not(.ast-hf-account-unfill), .ast-theme-transparent-header .ast-header-account-wrap .ast-header-account-type-icon .ahfb-svg-iconset svg circle'
	);

	// logged out text Color.
	astra_css(
		'astra-settings[transparent-account-type-text-color]',
		'color',
		'.ast-theme-transparent-header .ast-header-account-wrap .ast-header-account-text'
	);

	// Menu - Normal Color
	astra_color_responsive_css(
		'transparent-astra-account-menu-color-preview',
		'astra-settings[transparent-account-menu-color-responsive]',
		'color',
		'.ast-theme-transparent-header .ast-header-account-wrap .main-header-menu .menu-item > .menu-link'
	);

	// Menu - Hover Color
	astra_color_responsive_css(
		'transparent-astra-account-menu-h-color-preview',
		'astra-settings[transparent-account-menu-h-color-responsive]',
		'color',
		'.ast-theme-transparent-header .ast-header-account-wrap .menu-item:hover > .menu-link'
	);

	// Menu - Active Color
	astra_color_responsive_css(
		'transparent-astra-account-menu-active-color-preview',
		'astra-settings[transparent-account-menu-a-color-responsive]',
		'color',
		'.ast-theme-transparent-header .ast-header-account-wrap .menu-item.current-menu-item > .menu-link'
	);

	// Menu - Hover Background
	astra_color_responsive_css(
		'transparent-astra-account-menu-bg-preview',
		'astra-settings[transparent-account-menu-bg-obj-responsive]',
		'background',
		'.ast-theme-transparent-header .ast-header-account-wrap .account-main-navigation ul'
	);

	// Menu - Hover Background
	astra_color_responsive_css(
		'transparent-astra-account-menu-bg-preview',
		'astra-settings[transparent-account-menu-h-bg-color-responsive]',
		'background',
		'.ast-theme-transparent-header .ast-header-account-wrap .menu-item:hover > .menu-link'
	);

	// Menu - Active Background
	astra_color_responsive_css(
		'transparent-astra-account-menu',
		'astra-settings[transparent-account-menu-a-bg-color-responsive]',
		'background',
		'.ast-theme-transparent-header .ast-header-account-wrap .menu-item.current-menu-item > .menu-link'
	);

} )( jQuery );
