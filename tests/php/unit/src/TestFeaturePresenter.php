<?php
/**
 * This class tests the FeaturePresenter class.
 *
 * @package multisyde-unit-tests
 */

declare(strict_types = 1);

namespace Syde\MultisiteImprovementsUnitTests\src;

use Brain\Monkey\Actions;
use Brain\Monkey\Filters;
use Brain\Monkey\Functions;
use Syde\MultisiteImprovements\FeaturePresenter;
use Syde\MultisiteImprovementsUnitTests\UnitTestCase;

/**
 * Test the FeaturePresenter class.
 */
class TestFeaturePresenter extends UnitTestCase {

	/**
	 * Test the init method.
	 *
	 * @return void
	 */
	public function test_init(): void {
		Actions\expectAdded( 'network_admin_menu' );
		Filters\expectAdded( 'admin_footer_text' );

		FeaturePresenter::init();
	}

	/**
	 * Test the static get_all method.
	 *
	 * @return void
	 */
	public function test_add_network_admin_menu() {
		Functions\expect( 'add_menu_page' )->once()->with(
			'Multisyde',
			'Multisyde',
			'manage_network',
			'multisyde',
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

		$this->expectOutputRegex( '/.*Multisyde.*This plugin provides various improvements for WordPress multisite installations.*/' );
	}
}
