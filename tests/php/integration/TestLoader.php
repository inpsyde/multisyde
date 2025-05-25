<?php
/**
 * Loader Tests
 *
 * @package multisyde-integration-tests
 */

declare( strict_types=1 );

namespace Syde\MultisydeIntegrationTests;

use Syde\Multisyde\Multisyde;

/**
 * Test the Loader class.
 *
 * @internal
 * @coversDefaultClass \Syde\MultisiteImprovements\ImprovementsLoader
 */
class TestLoader extends UnitTestCase {

	/**
	 * Test the init method.
	 *
	 * @return void
	 */
	public function test_init(): void {
		// Should return true if the test-env is a multisite.
		$this->assertTrue( Multisyde::init() );
	}
}
