<?php
/**
 * FeatureRegistry Tests
 *
 * @package multisite-improvements-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultisiteImprovementsUnitTests;

use Syde\MultisiteImprovements\FeatureRegistry;

/**
 * Test the FeatureRegistry class.
 *
 * @internal
 * @coversDefaultClass \Syde\MultisiteImprovements\FeatureRegistry
 */
class TestFeatureRegistry extends UnitTestCase {

	/**
	 * Test the static get_all method.
	 *
	 * @return void
	 */
	public function test_init() {
		$this->assertCount( 1, FeatureRegistry::get_all() );
	}
}
