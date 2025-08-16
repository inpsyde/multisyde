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

	const META_KEY   = 'multisyde-status';
	const META_VALUE = 'retired';

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
	}

	/**
	 * Add the retired status column to the networks' sites' list table.
	 *
	 * @param array<string, string> $views Views to display in the sites' list table.
	 *
	 * @return array<string, string>
	 */
	public static function views_sites_network( array $views ): array {
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
			$format = _n(
				'Retired website <span class="count">(%s)</span>',
				'Retired websites <span class="count">(%s)</span>',
				$counts,
				'multisyde'
			);
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
			// otherwise, add our meta-query.
			$args['meta_query'] = array(
				$meta_query,
			);
		}

		return $args;
	}
}
