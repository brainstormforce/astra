import SettingsGroupComponent from './settings-group-component';
import BorderComponent from '../border/border-component';
import ResponsiveComponent from '../responsive/responsive-component';
import ResponsiveSliderComponent from '../responsive-slider/responsive-slider-component';
import ResponsiveSpacingComponent from '../responsive-spacing/responsive-spacing-component';
import SliderComponent from '../slider/slider-component';
import Background from '../background/background';
import ResponsiveBackground from '../responsive-background/responsive-background';
import ColorComponent from '../color/color-component';
import ResponsiveColorComponent from '../responsive-color/responsive-color-component';
import SelectComponent from '../select/select-component';
import DividerComponent from '../divider/divider-component';

import {
	astraGetBackground,
	astraGetColor,
	astraGetResponsiveBgJs,
	astraGetResponsiveColorJs,
	astraGetResponsiveJs,
	astraGetResponsiveSliderJs,
	astraGetResponsiveSpacingJs
} from '../common/responsive-helper';

export const settingsGroupControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
	ReactDOM.render( <SettingsGroupComponent control={ control } />, control.container[0] );
	},
	ready : function() {
		'use strict';

		var control = this,
		value   = control.setting._value;

		control.registerToggleEvents();
		this.container.on( 'ast_settings_changed', control.onOptionChange );

		var last_scroll_top = 0;
        var parentSection   = jQuery( '.wp-full-overlay-sidebar-content' );
        var browser = navigator.userAgent.toLowerCase();
        if ( ! ( browser.indexOf( 'firefox' ) > -1 ) ) {
        var parent_width_remove = 6;
        } else {
        var parent_width_remove = 16;
		}

        jQuery('#customize-controls .wp-full-overlay-sidebar-content .control-section').on( 'scroll', function (event) {
            var $this = jQuery(this);
            // Run sticky js for only open section.
            if ( $this.hasClass( 'open' ) ) {
                var section_title = $this.find( '.customize-section-title' );
                var scroll_top    = $this.scrollTop();
                if ( scroll_top > last_scroll_top ) {
                    // On scroll down, remove sticky section title.
                    section_title.removeClass( 'maybe-sticky' ).removeClass( 'is-in-view' ).removeClass( 'is-sticky' );
                    $this.css( 'padding-top', '' );
                } else {
                    // On scroll up, add sticky section title.
                    var parent_width = $this.outerWidth();
                    section_title.addClass( 'maybe-sticky' ).addClass( 'is-in-view' ).addClass( 'is-sticky' ).width( parent_width - parent_width_remove ).css( 'top', parentSection.css( 'top' ) );
                    if ( ! ( browser.indexOf( 'firefox' ) > -1 ) ) {
                        $this.css( 'padding-top', section_title.height() );
                    }
                    if( scroll_top === 0 ) {
                        // Remove sticky section heading when scrolled to the top.
                        section_title.removeClass( 'maybe-sticky' ).removeClass( 'is-in-view' ).removeClass( 'is-sticky' );
                        $this.css( 'padding-top', '' );
                    }
                }
                last_scroll_top = scroll_top;
            }
        });
	},
	registerToggleEvents: function() {

		var control = this;

		/* Close popup when click outside anywhere outside of popup */
		jQuery( '.wp-full-overlay-sidebar-content, .wp-picker-container' ).click( function( e ) {
			if ( ! jQuery( e.target ).closest( '.ast-field-settings-modal' ).length ) {
				jQuery( '.ast-adv-toggle-icon.open' ).trigger( 'click' );
			}
		});

		control.container.on( 'click', '.ast-toggle-desc-wrap .ast-adv-toggle-icon', function( e ) {

			e.preventDefault();
			e.stopPropagation();

			var $this = jQuery(this);

			var parent_wrap = $this.closest( '.customize-control-ast-settings-group' );
			var is_loaded = parent_wrap.find( '.ast-field-settings-modal' ).data('loaded');
			var parent_section = parent_wrap.parents('.control-section');

			if( $this.hasClass('open') ) {
				parent_wrap.find( '.ast-field-settings-modal' ).hide();
			} else {
				/* Close popup when another popup is clicked to open */
				var get_open_popup = parent_section.find('.ast-adv-toggle-icon.open');
				if( get_open_popup.length > 0 ) {
					get_open_popup.trigger('click');
				}
				if( is_loaded ) {
					parent_wrap.find( '.ast-field-settings-modal' ).show();
				} else {
					var fields = control.params.ast_fields;

					var $modal_wrap = jQuery( astra.customizer.group_modal_tmpl );

					parent_wrap.find( '.ast-field-settings-wrap' ).append( $modal_wrap );
					parent_wrap.find( '.ast-fields-wrap' ).attr( 'data-control', control.params.name );
					control.ast_render_field( parent_wrap, fields, control );

					parent_wrap.find( '.ast-field-settings-modal' ).show();

					var device = jQuery("#customize-footer-actions .active").attr('data-device');

					if( 'mobile' == device ) {
						jQuery('.ast-responsive-btns .mobile, .ast-responsive-slider-btns .mobile').addClass('active');
						jQuery('.ast-responsive-btns .preview-mobile, .ast-responsive-slider-btns .preview-mobile').addClass('active');
					} else if( 'tablet' == device ) {
						jQuery('.ast-responsive-btns .tablet, .ast-responsive-slider-btns .tablet').addClass('active');
						jQuery('.ast-responsive-btns .preview-tablet, .ast-responsive-slider-btns .preview-tablet').addClass('active');
					} else {
						jQuery('.ast-responsive-btns .desktop, .ast-responsive-slider-btns .desktop').addClass('active');
						jQuery('.ast-responsive-btns .preview-desktop, .ast-responsive-slider-btns .preview-desktop').addClass('active');
					}
				}
			}

			$this.toggleClass('open');

		});

		control.container.on( "click", ".ast-toggle-desc-wrap > .customizer-text", function( e ) {

			e.preventDefault();
			e.stopPropagation();

			jQuery(this).find( '.ast-adv-toggle-icon' ).trigger('click');
		});
	},
	ast_render_field: function( wrap, fields, control_elem ) {

		var control = this;
		var ast_field_wrap = wrap.find( '.ast-fields-wrap' );
		var fields_html = '';
		var control_types = [];
		var field_values = control.isJsonString( control_elem.params.value ) ? JSON.parse( control_elem.params.value ) : {};

		if( 'undefined' != typeof fields.tabs ) {

			var clean_param_name = control_elem.params.name.replace( '[', '-' ),
				clean_param_name = clean_param_name.replace( ']', '' );

			fields_html += '<div id="' + clean_param_name + '-tabs" class="ast-group-tabs">';
			fields_html += '<ul class="ast-group-list">';
			var counter = 0;

			_.each( fields.tabs, function ( value, key ) {

				var li_class = '';
				if( 0 == counter ) {
					li_class = "active";
				}

				fields_html += '<li class="'+ li_class + '"><a href="#tab-' + key + '"><span>' + key +  '</span></a></li>';
				counter++;
			});

			fields_html += '</ul>';

			fields_html += '<div class="ast-tab-content" >';

			_.each( fields.tabs, function ( fields_data, key ) {

				fields_html += '<div id="tab-'+ key +'" class="tab">';

				var result = control.generateFieldHtml( fields_data, field_values );

				fields_html += result.html;

				_.each( result.controls , function ( control_value, control_key ) {
					control_types.push({
						key: control_value.key,
						value : control_value.value,
						name  : control_value.name
					});
				});

				fields_html += '</div>';
			});

			fields_html += '</div></div>';

			ast_field_wrap.html( fields_html );

			control.renderReactControl( fields, control );

			jQuery( "#" + clean_param_name + "-tabs" ).tabs();

		} else {

			var result = control.generateFieldHtml( fields, field_values );

			fields_html += result.html;

			_.each( result.controls, function (control_value, control_key) {
				control_types.push({
					key: control_value.key,
					value: control_value.value,
					name: control_value.name
				});
			});

			ast_field_wrap.html(fields_html);

			control.renderReactControl( fields, control );
		}

		_.each( control_types, function( control_type, index ) {

			switch( control_type.key ) {

				case "ast-color":
					astraGetColor( "#customize-control-" + control_type.name )
					break;
				case "ast-background":
					astraGetBackground( "#customize-control-" + control_type.name )
					break;
				case "ast-responsive-background":
					astraGetResponsiveBgJs( control, "#customize-control-" + control_type.name )
					break;
				case "ast-responsive-color":
					astraGetResponsiveColorJs( control, "#customize-control-" + control_type.name )
					break;
				case "ast-responsive":
					astraGetResponsiveJs( control )
					break;
				case "ast-responsive-slider":
					astraGetResponsiveSliderJs( control )
					break;
				case "ast-responsive-spacing":
					astraGetResponsiveSpacingJs( control )
					break;
				case "ast-font":

					var googleFontsString = astra.customizer.settings.google_fonts;
					control.container.find( '.ast-font-family' ).html( googleFontsString );

					control.container.find( '.ast-font-family' ).each( function() {
						var selectedValue = jQuery(this).data('value');
						jQuery(this).val( selectedValue );

						var optionName = jQuery(this).data('name');

						// Set inherit option text defined in control parameters.
						jQuery("select[data-name='" + optionName + "'] option[value='inherit']").text( jQuery(this).data('inherit') );

						var fontWeightContainer = jQuery(".ast-font-weight[data-connected-control='" + optionName + "']");
						var weightObject = AstTypography._getWeightObject( AstTypography._cleanGoogleFonts( selectedValue ) );

						control.generateDropdownHtml( weightObject, fontWeightContainer );
						fontWeightContainer.val( fontWeightContainer.data('value') );

					});

					control.container.find( '.ast-font-family' ).selectWoo();
					control.container.find( '.ast-font-family' ).on( 'select2:select', function() {

						var value = jQuery(this).val();
						var weightObject = AstTypography._getWeightObject( AstTypography._cleanGoogleFonts( value ) );
						var optionName = jQuery(this).data( 'name' );
						var fontWeightContainer = jQuery(".ast-font-weight[data-connected-control='" + optionName + "']");

						control.generateDropdownHtml( weightObject, fontWeightContainer );

						var font_control = jQuery(this).parents( '.customize-control' ).attr( 'id' );
						font_control = font_control.replace( 'customize-control-', '' );

						control.container.trigger( 'ast_settings_changed', [ control, jQuery(this), value, font_control ] );

						var font_weight_control = fontWeightContainer.parents( '.customize-control' ).attr( 'id' );
						font_weight_control = font_weight_control.replace( 'customize-control-', '' );

						control.container.trigger( 'ast_settings_changed', [ control, fontWeightContainer, fontWeightContainer.val(), font_weight_control ] );

					});

					control.container.find( '.ast-font-weight' ).on( 'change', function() {

						var value = jQuery(this).val();

						name = jQuery(this).parents( '.customize-control' ).attr( 'id' );
						name = name.replace( 'customize-control-', '' );

						control.container.trigger( 'ast_settings_changed', [ control, jQuery(this), value, name ] );
					});

				break;
			}

		});

		wrap.find( '.ast-field-settings-modal' ).data( 'loaded', true );

	},
	getJS: function( control ) {

	},
	generateFieldHtml: function ( fields_data, field_values ) {

		var fields_html = '';
		var control_types = [];


		_.each(fields_data, function (attr, index) {

			var new_value = ( wp.customize.control( 'astra-settings['+attr.name+']' ) ? wp.customize.control( 'astra-settings['+attr.name+']' ).params.value : '' );
			var control = attr.control;
			var template_id = "customize-control-" + control + "-content";
            var template = wp.template(template_id);
			var value = new_value || attr.default;
			attr.value = value;
			var dataAtts = '';
			var input_attrs = '';

			attr.label = attr.title;

			// Data attributes.
			_.each( attr.data_attrs, function( value, name ) {
				dataAtts += " data-" + name + " ='" + value + "'";
			});

			// Input attributes
			_.each( attr.input_attrs, function ( value, name ) {
				input_attrs += name + '="' + value + '" ';
			});

			attr.dataAttrs = dataAtts;
			attr.inputAttrs = input_attrs;

			control_types.push({
				key: control,
				value: value,
				name: attr.name
			});

			if ('ast-responsive' == control) {
				var is_responsive = 'undefined' == typeof attr.responsive ? true : attr.responsive;
				attr.responsive = is_responsive;
			}

			var control_clean_name = attr.name.replace('[', '-');
			control_clean_name = control_clean_name.replace(']', '');

			fields_html += "<li id='customize-control-" + control_clean_name + "' class='customize-control customize-control-" + attr.control + "' >";
			fields_html += template(attr);
			fields_html += '</li>';

		});

		var result = new Object();

		result.controls = control_types;
		result.html     = fields_html;

		return result;
	},

	generateDropdownHtml: function( weightObject, element ) {

		var currentWeightTitle  = element.data( 'inherit' );
		var weightOptions       = '';
		var inheritWeightObject = [ 'inherit' ];
		var counter = 0;
		var weightObject        = jQuery.merge( inheritWeightObject, weightObject );
		var weightValue         = element.val() || '400';
		var selected = '';
		astraTypo[ 'inherit' ] = currentWeightTitle;

		for ( ; counter < weightObject.length; counter++ ) {

			if ( 0 === counter && -1 === jQuery.inArray( weightValue, weightObject ) ) {
				weightValue = weightObject[ 0 ];
				selected 	= ' selected="selected"';
			} else {
				selected = weightObject[ counter ] == weightValue ? ' selected="selected"' : '';
			}
			if( ! weightObject[ counter ].includes( "italic" ) ){
				weightOptions += '<option value="' + weightObject[ counter ] + '"' + selected + '>' + astraTypo[ weightObject[ counter ] ] + '</option>';
			}
		}

		element.html( weightOptions );
	},

	onOptionChange:function ( e, control, element, value, name ) {

		var control_id  = jQuery( '.hidden-field-astra-settings-' + name );
		control_id.val( value );
		let sub_control = wp.customize.control( "astra-settings[" + name + "]" );
		sub_control.setting.set( value );
	},

	isJsonString: function( str ) {

		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	},
	getFinalControlObject: function ( attr, controlObject ) {

		if ( undefined !== attr.choices && undefined === controlObject.params['choices'] ) {
			controlObject.params['choices'] = attr.choices;
		}
		if ( undefined !== attr.inputAttrs && undefined === controlObject.params['inputAttrs'] ) {
			controlObject.params['inputAttrs'] = attr.inputAttrs;
		}
		if ( undefined !== attr.link && undefined === controlObject.params['link'] ) {
			controlObject.params['link'] = attr.link;
		}
		if ( undefined !== attr.units && undefined === controlObject.params['units'] ) {
			controlObject.params['units'] = attr.units;
		}
		if ( undefined !== attr.linked_choices && undefined === controlObject.params['linked_choices'] ) {
			controlObject.params['linked_choices'] = attr.linked_choices;
		}
		if ( undefined !== attr.title && ( undefined === controlObject.params['label'] || '' === controlObject.params['label'] || null === controlObject.params['label'] ) ) {
			controlObject.params['label'] = attr.title;
		}
		if ( undefined !== attr.responsive && ( undefined === controlObject.params['responsive'] || '' === controlObject.params['responsive'] || null === controlObject.params['responsive'] ) ) {
			controlObject.params['responsive'] = attr.responsive;
		}

		return controlObject;
	},
	renderReactControl: function( fields, control ) {

		const reactControls = {
			'ast-background' : Background,
			'ast-responsive-background' : ResponsiveBackground,
			'ast-responsive-color' : ResponsiveColorComponent,
			'ast-color' : ColorComponent,
			'ast-border' : BorderComponent,
			'ast-responsive' : ResponsiveComponent,
			'ast-responsive-slider' : ResponsiveSliderComponent,
			'ast-slider' : SliderComponent,
			'ast-responsive-spacing' : ResponsiveSpacingComponent,
			'ast-select' : SelectComponent,
			'ast-divider' : DividerComponent,
		};

		if( 'undefined' != typeof fields.tabs ) {

			_.each( fields.tabs, function ( fields_data, key ) {

				_.each(fields_data, function (attr, index) {
					if ( 'ast-font' !== attr.control ) {
						var control_clean_name = attr.name.replace('[', '-');
						control_clean_name = control_clean_name.replace(']', '');
						var selector = '#customize-control-' + control_clean_name;
						var controlObject = wp.customize.control( 'astra-settings['+attr.name+']' );
						controlObject = control.getFinalControlObject( attr, controlObject );
						const ComponentName = reactControls[ attr.control ];
						ReactDOM.render(
							<ComponentName control={controlObject} customizer={ wp.customize }/>,
							jQuery( selector )[0]
						);
					}
				});

			});
		} else {

			_.each(fields, function (attr, index) {

				if ( 'ast-font' !== attr.control ) {

					var control_clean_name = attr.name.replace('[', '-');
					control_clean_name = control_clean_name.replace(']', '');
					var selector = '#customize-control-' + control_clean_name;
					var controlObject = wp.customize.control( 'astra-settings['+attr.name+']' );
					controlObject = control.getFinalControlObject( attr, controlObject );
					const ComponentName = reactControls[ attr.control ];

					ReactDOM.render(
						<ComponentName control={controlObject} customizer={ wp.customize }/>,
						jQuery( selector )[0]
					);
				}
			});
		}
	}
} );
