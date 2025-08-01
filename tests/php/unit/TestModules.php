<?php
/**
 * Modules Tests
 *
 * @package multisyde-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultiSydeUnitTests;

use Syde\MultiSyde\Modules;

/**
 * Test the Modules class.
 */
final class TestModules extends UnitTestCase {

	/**
	 * Test the static get_all method.
	 *
	 * @return void
	 */
	public function test_features() {
		$this->assertTrue( count( Modules::init()->features() ) > 0 );
	}
}
