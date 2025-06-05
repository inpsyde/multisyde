<?php
/**
 * MultiSyde Tests
 *
 * @package multisyde-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultiSydeUnitTests;

use Brain\Monkey\Actions;
use Brain\Monkey\Functions;
use Syde\MultiSyde\MultiSyde;

/**
 * Test the ImprovementsLoader class.
 */
final class TestMultiSyde extends UnitTestCase {

	/**
	 * Test the init method.
	 *
	 * @return void
	 */
	public function test_init() {
		Actions\expectAdded( 'init' );

		MultiSyde::init();
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

		MultiSyde::load();
	}

	/**
	 * Test the load method in a single-site.
	 *
	 * @return void
	 */
	public function test_load_singlesite(): void {
		Functions\expect( 'function_exists' )->once()->with( 'is_multisite' )->andReturn( false );

		MultiSyde::load();
	}
}
