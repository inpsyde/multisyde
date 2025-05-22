<?php
/**
 * SiteActivePlugins Tests
 *
 * @package multisyde-unit-tests
 */

declare( strict_types=1 );

namespace Syde\MultisiteImprovementsUnitTests\modules;

use Brain\Monkey\Functions;
use Syde\MultisiteImprovements\Modules\SiteActivePlugins\Feature;
use Syde\MultisiteImprovementsUnitTests\UnitTestCase;

/**
 * Test the SiteActivePlugins class.
 */
final class TestSiteActivePlugins extends UnitTestCase {

	/**
	 * Test the init method.
	 *
	 * @runInSeparateProcess
	 * @return void
	 */
	public function test_init(): void {
		Functions\expect( 'is_network_admin' )->once()->andReturn( true );

		Feature::init();
	}

	/**
	 * Test the add_thickbox method in the right page.
	 *
	 * @return void
	 */
	public function test_add_thickbox_right_page(): void {
		Functions\expect( 'get_current_screen' )->once()->andReturn( (object) array( 'id' => 'plugins-network' ) );
		Functions\expect( 'add_thickbox' )->once();

		Feature::add_thickbox();
	}

	/**
	 * Test the add_thickbox method in the wrong page.
	 *
	 * @return void
	 */
	public function test_add_thickbox_wrong_page(): void {
		$wp_screen = (object) array( 'id' => 'plugins' );

		Functions\expect( 'get_current_screen' )->once()->andReturn( $wp_screen );
		Functions\expect( 'add_thickbox' )->never();

		Feature::add_thickbox();
	}

	/**
	 * Test the maybe_show_notice method in other admin pages than the network.
	 *
	 * @return void
	 */
	public function test_maybe_show_notice_no_network(): void {
		Functions\expect( 'is_network_admin' )->once()->andReturn( false );

		Feature::maybe_show_notice();

		$this->expectOutputString( '' );
	}

	/**
	 * Test the maybe_show_notice method in the network admin page.
	 *
	 * @return void
	 */
	public function test_maybe_show_notice_network(): void {
		Functions\expect( 'is_network_admin' )->once()->andReturn( true );
		Functions\expect( 'sanitize_key' )->once()->andReturnFirstArg();
		Functions\expect( 'wp_unslash' )->once()->andReturnFirstArg();

		Feature::maybe_show_notice();

		$this->expectOutputString( '' );
	}

	/**
	 * Test the bulk_deactivate method with a user that is not a network admin.
	 *
	 * @return void
	 */
	public function test_bulk_deactivate_no_network_admin(): void {
		Functions\expect( 'current_user_can' )->once()->with( 'manage_network_plugins' )->andReturn( false );

		Feature::bulk_deactivate();
	}

	/**
	 * Test the add_action_link method with an empty active_plugins array.
	 *
	 * @return void
	 */
	public function test_add_action_link_no_active_plugins(): void {
		$obj         = new Feature();
		$links       = array(
			'deactivate' => '<a href="https://example.com/wp-admin/plugins.php?action=deactivate&amp;plugin=plugin/plugin.php" class="edit">Deactivate</a>',
		);
		$plugin_file = 'plugin/plugin.php';

		$this->assertSame( $links, $obj->add_action_link( $links, $plugin_file ) );
	}

	/**
	 * Data provider for add_action_link.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public static function provide_data_for_add_action_link(): array {
		return array(
			'with active plugins one link'  => array(
				'sites'          => array( 1 ),
				'active_plugins' => array( 'plugin/plugin.php' ),
				'plugin_data'    => array( 'Name' => 'Plugin' ),
				'given_links'    => array(
					'delete' => '<a href="#delete-link" class="edit">Delete</a>',
				),
				'expected_links' => array(
					'delete'          => '<a href="#delete-link" class="edit">Delete</a>',
					'site_deactivate' => '<a class="thickbox" title="&quot;Plugin&quot; is active in 1 site" style="display: inline-block" href="#TB_inline?width=600&height=550&inlineId=395f8f9a42dd48eb350e968ed5a81a0a">Sites deactivate</a>',
				),
			),
			'with active plugins two links' => array(
				'sites'          => array( 1 ),
				'active_plugins' => array( 'plugin/plugin.php' ),
				'plugin_data'    => array( 'Name' => 'Plugin' ),
				'given_links'    => array(
					'activate' => '<a href="#activate-link" class="edit">Activate</a>',
					'delete'   => '<a href="#delete-link" class="edit">Delete</a>',
				),
				'expected_links' => array(
					'activate'        => '<a href="#activate-link" class="edit">Activate</a>',
					'site_deactivate' => '<a class="thickbox" title="&quot;Plugin&quot; is active in 1 site" style="display: inline-block" href="#TB_inline?width=600&height=550&inlineId=395f8f9a42dd48eb350e968ed5a81a0a">Sites deactivate</a>',
					'delete'          => '<a href="#delete-link" class="edit">Delete</a>',
				),
			),
		);
	}

	/**
	 * Test the add_action_link method with an active_plugins array.
	 *
	 * @dataProvider provide_data_for_add_action_link
	 *
	 * @param int[]                 $sites          The sites.
	 * @param string[]              $active_plugins The active plugins.
	 * @param array<string, string> $plugin_data     The plugin data.
	 * @param array<string, string> $given_links    The given links.
	 * @param array<string, string> $expected_links The expected links.
	 *
	 * @return void
	 */
	public function test_add_action_link_with_active_plugins( array $sites, array $active_plugins, array $plugin_data, array $given_links, array $expected_links ): void {
		Functions\expect( 'get_sites' )->once()->andReturn( $sites );
		Functions\expect( 'get_blog_option' )->once()->with( $sites[0], 'active_plugins', array() )->andReturn( $active_plugins );
		Functions\expect( 'is_plugin_active_for_network' )->once()->andReturn( false );
		Functions\expect( 'get_plugin_data' )->once()->andReturn( $plugin_data );

		$obj = new Feature();
		$obj->populate_active_plugins();

		$this->assertSame( $expected_links, $obj->add_action_link( $given_links, $active_plugins[0] ) );
	}

