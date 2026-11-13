<?php
/**
 * SitesDataViews Tests
 *
 * @package multisyde-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultiSyde\Modules\SitesDataViews\tests\unit;

use Brain\Monkey\Functions;
use Syde\MultiSyde\Modules\RestSitesEndpoint\Feature as RestSitesEndpoint;
use Syde\MultiSyde\Modules\SitesDataViews\Feature;
use Syde\MultiSydeUnitTests\UnitTestCase;

/**
 * Test the SitesDataViews class.
 */
final class TestFeature extends UnitTestCase {

	/**
	 * Nothing is hooked without a sites endpoint.
	 *
	 * @return void
	 */
	public function test_init_without_endpoint(): void {
		Functions\expect( 'is_network_admin' )->never();

		Feature::init();

		$this->assertFalse( has_action( 'rest_insert_site', array( Feature::class, 'sync_site_title_option' ) ) );
	}

	/**
	 * Hooks are registered when the RestSitesEndpoint module provides the endpoint.
	 *
	 * @return void
	 */
	public function test_init_with_endpoint_module(): void {
		Functions\expect( 'is_network_admin' )->once()->andReturn( true );

		RestSitesEndpoint::init();
		Feature::init();

		$this->assertSame( 10, has_action( 'rest_insert_site', array( Feature::class, 'sync_site_title_option' ) ) );
		$this->assertSame( 999, has_action( 'network_admin_menu', array( Feature::class, 'register_submenu' ) ) );
	}

	/**
	 * Email and locale of a new site end up as admin and WPLANG.
	 *
	 * @runInSeparateProcess
	 * @return void
	 */
	public function test_prepare_new_site(): void {
		$params  = array(
			'email'  => 'admin@example.com',
			'locale' => 'de_DE',
		);
		$request = \Mockery::mock( 'WP_REST_Request, ArrayAccess' );
		$request->shouldReceive( 'offsetExists' )->andReturnUsing( fn ( $key ) => isset( $params[ $key ] ) );
		$request->shouldReceive( 'offsetGet' )->andReturnUsing( fn ( $key ) => $params[ $key ] ?? null );

		Functions\when( 'is_email' )->justReturn( true );
		Functions\when( 'email_exists' )->justReturn( 7 );
		Functions\when( 'get_available_languages' )->justReturn( array( 'de_DE' ) );

		$this->assertSame(
			array(
				'domain'  => 'example.com',
				'user_id' => 7,
				'options' => array( 'WPLANG' => 'de_DE' ),
			),
			Feature::prepare_new_site( array( 'domain' => 'example.com' ), $request )
		);
	}

	/**
	 * Updates are left alone.
	 *
	 * @runInSeparateProcess
	 * @return void
	 */
	public function test_prepare_new_site_ignores_updates(): void {
		$request = \Mockery::mock( 'WP_REST_Request, ArrayAccess' );
		$request->shouldReceive( 'offsetExists' )->with( 'id' )->andReturn( true );
		$request->shouldReceive( 'offsetGet' )->with( 'id' )->andReturn( 3 );

		$this->assertSame( array( 'path' => '/' ), Feature::prepare_new_site( array( 'path' => '/' ), $request ) );
	}
}
