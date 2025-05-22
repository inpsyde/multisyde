<?php
/**
 * Class loads the get_site_by() function for multisite.
 *
 * @package multisyde
 */

declare( strict_types=1 );

namespace Syde\MultisiteImprovements\Modules\GetSiteBy;

use Syde\MultisiteImprovements\FeatureInformation;
use Syde\MultisiteImprovements\LoadableFeature;
use Syde\MultisiteImprovements\PresentableFeature;

/**
 * Class GetSiteBy
 */
class Feature implements LoadableFeature, PresentableFeature {

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

	/**
	 * Get the feature information.
	 *
	 * @return FeatureInformation
	 */
	public static function get_feature_information(): FeatureInformation {
		return new FeatureInformation(
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
