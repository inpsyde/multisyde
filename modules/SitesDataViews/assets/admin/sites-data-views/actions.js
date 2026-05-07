import { __ } from '@wordpress/i18n';
import { siteHomeUrl, siteAdminUrl } from './urls';

export function buildActions({ openEditor } = {}) {
	return [
		{
			id: 'visit',
			label: __( 'Visit', 'multisyde' ),
			isPrimary: true,
			callback: (items) => window.open( siteHomeUrl( items[0] ), '_blank' ),
		},
		{
			id: 'admin',
			label: __( 'Admin', 'multisyde' ),
			callback: (items) => window.open( siteAdminUrl( items[0] ), '_blank' ),
		},
	];
}
