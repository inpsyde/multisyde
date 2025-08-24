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
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest' ) );

		if ( is_network_admin() ) {
			add_action( 'network_admin_menu', array( __CLASS__, 'register_submenu' ) );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		}
	}

	/**
	 * Register the submenu page under "Sites" in the network admin.
	 *
	 * @return void
	 */
	public static function register_submenu(): void {
		add_submenu_page(
			'sites.php',
			__( 'Sites (Data Views)', 'multisyde' ),
			__( 'Sites (Data Views)', 'multisyde' ),
			'manage_network',
			self::SLUG,
			array( __CLASS__, 'render_page' ),
			30
		);
	}

	/**
	 * Render the content of the submenu page.
	 *
	 * @return void
	 */
	public static function render_page(): void {
		echo '<div class="wrap"><h1>' . esc_html__( 'Sites (Data Views)', 'multisyde' ) . '</h1><div id="ms-dataviews-root"></div></div>';
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
			'restNs' => 'multisyde/v1',
			'nonce'  => wp_create_nonce( 'wp_rest' ),
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
	public static function register_rest(): void {
		( new SitesController() )->register_routes();
	}
}
