/**
 * File typography.js
 *
 * Handles Typography of the site
 *
 * @package Astra
 */

( function( $ ) {
	$(document).ready(function() {
		// Apply select2 only to the Base Typography -- Font Family.
		// $('#customize-control-astra-settings-body-font-family select, #customize-control-astra-settings-headings-font-family select').select2({
		// 	// minimumInputLength: 2,

		// });
		// multiple select with AJAX search
		$('.customize-control-ast-font-family select').select2({
			ajax: {
				url: ajaxurl, // AJAX URL is predefined in WordPress admin
				type: 'post',
				dataType: 'json',
				delay: 250, // delay in ms while typing when to perform a AJAX search
				data: function (params) {
						return {
						font_search: params.term, 
						action: 'astra_get_all_google_fonts_ajax',
						astra_customize_nonce: astraTypo.nonce
						};
				},
				processResults: function( data ) {
					var options = [];
					var systemFonts = { 
						text: "System Fonts",
						element:HTMLOptGroupElement,
						children:[]
					};
					var googleFonts = { 
						text: "Google Fonts", 
						element:HTMLOptGroupElement,
						children:[]
					};
					
					if ( data ) {

						// data is the array of arrays, and each of them contains ID and the Label of the option
						$.each( data, function( index, keys ) { // do not forget that "index" is just auto incremented value
							if( 'undefined' !== typeof( keys.fallback ) ){
								var insideOption =  {
									id:index, text:index 
								};
								systemFonts.children.push( insideOption );
							}
							if( 'undefined' !== typeof( keys.category ) ){
								var insideOption =   {
									id: "'" + index + "', " + keys.category, text:index 
								};
								googleFonts.children.push( insideOption );
							}
						});
						if( systemFonts.children.length != 0 ){
							options.push(systemFonts);
						}
						if( googleFonts.children.length != 0 ){
							options.push(googleFonts);
						}
					}
					return {
						results: options
					};
				},
				cache: true
			},
			minimumInputLength: 2, // the minimum of symbols to input before perform a search
			placeholder: 'Search for a Font',
		});
	});
	/* Internal shorthand */
	var api = wp.customize;

	/**
	 * Helper class for the main Customizer interface.
	 *
	 * @since 1.0.0
	 * @class AstTypography
	 */
	AstTypography = {

		/**
		 * Initializes our custom logic for the Customizer.
		 *
		 * @since 1.0.0
		 * @method init
		 */
		init: function() {
			AstTypography._initFonts();
		},

		/**
		 * Initializes logic for font controls.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _initFonts
		 */
		_initFonts: function()
		{
			$( '.customize-control-ast-font-family select' ).each( AstTypography._initFont );
		},

		/**
		 * Initializes logic for a single font control.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _initFont
		 */
		_initFont: function()
		{
				var select  = $( this ),
				link    = select.data( 'customize-setting-link' ),
				weight  = select.data( 'connected-control' );			

				// select.on( 'change', function() {
				// if ( 'undefined' != typeof weight ) {
				// 	api( link ).bind( AstTypography._fontSelectChange );
				// 	AstTypography._setFontWeightOptions.apply( api( link ), [ true ] );
				// }

// 				// Send our request to the generate_get_all_google_fonts_ajax function
// 				var response = jQuery.getJSON({
// 					type: 'POST',
// 					url: ajaxurl,
// 					data: {
// 						action: 'astra_get_all_google_fonts_ajax',
// 						astra_customize_nonce: astraTypo.nonce
// 					},
// 					async: false,
// 					dataType: 'json',
// 				});

// 				// Get our response
// 				var fonts = response.responseJSON;

// 				// Create an ID from our selected font
// 				var id = value.split(' ').join('_').toLowerCase();
// console.log(id);
// 				if ( id in fonts ) {
// 					// Populate our select input with available variants
// 					jQuery.each( fonts[ id ].variants, function( key, value ) {
// 						jQuery( 'select[name="' + _variantsID + '"]' ).append( jQuery( '<option></option>' ).attr( 'value', value ).text( value ) );
// 					} );
// 					console.log(fonts);
// 				}

		// });
		}, 

		/**
		 * Callback for when a font control changes.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _fontSelectChange
		 */
		_fontSelectChange: function()
		{
			AstTypography._setFontWeightOptions.apply( this, [ false ] );
		},

		/**
		 * Clean font name.
		 *
		 * Google Fonts are saved as {'Font Name', Category}. This function cleanes this up to retreive only the {Font Name}.
		 *
		 * @since  1.3.0
		 * @param  {String} fontValue Name of the font.
		 * 
		 * @return {String}  Font name where commas and inverted commas are removed if the font is a Google Font.
		 */
		_cleanGoogleFonts: function(fontValue)
		{
			// Bail if fontVAlue does not contain a comma.
			if ( ! fontValue.includes(',') ) return fontValue;

			var splitFont 	= fontValue.split(',');
			var pattern 	= new RegExp("'", 'gi');

			// Check if the cleaned font exists in the Google fonts array.
			var googleFontValue = splitFont[0].replace(pattern, '');
			if ( 'undefined' != typeof AstFontFamilies.google[ googleFontValue ] ) {
				fontValue = googleFontValue;
			}

			return fontValue;
		},

		/**
		 * Sets the options for a font weight control when a
		 * font family control changes.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _setFontWeightOptions
		 * @param {Boolean} init Whether or not we're initializing this font weight control.
		 */
		_setFontWeightOptions: function( init )
		{
			var i               = 0,
			fontSelect          = api.control( this.id ).container.find( 'select' ),
			fontValue           = this(),
			selected            = '',
			weightKey           = fontSelect.data( 'connected-control' ),
			inherit             = fontSelect.data( 'inherit' ),
			weightSelect        = api.control( weightKey ).container.find( 'select' ),
			currentWeightTitle  = weightSelect.data( 'inherit' ),
			weightValue         = init ? weightSelect.val() : '400',
			inheritWeightObject = [ 'inherit' ],
			weightObject        = [ '400', '600' ],
			weightOptions       = '',
			weightMap           = astraTypo;
			if ( fontValue == 'inherit' ) {
				weightValue     = init ? weightSelect.val() : 'inherit';
			}

			var fontValue = AstTypography._cleanGoogleFonts(fontValue);

			if ( fontValue == 'inherit' ) {
				weightObject = [ '400','500','600','700' ];
			} else if ( 'undefined' != typeof AstFontFamilies.system[ fontValue ] ) {
				weightObject = AstFontFamilies.system[ fontValue ].weights;
			} else if ( 'undefined' != typeof AstFontFamilies.google[ fontValue ] ) {
				weightObject = AstFontFamilies.google[ fontValue ][0];
				weightObject = Object.keys(weightObject).map(function(k) {
				  return weightObject[k];
				});
			} else if ( 'undefined' != typeof AstFontFamilies.custom[ fontValue.split(',')[0] ] ) {
				weightObject = AstFontFamilies.custom[ fontValue.split(',')[0] ].weights;
			}

			weightObject = $.merge( inheritWeightObject, weightObject )
			weightMap[ 'inherit' ] = currentWeightTitle;
			for ( ; i < weightObject.length; i++ ) {

				if ( 0 === i && -1 === $.inArray( weightValue, weightObject ) ) {
					weightValue = weightObject[ 0 ];
					selected 	= ' selected="selected"';
				} else {
					selected = weightObject[ i ] == weightValue ? ' selected="selected"' : '';
				}

				weightOptions += '<option value="' + weightObject[ i ] + '"' + selected + '>' + weightMap[ weightObject[ i ] ] + '</option>';
			}

			weightSelect.html( weightOptions );

			if ( ! init ) {
				api( weightKey ).set( '' );
				api( weightKey ).set( weightValue );
			}
		},
	};

	$( function() { AstTypography.init(); } );

})( jQuery );