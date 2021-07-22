import {createURL,createNewPost,publishPost} from '@wordpress/e2e-test-utils';
import {setCustomize} from '../../../utils/set-customize';

describe('Breadcrumb typography settings and color settings',()=>{
    it('breadcrumb typography and color should apply correctly',async ()=>{
        const breadcrumbTypography =  {
			'display-breadcrumb-responsive': {
				desktop: true,
				tablet: true,
				mobile: true,
			},
            'body-font-family': 'Almendra, sans-serif',
			'body-font-variant': '800',
			'body-font-weight': '800',
			'body-text-transform': 'lowercase',
            'font-size-breadcrumb': {
				desktop: 15,
				tablet: 14,
				mobile: 12,
				'desktop-unit': 'px',
				'tablet-unit': 'px',
				'mobile-unit': 'px',
			},
			'breadcrumb-height': 0.99,
			'header-color-site-tagline': 'rgb(59, 191, 13)',
		};
        await setCustomize(breadcrumbTypography)

        // Create New Post.
		await createNewPost( {
			postType: 'post',
			title: 'Related Post - admin',
		} );
		await publishPost();



        await page.goto( createURL( '/' ), {
			waitUntil: 'networkidle0',
		} );

        await page.waitForSelector( '.ast-breadcrumbs ul li' );

        await expect( {
			selector: '.trail-items li',
			property: 'font-size',
		} ).cssValueToBe(
			`${ siteTagline[ 'font-size-breadcrumb' ].desktop }${ siteTagline[ 'font-size-breadcrumb' ][ 'desktop-unit' ] }`,
		);
    })
})