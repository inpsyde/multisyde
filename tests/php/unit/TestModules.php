<?php
/**
 * Modules Tests
 *
 * @package multisyde-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultisydeUnitTests;

use Syde\Multisyde\Modules;

/**
 * Test the Modules class.
 */
final class TestModules extends UnitTestCase {

	/**
	 * Test the static get_all method.
	 *
	 * @return void
	 */
	public function test_get_presentables() {
		$this->assertTrue( count( ( new Modules() )->get_presentable_features() ) > 0 );
	}
}
