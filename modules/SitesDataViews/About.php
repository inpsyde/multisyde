<?php
/**
 * Information class for the SitesDataViews feature.
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\MultiSyde\Modules\SitesDataViews;

use Syde\MultiSyde\Summary;
use Syde\MultiSyde\ShareableInformation;

/**
 * Provides information about the SitesDataViews feature.
 */
class About implements ShareableInformation {

	/**
	 * Get the feature information.
	 *
	 * @return Summary
	 */
	public static function get(): Summary {
		return new Summary(
			__( 'Sites — DataViews (prototype)', 'multisyde' ),
			__( 'Experimental Network Admin screen that renders the Sites list using DataViews and DataForm. Includes global search, sorting, basic filters, and inline editing of selected site flags via REST.', 'multisyde' ),
			array()
		);
	}
}
