<?php
/**
 * Loadable classes should implement this interface.
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\Multisyde;

/**
 * Interface LoadableFeature
 */
interface LoadableFeature {


	/**
	 * Hook into WordPress.
	 *
	 * @return void
	 */
	public static function init(): void;
}
