<?php
/**
 * RestSitesEndpoint Tests
 *
 * @package multisyde-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultiSyde\Modules\RestSitesEndpoint\tests\unit;

use Syde\MultiSyde\Modules\RestSitesEndpoint\Feature;
use Syde\MultiSydeUnitTests\UnitTestCase;

/**
 * Test the RestSitesEndpoint class.
 */
final class TestFeature extends UnitTestCase {

	/**
	 * Nothing is loaded when core already provides the controller.
	 *
	 * @runInSeparateProcess
	 * @return void
	 */
	public function test_init_core_provides_controller(): void {
		eval( 'class WP_REST_Sites_Controller {}' ); // phpcs:ignore Squiz.PHP.Eval.Discouraged

		Feature::init();

		$this->assertFalse( has_action( 'rest_api_init', array( Feature::class, 'register_routes' ) ) );
	}

	/**
	 * The routes are hooked otherwise.
	 *
	 * @return void
	 */
	public function test_init_hooks_routes(): void {
		Feature::init();

		$this->assertSame( 10, has_action( 'rest_api_init', array( Feature::class, 'register_routes' ) ) );
	}
}
