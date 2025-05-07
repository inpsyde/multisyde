<?php
/**
 * Bootstrap Integration tests
 *
 * @package multisite-improvements-integration-tests
 */

// Force Multisite mode before loading WP.
define( 'WP_ALLOW_MULTISITE', true );
define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', false );
define( 'DOMAIN_CURRENT_SITE', 'localhost' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );

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

require $_tests_dir . '/includes/bootstrap.php';
