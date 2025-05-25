<?php
/**
 * Multisyde Tests
 *
 * @package multisyde-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultisydeUnitTests;

use Brain\Monkey\Actions;
use Brain\Monkey\Functions;
use Syde\Multisyde\Multisyde;

/**
 * Test the ImprovementsLoader class.
 */
final class TestMultisyde extends UnitTestCase {

	/**
	 * Test the init method.
	 *
	 * @return void
	 */
	public function test_init() {
		Actions\expectAdded( 'init' );

		Multisyde::init();
	}

	/**
	 * Test the load method in a multisite.
	 *
	 * @runInSeparateProcess
	 * @return void
	 */
	public function test_load_multisite(): void {
		Functions\expect( 'function_exists' )->once()->with( 'is_multisite' )->andReturn( true );
		Functions\expect( 'is_network_admin' )->once()->andReturn( false );

		Multisyde::load();
	}

	/**
	 * Test the load method in a single-site.
	 *
	 * @return void
	 */
	public function test_load_singlesite(): void {
		Functions\expect( 'function_exists' )->once()->with( 'is_multisite' )->andReturn( false );

		Multisyde::load();
	}
}
