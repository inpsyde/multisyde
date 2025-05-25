<?php
/**
 * Interface ShareableInformation
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\Multisyde;

/**
 * This interface is used to define a contract for classes that provide feature information.
 */
interface ShareableInformation {

	/**
	 * Get the feature information.
	 *
	 * @return Summary
	 */
	public static function get(): Summary;
}
