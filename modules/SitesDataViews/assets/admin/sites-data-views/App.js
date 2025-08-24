// modules/SitesDataViews/assets/admin/sites-data-views/App.js
import { Fragment } from '@wordpress/element';
import { DataViews } from '@wordpress/dataviews/wp';

import { fields } from './fields';
import { defaultLayouts, initialView } from './viewDefaults';
import { setupApiFetch } from './api';
import useSites from './hooks/useSites';
import { buildActions } from './actions';

setupApiFetch( window.MS_SITES_DATA?.nonce );

const App = () => {
	const { rows, view, setView, isLoading, paginationInfo } = useSites( initialView );
	const actions = buildActions();

	return (
		<Fragment>
			<DataViews
				data={ rows }
				fields={ fields }
				view={ view }
				onChangeView={ setView }
				defaultLayouts={ defaultLayouts }
				actions={ actions }
				isLoading={ isLoading }
				getItemId={ (item) => String( item.id ) }
				paginationInfo={ paginationInfo }
			/>
		</Fragment>
	);
};

export default App;