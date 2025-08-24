import apiFetch from '@wordpress/api-fetch';

export function setupApiFetch(nonce) {
	if (nonce) {
		apiFetch.use(apiFetch.createNonceMiddleware(nonce));
	}
}

export function fetchSites({ view, restNs = 'multisyde/v1' }) {
	const params = new URLSearchParams();

	if (view?.page) params.set('page', String(view.page));
	if (view?.perPage) params.set('per_page', String(view.perPage));
	if (view?.search) params.set('search', view.search);
	if (view?.sort?.field) params.set('orderby', view.sort.field);
	if (view?.sort?.direction) params.set('order', view.sort.direction);

	return apiFetch({ path: `/${restNs}/sites?${params.toString()}`, method: 'GET' });
}