	/**
	 * Test the print_row_styles method with an empty active_plugins array.
	 *
	 * @return void
	 */
	public function test_print_row_styles_empty(): void {
		( new Feature() )->print_row_styles();

		$this->expectOutputString( '' );
	}

	/**
	 * Test the print_row_styles method.
	 *
	 * @return void
	 */
	public function test_print_row_styles(): void {
		Functions\expect( 'get_sites' )->once()->andReturn( array( 1 ) );
		Functions\expect( 'get_blog_option' )->once()->with( 1, 'active_plugins', array() )->andReturn( array( 'plugin/plugin.php' ) );
		Functions\expect( 'is_plugin_active_for_network' )->once()->andReturn( false );

		$obj = new Feature();
		$obj->populate_active_plugins();
		$obj->print_row_styles();

		$this->expectOutputString( '<style>tr[data-plugin="plugin/plugin.php"]{ background-color: #f6f7f7 !important; }</style>' );
	}

	/**
	 * Test the print_thickbox_content method on another screen than plugins-network.
	 */
	public function test_print_thickbox_content_not_plugins_network(): void {
		Functions\expect( 'get_current_screen' )->once()->andReturn( (object) array( 'id' => 'plugins' ) );

		$obj = new Feature();
		$obj->print_thickbox_content();

		$this->expectOutputString( '' );
	}

	/**
	 * Test the print_thickbox_content method with a populated active_plugins array.
	 *
	 * @return void
	 */
	public function test_print_thickbox_content_populated(): void {
		Functions\expect( 'get_current_screen' )->once()->andReturn( (object) array( 'id' => 'plugins-network' ) );

		$obj = new Feature();
		$obj->print_thickbox_content();
	}

	/**
	 * Test the print_thickbox_content method with an empty active_plugins array.
	 *
	 * @return void
	 */
	public function test_print_thickbox_content(): void {
		Functions\expect( 'get_current_screen' )->once()->andReturn( (object) array( 'id' => 'plugins-network' ) );
		Functions\expect( 'get_sites' )->once()->andReturn( array( 1 ) );
		Functions\expect( 'get_blog_option' )->once()->with( 1, 'active_plugins', array() )->andReturn( array( 'plugin/plugin.php' ) );
		Functions\expect( 'is_plugin_active_for_network' )->once()->andReturn( false );
		Functions\expect( 'add_query_arg' )->once()->andReturn( '#some-abitrary-url' );
		Functions\expect( 'wp_nonce_field' )->once();
		Functions\expect( 'get_blog_details' )->once()->andReturn(
			(object) array(
				'blog_name' => 'ABC',
				'siteurl'   => '/abc',
			)
		);
		Functions\expect( 'get_admin_url' )->once()->with( 1, 'plugins.php' )->andReturn( '/abc/wp-admin/plugins.php' );
		Functions\expect( 'submit_button' )->once()->andReturn( '<button type="submit" class="button button-primary">Deactivate on selected sites</button>' );

		$obj = new Feature();
		$obj->populate_active_plugins();
		$obj->print_thickbox_content();

		$this->expectOutputString( '<div id="395f8f9a42dd48eb350e968ed5a81a0a" style="display:none"><p>Select the sites where you want to deactivate this plugin. Clicking on a site name will open the plugin screen for that site.</p><form method="post" action="http://#some-abitrary-url"><input type="hidden" name="action" value="bulk_deactivate" /><input type="hidden" name="plugin_file" value="plugin/plugin.php" /><ul><li><label><input type="checkbox" name="site_ids[]" value="1" /> <a href="http:///abc/wp-admin/plugins.php" target="_blank" rel="noopener noreferrer">/abc</a></label></li></ul></form></div>' );
	}
}
