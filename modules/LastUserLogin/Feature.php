<?php
/**
 * Last User Login Feature
 *
 * @author Daniel Huesken
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\MultiSyde\Modules\LastUserLogin;

use Syde\MultiSyde\LoadableFeature;

/**
 * Feature Class LastUserLogin
 */
final class Feature implements LoadableFeature {

	const COLUMN_KEY = 'last-logged-in';
	const META_KEY   = 'last_logged_in';

	/**
	 * Adds functionality to their respective hooks.
	 *
	 * @return void
	 */
	public static function init(): void {
		add_filter( 'manage_users-network_columns', array( __CLASS__, 'manage_users_columns' ) );
		add_filter( 'manage_users-network_sortable_columns', array( __CLASS__, 'manage_users_sortable_columns' ) );
		add_filter( 'manage_users_custom_column', array( __CLASS__, 'manage_users_custom_column' ), 10, 3 );

		add_action( 'pre_get_users', array( __CLASS__, 'pre_get_users' ) );
		add_action( 'wp_login', array( __CLASS__, 'record_last_logged_in' ), 10, 2 );
	}

	/**
	 * Add the column to the users' list table.
	 *
	 * @param array<string, string> $columns Columns to display in the users' list table.
	 *
	 * @return array<string, string>
	 */
	public static function manage_users_columns( array $columns ): array {
		$columns[ self::COLUMN_KEY ] = __( 'Last Login', 'multisyde' );

		return $columns;
	}

	/**
	 * Define colum as sortable
	 *
	 * @param array<string, string> $columns Columns sortable.
	 *
	 * @return array<string, string>
	 */
	public static function manage_users_sortable_columns( array $columns ): array {
		$columns[ self::COLUMN_KEY ] = __( 'Last Login', 'multisyde' );

		return $columns;
	}

	/**
	 * Display colum content
	 *
	 * @param string $value Value from before.
	 * @param string $column current column name.
	 * @param int    $user_id user ID to display information for.
	 *
	 * @return string
	 */
	public static function manage_users_custom_column( $value, $column, $user_id ) {
		if ( self::COLUMN_KEY !== $column || ! current_user_can( 'list_users' ) ) {
			return $value;
		}

		$last_login = get_user_meta( $user_id, self::META_KEY, true );

		if ( is_string( $last_login ) && '' !== $last_login ) {
			return (string) wp_date( __( 'Y/m/d g:i:s a', 'multisyde' ), (int) $last_login );
		}

		return esc_html__( '—', 'multisyde' );
	}

	/**
	 * Filter users for sorting
	 *
	 * @param \WP_User_Query $query User query.
	 *
	 * @return void
	 */
	public static function pre_get_users( \WP_User_Query $query ): void {
		if ( ! is_network_admin() || self::COLUMN_KEY !== $query->get( 'orderby' ) || ! current_user_can( 'list_users' ) ) {
			return;
		}

		$meta_query = array(
			'relation'             => 'OR',
			'last_logged_in_never' => array(
				'key'     => self::META_KEY,
				'compare' => 'NOT EXISTS',
			),
			self::META_KEY         => array(
				'key'  => self::META_KEY,
				'type' => 'DATE',
			),
		);

		$query->set( 'orderby', self::META_KEY );
		$query->set( 'meta_query', $meta_query );
	}

	/**
	 * Record the last date a user logged in.
	 *
	 * @param string   $user_login User login name.
	 * @param \WP_User $user User object.
	 */
	public static function record_last_logged_in( string $user_login, \WP_User $user ): void {
		update_user_meta( $user->ID, self::META_KEY, time() );
	}
}
