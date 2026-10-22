<?php
/**
 * Information class for the RestSitesEndpoint feature.
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\MultiSyde\Modules\RestSitesEndpoint;

use Syde\MultiSyde\Summary;
use Syde\MultiSyde\ShareableInformation;

/**
 * Provides information about the RestSitesEndpoint feature.
 */
class About implements ShareableInformation {

	/**
	 * Get the feature information.
	 *
	 * @return Summary
	 */
	public static function get(): Summary {
		return new Summary(
			__( 'REST API endpoint for sites (`wp/v2/sites`)', 'multisyde' ),
			__(
				'Adds the `wp/v2/sites` REST API endpoint to list, create, update and delete sites in a multisite network, including site meta. It is the foundation for modern Network Admin screens built with DataViews and DataForm.',
				'multisyde'
			),
			array(
				'https://core.trac.wordpress.org/ticket/40365',
			)
		);
	}
}
