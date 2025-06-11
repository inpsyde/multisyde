<?php
/**
 * Information class for the GetSiteBy feature.
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\MultiSyde\Modules\GetSiteBy;

use Syde\MultiSyde\Summary;
use Syde\MultiSyde\ShareableInformation;

/**
 * Provides information about the GetSiteBy feature.
 */
class About implements ShareableInformation {

	/**
	 * Get the feature information.
	 *
	 * @return Summary
	 */
	public static function get(): Summary {
		return new Summary(
			__( 'Introduce `get_site_by()` function for multisite', 'multisyde' ),
			__(
				'Provides a utility function to retrieve a site object from the multisite network using a specific field such as ID, slug, domain, path, or full URL. This makes it easier to locate subsites without relying on raw SQL or manual loops.',
				'multisyde'
			),
			array(
				'https://core.trac.wordpress.org/ticket/40180',
			)
		);
	}
}
