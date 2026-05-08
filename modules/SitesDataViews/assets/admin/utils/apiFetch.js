import apiFetch from '@wordpress/api-fetch';

/**
 * Wire the WP REST nonce into `@wordpress/api-fetch` once per page load.
 * Each entry point reads its own inline `window.MS_*_DATA.nonce` and
 * passes it here.
 */
export function setupApiFetch( nonce ) {
	if ( nonce ) {
		apiFetch.use( apiFetch.createNonceMiddleware( nonce ) );
	}
}
