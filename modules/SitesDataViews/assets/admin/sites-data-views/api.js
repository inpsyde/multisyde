import apiFetch from '@wordpress/api-fetch';

const STATUS_FILTER_MAP = {
	public: { public: 1 },
	private: { public: 0 },
	archived: { archived: 1 },
	spam: { spam: 1 },
	deleted: { deleted: 1 },
	mature: { mature: 1 },
};

export function setupApiFetch(nonce) {
	if (nonce) {
		apiFetch.use(apiFetch.createNonceMiddleware(nonce));
	}
}

export function fetchSites({ view, restNs = 'wp/v2' }) {
	const params = new URLSearchParams();

	if (view?.page) params.set('page', String(view.page));
	if (view?.perPage) params.set('per_page', String(view.perPage));
	if (view?.search) params.set('search', view.search);
	if (view?.sort?.field) params.set('orderby', view.sort.field);
	if (view?.sort?.direction) params.set('order', view.sort.direction);

	const filters = Array.isArray(view?.filters) ? view.filters : [];
	filters.forEach((filter) => {
		if (filter?.field === 'status' && STATUS_FILTER_MAP[filter.value]) {
			Object.entries(STATUS_FILTER_MAP[filter.value]).forEach(([key, value]) => {
				params.set(key, String(value));
			});
		}
	});

	return apiFetch({
		path: `/${restNs}/sites?${params.toString()}`,
		method: 'GET',
		parse: false,
	} ).then( async ( response ) => {
		const items = await response.json();
		const total = parseInt( response.headers.get( 'X-WP-Total' ), 10 );
		const totalPages = parseInt( response.headers.get( 'X-WP-TotalPages' ), 10 );
		return {
			items: Array.isArray( items ) ? items : [],
			total: Number.isFinite( total ) ? total : ( Array.isArray( items ) ? items.length : 0 ),
			totalPages: Number.isFinite( totalPages ) ? totalPages : 1,
		};
	} );
}
