import { __, sprintf } from '@wordpress/i18n';

const config = window.MS_ADD_SITE_DATA || {};

const blogNameHelp = config.isSubdomain
	? sprintf(
		/* translators: %s: full URL preview, e.g. http://example.example.com */
		__( 'The full URL will be: %s', 'multisyde' ),
		`${ config.siteUrlScheme || 'http' }://<subdomain>.${ ( config.networkDomain || '' ).replace( /^www\./, '' ) }${ config.networkPath || '/' }`
	)
	: sprintf(
		/* translators: %s: full URL preview, e.g. http://example.com/site */
		__( 'The full URL will be: %s', 'multisyde' ),
		`${ config.siteUrlScheme || 'http' }://${ config.networkDomain || '' }${ config.networkPath || '/' }<path>`
	);

const blogNamePlaceholder = config.isSubdomain
	? __( 'subdomain', 'multisyde' )
	: __( 'site-path', 'multisyde' );

export const fields = [
	{
		id: 'title',
		label: __( 'Site Title', 'multisyde' ),
		type: 'text',
		isValid: { required: true },
	},
	{
		id: 'blogname',
		label: config.isSubdomain
			? __( 'Site Address (subdomain)', 'multisyde' )
			: __( 'Site Address (path)', 'multisyde' ),
		type: 'text',
		placeholder: blogNamePlaceholder,
		description: blogNameHelp,
		isValid: { required: true },
	},
	{
		id: 'email',
		label: __( 'Admin Email', 'multisyde' ),
		type: 'email',
		description: __(
			'A new user will be created if the email address is not already in use.',
			'multisyde'
		),
		isValid: { required: true },
	},
	{
		id: 'locale',
		label: __( 'Site Language', 'multisyde' ),
		type: 'text',
		elements: ( config.languages && config.languages.length )
			? config.languages
			: [ { value: '', label: __( 'Site Default', 'multisyde' ) } ],
	},
];

export const form = {
	type: 'regular',
	fields: [
		{
			id: 'site-details',
			label: __( 'Site Details', 'multisyde' ),
			children: [ 'blogname', 'title', 'email' ],
			layout: {
				type: 'card',
				withHeader: true,
				isOpened: true,
			},
		},
		{
			id: 'settings',
			label: __( 'Settings', 'multisyde' ),
			children: [ 'locale' ],
			layout: {
				type: 'card',
				withHeader: true,
				isOpened: true,
			},
		},
	],
};

export const defaults = {
	blogname: '',
	title: '',
	email: '',
	locale: config.defaultLanguage || '',
};
