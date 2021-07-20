import {
	createURL,
	createNewPost,
	publishPost,
	visitAdminPage,
} from '@wordpress/e2e-test-utils';

describe( 'Create new post for Testing', () => {
	it( 'Create Post test', async () => {
	// Create New Post.
		await createNewPost( { postType: 'post', title: 'Testing Monika' } );
		await publishPost();
		});
} );
