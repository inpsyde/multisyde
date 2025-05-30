<?php
/**
 * Last User Login Feature
 *
 * @author Daniel Huesken
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\Multisyde\Modules\LastUserLogin;

use Syde\Multisyde\LoadableFeature;

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
		add_action( 'set_auth_cookie', array( __CLASS__, 'record_last_logged_in' ), 10, 4 );
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
	 * @param string $value Value form before.
	 * @param string $column current column name.
	 * @param int    $user_id user ID to display information for.
	 *
	 * @return string
	 */
	public static function manage_users_custom_column( $value, $column, $user_id ) {
		if ( self::COLUMN_KEY !== $column || ! current_user_can( 'list_users' ) ) {
			return $value;
		}

		$last_login = \DateTime::createFromFormat(
			'Y-m-d H:i:s',
			get_user_meta( $user_id, self::META_KEY, true ),
			new \DateTimeZone( 'UTC' )
		);

		if ( ! $last_login ) {
			return sprintf( '<span>%s</span>', esc_html__( '-', 'multisyde' ) );
		}

		$last_login->setTimezone( wp_timezone() );

		return sprintf(
			'<span title="%1$s">%2$s</span>',
			$last_login->format( 'c' ),
			$last_login->format( get_option( 'links_updated_date_format' ) )
		);
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
	 * Note: This might be before they agree to the new TOS, which is recorded separately.
	 *
	 * @param string $auth_cookie Authentication cookie value.
	 * @param int    $expire The time the login grace period expires as a UNIX timestamp.
	 *                                     Default is 12 hours past the cookie's expiration time.
	 * @param int    $expiration The time when the authentication cookie expires as a UNIX timestamp.
	 *                                     Default is 14 days from now.
	 * @param int    $user_id User ID.
	 *
	 * @throws \Exception For hooked function.
	 */
	public static function record_last_logged_in( $auth_cookie, $expire, $expiration, $user_id ): void {
		$login_at = new \DateTime( 'now', new \DateTimeZone( 'UTC' ) );

		update_user_meta( $user_id, self::META_KEY, $login_at->format( 'Y-m-d H:i:s' ) );
	}
}
