<?php
/**
 * Bootstrap Integration tests
 *
 * @package multisite-improvements-integration-tests
 */

if ( ! defined( 'MULTISITE' ) ) {
    define( 'MULTISITE', true );
}

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

require_once $_tests_dir . '/includes/functions.php';

/**
 * Manual load plugin
 *
 * @return void
 */
function _manually_load_plugin() {
	require dirname( __DIR__, 3 ) . '/index.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', dirname( __DIR__, 3 ) . '/vendor/yoast/phpunit-polyfills' );

require $_tests_dir . '/includes/bootstrap.php';
