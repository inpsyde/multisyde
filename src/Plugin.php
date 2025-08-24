<?php
/**
 * Class loads plugin functionality.
 *
 * @package multisyde
 */

declare( strict_types=1 );

namespace Syde\MultiSyde;

/**
 * Class Loader
 */
class Plugin {

	/**
	 * Hook into WordPress.
	 *
	 * @return void
	 */
	public static function init(): void {
		add_action( 'init', array( __CLASS__, 'load' ) );
	}

	/**
	 * Load the multisyde improvements.
	 *
	 * @return void
	 */
	public static function load(): void {
		if ( ! function_exists( 'is_multisite' ) ) {
			return;
		}

		Presenter::init( Modules::init()->load() );
	}

	/**
	 * Get the plugin's directory path.
	 *
	 * @param string $asset Optional asset path to append to the plugin directory path.
	 *
	 * @return string
	 */
	public static function plugin_dir_path( string $asset = '' ): string {
		return plugin_dir_path( self::plugin_file() ) . $asset;
	}

	/**
	 * Get the plugin's directory URL.
	 *
	 * @param string $asset Optional asset path to append to the plugin directory URL.
	 *
	 * @return string
	 */
	public static function plugin_dir_url( string $asset = '' ): string {
		return plugin_dir_url( self::plugin_file() ) . $asset;
	}

	/**
	 * Absolute path to the main plugin file.
	 *
	 * @return string
	 */
	public static function plugin_file(): string {
		return dirname( __DIR__ ) . '/multisyde.php';
	}
}
