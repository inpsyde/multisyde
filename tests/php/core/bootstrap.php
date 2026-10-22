<?php
/**
 * Bootstrap for tests ported from WordPress core, using the WordPress test library shipped with wp-env (Multisite).
 *
 * @package multisyde-core-tests
 */

$multisyde_tests_dir = getenv( 'WP_TESTS_DIR' ) ? getenv( 'WP_TESTS_DIR' ) : '/wordpress-phpunit';

define( 'WP_TESTS_CONFIG_FILE_PATH', __DIR__ . '/wp-tests-config.php' );
define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', dirname( __DIR__, 3 ) . '/vendor/yoast/phpunit-polyfills' );

require_once $multisyde_tests_dir . '/includes/functions.php';

tests_add_filter(
	'muplugins_loaded',
	static function () {
		require dirname( __DIR__, 3 ) . '/multisyde.php';
	}
);

require $multisyde_tests_dir . '/includes/bootstrap.php';
