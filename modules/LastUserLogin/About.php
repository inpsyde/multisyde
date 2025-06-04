<?php
/**
 * Information class for the LastUserLogin feature.
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\Multisyde\Modules\LastUserLogin;

use Syde\Multisyde\Summary;
use Syde\Multisyde\ShareableInformation;

/**
 * Provides information about the LastUserLogin feature.
 */
class About implements ShareableInformation {

	/**
	 * Get the feature information.
	 *
	 * @return Summary
	 */
	public static function get(): Summary {
		return new Summary(
			__( 'Last User Login', 'multisyde' ),
			__( 'This module enhances the Network Admin Users screen in WordPress Multisite by adding a “Last Login” column. It automatically records the timestamp each time a user logs in and displays it in a readable, timezone-aware format.', 'multisyde' ),
			array(
				'https://github.com/inpsyde/multisyde/issues/11',
			)
		);
	}
}
