<?php
/**
 * Loadable classes should implement this interface.
 *
 * @package multisite-improvements
 */

declare(strict_types=1);

namespace Syde\MultisiteImprovements;

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
