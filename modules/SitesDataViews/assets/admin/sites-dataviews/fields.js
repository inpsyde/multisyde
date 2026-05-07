import { __ } from '@wordpress/i18n';
import { dateI18n, getSettings } from '@wordpress/date';
import { siteInfoUrl } from './urls';

const formatSiteDate = ( value ) => {
	if ( ! value || value === '0000-00-00 00:00:00' ) {
		return '';
	}
	const { formats } = getSettings();
	return dateI18n( `${ formats.date } ${ formats.time }`, value );
};

export const fields = [
	{ id: 'id', label: __( 'ID', 'multisyde' ), enableSorting: true },
	{ id: 'domain', label: __( 'Domain', 'multisyde' ), enableGlobalSearch: true, enableSorting: true },
	{ id: 'path', label: __( 'Path', 'multisyde' ), enableGlobalSearch: true, enableSorting: true },
	{
		id: 'url',
		label: __( 'URL', 'multisyde' ),
		enableSorting: false,
		render: ( { item } ) => (
			<a href={ siteInfoUrl( item ) } className="ms-sites-url">
				{ item.domain + item.path }
			</a>
		),
	},
	{
		id: 'status',
		label: __( 'Status', 'multisyde' ),
		enableSorting: false,
		elements: [
			{ value: 'public', label: __( 'Public', 'multisyde' ) },
			{ value: 'private', label: __( 'Private', 'multisyde' ) },
			{ value: 'archived', label: __( 'Archived', 'multisyde' ) },
			{ value: 'spam', label: __( 'Spam', 'multisyde' ) },
			{ value: 'deleted', label: __( 'Deleted', 'multisyde' ) },
			{ value: 'mature', label: __( 'Mature', 'multisyde' ) },
		],
		filterBy: { operators: [ 'is' ] },
		render: ( { item } ) => {
			const labels = [];
			if ( Number( item.public ) !== 1 ) {
				labels.push( __( 'Private', 'multisyde' ) );
			}
			if ( Number( item.archived ) === 1 ) {
				labels.push( __( 'Archived', 'multisyde' ) );
			}
			if ( Number( item.spam ) === 1 ) {
				labels.push( __( 'Spam', 'multisyde' ) );
			}
			if ( Number( item.deleted ) === 1 ) {
				labels.push( __( 'Deleted', 'multisyde' ) );
			}
			if ( Number( item.mature ) === 1 ) {
				labels.push( __( 'Mature', 'multisyde' ) );
			}
			return labels.join( ', ' );
		},
	},
	{
		id: 'last_updated',
		label: __( 'Last Updated', 'multisyde' ),
		enableSorting: true,
		render: ( { item } ) => formatSiteDate( item.last_updated ),
	},
	{
		id: 'registered',
		label: __( 'Registered', 'multisyde' ),
		enableSorting: true,
		render: ( { item } ) => formatSiteDate( item.registered ),
	},
];
