<?php
/**
 * Information class for the SiteActivePlugins feature.
 *
 * @package multisyde
 */

declare(strict_types=1);

namespace Syde\MultiSyde\Modules\SiteActiveTheme;

use Syde\MultiSyde\Summary;
use Syde\MultiSyde\ShareableInformation;

/**
 * Provides information about the SiteActiveThemes feature.
 */
class About implements ShareableInformation {

	/**
	 * Get the feature information.
	 *
	 * @return Summary
	 */
	public static function get(): Summary {
		return new Summary(
			__( 'Site Active Theme', 'multisyde' ),
			__( 'Displays the active theme (and its version) for each site in the Network Admin > Sites dashboard. This makes it easy for network administrators to quickly audit which themes are used across the network.', 'multisyde' ),
			array(
				'https://core.trac.wordpress.org/ticket/56458',
			)
		);
	}
}
