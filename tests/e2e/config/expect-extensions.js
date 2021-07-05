/**
 * `expect` extension to count the number of elements with a given selector on the page.
 */
expect.extend( {
	async countToBe( selector, expected ) {
		const count = await page.$$eval( selector, ( els ) => els.length );

		if ( count !== expected ) {
			return {
				pass: false,
				message: () =>
					`Expected ${ expected } elements for selector ${ selector }. Received ${ count }.`,
			};
		}

		return {
			pass: true,
			message: () =>
				`Expected ${ expected } elements for selector ${ selector }.`,
		};
	},

	async cssValueToBe( css, expected ) {
		const value = await page.$eval(
			css.selector,
			( el, prop, pseudoEl ) =>
				window
					.getComputedStyle( el, pseudoEl || null )
					.getPropertyValue( prop ),
			css.property,
		);

		const sanitizedValue = sanitizeValue( css.property, value );

		if ( sanitizedValue !== expected ) {
			return {
				pass: false,
				message: () =>
					`Expected ${ expected } for ${ css.property } of ${ css.selector }. Received ${ sanitizedValue }.`,
			};
		}

		return {
			pass: true,
			message: () =>
				`Expected ${ expected } for ${ css.property } of ${ css.selector }.`,
		};
	},
} );

/**
 * Sanitize the given css value for the property.
 *
 * @param {string} cssProperty CSS Property
 * @param {string} cssValue CSS Value for the property.
 * @return {string} Sanitized CSS Value.
 */
const sanitizeValue = ( cssProperty, cssValue ) => {
	const SANITIZERS = {
		'font-family': sanitizeFontFamily,
	};

	const sanitizer = SANITIZERS[ `${ cssProperty }` ];

	if ( typeof sanitizer === 'function' ) {
		return sanitizer( cssValue );
	}

	return cssValue;
};

/**
 * Sanitize font family string.
 *
 * @param {string} fontFamily Font family string.
 * @return {string} Sanitized font family.
 */
const sanitizeFontFamily = ( fontFamily ) => {
	return fontFamily.replace( /\\/g, '' ).replace( /"/g, "'" );
};
