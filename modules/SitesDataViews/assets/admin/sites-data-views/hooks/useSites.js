import { useState, useEffect } from '@wordpress/element';
import { fetchSites } from '../api';

const restNs = window.MS_SITES_DATA?.restNs || 'multisyde/v1';

export default function useSites(initialView) {
	const [rows, setRows] = useState([]);
	const [view, setView] = useState(initialView);
	const [isLoading, setIsLoading] = useState(true);
	const [paginationInfo, setPaginationInfo] = useState({ totalItems: 0, totalPages: 1 });

	useEffect(() => {
		const params = { view, restNs };
		let alive = true;
		setIsLoading(true);
		fetchSites(params)
			.then(({ items, total, totalPages }) => {
				if (!alive) return;
				setRows(items);
				setPaginationInfo({
					totalItems: total,
					totalPages: Math.max(1, totalPages),
				});
			})
			.catch(() => {
				if (!alive) return;
				setRows([]);
				setPaginationInfo({ totalItems: 0, totalPages: 1 });
			})
			.finally(() => {
				if (!alive) return;
				setIsLoading(false);
			});
		return () => { alive = false; };
	}, [view.page, view.perPage, view.search, view.sort, JSON.stringify(view.filters)]);

	return { rows, setRows, view, setView, isLoading, paginationInfo };
}