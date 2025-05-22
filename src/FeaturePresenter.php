<?php
/**
 * FeaturePresenter class
 *
 * @package multisite-improvements
 */

namespace Syde\MultisiteImprovements;

/**
 * This class is responsible for presenting the features of the plugin in the network admin menu.
 */
final class FeaturePresenter {

	const MENU_SLUG = 'multisite-improvements';

	const CAPABILITY = 'manage_network';

	const ICON_URL = 'dashicons-heart';

	/**
	 * Initializes the feature presenter.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'network_admin_menu', array( __CLASS__, 'add_network_admin_menu' ) );

		add_filter( 'admin_footer_text', array( __CLASS__, 'get_admin_footer_text' ) );
	}

	/**
	 * Adds the feature overview page to the menu.
	 *
	 * @return void
	 */
	public static function add_network_admin_menu(): void {
		add_menu_page(
			__( 'Multisite Improvements', 'multisite-improvements' ),
			__( 'Multisite Improvements', 'multisite-improvements' ),
			self::CAPABILITY,
			self::MENU_SLUG,
			array( __CLASS__, 'render_overview_page' ),
			self::ICON_URL,
		);
	}

	/**
	 * Renders the feature overview page.
	 *
	 * @return void
	 */
	public static function render_overview_page(): void {
		$features = FeatureRegistry::get_presentable_classes();

		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Multisite Improvements', 'multisite-improvements' ) . '</h1>';
		echo '<p>' . esc_html__( 'This plugin provides various improvements for WordPress multisite installations.', 'multisite-improvements' ) . '</p>';

		echo '<h2>' . esc_html__( 'Available Features', 'multisite-improvements' ) . '</h2>';

		if ( ! empty( $features ) ) {
			echo '<table class="widefat fixed striped">';
			echo '<thead>';
			echo '<tr>';
			echo '<th scope="col" id="title" class="manage-column column-title" abbr="Title">' . esc_html__( 'Title', 'multisite-improvements' ) . '</th>';
			echo '<th scope="col" id="description"  class="manage-column column-description" abbr="Description">' . esc_html__( 'Description', 'multisite-improvements' ) . '</th>';
			echo '<th scope="col" id="tickets" class="manage-column column-tickets" abbr="Tickets">' . esc_html__( 'Tickets', 'multisite-improvements' ) . '</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			foreach ( $features as $feature ) {
				$feature_info = $feature::get_feature_information();

				echo '<tr>';
				echo '<td class="title column-title column-primary" data-colname="Title"><strong>' . esc_html( $feature_info->title ) . '</strong></td>';
				echo '<td class="description column-description" data-colname="Description">' . esc_html( $feature_info->description ) . '</td>';
				echo '<td class="tickets column-tickets has-row-actions" data-colname="Tickets">';

				foreach ( $feature_info->tickets as $ticket ) {
					echo '<span class="ticket"><a href="' . esc_url( $ticket ) . '" target="_blank">' . esc_html( $ticket ) . '</a></span><br>';
				}

				echo '</td>';
				echo '</tr>';
			}

			echo '</tbody>';
			echo '</table>';
		}

		echo '</div>';
	}

	/**
	 * Overrides the admin footer text.
	 *
	 * @param string $text The original footer text.
	 *
	 * @return string
	 */
	public static function get_admin_footer_text( string $text ): string {
		$screen = get_current_screen();
		if ( is_null( $screen ) || 'toplevel_page_multisite-improvements-network' !== $screen->id ) {
			return $text;
		}

		/* translators: 1: dashicon, 2: opening HTML tag for a link, 3: closing HTML tags for a link. */
		$translation = __( 'Made with %1$s by %2$sSyde%3$s.', 'multisite-improvements' );

		return sprintf( $translation, '<span class="dashicons dashicons-heart"></span>', '<a href="https://syde.com" target="_blank" rel="noopener noreferrer">', '</a>' );
	}
}
