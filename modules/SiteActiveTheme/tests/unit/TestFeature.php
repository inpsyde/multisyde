<?php
/**
 * SiteActiveTheme Tests
 *
 * @package multisyde-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultiSyde\Modules\SiteActiveTheme\tests\unit;

use Brain\Monkey\Functions;
use Brain\Monkey\Filters;
use Mockery\Exception;
use Mockery\Mock;
use Syde\MultiSyde\Modules\SiteActiveTheme\Feature;
use Syde\MultiSydeUnitTests\UnitTestCase;
use WP_Theme;

/**
 * Test the SiteActiveTheme class.
 */
final class TestFeature extends UnitTestCase {

	/**
	 * Test the init method.
	 *
	 * @runInSeparateProcess
	 * @return void
	 */
	public function test_init(): void {
		Functions\expect( 'is_network_admin' )->once()->andReturn( true );

		Feature::init();
	}

	/**
	 * Test the static add_column method.
	 *
	 * @return void
	 */
	public function test_add_column(): void {
		$expected = array( Feature::COLUMN_NAME => 'Active Theme' );

		$this->assertSame( $expected, Feature::add_column( array() ) );
	}

	/**
	 * Test the static manage_custom_column method with any other column name incoming.
	 *
	 * @return void
	 */
	public function test_manage_custom_column_any_other_column(): void {
		Functions\expect( 'switch_to_blog' )->never();
		Functions\expect( 'restore_current_blog' )->never();

		Feature::manage_custom_column( 'abitrary_column', 1 );
	}

	/**
	 * Test the static manage_custom_column method with a theme without a parent theme.
	 *
	 * @return void
	 */
	public function test_manage_custom_column_theme_without_parent_theme(): void {
		$theme = \Mockery::mock( WP_Theme::class );
		$theme->shouldReceive( 'get' )->twice()->andReturnArg( 0 );
		$theme->shouldReceive( 'parent' )->once()->andReturn( false );

		Functions\expect( 'switch_to_blog' )->once();
		Functions\expect( 'wp_get_theme' )->once()->andReturn( $theme );
		Functions\expect( 'restore_current_blog' )->once();

		$this->expectOutputString( 'Name Version' );

		Feature::manage_custom_column( Feature::COLUMN_NAME, 1 );
	}

	/**
	 * Test the static manage_custom_column method with a theme with a parent theme.
	 *
	 * @return void
	 */
	public function test_manage_custom_column_theme_with_parent_theme(): void {
		$parent = \Mockery::mock( WP_Theme::class );
		$parent->shouldReceive( 'get' )->once()->andReturn( 'Parent Theme' );

		$theme = \Mockery::mock( WP_Theme::class );
		$theme->shouldReceive( 'get' )->twice()->andReturnArg( 0 );
		$theme->shouldReceive( 'parent' )->once()->andReturn( $parent );

		Functions\expect( 'switch_to_blog' )->once();
		Functions\expect( 'wp_get_theme' )->once()->andReturn( $theme );
		Functions\expect( 'restore_current_blog' )->once();

		$this->expectOutputString( 'Name Version (Parent Theme)' );

		Feature::manage_custom_column( Feature::COLUMN_NAME, 1 );
	}
}
