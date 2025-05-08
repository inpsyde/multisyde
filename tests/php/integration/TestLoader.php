<?php
/**
 * Loader Tests
 *
 * @package multisite-improvements-integration-tests
 */

declare( strict_types=1 );

namespace Syde\MultisiteImprovementsIntegrationTests;

use Syde\MultisiteImprovements\Loader;

/**
 * Test the Loader class.
 *
 * @internal
 * @coversDefaultClass \Syde\MultisiteImprovements\Loader
 */
class TestLoader extends UnitTestCase {

	/**
	 * Test if the installation is a multisite
	 *
	 * @return void
	 */
	public function test_is_multisite(): void {
		$this->assertTrue( is_multisite() );
	}

	/**
	 * Test the init method.
	 *
	 * @return void
	 */
	public function test_init(): void {
		$this->expectNotToPerformAssertions();

		Loader::init();
	}
}
