<?php
/**
 * Bootstrap for integration tests using wp-env (Multisite).
 *
 * @package multisyde-integration-tests
 */

define( 'WP_USE_THEMES', false );

require '/var/www/html/wp-load.php';

// Directly load your plugin
require dirname( __DIR__, 3 ) . '/multisyde.php';