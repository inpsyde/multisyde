import apiFetch from '@wordpress/api-fetch';

export function setupApiFetch( nonce ) {
	if ( nonce ) {
		apiFetch.use( apiFetch.createNonceMiddleware( nonce ) );
	}
}

export function createSite( { restNs = 'wp/v2', data } ) {
	return apiFetch( {
		path: `/${ restNs }/sites`,
		method: 'POST',
		data,
	} );
}
