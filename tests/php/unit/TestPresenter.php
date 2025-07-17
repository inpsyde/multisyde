<?php
/**
 * This class tests the Presenter class.
 *
 * @package multisyde-unit-tests
 */

declare(strict_types = 1);

namespace Syde\MultiSydeUnitTests;

use Brain\Monkey\Actions;
use Brain\Monkey\Filters;
use Brain\Monkey\Functions;
use Syde\MultiSyde\Modules;
use Syde\MultiSyde\Presenter;

/**
 * Test the Presenter class.
 */
class TestPresenter extends UnitTestCase {

	/**
	 * Test the init method.
	 *
	 * @return void
	 */
	public function test_init(): void {
		$modules = \Mockery::mock( Modules::class );

		Actions\expectAdded( 'network_admin_menu' );
		Filters\expectAdded( 'admin_footer_text' );

		Presenter::init( $modules );
	}

	/**
	 * Test the static get_all method.
	 *
	 * @return void
	 */
	public function test_add_network_admin_menu() {
		$modules = \Mockery::mock( Modules::class );
		$test    = new Presenter( $modules );

		Functions\expect( 'add_menu_page' )->once()->with(
			'MultiSyde',
			'MultiSyde',
			'manage_network',
			'multisyde',
			array( $test, 'render_overview_page' ),
			'dashicons-heart',
		);

		$test->add_network_admin_menu();
	}

	/**
	 * Test the static render_overview_page method.
	 *
	 * @return void
	 */
	public function test_render_overview_page(): void {
		$modules = \Mockery::mock( Modules::class );
		$modules->shouldReceive( 'features' )->andReturn(
			array(
				Modules\SiteActivePlugins\Feature::class => Modules\SiteActivePlugins\About::class,
				Modules\GetSiteBy\Feature::class         => Modules\GetSiteBy\About::class,
			)
		);

		( new Presenter( $modules ) )->render_overview_page();

		$this->expectOutputRegex( '/.*MultiSyde.*This plugin provides various improvements for WordPress multisite installations.*/' );
	}

	/**
	 * Test the get_admin_footer_text method.
	 *
	 * @return void
	 */
	public function test_get_admin_footer_text(): void {
		Functions\expect( 'get_current_screen' )->andReturn( (object) array( 'id' => 'toplevel_page_multisyde-network' ) );

		$result   = Presenter::get_admin_footer_text( 'Original footer text' );
		$expected = 'Made with <span class="dashicons dashicons-heart"></span> by <a href="https://syde.com" target="_blank" rel="noopener noreferrer">Syde</a>.';

		$this->assertEquals( $expected, $result );
	}

	/**
	 * Test the get_admin_footer_text method when not on the MultiSyde network admin page.
	 *
	 * @return void
	 */
	public function test_get_admin_footer_text_elsewhere(): void {
		Functions\expect( 'get_current_screen' )->andReturn( (object) array( 'id' => 'elsewhere_page' ) );

		$text   = 'Original footer text';
		$result = Presenter::get_admin_footer_text( $text );

		$this->assertEquals( $text, $result );
	}
}
