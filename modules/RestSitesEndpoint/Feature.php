<?php
/**
 * Class loads the `wp/v2/sites` REST API endpoint for multisite.
 *
 * @package multisyde
 */

declare( strict_types=1 );

namespace Syde\MultiSyde\Modules\RestSitesEndpoint;

use Syde\MultiSyde\LoadableFeature;

/**
 * Feature Class RestSitesEndpoint
 */
class Feature implements LoadableFeature {

	/**
	 * Load the feature unless WordPress core already ships the controller.
	 *
	 * @return void
	 */
	public static function init(): void {
		if ( class_exists( 'WP_REST_Sites_Controller' ) ) {
			return;
		}

		add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
	}

	/**
	 * Loads the core patches and registers the sites routes.
	 *
	 * @return void
	 */
	public static function register_routes(): void {
		require_once __DIR__ . '/patches/class-wp-rest-site-meta-fields.php';
		require_once __DIR__ . '/patches/class-wp-rest-sites-controller.php';

		( new \WP_REST_Sites_Controller() )->register_routes();
	}
}
