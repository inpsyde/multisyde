/**
 * URL helpers shared by the sites list, the Add Site form, and the
 * Edit Site form. The functions read the entry-specific inline config
 * (`window.MS_SITES_DATA`, `window.MS_ADD_SITE_DATA`, or
 * `window.MS_EDIT_SITE_DATA`) — only one is set per page load.
 */

const trimSlash = ( url ) => String( url || '' ).replace( /\/+$/, '' );

const getConfig = () =>
	window.MS_SITES_DATA ||
	window.MS_ADD_SITE_DATA ||
	window.MS_EDIT_SITE_DATA ||
	{};

export const getNetworkAdminUrl = () =>
	getConfig().networkAdminUrl || '/wp-admin/network/';

export const siteHomeUrl = ( item ) => item?.home || item?.siteurl || '';

export const siteAdminUrl = ( item ) => {
	const base = trimSlash( item?.siteurl || item?.home );
	return base ? `${ base }/wp-admin/` : '';
};

export const siteEditUrl = ( item ) =>
	`${ getNetworkAdminUrl() }sites.php?page=ms-edit-site&id=${ item?.id }`;

/**
 * Parse a user-entered Site Address (URL) into the component pieces the
 * REST controller expects (`domain`, `path`, `scheme`).
 *
 * Mirrors the legacy `wp-admin/network/site-info.php` URL handling:
 *   - falls back to the default scheme when the user omits one
 *   - host is lowercased and may include a port
 *   - path is normalized: leading slash, trailing slash, no doubles
 *   - default path is `/`
 *
 * Returns `null` when the input cannot be parsed into a host.
 */
export function parseSiteUrl( input, fallbackScheme = 'http' ) {
	const raw = String( input || '' ).trim();
	if ( ! raw ) {
		return null;
	}

	let withScheme = raw;
	if ( ! /^[a-zA-Z][a-zA-Z0-9+\-.]*:\/\//.test( raw ) ) {
		withScheme = `${ fallbackScheme }://${ raw.replace( /^\/+/, '' ) }`;
	}

	let parsed;
	try {
		parsed = new URL( withScheme );
	} catch ( e ) {
		return null;
	}

	const scheme = parsed.protocol.replace( /:$/, '' ) || fallbackScheme;
	const host = parsed.host.toLowerCase();
	if ( ! host ) {
		return null;
	}

	let path = parsed.pathname || '/';
	path = path.replace( /\/+/g, '/' );
	if ( ! path.startsWith( '/' ) ) {
		path = `/${ path }`;
	}
	if ( ! path.endsWith( '/' ) ) {
		path = `${ path }/`;
	}

	return { scheme, domain: host, path };
}

/**
 * Compose a display URL from a site object returned by the REST controller.
 * Prefers `siteurl` (which includes scheme), falls back to building one
 * from `domain`/`path` plus the configured default scheme.
 */
export function buildDisplayUrl( site, fallbackScheme = 'http' ) {
	if ( site?.siteurl ) {
		return site.siteurl;
	}
	const domain = site?.domain || '';
	const path = site?.path || '/';
	if ( ! domain ) {
		return '';
	}
	return `${ fallbackScheme }://${ domain }${ path }`;
}
