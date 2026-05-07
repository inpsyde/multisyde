const trimSlash = ( url ) => String( url || '' ).replace( /\/+$/, '' );

export const getNetworkAdminUrl = () =>
	window.MS_SITES_DATA?.networkAdminUrl || '/wp-admin/network/';

export const siteHomeUrl = ( item ) => item?.home || item?.siteurl || '';

export const siteAdminUrl = ( item ) => {
	const base = trimSlash( item?.siteurl || item?.home );
	return base ? `${ base }/wp-admin/` : '';
};

export const siteInfoUrl = ( item ) =>
	`${ getNetworkAdminUrl() }site-info.php?id=${ item?.id }`;
