<?php
/**
 * Class loads the get_site_by() function for multisite.
 *
 * @package multisyde
 */

declare( strict_types=1 );

namespace Syde\Multisyde\Modules\GetSiteBy;

use Syde\Multisyde\LoadableFeature;

/**
 * Class Feature
 */
class Feature implements LoadableFeature {

	/**
	 * Load the feature.
	 *
	 * @return void
	 */
	public static function init(): void {
		if ( ! function_exists( 'get_site_by' ) ) {
			require_once __DIR__ . '/patches/40180.php';
		}
	}
}
