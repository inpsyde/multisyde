<?php
/**
 * Information class for the SiteActivePlugins feature.
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\MultiSyde\Modules\SiteActivePlugins;

use Syde\MultiSyde\Summary;
use Syde\MultiSyde\ShareableInformation;

/**
 * Provides information about the SiteActivePlugins feature.
 */
class About implements ShareableInformation {

	/**
	 * Get the feature information.
	 *
	 * @return Summary
	 */
	public static function get(): Summary {
		return new Summary(
			__( 'Site Active Plugins', 'multisyde' ),
			__( 'Displays which plugins are active on each site in the network. Adds a “Sites deactivate” link to the Network Admin Plugins page with a modal that lists subsites using the plugin. Supports selective bulk deactivation across subsites.', 'multisyde' ),
			array(
				'https://core.trac.wordpress.org/ticket/53255',
			)
		);
	}
}
