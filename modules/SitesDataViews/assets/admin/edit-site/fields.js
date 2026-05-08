import { __ } from '@wordpress/i18n';
import DateTimeLocalEdit from '../utils/DateTimeLocalEdit';

/**
 * Build the DataForm field definitions for the Edit Site page.
 *
 * On the network's main site the URL field is omitted entirely (the
 * URL is shown as static text above the form) and the
 * archived/spam/deleted toggles are dropped from the section list — see
 * `buildForm`. The full field list is still returned so DataForm can
 * normalize them; only the form sections decide what's visible.
 */
export function buildFields() {
	return [
		{
			id: 'title',
			label: __( 'Site Title', 'multisyde' ),
			type: 'text',
			isValid: { required: true },
		},
		{
			id: 'url',
			label: __( 'Site Address (URL)', 'multisyde' ),
			type: 'text',
			description: __(
				'Enter the full URL, e.g. https://example.com/site/.',
				'multisyde'
			),
			isValid: { required: true },
		},
		{
			id: 'registered',
			label: __( 'Registered', 'multisyde' ),
			type: 'datetime',
			Edit: DateTimeLocalEdit,
		},
		{
			id: 'last_updated',
			label: __( 'Last Updated', 'multisyde' ),
			type: 'datetime',
			Edit: DateTimeLocalEdit,
		},
		{
			id: 'public',
			label: __( 'Public', 'multisyde' ),
			type: 'boolean',
		},
		{
			id: 'archived',
			label: __( 'Archived', 'multisyde' ),
			type: 'boolean',
		},
		{
			id: 'spam',
			label: __( 'Spam', 'multisyde' ),
			type: 'boolean',
		},
		{
			id: 'deleted',
			label: __( 'Deleted', 'multisyde' ),
			type: 'boolean',
		},
		{
			id: 'mature',
			label: __( 'Mature', 'multisyde' ),
			type: 'boolean',
		},
	];
}

/**
 * Build the DataForm `form` (sections) — the section list depends on
 * whether this is the main site (URL is shown statically above the
 * form, archived/spam/deleted are not editable for the main site).
 */
export function buildForm( { isMainSite = false } = {} ) {
	const infoChildren = isMainSite
		? [ 'title', 'registered', 'last_updated' ]
		: [ 'title', 'url', 'registered', 'last_updated' ];

	const statusFields = isMainSite
		? [ 'public', 'mature' ]
		: [ 'public', 'archived', 'spam', 'deleted', 'mature' ];

	return {
		type: 'regular',
		fields: [
			{
				id: 'site-info',
				label: __( 'Site Info', 'multisyde' ),
				children: infoChildren,
				layout: {
					type: 'card',
					withHeader: true,
					isOpened: true,
				},
			},
			{
				id: 'site-status',
				label: __( 'Site Status', 'multisyde' ),
				children: statusFields,
				layout: {
					type: 'card',
					withHeader: true,
					isOpened: true,
				},
			},
		],
	};
}

export const defaults = {
	title: '',
	url: '',
	registered: '',
	last_updated: '',
	public: true,
	archived: false,
	spam: false,
	deleted: false,
	mature: false,
};
