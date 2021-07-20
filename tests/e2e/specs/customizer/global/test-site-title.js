import { createURL } from '@wordpress/e2e-test-utils';
import { setCustomize } from '../../../utils/set-customize';

describe.only( 'Site Title Typography settings and color settings in the customizer', function() {
	it( 'site title typography and color should apply corectly', async function() {
		const sitetitleTypography = {
			'body-font-family': 'Almendra, sans-serif',
			'body-font-variant': '700',
			'body-font-weight': '700',
			'body-text-transform': 'uppercase',
			'font-size-site-title': {
				desktop: 70,
				tablet: 50,
				mobile: 25,
				'desktop-unit': 'px',
				'tablet-unit': 'px',
				'mobile-unit': 'px',
			},
			'site-title-line-height': 0.99,
			'header-color-site-title': 'rgb(24, 55, 242)',
		};
		await setCustomize( sitetitleTypography );

		await page.goto( createURL( '/' ), {
			waitUntil: 'networkidle0',
		} );

		await page.waitForSelector( '.site-title a' );

		await expect( {
			selector: '.site-title',
			property: 'font-size',
		} ).cssValueToBe(
			`${ sitetitleTypography[ 'font-size-site-title' ].desktop }${ sitetitleTypography[ 'font-size-site-title' ][ 'desktop-unit' ] }`,
		);

		await expect( {
			selector: '.ast-site-identity .site-title a',
			property: 'color',
		} ).cssValueToBe( `${ sitetitleTypography[ 'header-color-site-title' ] }` );

		await expect( {
			selector: '.site-title a',
			property: 'font-family',
		} ).cssValueToBe( `${ sitetitleTypography[ 'body-font-family' ] }` );
	} );
} );
