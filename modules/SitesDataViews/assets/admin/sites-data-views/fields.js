import { __ } from '@wordpress/i18n';
import { Badge } from '@wordpress/components';

export const fields = [
	{ id: 'blog_id', label: __( 'ID', 'multisyde' ), enableSorting: true },
	{ id: 'domain', label: __( 'Domain', 'multisyde' ), enableGlobalSearch: true, enableSorting: true },
	{ id: 'path',    label: __( 'Path', 'multisyde' ), enableGlobalSearch: true, enableSorting: true },
	{
		id: 'url',
		label: __( 'URL', 'multisyde' ),
		enableSorting: true,
		render: ( { item } ) => {
			return (
				<a href={ item.home_url } target="_blank" rel="noreferrer" className="ms-sites-url">
					{ item.domain }
					{ item.path }
				</a>
			);
		},
	},
	{
		id: 'status',
		label: __( 'Status', 'multisyde' ),
		enableSorting: false,
		render: ( { item } ) => (
			<div className="ms-sites-chips">
				{ Number(item.public) !== 1 && <span className="ms-chip">{ __( 'Private', 'multisyde' ) }</span> }
				{ Number(item.archived) === 1 && <span className="ms-chip ms-chip--warn">{ __( 'Archived', 'multisyde' ) }</span> }
				{ Number(item.spam) === 1 && <span className="ms-chip ms-chip--danger">{ __( 'Spam', 'multisyde' ) }</span> }
				{ Number(item.deleted) === 1 && <span className="ms-chip ms-chip--danger">{ __( 'Deleted', 'multisyde' ) }</span> }
				{ Number(item.mature) === 1 && <span className="ms-chip ms-chip--warn">{ __( 'Mature', 'multisyde' ) }</span> }
			</div>
		)
	},
	{
		id: 'public',
		label: __( 'Public', 'multisyde' ),
		type: 'boolean',
		enableSorting: false,
		elements: [ { value: 1, label: __( 'Yes', 'multisyde' ) }, { value: 0, label: __( 'No', 'multisyde' ) } ],
		filterBy: { operators: [ 'isAny' ] },
	},
	{
		id: 'mature',
		label: __( 'Mature', 'multisyde' ),
		type: 'boolean',
		enableSorting: false,
		elements: [ { value: 1, label: __( 'Yes', 'multisyde' ) }, { value: 0, label: __( 'No', 'multisyde' ) } ],
		filterBy: { operators: [ 'isAny' ] },
	},
	{
		id: 'archived',
		label: __( 'Archived', 'multisyde' ),
		type: 'boolean',
		enableSorting: false,
		elements: [ { value: 1, label: __( 'Yes', 'multisyde' ) }, { value: 0, label: __( 'No', 'multisyde' ) } ],
		filterBy: { operators: [ 'isAny' ] },
	},
	{
		id: 'spam',
		label: __( 'Spam', 'multisyde' ),
		type: 'boolean',
		enableSorting: false,
		elements: [ { value: 1, label: __( 'Yes', 'multisyde' ) }, { value: 0, label: __( 'No', 'multisyde' ) } ],
		filterBy: { operators: [ 'isAny' ] },
	},
	{
		id: 'deleted',
		label: __( 'Deleted', 'multisyde' ),
		type: 'boolean',
		enableSorting: false,
		elements: [ { value: 1, label: __( 'Yes', 'multisyde' ) }, { value: 0, label: __( 'No', 'multisyde' ) } ],
		filterBy: { operators: [ 'isAny' ] },
	},
	{ id: 'last_updated', label: __( 'Last Updated', 'multisyde' ), enableSorting: true },
	{ id: 'registered',   label: __( 'Registered', 'multisyde' ), enableSorting: true },
];
