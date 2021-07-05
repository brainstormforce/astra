import { createNewPost, setPostContent } from '@wordpress/e2e-test-utils';
import { TPOGRAPHY_TEST_POST_CONTENT } from '../../utils/post';
import { setCustomize } from '../../utils/set-customize';

describe( 'Typography in the block editor', () => {
	it( 'should work as set in the customizer for h1-h6', async () => {
		const headingTypography = {
			'headings-font-family': "'Alex Brush', handwriting",
			'headings-font-weight': '400',
			'font-family-h1': "'Alegreya Sans SC', sans-serif",
			'font-weight-h1': '400',
			'line-height-h1': 1.8,
			'font-size-h1': {
				desktop: '39',
				'desktop-unit': 'px',
			},
			'font-family-h2': "'Allerta Stencil', sans-serif",
			'font-weight-h2': '400',
			'line-height-h2': 1.5,
			'font-size-h2': {
				desktop: '28',
				'desktop-unit': 'px',
			},
			'font-family-h3': "'Akaya Kanadaka', display",
			'font-weight-h3': '400',
			'line-height-h3': 2.54,
			'font-size-h3': {
				desktop: '23',
				'desktop-unit': 'px',
			},
			'font-size-h4': {
				desktop: '25',
				'desktop-unit': 'px',
			},
			'font-size-h5': {
				desktop: '23',
				'desktop-unit': 'px',
			},
			'font-size-h6': {
				desktop: '20',
				'desktop-unit': 'px',
			},
		};

		await setCustomize( headingTypography );
		await createNewPost();
		await setPostContent( TPOGRAPHY_TEST_POST_CONTENT );

		// Test typography for h1.
		await expect( {
			selector: '.block-editor-writing-flow h1',
			property: 'font-family',
		} ).cssValueToBe( `${ headingTypography[ 'font-family-h1' ] }` );

		await expect( {
			selector: '.block-editor-writing-flow h1',
			property: 'font-weight',
		} ).cssValueToBe( `${ headingTypography[ 'font-weight-h1' ] }` );

		await expect( {
			selector: '.block-editor-writing-flow h1',
			property: 'font-size',
		} ).cssValueToBe(
			`${ headingTypography[ 'font-size-h1' ].desktop }${ headingTypography[ 'font-size-h1' ][ 'desktop-unit' ] }`,
		);

		// Test typography for h2.
		await expect( {
			selector: '.block-editor-writing-flow h2',
			property: 'font-family',
		} ).cssValueToBe( `${ headingTypography[ 'font-family-h2' ] }` );

		await expect( {
			selector: '.block-editor-writing-flow h2',
			property: 'font-weight',
		} ).cssValueToBe( `${ headingTypography[ 'font-weight-h2' ] }` );

		await expect( {
			selector: '.block-editor-writing-flow h2',
			property: 'font-size',
		} ).cssValueToBe(
			`${ headingTypography[ 'font-size-h2' ].desktop }${ headingTypography[ 'font-size-h2' ][ 'desktop-unit' ] }`,
		);

		// Test typography for h3.
		await expect( {
			selector: '.block-editor-writing-flow h3',
			property: 'font-family',
		} ).cssValueToBe( `${ headingTypography[ 'font-family-h3' ] }` );

		await expect( {
			selector: '.block-editor-writing-flow h3',
			property: 'font-weight',
		} ).cssValueToBe( `${ headingTypography[ 'font-weight-h3' ] }` );

		await expect( {
			selector: '.block-editor-writing-flow h3',
			property: 'font-size',
		} ).cssValueToBe(
			`${ headingTypography[ 'font-size-h3' ].desktop }${ headingTypography[ 'font-size-h3' ][ 'desktop-unit' ] }`,
		);

		// Test typography for h4.
		await expect( {
			selector: '.block-editor-writing-flow h4',
			property: 'font-family',
		} ).cssValueToBe( `${ headingTypography[ 'headings-font-family' ] }` );

		await expect( {
			selector: '.block-editor-writing-flow h4',
			property: 'font-weight',
		} ).cssValueToBe( `${ headingTypography[ 'headings-font-weight' ] }` );

		await expect( {
			selector: '.block-editor-writing-flow h4',
			property: 'font-size',
		} ).cssValueToBe(
			`${ headingTypography[ 'font-size-h4' ].desktop }${ headingTypography[ 'font-size-h4' ][ 'desktop-unit' ] }`,
		);

		// Test typography for h5.
		await expect( {
			selector: '.block-editor-writing-flow h5',
			property: 'font-family',
		} ).cssValueToBe( `${ headingTypography[ 'headings-font-family' ] }` );

		await expect( {
			selector: '.block-editor-writing-flow h5',
			property: 'font-weight',
		} ).cssValueToBe( `${ headingTypography[ 'headings-font-weight' ] }` );

		await expect( {
			selector: '.block-editor-writing-flow h5',
			property: 'font-size',
		} ).cssValueToBe(
			`${ headingTypography[ 'font-size-h5' ].desktop }${ headingTypography[ 'font-size-h5' ][ 'desktop-unit' ] }`,
		);

		// Test typography for h6.
		await expect( {
			selector: '.block-editor-writing-flow h6',
			property: 'font-family',
		} ).cssValueToBe( `${ headingTypography[ 'headings-font-family' ] }` );

		await expect( {
			selector: '.block-editor-writing-flow h6',
			property: 'font-weight',
		} ).cssValueToBe( `${ headingTypography[ 'headings-font-weight' ] }` );

		await expect( {
			selector: '.block-editor-writing-flow h6',
			property: 'font-size',
		} ).cssValueToBe(
			`${ headingTypography[ 'font-size-h6' ].desktop }${ headingTypography[ 'font-size-h6' ][ 'desktop-unit' ] }`,
		);
	} );
} );
