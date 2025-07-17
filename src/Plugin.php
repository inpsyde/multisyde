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
}
