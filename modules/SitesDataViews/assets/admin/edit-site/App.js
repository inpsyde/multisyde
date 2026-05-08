import { __, sprintf } from '@wordpress/i18n';
import { useEffect, useMemo, useState } from '@wordpress/element';
import { DataForm } from '@wordpress/dataviews/wp';
import {
	Button,
	Notice,
	Flex,
	FlexItem,
	Spinner,
} from '@wordpress/components';

import { buildFields, buildForm, defaults } from './fields';
import { setupApiFetch, getSite, updateSite } from './api';
import { parseSiteUrl, buildDisplayUrl } from '../utils/siteUrl';

const config = window.MS_EDIT_SITE_DATA || {};

setupApiFetch( config.nonce );

// SQL datetime ("YYYY-MM-DD HH:MM:SS") <-> datetime-local input value.
const sqlToInput = ( value ) =>
	value ? String( value ).replace( ' ', 'T' ) : '';
const inputToSql = ( value ) =>
	value ? String( value ).replace( 'T', ' ' ) : '';

const siteToFormData = ( site, fallbackScheme ) => ( {
	title: site?.blogname || '',
	url: buildDisplayUrl( site, fallbackScheme ),
	registered: sqlToInput( site?.registered ),
	last_updated: sqlToInput( site?.last_updated ),
	public: Boolean( Number( site?.public ?? 0 ) ),
	archived: Boolean( Number( site?.archived ?? 0 ) ),
	spam: Boolean( Number( site?.spam ?? 0 ) ),
	deleted: Boolean( Number( site?.deleted ?? 0 ) ),
	mature: Boolean( Number( site?.mature ?? 0 ) ),
} );

const tabLink = ( base, id ) =>
	`${ base }${ base.includes( '?' ) ? '&' : '?' }id=${ id }`;

