import {
	createURL,
	setBrowserViewport,
} from '@wordpress/e2e-test-utils';
import { setCustomize } from '../../../utils/set-customize';

describe( 'Site tagline typography settings in the customizer', () => {
	it( 'Site tagline typography should apply corectly', async () => {
		const sitetaglinetypography = {
			'display-site-tagline-responsive': {
				desktop: true,
				tablet: true,
				mobile: true,
			},

			'font-size-site-tagline': {
				desktop:'50',
				tablet: '20',
				mobile: '15',
				'desktop-unit': 'px',
				'tablet-unit': 'px',
				'mobile-unit': 'px',
			},
		};

		await setCustomize( sitetaglinetypography );
		
		await page.goto( createURL( '/' ), {
			waitUntil: 'networkidle0',
		} );
		
		await expect( {
			selector: '.site-header .site-description',
			property: 'font-size',
		} ).cssValueToBe(
			`${ sitetaglinetypography[ 'font-size-site-tagline' ].desktop }${ sitetaglinetypography[ 'font-size-site-tagline' ][ 'desktop-unit' ] }`,
		);

		await setBrowserViewport( 'medium' );

		await expect( {
			selector: '.site-header .site-description',
			property: 'font-size',
		} ).cssValueToBe(
			`${ sitetaglinetypography[ 'font-size-site-tagline' ].tablet }${ sitetaglinetypography[ 'font-size-site-tagline' ][ 'tablet-unit' ] }`,
		);
		await setBrowserViewport( 'small' );

		await expect( {
			selector: '.site-header .site-description',
			property: 'font-size',
		} ).cssValueToBe(
			`${ sitetaglinetypography[ 'font-size-site-tagline' ].mobile }${ sitetaglinetypography[ 'font-size-site-tagline' ][ 'mobile-unit' ] }`,
		);

	} );
} );