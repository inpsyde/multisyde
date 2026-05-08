import apiFetch from '@wordpress/api-fetch';

export { setupApiFetch } from '../utils/apiFetch';

export function getSite( { restNs = 'wp/v2', id } ) {
	return apiFetch( {
		path: `/${ restNs }/sites/${ id }?context=edit`,
		method: 'GET',
	} );
}

export function updateSite( { restNs = 'wp/v2', id, data } ) {
	return apiFetch( {
		path: `/${ restNs }/sites/${ id }`,
		method: 'PUT',
		data,
	} );
}
