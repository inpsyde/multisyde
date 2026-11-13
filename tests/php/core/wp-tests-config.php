<?php
/**
 * WordPress test library config for the tests ported from core.
 *
 * The wp-env generated config defines MULTISITE upfront and shares the tables of the tests site,
 * both break the test library installer. This config uses wp-env's database with its own prefix.
 *
 * @package multisyde-core-tests
 */

define( 'ABSPATH', '/var/www/html/' );

define( 'DB_NAME', getenv( 'WORDPRESS_DB_NAME' ) );
define( 'DB_USER', getenv( 'WORDPRESS_DB_USER' ) );
define( 'DB_PASSWORD', getenv( 'WORDPRESS_DB_PASSWORD' ) );
define( 'DB_HOST', getenv( 'WORDPRESS_DB_HOST' ) );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

$table_prefix = 'multisyde_tests_'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

define( 'WP_TESTS_DOMAIN', 'example.org' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Test Blog' );
define( 'WP_PHP_BINARY', 'php' );
define( 'WPLANG', '' );
define( 'WP_DEBUG', true );
