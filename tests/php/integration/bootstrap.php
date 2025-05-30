<?php
/**
 * Bootstrap Integration tests
 *
 * @package multisyde-integration-tests
 */

define( 'TESTS_PLUGIN_DIR', dirname( __DIR__, 3 ) );
define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', TESTS_PLUGIN_DIR . '/vendor/yoast/phpunit-polyfills' );

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

require_once $_tests_dir . '/includes/functions.php';

tests_add_filter(
	'setup_theme',
	function () {
		require_once TESTS_PLUGIN_DIR . '/multisyde.php';
	}
);

require_once $_tests_dir . '/includes/bootstrap.php';
