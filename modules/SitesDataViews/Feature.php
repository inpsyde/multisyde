<?php
/**
 * Class implements functionality to view and manage data views for sites in the network admin dashboard.
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\MultiSyde\Modules\SitesDataViews;

use Syde\MultiSyde\LoadableFeature;
use Syde\MultiSyde\Plugin;

/**
 * Feature Class SitesDataViews
 */
final class Feature implements LoadableFeature {

	private const SLUG          = 'ms-all-sites';
	private const SCRIPT_HANDLE = 'multisyde-all-sites';

	private const STYLE_HANDLE = 'multisyde-all-sites-styles';

	private const ADD_SITE_SLUG          = 'ms-add-site';
	private const ADD_SITE_SCRIPT_HANDLE = 'multisyde-add-site';
	private const ADD_SITE_STYLE_HANDLE  = 'multisyde-add-site-styles';

	private const EDIT_SITE_SLUG          = 'ms-edit-site';
	private const EDIT_SITE_SCRIPT_HANDLE = 'multisyde-edit-site';
	private const EDIT_SITE_STYLE_HANDLE  = 'multisyde-edit-site-styles';

	/**
	 * Adds functionality to their respective hooks.
	 *
	 * @return void
	 */
	public static function init(): void {
		add_action( 'rest_api_init', array( __CLASS__, 'sites_rest_api_init' ) );
		add_filter( 'rest_site_collection_params', array( __CLASS__, 'register_status_collection_params' ) );
		add_action( 'rest_insert_site', array( __CLASS__, 'sync_site_title_option' ), 10, 3 );

		if ( is_network_admin() ) {
			add_action( 'network_admin_menu', array( __CLASS__, 'register_submenu' ), 999 );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_all_sites_assets' ) );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_add_site_assets' ) );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_edit_site_assets' ) );
			add_action( 'admin_head', array( __CLASS__, 'hide_edit_site_menu_link' ) );
			add_action( 'load-sites.php', array( __CLASS__, 'redirect_legacy_sites_page' ) );
			add_action( 'load-site-new.php', array( __CLASS__, 'redirect_legacy_site_new_page' ) );
			add_action( 'load-site-info.php', array( __CLASS__, 'redirect_legacy_site_info_page' ) );
		}
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

        $sites_controller = new \WP_REST_Sites_Controller();
        $sites_controller->register_routes();
    }

    /**
	 * Register the boolean site-status flags (public/archived/mature/spam/deleted)
	 * as collection params so the REST controller actually forwards them to
	 * WP_Site_Query — without this they are silently dropped.
	 *
	 * @param array<string, mixed> $query_params Existing collection params.
	 *
	 * @return array<string, mixed>
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
     * Sync the per-site `blogname` option when a `title` is supplied via
     * the sites REST endpoint. The REST controller already accepts `title`
     * for site creation but its `update_item()` does not propagate it on
     * subsequent edits — this listener fills that gap.
     *
     * @param \WP_Site $site     The site that was inserted/updated.
     * @param \WP_REST_Request $request  The REST request.
     * @param bool $creating Whether this fired during creation.
     *
     * @return void
     */
    public static function sync_site_title_option( \WP_Site $site, \WP_REST_Request $request, bool $creating ): void {
        if ( $creating ) {
            return;
        }

        if ( ! $request->has_param( 'title' ) ) {
            return;
        }

        $title_raw = $request->get_param( 'title' );
        if ( ! is_scalar( $title_raw ) ) {
            return;
        }

        $title = trim( (string) $title_raw );
        if ( '' === $title ) {
            return;
        }

        switch_to_blog( (int) $site->blog_id );
        update_option( 'blogname', sanitize_text_field( $title ) );
        restore_current_blog();
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
			array( __CLASS__, 'render_all_sites_page'),
			0
		);

		add_submenu_page(
			'sites.php',
			__( 'Add New Site', 'multisyde' ),
			__( 'Add New Site', 'multisyde' ),
			'manage_network',
			self::ADD_SITE_SLUG,
			array( __CLASS__, 'render_add_site_page' ),
			1
		);

		// Edit Site: registered as a real submenu so WP can resolve its
		// capability when accessing it via `?page=ms-edit-site`. The menu
		// link itself is hidden through `admin_head` CSS below — we don't
		// call `remove_submenu_page()` here because that would empty the
		// $submenu entry that WP's `get_plugin_page_capability()` scans,
		// causing access to wp_die with "Sorry, you are not allowed".
		add_submenu_page(
			'sites.php',
			__( 'Edit Site', 'multisyde' ),
			__( 'Edit Site', 'multisyde' ),
			'manage_sites',
			self::EDIT_SITE_SLUG,
			array( __CLASS__, 'render_edit_site_page' )
		);

		remove_submenu_page( 'sites.php', 'sites.php' );
		remove_submenu_page( 'sites.php', 'site-new.php' );
	}

    /**
     * Enqueue the necessary scripts and styles for the data views page.
     *
     * @param string $hook The current admin page hook.
     *
     * @return void
     */
    public static function enqueue_all_sites_assets( string $hook ): void {
        if ( 'sites_page_' . self::SLUG !== $hook ) {
            return;
        }

        $asset_file = Plugin::plugin_dir_path( 'modules/SitesDataViews/build/all-sites/all-sites.asset.php' );
        if ( ! file_exists( $asset_file ) ) {
            return;
        }

        $asset = include $asset_file;

        wp_enqueue_script(
            self::SCRIPT_HANDLE,
            Plugin::plugin_dir_url( 'modules/SitesDataViews/build/all-sites/all-sites.js' ),
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
            Plugin::plugin_dir_url( 'modules/SitesDataViews/build/all-sites/style-index.css' ),
            array( 'wp-components' ),
            $asset['version'],
        );
    }

    /**
     * Enqueue scripts and styles for the Add New Site page.
     *
     * @param string $hook The current admin page hook.
     *
     * @return void
     */
    public static function enqueue_add_site_assets( string $hook ): void {
        if ( 'sites_page_' . self::ADD_SITE_SLUG !== $hook ) {
            return;
        }

        $asset_file = Plugin::plugin_dir_path( 'modules/SitesDataViews/build/add-site/add-site.asset.php' );
        if ( ! file_exists( $asset_file ) ) {
            return;
        }

        $asset = include $asset_file;

        wp_enqueue_script(
            self::ADD_SITE_SCRIPT_HANDLE,
            Plugin::plugin_dir_url( 'modules/SitesDataViews/build/add-site/add-site.js' ),
            $asset['dependencies'],
            $asset['version'],
            true
        );

        $current_network = get_network();
        $config          = array(
            'restNs'           => 'wp/v2',
            'nonce'            => wp_create_nonce( 'wp_rest' ),
            'networkAdminUrl'  => network_admin_url(),
            'sitesListUrl'     => network_admin_url( 'sites.php?page=' . self::SLUG ),
            'isSubdomain'      => (bool) is_subdomain_install(),
            'networkDomain'    => $current_network ? $current_network->domain : '',
            'networkPath'      => $current_network ? $current_network->path : '/',
            'siteUrlScheme'    => is_ssl() ? 'https' : 'http',
            'languages'        => self::get_available_languages_choices(),
            'defaultLanguage'  => get_network_option( null, 'WPLANG', '' ),
        );

        wp_add_inline_script(
            self::ADD_SITE_SCRIPT_HANDLE,
            'window.MS_ADD_SITE_DATA = ' . wp_json_encode( $config ) . ';',
            'before'
        );

        wp_enqueue_style(
            self::ADD_SITE_STYLE_HANDLE,
            Plugin::plugin_dir_url( 'modules/SitesDataViews/build/add-site/style-index.css' ),
            array( 'wp-components' ),
            $asset['version'],
        );
    }

    /**
     * Enqueue scripts and styles for the Edit Site page.
     *
     * @param string $hook The current admin page hook.
     *
     * @return void
     */
    public static function enqueue_edit_site_assets( string $hook ): void {
        if ( 'sites_page_' . self::EDIT_SITE_SLUG !== $hook ) {
            return;
        }

        $site_id = isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ? (int) $_GET['id'] : 0;
        if ( $site_id <= 0 ) {
            return;
        }

        $asset_file = Plugin::plugin_dir_path( 'modules/SitesDataViews/build/edit-site/edit-site.asset.php' );
        if ( ! file_exists( $asset_file ) ) {
            return;
        }

        $asset = include $asset_file;

        wp_enqueue_script(
            self::EDIT_SITE_SCRIPT_HANDLE,
            Plugin::plugin_dir_url( 'modules/SitesDataViews/build/edit-site/edit-site.js' ),
            $asset['dependencies'],
            $asset['version'],
            true
        );

        $config = array(
            'restNs'          => 'wp/v2',
            'nonce'           => wp_create_nonce( 'wp_rest' ),
            'networkAdminUrl' => network_admin_url(),
            'sitesListUrl'    => network_admin_url( 'sites.php?page=' . self::SLUG ),
            'siteId'          => $site_id,
            'isMainSite'      => is_main_site( $site_id ),
            'homeUrl'         => get_home_url( $site_id, '/' ),
            'adminUrl'        => get_admin_url( $site_id ),
            'defaultScheme'   => is_ssl() ? 'https' : 'http',
        );

        wp_add_inline_script(
            self::EDIT_SITE_SCRIPT_HANDLE,
            'window.MS_EDIT_SITE_DATA = ' . wp_json_encode( $config ) . ';',
            'before'
        );

        wp_enqueue_style(
            self::EDIT_SITE_STYLE_HANDLE,
            Plugin::plugin_dir_url( 'modules/SitesDataViews/build/edit-site/style-index.css' ),
            array( 'wp-components' ),
            $asset['version'],
        );
    }

    /**
	 * Hide the Edit Site submenu link from the network admin sidebar without
	 * unregistering it (see register_submenu() for the rationale).
	 *
	 * @return void
	 */
	public static function hide_edit_site_menu_link(): void {
		echo '<style>#adminmenu a.wp-submenu-edit-site,#adminmenu li.wp-submenu-edit-site,#adminmenu .wp-submenu a[href$="page=' . esc_attr( self::EDIT_SITE_SLUG ) . '"]{display:none !important;}</style>';
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
	 * Redirect any direct hit on the legacy site-new.php to our DataForm
	 * replacement.
	 *
	 * @return void
	 */
	public static function redirect_legacy_site_new_page(): void {
		if ( ! current_user_can( 'manage_network' ) ) {
			return;
		}

		wp_safe_redirect( network_admin_url( 'sites.php?page=' . self::ADD_SITE_SLUG ) );
		exit;
	}

	/**
	 * Redirect any direct hit on the legacy network/site-info.php to our
	 * DataForm replacement, preserving the site `id` query arg.
	 *
	 * @return void
	 */
	public static function redirect_legacy_site_info_page(): void {
		if ( ! current_user_can( 'manage_sites' ) ) {
			return;
		}

		$id = isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ? (int) $_GET['id'] : 0;
		if ( $id <= 0 ) {
			return;
		}

		wp_safe_redirect(
			network_admin_url(
				'sites.php?page=' . self::EDIT_SITE_SLUG . '&id=' . $id
			)
		);
		exit;
	}

	/**
	 * Render the content of the submenu page.
	 *
	 * @return void
	 */
	public static function render_all_sites_page(): void {
		echo '<div class="wrap"><div id="ms-all-sites-root"></div></div>';
	}

	/**
	 * Render the content of the Add New Site submenu page.
	 *
	 * @return void
	 */
	public static function render_add_site_page(): void {
		echo '<div class="wrap"><div id="ms-add-site-root"></div></div>';
	}

	/**
	 * Render the content of the Edit Site submenu page (hidden submenu).
	 *
	 * @return void
	 */
	public static function render_edit_site_page(): void {
		$site_id = isset( $_GET['id'] ) && is_numeric( $_GET['id'] ) ? (int) $_GET['id'] : 0;
		if ( $site_id <= 0 ) {
			printf(
				'<div class="wrap"><h1>%s</h1><p>%s <a href="%s">%s</a></p></div>',
				esc_html__( 'Edit Site', 'multisyde' ),
				esc_html__( 'No site selected.', 'multisyde' ),
				esc_url( network_admin_url( 'sites.php?page=' . self::SLUG ) ),
				esc_html__( 'Back to all sites', 'multisyde' )
			);
			return;
		}

		echo '<div class="wrap"><div id="ms-edit-site-root"></div></div>';
	}

	/**
	 * Build the list of language choices for the Add Site DataForm field.
	 *
	 * Mirrors `wp_dropdown_languages()` on the legacy `network/site-new.php`
	 * screen with `show_available_translations` enabled: lists installed
	 * languages first, then all uninstalled translations from the API. The
	 * REST controller downloads the language pack on submit when needed.
	 *
	 * @return array<int, array{value: string, label: string}>
	 */
	private static function get_available_languages_choices(): array {
		require_once ABSPATH . 'wp-admin/includes/translation-install.php';

		$installed              = get_available_languages();
		$translations           = wp_get_available_translations();
		$can_install            = current_user_can( 'install_languages' ) && wp_can_install_language_pack();
		$installed_translations = array();
		$available_translations = array();

		foreach ( $installed as $locale ) {
			if ( isset( $translations[ $locale ] ) ) {
				$installed_translations[] = array(
					'value' => $locale,
					'label' => $translations[ $locale ]['native_name'],
				);
				unset( $translations[ $locale ] );
				continue;
			}

			$installed_translations[] = array(
				'value' => $locale,
				'label' => $locale,
			);
		}

		usort(
			$installed_translations,
			static fn ( $a, $b ) => strcoll( $a['label'], $b['label'] )
		);

		if ( $can_install ) {
			foreach ( $translations as $locale => $translation ) {
				if ( ! is_string( $locale ) ) {
					continue;
				}
				$available_translations[] = array(
					'value' => $locale,
					'label' => $translation['native_name'],
				);
			}

			usort(
				$available_translations,
				static fn ( $a, $b ) => strcoll( $a['label'], $b['label'] )
			);
		}

		return array_merge(
			array(
				array(
					'value' => '',
					'label' => __( 'Site Default', 'multisyde' ),
				),
				array(
					'value' => 'en_US',
					'label' => 'English (United States)',
				),
			),
			$installed_translations,
			$available_translations
		);
	}
}
