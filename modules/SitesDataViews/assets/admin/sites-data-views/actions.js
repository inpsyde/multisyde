import { __ } from '@wordpress/i18n';

export function buildActions({ openEditor } = {}) {
	return [
		{
			id: 'visit',
			label: __( 'Visit', 'multisyde' ),
			isPrimary: true,
			callback: (items) => window.open(items[0].home_url, '_blank'),
		},
		{
			id: 'admin',
			label: __( 'Admin', 'multisyde' ),
			callback: (items) => window.open(items[0].admin_url, '_blank'),
		},
	];
}