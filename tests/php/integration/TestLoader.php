<?php
/**
 * Loader Tests
 *
 * @package multisite-improvements-integration-tests
 */

declare( strict_types=1 );

namespace Syde\MultisiteImprovementsIntegrationTests;

use Syde\MultisiteImprovements\ImprovementsLoader;

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
		$this->assertTrue( ImprovementsLoader::init() );
	}
}
