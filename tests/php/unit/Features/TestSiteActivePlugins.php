<?php
/**
 * SiteActivePlugins Tests
 *
 * @package multisite-improvements-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultisiteImprovementsUnitTests\Features;

use Syde\MultisiteImprovements\Features\SiteActivePlugins;
use Syde\MultisiteImprovementsUnitTests\UnitTestCase;
use Brain\Monkey\Functions;

/**
 * Test the SiteActivePlugins class.
 */
final class TestSiteActivePlugins extends UnitTestCase {

	/**
	 * Test the init method.
	 *
	 * @runInSeparateProcess
	 * @return void
	 */
	public function test_init(): void {
		Functions\expect( 'is_network_admin' )->once()->andReturn( true );

		SiteActivePlugins::init();
	}

	/**
	 * Test the add_thickbox method in the right page.
	 *
	 * @return void
	 */
	public function test_add_thickbox_right_page(): void {
		Functions\expect( 'get_current_screen' )->once()->andReturn( (object) array( 'id' => 'plugins-network' ) );
		Functions\expect( 'add_thickbox' )->once();

		SiteActivePlugins::add_thickbox();
	}

	/**
	 * Test the add_thickbox method in the wrong page.
	 *
	 * @return void
	 */
	public function test_add_thickbox_wrong_page(): void {
		$wp_screen = (object) array( 'id' => 'plugins' );

		Functions\expect( 'get_current_screen' )->once()->andReturn( $wp_screen );
		Functions\expect( 'add_thickbox' )->never();

		SiteActivePlugins::add_thickbox();
	}

	/**
	 * Test the maybe_show_notice method in other admin pages than the network.
	 *
	 * @return void
	 */
	public function test_maybe_show_notice_no_network(): void {
		Functions\expect( 'is_network_admin' )->once()->andReturn( false );

		SiteActivePlugins::maybe_show_notice();

		$this->expectOutputString( '' );
	}

	/**
	 * Test the maybe_show_notice method in the network admin page.
	 *
	 * @return void
	 */
	public function test_maybe_show_notice_network(): void {
		Functions\expect( 'is_network_admin' )->once()->andReturn( true );
		Functions\expect( 'sanitize_key' )->once()->andReturnFirstArg();
		Functions\expect( 'wp_unslash' )->once()->andReturnFirstArg();

		SiteActivePlugins::maybe_show_notice();

		$this->expectOutputString( '' );
	}

	/**
	 * Test the bulk_deactivate method with a user that is not a network admin.
	 *
	 * @return void
	 */
	public function test_bulk_deactivate_no_network_admin(): void {
		Functions\expect( 'current_user_can' )->once()->with( 'manage_network_plugins' )->andReturn( false );

		SiteActivePlugins::bulk_deactivate();
	}
}
