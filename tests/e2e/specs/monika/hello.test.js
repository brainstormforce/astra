import {
	createURL,
	createNewPost,
	publishPost,
	visitAdminPage,
} from '@wordpress/e2e-test-utils';

describe( 'Related Posts correct Author Name', () => {
	it( 'Related Posts should display correct author name', async () => {
	

		// Create New Post.
		await createNewPost( { postType: 'post', title: 'Related Post - admin', content: `dummy-text` excerpt: `test-ex` } );
		await publishPost();
		});
} );
