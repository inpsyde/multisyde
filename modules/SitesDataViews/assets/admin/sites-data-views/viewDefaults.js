export const defaultLayouts = { table: { showMedia: false } };

export const initialView = {
	type: 'table',
	perPage: 20,
	perPageSizes: [ 10, 20, 50, 100 ],
	page: 1,
	search: '',
	sort: { field: 'blog_id', direction: 'asc' },
	fields: [ 'url', 'status', 'last_updated', 'registered' ],
};
