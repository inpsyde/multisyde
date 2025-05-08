<?php
/**
 * Loader Tests
 *
 * @package multisite-improvements-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultisiteImprovementsUnitTests;

use Syde\MultisiteImprovements\Loader;
use Brain\Monkey\Functions;

/**
 * Test the Loader class.
 *
 * @internal
 * @coversDefaultClass \Syde\MultisiteImprovements\Loader
 */
class TestLoader extends UnitTestCase {

	/**
	 * Test the init method in a multisite.
	 *
	 * @return void
	 */
	public function test_init_multisite(): void {
		Functions\expect( 'function_exists' )->once()->with( 'is_multisite' )->andReturn( true );

		$this->assertTrue( Loader::init() );
	}

	/**
	 * Test the init method in a single-site.
	 *
	 * @return void
	 */
	public function test_init_singlesite(): void {
		Functions\expect( 'function_exists' )->once()->with( 'is_multisite' )->andReturn( false );

		$this->assertFalse( Loader::init() );
	}
}
