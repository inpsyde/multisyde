<?php
/**
 * LastUserLogin Tests
 *
 * @package multisyde-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultiSyde\Modules\LastUserLogin\tests\unit;

use Brain\Monkey\Functions;
use Brain\Monkey\Filters;
use Brain\Monkey\Actions;
use Syde\MultiSyde\Modules\LastUserLogin\Feature;
use Syde\MultiSydeUnitTests\UnitTestCase;

/**
 * Test the SiteActivePlugins class.
 */
final class TestFeature extends UnitTestCase {


	/**
	 * Test the init method.

	 * @return void
	 */
	public function test_init(): void {
		Filters\expectAdded( 'manage_users-network_columns' )
			->with( array( Feature::class, 'manage_users_columns' ) )
			->once();
		Filters\expectAdded( 'manage_users-network_sortable_columns' )
			->with( array( Feature::class, 'manage_users_sortable_columns' ) )
			->once();
		Filters\expectAdded( 'manage_users_custom_column' )
			->with( array( Feature::class, 'manage_users_custom_column' ), 10, 3 )
			->once();
		Actions\expectAdded( 'pre_get_users' )
			->with( array( Feature::class, 'pre_get_users' ) )
			->once();
		Actions\expectAdded( 'wp_login' )
			->with( array( Feature::class, 'record_last_logged_in' ), 10, 2 )
			->once();

		Feature::init();
	}

	/**
	 * Test the manage_users_columns method.
	 *
	 * @return void
	 */
	public function test_manage_users_columns(): void {
		$columns = array(
			'username' => 'Username',
			'email'    => 'Email',
		);

		$expected = array(
			'username'          => 'Username',
			'email'             => 'Email',
			Feature::COLUMN_KEY => 'Last Login',
		);

		$result = Feature::manage_users_columns( $columns );
		$this->assertSame( $expected, $result );
	}

	/**
	 * Test the manage_users_sortable_columns method.
	 *
	 * @return void
	 */
	public function test_manage_users_sortable_columns(): void {
		$columns = array(
			'username' => 'Username',
			'email'    => 'Email',
		);

		$expected = array(
			'username'          => 'Username',
			'email'             => 'Email',
			Feature::COLUMN_KEY => 'Last Login',
		);

		$result = Feature::manage_users_sortable_columns( $columns );
		$this->assertSame( $expected, $result );
	}

	/**
	 * Test the manage_users_custom_column method.
	 *
	 * @return void
	 */
	public function test_manage_users_custom_column(): void {
		$value   = 'Previous Value';
		$column  = Feature::COLUMN_KEY;
		$user_id = 1;

		Functions\expect( 'current_user_can' )
			->once()
			->with( 'list_users' )
			->andReturnTrue();
		Functions\expect( 'get_user_meta' )
			->once()
			->with( $user_id, Feature::META_KEY, true )
			->andReturn( '2025-07-10 12:00:00' );
		Functions\expect( 'wp_date' )
			->once()
			->with( 'Y/m/d g:i:s a', '2025-07-10 12:00:00' )
			->andReturn( '2025/07/10 12:00:00 pm' );

		$result = Feature::manage_users_custom_column( $value, $column, $user_id );
		$this->assertSame( '2025/07/10 12:00:00 pm', $result );
	}

	/**
	 * Test the manage_users_custom_column method passing any other column name.
	 *
	 * @return void
	 */
	public function test_manage_users_custom_column_arbitrary_column(): void {
		$value   = 'Arbitrary Value';
		$column  = 'Arbitrary Column';
		$user_id = 1;

		$result = Feature::manage_users_custom_column( $value, $column, $user_id );
		$this->assertSame( $value, $result );
	}

	/**
	 * Test the manage_users_custom_column method when the user cannot list users.
	 */
	public function test_manage_users_custom_column_no_permission(): void {
		$value   = 'Any Value';
		$column  = Feature::COLUMN_KEY;
		$user_id = 1;

		Functions\expect( 'current_user_can' )
			->once()
			->with( 'list_users' )
			->andReturnFalse();

		$result = Feature::manage_users_custom_column( $value, $column, $user_id );
		$this->assertSame( $value, $result );
	}

	/**
	 * Test the pre_get_users method.
	 *
	 * @return void
	 */
	public function test_pre_get_users(): void {
		Functions\expect( 'is_network_admin' )
			->once()
			->andReturnTrue();
		Functions\expect( 'current_user_can' )
			->once()
			->with( 'list_users' )
			->andReturnTrue();

		$query = \Mockery::mock( \WP_User_Query::class );
		$query->shouldReceive( 'get' )
			->once()
			->with( 'orderby' )
			->andReturn( Feature::COLUMN_KEY );
		$query->shouldReceive( 'set' )
			->twice()
			->withAnyArgs();

		Feature::pre_get_users( $query );
	}

	/**
	 * Test the pre_get_users method when not in network admin.
	 *
	 * @return void
	 */
	public function test_pre_get_users_not_network_admin(): void {
		Functions\expect( 'is_network_admin' )
			->once()
			->andReturnFalse();

		$query = \Mockery::mock( \WP_User_Query::class );
		$query->shouldNotReceive( 'get' );
		$query->shouldNotReceive( 'set' );

		Feature::pre_get_users( $query );
	}

	/**
	 * Test the pre_get_users method when the orderby is not the COLUMN_KEY.
	 */
	public function test_pre_get_users_not_orderby_column_key(): void {
		Functions\expect( 'is_network_admin' )
			->once()
			->andReturnTrue();

		$query = \Mockery::mock( \WP_User_Query::class );
		$query->shouldReceive( 'get' )
			->once()
			->with( 'orderby' )
			->andReturn( 'some_other_column' );
		$query->shouldNotReceive( 'set' );

		Feature::pre_get_users( $query );
	}

	/**
	 * Test the pre_get_users method when the user cannot list users.
	 *
	 * @return void
	 */
	public function test_pre_get_users_no_permission(): void {
		Functions\expect( 'is_network_admin' )
			->once()
			->andReturnTrue();
		Functions\expect( 'current_user_can' )
			->once()
			->with( 'list_users' )
			->andReturnFalse();

		$query = \Mockery::mock( \WP_User_Query::class );
		$query->shouldReceive( 'get' )
			->once()
			->with( 'orderby' )
			->andReturn( Feature::COLUMN_KEY );
		$query->shouldNotReceive( 'set' );

		Feature::pre_get_users( $query );
	}

	/**
	 * Test the record_last_logged_in method.
	 *
	 * @return void
	 */
	public function test_record_last_logged_in(): void {
		$user_login = 'testuser';
		$user       = \Mockery::mock( \WP_User::class );
		$user->ID   = 1;

		Functions\expect( 'update_user_meta' )
			->once()
			->with( $user->ID, Feature::META_KEY, time() );

		Feature::record_last_logged_in( $user_login, $user );
	}
}
