<?php
/**
 * ImprovementsLoader Tests
 *
 * @package multisite-improvements-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultisiteImprovementsUnitTests;

use Syde\MultisiteImprovements\ImprovementsLoader;
use Brain\Monkey\Functions;
use Brain\Monkey\Actions;

/**
 * Test the ImprovementsLoader class.
 *
 * @internal
 * @coversDefaultClass \Syde\MultisiteImprovements\ImprovementsLoader
 */
class TestImprovementsLoader extends UnitTestCase {

	/**
	 * Test the init method.
	 *
	 * @return void
	 */
	public function test_init() {
		Actions\expectAdded( 'init' );

		ImprovementsLoader::init();

		$this->expectNotToPerformAssertions();
	}

	/**
	 * Test the load method in a multisite.
	 *
	 * @return void
	 */
	public function test_load_multisite(): void {
		Functions\expect( 'function_exists' )->once()->with( 'is_multisite' )->andReturn( true );
        Functions\expect( 'is_network_admin' )->once()->with()->andReturn( false );

		ImprovementsLoader::load();

		$this->expectNotToPerformAssertions();
	}

	/**
	 * Test the load method in a single-site.
	 *
	 * @return void
	 */
	public function test_load_singlesite(): void {
		Functions\expect( 'function_exists' )->once()->with( 'is_multisite' )->andReturn( false );

		ImprovementsLoader::load();

		$this->expectNotToPerformAssertions();
	}
}
