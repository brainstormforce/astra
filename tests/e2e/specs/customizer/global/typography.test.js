import {
	createURL,
	createNewPost,
	setPostContent,
	publishPost,
} from '@wordpress/e2e-test-utils';
import { setCustomize } from '../../../utils/set-customize';
import { TPOGRAPHY_TEST_POST_CONTENT } from '../../../utils/post';

describe( 'Global Typography settings in the customizer', () => {
	it( 'body typography should be applied correctly', async () => {
		const globalTypegraphy = {
			'body-font-family': "'Open Sans', sans-serif",
			'body-font-variant': '800',
			'body-font-weight': '800',
			'body-text-transform': '',
			'font-size-body': {
				desktop: 21,
				tablet: '20',
				mobile: '18',
				'desktop-unit': 'px',
				'tablet-unit': 'px',
				'mobile-unit': 'px',
			},
			'body-line-height': 0.99,
			'para-margin-bottom': 1.68,
		};

		await setCustomize( globalTypegraphy );

		await createNewPost( { postType: 'post', title: 'Typography Test' } );
		await setPostContent( TPOGRAPHY_TEST_POST_CONTENT );
		await publishPost();
		await page.goto( createURL( '/typography-test/' ), {
			waitUntil: 'networkidle0',
		} );
		await page.waitForSelector( '.entry-content' );

		await expect( {
			selector: '.entry-content p',
			property: 'font-size',
		} ).cssValueToBe(
			`${ globalTypegraphy[ 'font-size-body' ].desktop }${ globalTypegraphy[ 'font-size-body' ][ 'desktop-unit' ] }`,
		);
		await expect( {
			selector: '.entry-content p',
			property: 'font-family',
		} ).cssValueToBe( `${ globalTypegraphy[ 'body-font-family' ] }` );
	} );

	it( 'body typography and heading typography combinations for site title', async () => {
		const globalTypegraphy = {
			'body-font-family': "'Open Sans', sans-serif",
			'body-font-weight': '400',
			'headings-font-family': 'inherit',
			'headings-font-weight': '400',
		};

		await setCustomize( globalTypegraphy );
		await page.goto( createURL( '/' ), { waitUntil: 'networkidle0' } );
		await page.waitForSelector( '.entry-content' );

		await expect( {
			selector: '.site-title a',
			property: 'font-family',
		} ).cssValueToBe( `${ globalTypegraphy[ 'body-font-family' ] }` );
	} );

	it( 'heading typography is applied when global headings typography is set', async () => {
		const headingTypography = {
			'body-font-family': 'inherit',
			'body-font-weight': '400',
			'headings-font-family': "'Tulpen One', display",
			'headings-font-weight': '400',
		};

		await setCustomize( headingTypography );
		await page.goto( createURL( '/' ), { waitUntil: 'networkidle0' } );
		await page.waitForSelector( '.entry-content' );

		await expect( {
			selector: '.site-title a',
			property: 'font-family',
		} ).cssValueToBe( `${ headingTypography[ 'headings-font-family' ] }` );
	} );
} );
