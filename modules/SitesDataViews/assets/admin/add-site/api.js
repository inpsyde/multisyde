import apiFetch from '@wordpress/api-fetch';

export { setupApiFetch } from '../utils/apiFetch';

export function createSite( { restNs = 'wp/v2', data } ) {
	return apiFetch( {
		path: `/${ restNs }/sites`,
		method: 'POST',
		data,
	} );
}
