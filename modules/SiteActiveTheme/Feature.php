<?php
/**
 * Class implements functionality active themes for sites in the network admin dashboard.
 *
 * @package multisyde
 */

namespace Syde\MultiSyde\Modules\SiteActiveTheme;

use Syde\MultiSyde\LoadableFeature;

/**
 * Feature Class SiteActiveTheme
 */
final class Feature implements LoadableFeature {

	public const COLUMN_NAME = 'site-theme';

	/**
	 * Adds functionality to their respective hooks.
	 *
	 * @return void
	 */
	public static function init(): void {
		if ( ! is_network_admin() ) {
			return;
		}

		add_filter( 'manage_sites-network_columns', array( __CLASS__, 'add_column' ) );

		add_action( 'manage_sites_custom_column', array( __CLASS__, 'manage_custom_column' ), 10, 2 );
	}

	/**
	 * Add the column to the sites' list table.
	 *
	 * @param array<string, string> $columns Columns to display in the sites' list table.
	 *
	 * @return array<string, string>
	 */
	public static function add_column( array $columns ): array {
		$columns[ self::COLUMN_NAME ] = __( 'Active Theme', 'multisyde' );

		return $columns;
	}

	/**
	 * Display column content for the active theme.
	 *
	 * @param string $column_name The name of the column.
	 * @param int    $blog_id     The ID of the blog.
	 *
	 * @return void
	 */
	public static function manage_custom_column( string $column_name, int $blog_id ): void {
		if ( self::COLUMN_NAME !== $column_name ) {
			return;
		}

		switch_to_blog( $blog_id );
		$theme = wp_get_theme();
		restore_current_blog();

		echo '<strong>', esc_html( $theme->get( 'Name' ) ), '</strong>&nbsp;';

		/* translators: 1: Theme Version */
		$format = __( 'Version %1$s', 'multisyde' );
		echo '<span>', esc_html( sprintf( $format, $theme->get( 'Version' ) ) ), '</span>';

		$parent = $theme->parent();
		if ( false === $parent ) {
			return;
		}

		/* translators: 1: Parent Theme Name */
		$format = __( 'Parent Theme: %1$s', 'multisyde' );
		echo '<p class="description">', esc_html( sprintf( $format, $parent->get( 'Name' ) ) ), '</p>';
	}
}
