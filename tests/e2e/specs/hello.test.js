/**
 * WordPress dependencies
 */
import { visitAdminPage } from '@wordpress/e2e-test-utils';

describe( 'Hello World', () => {
	it( 'should load properly', async () => {
		await visitAdminPage( '/' );
		const nodes = await page.$x(
			'//h2[contains(text(), "Welcome to WordPress!")]',
		);
		await expect( nodes ).not.toHaveLength( 0 );
	} );
} );
