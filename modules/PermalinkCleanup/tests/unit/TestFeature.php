<?php
/**
 * PermalinkCleanup Tests
 *
 * @package multisyde-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultiSyde\Modules\PermalinkCleanup\tests\unit;

use Brain\Monkey\Functions;
use Brain\Monkey\Filters;
use Syde\MultiSyde\Modules\PermalinkCleanup\Feature;
use Syde\MultiSydeUnitTests\UnitTestCase;

/**
 * Test the PermalinkCleanup class.
 */
final class TestFeature extends UnitTestCase {


	/**
	 * Test that init registers the correct filters.
	 *
	 * @covers Feature::init
	 *
	 * @return void
	 */
	public function test_registers_filters(): void {
		Filters\expectAdded( 'sanitize_option_permalink_structure' )
			->with( array( Feature::class, 'remove_blog_prefix' ) )
			->once();
		Filters\expectAdded( 'option_permalink_structure' )
			->with( array( Feature::class, 'remove_blog_prefix' ) )
			->once();

		Feature::init();
	}

	/**
	 * Test that /blog prefix is removed on main site in multisite.
	 *
	 * @covers Feature::remove_blog_prefix
	 *
	 * @return void
	 */
	public function test_removes_blog_prefix_on_main_site(): void {
		Functions\when( 'is_multisite' )->justReturn( true );
		Functions\when( 'is_main_site' )->justReturn( true );

		$result = Feature::remove_blog_prefix( '/blog/%postname%/' );

		$this->assertSame( '/%postname%/', $result );
	}

	/**
	 * Test that /blog prefix is removed with various permalink structures.
	 *
	 * @covers Feature::remove_blog_prefix
	 * @dataProvider blog_prefix_permalink_provider
	 *
	 * @param string $input    The input permalink structure.
	 * @param string $expected The expected output.
	 *
	 * @return void
	 */
	public function test_removes_blog_prefix_various_structures(
		string $input,
		string $expected
	): void {
		Functions\when( 'is_multisite' )->justReturn( true );
		Functions\when( 'is_main_site' )->justReturn( true );

		$result = Feature::remove_blog_prefix( $input );

		$this->assertSame( $expected, $result );
	}

	/**
	 * Data provider for various permalink structures with /blog prefix.
	 *
	 * @return array<string, array<string, string>>
	 */
	public function blog_prefix_permalink_provider(): array {
		return array(
			'simple postname'           => array(
				'input'    => '/blog/%postname%/',
				'expected' => '/%postname%/',
			),
			'year month postname'       => array(
				'input'    => '/blog/%year%/%monthnum%/%postname%/',
				'expected' => '/%year%/%monthnum%/%postname%/',
			),
			'category postname'         => array(
				'input'    => '/blog/%category%/%postname%/',
				'expected' => '/%category%/%postname%/',
			),
			'just /blog'                => array(
				'input'    => '/blog',
				'expected' => '',
			),
			'/blog with trailing slash' => array(
				'input'    => '/blog/',
				'expected' => '/',
			),
			'numeric structure'         => array(
				'input'    => '/blog/%year%/%monthnum%/%day%/%postname%/',
				'expected' => '/%year%/%monthnum%/%day%/%postname%/',
			),
		);
	}

	/**
	 * Test that value is unchanged when not on multisite.
	 *
	 * @covers Feature::remove_blog_prefix
	 *
	 * @return void
	 */
	public function test_does_not_remove_prefix_when_not_multisite(): void {
		Functions\when( 'is_multisite' )->justReturn( false );
		Functions\when( 'is_main_site' )->justReturn( true );

		$result = Feature::remove_blog_prefix( '/blog/%postname%/' );

		$this->assertSame( '/blog/%postname%/', $result );
	}

	/**
	 * Test that value is unchanged when not on main site.
	 *
	 * @covers Feature::remove_blog_prefix
	 *
	 * @return void
	 */
	public function test_does_not_remove_prefix_when_not_main_site(): void {
		Functions\when( 'is_multisite' )->justReturn( true );
		Functions\when( 'is_main_site' )->justReturn( false );

		$result = Feature::remove_blog_prefix( '/blog/%postname%/' );

		$this->assertSame( '/blog/%postname%/', $result );
	}

	/**
	 * Test that value is unchanged when both conditions are false.
	 *
	 * @covers Feature::remove_blog_prefix
	 *
	 * @return void
	 */
	public function test_does_not_remove_prefix_when_neither_condition_met(): void {
		Functions\when( 'is_multisite' )->justReturn( false );
		Functions\when( 'is_main_site' )->justReturn( false );

		$result = Feature::remove_blog_prefix( '/blog/%postname%/' );

		$this->assertSame( '/blog/%postname%/', $result );
	}

	/**
	 * Test that empty string is handled correctly.
	 *
	 * @covers Feature::remove_blog_prefix
	 *
	 * @return void
	 */
	public function test_handles_empty_string(): void {
		Functions\when( 'is_multisite' )->justReturn( true );
		Functions\when( 'is_main_site' )->justReturn( true );

		$result = Feature::remove_blog_prefix( '' );

		$this->assertSame( '', $result );
	}

	/**
	 * Test that permalink without /blog prefix is unchanged.
	 *
	 * @covers Feature::remove_blog_prefix
	 *
	 * @return void
	 */
	public function test_does_not_modify_permalink_without_blog_prefix(): void {
		Functions\when( 'is_multisite' )->justReturn( true );
		Functions\when( 'is_main_site' )->justReturn( true );

		$result = Feature::remove_blog_prefix( '/%year%/%monthnum%/%postname%/' );

		$this->assertSame( '/%year%/%monthnum%/%postname%/', $result );
	}

	/**
	 * Test that /blog in the middle of structure is not removed.
	 *
	 * @covers Feature::remove_blog_prefix
	 *
	 * @return void
	 */
	public function test_does_not_remove_blog_from_middle_of_structure(): void {
		Functions\when( 'is_multisite' )->justReturn( true );
		Functions\when( 'is_main_site' )->justReturn( true );

		$result = Feature::remove_blog_prefix( '/%category%/blog/%postname%/' );

		$this->assertSame( '/%category%/blog/%postname%/', $result );
	}

	/**
	 * Test that similar prefixes are not affected.
	 *
	 * @covers Feature::remove_blog_prefix
	 *
	 * @return void
	 */
	public function test_does_not_remove_similar_prefixes(): void {
		Functions\when( 'is_multisite' )->justReturn( true );
		Functions\when( 'is_main_site' )->justReturn( true );

		$result = Feature::remove_blog_prefix( '/blogger/%postname%/' );

		$this->assertSame( '/blogger/%postname%/', $result );
	}
}
