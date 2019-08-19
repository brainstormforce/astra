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

			var dynamicStyle = ' body.ast-theme-transparent-header.ast-header-break-point .site-header { border-bottom-width: ' + border + 'px } ';

			dynamicStyle += 'body.ast-theme-transparent-header.ast-desktop .main-header-bar {';
			dynamicStyle += 'border-bottom-width: ' + border + 'px';
			dynamicStyle += '}';

			astra_add_dynamic_css( 'transparent-header-main-sep', dynamicStyle );

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

			if ( color ) {

				var dynamicStyle = ' body.ast-theme-transparent-header.ast-desktop .main-header-bar { border-bottom-color: ' + color + '; } ';
					dynamicStyle += ' body.ast-theme-transparent-header.ast-header-break-point .site-header { border-bottom-color: ' + color + '; } ';

				astra_add_dynamic_css( 'transparent-header-main-sep-color', dynamicStyle );
			}

		} );
	} );


	/* Transparent Header Colors */     
	astra_color_responsive_css( 'colors-background', 'astra-settings[primary-menu-a-bg-color-responsive]', 	'background-color', 	'.main-header-menu .current-menu-item > a, .main-header-menu .current-menu-ancestor > a, .main-header-menu .current_page_item > a,.ast-header-sections-navigation li.current-menu-item > a, .ast-above-header-menu-items li.current-menu-item > a,.ast-below-header-menu-items li.current-menu-item > a,.ast-header-sections-navigation li.current-menu-ancestor > a, .ast-above-header-menu-items li.current-menu-ancestor > a,.ast-below-header-menu-items li.current-menu-ancestor > a' );

	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-header-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header .main-header-bar, .ast-theme-transparent-header.ast-header-break-point .main-header-bar-wrap .main-header-menu, .ast-theme-transparent-header.ast-header-break-point .main-header-bar-wrap .main-header-bar' );
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-header-color-site-title-responsive]', 'color', '.ast-theme-transparent-header .site-title a, .ast-theme-transparent-header .site-title a:focus, .ast-theme-transparent-header .site-title a:hover, .ast-theme-transparent-header .site-title a:visited, .ast-theme-transparent-header .site-header .site-description' );
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-header-color-h-site-title-responsive]', 'color', '.ast-theme-transparent-header .site-header .site-title a:hover' );
	
	// Primary Menu
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-menu-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header .main-header-menu, .ast-theme-transparent-header.ast-header-break-point .main-header-bar .main-header-menu, .ast-flyout-menu-enable.ast-header-break-point.ast-theme-transparent-header .main-header-bar-navigation #site-navigation, .ast-fullscreen-menu-enable.ast-header-break-point.ast-theme-transparent-header .main-header-bar-navigation #site-navigation, .ast-flyout-menu-enable.ast-header-break-point .main-header-bar-navigation #site-navigation' );
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-menu-color-responsive]', 'color', '.ast-theme-transparent-header .main-header-menu, .ast-theme-transparent-header .main-header-menu a,.ast-theme-transparent-header .ast-masthead-custom-menu-items, .ast-theme-transparent-header .ast-masthead-custom-menu-items a,.ast-theme-transparent-header .main-header-menu li > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu li > .ast-menu-toggle' );
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-menu-h-color-responsive]', 'color', '.ast-theme-transparent-header .main-header-menu li:hover > a, .ast-theme-transparent-header .main-header-menu li:hover > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header .main-header-menu .focus > a, .ast-theme-transparent-header .main-header-menu .focus > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .current-menu-item > a, .ast-theme-transparent-header .main-header-menu .current-menu-ancestor > a, .ast-theme-transparent-header .main-header-menu .current_page_item > a, .ast-theme-transparent-header .main-header-menu .current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu .current_page_item > .ast-menu-toggle' );
	// Primary SubMenu
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-submenu-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header .main-header-menu ul.sub-menu, .ast-header-break-point.ast-theme-transparent-header .main-header-menu ul.sub-menu' );
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-submenu-color-responsive]', 'color', '.ast-theme-transparent-header .main-header-menu ul.sub-menu li a,.ast-theme-transparent-header .main-header-menu ul.sub-menu li > .ast-menu-toggle' );
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-submenu-h-color-responsive]', 'color', '.ast-theme-transparent-header .main-header-menu ul.sub-menu a:hover,.ast-theme-transparent-header .main-header-menu ul.sub-menu li:hover > a, .ast-theme-transparent-header .main-header-menu ul.sub-menu li.focus > a, .ast-theme-transparent-header .main-header-menu ul.sub-menu li.current-menu-item > a,	.ast-theme-transparent-header .main-header-menu ul.sub-menu li.current-menu-item > .ast-menu-toggle,.ast-theme-transparent-header .main-header-menu ul.sub-menu li:hover > .ast-menu-toggle, .ast-theme-transparent-header .main-header-menu ul.sub-menu li.focus > .ast-menu-toggle' );


	// Primary Content Section text color
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-content-section-text-color-responsive]', 'color', '.ast-theme-transparent-header div.ast-masthead-custom-menu-items, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget-title' );
	// Primary Content Section link color
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-content-section-link-color-responsive]', 'color', '.ast-theme-transparent-header div.ast-masthead-custom-menu-items a, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a' );
	// Primary Content Section link hover color
	astra_color_responsive_css( 'transparent-primary-header', 'astra-settings[transparent-content-section-link-h-color-responsive]', 'color', '.ast-theme-transparent-header div.ast-masthead-custom-menu-items a:hover, .ast-theme-transparent-header div.ast-masthead-custom-menu-items .widget a:hover' );



	// Above Header Menu
	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-header-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header .ast-above-header-wrap .ast-above-header' );

	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-menu-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header .ast-above-header-menu, .ast-theme-transparent-header.ast-header-break-point .ast-above-header-section-separated .ast-above-header-navigation ul, .ast-flyout-above-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation-wrap .ast-above-header-navigation, .ast-fullscreen-above-menu-enable.ast-header-break-point.ast-theme-transparent-header .ast-above-header-section-separated .ast-above-header-navigation-wrap' );
	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-menu-color-responsive]', 'color', '.ast-theme-transparent-header .ast-above-header-navigation a, .ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation a, .ast-header-break-point.ast-theme-transparent-header .ast-above-header-navigation > ul.ast-above-header-menu > .menu-item-has-children:not(.current-menu-item) > .ast-menu-toggle' );
	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-menu-h-color-responsive]', 'color', '.ast-theme-transparent-header .ast-above-header-navigation li.current-menu-item > a,.ast-theme-transparent-header .ast-above-header-navigation li.current-menu-ancestor > a, .ast-theme-transparent-header .ast-above-header-navigation li:hover > a' )
	// Above Header SubMenu
	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-submenu-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header .ast-above-header-menu .sub-menu' );
	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-submenu-color-responsive]', 'color', '.ast-theme-transparent-header .ast-above-header-menu .sub-menu, .ast-theme-transparent-header .ast-above-header-navigation .ast-above-header-menu .sub-menu a' );
	astra_color_responsive_css( 'transparent-above-header', 'astra-settings[transparent-submenu-h-color-responsive]', 'color', '.ast-theme-transparent-header .ast-above-header-menu .sub-menu li:hover > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li:focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.focus > a,.ast-theme-transparent-header .ast-above-header-menu .sub-menu li:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.focus > .ast-menu-toggle,.ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item.focus > .ast-menu-toggle,.ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:hover > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item:focus > a, .ast-theme-transparent-header .ast-above-header-menu .sub-menu li.current-menu-item.focus > a' );

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
	astra_color_responsive_css( 'transparent-below-header', 'astra-settings[transparent-menu-h-color-responsive]', 'color', '.ast-theme-transparent-header .ast-below-header-menu li:hover > a, .ast-theme-transparent-header .ast-below-header-menu li:focus > a, .ast-theme-transparent-header .ast-below-header-menu li.focus > a,.ast-theme-transparent-header .ast-below-header-menu li.current-menu-ancestor > a, .ast-theme-transparent-header .ast-below-header-menu li.current-menu-item > a, .ast-theme-transparent-header .ast-below-header-menu li.current-menu-ancestor > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu li.current-menu-item > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item.focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:hover > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:focus > .ast-menu-toggle, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item.focus > .ast-menu-toggle' );
	// below Header SubMenu
	astra_color_responsive_css( 'transparent-below-header', 'astra-settings[transparent-submenu-bg-color-responsive]', 'background-color', '.ast-theme-transparent-header .ast-below-header-menu .sub-menu' );
	astra_color_responsive_css( 'transparent-below-header', 'astra-settings[transparent-submenu-color-responsive]', 'color', '.ast-theme-transparent-header .ast-below-header-menu .sub-menu, .ast-theme-transparent-header .ast-below-header-menu .sub-menu a' );
	astra_color_responsive_css( 'transparent-below-header', 'astra-settings[transparent-submenu-h-color-responsive]', 'color', '.ast-theme-transparent-header .ast-below-header-menu .sub-menu li:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.focus > a,.ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-ancestor.focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:hover > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item:focus > a, .ast-theme-transparent-header .ast-below-header-menu .sub-menu li.current-menu-item.focus > a' );

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

} )( jQuery );
