export function astraGetColor( control ) {
	'use strict';
	jQuery(document).mouseup(function(e){
		var container = jQuery(control);
		var colorWrap = container.find('.astra-color-picker-wrap');
		// If the target of the click isn't the container nor a descendant of the container.
		if (!colorWrap.is(e.target) && colorWrap.has(e.target).length === 0){
			container.find('.components-button.astra-color-icon-indicate.open').click();
		}
	});
}
export function astraGetBackground( control ) {
	'use strict';
	jQuery(document).mouseup(function(e){
		var container = jQuery(control);
		var bgWrap = container.find('.background-wrapper');
		// If the target of the click isn't the container nor a descendant of the container.
		if (!bgWrap.is(e.target) && bgWrap.has(e.target).length === 0){
			container.find('.components-button.astra-color-icon-indicate.open').click();
		}
	});
}
export function astraGetResponsiveBgJs( control, child_control_name ) {
    'use strict';

    jQuery('html').addClass('responsive-background-img-ready');

    let device = jQuery('.wp-full-overlay-footer .devices button.active').attr('data-device')

    jQuery( '.customize-control-ast-responsive-background .customize-control-content .background-container' ).removeClass( 'active' );

    jQuery( '.customize-control-ast-responsive-background .customize-control-content .background-container.' + device ).addClass( 'active' );

    jQuery( '.customize-control-ast-responsive-background .ast-responsive-btns li' ).removeClass( 'active' );

    jQuery( '.customize-control-ast-responsive-background .ast-responsive-btns li.' + device ).addClass( 'active' );

    jQuery('.wp-full-overlay-footer .devices button').on('click', function() {

        let device = jQuery(this).attr('data-device');

        jQuery( '.customize-control-ast-responsive-background .customize-control-content .background-container' ).removeClass( 'active' );
        jQuery( '.customize-control-ast-responsive-background .customize-control-content .background-container.' + device ).addClass( 'active' );
        jQuery( '.customize-control-ast-responsive-background .ast-responsive-btns li' ).removeClass( 'active' );
        jQuery( '.customize-control-ast-responsive-background .ast-responsive-btns li.' + device ).addClass( 'active' );
    });

    control.container.find( '.ast-responsive-btns button' ).on( 'click', function( event ) {
        event.preventDefault();
        let device = jQuery(this).attr('data-device');
        if( 'desktop' == device ) {
            device = 'tablet';
        } else if( 'tablet' == device ) {
            device = 'mobile';
        } else {
            device = 'desktop';
        }

        jQuery( '.wp-full-overlay-footer .devices button[data-device="' + device + '"]' ).trigger( 'click' );
	});
	if (child_control_name) {
		jQuery(document).mouseup(function(e){
			var container = jQuery(child_control_name);
			var bgWrap = container.find('.background-wrapper');
			// If the target of the click isn't the container nor a descendant of the container.
			if (!bgWrap.is(e.target) && bgWrap.has(e.target).length === 0){
				container.find('.components-button.astra-color-icon-indicate.open').click();
			}
		});
	}
}
export function astraGetResponsiveColorJs( control, child_control_name ) {
    'use strict';

    jQuery('html').addClass('responsive-background-color-ready');

    let device = jQuery('.wp-full-overlay-footer .devices button.active').attr('data-device')

    jQuery( '.customize-control-ast-responsive-color .customize-control-content .ast-color-picker-alpha' ).removeClass( 'active' );

    jQuery( '.customize-control-ast-responsive-color .customize-control-content .ast-color-picker-alpha.' + device ).addClass( 'active' );

    jQuery( '.customize-control-ast-responsive-color .ast-responsive-btns li' ).removeClass( 'active' );

    jQuery( '.customize-control-ast-responsive-color .ast-responsive-btns li.' + device ).addClass( 'active' );

    jQuery('.wp-full-overlay-footer .devices button').on('click', function() {

        let device = jQuery(this).attr('data-device');

        jQuery( '.customize-control-ast-responsive-color .customize-control-content .ast-color-picker-alpha' ).removeClass( 'active' );
        jQuery( '.customize-control-ast-responsive-color .customize-control-content .ast-responsive-color.' + device ).addClass( 'active' );
        jQuery( '.customize-control-ast-responsive-color .ast-responsive-btns li' ).removeClass( 'active' );
        jQuery( '.customize-control-ast-responsive-color .ast-responsive-btns li.' + device ).addClass( 'active' );
    });

    control.container.find( '.ast-responsive-btns button' ).on( 'click', function( event ) {
        event.preventDefault();
        let device = jQuery(this).attr('data-device');
        if( 'desktop' == device ) {
            device = 'tablet';
        } else if( 'tablet' == device ) {
            device = 'mobile';
        } else {
            device = 'desktop';
        }

        jQuery( '.wp-full-overlay-footer .devices button[data-device="' + device + '"]' ).trigger( 'click' );
	});
	if (child_control_name) {
		jQuery(document).mouseup(function(e){
			var container = jQuery(child_control_name);
			var resColorWrap = container.find('.customize-control-content');
			// If the target of the click isn't the container nor a descendant of the container.
			if (!resColorWrap.is(e.target) && resColorWrap.has(e.target).length === 0){
				container.find('.components-button.astra-color-icon-indicate.open').click();
			}
		});
	}
}
export function astraGetResponsiveJs ( control ) {
    'use strict';

    let device = jQuery('.wp-full-overlay-footer .devices button.active').attr('data-device')

    jQuery( '.customize-control-ast-responsive .input-wrapper input' ).removeClass( 'active' );

    jQuery( '.customize-control-ast-responsive .input-wrapper input.' + device ).addClass( 'active' );

    jQuery( '.customize-control-ast-responsive .ast-responsive-btns li' ).removeClass( 'active' );

    jQuery( '.customize-control-ast-responsive .ast-responsive-btns li.' + device ).addClass( 'active' );

    jQuery('.wp-full-overlay-footer .devices button').on('click', function() {

        let device = jQuery(this).attr('data-device');

        jQuery( '.customize-control-ast-responsive .input-wrapper input, .customize-control .ast-responsive-btns > li' ).removeClass( 'active' );
        jQuery( '.customize-control-ast-responsive .input-wrapper input.' + device + ', .customize-control .ast-responsive-btns > li.' + device ).addClass( 'active' );

    });

    control.container.find( '.ast-responsive-btns button' ).on( 'click', function( event ) {
        event.preventDefault();
        let device = jQuery(this).attr('data-device');
        if( 'desktop' == device ) {
            device = 'tablet';
        } else if( 'tablet' == device ) {
            device = 'mobile';
        } else {
            device = 'desktop';
        }

        jQuery( '.wp-full-overlay-footer .devices button[data-device="' + device + '"]' ).trigger( 'click' );
    });
}
export function astraGetResponsiveSliderJs ( control ) {
    'use strict';

    let device = jQuery('.wp-full-overlay-footer .devices button.active').attr('data-device')

    jQuery( '.customize-control-ast-responsive-slider .input-field-wrapper' ).removeClass( 'active' );

    jQuery( '.customize-control-ast-responsive-slider .input-field-wrapper.' + device ).addClass( 'active' );

    jQuery( '.customize-control-ast-responsive-slider .ast-responsive-slider-btns li' ).removeClass( 'active' );

    jQuery( '.customize-control-ast-responsive-slider .ast-responsive-slider-btns li.' + device ).addClass( 'active' );

    jQuery('.wp-full-overlay-footer .devices button').on('click', function() {

        let device = jQuery(this).attr('data-device');

        jQuery( '.customize-control-ast-responsive-slider .input-field-wrapper, .customize-control .ast-responsive-slider-btns > li' ).removeClass( 'active' );
        jQuery( '.customize-control-ast-responsive-slider .input-field-wrapper.' + device + ', .customize-control .ast-responsive-slider-btns > li.' + device ).addClass( 'active' );
    });

    control.container.find( '.ast-responsive-slider-btns button' ).on( 'click', function( event ) {
        event.preventDefault();
        let device = jQuery(this).attr('data-device');
        if( 'desktop' == device ) {
            device = 'tablet';
        } else if( 'tablet' == device ) {
            device = 'mobile';
        } else {
            device = 'desktop';
        }

        jQuery( '.wp-full-overlay-footer .devices button[data-device="' + device + '"]' ).trigger( 'click' );
    });
}
export function astraGetResponsiveSpacingJs ( control ) {
    'use strict';

    let device = jQuery('.wp-full-overlay-footer .devices button.active').attr('data-device')

    jQuery( '.customize-control-ast-responsive-spacing .input-wrapper .ast-spacing-wrapper' ).removeClass( 'active' );

    jQuery( '.customize-control-ast-responsive-spacing .input-wrapper .ast-spacing-wrapper.' + device ).addClass( 'active' );

    jQuery( '.customize-control-ast-responsive-spacing .ast-spacing-responsive-btns li' ).removeClass( 'active' );

    jQuery( '.customize-control-ast-responsive-spacing .ast-spacing-responsive-btns li.' + device ).addClass( 'active' );

    jQuery('.wp-full-overlay-footer .devices button').on('click', function() {

        let device = jQuery(this).attr('data-device');

        jQuery( '.customize-control-ast-responsive-spacing .input-wrapper .ast-spacing-wrapper, .customize-control .ast-spacing-responsive-btns > li' ).removeClass( 'active' );
        jQuery( '.customize-control-ast-responsive-spacing .input-wrapper .ast-spacing-wrapper.' + device + ', .customize-control .ast-spacing-responsive-btns > li.' + device ).addClass( 'active' );
    });

    control.container.find( '.ast-spacing-responsive-btns button' ).on( 'click', function( event ) {
        event.preventDefault();
        let device = jQuery(this).attr('data-device');
        if( 'desktop' == device ) {
            device = 'tablet';
        } else if( 'tablet' == device ) {
            device = 'mobile';
        } else {
            device = 'desktop';
        }

        jQuery( '.wp-full-overlay-footer .devices button[data-device="' + device + '"]' ).trigger( 'click' );
    });
}
