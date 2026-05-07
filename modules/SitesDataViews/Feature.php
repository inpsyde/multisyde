<?php
/**
 * Class implements functionality to view and manage data views for sites in the network admin dashboard.
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\MultiSyde\Modules\SitesDataViews;

use Syde\MultiSyde\LoadableFeature;
use Syde\MultiSyde\Modules\SitesDataViews\Rest\SitesController;
use Syde\MultiSyde\Plugin;

/**
 * Feature Class SitesDataViews
 */
final class Feature implements LoadableFeature {

	private const SLUG          = 'ms-sites-dataviews';
	private const SCRIPT_HANDLE = 'multisyde-sites-data-views';

	private const STYLE_HANDLE = 'multisyde-sites-data-views-styles';

	/**
	 * Adds functionality to their respective hooks.
	 *
	 * @return void
	 */
	public static function init(): void {
		add_action( 'rest_api_init', array( __CLASS__, 'sites_rest_api_init' ) );
		add_filter( 'rest_site_collection_params', array( __CLASS__, 'register_status_collection_params' ) );

		if ( is_network_admin() ) {
			add_action( 'network_admin_menu', array( __CLASS__, 'register_submenu' ), 999 );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
			add_action( 'load-sites.php', array( __CLASS__, 'redirect_legacy_sites_page' ) );
		}
	}

	/**
	 * Register the boolean site-status flags (public/archived/mature/spam/deleted)
	 * as collection params so the REST controller actually forwards them to
	 * WP_Site_Query — without this they are silently dropped.
	 *
	 * @param array $query_params Existing collection params.
	 *
	 * @return array
	 */
	public static function register_status_collection_params( array $query_params ): array {
		foreach ( array( 'public', 'archived', 'mature', 'spam', 'deleted' ) as $flag ) {
			$query_params[ $flag ] = array(
				'description' => sprintf(
					/* translators: %s: site flag name. */
					__( 'Limit result set to sites whose %s flag matches the value (0 or 1).', 'multisyde' ),
					$flag
				),
				'type'        => 'integer',
				'enum'        => array( 0, 1 ),
			);
		}

		return $query_params;
	}

	/**
	 * Register the submenu page under "Sites" in the network admin and remove
	 * the default "All Sites" entry so this POC takes its place.
	 *
	 * @return void
	 */
	public static function register_submenu(): void {
		add_submenu_page(
			'sites.php',
			__( 'All Sites', 'multisyde' ),
			__( 'All Sites', 'multisyde' ),
			'manage_network',
			self::SLUG,
			array( __CLASS__, 'render_page' ),
			0
		);

		remove_submenu_page( 'sites.php', 'sites.php' );
	}

	/**
	 * Redirect any direct hit on the legacy sites.php (no submenu page param)
	 * to our DataViews replacement.
	 *
	 * @return void
	 */
	public static function redirect_legacy_sites_page(): void {
		if ( isset( $_GET['page'] ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_network' ) ) {
			return;
		}

		wp_safe_redirect( network_admin_url( 'sites.php?page=' . self::SLUG ) );
		exit;
	}

	/**
	 * Render the content of the submenu page.
	 *
	 * @return void
	 */
	public static function render_page(): void {
		echo '<div class="wrap"><div id="ms-sites-dataviews-root"></div></div>';
	}

	/**
	 * Enqueue the necessary scripts and styles for the data views page.
	 *
	 * @param string $hook The current admin page hook.
	 *
	 * @return void
	 */
	public static function enqueue_assets( string $hook ): void {
		if ( 'sites_page_' . self::SLUG !== $hook ) {
			return;
		}

		$asset_file = Plugin::plugin_dir_path( 'modules/SitesDataViews/build/sites-data-views.asset.php' );
		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = include $asset_file;

		wp_enqueue_script(
			self::SCRIPT_HANDLE,
			Plugin::plugin_dir_url( 'modules/SitesDataViews/build/sites-data-views.js' ),
			$asset['dependencies'],
			$asset['version'],
			true
		);

		$config = array(
			'restNs'          => 'wp/v2',
			'nonce'           => wp_create_nonce( 'wp_rest' ),
			'networkAdminUrl' => network_admin_url(),
		);

		wp_add_inline_script(
			self::SCRIPT_HANDLE,
			'window.MS_SITES_DATA = ' . wp_json_encode( $config ) . ';',
			'before'
		);

		wp_enqueue_style(
			self::STYLE_HANDLE,
			Plugin::plugin_dir_url( 'modules/SitesDataViews/build/style-index.css' ),
			array( 'wp-components' ),
			$asset['version'],
		);
	}

	/**
	 * Register the REST API routes for managing sites data views.
	 *
	 * @return void
	 */
	public static function sites_rest_api_init(): void {
        if ( class_exists( 'WP_REST_Controller' ) && ! class_exists( 'WP_REST_Sites_Controller' ) ) {
            require_once __DIR__ . '/lib/class-wp-rest-site-meta-fields.php';
            require_once __DIR__ . '/lib/class-wp-rest-sites-controller.php';
        }

        $plugins_controller = new \WP_REST_Sites_Controller();
        $plugins_controller->register_routes();
	}
}
