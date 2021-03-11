/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package Astra
 * @since 3.0.0
 */

( function( $ ) {

	var tablet_break_point    = AstraBuilderMenuData.tablet_break_point || 768,
		mobile_break_point    = AstraBuilderMenuData.mobile_break_point || 544;

    var selector = '.ast-builder-menu-mobile .main-navigation';
    var section = 'section-header-mobile-menu';

    // Advanced Visibility CSS Generation.
    astra_builder_visibility_css( section, selector, 'block' );

    /**
     * Typography CSS.
     */

        // Menu Typography.
        astra_generate_outside_font_family_css(
            'astra-settings[header-mobile-menu-font-family]',
            selector + ' .menu-item > .menu-link'
        );
        astra_generate_font_weight_css(
            'astra-settings[header-mobile-menu-font-family]',
            'astra-settings[header-mobile-menu-font-weight]',
            'font-weight',
            selector + ' .menu-item > .menu-link'
        );
        astra_css(
            'astra-settings[header-mobile-menu-text-transform]',
            'text-transform',
            selector + ' .menu-item > .menu-link'
        );
        astra_responsive_font_size(
            'astra-settings[header-mobile-menu-font-size]',
            selector + ' .menu-item > .menu-link'
        );
        astra_css(
            'astra-settings[header-mobile-menu-line-height]',
            'line-height',
            selector + ' .menu-item > .menu-link'
        );
        astra_css(
            'astra-settings[header-mobile-menu-letter-spacing]',
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
            'astra-menu-color-preview',
            'astra-settings[header-mobile-menu-color-responsive]',
            'color',
            selector + ' .main-header-menu .menu-item > .menu-link'
        );

        // Menu - Hover Color
        astra_color_responsive_css(
            'astra-menu-h-color-preview',
            'astra-settings[header-mobile-menu-h-color-responsive]',
            'color',
            selector + ' .menu-item:hover > .menu-link, ' + selector + ' .inline-on-mobile .menu-item:hover > .ast-menu-toggle'
        );

        // Menu Toggle -  Color
        astra_color_responsive_css(
            'astra-builder-toggle',
            'astra-settings[header-mobile-menu-color-responsive]',
            'color',
            selector + ' .menu-item > .ast-menu-toggle'
        );

        // Menu Toggle - Hover Color
        astra_color_responsive_css(
            'astra-menu-h-toogle-color-preview',
            'astra-settings[header-mobile-menu-h-color-responsive]',
            'color',
            selector + ' .menu-item:hover > .ast-menu-toggle'
        );
        // Menu - Active Color
        astra_color_responsive_css(
            'astra-menu-active-color-preview',
            'astra-settings[header-mobile-menu-a-color-responsive]',
            'color',
            selector + ' .menu-item.current-menu-item > .menu-link, ' + selector + ' .inline-on-mobile .menu-item.current-menu-item > .ast-menu-toggle'
        );

        // Menu - Normal Background
        astra_apply_responsive_background_css( 'astra-settings[header-mobile-menu-bg-obj-responsive]', selector + ' .main-header-menu, ' + selector + ' .main-header-menu .sub-menu', 'desktop' );
        astra_apply_responsive_background_css( 'astra-settings[header-mobile-menu-bg-obj-responsive]', selector + ' .main-header-menu, ' + selector + ' .main-header-menu .sub-menu', 'tablet' );
        astra_apply_responsive_background_css( 'astra-settings[header-mobile-menu-bg-obj-responsive]', selector + ' .main-header-menu, ' + selector + ' .main-header-menu .sub-menu', 'mobile' );

        // Menu - Hover Background
        astra_color_responsive_css(
            'astra-menu-bg-preview',
            'astra-settings[header-mobile-menu-h-bg-color-responsive]',
            'background',
            selector + ' .menu-item:hover > .menu-link, ' + selector + ' .inline-on-mobile .menu-item:hover > .ast-menu-toggle'
        );

        // Menu - Active Background
        astra_color_responsive_css(
            'astra-builder',
            'astra-settings[header-mobile-menu-a-bg-color-responsive]',
            'background',
            selector + ' .menu-item.current-menu-item > .menu-link, ' + selector + ' .inline-on-mobile .menu-item.current-menu-item > .ast-menu-toggle'
        );

    /**
     * Border CSS.
     */

        (function () {

            // Sub Menu - Divider Size.
            wp.customize( 'astra-settings[header-mobile-menu-submenu-item-b-size]', function( value ) {
                value.bind( function( borderSize ) {
                    var selector = '.ast-header-break-point .ast-builder-menu-mobile .main-navigation';
                    var dynamicStyle = '';
                    dynamicStyle += selector + ' .main-header-menu {';
                    dynamicStyle += 'border-top-width: ' + borderSize + 'px;';
                    dynamicStyle += '} ';
                    dynamicStyle += selector + ' .menu-item .sub-menu .menu-link, ' + selector + ' .menu-item .menu-link {';
                    dynamicStyle += 'border-bottom-width: ' + borderSize + 'px;';
                    dynamicStyle += '} ';
                    astra_add_dynamic_css( 'header-mobile-menu-submenu-item-b-size', dynamicStyle );
                } );
            } );

            // Menu 1 > Sub Menu Border Size.
            wp.customize( 'astra-settings[header-mobile-menu-submenu-border]', function( setting ) {
                setting.bind( function( border ) {

                    var dynamicStyle = '.ast-builder-menu-mobile  .sub-menu {';
                    dynamicStyle += 'border-top-width:'  + border.top + 'px;';
                    dynamicStyle += 'border-right-width:'  + border.right + 'px;';
                    dynamicStyle += 'border-left-width:'   + border.left + 'px;';
                    dynamicStyle += 'border-style: solid;';
                    dynamicStyle += 'border-bottom-width:'   + border.bottom + 'px;';

                    dynamicStyle += '}';
                    astra_add_dynamic_css( 'header-mobile-menu-submenu-border', dynamicStyle );

                } );
            } );

            // Menu Spacing - Menu 1.
            wp.customize( 'astra-settings[header-mobile-menu-menu-spacing]', function( value ) {
                value.bind( function( padding ) {
                    var dynamicStyle = '';
                    dynamicStyle += '.ast-builder-menu-mobile .main-header-menu .menu-item > .menu-link {';
                    dynamicStyle += 'padding-left: ' + padding['desktop']['left'] + padding['desktop-unit'] + ';';
                    dynamicStyle += 'padding-right: ' + padding['desktop']['right'] + padding['desktop-unit'] + ';';
                    dynamicStyle += 'padding-top: ' + padding['desktop']['top'] + padding['desktop-unit'] + ';';
                    dynamicStyle += 'padding-bottom: ' + padding['desktop']['bottom'] + padding['desktop-unit'] + ';';
                    dynamicStyle += '} ';

                    dynamicStyle +=  '@media (max-width: ' + tablet_break_point + 'px) {';
                    dynamicStyle += '.ast-header-break-point .ast-builder-menu-mobile .main-header-menu .menu-item > .menu-link {';
                    dynamicStyle += 'padding-left: ' + padding['tablet']['left'] + padding['tablet-unit'] + ';';
                    dynamicStyle += 'padding-right: ' + padding['tablet']['right'] + padding['tablet-unit'] + ';';
                    dynamicStyle += 'padding-top: ' + padding['tablet']['top'] + padding['tablet-unit'] + ';';
                    dynamicStyle += 'padding-bottom: ' + padding['tablet']['bottom'] + padding['tablet-unit'] + ';';
                    dynamicStyle += '} ';
                    // Toggle top.
                    dynamicStyle += '.astra-hfb-header .ast-builder-menu-mobile .main-navigation ul .menu-item.menu-item-has-children > .ast-menu-toggle {';
                    dynamicStyle += 'top: ' + padding['tablet']['top'] + padding['tablet-unit'] + ';';
                    dynamicStyle += 'right: calc( ' + padding['tablet']['right'] + padding['tablet-unit'] + ' - 0.907em );'
                    dynamicStyle += '} ';

                    dynamicStyle += '} ';

                    dynamicStyle +=  '@media (max-width: ' + mobile_break_point + 'px) {';
                    dynamicStyle += '.ast-header-break-point .ast-builder-menu-mobile .main-header-menu .menu-item > .menu-link {';
                    dynamicStyle += 'padding-left: ' + padding['mobile']['left'] + padding['mobile-unit'] + ';';
                    dynamicStyle += 'padding-right: ' + padding['mobile']['right'] + padding['mobile-unit'] + ';';
                    dynamicStyle += 'padding-top: ' + padding['mobile']['top'] + padding['mobile-unit'] + ';';
                    dynamicStyle += 'padding-bottom: ' + padding['mobile']['bottom'] + padding['mobile-unit'] + ';';
                    dynamicStyle += '} ';
                    // Toggle top.
                    dynamicStyle += '.astra-hfb-header .ast-builder-menu-mobile .main-navigation ul .menu-item.menu-item-has-children > .ast-menu-toggle {';
                    dynamicStyle += 'top: ' + padding['mobile']['top'] + padding['mobile-unit'] + ';';
                    dynamicStyle += 'right: calc( ' + padding['mobile']['right'] + padding['mobile-unit'] + ' - 0.907em );'
                    dynamicStyle += '} ';

                    dynamicStyle += '} ';

                    astra_add_dynamic_css( 'header-mobile-menu-menu-spacing-toggle-button', dynamicStyle );
                } );
            } );

            // Margin - Menu 1.
            wp.customize( 'astra-settings[section-header-mobile-menu-margin]', function( value ) {
                value.bind( function( margin ) {
                    var selector = '.ast-builder-menu-mobile .main-header-menu, .ast-header-break-point .ast-builder-menu-mobile .main-header-menu';
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
                    astra_add_dynamic_css( 'section-header-mobile-menu-margin', dynamicStyle );
                } );
            } );

            /**
             * Header Menu 1 > Submenu border Color
             */
            wp.customize('astra-settings[header-mobile-menu-submenu-item-b-color]', function (value) {
                value.bind(function (color) {
                    var insideBorder = wp.customize('astra-settings[header-mobile-menu-submenu-item-border]').get(),
                        borderSize = wp.customize('astra-settings[header-mobile-menu-submenu-item-b-size]').get();
                        console.log( borderSize );
                    if ('' != color) {
                        if ( true == insideBorder ) {

                            var dynamicStyle = '';

                            dynamicStyle += '.ast-header-break-point .ast-builder-menu-mobile .main-navigation .menu-item .sub-menu .menu-link, .ast-header-break-point .ast-builder-menu-mobile .main-navigation .menu-item .menu-link';
                            dynamicStyle += '{';
                            dynamicStyle += 'border-bottom-width:' + ( ( true === insideBorder ) ? borderSize + 'px;' : '0px;' );
                            dynamicStyle += 'border-color:' + color + ';';
                            dynamicStyle += 'border-style: solid;';
                            dynamicStyle += '}';
                            dynamicStyle += '.ast-header-break-point .ast-builder-menu-mobile .main-navigation .main-header-menu';
                            dynamicStyle += '{';
                            dynamicStyle += 'border-top-width:' + ( ( true === insideBorder ) ? borderSize + 'px;' : '0px;' );
                            dynamicStyle += 'border-color:' + color + ';';
                            dynamicStyle += '}';

                            astra_add_dynamic_css('header-mobile-menu-submenu-item-b-color', dynamicStyle);
                        }
                    } else {
                        wp.customize.preview.send('refresh');
                    }
                });
            });

            /**
             * Header Menu 1 > Submenu border Color
             */
            wp.customize( 'astra-settings[header-mobile-menu-submenu-item-border]', function( value ) {
                value.bind( function( border ) {
                    var color = wp.customize( 'astra-settings[header-mobile-menu-submenu-item-b-color]' ).get(),
                        borderSize = wp.customize('astra-settings[header-mobile-menu-submenu-item-b-size]').get();

                    if( true === border  ) {
                        var dynamicStyle = '.ast-header-break-point .ast-builder-menu-mobile .main-navigation .menu-item .sub-menu .menu-link, .ast-header-break-point .ast-builder-menu-mobile .main-navigation .menu-item .menu-link';
                        dynamicStyle += '{';
                        dynamicStyle += 'border-bottom-width:' + ( ( true === border ) ? borderSize + 'px;' : '0px;' );
                        dynamicStyle += 'border-color:'        + color + ';';
                        dynamicStyle += 'border-style: solid;';
                        dynamicStyle += '}';
                        dynamicStyle += '.ast-header-break-point .ast-builder-menu-mobile .main-navigation .main-header-menu';
                        dynamicStyle += '{';
                        dynamicStyle += 'border-top-width:' + ( ( true === border ) ? borderSize + 'px;' : '0px;' );
                        dynamicStyle += 'border-style: solid;';
                        dynamicStyle += 'border-color:' + color + ';';
                        dynamicStyle += '}';

                        astra_add_dynamic_css( 'header-mobile-menu-submenu-item-border', dynamicStyle );
                    } else {
                        wp.customize.preview.send( 'refresh' );
                    }

                } );
            } );
        })();


        // Sub Menu - Border Color.
        astra_css(
            'astra-settings[header-mobile-menu-submenu-b-color]',
            'border-color',
            selector + ' li.menu-item .sub-menu, ' + selector + ' .main-header-menu'
        );

	// Transparent header > Submenu link hover color.
	astra_color_responsive_css( 'astra-builder-transparent-submenu', 'astra-settings[transparent-submenu-h-color-responsive]', 'color', '.ast-theme-transparent-header .main-header-menu .menu-item .sub-menu .menu-item:hover > .menu-link' );

} )( jQuery );
