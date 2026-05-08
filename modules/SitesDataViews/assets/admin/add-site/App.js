import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { DataForm } from '@wordpress/dataviews/wp';
import { Button, Notice, Flex, FlexItem } from '@wordpress/components';

import { fields, form, defaults } from './fields';
import { setupApiFetch, createSite } from './api';

const config = window.MS_ADD_SITE_DATA || {};

setupApiFetch( config.nonce );

const App = () => {
	const [ data, setData ] = useState( defaults );
	const [ isSaving, setIsSaving ] = useState( false );
	const [ notice, setNotice ] = useState( null );

	const onChange = ( edits ) =>
		setData( ( current ) => ( { ...current, ...edits } ) );

	const submit = async () => {
		setNotice( null );
		setIsSaving( true );

		try {
			const result = await createSite( {
				restNs: config.restNs,
				data: {
					slug: data.blogname,
					title: data.title,
					email: data.email,
					locale: data.locale || '',
				},
			} );

			const editUrl = result?.id
				? `${ config.networkAdminUrl || '' }sites.php?page=ms-edit-site&id=${ result.id }`
				: undefined;

			setNotice( {
				status: 'success',
				message: __( 'Site created successfully.', 'multisyde' ),
				editUrl,
			} );
			setData( defaults );
		} catch ( error ) {
			setNotice( {
				status: 'error',
				message: error?.message || __( 'Could not create site.', 'multisyde' ),
			} );
		} finally {
			setIsSaving( false );
		}
	};

	return (
		<div className="ms-add-site">
			<h1 className="wp-heading-inline">
				{ __( 'Add New Site', 'multisyde' ) }
			</h1>

			{ notice && (
				<Notice
					status={ notice.status }
					isDismissible
					onRemove={ () => setNotice( null ) }
				>
					{ notice.message }
					{ notice.editUrl && (
						<>
							{ ' ' }
							<a href={ notice.editUrl }>
								{ __( 'Edit site', 'multisyde' ) }
							</a>
						</>
					) }
				</Notice>
			) }

			<div className="ms-add-site__form">
				<DataForm
					data={ data }
					fields={ fields }
					form={ form }
					onChange={ onChange }
				/>

				<Flex justify="flex-start" className="ms-add-site__actions">
					<FlexItem>
						<Button
							variant="primary"
							onClick={ submit }
							isBusy={ isSaving }
							disabled={ isSaving }
						>
							{ __( 'Add Site', 'multisyde' ) }
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
			</div>
		</div>
	);
};

export default App;