const App = () => {
	const isMainSite = !! config.isMainSite;
	const siteId = Number( config.siteId || 0 );
	const fallbackScheme = config.defaultScheme || 'http';

	const [ data, setData ] = useState( defaults );
	const [ site, setSite ] = useState( null );
	const [ isLoading, setIsLoading ] = useState( true );
	const [ isSaving, setIsSaving ] = useState( false );
	const [ notice, setNotice ] = useState( null );

	const fields = useMemo( () => buildFields(), [] );
	const form = useMemo( () => buildForm( { isMainSite } ), [ isMainSite ] );

	useEffect( () => {
		let alive = true;
		setIsLoading( true );
		getSite( { restNs: config.restNs, id: siteId } )
			.then( ( result ) => {
				if ( ! alive ) {
					return;
				}
				setSite( result );
				setData( siteToFormData( result, fallbackScheme ) );
			} )
			.catch( ( error ) => {
				if ( ! alive ) {
					return;
				}
				setNotice( {
					status: 'error',
					message:
						error?.message ||
						__( 'Could not load site.', 'multisyde' ),
				} );
			} )
			.finally( () => {
				if ( alive ) {
					setIsLoading( false );
				}
			} );
		return () => {
			alive = false;
		};
	}, [ siteId, fallbackScheme ] );

	const onChange = ( edits ) =>
		setData( ( current ) => ( { ...current, ...edits } ) );

	const submit = async () => {
		setNotice( null );

		const payload = {
			title: data.title,
			registered: inputToSql( data.registered ),
			last_updated: inputToSql( data.last_updated ),
			public: data.public ? 1 : 0,
			mature: data.mature ? 1 : 0,
		};

		if ( ! isMainSite ) {
			const parsed = parseSiteUrl( data.url, fallbackScheme );
			if ( ! parsed ) {
				setNotice( {
					status: 'error',
					message: __(
						'Please enter a valid Site Address.',
						'multisyde'
					),
				} );
				return;
			}
			payload.domain = parsed.domain;
			payload.path = parsed.path;
			payload.archived = data.archived ? 1 : 0;
			payload.spam = data.spam ? 1 : 0;
			payload.deleted = data.deleted ? 1 : 0;
		}

		setIsSaving( true );
		try {
			const result = await updateSite( {
				restNs: config.restNs,
				id: siteId,
				data: payload,
			} );
			setSite( result );
			setData( siteToFormData( result, fallbackScheme ) );
			setNotice( {
				status: 'success',
				message: __( 'Site updated.', 'multisyde' ),
			} );
		} catch ( error ) {
			setNotice( {
				status: 'error',
				message:
					error?.message ||
					__( 'Could not update site.', 'multisyde' ),
			} );
		} finally {
			setIsSaving( false );
		}
	};

	const headerLabel = site
		? sprintf(
			/* translators: %s: site title. */
			__( 'Edit Site: %s', 'multisyde' ),
			site.blogname || buildDisplayUrl( site, fallbackScheme )
		)
		: __( 'Edit Site', 'multisyde' );

	const homeUrl = site ? site.home || site.siteurl || '' : '';
	const adminUrl = config.adminUrl || '';

	return (
		<div className="ms-edit-site">
			<h1 className="wp-heading-inline">{ headerLabel }</h1>

			{ ( homeUrl || adminUrl ) && (
				<p className="ms-edit-site__top-actions edit-site-actions">
					{ homeUrl && (
						<a href={ homeUrl }>
							{ __( 'Visit', 'multisyde' ) }
						</a>
					) }
					{ homeUrl && adminUrl && ' | ' }
					{ adminUrl && (
						<a href={ adminUrl }>
							{ __( 'Dashboard', 'multisyde' ) }
						</a>
					) }
				</p>
			) }

			<nav
				className="ms-edit-site__tabs nav-tab-wrapper wp-clearfix"
				aria-label={ __( 'Secondary menu', 'multisyde' ) }
			>
				<a
					href={ `${ config.networkAdminUrl || '' }sites.php?page=ms-edit-site&id=${ siteId }` }
					id="site-info"
					className="nav-tab nav-tab-active"
					aria-current="page"
				>
					{ __( 'Info', 'multisyde' ) }
				</a>
				<a
					href={ tabLink(
						`${ config.networkAdminUrl || '' }site-users.php`,
						siteId
					) }
					id="site-users"
					className="nav-tab"
				>
					{ __( 'Users', 'multisyde' ) }
				</a>
				<a
					href={ tabLink(
						`${ config.networkAdminUrl || '' }site-themes.php`,
						siteId
					) }
					id="site-themes"
					className="nav-tab"
				>
					{ __( 'Themes', 'multisyde' ) }
				</a>
				<a
					href={ tabLink(
						`${ config.networkAdminUrl || '' }site-settings.php`,
						siteId
					) }
					id="site-settings"
					className="nav-tab"
				>
					{ __( 'Settings', 'multisyde' ) }
				</a>
			</nav>

			{ notice && (
				<div className="ms-edit-site__form">
					<Notice
						status={ notice.status }
						isDismissible
						onRemove={ () => setNotice( null ) }
					>
						{ notice.message }
					</Notice>
				</div>
			) }

			<div className="ms-edit-site__form">
				{ isLoading ? (
					<Spinner />
				) : (
					<>
						{ isMainSite && site && (
							<p className="ms-edit-site__main-url">
								<strong>
									{ __(
										'Site Address (URL):',
										'multisyde'
									) }
								</strong>{ ' ' }
								<span>
									{ buildDisplayUrl( site, fallbackScheme ) }
								</span>
								<br />
								<em className="description">
									{ __(
										'The main site\'s address cannot be changed here.',
										'multisyde'
									) }
								</em>
							</p>
						) }
						<DataForm
							data={ data }
							fields={ fields }
							form={ form }
							onChange={ onChange }
						/>
						<Flex
							justify="flex-start"
							className="ms-edit-site__actions"
						>
							<FlexItem>
								<Button
									variant="primary"
									onClick={ submit }
									isBusy={ isSaving }
									disabled={ isSaving }
								>
									{ __( 'Save Changes', 'multisyde' ) }
								</Button>
							</FlexItem>
							<FlexItem>
								<Button
									variant="tertiary"
									href={ config.sitesListUrl }
									disabled={ isSaving }
								>
									{ __( 'Cancel', 'multisyde' ) }
								</Button>
							</FlexItem>
						</Flex>
					</>
				) }
			</div>
		</div>
	);
};

export default App;
