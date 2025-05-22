<?php
/**
 * FeatureRegistry Tests
 *
 * @package multisyde-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultisiteImprovementsUnitTests\src;

use Syde\MultisiteImprovements\FeatureRegistry;
use Syde\MultisiteImprovementsUnitTests\UnitTestCase;

/**
 * Test the FeatureRegistry class.
 */
final class TestFeatureRegistry extends UnitTestCase {

	/**
	 * Test the static get_all method.
	 *
	 * @return void
	 */
	public function test_get_presentable_classes() {
		$this->assertTrue( count( FeatureRegistry::get_presentable_classes() ) > 0 );
	}
}
