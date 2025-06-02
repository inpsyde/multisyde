<?php
/**
 * Class implements functionality active plugins for sites in the network admin dashboard.
 *
 * @package multisyde
 */

namespace Syde\Multisyde\Modules\SiteActivePlugins;

use Syde\Multisyde\LoadableFeature;

/**
 * Feature Class SiteActivePlugins
 */
final class Feature implements LoadableFeature {

	const ACTION_DEACTIVATION = 'bulk_deactivate';
	const NOTICE_DEACTIVATION = 'bulk_deactivated';

	/**
	 * The active plugins in the sites in the network.
	 *
	 * @var array<string, int[]>
	 */
	protected array $active_plugins = array();

	/**
	 * Adds functionality to their respective hooks.
	 *
	 * @return void
	 */
	public static function init(): void {
		if ( ! is_network_admin() ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'add_thickbox' ) );
		add_action( 'network_admin_notices', array( __CLASS__, 'maybe_show_notice' ) );
		add_action( 'load-plugins.php', array( __CLASS__, 'bulk_deactivate' ) );

		$obj = new self();

		add_action( 'load-plugins.php', array( $obj, 'populate_active_plugins' ) );
		add_action( 'admin_print_styles-plugins.php', array( $obj, 'print_row_styles' ) );
		add_action( 'admin_footer', array( $obj, 'print_thickbox_content' ) );

