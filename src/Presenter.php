<?php
/**
 * FeaturePresenter class
 *
 * @package multisyde
 */

namespace Syde\MultiSyde;

/**
 * This class is responsible for presenting the features of the plugin in the network admin menu.
 */
final class Presenter {

	const MENU_SLUG = 'multisyde';

	const CAPABILITY = 'manage_network';

	const ICON_URL = 'dashicons-heart';

	/**
	 * Instance of Modules class to access the features.
	 *
	 * @var Modules $modules
	 */
	protected Modules $modules;

	/**
	 * Constructor for Presenter class.
	 *
	 * @param Modules $modules Instance of Modules class to access the features.
	 */
	public function __construct( Modules $modules ) {
		$this->modules = $modules;
	}

	/**
	 * Initializes the feature presenter.
	 *
	 * @param Modules $modules Instance of Modules class to access the features.
	 * @return void
	 */
	public static function init( Modules $modules ) {
		$obj = new self( $modules );

		add_action( 'network_admin_menu', array( $obj, 'add_network_admin_menu' ) );

		add_filter( 'admin_footer_text', array( $obj, 'get_admin_footer_text' ) );
	}

	/**
	 * Adds the feature overview page to the menu.
	 *
	 * @return void
	 */
	public function add_network_admin_menu(): void {
		add_menu_page(
			__( 'MultiSyde', 'multisyde' ),
			__( 'MultiSyde', 'multisyde' ),
			self::CAPABILITY,
			self::MENU_SLUG,
			array( $this, 'render_overview_page' ),
			self::ICON_URL,
		);
	}

	/**
	 * Renders the feature overview page.
	 *
	 * @return void
	 */
	public function render_overview_page(): void {
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'MultiSyde', 'multisyde' ) . '</h1>';
		echo '<p>' . esc_html__( 'This plugin provides various improvements for WordPress multisite installations.', 'multisyde' ) . '</p>';

		echo '<h2>' . esc_html__( 'Available Features', 'multisyde' ) . '</h2>';

		$features_abouts = $this->modules->features();
		if ( ! empty( $features_abouts ) ) {
			echo '<table class="wp-list-table widefat striped">';
			echo '<thead>';
			echo '<tr>';
			echo '<th scope="col" id="title" class="manage-column column-title" abbr="Title">' . esc_html__( 'Title', 'multisyde' ) . '</th>';
			echo '<th scope="col" id="description"  class="manage-column column-description" abbr="Description">' . esc_html__( 'Description', 'multisyde' ) . '</th>';
			echo '<th scope="col" id="tickets" class="manage-column column-tickets" abbr="Tickets">' . esc_html__( 'Tickets', 'multisyde' ) . '</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			foreach ( $features_abouts as $about ) {
				$feature = $about::get();

				echo '<tr>';
				echo '<td class="title column-title column-primary" data-colname="Title"><strong>' . esc_html( $feature->title ) . '</strong></td>';
				echo '<td class="description column-description" data-colname="Description">' . esc_html( $feature->description ) . '</td>';
				echo '<td class="tickets column-tickets has-row-actions" data-colname="Tickets">';

				foreach ( $feature->tickets as $ticket ) {
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
		if ( is_null( $screen ) || 'toplevel_page_multisyde-network' !== $screen->id ) {
			return $text;
		}

		/* translators: 1: dashicon, 2: opening HTML tag for a link, 3: closing HTML tags for a link. */
		$translation = __( 'Made with %1$s by %2$sSyde%3$s.', 'multisyde' );

		return sprintf( $translation, '<span class="dashicons dashicons-heart"></span>', '<a href="https://syde.com" target="_blank" rel="noopener noreferrer">', '</a>' );
	}
}
