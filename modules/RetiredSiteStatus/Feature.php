<?php
/**
 * Class implements the retired site status for multisite.
 *
 * @package multisyde
 */

declare( strict_types=1 );

namespace Syde\MultiSyde\Modules\RetiredSiteStatus;

use Syde\MultiSyde\LoadableFeature;

/**
 * Feature Class RetiredSiteStatus
 */
class Feature implements LoadableFeature {

	const META_KEY = 'multisyde-status';

	const META_VALUE = 'retired';

	const DEFAULT_MAX_SITES = 100;

	/**
	 * Load the feature.
	 *
	 * @return void
	 */
	public static function init(): void {
		if ( ! is_network_admin() ) {
			return;
		}

		add_filter( 'views_sites-network', array( __CLASS__, 'views_sites_network' ) );
		add_filter( 'ms_sites_list_table_query_args', array( __CLASS__, 'ms_sites_list_table_query_args' ) );
		add_action( 'network_site_info_form', array( __CLASS__, 'network_site_info_form' ) );
		add_action( 'load-site-info.php', array( __CLASS__, 'update_site_info' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_list_css' ) );
		add_filter( 'display_site_states', array( __CLASS__, 'add_retired_state' ), 10, 2 );
	}

	/**
	 * Add the retired status column to the networks' sites' list table.
	 *
	 * @param array<string, string> $views Views to display in the sites' list table.
	 *
	 * @return array<string, string>
	 */
	public static function views_sites_network( array $views ): array {
        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		$counts = get_sites(
			array(
				'meta_query' => array(
					array(
						'key'     => self::META_KEY,
						'compare' => '=',
						'value'   => self::META_VALUE,
					),
				),
				'count'      => true,
			)
		);

		if ( $counts > 0 ) {
			// Translators: %s is the number of retired websites.
			$format = __( 'Retired <span class="count">(%s)</span>', 'multisyde' );
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$status = sanitize_text_field( wp_unslash( $_GET['status'] ?? '' ) );
			$attr   = self::META_VALUE === $status ? ' class="current" aria-current="page"' : '';
			$label  = sprintf( $format, number_format_i18n( $counts ) );

			$views[ self::META_VALUE ] = sprintf(
				'<a href="%1$s"%2$s>%3$s</a>',
				esc_url( add_query_arg( 'status', self::META_VALUE, 'sites.php' ) ),
				$attr,
				$label
			);
		}

		return $views;
	}

	/**
	 * Filter the query arguments for the sites' list table to include only retired sites.
	 *
	 * @param array<string, mixed> $args Query arguments for the sites' list table.
	 *
	 * @return array<string, mixed>
	 */
	public static function ms_sites_list_table_query_args( $args ) {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$status = sanitize_text_field( wp_unslash( $_GET['status'] ?? '' ) );
		if ( self::META_VALUE !== $status ) {
			return $args;
		}

		$meta_query = array(
			'key'   => self::META_KEY,
			'value' => self::META_VALUE,
		);

		if ( isset( $args['meta_query'] ) ) {
			$args['meta_query'] = array(
				'relation' => 'AND',
				$meta_query,
				array( $args['meta_query'] ),
			);
		} else {
			// Otherwise, add our meta-query.
			$args['meta_query'] = array(
				$meta_query,
			);
		}

		return $args;
	}

	/**
	 * Render the form field for the retired site status in the network site info.
	 *
	 * @param int $site_id The ID of the site.
	 *
	 * @return void
	 */
	public static function network_site_info_form( int $site_id ): void {
		$status = get_site_meta( $site_id, self::META_KEY, true );

		printf(
			'<table class="form-table" role="presentation">
                <tbody>
                    <tr class="form-field">
                        <th scope="row">%1$s</th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text">%2$s</legend>
                                <label>
                                    <input type="checkbox" name="%3$s" value="1" %4$s/> %5$s
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                </tbody>
            </table>',
			esc_html__( 'Site Status', 'multisyde' ),
			esc_html__( 'Set site status', 'multisyde' ),
			esc_attr( self::META_KEY ),
			checked( $status, self::META_VALUE, false ),
			esc_html__( 'Retired', 'multisyde' ),
		);
	}

	/**
	 * Handle the site info update POST request to set the retired status.
	 *
	 * @return void
	 */
	public static function update_site_info(): void {
		if (
			! current_user_can( 'manage_network' ) ||
			! isset( $_SERVER['REQUEST_METHOD'] ) ||
			'POST' !== $_SERVER['REQUEST_METHOD'] ||
			empty( $_REQUEST['action'] ) ||
			'update-site' !== $_REQUEST['action'] ) {
			return;
		}

		check_admin_referer( 'edit-site' );

		$site_id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		if ( $site_id <= 0 ) {
			return;
		}

		$status = isset( $_POST[ self::META_KEY ] ) ? self::META_VALUE : '';

		update_site_meta( $site_id, self::META_KEY, $status );
	}

	/**
	 * Add inline CSS (via WP API) to highlight retired sites in Network → Sites.
	 *
	 * @param string $hook_suffix Current admin page hook suffix.
	 * @return void
	 */
	public static function enqueue_list_css( string $hook_suffix ): void {
		if ( 'sites.php' !== $hook_suffix ) {
			return;
		}

		/**
		 * Filter to set the maximum number of retired sites to show.
		 *
		 * @since 1.2.0
		 *
		 * @param int $max_sites The maximum number of retired sites to show.
		 */
		$max_sites = apply_filters( 'retired_site_status_max_sites', self::DEFAULT_MAX_SITES );

		$ids = get_sites(
			array(
				'fields'     => 'ids',
				'number'     => $max_sites,
				'meta_query' => array(
					array(
						'key'     => self::META_KEY,
						'compare' => '=',
						'value'   => self::META_VALUE,
					),
				),
			)
		);

		if ( empty( $ids ) ) {
			return;
		}

		$selectors = array_map(
			static fn( $id ) => sprintf(
				'#the-list tr:has(input[name="allblogs[]"][value="%d"])',
				(int) $id
			),
			$ids
		);

		$css = implode( ',', $selectors ) . '{ background:#e8f4ff; }';

		wp_add_inline_style( 'wp-admin', $css );
	}

	/**
	 * Add retired state to the sites in the sites list table.
	 *
	 * @param array    $site_states Array of site states.
	 * @param \WP_Site $site The site object.
	 *
	 * @return array
	 */
	public static function add_retired_state( array $site_states, \WP_Site $site ): array {
		$status = get_site_meta( $site->id, self::META_KEY, true );

		if ( self::META_VALUE === $status ) {
			$site_states['multisyde_retired'] = __( 'Retired', 'multisyde' );
		}

		return $site_states;
	}
}
