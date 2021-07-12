import {
	createURL,
	createNewPost,
	publishPost,
} from '@wordpress/e2e-test-utils';
import { setCustomize } from '../../../utils/set-customize';
import { createUser } from '../../../utils/create-new-user';

describe( 'Related Posts correct Author Name', () => {
	it( 'related posts should display correct author name', async () => {
		const relatedPosts = {
			'enable-related-posts': true,
		};

		// Enable Related Posts.
		await setCustomize( relatedPosts );

		// Create New User.
		await createUser( 'adminRelatedPost', { role: 'administrator' } );

		// Create New Post.
		await createNewPost( {
			postType: 'post',
			title: 'Related Post - admin',
		} );
		await publishPost();

		// Create New Post.
		await createNewPost( {
			postType: 'post',
			title: 'Related Post - adminRelatedPost',
		} );
		// Select new user for publishing the post.
		await page.waitForSelector( '#inspector-select-control-3' );

		const authorNameOption = (
			await page.$x(
				'//*[ @id = "inspector-select-control-3" ]/option[ text() = "adminRelatedPost" ]',
			)
		)[ 0 ];

		const authorNameValue = await (
			await authorNameOption.getProperty( 'value' )
		).jsonValue();

		await page.select( '#inspector-select-control-3', authorNameValue );

		await publishPost();

		await page.goto( createURL( '/related-post-admin/' ), {
			waitUntil: 'networkidle0',
		} );

		await page.waitForSelector( '.entry-content' );

		let currentPostAuthor = await page.$( '.single-layout-1 .author-name' );
		currentPostAuthor = await page.evaluate(
			( el ) => el.textContent,
			currentPostAuthor,
		);

		// Check if current author name correct or not. If not, throw error.
		await expect( currentPostAuthor ).toBe( 'admin' );

		let relatedPostAuthor = await page.$(
			'.ast-related-post-content .author-name',
		);
		relatedPostAuthor = await page.evaluate(
			( el ) => el.textContent,
			relatedPostAuthor,
		);

		// Check if related post author name correct or not. If not, throw error.
		await expect( relatedPostAuthor ).toBe( 'adminRelatedPost' );
	} );
} );
