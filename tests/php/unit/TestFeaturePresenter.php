<?php
/**
 * This class tests the FeaturePresenter class.
 *
 * @package multisite-improvements-unit-tests
 */

declare(strict_types = 1);

namespace Syde\MultisiteImprovementsUnitTests;

use Brain\Monkey\Functions;
use Syde\MultisiteImprovements\FeaturePresenter;

/**
 * Test the FeaturePresenter class.
 */
class TestFeaturePresenter extends UnitTestCase {


	/**
	 * Test the static get_all method.
	 *
	 * @return void
	 */
	public function test_add_network_admin_menu() {
		Functions\expect( 'add_menu_page' )->once()->with(
			'Multisite Improvements',
			'Multisite Improvements',
			'manage_network',
			'multisite-improvements',
			array( 'Syde\MultisiteImprovements\FeaturePresenter', 'render_overview_page' ),
			'dashicons-heart',
		);

		FeaturePresenter::add_network_admin_menu();
	}

	/**
	 * Test the static render_overview_page method.
	 *
	 * @return void
	 */
	public function test_render_overview_page(): void {
		FeaturePresenter::render_overview_page();

		$this->expectOutputString( '<div class="wrap"><h1>Multisite Improvements</h1><p>This plugin provides various improvements for WordPress multisite installations.</p><h2>Available Features</h2><table class="widefat fixed striped"><thead><tr><th scope="col" id="title" class="manage-column column-title" abbr="Title">Title</th><th scope="col" id="description"  class="manage-column column-description" abbr="Description">Description</th><th scope="col" id="tickets" class="manage-column column-tickets" abbr="Tickets">Tickets</th></tr></thead><tbody><tr><td class="title column-title column-primary" data-colname="Title"><strong>Site Active Plugins</strong></td><td class="description column-description" data-colname="Description">Displays which plugins are active on each site in the network. Adds a “Sites deactivate” link to the Network Admin Plugins page with a modal that lists subsites using the plugin. Supports selective bulk deactivation across subsites.</td><td class="tickets column-tickets has-row-actions" data-colname="Tickets"><span class="ticket"><a href="https://core.trac.wordpress.org/ticket/53255" target="_blank">https://core.trac.wordpress.org/ticket/53255</a></span><br></td></tr></tbody></table></div>' );
	}
}