		add_filter( 'network_admin_plugin_action_links', array( $obj, 'add_action_link' ), 10, 2 );
	}

	/**
	 * Adds thickbox if we are on the plugins-network screen.
	 *
	 * @return void
	 */
	public static function add_thickbox(): void {
		$screen = get_current_screen();
		if ( is_null( $screen ) || 'plugins-network' !== $screen->id ) {
			return;
		}
		add_thickbox();
	}

	/**
	 * Shows a notice if there were plugins deactivated.
	 *
	 * @return void
	 */
	public static function maybe_show_notice(): void {
		if (
			! is_network_admin() ||
			! isset( $_GET['_wpnonce'] ) ||
			! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), self::ACTION_DEACTIVATION ) ||
			self::NOTICE_DEACTIVATION !== sanitize_key( wp_unslash( $_GET['notice'] ?? '' ) )
		) {
			return;
		}

		$plugin_file = sanitize_text_field( wp_unslash( $_GET['plugin_file'] ?? '' ) );
		$site_count  = absint( wp_unslash( $_GET['site_count'] ?? 0 ) );
		if ( '' === $plugin_file || 0 === $site_count ) {
			return;
		}

		/* translators: 1: Plugin Name, 2: Number of sites. */
		$message = _n(
			'The plugin "%1$s" has been deactivated on %2$d site.',
			'The plugin "%1$s" has been deactivated on %2$d sites.',
			$site_count,
			'multisyde'
		);

		printf(
			'<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>',
			esc_html(
				sprintf(
					$message,
					get_plugin_data( trailingslashit( WP_PLUGIN_DIR ) . $plugin_file )['Name'],
					$site_count
				)
			)
		);
	}

	/**
	 * Bulk deactivates on an incoming $_POST containing a plugin_file and site_ids.
	 *
	 * @return void
	 */
	public static function bulk_deactivate(): void {
		if (
			! current_user_can( 'manage_network_plugins' ) ||
			! isset( $_POST['_wpnonce'] ) ||
			! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ), self::ACTION_DEACTIVATION ) ||
			self::ACTION_DEACTIVATION !== sanitize_text_field( wp_unslash( $_POST['action'] ?? '' ) )
		) {
			return;
		}

		$plugin_file = sanitize_text_field( wp_unslash( $_POST['plugin_file'] ?? '' ) );
		$site_ids    = array_map( 'absint', (array) wp_unslash( $_POST['site_ids'] ?? array() ) );
		if ( '' === $plugin_file || empty( $site_ids ) ) {
			return;
		}

		foreach ( $site_ids as $site_id ) {
			switch_to_blog( $site_id );
			deactivate_plugins( $plugin_file, false, false );
			restore_current_blog();
		}

		wp_safe_redirect(
			add_query_arg(
				array(
					'notice'      => self::NOTICE_DEACTIVATION,
					'plugin_file' => rawurlencode( $plugin_file ),
					'site_count'  => count( $site_ids ),
					'_wpnonce'    => wp_create_nonce( self::ACTION_DEACTIVATION ),
				),
				network_admin_url( 'plugins.php' )
			)
		);
		exit;
	}

	/**
	 * Populates the $active_plugins var.
	 *
	 * @return void
	 */
	public function populate_active_plugins(): void {
		$this->active_plugins = array();

		foreach ( get_sites( array( 'fields' => 'ids' ) ) as $site_id ) {
			foreach ( (array) get_blog_option( $site_id, 'active_plugins', array() ) as $plugin ) {
				if ( is_plugin_active_for_network( $plugin ) ) {
					continue;
				}

				if ( ! isset( $this->active_plugins[ $plugin ] ) ) {
					$this->active_plugins[ $plugin ] = array();
				}

				$this->active_plugins[ $plugin ][] = $site_id;
			}
		}
	}

	/**
	 * Applies background color "Grey 0" to the "Site Activated" plugins.
	 *
	 * @return void
	 */
	public function print_row_styles(): void {
		if ( empty( $this->active_plugins ) ) {
			return;
		}

		$selectors_escaped = implode(
			', ',
			array_map(
				static fn( string $plugin_file ): string => sprintf(
					'tr[data-plugin="%s"]',
					esc_attr( $plugin_file )
				),
				array_keys( $this->active_plugins )
			)
		);

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<style>', $selectors_escaped, '{ background-color: #f6f7f7 !important; }</style>';
	}

	/**
	 * Prints the thickbox content for the active plugins.
	 *
	 * @return void
	 */
	public function print_thickbox_content(): void {
		$screen = get_current_screen();
		if ( is_null( $screen ) || 'plugins-network' !== $screen->id ) {
			return;
		}

		foreach ( $this->active_plugins as $plugin_file => $site_ids ) {
			echo '<div id="' . esc_attr( md5( $plugin_file ) ) . '" style="display:none">';

			echo '<p>' . esc_html__(
				'Select the sites where you want to deactivate this plugin. Clicking on a site name will open the plugin screen for that site.',
				'multisyde'
			) . '</p>';

			echo '<form method="post" action="' . esc_url( add_query_arg( array() ) ) . '">';
			wp_nonce_field( self::ACTION_DEACTIVATION );
			echo '<input type="hidden" name="action" value="' . esc_attr( self::ACTION_DEACTIVATION ) . '" />';
			echo '<input type="hidden" name="plugin_file" value="' . esc_attr( $plugin_file ) . '" />';

			echo '<ul>';
			foreach ( $site_ids as $site_id ) {
				$site = get_blog_details( $site_id );
				if ( $site ) {
					echo '<li>';
					echo '<label>';
					echo '<input type="checkbox" name="site_ids[]" value="' . esc_attr( strval( $site_id ) ) . '" /> ';
					echo '<a href="' . esc_url( get_admin_url( $site_id, 'plugins.php' ) ) . '" target="_blank" rel="noopener noreferrer">';
					echo esc_html( ! empty( $site->blogname ) ? $site->blogname : $site->siteurl );
					echo '</a>';
					echo '</label>';
					echo '</li>';               }
			}
			echo '</ul>';

			submit_button( __( 'Deactivate on selected sites', 'multisyde' ) );
			echo '</form>';
			echo '</div>';
		}
	}

	/**
	 * Adds action links for site active plugins to the network admin plugin page.
	 *
	 * @param array<string, string> $links The action links.
	 * @param string                $plugin_file The plugin file path.
	 *
	 * @return array<string, string> The modified action links.
	 */
	public function add_action_link( array $links, string $plugin_file ): array {
		if ( ! isset( $this->active_plugins[ $plugin_file ] ) ) {
			return $links;
		}

		$count = count( $this->active_plugins[ $plugin_file ] );
		/* translators: 1: Plugin Name, 2: Number of sites. */
		$translation = _n( '"%1$s" is active in %2$d site', '"%1$s" is active in %2$d sites', $count, 'multisyde' );
		$title       = sprintf(
			$translation,
			get_plugin_data( trailingslashit( WP_PLUGIN_DIR ) . $plugin_file )['Name'],
			$count
		);

		$sites_deactivate_link = array(
			'site_deactivate' => sprintf(
				'<a class="thickbox" title="%1$s" style="display: inline-block" href="#TB_inline?width=600&height=550&inlineId=%2$s">%3$s</a>',
				esc_attr( $title ),
				esc_attr( md5( $plugin_file ) ),
				esc_html__( 'Sites deactivate', 'multisyde' )
			),
		);

		$before = array_slice( $links, 0, 1 );
		$after  = array_slice( $links, 1 );

		return array_merge( $before, $sites_deactivate_link, $after );
	}
}
